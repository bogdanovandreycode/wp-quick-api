<?php

namespace quickapi\Controller\Route;

use WP_REST_Request;
use WpToolKit\Entity\MetaPoly;
use WpToolKit\Controller\RouteController;
use WpToolKit\Interface\RestRouteInterface;
use quickapi\Controller\RouteParam\SecretKey;
use quickapi\Controller\RouteParam\IntegrationId;
use quickapi\Controller\RouteParam\ProjectIdYandex;

class GetAnswersYandex extends RouteController implements RestRouteInterface
{
    public function __construct(
        private MetaPoly $secret
    ) {
        parent::__construct(
            'quickapi/v1',
            '/get-answers-yandex',
            [
                new ProjectIdYandex(),
                new IntegrationId(),
                new SecretKey($this->secret)
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
