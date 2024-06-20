<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TbProspektus;

/**
 * TbProspektusSearch represents the model behind the search form about `app\models\TbProspektus`.
 */
class TbProspektusSearch extends TbProspektus
{
    public function rules()
    {
        return [
            [['id_prospektus'], 'integer'],
            [['barcode', 'company', 'bisnis', 'tanggal', 'content', 'remarks', 'legal_advisor', 'address', 'datetime_entry', 'attachment'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = TbProspektus::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $all = Yii::$app->getRequest()->getQueryParam('all');
        if($all !=''){ // jika kolom keyword diisi
            $keyword = str_replace('/\s+/','%',$all);
			$query->andFilterWhere(['like', 'upper(barcode)', strtoupper($keyword)])
				  ->orFilterWhere(['like', 'bisnis', $keyword])
				  ->orFilterWhere(['like', 'content', $keyword])
				  ->orFilterWhere(['like', 'legal_advisor', $keyword])
                  ->orFilterWhere(['like', 'address', $keyword])
                  ->orFilterWhere(['like', 'tanggal', $keyword])
                  ->orFilterWhere(['like', 'company', $keyword])
                  ->orderBy('tanggal ASC');
		}else{
			$query->andFilterWhere(['like', 'barcode', $this->barcode])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'tanggal', $this->tanggal])
            ->andFilterWhere(['like', 'company', $this->company])
            ->orderBy('tanggal ASC');
		}

        return $dataProvider;
    }
}
