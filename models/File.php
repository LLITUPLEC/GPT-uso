<?php


namespace app\models;

use app\controllers\FileUploadBehavior;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\web\UploadedFile;

/**
 * Class File
 * @package app\models
 *
 * @property int $id
 * @property string $title
 * @property int $date_in
 * @property int $number
 * @property int $created_at
 * @property int updated_at
 * @property int $category_id
 * @property string $path
 *
 * @property-read Category $category
 */
class File extends ActiveRecord
{
    public $info_docs = [];

 public function behaviors()
  {
      return [
         TimestampBehavior::class,
         [
           'class' => FileUploadBehavior::class,
           'storagePath' => '@storage',
           'uploadPath' => '/documents',
           'attributes' => ['path'],
//           'callback' => function (string $filename) {...},
         ],
      ];
  }

    public function attributeLabels()
    {
        return [
            'id' => '#',
            'title' => 'Наименование',
            'date_in' => 'Дата ввода в действие',
            'number' => 'Номер документа',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата последнего изменения',
            'category_id' => 'Категория документа',
            'path' => 'Путь к файлу',
        ];
    }

    public static function tableName()
    {
        return 'files';
    }

    public function rules()
    {
        return [
            [['title', 'date_in', 'number', 'category_id'], 'required'],
            [['title'], 'string'],
            [['number', 'category_id'], 'integer'],
            [['title'], 'string', 'min' => 5],
            [['path'], 'file', 'skipOnEmpty' => false, 'extensions' => 'jpg, pdf, pptx, docx,'],
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /** При загрузке документа
     * @param $doc_ID
     * @throws Exception
     */
    public function infoDocInsert($doc_ID)
    {
        foreach (User::$totalUserID as $value) {
            $sql0 = "SELECT `status` FROM `info_doc` WHERE `id_user` = {$value} AND `id_doc` = {$doc_ID}";
            $query0 = \Yii::$app->db->createCommand($sql0)->query();
            $query = [];
            foreach ($query0 as $item => $value0) {
                $query[] = $value0;
            }
            if ($query == null) {
                $sql = "INSERT INTO `info_doc` (`id_user`, `id_doc`, `status`) VALUES ({$value},{$doc_ID}, 0)";
                \Yii::$app->db->createCommand($sql)->execute();
                $sql2 = "UPDATE `users` SET `i_doc` = `i_doc` +1 WHERE `id` = {$value}";
                \Yii::$app->db->createCommand($sql2)->execute();
            } else {
                $status = null;
                foreach ($query as $value1) {
                    $status = $value1['status'];
                }
                if ($status == 1) {
                    $sql = "UPDATE `info_doc` SET `status` = 0 WHERE `id_user` = {$value} AND `id_doc` = {$doc_ID}";
                    \Yii::$app->db->createCommand($sql)->execute();
                    $sql2 = "UPDATE `users` SET `i_doc` = `i_doc` +1 WHERE `id` = {$value}";
                    \Yii::$app->db->createCommand($sql2)->execute();
                } else {
                    continue;
                }
            }
        }

    }

    /** При удалении документа Администратором
     * @param $doc_ID
     * @throws Exception
     */
    public function infoDocDelete($doc_ID)
    {
        (new User)->findAllUsersID();
        foreach (User::$totalUserID as $value) {
            $sql1 = \Yii::$app->db->createCommand("SELECT `status` FROM `info_doc` WHERE `id_user` = {$value} AND `id_doc` = {$doc_ID}")->query();
            foreach ($sql1 as $val => $item) {
                if ($item['status'] == 1) {
                    break;
                } else {
                    $sql2 = "UPDATE `users` SET `i_doc` = `i_doc` -1 WHERE `id` = {$value}";
                    \Yii::$app->db->createCommand($sql2)->execute();
                }
            }
        }
        $sql3 = "DELETE FROM `info_doc` WHERE id_doc = {$doc_ID}";
        \Yii::$app->db->createCommand($sql3)->execute();
    }

    /** Действия при ознакомлении с документом
     * @param $doc_ID
     * @throws Exception
     */
    public static function markDocRead($doc_ID)
    {
        $user_ID = \Yii::$app->user->id;

        $sql = \Yii::$app->db->createCommand("SELECT `status` FROM `info_doc` WHERE `id_user` = {$user_ID} AND `id_doc` = {$doc_ID}")->query();

        foreach ($sql as $value => $item) {
            if ($item['status'] != 1) {
                $sql1 = "UPDATE `info_doc` SET `status` = 1 WHERE `id_user` = {$user_ID} AND `id_doc` = {$doc_ID}";
                \Yii::$app->db->createCommand($sql1)->execute();

                $sql2 = "UPDATE `users` SET `i_doc` = `i_doc` -1 WHERE `id` = {$user_ID}";
                \Yii::$app->db->createCommand($sql2)->execute();
            }
        }
    }

    public function getActualDocs($user_ID)
    {
        $sql = "SELECT files.id, files.title 
                FROM `files` 
                LEFT JOIN `info_doc` 
                ON files.id = info_doc.id_doc
                WHERE info_doc.id_user = {$user_ID} AND info_doc.status = 0";
        $query = \Yii::$app->db->createCommand($sql)->queryAll();

        foreach ($query as $item => $value) {
            $this->info_docs[] = $value;
        }
        return $this->info_docs;
    }


}