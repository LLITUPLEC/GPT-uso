<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form of `app\models\User`.
 */
class UserSearch extends User
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [  
              /**  Удалена из поиска форма Должность, т к поле integer а записи выводит string  **/
//            [['id', 'created_at', 'updated_at', 'telny_number', 'position_id'], 'integer'],
          
            [['id', 'created_at', 'updated_at', 'telny_number'], 'integer'],
            [['username', 'password_hash', 'auth_key', 'access_token', 'first_name', 'last_name', 'third_name', 'date_birth', 'date_receipt'], 'safe'],
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
        $query = User::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'telny_number' => $this->telny_number,
            'position_id' => $this->position_id,
            'date_birth' => $this->date_birth,
            'date_receipt' => $this->date_receipt,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'access_token', $this->access_token])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'third_name', $this->third_name])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
