<?php

namespace quickapi\Controller\Route;

use WP_REST_Request;
use quickapi\DataBase;
use WpToolKit\Controller\RouteController;
use WpToolKit\Interface\RestRouteInterface;

class GetAnswers extends RouteController implements RestRouteInterface
{
    public function __construct()
    {
        parent::__construct(
            'quickapi/v1',
            '/get-answers',
            []
        );
    }

    public function callback(WP_REST_Request $request): mixed
    {
        $secretKey = (string)$request->get_param('quickapi-secret');
        $projectId = (int)$request->get_param('quickapi-form-id');
        $integrationId = (int)$request->get_param('quickapi-integration-id');
        $datePoint = (string)$request->get_param('quickapi-date-point');
        $lastId = (int)$request->get_param('quickapi-last-answer');

        if (empty($secretKey) || empty($projectId) || empty($integrationId)) {
            return $this->getResponce('Too few arguments for this argument.');
        }

        if (empty($datePoint)) {
            $datePoint = date('yyyy-MM-dd HH:mm:ss');
        }

        if (empty($lastId)) {
            $lastId = '0';
        }

        $project = DataBase::getProject($projectId);

        if (empty($project)) {
            return $this->getResponce('Project is not exist.');
        }

        $correct_secret_key = get_option('quickapi_secret_key');

        if ($correct_secret_key !== $secretKey) {
            return $this->getResponce('Ivalid secret key.');
        }

        $history = DataBase::getHistoryByDateAndLast($projectId, $datePoint, $lastId);
        $result  = [];

        foreach ($history as $answer) {
            $fields = json_decode($answer['st_form']);
            $row = [];

            foreach ($fields as $field) {
                $row[] = [
                    'name' => $field->name,
                    'value' => $field->value
                ];
            }

            $result[] = [
                'id' => $answer['id'],
                'date' => $answer['st_date'],
                'fields' => $row
            ];
        }

        return $this->getResponce('Success.', json_encode($result, JSON_UNESCAPED_UNICODE));
    }

    public function checkPermission(WP_REST_Request $request): bool
    {
        return true;
    }

    private function getResponce(string $message, mixed $result = null): array
    {
        return [
            'Code' => 0,
            'Message' => $message,
            'Result' => $result
        ];
    }
}
