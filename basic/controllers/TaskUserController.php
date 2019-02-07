<?php

namespace app\controllers;

use app\models\Task;
use app\models\User;
use Yii;
use app\models\TaskUser;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TaskUserController implements the CRUD actions for TaskUser model.
 */
class TaskUserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'deleteAll' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Creates a new TaskUser model.
     * If creation is successful, the browser will be redirected to Shared tasks.
     * @param integer $taskId
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionCreate($taskId)
    {
        $model = new TaskUser();

        $task = $this->findTask($taskId);

        TaskController::isAvailableChange($task->creator_id);

        $model->task_id = $taskId;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            app()->session->setFlash('success', 'Task is shared successfully');
            return $this->redirect(['/task/shared']);
        }

        $usersWithCurrentTask = TaskUser::find()->select('user_id')->where(['task_id' => $taskId]);
        $users = User::find()->select('username')
            ->where(['and', ['<>', 'id', app()->user->id], ['not in', 'id', $usersWithCurrentTask]])
            ->indexBy('id')->column();
        return $this->render('create', [
            'model' => $model,
            'users' => $users,
        ]);

    }

    /**
     * @param $taskId
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionDeleteAll($taskId)
    {
        $task = $this->findTask($taskId);

        TaskController::isAvailableChange($task->creator_id);

        $result = TaskUser::deleteAll(['task_id' => $taskId]);
        if($result) {
            app()->session->setFlash('success', 'Task is unshared successfully for all users');
        }

        return $this->redirect(['/task/shared']);
    }

    /**
     * Deletes an existing TaskUser model.
     * If deletion is successful, the browser will be redirected to the '/task/view' page.
     * @param integer $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the task cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $result = $model->delete();
        if($result)
            app()->session->setFlash('success', 'Task is unshared successfully');
        return $this->redirect(['/task/view', 'id' => $model->task_id]);

    }

    /**
     * Finds the TaskUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findTask($id)
    {
        if (($task = Task::findOne($id)) !== null) {
            return $task;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the TaskUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TaskUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TaskUser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
