<?php

namespace Src\Controllers;

use PDO;
use Src\Api;
use Src\Auth\JWTManager;
use Src\Database\Database;
use Src\Helpers\Util;

class Auth extends Api
{
    public function login()
    {
        $this->assertMethod('POST');
        $input = json_decode(file_get_contents('php://input'), true);

        $db = new Database();
        $user = $db->query("SELECT * FROM ck_users WHERE email = ?", [ $input['email'] ])->fetch(PDO::FETCH_ASSOC);
        
        if (!$user ) {

            $this->response(false, 401, 'Invalid credentials');
        }
        
        if (!password_verify($input['password'], $user['password'])) {
            
            $this->response(false, 401, 'Invalid credentials');
        }

        $jwt = new JWTManager();

        $token = $jwt->encode([

            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name']
        ]);

        $this->response(true, 200, 'Login successful', [

            'token' => $token,
            'user' => $user
        ]);
    }
}