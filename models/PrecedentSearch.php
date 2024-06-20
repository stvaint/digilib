<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Precedent;

/**
 * PrecedentSearch represents the model behind the search form about `app\models\Precedent`.
 */
class PrecedentSearch extends Precedent
{
    public function rules()
    {
        return [
            [['id', 'document_type', 'language'], 'integer'],
            [['document_title','document_number', 'document_description', 'reviewer', 'last_update', 'reviewed_by', 'keyword', 'attachment', 'datetime_entry'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Precedent::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
		
		$all = Yii::$app->getRequest()->getQueryParam('all');
        if($all !=''){ // jika kolom keyword diisi
            $keyword = str_replace('/\s+/','%',$all);
			$query->andFilterWhere(['like', 'document_title', $keyword])
                ->orFilterWhere(['like', 'document_description', $keyword])
                ->orFilterWhere(['like', 'document_number', $keyword])
                ->orFilterWhere(['like', 'keyword', $keyword]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'document_type' => $this->document_type,
            'document_number' => $this->document_number,
        ]);

        $query->andFilterWhere(['like', 'document_title', $this->document_title])
            ->andFilterWhere(['like', 'document_description', $this->document_description])
            ->andFilterWhere(['like', 'document_number', $this->document_number])
            ->andFilterWhere(['like', 'keyword', $this->keyword]);
        return $dataProvider;
    }
}
