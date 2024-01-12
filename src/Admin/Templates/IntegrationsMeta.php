<?php
    $secret = get_option('quickapi_secret_key');
    $selected_project = get_post_meta( $post->ID, 'project-id', true );
?>
<div class="quickapi-wrapper-admin">
    <h1>Настройки</h1>
    <div class="quickapi-group-box">
        <div class="quickapi-field">
            <div class="quickapi-text--label">ID интеграции</div>
            <div class="quickapi-text--copy"><?= $post->ID; ?></div>
        </div>

        <div class="quickapi-field">
            <div class="quickapi-text--label">Секретный ключ</div>
            <div class="quickapi-text--copy"><?= ( empty( $secret ) ? '<red>Сгенерируйте ключ в настройках</red>' : $secret ) ?></div>
        </div>

        <div class="quickapi-field">
            <div class="quickapi-text--label">ID формы интеграции</div>
            <div class="quickapi-text--copy"><?= ( !empty( $selected_project ) ?  $selected_project : 'Форма не выбрана' ); ?></div>
        </div>
    </div>

    <div class="quickapi-group-box">
      <?php wp_nonce_field( 'qapi_integration_meta_nonce', 'qapi_integration_meta_nonce' ); ?>
      <div class="quickapi-field">
        <label class="quickapi-label" for="qapi-project-integration-id">Форма интеграции</label>
        <select class="quickapi-select" name="qapi-project-integration-id">
          <?php foreach( $this->projects as $project ): ?>
              <option value="<?= $project['id']; ?>" <?= ( $selected_project == $project['id'] ? 'selected' : '' ); ?> ><?= $project['title']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
</div>
