<?php

namespace quickapi\Controller\RouteParam;

use WP_Error;
use WpToolKit\Entity\MetaPoly;
use WpToolKit\Controller\ParamRoute;
use WpToolKit\Interface\ParamRoureInterface;

class SecretKey extends ParamRoute implements ParamRoureInterface
{
    public function __construct(
        private MetaPoly $secret
    ) {
        parent::__construct('quickapi-secret', null, true);
    }

    public function validate($param, $request, $key): bool|WP_Error
    {
        $correctSecretKey = get_option($this->secret->name);

        if (empty($param)) {
            return new WP_Error('err', 'Seccret key is empty.');
        }

        if ($correctSecretKey !== $param) {
            return new WP_Error('err', 'Ivalid secret key.');
        }

        return true;
    }

    public function sanitize($param, $request, $key): mixed
    {
        return (string)$param;
    }
}
