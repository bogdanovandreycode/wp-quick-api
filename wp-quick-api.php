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

define( 'QAPI_DIR', __DIR__ );
define( 'QAPI_NAMESPACE', 'quickapi' );
define( 'QAPI_FOLDER', 'Source' );
define( 'QAPI_PLUGIN_NAME', 'wp-quick-api' );

add_action( 'plugins_loaded', function()
{
    if ( !is_plugin_active( 'quickform/quickform.php' ) )
        trigger_error( "Для работы этого плагина необходимо установить и активировать плагин QuickForm", E_USER_ERROR );
});

register_activation_hook( __FILE__, 'quick_api_activation' );
register_deactivation_hook( __FILE__, 'quick_api_deactivation' );

function quick_api_activation()
{
    $source_file = QAPI_DIR . '/Injection/json_for_api.php';
    $destination_file = WP_PLUGIN_DIR . '/quickform/site/classes/email/json_for_api.php';

    if ( ! file_exists( $destination_file ) )
        copy( $source_file, $destination_file );
}

function quick_api_deactivation()
{
    $destination_file = WP_PLUGIN_DIR . '/quickform/site/classes/email/json_for_api.php';

    if ( file_exists( $destination_file ) )
        unlink( $destination_file );
}

require_once QAPI_DIR . '/wp-quick-api-autoload.php';
use quickapi\Main;
new Main();
