<?php

namespace Src\Controllers;

use Src\Api;

class Company extends Api
{
    protected $table = 'ck_companies';
    protected $name = 'Companies';
    protected $requiredFields = ['name'];
    protected $secured = true;
    protected $belongsToUser = true;
}