<div class="quickapi-wrapper-admin">
  <h2>Общие настройки интеграций</h2>
  <form class="quickapi-form" enctype="application/x-www-form-urlencoded" method="post">
    <?= wp_nonce_field($nonce, $nonce); ?>

    <div class="quickapi-field">
      <label class="quickapi-label" for="qapi-secret-key">Секретный ключ</label>
      <input required class="quickapi-input" type="text" name="quickapi_secret_key" value="<?= $secret; ?>">
    </div>

    <input type="submit" class="quickapi-button--save" name="save-profile" value="Сохранить">
  </form>
</div>