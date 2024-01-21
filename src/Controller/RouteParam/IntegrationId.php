<?php

namespace quickapi\Controller\RouteParam;

use WP_Error;
use WpToolKit\Controller\ParamRoute;
use WpToolKit\Interface\ParamRoureInterface;

class IntegrationId extends ParamRoute implements ParamRoureInterface
{
    public function __construct()
    {
        parent::__construct('quickapi-integration-id', null, true);
    }

    public function validate($param, $request, $key): bool|WP_Error
    {
        return empty($param) ? new WP_Error('err', 'Integration id is empty.') : true;
    }

    public function sanitize($param, $request, $key): mixed
    {
        return (int)$param;
    }
}
