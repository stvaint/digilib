<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TbBooks;

/**
 * TbBooksSearch represents the model behind the search form about `app\models\TbBooks`.
 */
class TbBooksSearch extends TbBooks
{
    public function rules()
    {
        return [
            [['id_book', 'year', 'price'], 'integer'],
            [['call_no', 'book_id', 'title', 'author', 'subject', 'edition', 'publisher', 'isbn', 'remark', 'source', 'attachment_cover', 'attachment_ebook', 'attachment_toc', 'datetime_entry', 'comment'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = TbBooks::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

		$keyword = "";
        if($this->attachment_cover !=''){ // jika kolom keyword diisi
			$keyword = str_replace('/\s+/','%',$this->attachment_cover);
			$query->andFilterWhere(['like', 'upper(title)', strtoupper($keyword)])
				  ->orFilterWhere(['like', 'call_no', $keyword])
				  ->orFilterWhere(['like', 'subject', $keyword])
				  ->orFilterWhere(['like', 'author', $keyword])
				  ->orFilterWhere(['like', 'publisher', $keyword]);
		}else{
			if($this->year !=''){ // year
				if($this->remark =='1'){ // &
					$query->andFilterWhere(['like', 'year', $this->year]);
					$query->orFilterWhere(['like','year',$this->isbn]);
				}else if($this->remark =='2'){ // >>
					$query->andFilterWhere(['between', 'year', $this->year, $this->isbn]);
				}else{
					$query->andFilterWhere(['like', 'year', $this->year]);
				}
			}
			
			$query->andFilterWhere(['like', 'call_no', $this->call_no])
            ->andFilterWhere(['like', 'book_id', $this->book_id])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'edition', $this->edition])
            ->andFilterWhere(['like', 'publisher', $this->publisher])
            ->andFilterWhere(['like', 'isbn', $this->isbn])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'source', $this->source])
            ->andFilterWhere(['like', 'attachment_cover', $this->attachment_cover])
            ->andFilterWhere(['like', 'attachment_ebook', $this->attachment_ebook])
            ->andFilterWhere(['like', 'attachment_toc', $this->attachment_toc])
            ->andFilterWhere(['like', 'comment', $this->comment]);
		}

        

        return $dataProvider;
    }
}
