<?php

/**
 * Plugin Name: Wp Quick Api
 * Plugin URI: /wp-admin/admin.php?page=settings_quickapi
 * Description: Плагин добавляет возможность интеграции по эндпоинтам api ответов плагина Quick Form.
 * Version: 1.0.0
 * Author: Bogdanov Andrey
 * Author URI: mailto://swarzone2100@yandex.ru
 *
 * @package Wp quick Api
 * @author Bogdanov Andrey (swarzone2100@yandex.ru)
 */

require_once __DIR__ . '/vendor/autoload.php';

use quickapi\Main;

register_activation_hook(__FILE__, 'quick_api_activation');
register_deactivation_hook(__FILE__, 'quick_api_deactivation');

function quick_api_activation()
{
    $source_file = WP_PLUGIN_DIR . '/wp-quick-api/src/Injection/json_for_api.php';
    $destination_file = WP_PLUGIN_DIR . '/quickform/site/classes/email/json_for_api.php';

    if (!file_exists($destination_file)) {
        copy($source_file, $destination_file);
    }
}

function quick_api_deactivation()
{
    $destination_file = WP_PLUGIN_DIR . '/quickform/site/classes/email/json_for_api.php';

    if (file_exists($destination_file)) {
        unlink($destination_file);
    }
}

add_action('plugins_loaded', function () {
    if (!is_plugin_active('quickform/quickform.php')) {
        trigger_error("Для работы этого плагина необходимо установить и активировать плагин QuickForm", E_USER_ERROR);
    }

    new Main();
});
