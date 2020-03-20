<?php


namespace app\controllers;


use app\models\Acquaint;
use app\models\AcquaintSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class AcquaintController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'delete',],
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view',],
                        'roles' => ['user'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new AcquaintSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView(int $id)
    {

        $item = Acquaint::findOne($id);

        // просматривать записи может Работник или Администратор сайта
        if (Yii::$app->user->can('admin') || $item->user_id == Yii::$app->user->id) {
            return $this->render('@app/views/acquaint/view', [
                'model' => $item,
            ]);
        } else {
            throw new NotFoundHttpException();
        }

    }

    public function actionUpdate(int $id = null)
    {
        $item1 = $id ? Acquaint::findOne($id) : new Acquaint([
            'user_id' => Yii::$app->user->id,
        ]);

        // обновлять записи может только админ
        if (Yii::$app->user->can('admin')) {
            if ($item1->load(Yii::$app->request->post()) && $item1->validate()) {
                if ($item1->save()) {
                    return $this->redirect(['acquaint/view', 'id' => $item1->id]);
                }
            }

            return $this->render('edit', [
                'model' => $item1,
            ]);
        } else {
            throw new NotFoundHttpException();
        }
    }
}