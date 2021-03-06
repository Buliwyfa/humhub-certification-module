<?php

namespace humhub\modules\certified\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AwaitingCertificationSearch represents the model behind the search form about `certified\models\AwaitingCertification`.
 */
class AwaitingCertificationSearch extends AwaitingCertification
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['created_at', 'his_picture_guid', 'her_picture_guid'], 'safe'],
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
        $query = AwaitingCertification::find();

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
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'his_picture_guid', $this->his_picture_guid])
            ->andFilterWhere(['like', 'her_picture_guid', $this->her_picture_guid]);

        return $dataProvider;
    }
}
