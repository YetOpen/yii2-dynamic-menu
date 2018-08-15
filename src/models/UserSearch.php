<?php

namespace esempla\dynamicmenu\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\User\models\user;

/**
 * UserSearch represents the model behind the search form of `common\modules\User\models\user`.
 */
class UserSearch extends user
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status_id', 'admin', 'create_user_id', 'update_user_id', 'create_datetime', 'update_datetime', 'new_column'], 'integer'],
            [['username', 'password_hash', 'idnp', 'penalization', 'authid'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = user::find();

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
            'status_id' => $this->status_id,
            'admin' => $this->admin,
            'create_user_id' => $this->create_user_id,
            'update_user_id' => $this->update_user_id,
            'create_datetime' => $this->create_datetime,
            'update_datetime' => $this->update_datetime,
            'new_column' => $this->new_column,
        ]);

        $query->andFilterWhere(['ilike', 'username', $this->username])
            ->andFilterWhere(['ilike', 'password_hash', $this->password_hash])
            ->andFilterWhere(['ilike', 'idnp', $this->idnp])
            ->andFilterWhere(['ilike', 'penalization', $this->penalization])
            ->andFilterWhere(['ilike', 'authid', $this->authid]);

        return $dataProvider;
    }
}
