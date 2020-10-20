<?php


namespace app\models;


use yii\db\ActiveRecord;

class Comment extends ActiveRecord
{
    public static function tableName()
    {
        return 'comments';
    }

    public static function getComments()
    {
        return self::find()->all();
    }
}