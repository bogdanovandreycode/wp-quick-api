<?php

namespace quickapi;

use quickapi\Table\YandexAnswer;

final class Boot
{
    private string $sourceFilePath = WP_PLUGIN_DIR . '/wp-quick-api/src/Injection/json_for_api.php';
    private string $destinationFilePath = WP_PLUGIN_DIR . '/quickform/site/classes/email/json_for_api.php';

    public function __construct(
        private string $mainFilePath,
    ) {
        register_activation_hook($mainFilePath, [$this, 'quickApiActivation']);
        register_deactivation_hook($mainFilePath, [$this, 'quickApiDeactivation']);
    }

    public function quickApiActivation()
    {
        YandexAnswer::createTable();

        if (!file_exists($this->destinationFilePath)) {
            copy($this->sourceFilePath, $this->destinationFilePath);
        }
    }

    public function quickApiDeactivation()
    {
        YandexAnswer::deleteTable();

        if (file_exists($this->destinationFilePath)) {
            unlink($this->destinationFilePath);
        }
    }
}
