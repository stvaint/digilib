<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TbSeminarBaru;

/**
 * TbSeminarBaruSearch represents the model behind the search form about `app\models\TbSeminarBaru`.
 */
class TbSeminarBaruSearch extends TbSeminarBaru
{
    public function rules()
    {
        return [
            [['id_seminar'], 'integer'],
            [['barcode', 'call_no', 'seminar', 'venue', 'panitia', 'tgl_satu', 'tgl_dua', 'isi_seminar', 'datetime_entry', 'attachment'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = TbSeminarBaru::find();

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
			$query->andFilterWhere(['like', 'upper(barcode)', strtoupper($keyword)])
				  ->orFilterWhere(['like', 'call_no', $keyword])
				  ->orFilterWhere(['like', 'seminar', $keyword])
				  ->orFilterWhere(['like', 'venue', $keyword])
				  ->orFilterWhere(['like', 'isi_seminar', $keyword])
				  ->orFilterWhere(['like', 'panitia', $keyword])
                    ->orderBy('datetime_entry desc');

		}else{
			$query->andFilterWhere(['like', 'barcode', $this->barcode])
            ->andFilterWhere(['like', 'venue', $this->venue])
            ->andFilterWhere(['like', 'panitia', $this->panitia])
            ->andFilterWhere(['like', 'isi_seminar', $this->isi_seminar])
            ->orderBy('datetime_entry desc');
            
		}

        return $dataProvider;
    }
}
