<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TbMemo;

/**
 * TbMemoSearch represents the model behind the search form about `app\models\TbMemo`.
 */
class TbMemoSearch extends TbMemo
{
    public function rules()
    {
        return [
            [['title', 'number', 'type', 'desc', 'author','keywords','attachment'], 'string'],
            [['date', 'updated','datetime_entry'], 'safe'],        
            ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = TbMemo::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $all = Yii::$app->getRequest()->getQueryParam('all');
        if($all !=''){ // jika kolom keyword diisi
			$keyword = str_replace('/\s+/','%',$all);
			$query->andFilterWhere(['like', 'upper(title)', strtoupper($keyword)])
				  ->orFilterWhere(['like', 'keywords', $keyword])
				  ->orFilterWhere(['like', 'number', $keyword])
				  ->orFilterWhere(['like', 'type', $keyword])
                  ->orFilterWhere(['like', 'desc', $keyword])
                  ->orFilterWhere(['like', 'author', $keyword])
                  ->orFilterWhere(['like', 'date', $keyword])
                  ->orderBy('datetime_entry desc');
		}else{
			$query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'keywords', $this->updated])
            ->andFilterWhere(['like', 'date', $this->date])
            ->orderBy('datetime_entry desc');
		}

        return $dataProvider;
    }
}
