<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Download;

/**
 * DownloadSearch represents the model behind the search form about `app\models\Download`.
 */
class DownloadSearch extends Download
{
    public function rules()
    {
       return [
            [['user_id', 'page_id', 'download_page', 'download_file','created_date'], 'safe'],
            [['download_id','username','email','registration_ip'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Download::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'download_id' => $this->download_id,
            'download_page' => $this->download_page,
            'download_file' => $this->download_file,
        ]);

        $query->andFilterWhere(['DATE(created_date)'=>$this->created_date]);

        $query->andFilterWhere(['like', 'download_page', $this->document_page])
            ->andFilterWhere(['like', 'download_file', $this->download_file]);

        
        return $dataProvider;
    }
}
