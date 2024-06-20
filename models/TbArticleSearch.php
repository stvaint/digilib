<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TbArticle;

/**
 * TbArticleSearch represents the model behind the search form about `app\models\TbArticle`.
 */
class TbArticleSearch extends TbArticle
{
    public function rules()
    {
        return [
            [['id_article'], 'integer'],
            [['title', 'subjek', 'art_date', 'source', 'keyword', 'website', 'attachment', 'datetime_entry'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = TbArticle::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->load($params);
	   
	   $all = Yii::$app->getRequest()->getQueryParam('all');
        if($all !=''){ // jika kolom keyword diisi
            $keyword = str_replace('/\s+/','%',$all);
			$query->andFilterWhere(['like', 'upper(title)', strtoupper($keyword)])
				  ->orFilterWhere(['like', 'subjek', $keyword])
				  ->orFilterWhere(['like', 'source', $keyword])
				  ->orFilterWhere(['like', 'keyword', $keyword])
				  ->orFilterWhere(['like', 'website', $keyword]);
		}else{
			if($this->art_date !=''){ // year
				if($this->attachment =='1'){ // &
					$query->andFilterWhere(['like', "date_format(art_date,'%Y')", $this->art_date]);
					$query->orFilterWhere(['like',"date_format(art_date,'%Y')",$this->datetime_entry]);
				}else if($this->attachment =='2'){ // >>
					$query->andFilterWhere(['between', "date_format(art_date,'%Y')", $this->art_date, $this->datetime_entry]);
				}else{
					$query->andFilterWhere(['like', "date_format(art_date,'%Y')", $this->art_date]);
				}
			}
			
			$query->andFilterWhere(['like', 'source', $this->source])
            ->andFilterWhere(['like', 'title', $this->title]);
		}

        return $dataProvider;
    }
}
