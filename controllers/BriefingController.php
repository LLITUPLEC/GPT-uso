<?php


namespace app\controllers;


use app\models\Briefing;
use app\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BriefingController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete'],
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'view','index_ot','index_bd',],
                        'roles' => ['user'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionIndex_ot()
    {
        $queryo = Briefing::find()->where(['section' => 'Охрана труда']);
        $userModel = Yii::$app->user->identity;

        // добавим условие на выборку по пользователю, если это не admin
        if (!Yii::$app->user->can('admin')) {
            $queryo->andWhere(['user_id' => Yii::$app->user->id])
                ->andWhere(['position_id' => $userModel->position_id])
                ->orWhere(['user_id' => Yii::$app->user->id])
                ->orWhere(['position_id' => 13])
                ->orWhere(['user_id'=> 4,'position_id' =>$userModel->position_id]);
        }

        $provider = new ActiveDataProvider([
            'query' => $queryo,
            'pagination' => [
                'validatePage' => true,
                'pageSize' => 12,
            ],
        ]);

        return $this->render('@app/views/briefing/index_ot', [
            'provider' => $provider
        ]);
    }

    public function actionIndex_bd()
    {
        $queryb = Briefing::find()->where(['section' => 'Безопасность движения']);
        $userModel = Yii::$app->user->identity;

        // добавим условие на выборку по пользователю, если это не admin
        if (!Yii::$app->user->can('admin')) {
            $queryb->andWhere(['user_id' => Yii::$app->user->id])
                ->andWhere(['position_id' => $userModel->position_id])
                ->orWhere(['user_id' => Yii::$app->user->id])
                ->orWhere(['position_id' => 13])
                ->orWhere(['user_id'=> 4,'position_id' =>$userModel->position_id]);
        }

        $provider = new ActiveDataProvider([
            'query' => $queryb,
            'pagination' => [
                'validatePage' => true,
                'pageSize' => 12,
            ],
        ]);

        return $this->render('@app/views/briefing/index_bd', [
            'provider' => $provider
        ]);
    }

    public function actionView(int $id)
    {
        $item = Briefing::findOne($id);
        if (Yii::$app->user->can('admin') || $item->user_id == Yii::$app->user->id || $item->position_id == 13) {
            return $this->render('@app/views/briefing/view', [
                'model' => $item,
            ]);
        } else {
            throw new NotFoundHttpException();
        }
    }

    public function actionUpdate(int $id = null)
    {
        $item = $id ? Briefing::findOne($id) : new Briefing([
            'user_id' => Yii::$app->user->id,
        ]);

        // обновлять записи может только admin
        if (Yii::$app->user->can('admin')) {
            if ($item->load(Yii::$app->request->post()) && $item->validate()) {
                if ($item->save()) {
                    (new User)->findAllUsersID();
                    (new Briefing)->infoBriefInsert($item->id, $item->user_id, $item->position_id);
                    return $this->redirect(['briefing/view', 'id' => $item->id]);
                }
            }

            return $this->render('@app/views/briefing/edit', [
                'model' => $item,
            ]);
        } else {
            throw new NotFoundHttpException();
        }
    }

    public function actionDelete(int $id)
    {
        $item = Briefing::findOne($id);

        // удалять записи может только admin
        if (Yii::$app->user->can('admin')) {
            (new Briefing)->infoBriefDelete($id);
            $item->delete();

            return $this->redirect(['briefing/index']);
        }

        throw new NotFoundHttpException();
    }
}