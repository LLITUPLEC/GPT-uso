<?php

namespace app\models;

use app\modules\testing\models\Test;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Result
 * @package app\models
 *
 * @property int $id
 * @property int $test_id
 * @property int $user_id
 * @property int $date_test
 * @property int $attempts
 * @property int $quantity
 * @property bool $status
 * @property int $userID
 *
 * @property-read User $user
 * @property-read Test $test
 */
class Result extends ActiveRecord
{

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['date_test'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['date_test'],
                ],
            ],
        ];
    }

    public static $testArray = [];
    public static $testNames = [];
    public static $testComplete = [];
    public static $testForUser = [];
    public static $firstID;
    public static $countUsersCompleteTest;
    public static $countUsers;


    /**
     * @return int
     */
    public static function getUserID(): int
    {
        return Yii::$app->user->id;
    }

    public static function tableName()
    {
        return 'results';
    }

    public function attributeLabels()
    {
        return [
            'id' => '№',
            'test_id' => 'Номер теста',
            'user_id' => 'Тестируемый',
            'date_test' => 'Дата прохождения',
            'attempts' => 'Количество попыток',
            'quantity' => 'Количество баллов',
            'status' => 'Результат(сдал?)',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getTest()
    {
        return $this->hasOne(Test::class, ['id' => 'test_id']);
    }

    /* Проверка наличия записи в БД  */
    public static function ifRecord($testID)
    {
        $userID = self::getUserID();

        $sql = "SELECT * FROM results WHERE user_id = {$userID} AND test_id = {$testID}";
        $result = Yii::$app->db->createCommand($sql)->execute();
        if ($result == false) {
            $recordTest = new Result();
            $recordTest->test_id = $testID;
            $recordTest->user_id = $userID;
            $recordTest->attempts = 1;
            $recordTest->quantity = 0;
            $recordTest->status = 0;

            $recordTest->save();
        }
    }

    /* Получение данных из БД о тесте  */
    static public function getUserTestStatus($id)
    {
        $userID = self::getUserID();

        $sql = "SELECT * FROM results WHERE test_id = {$id} AND user_id = {$userID}";
        $result = Yii::$app->db->createCommand($sql)->query();
        if ($result) {
            $data = [];
            foreach ($result as $key => $res) {
                $data['test_id'] = $res['test_id'];
                $data['user_id'] = $res['user_id'];
                $data['quantity'] = $res['quantity'];
                $data['status'] = $res['status'];
                $data['attempts'] = $res['attempts'];
            }
            return $data;
        } else {
            return false;
        }
    }

    /* Если тест не сдан  */
    public static function testNotCompleted($id, $data)
    {
        $userID = self::getUserID();

        $recordTest = Result::findOne(['test_id' => $id, 'user_id' => $userID]);
        $recordTest->attempts++;
        $recordTest->quantity = $data;
        $recordTest->save();
    }

    /* Кол-во пользователей, прошедших тест  */
    public static function getCountUsersCompleteTest($id)
    {
        $sql = Yii::$app->db->createCommand("
        SELECT COUNT(*) 
        FROM `results` 
        WHERE `test_id` = {$id} AND `status` = 1")->queryAll();

        foreach ($sql as $value => $item) {
            self::$countUsersCompleteTest = $item['COUNT(*)'];
        }
    }

    /* Общее кол-во пользователей в статусе USER  */
    public static function getCountUsers()
    {
        $sql = Yii::$app->db->createCommand("
            SELECT COUNT(*) FROM `users` 
            LEFT JOIN auth_assignment 
            ON users.id = auth_assignment.user_id 
            WHERE auth_assignment.item_name = 'user'")->queryAll();

        foreach ($sql as $value => $item) {
            self::$countUsers = $item['COUNT(*)'];
        }

        /* Обновляем кол-во пользователей в `tests` на актуальное  */
        $total = self::$countUsers;
        $sql2 = "UPDATE `tests` SET `total` = {$total} WHERE `closed` = 0";
        Yii::$app->db->createCommand($sql2)->execute();
    }

    /* Если тест сдан  */
    public static function testCompleted($id, $data)
    {
        $userID = self::getUserID();

        $recordTest = Result::findOne(['test_id' => $id, 'user_id' => $userID]);
        $recordTest->attempts++;
        $recordTest->quantity = $data;
        $recordTest->status = 1;
        $recordTest->save();

        if (Yii::$app->user->can('user')) {
            $passedPlus = Test::findOne(['id' => $id]);
            $passedPlus->passed++;
            $passedPlus->save();
        }

        self::getCountUsersCompleteTest($id);
        self::getCountUsers();

        /* При совпадении, архивируем тест  */
        if (self::$countUsersCompleteTest === self::$countUsers) {
            $sql = "UPDATE `tests` SET `closed`= 1 WHERE id = {$id}";
            Yii::$app->db->createCommand($sql)->execute();

            /* Удаляем из Results у всех пользователей неактуальный тест */
            $sql2 = "DELETE FROM `results` WHERE `test_id` = {$id} AND `status` = 1";
            Yii::$app->db->createCommand($sql2)->execute();
        }
    }

    /* Получаем массив с актуальными тестами (ID, name)  */
    public static function actualTests()
    {
        $sql = Yii::$app->db->createCommand("SELECT `id`, `name` FROM tests WHERE `closed` = 0")
            ->queryAll();

        foreach ($sql as $testID) {
            self::$testArray[] = $testID['id'];

            self::$testNames[] = $testID;
        }
    }

    /* Находим ID сданных тестов  */
    public static function userTestComplete()
    {
        $userID = self::getUserID();

        $sql1 = Yii::$app->db->createCommand(
            "SELECT `test_id` FROM results WHERE user_id = $userID AND status = 1")
            ->queryAll();
        if ($sql1 !== null) {
            foreach ($sql1 as $testID) {
                self::$testComplete[] = $testID['test_id'];
            }
        }
    }

    /* Получаем массив с тестами для сдачи (ID, name)
        и ID первого теста для отображения */
    public static function userNeedTests()
    {
        $arr = [];
        self::actualTests();
        self::userTestComplete();

        if (!empty(self::$testComplete)) {
            foreach (self::$testComplete as $val) {
                $testID[] = $val;
                $arr = array_values(array_diff(self::$testArray, $testID));
            }
        } else {
            $arr = self::$testArray;
        }

        foreach ($arr as $item => $value) {
            foreach (self::$testNames as $id => $name) {
                if ($value == $name['id']) {
                    self::$testForUser[] = $name;
                }
            }
        }

        /* Находим первый ID для отображения теста  */
        if (self::$testForUser[0]['id'] != null) {
            self::$firstID = self::$testForUser[0]['id'];
        }
    }

}