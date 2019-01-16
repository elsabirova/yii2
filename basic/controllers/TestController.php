<?php
namespace app\controllers;

use app\models\Product;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionIndex()
    {
        $product = new Product( ['id' => 1, 'name' => 'Computer', 'price' => 70000, 'created_at' => 2]);
        return $this->render('index', [
            'data' => \Yii::$app->test->get(),
            'product' => $product,
        ]);
    }
}
