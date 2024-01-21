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
        parent::__construct(
            'quickapi/v1',
            '/send-answers-yandex',
            [
                new ProjectIdYandex(),
                new IntegrationId(),
                new SecretKey($this->secret)
            ]
        );
    }

    public function callback(WP_REST_Request $request): mixed
    {
        file_put_contents('test.json', json_encode($request));
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
