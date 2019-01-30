<?php

namespace app\controllers;

use app\models\Task;
use Yii;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTest() {
        //Task 4a
        $user = new User([
            'username' => 'user1',
            'password_hash' => 'FgiNYwcq1ReyXuGAj97Ac87zQda',
            'auth_key' => '123',
            'creator_id' => 1,
            'created_at' => time(),
        ]);
         $user->save();

         //Task 4б
         $task1 = new Task([
             'title' => 'Task1',
             'description' => 'call',
             'created_at' => time()
         ]);

         $task1->link(Task::RELATION_CREATOR, $user);

         $task2 = new Task([
             'title' => 'Task2',
             'description' => 'plan',
             'created_at' => time()
         ]);

         $task2->link(Task::RELATION_CREATOR, $user);

         $task3 = new Task([
             'title' => 'Task3',
             'description' => 'ask',
             'created_at' => time()
         ]);

         $task3->link(Task::RELATION_CREATOR, $user);

        //Task 4в
        _log(User::find()->with(User::RELATION_CREATED_TASKS)->asArray()->all());

        //Task 4г
        _log(User::find()->joinWith(User::RELATION_CREATED_TASKS)->asArray()->all());

        //Task 5
        $user = User::findOne(1);
        _log($user->getAccessedTasks()->asArray()->all());

        //Task 6
        $task = Task::findOne(3);
        $user->link(User::RELATION_ACCESSED_TASKS, $task);

        return $this->renderContent('test');
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->setScenario(User::SCENARIO_CREATE);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario(User::SCENARIO_UPDATE);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
