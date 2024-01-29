<?php

namespace quickapi\Controller\RouteParam;

use WP_Error;
use quickapi\Table\YandexAnswer;
use WpToolKit\Controller\ParamRoute;
use WpToolKit\Interface\ParamRoureInterface;

class ProjectIdYandex extends ParamRoute implements ParamRoureInterface
{
    public function __construct()
    {
        parent::__construct('quickapi-form-id-yandex', null, true);
    }

    public function validate($param, $request, $key): bool|WP_Error
    {
        if (empty($param)) {
            return new WP_Error('err', 'Project id is empty.');
        }

        return true;
    }

    public function sanitize($param, $request, $key): mixed
    {
        return (string)$param;
    }
}
