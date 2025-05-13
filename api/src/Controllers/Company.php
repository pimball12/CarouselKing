<?php

namespace Src\Controllers;

use Src\Api;
use Src\Auth\JWTManager;
use Src\Helpers\Util;

class Company extends Api
{
    protected $table = 'ck_companies';
    protected $name = 'Companies';
    protected $requiredFields = ['name'];
    protected $secured = true;
    protected $belongsToUser = true;
}