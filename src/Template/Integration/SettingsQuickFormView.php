<div class="quickapi-wrapper-admin">
    <h1>Настройки</h1>
    <div class="quickapi-group-box">
        <div class="quickapi-field">
            <div class="quickapi-text--label">ID интеграции</div>
            <div class="quickapi-text--copy"><?= $post->ID; ?></div>
        </div>

        <div class="quickapi-field">
            <div class="quickapi-text--label">Секретный ключ</div>
            <div class="quickapi-text--copy"><?= (empty($secret) ? '<red>Сгенерируйте ключ в настройках</red>' : $secret) ?></div>
        </div>

        <div class="quickapi-field">
            <div class="quickapi-text--label">ID формы интеграции</div>
            <div class="quickapi-text--copy"><?= (!empty($currentProjectId) ? $currentProjectId : 'Форма не выбрана'); ?></div>
        </div>
    </div>

    <div class="quickapi-group-box">
        <?= wp_nonce_field($nonce, $nonce); ?>
        <div class="quickapi-field">
            <label class="quickapi-label" for="project-integration-id">Форма интеграции</label>
            <select class="quickapi-select" name="project-integration-id">
                <?php foreach ($projects as $project) : ?>
                    <option value="<?= $project['id']; ?>" <?= ($currentProjectId == $project['id'] ? 'selected' : ''); ?>>
                        <?= $project['title']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>