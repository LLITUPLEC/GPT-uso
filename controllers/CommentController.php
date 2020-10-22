<?php


namespace app\controllers;


use app\models\Comment;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CommentController extends Controller
{
    public function actionIndex()
    {
        $query = Comment::find();

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
//                'validatePage' => false,
                'pageSize' => 3,
            ],
        ]);

        $item =  new Comment();


        if (Yii::$app->user->isGuest) {
            if ($item->load(Yii::$app->request->post()) && $item->validate()) {
                if ($item->save()) {
                    return $this->redirect(['comment/index']);
//                    return 'Запрос принят!';
                }
            }

            return $this->render('/comment/index', [
                'model' => $item,
                'provider' => $provider,
            ]);
        }


        return $this->render('@app/views/comment/index', [
            'provider' => $provider,
        ]);
    }

    public function actionView(int $id)
    {
        $item = Comment::findOne($id);


        // просматривать события может любой авторизоанный пользователь
        return $this->render('@app/views/comment/view', [
            'model' => $item,
        ]);
    }

    public function actionUpdate(int $id = null)
    {
        $query = Comment::find();

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
//                'validatePage' => false,
                'pageSize' => 3,
            ],
        ]);
        $item = $id ? Comment::findOne($id) : new Comment();

        // обновлять записи может только создатель или менеджер
        if (Yii::$app->user->isGuest) {
            if ($item->load(Yii::$app->request->post()) && $item->validate()) {
                if ($item->save()) {
                    return $this->redirect(['comment/view', 'id' => $item->id]);
                }
            }

            return $this->render('@app/views/comment/index', [
                'model' => $item,
                'provider' => $provider,
            ]);
        } else {
            throw new NotFoundHttpException();
        }
    }

    /**
     * Удаление выбранного клмментария
     *
     * @param int $id
     *
     * @return string
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete(int $id)
    {
        $item = Comment::findOne($id);

        // удалять записи может только admin, пока поставил для гостя
        if (Yii::$app->user->isGuest) {
            $item->delete();
            return $this->redirect(['comment/index']);
        }

        throw new NotFoundHttpException();
    }

    public function actionSecond()
    {
        return $this->render('second');
    }
}