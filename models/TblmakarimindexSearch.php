<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tblmakarimindex;

/**
 * TblmakarimindexSearch represents the model behind the search form about `app\models\Tblmakarimindex`.
 */
class TblmakarimindexSearch extends Tblmakarimindex
{
    public function rules()
    {
        return [
            [['ID', 'regulation_id'], 'integer'],
            [['DocName', 'DocText'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Tblmakarimindex::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'regulation_id' => $this->regulation_id,
        ]);

        $query->andFilterWhere(['like', 'DocName', $this->DocName])
            ->andFilterWhere(['like', 'DocText', $this->DocText]);

        return $dataProvider;
    }
}
