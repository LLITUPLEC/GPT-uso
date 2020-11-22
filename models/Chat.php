<?php


namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Класс - Чат
 *
 * @package app\models
 *
 * @property int $id [int(11)]  Порядковый номер
 * @property int $user_id [int(11)]  Автор сообщения
 * @property string $message Сообщение
 * @property bool $blocked [tinyint(1)]  Блокирует ли сообщение
 *
 * @property-read User $user
 *
 * @property int $created_at [int(11)]
 * @property int $updated_at [int(11)]
 */
class Chat extends ActiveRecord
{
    public function behaviors()
    {
        return [TimestampBehavior::class,];
    }

    public static function tableName()
    {
        return 'chat';
    }

    /**
     * Правила валидации данных модели
     * @return array
     */
    public function rules()
    {
        return [

            [['user_id'], 'integer'],
            [['message'], 'string', 'min' => 1, 'max' => 160],
            [['message'], 'required'],

            [['blocked'], 'boolean'],
        ];
    }

    /**
     * Названия полей модели
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'user_id' => 'Пользователь',
            'message' => 'Сообщение',
            'blocked' => 'Некорректное',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата бана',
        ];
    }

    /**
     * Магический метод для получение зависимого объекта из БД
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getDate() {
        return Yii::$app->formatter->format($this->created_at, 'datetime');
    }

    public function getRole($getId) {
        $roles = Yii::$app->authManager->getRolesByUser($getId);
        $result = ArrayHelper::getColumn($roles, 'name',false);
        return $result[0];
    }


}