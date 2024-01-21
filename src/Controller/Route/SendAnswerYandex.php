<?php

namespace quickapi\Controller\Route;

use quickapi\Controller\RouteParam\ProjectIdYandex;
use WP_REST_Request;
use WpToolKit\Entity\MetaPoly;
use WpToolKit\Controller\RouteController;
use WpToolKit\Interface\RestRouteInterface;
use quickapi\Controller\RouteParam\SecretKey;
use quickapi\Controller\RouteParam\IntegrationId;

class SendAnswersYandex extends RouteController implements RestRouteInterface
{
    public function __construct(
        private MetaPoly $secret
    ) {
        $integrationId = new IntegrationId();
        $secretKey = new SecretKey($this->secret);
        $projectId = new ProjectIdYandex();

        parent::__construct(
            'quickapi/v1',
            '/get-answers-yandex',
            [
                array_merge(
                    $integrationId->getArray(),
                    $secretKey->getArray(),
                    $projectId->getArray()
                )
            ]
        );
    }

    public function callback(WP_REST_Request $request): mixed
    {

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
