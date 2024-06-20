<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TbCourt;

/**
 * TbCourtSearch represents the model behind the search form about `app\models\TbCourt`.
 */
class TbCourtSearch extends TbCourt
{
    public function rules()
    {
        return [
            [['id_court'], 'integer'],
            [['dec_no', 'decision_type', 'court_type', 'court_date', 'summary', 'keyword', 'subjek', 'reference', 'penggugat', 'tergugat', 'datetime_entry', 'attachment'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = TbCourt::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $all = Yii::$app->getRequest()->getQueryParam('all');
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

		$this->load($params);
		
		if($all !=''){ // jika kolom keyword diisi
			
            $keyword = '%'.str_replace(' ', '%', $all).'%';
            $qq = "upper(dec_no) like '$keyword' or court_type like '$keyword' or summary like '$keyword' or keyword like '$keyword' or reference like '$keyword' or penggugat like '$keyword' or tergugat like '$keyword' or subjek like '$keyword'";
            //var_dump($qq);die;
            $query->andWhere(new \yii\db\Expression($qq));
            /*
			$query->andFilterWhere(['like', 'upper(dec_no)', strtoupper($keyword)])
				  ->orFilterWhere(['like', 'court_type', $keyword])
				  ->orFilterWhere(['like', 'summary', $keyword])
				  ->orFilterWhere(['like', 'keyword', $keyword])
				  ->orFilterWhere(['like', 'reference', $keyword])
				  ->orFilterWhere(['like', 'penggugat', $keyword])
				  ->orFilterWhere(['like', 'tergugat', $keyword])
				  ->orFilterWhere(['like', 'subjek', $keyword]);
                  */
                  $query->orderBy('datetime_entry desc');
		}else{
			
			if($this->court_date !=''){ // year
				if($this->attachment =='1'){ // &
					$query->andFilterWhere(['like', "date_format(court_date,'%Y')", $this->court_date]);
					$query->orFilterWhere(['like',"date_format(court_date,'%Y')",$this->datetime_entry]);
				}else if($this->attachment =='2'){ // >>
					$query->andFilterWhere(['between', "date_format(court_date,'%Y')", $this->court_date, $this->datetime_entry]);
				}else{
					$query->andFilterWhere(['like', "date_format(court_date,'%Y')", $this->court_date]);
				}
			}
			
			$query->andFilterWhere(['like', 'dec_no', $this->dec_no])
            ->andFilterWhere(['like', 'court_type', $this->court_type])
            ->andFilterWhere(['like', 'penggugat', $this->penggugat])
            ->andFilterWhere(['like', 'tergugat', $this->tergugat])
            ->orderBy('datetime_entry desc');
		}

	   
        
        //echo ($query->createCommand()->getRawSql());die;
        return $dataProvider;
    }
}
