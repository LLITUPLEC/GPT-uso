<?php


namespace app\controllers;


use app\models\Activity;
use app\models\User;
use Throwable;
use Yii;
use yii\caching\DbDependency;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\PageCache;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ActivityController extends Controller
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
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'delete', 'submit'],
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view',],
                        'roles' => ['user'],
                    ],
                ],
            ],

            // кеширование страницы index
//            [
//                'class' => PageCache::class,
//                'only' => ['index'],
//                'duration' => 120,
//                'dependency' => [
//                    'class' => DbDependency::class,
//                    'sql' => 'SELECT COUNT(*) FROM activity',
//                ],
//            ],
        ];
    }

    /**
     * Просмотр всех событий
     * @return string
     */
    public function actionIndex()
    {
        $query = Activity::find();

        // добавим условие на выборку по пользователю, если это не админ
        if (!Yii::$app->user->can('user')) {
            $query->andWhere(['user_id' => Yii::$app->user->id]);
        }

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
//                'validatePage' => false,
                'pageSize' => 5,
            ],
        ]);

        return $this->render('@app/views/activity/index', [
            'provider' => $provider,
        ]);
    }

    /**
     * Просмотр выбранного события
     *
     * @param int $id
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(int $id)
    {
        // ключ для записи в кеш
//        $cacheKey = "activity_{$id}"; // activity_1
//
//        // проверка на наличие в кеше
//        if (Yii::$app->cache->exists($cacheKey)) {
//            $item = Yii::$app->cache->get($cacheKey);
//        } else {
//            // получение из бд с сохранением в кеш
            $item = Activity::findOne($id);

//            Yii::$app->cache->set($cacheKey, $item);
//        }

        // просматривать записи может только создатель или Администратор сайта
//        if (Yii::$app->user->can('admin') || $item->user_id == Yii::$app->user->id) {
//            return $this->render('view', [
//                'model' => $item,
//            ]);
//        } else {
//            throw new NotFoundHttpException();
//        }

        // просматривать события может любой авторизоанный пользователь
        return $this->render('@app/views/activity/view', [
            'model' => $item,
        ]);
    }

    /**
     * Создание нового события
     *
     * @param int|null $id
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id = null)
    {
        $item = $id ? Activity::findOne($id) : new Activity([
            'user_id' => Yii::$app->user->id,
        ]);

        // обновлять записи может только создатель или менеджер
        if (Yii::$app->user->can('admin') || $item->user_id == Yii::$app->user->id) {
            if ($item->load(Yii::$app->request->post()) && $item->validate()) {
                if ($item->save()) {
                    (new User)->findAllUsersID();
                    (new Activity)->infoActionInsert($item->id);
                    return $this->redirect(['activity/view', 'id' => $item->id]);
                }
            }

            return $this->render('@app/views/activity/edit', [
                'model' => $item,
            ]);
        } else {
            throw new NotFoundHttpException();
        }
    }

    /**
     * Удаление выбранного события
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
        $item = Activity::findOne($id);

        // удалять записи может только admin
        if ($item->user_id == Yii::$app->user->id || Yii::$app->user->can('admin')) {
            (new Activity)->infoActionDelete($id);
            $item->delete();
            return $this->redirect(['activity/index']);
        }

        throw new NotFoundHttpException();
    }
}