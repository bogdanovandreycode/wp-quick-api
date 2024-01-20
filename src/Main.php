<?php

/**
 * @package Wp Quick Api
 * @author Bogdanov Andrey (swarzone2100@yandex.ru)
 */

namespace quickapi;

use WP_REST_Request;
use quickapi\DataBase;
use WpToolKit\Entity\View;
use WpToolKit\Entity\MetaPoly;
use WpToolKit\Entity\ScriptType;
use WpToolKit\Entity\MetaPolyType;
use WpToolKit\Controller\ViewLoader;
use WpToolKit\Factory\ServiceFactory;
use quickapi\Controller\Page\Settings;
use WpToolKit\Controller\ScriptController;
use quickapi\Controller\Post\Integration\PostQuickForm;
use quickapi\Controller\MetaBox\Integration\SettingsQuickForm;

class Main
{
    private ViewLoader $views;
    private ScriptController $scripts;

    public function __construct()
    {
        DataBase::init();
        $this->views = new ViewLoader();
        $this->scripts = ServiceFactory::getService('ScriptController');

        $this->addScript();
        $this->apiInit();
        $this->createView();
        $this->createStructure();
    }

    private function createStructure(): void
    {
        $integrationQf = new PostQuickForm();
        $secret = new MetaPoly('quickapi_secret_key', MetaPolyType::STRING);
        $integrationQfSettings = new SettingsQuickForm($this->views, $integrationQf->post, $secret);
        $settings = new Settings($this->views, $integrationQf->post, $secret);
    }

    private function createView()
    {
        $basePathTemplate = WP_PLUGIN_DIR . '/wp-quick-api/src/Template';

        $this->views->add(
            new View(
                'settings_quick_form',
                $basePathTemplate . '/Integration/SettingsQuickFormView.php',
                []
            )
        );

        $this->views->add(
            new View(
                'settings',
                $basePathTemplate . '/SettingsView.php',
                []
            )
        );
    }

    private function addScript()
    {
        $this->scripts->addStyle(
            'wp-quick-api-style',
            '/wp-quick-api/assets/style/Style.css',
            ScriptType::ADMIN
        );
    }

    private function getApiAnswer($code, $message, $result)
    {
        return [
            'Code' => $code,
            'Message' => $message,
            'Result' => $result
        ];
    }

    private function apiInit()
    {
        add_action('rest_api_init', function () {
            register_rest_route(
                'quickapi/v1',
                '/getanswers',
                [
                    'methods' => 'POST',
                    'callback' => function (WP_REST_Request $request) {
                        $secret_key = (string)$request->get_param('quickapi-secret');
                        $project_id = (int)$request->get_param('quickapi-form-id');
                        $integration_id = (int)$request->get_param('quickapi-integration-id');
                        $date_point = (int)$request->get_param('quickapi-date-point');
                        $last_id = (int)$request->get_param('quickapi-last-answer');

                        if (empty($secret_key) || empty($project_id) || empty($integration_id))
                            return $this->getApiAnswer(-99, 'Too few arguments for this argument.', '');

                        if (empty($date_point))
                            $date_point = date('yyyy-MM-dd HH:mm:ss');

                        if (empty($last_id))
                            $last_id = '0';

                        $project = DataBase::getProject($project_id);

                        if (empty($project))
                            return $this->getApiAnswer(-98, 'Project is not exist.', '');

                        $correct_secret_key = get_option('quickapi_secret_key');

                        if ($correct_secret_key !== $secret_key)
                            return $this->getApiAnswer(-97, 'Ivalid secret key.', '');

                        $history = DataBase::getHistoryByDateAndLast($project_id, $date_point, $last_id);
                        $result  = array();

                        foreach ($history as $answer) {
                            $fields = json_decode($answer['st_form']);
                            $row = array();

                            foreach ($fields as $field)
                                array_push($row, ['name' => $field->name, 'value' => $field->value]);

                            array_push($result, ['id' => $answer['id'], 'date' => $answer['st_date'], 'fields' => $row]);
                        }

                        return $this->getApiAnswer(0, 'Success.', json_encode($result, JSON_UNESCAPED_UNICODE));
                    }
                ]
            );

            register_rest_route(
                'quickapi/v1',
                '/syncfield',
                [
                    'methods' => 'POST',
                    'callback' => function (WP_REST_Request $request) {
                        $secret_key = (string)$request->get_param('quickapi-secret');
                        $project_id = (int)$request->get_param('quickapi-form-id');
                        $integration_id = (int)$request->get_param('quickapi-integration-id');
                        $cycles = (string)$request->get_param('quickapi-cycles');

                        if (empty($secret_key) || empty($project_id) || empty($integration_id)) {
                            return $this->getApiAnswer(-99, 'Too few arguments for this argument.', '');
                        }

                        $project = DataBase::getProject($project_id);

                        if (empty($project)) {
                            return $this->getApiAnswer(-98, 'Project is not exist.', '');
                        }

                        $correct_secret_key = get_option('quickapi_secret_key');

                        if ($correct_secret_key !== $secret_key) {
                            return $this->getApiAnswer(-97, 'Ivalid secret key.', '');
                        }

                        $cycles_arrow = json_decode($cycles);

                        $forms = DataBase::getForms($project_id);

                        foreach ($forms as $form) {
                            $fields = json_decode($form['fields']);

                            foreach ($fields as $field) {
                                if ($field->label == 'Цикл') {
                                    $field->options = array();

                                    array_push($field->options, ['label' => 'Выберите']);

                                    foreach ($cycles_arrow as $cycle) {
                                        array_push($field->options, ['label' => $cycle]);
                                    }

                                    break;
                                }
                            }

                            DataBase::updateForm($form['id'], json_encode($fields));
                        }

                        return $this->getApiAnswer(0, 'Success.', '');
                    }
                ]
            );
        });
    }
}
