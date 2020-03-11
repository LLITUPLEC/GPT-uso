<?php


namespace app\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * Class Briefing
 * @package app\models
 *
 * @property int $id [int(11)]  Порядковый номер
 * @property string $title [varchar(255)]  Название инструктажа
 * @property string $type [varchar(255)]  Тип
 * @property string $date_start [varchar(255)]  Дата проведения
 * @property int $user_id [int(11)]  Кому назначен
 * @property int $position_id [int(11)]  Категория должностей
 *
 * @property-read User $user
 * @property-read Position $position
 *
 * @property int $created_at [int(11)]
 * @property int $updated_at [int(11)]
 */
class Briefing extends ActiveRecord
{
    public $info_brief = [];

    public function behaviors()
    {
        return [TimestampBehavior::class,];
    }

    public function attributeLabels()
    {
        return [
            'id' => '#',
            'title' => 'Название проверки',
            'type' => 'Тип проверки',
            'section' => 'Раздел',
            'position_id' => 'Категория должностей',
            'user_id' => 'Кому назначен',
            'date_start' => 'Дата проведения',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата последнего изменения',
        ];
    }

    public static function tableName()
    {
        return 'briefings';
    }

    public function rules()
    {
        return [
            [['title', 'date_start', 'section',], 'required'],

            [['title', 'type', 'section',], 'string'],

            [['title'], 'string', 'min' => 8, 'max' => 160],

            [['date_start',], 'date', 'format' => 'php:Y-m-d'],

            [['user_id', 'position_id'], 'integer'],

        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getPosition()
    {
        return $this->hasOne(Position::class, ['id' => 'position_id']);
    }

    /** При создании инструктажа
     * @param $brief_ID
     * @param $user_id
     * @param $position_id
     * @throws Exception
     */
    public function infoBriefInsert($brief_ID, $user_id, $position_id)
    {
        $usersForBrief = [];

        /** Поиск конкретного человека по ID. Должность указана или == ВСЕ */
        if ($user_id != 555) {
            $sqlUser = "SELECT users.id
                    FROM `users` 
                    LEFT JOIN `briefings` 
                    ON users.id = briefings.user_id
                    WHERE users.id = {$user_id}";
            $array = \Yii::$app->db->createCommand($sqlUser)->query();
            foreach ($array as $value) {
                $usersForBrief[] = $value['id'];
            }
            /** Поиск группы по указанной должности. ID работника == ВСЕ */
        } elseif ($user_id == 555 && $position_id != 13) {
            $sqlPosition = "SELECT users.id
                        FROM `users` 
                        LEFT JOIN `briefings` 
                        ON users.id = briefings.user_id
                        WHERE users.position_id = {$position_id}";
            $array = \Yii::$app->db->createCommand($sqlPosition)->queryAll();
            foreach ($array as $value) {
                $usersForBrief[] = $value['id'];
            }
            /** ID работника == ВСЕ, Должности == ВСЕ */
        } elseif ($user_id == 555 && $position_id == 13) {
            $usersForBrief = User::$totalUserID;
        }

        /** Выбранному массиву работников устанавливаем уведомления
            в зависимости от того, новая запись или update имеющейся */
        foreach ($usersForBrief as $value) {
            $sql0 = "SELECT `status` FROM `info_brief` WHERE `id_user` = {$value} AND `id_brief` = {$brief_ID}";
            $query0 = \Yii::$app->db->createCommand($sql0)->query();
            $query = [];
            foreach ($query0 as $item => $value0) {
                $query[] = $value0;
            }
            if ($query == null) {
                $sql = "INSERT INTO `info_brief` (`id_user`, `id_brief`, `status`) VALUES ({$value},{$brief_ID}, 0)";
                \Yii::$app->db->createCommand($sql)->execute();
                $sql2 = "UPDATE `users` SET `i_instr` = `i_instr` +1 WHERE `id` = {$value}";
                \Yii::$app->db->createCommand($sql2)->execute();
            } else {
                $status = null;
                foreach ($query as $value1) {
                    $status = $value1['status'];
                }
                if ($status == 1) {
                    $sql = "UPDATE `info_brief` SET `status` = null WHERE `id_user` = {$value} AND `id_brief` = {$brief_ID}";
                    \Yii::$app->db->createCommand($sql)->execute();
                    $sql2 = "UPDATE `users` SET `i_instr` = `i_instr` +1 WHERE `id` = {$value}";
                    \Yii::$app->db->createCommand($sql2)->execute();
                } else {
                    continue;
                }
            }
        }
    }

    /** При удалении инструктажа Администратором
     * @param $brief_ID
     * @throws Exception
     */
    public function infoBriefDelete($brief_ID)
    {
        (new User)->findAllUsersID();
        foreach (User::$totalUserID as $value) {
            $sql1 = \Yii::$app->db->createCommand("SELECT `status` FROM `info_brief` WHERE `id_user` = {$value} AND `id_brief` = {$brief_ID}")->query();
            foreach ($sql1 as $val => $item) {
                if ($item['status'] == 1) {
                    break;
                } else {
                    $sql2 = "UPDATE `users` SET `i_instr` = `i_instr` -1 WHERE `id` = {$value}";
                    \Yii::$app->db->createCommand($sql2)->execute();
                }
            }
        }
        $sql3 = "DELETE FROM `info_brief` WHERE id_brief = {$brief_ID}";
        \Yii::$app->db->createCommand($sql3)->execute();
    }

    /** Действия при ознакомлении с инструктажём (метка "ознакомлен")
     * @param $brief_ID
     * @throws Exception
     */
    public static function markBriefRead($brief_ID)
    {
        $user_ID = \Yii::$app->user->id;

        $sql = \Yii::$app->db->createCommand("SELECT `status` FROM `info_brief` WHERE `id_user` = {$user_ID} AND `id_brief` = {$brief_ID}")->query();

        if ($sql == null) {
            return;
        } else {
            foreach ($sql as $value => $item) {
                if ($item['status'] != 1) {
                    $sql1 = "UPDATE `info_brief` SET `status` = 1 WHERE `id_user` = {$user_ID} AND `id_brief` = {$brief_ID}";
                    \Yii::$app->db->createCommand($sql1)->execute();

                    $sql2 = "UPDATE `users` SET `i_instr` = `i_instr` -1 WHERE `id` = {$user_ID}";
                    \Yii::$app->db->createCommand($sql2)->execute();
                }
            }
        }
    }

    /** Для отображения в ЛК
     * @param $user_ID
     * @return array
     * @throws Exception
     */
    public function getActualBriefings($user_ID)
    {
        $sql = "SELECT briefings.id, briefings.title 
                FROM `briefings` 
                LEFT JOIN `info_brief` 
                ON briefings.id = info_brief.id_brief
                WHERE info_brief.id_user = {$user_ID} AND info_brief.status = 0";
        $query = \Yii::$app->db->createCommand($sql)->queryAll();

        foreach ($query as $item => $value) {
            $this->info_brief[] = $value;
        }
        return $this->info_brief;
    }

}