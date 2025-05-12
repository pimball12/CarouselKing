<?php

namespace Src;

use PDO;
use Src\Auth\JWTManager;
use Src\Database\Database;
use Src\Helpers\Util;

class Api
{
    public function home()
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
    }
}
