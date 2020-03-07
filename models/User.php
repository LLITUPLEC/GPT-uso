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
 *
 * @property-read Position $position
 *
 * @property-write $password -> setPassword()
 */
class User extends ActiveRecord implements IdentityInterface
{

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
}