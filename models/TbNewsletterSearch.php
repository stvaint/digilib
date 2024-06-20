<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TbNewsletter;

/**
 * TbNewsletterSearch represents the model behind the search form about `app\models\TbNewsletter`.
 */
class TbNewsletterSearch extends TbNewsletter
{
    public function rules()
    {
        return [
            [['id_newsletter'], 'integer'],
            [['title', 'issue', 'highlight', 'source', 'keyword', 'attachment', 'datetime_entry'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = TbNewsletter::find();

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
            ->orFilterWhere(['like', 'issue', $keyword])
            ->orFilterWhere(['like', 'keyword', $keyword])
            ->orFilterWhere(['like', 'highlight', $keyword])
            ->orFilterWhere(['like', 'source', $keyword])
            ->orFilterWhere(['like', 'notes', $keyword])
            ->orFilterWhere(['like', 'attachment', $keyword])
            ->orderBy('issue desc');
		}else{
			
			
			$query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'issue', $this->issue])
            ->andFilterWhere(['like', 'keyword', $this->keyword])
            ->andFilterWhere(['like', 'highlight', $this->highlight])
            ->orderBy('issue desc');
           
		}
		
        return $dataProvider;
    }
}
