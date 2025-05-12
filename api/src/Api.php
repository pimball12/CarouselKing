<?php

namespace Src;

use PDO;
use Src\Auth\JWTManager;
use Src\Database\Database;
use Src\Helpers\Util;

class Api
{
    private function assertMethod(string|array $method)
    {
        if (!is_array($method)) {
            
            $method = [$method];
        }

        $method = array_map('strtoupper', $method);

        if (!in_array($_SERVER['REQUEST_METHOD'], $method)) {

            http_response_code(405);

            echo json_encode([

                'error' => 'Method not allowed',
                'allowed_methods' => [$method]
            ]);

            die();
        }
    }

    private function response($success = true, $code = 200, $message = '', $data = null)
    {
        http_response_code($code);

        echo json_encode([

            'success' => $success,
            'code' => $code,
            'message' => $message,
            'data' => $data
        ]);

        if (!$success) {

            die();
        }
    }

    public static function cors()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {

            http_response_code(200);
            die();
        }
    }

    public function test()
    {
        // $jwt = new JWTManager();

        // $token = $jwt->encode(['teste' => 'teste']);
        // Util::fd($token);

        // $content = $jwt->decode($token);
        // Util::fd($content);

        // $db = new Database();

        // $db->query("INSERT INTO ck_users (name, email, password) VALUES (?, ?, ?)", [ 'Lucas', 'lucas@email.com', '123456' ]);

        // $users = $db->query("SELECT * FROM ck_users")->fetchAll(PDO::FETCH_ASSOC);
        // Util::fd($users);
        echo password_hash('secret', PASSWORD_BCRYPT);
    }

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
