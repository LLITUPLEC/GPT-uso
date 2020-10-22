<?php


namespace app\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Класс - Комментарий
 *
 * @package app\models
 *
 * @property int $id [int(11)]  Порядковый номер
 * @property string $name [varchar(255)]  Имя автора комментатария
 * @property string $email [varchar(255)]  Имэйл автора комментария
 * @property string $comment Текст комментария
 *
 * @property int $created_at [int(11)]
 * @property int $updated_at [int(11)]
 */
class Comment extends ActiveRecord
{
    public function behaviors()
    {
        return [TimestampBehavior::class,];
    }

    public static function tableName()
    {
        return 'comments';
    }

    public function attributeLabels()
    {
        return [
            'id' => '#',
            'name' => 'Имя',
            'email' => 'Электронная почта',
            'comment' => 'Комментарий',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата последнего изменения',
        ];
    }

    public function rules()
    {
        return [
            [['name', 'comment', 'email'], 'required'],
            [['name', 'comment', 'email'], 'string'],
            [['email'], 'email'],
            [['name'], 'string', 'min' => 2, 'max' => 20],
            [['comment'], 'string', 'min' => 1, 'max' => 1000],
        ];
    }
}