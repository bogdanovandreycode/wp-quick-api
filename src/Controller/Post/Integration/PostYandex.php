<?php

namespace quickapi\Controller\Post\Integration;

use WpToolKit\Controller\PostController;
use WpToolKit\Entity\Post;

class PostYandex extends PostController
{
    public Post $post;

    public function __construct(
        public Post $parentPost
    ) {
        $this->post = new Post(
            'yandex_integrations',
            'Интеграции Yandex',
            'dashicons-category',
            'manage_options',
            ['title', 'page-attributes'],
        );

        $this->post->position = 1;
        parent::__construct($this->post);
        $this->addToSubMenu($parentPost);
    }
}
