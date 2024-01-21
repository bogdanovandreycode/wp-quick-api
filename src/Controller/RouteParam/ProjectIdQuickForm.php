<?php

namespace quickapi\Controller\RouteParam;

use WP_Error;
use quickapi\DataBase;
use WpToolKit\Controller\ParamRoute;
use WpToolKit\Interface\ParamRoureInterface;

class ProjectIdQuickForm extends ParamRoute implements ParamRoureInterface
{
    public function __construct()
    {
        parent::__construct('quickapi-form-id', null, true);
    }

    public function validate($param, $request, $key): bool|WP_Error
    {
        $project = DataBase::getProject($param);

        if (empty($param)) {
            return new WP_Error('err', 'Project id is empty.');
        }

        if (empty($project)) {
            return new WP_Error('err', 'Project does not exists.');
        }

        return true;
    }

    public function sanitize($param, $request, $key): mixed
    {
        return (int)$param;
    }
}
