<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tasks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a('Create Task', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'description:ntext',
            'created_at:datetime',
            'updated_at:datetime',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{share} {view} {update} {delete}',
                'buttons' => [
                        'share' => function ($url, \app\models\Task $model) {
                                        $icon = \yii\bootstrap\Html::icon('share');
                                        $url = ['/task-user/create', 'taskId' => $model->id];

                                        return Html::a($icon, $url);
                                  },
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
