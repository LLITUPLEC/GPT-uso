<?php


namespace app\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * Класс - Событие
 *
 * @package app\models
 *
 * @property int $id [int(11)]  Порядковый номер
 * @property string $title [varchar(255)]  Название события
 * @property string $date_start [varchar(255)]  Дата начала
 * @property string $date_end [varchar(255)]  Дата окончания
 * @property int $user_id [int(11)]  Создатель события
 * @property string $description Описание события
 * @property bool $repeat [tinyint(1)]  Может ли повторяться
 * @property bool $blocked [tinyint(1)]  Блокирует ли даты
 *
 * @property-read User $user
 *
 * @property int $created_at [int(11)]
 * @property int $updated_at [int(11)]
 */
class Activity extends ActiveRecord
{

    public $info_active = [];

    public function behaviors()
    {
        return [TimestampBehavior::class,];
    }

    public static function tableName()
    {
        return 'activities';
    }

    /**
     * Правила валидации данных модели
     * @return array
     */
    public function rules()
    {
        return [
            [['title', 'date_start', 'description'], 'required'],

            [['title', 'description'], 'string'],
            [['title'], 'string', 'min' => 2, 'max' => 160],

            [['date_start', 'date_end'], 'date', 'format' => 'php:Y-m-d'],

            ['date_end', 'default', 'value' => function () {
                return $this->date_start;
            }],

            ['date_end', 'validateDate'],

            [['user_id'], 'integer'],

            [['repeat', 'blocked'], 'boolean'],
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
            'title' => 'Название',
            'date_start' => 'Дата начала',
            'date_end' => 'Дата окончания',
            'user_id' => 'Пользователь',
            'description' => 'Описание события',
            'repeat' => 'Повтор',
            'blocked' => 'Блокирующее',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата последнего изменения',
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

    /**
     * Проверка даты окончания события (не раньше даты начала)
     *
     * @param $attr
     */
    public function validateDate($attr) // date_end
    {
        $start = strtotime($this->date_start);
        $end = strtotime($this->{$attr});

        if ($start && $end) {
            if ($end < $start) {
                $this->addError($attr, 'Некорректный формат даты');
            }
        }
    }

    public function infoActionInsert($action_ID)
    {
        foreach (User::$totalUserID as $value) {
            $sql0 = "SELECT `status` FROM `info_action` WHERE `id_user` = {$value} AND `id_action` = {$action_ID}";
            $query0 = \Yii::$app->db->createCommand($sql0)->query();
            $query = [];
            foreach ($query0 as $item => $value0) {
                $query[] = $value0;
            }
            if ($query == null) {
                $sql = "INSERT INTO `info_action` (`id_user`, `id_action`, `status`) VALUES ({$value},{$action_ID}, 0)";
                \Yii::$app->db->createCommand($sql)->execute();
                $sql2 = "UPDATE `users` SET `i_act` = `i_act` +1 WHERE `id` = {$value}";
                \Yii::$app->db->createCommand($sql2)->execute();
            } else {
                $status = null;
                foreach ($query as $value1) {
                    $status = $value1['status'];
                }
                if ($status == 1) {
                    $sql = "UPDATE `info_action` SET `status` = 0 WHERE `id_user` = {$value} AND `id_action` = {$action_ID}";
                    \Yii::$app->db->createCommand($sql)->execute();
                    $sql2 = "UPDATE `users` SET `i_act` = `i_act` +1 WHERE `id` = {$value}";
                    \Yii::$app->db->createCommand($sql2)->execute();
                } else {
                    continue;
                }
            }
        }
    }

    /** При удалении события Администратором
     * @param $action_ID
     * @throws Exception
     */
    public function infoActionDelete($action_ID)
    {
        (new User)->findAllUsersID();
        foreach (User::$totalUserID as $value) {
            $sql1 = \Yii::$app->db->createCommand("SELECT `status` FROM `info_action` WHERE `id_user` = {$value} AND `id_action` = {$action_ID}")->query();
            foreach ($sql1 as $val => $item) {
                if ($item['status'] == 1) {
                    break;
                } else {
                    $sql2 = "UPDATE `users` SET `i_act` = `i_act` -1 WHERE `id` = {$value}";
                    \Yii::$app->db->createCommand($sql2)->execute();
                }
            }
        }
//        $sql3 = "DELETE FROM `info_action` WHERE id_action = {$action_ID}";
//        \Yii::$app->db->createCommand($sql3)->execute();
    }

    /** Действия при ознакомлении с событием
     * @param $action_ID
     * @throws Exception
     */
    public static function markActionRead($action_ID)
    {
        $user_ID = \Yii::$app->user->id;

        $sql = \Yii::$app->db->createCommand("SELECT `status` FROM `info_action` WHERE `id_user` = {$user_ID} AND `id_action` = {$action_ID}")->query();

        foreach ($sql as $value => $item) {
            if ($item['status'] != 1) {
                $sql1 = "UPDATE `info_action` SET `status` = 1 WHERE `id_user` = {$user_ID} AND `id_action` = {$action_ID}";
                \Yii::$app->db->createCommand($sql1)->execute();

                $sql2 = "UPDATE `users` SET `i_act` = `i_act` -1 WHERE `id` = {$user_ID}";
                \Yii::$app->db->createCommand($sql2)->execute();
            }
        }
    }

    public function getActualActivities($user_ID)
    {
        $sql = "SELECT activities.id, activities.title
                FROM `activities` 
                LEFT JOIN `info_action` 
                ON activities.id = info_action.id_action
                WHERE info_action.id_user = {$user_ID} AND info_action.status = 0";
        $query = \Yii::$app->db->createCommand($sql)->queryAll();

        foreach ($query as $item => $value) {
            $this->info_active[] = $value;
        }
        return $this->info_active;
    }

}