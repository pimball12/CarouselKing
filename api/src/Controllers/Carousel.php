<?php

namespace Src\Controllers;

use PDO;
use Src\Api;
use Src\Database\Database;
use Src\Helpers\Util;

class Carousel extends Api
{
    protected $table = 'ck_carousels';
    protected $name = 'Carousels';
    protected $requiredFields = ['company_id', 'name'];
    protected $secured = true;
    protected $belongsToUser;

    public function listByCompany($companyId)
    {
        $this->assertMethod('GET');

        $db = new Database();

        $rows = $db->query("SELECT * FROM $this->table WHERE company_id = $companyId")->fetchAll(PDO::FETCH_ASSOC);

        if (!$rows) {

            $this->response(false, 404, $this->name . ' not found');
        }

        $this->response(true, 200, '', $rows);
    }
}
