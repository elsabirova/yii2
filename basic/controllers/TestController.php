<?php
namespace app\controllers;

use app\models\Product;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionIndex()
    {
        $product = new Product(1, 'Computer', 70000, 2);
        return $this->render('index', [
            'data' => 111111,
            'product' => $product,
        ]);
        //return $this->renderContent('test');
    }
}
