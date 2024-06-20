<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TbWebsite;

/**
 * TbWebsiteSearch represents the model behind the search form about `app\models\TbWebsite`.
 */
class TbWebsiteSearch extends TbWebsite
{
    public function rules()
    {
        return [
            [['id_website'], 'integer'],
            [['title', 'website', 'description', 'attachment', 'datetime_entry'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = TbWebsite::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
		
		$this->load($params);

        $keyword = "";
        if($this->description !=''){ // jika kolom keyword diisi
			$keyword = str_replace('/\s+/','%',$this->description);
			$query->andFilterWhere(['like', 'upper(title)', strtoupper($keyword)])
				  ->orFilterWhere(['like', 'website', $keyword])
				  ->orFilterWhere(['like', 'description', $keyword]);
		}else{
			$query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'website', $this->website]);
		}

        return $dataProvider;
    }
}
