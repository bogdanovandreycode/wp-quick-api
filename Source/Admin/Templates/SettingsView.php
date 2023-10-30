<div class="quickapi-wrapper-admin">
  <h2>Общие настройки интеграций</h2>
  <form class="quickapi-form" enctype="application/x-www-form-urlencoded" method="post">
      <?php echo wp_nonce_field('quickapi_settings_admin_nonce', 'quickapi_settings_admin_nonce'); ?>

      <div class="quickapi-field">
        <label class="quickapi-label" for="qapi-secret-key">Секретный ключ</label>
        <input required  class="quickapi-input" type="text" name="qapi-secret-key" value="<?= get_option('quickapi_secret_key');  ?>">
      </div>

      <input type="submit" class="quickapi-button--save" name="save-profile" value="Сохранить">
  </form>
</div>
