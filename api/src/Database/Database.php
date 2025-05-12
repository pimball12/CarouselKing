<?php

namespace Src\Database;

use PDO;
use PDOException;
use Src\Helpers\Env;

class Database {

    private $pdo;

    public function __construct() {

        try {

            $this->pdo = new PDO(

                "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'],
                $_ENV['DB_USER'],
                $_ENV['DB_PASS']
            );

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {

            die("Error connecting to databse: " . $e->getMessage());
        }
    }

    public function query($sql, $params = []) {

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}