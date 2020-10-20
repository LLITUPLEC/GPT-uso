<?php


namespace app\controllers;


use app\models\Comment;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class CommentController extends Controller
{
    public function actionIndex()
    {
        $form = new AjaxCommentForm();
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($form->load(Yii::$app->request->post()) && $form->validate()){
                $commentModel = new Comment();
                $commentModel->note = $form->note;
                if ($commentModel->save()) {
                    return $data = [
                        'success' => true,
                        'comment' => $form->comment,
                    ];
                }
            }
        }

        return $this->render('index', [
            'model' => $form,
            'comments' => Comment::getComments()
        ]);
    }
}