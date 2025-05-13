<?php

namespace Src;

use PDO;
use Src\Auth\JWTManager;
use Src\Database\Database;
use Src\Helpers\Util;

class Api
{
    protected $table;
    protected $name;
    protected $input = [];
    protected $requiredFields = [];
    protected $secured = false;
    protected $belongsToUser = false;
    protected $user = null;

    public function __construct()
    {
        $this->input = json_decode(file_get_contents('php://input'), true);

        if ($this->secured) {

            try {

                $token = JWTManager::getBearerToken();

                if (!$token) {

                    throw new \Exception('Unauthorized');
                }

                $jwt = new JWTManager();

                $content = $jwt->decode($token);

                if ($content['exp'] < time()) {

                    throw new \Exception('Token expired');
                }

                $this->user = $content;
            } catch (\Exception $e) {

                $this->response(false, 401, 'Unauthorized');
            }
        }
    }

    protected function assertMethod(string|array $method)
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

    protected function response($success = true, $code = 200, $message = '', $data = null)
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

    public function read($id = null)
    {
        $this->assertMethod('GET');

        if (!$id) {

            $this->response(false, 400, 'Company ID is required');
        }

        $db = new Database();

        $row = $db->query("SELECT * FROM $this->table WHERE id = ?", [$id])->fetch(PDO::FETCH_ASSOC);

        if (!$row) {

            $this->response(false, 404, Util::toSingular($this->name) . ' not found');
        }

        $this->response(true, 200, '', $row);
    }

    public function list()
    {
        $this->assertMethod('GET');

        $db = new Database();

        $rows = $db->query("SELECT * FROM $this->table")->fetchAll(PDO::FETCH_ASSOC);

        if (!$rows) {

            $this->response(false, 404, $this->name . ' not found');
        }

        $this->response(true, 200, '', $rows);
    }

    public function create()
    {
        $this->assertMethod('POST');

        if ($this->belongsToUser)   {
            
            if (!$this->user) {

                $this->response(false, 401, 'Unauthorized');
            }

            $this->input['user_id'] = $this->user['id'];
        }

        $fields = array_keys($this->input);
        $values = array_values($this->input);
        $bound = array_fill(0, count($values), '?');

        if (!empty(array_diff($this->requiredFields, $fields))) {

            $this->response(false, 400, 'Required fields: ' . implode(', ', $this->requiredFields));
        }

        $db = new Database();
        $db->query("INSERT INTO $this->table (" . implode(',', $fields) . ") VALUES (" . implode(', ', $bound)  . ")", $values);
        $id = intval($db->lastInsertId());

        $this->response(true, 201, Util::toSingular($this->name) . ' created successfully', ['id' => $id, ...$this->input]);
    }

    public function update($id = null)
    {
        $this->assertMethod('PUT');

        if (!$id) {

            $this->response(false, 400, 'Company ID is required');
        }

        $fields = array_keys($this->input);
        $values = array_values($this->input);
        $bound = array_fill(0, count($values), '?');

        $db = new Database();

        $db->query("UPDATE $this->table SET " . implode(' = ?, ', $fields) . " = ? WHERE id = ?", [...$values, $id]);
        $data = $db->query("SELECT * FROM $this->table WHERE id = ?", [$id])->fetch(PDO::FETCH_ASSOC);

        $this->response(true, 200, Util::toSingular($this->name) . ' updated successfully', $data);
    }

    public function delete()
    {
        $this->assertMethod('DELETE');

        if (!$this->input['id']) {

            $this->response(false, 400, 'Company ID is required');
        }

        $db = new Database();

        $db->query("DELETE FROM $this->table WHERE id = ?", [$this->input['id']]);

        $this->response(true, 200, Util::toSingular($this->name) . ' deleted successfully');
    }
}
