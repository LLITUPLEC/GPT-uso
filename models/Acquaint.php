<?php


namespace app\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Acquaint
 * @package app\models
 *
 * @property int $id [int(11)]  Порядковый номер
 * @property int $user_id [int(11)]  Кто ознакомлен
 * @property int $file_id [int(11)]  С чем ознакомлен
 *
 * @property-read User $user
 * @property-read File $file
 *
 * @property int $created_at [int(11)]
 * @property int $updated_at [int(11)]
 */
class Acquaint extends ActiveRecord
{
    public function behaviors()
    {
        return [TimestampBehavior::class,];
    }

    public static function tableName()
    {
        return 'acquaint';
    }

    public function rules()
    {
        return [
            [['user_id', 'file_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '#',
            'user_id' => 'Кто ознакомлен',
            'file_id' => '',
            'created_at' => 'Дата ознакомления',
            'updated_at' => 'Дата последнего изменения',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getFile()
    {
        return $this->hasOne(File::class, ['id' => 'file_id']);
    }

//    public function getPositionTitle()
//    {
//        return $this->file->title;
//    }
}