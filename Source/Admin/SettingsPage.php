<?php
/**
* @package Wp quick Api
* @author Bogdanov Andrey (swarzone2100@yandex.ru)
 */
namespace quickapi\Admin;

class SettingsPage
{
  public function __construct()
  {
      add_action('admin_menu', function()
      {
          add_submenu_page(
              'edit.php?post_type=qapi_integrations',
              'Общие настройки',
              'Общие настройки',
              'manage_options',
              'quickapi_settings',
              array( $this, 'quickApiSettingsCallback' )
          );
      });

      $this->saveSettings();
  }

  function quickApiSettingsCallback()
  {
      $secret = get_option('quickapi_secret_key');

      if( empty( $secret ) )
      {
          $secret = wp_generate_password( 16, false );
          update_option('quickapi_secret_key', $secret );
      }

      require_once( __DIR__ . '/Templates/SettingsView.php' );
  }

  private function saveSettings()
  {
      add_action( 'plugins_loaded', function()
      {
            if( isset( $_POST[ 'quickapi_settings_admin_nonce' ] ) && wp_verify_nonce( $_POST[ 'quickapi_settings_admin_nonce' ], 'quickapi_settings_admin_nonce' ) )
                update_option('quickapi_secret_key', $_POST['qapi-secret-key'] );
      });
  }
}
