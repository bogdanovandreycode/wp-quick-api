<?php

/**
 * @package Wp quick Api
 * @author Bogdanov Andrey (swarzone2100@yandex.ru)
 */

namespace quickapi\Admin;

use quickapi\Admin\SettingsPage;
use quickapi\DataBase;

class IntegrationsPage
{
    private mixed $projects;

    public function __construct()
    {
        add_action('init', function () {
            register_post_type(
                'qapi_integrations',
                [
                    'public' => true,
                    'label'  => 'Интеграции QickForm',
                    'menu_icon' => 'dashicons-category',
                    'supports' => array('title', 'editor', 'thumbnail', 'page-attributes'),
                    'show_in_menu' => 'edit.php?post_type=qapi_integrations',
                    'menu_position' => 6,
                ]
            );

            remove_post_type_support('qapi_integrations', 'editor');
        });

        add_action('admin_init', function () {
            add_meta_box(
                'integration_meta',
                'Настройки интеграции',
                array($this, 'showIntegrationMeta'),
                'qapi_integrations',
            );
        });

        add_action('admin_menu', function () {
            add_menu_page(
                'Интеграции QuickForm', // Название страницы в меню
                'Интеграции QuickForm', // Название пункта меню
                'manage_options', // Разрешения на просмотр страницы
                'edit.php?post_type=qapi_integrations', // Ссылка на страницу
                '', // Функция обработчик (в данном случае пустая)
                'dashicons-block-default', // Иконка
                5 // Позиция в меню
            );
        });

        add_action('do_meta_boxes', function () {
            remove_meta_box('pageparentdiv', 'qapi_integrations', 'side');
            remove_meta_box('us_page_settings', 'qapi_integrations', 'side');
            remove_meta_box('postimagediv', 'qapi_integrations', 'side');
            remove_meta_box('us_seo_settings', 'qapi_integrations', 'normal');
            remove_meta_box('us_portfolio_settings', 'qapi_integrations', 'normal');
        });

        add_action('save_post', function ($post_id, $post) {
            if (!current_user_can('edit_post', $post_id) || !isset($_POST['qapi_integration_meta_nonce']))
                return;

            if (!wp_verify_nonce($_POST['qapi_integration_meta_nonce'], 'qapi_integration_meta_nonce'))
                return;

            if (empty($_POST['qapi-project-integration-id']))
                return;

            update_post_meta($post_id, 'project-id', $_POST['qapi-project-integration-id']);
        }, 10, 2);

        new SettingsPage;
        $this->projects = DataBase::getProjects();
    }

    public function showIntegrationMeta($post)
    {
        require_once(__DIR__ . '/Templates/IntegrationsMeta.php');
    }
}
