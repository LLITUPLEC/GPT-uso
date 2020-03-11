<?php


namespace app\controllers;


use app\models\Result;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


class ResultController extends Controller
{
    public function actionIndex()
    {
        $query = Result::find();

        // добавим условие на выборку по пользователю, если это не менеджер
        if (!Yii::$app->user->can('admin')) {
            $query->andWhere(['user_id' => Yii::$app->user->id]);
        }

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'validatePage' => true,
                'pageSize' => 10,
            ],
        ]);

        return $this->render('@app/views/result/index', [
            'provider' => $provider,
        ]);
    }

    public function actionView(int $id)
    {

        $item = Result::findOne($id);

        // просматривать записи может только создатель или Администратор сайта
        if (Yii::$app->user->can('admin') || $item->user_id == Yii::$app->user->id) {
            return $this->render('@app/views/result/view', [
                'model' => $item,
            ]);
        } else {
            throw new NotFoundHttpException();
        }

    }
}
