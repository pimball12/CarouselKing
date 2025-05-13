<?php

namespace Src\Controllers;

use Src\Api;

class File extends Api
{
    protected string $storePath;

    public function __construct()
    {
        parent::__construct();

        $this->storePath = dirname(__DIR__, 2) . '/store';
        if (!is_dir($this->storePath)) {
            mkdir($this->storePath, 0777, true);
        }
    }

    public function upload()
    {
        $this->assertMethod('POST');
        $this->secure();

        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            
            $this->response(false, 400, 'File not sent or invalid.');
        }

        $file = $_FILES['file'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $hashName = hash_file('sha256', $file['tmp_name']) . '.' . $ext;
        $destination = $this->storePath . '/' . $hashName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {

            $this->response(false, 500, 'Error saving file.');
        }

        $this->response(true, 201, 'File saved successfully.', ['name' => $hashName]);
    }

    public function retrieve($name = '')
    {
        $this->assertMethod('GET');

        if (empty($name)) {

            $this->response(false, 400, 'Invalid file name.');
        }

        $filePath = $this->storePath . '/' . basename($name);

        if (!file_exists($filePath)) {

            $this->response(false, 404, 'File not found.');
        }

        $mimeType = mime_content_type($filePath);
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }
}
