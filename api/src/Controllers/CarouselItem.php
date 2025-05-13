<?php

namespace Src\Controllers;

use PDO;
use Src\Api;
use Src\Database\Database;

class CarouselItem extends Api
{
    protected $table = 'ck_carousel_items';
    protected $name = 'Items';
    protected $requiredFields = ['carousel_id'];
    protected $secured = true;

    public function listByCarousel($carouselId)
    {
        $this->assertMethod('GET');

        $db = new Database();

        $rows = $db->query("SELECT * FROM $this->table WHERE carousel_id = $carouselId")->fetchAll(PDO::FETCH_ASSOC);

        if (!$rows) {

            $this->response(false, 404, $this->name . ' not found');
        }

        $this->response(true, 200, '', $rows);
    }
}
