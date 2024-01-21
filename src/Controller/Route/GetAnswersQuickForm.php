<?php

namespace quickapi\Controller\Route;

use WP_REST_Request;
use quickapi\DataBase;
use WpToolKit\Entity\MetaPoly;
use WpToolKit\Controller\RouteController;
use WpToolKit\Interface\RestRouteInterface;
use quickapi\Controller\RouteParam\SecretKey;
use quickapi\Controller\RouteParam\IntegrationId;
use quickapi\Controller\RouteParam\ProjectIdQuickForm;

class GetAnswersQuickForm extends RouteController implements RestRouteInterface
{
    public function __construct(
        private MetaPoly $secret
    ) {
        parent::__construct(
            'quickapi/v1',
            '/get-answers-quickform',
            [
                new ProjectIdQuickForm(),
                new IntegrationId(),
                new SecretKey($this->secret)
            ]
        );
    }

    public function callback(WP_REST_Request $request): mixed
    {
        $projectId = (int)$request->get_param('quickapi-form-id');
        $datePoint = (string)$request->get_param('quickapi-date-point');
        $lastId = (int)$request->get_param('quickapi-last-answer');
        $datePoint = empty($datePoint) ? date('yyyy-MM-dd HH:mm:ss') : $datePoint;
        $lastId = empty($lastId) ? '0' : $lastId;
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
