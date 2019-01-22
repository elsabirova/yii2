<?php
namespace app\controllers;

use app\models\Product;
use yii\db\Query;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionIndex()
    {
        $product = new Product(['id' => 1, 'name' => 'Computer', 'price' => 70000, 'created_at' => 2]);

        return $this->render('index', [
            'data' => app()->test->get(),
            'product' => $product,
        ]);
    }

    public function actionInsert()
    {
        app()->db->createCommand()->insert('user', [
            'username' => 'admin',
            'password_hash' => 'Tbq9uYkGALcH1k1y.ZdF8FNrL7Yy',
            'auth_key' => '345',
            'creator_id' => 1,
            'created_at' => time(),
        ])->execute();
        app()->db->createCommand()->insert('user', [
            'username' => 'user',
            'password_hash' => 'FgiNYwcq1ReyXuGAj97Ac87zQda',
            'auth_key' => '123',
            'creator_id' => 1,
            'created_at' => time(),
        ])->execute();
        app()->db->createCommand()->insert('user', [
            'username' => 'test',
            'password_hash' => 'AxHiH1HNpB0rNwqzERhadOseSAc',
            'auth_key' => '231',
            'creator_id' => 1,
            'created_at' => time(),
        ])->execute();

        $result = app()->db->createCommand()->batchInsert('task',
            ['title', 'description', 'creator_id', 'created_at'],
            [
                ['task1', 'call', 1, time()],
                ['task2', 'ask', 2, time()],
                ['task3', 'plan', 3, time()],
            ])->execute();

        if($result)
            $msg = 'Tasks added in table';
        else
            $msg = 'Error';
        return $msg;
    }

    public function actionSelect()
    {
        $query = new Query();
        $result = $query->from('user')->where(['id' => 1])->indexBy('id')->all();
        _end($result);

        $query = new Query;
        $result = $query->from('user')->where(['>', 'id', 1])->orderBy('username')->indexBy('id')->all();
        _end($result);

        $query = new Query;
        $result = $query->from('user')->count();
        _end($result);

        $query = new Query();
        $result = $query->from(['t' => 'task'])->innerJoin(['u' => 'user'], 't.creator_id = u.id')->all();
        _end($result);
    }
}
