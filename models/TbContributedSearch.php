<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TbContributed;

/**
 * TbContributedSearch represents the model behind the search form about `app\models\TbContributed`.
 */
class TbContributedSearch extends TbContributed
{
    public function rules()
    {
        return [
            [['title_article'], 'required'],
            [['title_book', 'publisher', 'date', 'edition', 'author','notes','attachment'], 'string'],
            [['date','datetime_entry'], 'safe'],
            ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = TbContributed::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
         if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $all = Yii::$app->getRequest()->getQueryParam('all');
        if($all !=''){ // jika kolom keyword diisi
            $keyword = str_replace('/\s+/','%',$all);
            $query->andFilterWhere(['like', 'upper(title_article)', strtoupper($keyword)])
                  ->orFilterWhere(['like', 'title_book', $keyword])
                  ->orFilterWhere(['like', 'author', $keyword])
                  ->orFilterWhere(['like', 'publisher', $keyword])
                  ->orFilterWhere(['like', 'edition', $keyword])
                  ->orFilterWhere(['like', 'author', $keyword])
                  ->orFilterWhere(['like', 'date', $keyword])
                  ->orderBy('datetime_entry desc');
        }else{
            $query->andFilterWhere(['like', 'title_book', $this->title_book])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'publisher', $this->publisher])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'edition', $this->edition])
            ->orderBy('datetime_entry desc');
        }
       

        return $dataProvider;
    }
}
