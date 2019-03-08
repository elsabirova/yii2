<?php

namespace app\controllers;

use app\models\TaskUser;
use Yii;
use app\models\Task;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
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
     * Lists all tasks created by current user.
     * @return string
     */
    public function actionMy() {
        $query = Task::find()->byCreator(app()->user->id);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('my', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all tasks created by current user and shared for others.
     * @return string
     */
    public function actionShared() {
        $query = Task::find()->byCreator(app()->user->id)->innerJoinWith(Task::RELATION_TASK_USERS);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('shared', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all tasks shared for current user.
     * @return string
     */
    public function actionAccessed() {
        $query = Task::find()->innerJoinWith(Task::RELATION_TASK_USERS)->where(['user_id' => app()->user->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('accessed', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Task model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        $model = $this->findModel($id);

        if ($model->creator_id === app()->user->id) {
            $query = $model->getTaskUsers();
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);

            $dataProvider->sort->attributes['user.username'] = [
                'asc' => ['username' => SORT_ASC],
                'desc' => ['username' => SORT_DESC],
            ];
            $showUsers = true;
        }
        else {
            $dataProvider = '';
            $showUsers = false;
        }
        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'showUsers' => $showUsers
        ]);
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Task();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            app()->session->setFlash('success', 'Task is created successfully');
            return $this->redirect(['my']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the page Tasks.
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException if the task is not available for change
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        static::isAvailableChange($model->creator_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            app()->session->setFlash('success', 'Task is updated successfully');
            return $this->redirect(['my']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);

    }


    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the page Tasks.
     * @param integer $id
     * @return \yii\web\Response
     * @throws ForbiddenHttpException if the task is not available for delete
     * @throws NotFoundHttpException if the task cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);

        static::isAvailableChange($model->creator_id);

        $result = $model->delete();
        if ($result)
            app()->session->setFlash('success', 'Task is deleted successfully');
        return $this->redirect(['my']);
    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param integer $creator user who created the task
     * @throws ForbiddenHttpException if the task created not current user is not available for change or delete
     */
    public static function isAvailableChange($creator) {
        if ($creator !== app()->user->id) {
            throw new ForbiddenHttpException('The requested page is forbidden.');
        }
    }
}
