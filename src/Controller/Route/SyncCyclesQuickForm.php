<?php

namespace quickapi\Controller\Route;

use WP_REST_Request;
use quickapi\DataBase;
use WpToolKit\Controller\RouteController;
use WpToolKit\Interface\RestRouteInterface;

class SyncCyclesQuickForm extends RouteController implements RestRouteInterface
{
    public function __construct()
    {
        parent::__construct(
            'quickapi/v1',
            '/sync-cycles',
            []
        );
    }

    public function callback(WP_REST_Request $request): mixed
    {
        $secret_key = (string)$request->get_param('quickapi-secret');
        $project_id = (int)$request->get_param('quickapi-form-id');
        $integration_id = (int)$request->get_param('quickapi-integration-id');
        $cycles = (string)$request->get_param('quickapi-cycles');

        if (empty($secret_key) || empty($project_id) || empty($integration_id)) {
            return $this->getResponce('Too few arguments for this argument.');
        }

        $project = DataBase::getProject($project_id);

        if (empty($project)) {
            return $this->getResponce('Project is not exist.');
        }

        $correct_secret_key = get_option('quickapi_secret_key');

        if ($correct_secret_key !== $secret_key) {
            return $this->getResponce('Ivalid secret key.');
        }

        $cycles_arrow = json_decode($cycles);

        $forms = DataBase::getForms($project_id);

        foreach ($forms as $form) {
            $fields = json_decode($form['fields']);

            foreach ($fields as $field) {
                if ($field->label != 'Цикл') {
                    continue;
                }

                $field->options = [];
                $field->options[] = ['label' => 'Выберите'];

                foreach ($cycles_arrow as $cycle) {
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
