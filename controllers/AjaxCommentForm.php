<?php


namespace app\controllers;


use yii\base\Model;

class AjaxCommentForm extends Model
{
    public $note;

    public function rules()
    {
        return [
            ['note', 'string'],
        ];
    }
}