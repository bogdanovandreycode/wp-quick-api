<?php

/**
 * @package Wp Quick Api
 * @author Bogdanov Andrey (swarzone2100@yandex.ru)
 */

namespace quickapi;

use quickapi\Controller\Route\GetAnswers;
use quickapi\Controller\Route\SyncCyclesQuickForm;
use WP_REST_Request;
use quickapi\DataBase;
use WpToolKit\Entity\View;
use WpToolKit\Entity\MetaPoly;
use WpToolKit\Entity\ScriptType;
use WpToolKit\Entity\MetaPolyType;
use WpToolKit\Controller\ViewLoader;
use WpToolKit\Factory\ServiceFactory;
use quickapi\Controller\Page\Settings;
use WpToolKit\Controller\ScriptController;
use quickapi\Controller\Post\Integration\PostQuickForm;
use quickapi\Controller\MetaBox\Integration\SettingsQuickForm;

class Main
{
    private ViewLoader $views;
    private ScriptController $scripts;

    public function __construct()
    {
        DataBase::init();
        $this->views = new ViewLoader();
        $this->scripts = ServiceFactory::getService('ScriptController');

        $this->addScript();
        $this->createRestRoute();
        $this->createView();
        $this->createStructure();
    }

    private function createStructure(): void
    {
        $integrationQf = new PostQuickForm();
        $secret = new MetaPoly('quickapi_secret_key', MetaPolyType::STRING);
        new SettingsQuickForm($this->views, $integrationQf->post, $secret);
        new Settings($this->views, $integrationQf->post, $secret);
    }

    private function createView()
    {
        $basePathTemplate = WP_PLUGIN_DIR . '/wp-quick-api/src/Template';

        $this->views->add(
            new View(
                'settings_quick_form',
                $basePathTemplate . '/Integration/SettingsQuickFormView.php',
                []
            )
        );

        $this->views->add(
            new View(
                'settings',
                $basePathTemplate . '/SettingsView.php',
                []
            )
        );
    }

    private function addScript()
    {
        $this->scripts->addStyle(
            'wp-quick-api-style',
            '/wp-quick-api/assets/style/Style.css',
            ScriptType::ADMIN
        );
    }

    private function createRestRoute()
    {
        new GetAnswers();
        new SyncCyclesQuickForm();
    }
}
