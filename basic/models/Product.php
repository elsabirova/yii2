<?php

namespace app\models;

use yii\base\Model;

class Product extends Model
{
    public $id;
    public $name;
    public $price;
    public $category_id;

    /**
     * Product constructor.
     * @param $id
     * @param $name
     * @param $price
     * @param $category_id
     */
    public function __construct($id, $name, $price, $category_id) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->category_id = $category_id;
    }


}