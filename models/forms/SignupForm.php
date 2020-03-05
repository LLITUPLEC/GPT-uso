<?php

namespace app\models\forms;

use app\models\User;
use Yii;
use yii\base\Exception;
use yii\base\Model;


class SignupForm extends Model
{
    /**
     * @var int Идентификатор работника в БД
     */
    public $id;
    /**
     * @var string Логин для создания пользователя
     */
    public $username;
    /**
     * @var string Новый пароль пользователя
     */
    public $password;
    /**
     * @var int код, без которого нельзя зарегистрироваться
     */
    public $special_cod;
    /**
     * @var string Имя работника
     */
    public $first_name;
    /**
     * @var string Фамилия работника
     */
    public $last_name;
    /**
     * @var string Отчество работника
     */
    public $third_name;
    /**
     * @var int Табельный номер работника
     */
    public $telny_number;
    /**
     * @var string Должность работника
     */
    public $position_id;
    /**
     * @var int Дата рождения работника
     */
    public $date_birth;
    /**
     * @var int Дата устройства работника
     */
    public $date_receipt;

    /**
     * @var string Электронная почта
     */
    public $email;

    /**
     * Названия атрибутов формы
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'special_cod' => 'R.A.C',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'third_name' => 'Отчество',
            'telny_number' => 'Табельный номер',
            'position_id' => 'Занимаемая должность',
            'date_birth' => 'Дата рождения',
            'date_receipt' => 'Дата устройства',
            'email' => 'Электронная почта',
        ];
    }

    /**
     * Правила валидации полей формы
     * @return array
     */
    public function rules()
    {
        return [
            [['username'], 'unique', 'targetClass' => User::class, 'targetAttribute' => 'username'],
            [['username', 'password', 'first_name', 'last_name', 'third_name', 'telny_number',
                'position_id', 'date_birth', 'date_receipt', 'special_cod'], 'required'],
            [['username', 'password', 'first_name', 'last_name', 'third_name', 'position_id', 'email'], 'string'],
            [['special_cod', 'telny_number'], 'integer'],
            [['username'], 'string', 'min' => 3],
            [['password'], 'string', 'min' => 6, 'max' => 20],
            ['date_receipt', 'validateDate'],
            [['special_cod'], 'checkCod'],
        ];
    }

    /**
     * Попытка регистрации пользователя
     * @return User|null
     * @throws \Exception
     */
    public function register()
    {
        // если валидация прошла успешно
        if ($this->validate()) {
            $user = new User([
                'username' => $this->username,
                'access_token' => "{$this->username}-token",
//                'created_at' => time(),
//                'updated_at' => time(),
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'third_name' => $this->third_name,
                'telny_number' => $this->telny_number,
                'position_id' => $this->position_id,
                'date_birth' => $this->date_birth,
                'date_receipt' => $this->date_receipt,
                'email' => $this->email,
            ]);

            $user->generateAuthKey();
            $user->password = $this->password;

            if ($user->save()) {
                // назначение пользователю базовой роли User
                $auth = Yii::$app->authManager;

                $role = $auth->getRole('user');

                $auth->assign($role, $user->id);

                return $user;
//                return Yii::$app->runAction('user/index');
            }
        }

        // вернем false, если не прошла валидация
        return null;
    }

    public function checkCod($attr) // special_cod
    {
        $start = $this->special_cod;
        $end = 213131;

        if ($start && $end) {
            if ($end != $start) {
                $this->addError($attr, 'Некорректный код');
            }
        }
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

}
