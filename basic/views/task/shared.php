<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Task;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Shared tasks';
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
            'title',
            'description:ntext',
            [
                'label' => 'Username',
                'content' => function(Task $model) {
                    $users = $model->getSharedUsers()->select('username')->column();
                    $users = join(', ', $users);
                    return $users;
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{unshareAll} {view} {update} {delete}',
                'buttons' => [
                    'unshareAll' => function ($url, Task $model) {
                        $icon = \yii\bootstrap\Html::icon('remove');
                        $url = ['/task-user/delete-all', 'taskId' => $model->id];

                        return Html::a($icon, $url, [
                            'data' => [
                                'confirm' => 'Are you sure you want to unshare this item for all users?',
                                'method' => 'post',
                            ]
                        ]);
                    },
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
