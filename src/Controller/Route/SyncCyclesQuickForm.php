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

class SyncCyclesQuickForm extends RouteController implements RestRouteInterface
{
    public function __construct(
        private MetaPoly $secret
    ) {
        $projectId = new ProjectIdQuickForm();
        $integrationId = new IntegrationId();
        $secretKey = new SecretKey($this->secret);

        parent::__construct(
            'quickapi/v1',
            '/sync-cycles-quickform',
            [
                array_merge(
                    $projectId->getArray(),
                    $integrationId->getArray(),
                    $secretKey->getArray()
                )
            ]
        );
    }

    public function callback(WP_REST_Request $request): mixed
    {
        $projectId = (int)$request->get_param('quickapi-form-id');
        $cycles = (string)$request->get_param('quickapi-cycles');
        $cycles = json_decode($cycles);
        $forms = DataBase::getForms($projectId);

        foreach ($forms as $form) {
            $fields = json_decode($form['fields']);

            foreach ($fields as $field) {
                if ($field->label != 'Цикл') {
                    continue;
                }

                $field->options = ['label' => 'Выберите'];

                foreach ($cycles as $cycle) {
                    $field->options[] = ['label' => $cycle];
                }

                break;
            }

            DataBase::updateForm($form['id'], json_encode($fields));
        }

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
