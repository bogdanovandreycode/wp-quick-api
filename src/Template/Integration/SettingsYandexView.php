<div class="quickapi-wrapper-admin">
    <h1>Данные для интеграции в яндекс форме</h1>
    <div class="quickapi-group-box">
        <div class="quickapi-field">
            <div class="quickapi-text--label">Api ссылка</div>
            <div class="quickapi-text--copy"><?= site_url() . '/wp-json/quickapi/v1/send-answers-yandex' ?></div>
        </div>

        <div class="quickapi-field">
            <div class="quickapi-text--label">Необходимый заголовок</div>
            <div class="quickapi-text--copy">
                <p>Нужно разместить в раздел <b>Заголовки</b></p>
                <pre><b>Content-Type:</b> <b>application/x-www-form-urlencoded</b></pre>
            </div>
        </div>

        <div class="quickapi-field">
            <div class="quickapi-text--label">Формат тела запроса</div>
            <div class="quickapi-text--copy">
                <p>Данные должны быть без переходов на новую строку и лишних пробелов</p>
                <pre><b>key=value&key=value&key=value&etc=etc</b></pre>
            </div>
        </div>
    </div>
    <br>
    <h1>Настройки</h1>
    <div class="quickapi-group-box">
        <div class="quickapi-field">
            <div class="quickapi-text--label"><b>ID интеграции</b> (поле в запросе: <b>quickapi-integration-id</b>)</div>
            <div class="quickapi-text--copy"><?= $post->ID; ?></div>
        </div>

        <div class="quickapi-field">
            <div class="quickapi-text--label"><b>Секретный ключ</b> (поле в запросе: <b>quickapi-secret</b>)</div>
            <div class="quickapi-text--copy"><?= (empty($secret) ? '<red>Сгенерируйте ключ в настройках</red>' : $secret) ?></div>
        </div>

        <div class="quickapi-field">
            <div class="quickapi-text--label"><b>ID яндекс формы интеграции</b> ( поле в запросе: <b>quickapi-form-id-yandex</b>)</div>
            <div class="quickapi-text--copy"><?= (!empty($currentProjectId->value) ? $currentProjectId->value : 'Форма не введена'); ?></div>
        </div>
    </div>

    <div class="quickapi-group-box">
        <?= wp_nonce_field($nonce, $nonce); ?>
        <div class="quickapi-field">
            <?= $projectIdField->renderLabel(); ?>
            <?= $projectIdField->renderField(); ?>
        </div>
    </div>
</div>