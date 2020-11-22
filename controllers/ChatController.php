<?php


namespace app\controllers;


use app\models\Chat;
use yii\web\Controller;
use app\models\Activity;
use app\models\User;
use Throwable;
use Yii;
use yii\caching\DbDependency;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\PageCache;
use yii\web\NotFoundHttpException;

class ChatController extends Controller
{
    /**
     * Настройка поведений контроллера (ACF доступы)
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['view', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['view', 'update', 'delete'],
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Просмотр всех сообщений, если админ, и только корректных в ином случае
     * @return string
     */
    public function actionIndex()
    {
        $currentUserId = Yii::$app->user->id;
//        $messageQuery = null;
        if (Yii::$app->user->can('admin')) {
             $messageQuery = Chat::find();
        } else {
            $messageQuery = Chat::find()->where(['blocked' => 0]);
        }

        $message = new Chat([
            'user_id' => $currentUserId
        ]);

        if ($message->load(Yii::$app->request->post()) && $message->validate()) {
            $message->save();
            $message = new Chat([
                'user_id' => $currentUserId
            ]);
            if (Yii::$app->request->post()) {
                return $this->render('_chat', compact('messageQuery', 'message'));
            }
        }
        if (Yii::$app->request->post()) {
            return $this->render('_list', compact('messageQuery', 'message'));
        }


        return $this->render('chat', compact('messageQuery','message'));
    }

    /**
     * Просмотр всех некорректных сообщений
     * @return string
     */
    public function actionView()
    {
        $query = Chat::find()->where(['blocked' => 1]);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
//                'validatePage' => false,
                'pageSize' => 10,
            ],
        ]);

        return $this->render('@app/views/chat/view', [
            'provider' => $provider,
        ]);
    }

    public function actionUpdate(int $id = null)
    {
        $item = $id ? Chat::findOne($id) : new Chat([
            'user_id' => Yii::$app->user->id,
        ]);

        // обновлять записи может только админ
        if (Yii::$app->user->can('admin')) {
            if ($item->load(Yii::$app->request->post()) && $item->validate()) {
                if ($item->save()) {
                    return $this->redirect(['chat/view']);
                }
            }

            return $this->render('@app/views/chat/edit', [
                'model' => $item,
            ]);
        } else {
            throw new NotFoundHttpException();
        }
    }

    /**
     * Удаление выбранного сообщения
     *
     * @param int $id
     *
     * @return string
     * @throws NotFoundHttpException
     * @throws Throwable
     */
    public function actionDelete(int $id)
    {
        $item = Chat::findOne($id);

        // удалять сообщения может только admin
        if (Yii::$app->user->can('admin')) {
            $item->delete();
            return $this->redirect(['chat/view']);
        }

        throw new NotFoundHttpException();
    }
}