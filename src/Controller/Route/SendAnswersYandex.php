<?php

namespace quickapi\Controller\Route;

use quickapi\Controller\RouteParam\ProjectIdYandex;
use WP_REST_Request;
use WpToolKit\Entity\MetaPoly;
use WpToolKit\Controller\RouteController;
use WpToolKit\Interface\RestRouteInterface;
use quickapi\Controller\RouteParam\SecretKey;
use quickapi\Controller\RouteParam\IntegrationId;
use quickapi\Table\YandexAnswer;

class SendAnswersYandex extends RouteController implements RestRouteInterface
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
            '/send-answers-yandex',
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
        $fields = $request->get_params();
        unset($fields[$this->projectId->name], $fields[$this->integrationId->name], $fields[$this->secretKey->name]);
        $jsonFields = json_encode($fields, JSON_UNESCAPED_UNICODE);
        YandexAnswer::add($formId, $integrationId, $jsonFields);
        return $this->getResponce('Success.');
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
