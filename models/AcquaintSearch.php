<?php


namespace app\models;

use app\models\Acquaint;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AcquaintSearch represents the model behind the search form of `app\models\Acquaint`.
 */
class AcquaintSearch extends Acquaint
{
    public $user;
    public $file;
    public $number;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['user_id', 'file_id', 'created_at','number'], 'safe'],
        ];

    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        // create ActiveQuery
        $query = Acquaint::find();

        // добавим условие на выборку по пользователю, если это не админ
        if (!Yii::$app->user->can('admin')) {
            $query->andWhere(['user_id' => Yii::$app->user->id]);
        }

        // Important: lets join the query with our previously mentioned relations
        // I do not make any other configuration like aliases or whatever, feel free
        // to investigate that your self
        $query->joinWith(['user', 'file']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'validatePage' => true,
                'pageSize' => 10,
            ],
        ]);

        // Important: here is how we set up the sorting
        // The key is the attribute name on our "AcquaintSearch" instance
        $dataProvider->sort->attributes['user_id'] = [
            // The tables are the ones our relation are configured to
            'asc' => ['user.last_name' => SORT_ASC],
            'desc' => ['user.last_name' => SORT_DESC],
        ];
        // Lets do the same with file now
        $dataProvider->sort->attributes['file_id'] = [
            'asc' => ['file.title' => SORT_ASC],
            'desc' => ['file.title' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['number'] = [
            'asc' => ['file.number' => SORT_ASC],
            'desc' => ['file.number' => SORT_DESC],
        ];


//        $this->load($params);
//
//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }

        // No search? Then return data Provider
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
//            'user_id' => $this->user_id,
//            'file_id' => $this->file_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'files.title', $this->file_id])
            ->andFilterWhere(['like', 'users.last_name', $this->user_id])
            ->andFilterWhere(['like', 'files.number', $this->number]);

        return $dataProvider;
    }
}