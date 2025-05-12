<?php

namespace Src;

use Src\Auth\JWTManager;
use Src\Helpers\Util;

class Api
{
    public function home()
    {
        $jwt = new JWTManager();

        $token = $jwt->encode(['teste' => 'teste']);
        Util::fd($token);

        $content = $jwt->decode($token);
        Util::fd($content);
    }
}
