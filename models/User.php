<?php


namespace app\models;

use app\models\forms\UpdateUserForm;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\Exception;

/**
 * Class User
 * @package app\models
 *
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string $auth_key
 * @property string $access_token
 * @property int $created_at
 * @property int updated_at
 * @property string $first_name
 * @property string $last_name
 * @property string $third_name
 * @property int $telny_number
 * @property int $position_id
 * @property int $date_birth
 * @property int $date_receipt
 * @property string $status
 * @property int $i_act
 * @property int $i_doc
 * @property int $i_instr
 *
 * @property-read Position $position
 *
 * @property-write $password -> setPassword()
 */
class User extends ActiveRecord implements IdentityInterface
{
    public static $countInfo;
    public static $totalUserID;

    public function behaviors()
    {
        return [TimestampBehavior::class,];
    }

    public function attributeLabels()
    {
        return [
            'id' => '#',
            'username' => 'Логин',
            'password_hash' => 'Пароль',
            'auth_key' => 'Ключ авторизации',
            'access_token' => 'Токен доступа',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата последнего изменения',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'third_name' => 'Отчество',
            'telny_number' => 'Табельный номер',
            'position_id' => 'Должность',
            'date_birth' => 'Дата рождения',
            'date_receipt' => 'Дата устройства',
            'status' => 'активность',
            'email' => 'Электронная почта',
            'i_act' => 'Новых событий',
            'i_doc' => 'Новых документов',
            'i_instr' => 'Новых инструктажей',
        ];
    }

    public static function tableName()
    {
        return 'users';
    }

    public function rules()
    {
        return [
            [['username'], 'unique', 'targetClass' => User::className(), 'targetAttribute' => 'username'],
            [['username', 'first_name', 'last_name', 'third_name', 'telny_number',
                'position_id', 'date_birth', 'date_receipt',], 'required'],
            [['username', 'first_name', 'last_name', 'third_name', 'position_id'], 'string'],
            [['telny_number'], 'unique', 'targetClass' => User::className(), 'targetAttribute' => 'telny_number'],
            [['username'], 'string', 'min' => 3],
            ['date_receipt', 'validateDate'],
        ];
    }

    public static function findIdentity($id)
    {
        return self::findOne(['id' => $id]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key == $authKey;
    }

    public static function findByUsername($username)
    {
        return self::findOne(['username' => $username]);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function getPosition()
    {
        return $this->hasOne(Position::class, ['id' => 'position_id']);
    }

    public function validateDate($attr) // date_receipt
    {
        $birth = strtotime($this->date_birth);
        $receipt = strtotime($this->{$attr});

        if ($birth && $receipt) {
            if ($receipt < strtotime('+18 years', $birth)) {
                $this->addError($attr, 'Некорректный формат даты');
            }
        }
    }

    public function getFullName()
    {
        return $this->last_name . ' ' . $this->first_name;
    }

    public static function getCountInfo()
    {
        $ID_user = Yii::$app->user->id;
        $sql = Yii::$app->db->createCommand("SELECT SUM(`i_act` + `i_doc` + `i_instr`) FROM `users` WHERE `id` = {$ID_user}")->queryAll();
        $count = $sql[0]['SUM(`i_act` + `i_doc` + `i_instr`)'];
        if ($count == (null || 0)) {
            self::$countInfo = null;
        } else {
            self::$countInfo = $count;
        }
    }

    public function findAllUsersID()
    {
        $allID = [];
        $sql = Yii::$app->db->createCommand("SELECT `id` FROM `users` WHERE 1")->queryAll();
        foreach ($sql as $item => $id) {
            $allID[] = $id['id'];
        }
        self::$totalUserID = $allID;
    }

    public function newUserSetInfo($user_ID)
    {
        /** info_action */
        $sql1 = "SELECT `id`, `title` FROM `activities`";
        $query1 = Yii::$app->db->createCommand($sql1)->queryAll();
        $action_result = [];
        foreach ($query1 as $item) {
            $action_result[] = $item;
        }
        foreach ($action_result as $item => $value) {
            $sql1_2 = "INSERT INTO `info_action` (`id_user`, `id_action`, `status`) 
                        VALUES ({$user_ID}, {$value['id']}, 0)";
            \Yii::$app->db->createCommand($sql1_2)->execute();
            $sql1_3 = "UPDATE `users` SET `i_act` = `i_act` +1 WHERE users.id = $user_ID";
            \Yii::$app->db->createCommand($sql1_3)->execute();
        }


        /** info_doc */
        $sql2 = "SELECT `id`, `title` FROM `files`";
        $query2 = Yii::$app->db->createCommand($sql2)->queryAll();
        $doc_result = [];
        foreach ($query2 as $item) {
            $doc_result[] = $item;
        }
        foreach ($doc_result as $item => $value) {
            $sql2_2 = "INSERT INTO `info_doc` (`id_user`, `id_doc`, `status`) 
                        VALUES ({$user_ID}, {$value['id']}, 0)";
            \Yii::$app->db->createCommand($sql2_2)->execute();
            $sql2_3 = "UPDATE `users` SET `i_doc` = `i_doc` +1 WHERE users.id = $user_ID";
            \Yii::$app->db->createCommand($sql2_3)->execute();
        }

        /** info_brief */
        $sql3 = "SELECT briefings.id, briefings.title 
                    FROM `briefings` 
                    LEFT JOIN `users` ON briefings.user_id = users.id 
                    WHERE briefings.position_id = 
                    (SELECT users.position_id FROM `users` WHERE users.id = {$user_ID})";
        $query3 = Yii::$app->db->createCommand($sql3)->queryAll();
        $brief_result = [];
        foreach ($query3 as $item) {
            $brief_result[] = $item;
        }
        foreach ($brief_result as $item => $value) {
            $sql3_2 = "INSERT INTO `info_brief` (`id_user`, `id_brief`, `status`) 
                        VALUES ({$user_ID}, {$value['id']}, 0)";
            \Yii::$app->db->createCommand($sql3_2)->execute();
            $sql3_3 = "UPDATE `users` SET `i_instr` = `i_instr` +1 WHERE users.id = $user_ID";
            \Yii::$app->db->createCommand($sql3_3)->execute();
        }

    }

}