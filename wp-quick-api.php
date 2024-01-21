<?php

/**
 * Plugin Name: Wp Quick Api
 * Plugin URI: /wp-admin/admin.php?page=settings_quickapi
 * Description: Плагин добавляет возможность интеграции по эндпоинтам api ответов плагина Quick Form.
 * Version: 2.0.0
 * Author: Bogdanov Andrey
 * Author URI: mailto://swarzone2100@yandex.ru
 *
 * @package Wp Quick Api
 * @author Bogdanov Andrey (swarzone2100@yandex.ru)
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once(ABSPATH . 'wp-admin/includes/plugin.php');

use quickapi\Boot;
use quickapi\Main;

new Boot(__FILE__);

add_action('plugins_loaded', function () {
    if (!is_plugin_active('quickform/quickform.php')) {
        trigger_error("Для работы этого плагина необходимо установить и активировать плагин QuickForm", E_USER_ERROR);
    }

    new Main();
});
