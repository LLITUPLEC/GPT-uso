<?php

namespace app\controllers;

use app\models\File;
use app\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class FileController extends Controller
{
    public function behaviors()
    {
        return [
//            'access' => [
//                // доступ только для админов
//                'class' => AccessControl::class,
//                'only' => ['upload', 'update', 'delete'],
//                'rules' => [
//                    [
//                        'allow' => true,
//                        'roles' => ['admin'],
//                    ],
//                ],
//            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'upload', 'delete'],
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
        $query = File::find();

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'validatePage' => true,
                'pageSize' => 10,
            ],
        ]);

        return $this->render('@app/views/file/index', [
            'provider' => $provider,
        ]);
    }

    public function actionView(int $id)
    {
        $item = File::findOne($id);

        // просматривать файлы может любой авторизоанный пользователь
        return $this->render('@app/views/file/view', [
            'model' => $item,
        ]);
    }

    public function actionUpload()
    {
//        var_dump();
        $model = new File();

        if (Yii::$app->request->isPost) {
            $model->path = UploadedFile::getInstance($model, 'path');
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                Yii::$app->session->setFlash('success', 'Изображение загружено');
                $model->save();
                (new User)->findAllUsersID();
                (new File)->infoDocInsert($model->id);
                return $this->render('@app/views/file/view', ['model' => $model]);
            }
        }
        return $this->render('@app/views/file/upload', ['model' => $model]);
    }

    public function actionUpdate(int $id = null)
    {
        $item = $id ? File::findOne($id) : new File;

        // обновлять записи может только создатель
        if (Yii::$app->user->can('admin')) {
            if (Yii::$app->request->isPost) {
                $item->path = UploadedFile::getInstance($item, 'path');
                if ($item->load(Yii::$app->request->post()) && $item->validate()) {
                    Yii::$app->session->setFlash('success', 'Данные успешно изменены');
                    $item->save();
                    (new User)->findAllUsersID();
                    (new File)->infoDocInsert($item->id);
                    return $this->render('@app/views/file/view', ['model' => $item]);
                }
            }
            return $this->render('@app/views/file/upload', ['model' => $item]);
        } else {
            throw new NotFoundHttpException();
        }
    }


    public function actionDelete(int $id)
    {
        $item = File::findOne($id);

        // удалять записи может только админ
        if (Yii::$app->user->can('admin')) {
            (new File)->infoDocDelete($id);
            $item->delete();

            return $this->redirect(['file/index']);
        }

        throw new NotFoundHttpException();
    }
}
