<?php

namespace quickapi\Controller\Route;

use WP_REST_Request;
use WpToolKit\Entity\MetaPoly;
use WpToolKit\Controller\RouteController;
use WpToolKit\Interface\RestRouteInterface;
use quickapi\Controller\RouteParam\SecretKey;
use quickapi\Controller\RouteParam\IntegrationId;
use quickapi\Controller\RouteParam\ProjectIdYandex;
use quickapi\Table\YandexAnswer;

class GetAnswersYandex extends RouteController implements RestRouteInterface
{
    private ProjectIdYandex $projectId;
    private IntegrationId $integrationId;
    private SecretKey $secretKey;

    public function __construct(
        private MetaPoly $secret
    ) {
        $this->projectId = new ProjectIdYandex();
        $this->integrationId = new IntegrationId();
        $this->secretKey = new SecretKey($this->secret);

        parent::__construct(
            'quickapi/v1',
            '/get-answers-yandex',
            [
                $this->projectId,
                $this->integrationId,
                $this->secretKey
            ]
        );
    }

    public function callback(WP_REST_Request $request): mixed
    {
        $formId = $request->get_param($this->projectId->name);
        $integrationId = $request->get_param($this->integrationId->name);
        $datePoint = (string)$request->get_param('quickapi-date-point');
        $lastId = (int)$request->get_param('quickapi-last-answer');
        $datePoint = empty($datePoint) ? date('yyyy-MM-dd HH:mm:ss') : $datePoint;
        $lastId = empty($lastId) ? '0' : $lastId;
        $history = YandexAnswer::getHistoryByDateAndLast($integrationId, $formId, $datePoint, $lastId);
        $result = [];
        
        foreach ($history as $answer) {
            $fields = json_decode($answer['json_fields'], true);
            $row = [];

            foreach ($fields as $name => $value) {
                $row[] = [
                    'name' => $name,
                    'value' => $value
                ];
            }

            $result[] = [
                'id' => $answer['id'],
                'date' => $answer['create_date'],
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
