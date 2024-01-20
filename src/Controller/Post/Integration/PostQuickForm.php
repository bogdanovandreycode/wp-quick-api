<?php

namespace quickapi\Controller\Post\Integration;

use WpToolKit\Controller\PostController;
use WpToolKit\Entity\Post;

class PostQuickForm extends PostController
{
    public Post $post;

    public function __construct()
    {

        $this->post = new Post(
            'qapi_integrations',
            'Интеграции QickForm',
            'dashicons-category',
            'manage_options',
            ['title', 'page-attributes'],
        );

        $this->post->position = 6;
        parent::__construct($this->post);
        $this->addToMenu();
    }
}
