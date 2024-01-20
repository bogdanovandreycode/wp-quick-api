<?php

namespace quickapi\Controller\Page;

use WpToolKit\Controller\AdminPage;
use WpToolKit\Controller\ViewLoader;
use WpToolKit\Entity\MetaPoly;
use WpToolKit\Entity\Post;
use WpToolKit\Interface\ContentHandlerInterface;

class Settings extends AdminPage implements ContentHandlerInterface
{
    private string $nonce = 'quickapi_settings_admin_nonce';

    public function __construct(
        private ViewLoader $views,
        private Post $parentPost,
        private MetaPoly $secret
    ) {
        parent::__construct(
            'Общие настройки',
            'Общие настройки',
            'manage_options',
            'quickapi_settings',
            15,
            true,
            $this->parentPost->getUrl(),
        );
    }

    public function render(): void
    {
        $this->secret->value = get_option($this->secret->name);

        if (empty($this->secret->value)) {
            $this->secret->value = wp_generate_password(16, false);
            update_option($this->secret->name, $this->secret->value);
        }

        $view = $this->views->getView('settings');
        $view->addVariable('secret', $this->secret->value);
        $view->addVariable('nonce', $this->nonce);
        $this->views->load($view->name);
    }

    public function callback(): void
    {
        if (isset($_POST[$this->nonce]) && wp_verify_nonce($_POST[$this->nonce], $this->nonce)) {
            update_option($this->secret->name, $_POST[$this->secret->name]);
        }
    }
}
