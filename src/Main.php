<?php

/**
 * @package Wp quick Api
 * @author Bogdanov Andrey (swarzone2100@yandex.ru)
 */

namespace quickapi;

use quickapi\Admin\IntegrationsPage;
use quickapi\DataBase;
use WP_REST_Request;

class Main
{
    public function __construct()
    {
        DataBase::init();
        new IntegrationsPage;
        $this->apiInit();
        $this->scriptAdd();
        $this->Shortcode();
    }

    private function scriptAdd()
    {
        wp_enqueue_style('qapi-front', plugins_url('/' . QAPI_PLUGIN_NAME  . '/Source/Assets/Style/Style.css'));

        add_action('wp_enqueue_scripts', function () {
            $scripts = [
                'qapi-main' => 'Main.js',
            ];

            foreach ($scripts as $label => $path) {
                wp_enqueue_script(
                    $label,
                    plugins_url('/' . QAPI_PLUGIN_NAME  . '/Source/Assets/Js/' . $path),
                    [],
                    '1.0.0'
                );
            }
        });
    }

    private function Shortcode()
    {
        add_shortcode('quickapi-shortcode', function ($atts, $content) {
            $atts = shortcode_atts([
                'project_id' => '',
            ], $atts);

            // if( !empty( $atts[ 'url' ] ) )
            //     return '<script>document.location.href = \'' . $atts[ 'url' ] . '\'</script>';
            return;
            // ob_start();
            // include  __PLUGIN_QUICK_API_DIR__ . '/Source/Template/AuthorMenu.php';
            // return ob_get_clean();
        });
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
                        $cycles_field = (string)$request->get_param('quickapi-field-cycles');
                        $cycles = (string)$request->get_param('quickapi-cycles');

                        if (empty($secret_key) || empty($project_id) || empty($integration_id))
                            return $this->getApiAnswer(-99, 'Too few arguments for this argument.', '');

                        $project = DataBase::getProject($project_id);

                        if (empty($project))
                            return $this->getApiAnswer(-98, 'Project is not exist.', '');

                        $correct_secret_key = get_option('quickapi_secret_key');

                        if ($correct_secret_key !== $secret_key)
                            return $this->getApiAnswer(-97, 'Ivalid secret key.', '');

                        $cycles_arrow = json_decode($cycles);

                        $forms = DataBase::getForms($project_id);

                        foreach ($forms as $form) {
                            $fields = json_decode($form['fields']);

                            foreach ($fields as $field) {
                                if ($field->label == 'Цикл') {
                                    $field->options = array();

                                    array_push($field->options, ['label' => 'Выберите']);

                                    foreach ($cycles_arrow as $cycle)
                                        array_push($field->options, ['label' => $cycle]);

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
