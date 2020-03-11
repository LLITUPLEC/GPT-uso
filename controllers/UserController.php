<?php

namespace app\controllers;

use app\models\Activity;
use app\models\forms\SignupForm;
use app\models\forms\UpdateUserForm;
use app\models\Position;
use app\models\User;
use app\models\UserSearch;
use Exception;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class UserController extends Controller
{
    public function behaviors()
    {
        /**
         * {@inheritdoc}
         */
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                // доступ только для админов
                'class' => AccessControl::class,
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUser_homepage()
    {
        /** @var User $user */
        $user_id = Yii::$app->user->identity;

        $provider = new ActiveDataProvider([
            'query' => Activity::find()->where(['id' => $user_id]),
            'pagination' => [
                'validatePage' => false,
            ],
        ]);


        $model = new UpdateUserForm();

        if ($model->load(Yii::$app->request->post()) && $model->updatePass()) {
            Yii::$app->session->setFlash('success', 'Изменения успешно сохранены');
        } else if ($model->load(Yii::$app->request->post()) && !$model->updatePass()) {
            Yii::$app->session->setFlash('warning', 'Проверьте правильность заполнения');
        }

        return $this->render('@app/views/user/user_homepage', compact('model', 'provider'));

    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id)
    {
        return $this->render('@app/views/user/view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws Exception
     */
    public function actionCreate()
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post())) {
            $user = $model->register();
            if ($user) {
                (new User)->newUserSetInfo($user->id);
                Yii::$app->session->setFlash('success', 'Новый работник зарегистрирован.');
                return $this->redirect(['view', 'id' => $user->id]);
            }
        }

        return $this->render('@app/views/user/create', [
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
    public function actionUpdate(int $id = null)
    {
        $item = $id ? User::findOne($id) : new User([
            'id' => Yii::$app->user->id,
        ]);
        if ( $item->id == 4) {
            return 'Редактирование данной записи невозможно';
        } else
        // обновлять записи может только admin
        if (Yii::$app->user->can('admin')) {
            if ($item->load(Yii::$app->request->post()) && $item->validate()) {
                if ($item->save()) {
                    return $this->redirect(['user/view', 'id' => $item->id]);
                }
            }

            return $this->render('@app/views/user/update', [
                'model' => $item,
            ]);
        } else {
            throw new NotFoundHttpException();
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete(int $id)
    {
       $item = $this->findModel($id);
       if ($item->id == Yii::$app->user->id) {
           return 'Удаление собственной записи невозможно';
       } else if ($item->id == 4) {
           return 'Удаление общей записи также невозможно';
       } else {
           $this->findModel($id)->delete();
       }

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

    public function actionMobile()
    {
        return $this->render('@app/views/user/mobi');
    }

}
