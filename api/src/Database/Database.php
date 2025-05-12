<?php

namespace Src\Database;

use PDO;
use PDOException;
use Src\Helpers\Env;

class Database {

    private $pdo;

    public function __construct() {

        $config = getenv();

        try {

            $this->pdo = new PDO(

                "mysql:host=" . $config['DB_HOST'] . ";dbname=" . $config['DB_NAME'],
                $config['DB_USER'],
                $config['DB_PASS']
            );

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {

            die("Erro ao conectar ao banco: " . $e->getMessage());
        }
    }

    public function query($sql, $params = []) {

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}