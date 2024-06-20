<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TbRegulation;

/**
 * TbRegulationSearch represents the model behind the search form about `app\models\TbRegulation`.
 */
class TbRegulationSearch extends TbRegulation
{
    public function rules()
    {
        return [
            [['id_reg', 'hits'], 'integer'],
            [['title', 'regard_ina', 'regard_eng', 'reg_number', 'year', 'subject', 'issue_date', 'issuer', 'keyword', 'source', 'note', 'last_update', 'in_view_of', 'revoke', 'revoked_by', 'ammend', 'ammend_by', 'imp_reg', 'reference', 'att_ind1', 'att_ind2', 'att_eng1', 'att_eng2', 'datetime_entry'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        //$query = TbRegulation::find();   
		$query = new \yii\db\Query;
		$tahun = "";
		$where = "id_reg!=0 ";
		$where_index = "";
		$keyword = "";
		$in_view_of = "";
		$on = "id_reg!=0 ";		
		$this->load($params);
		
		if($this->keyword !=""){
			$keyword = str_replace('/\s+/','%',$this->keyword);
			$keyword = str_replace(' ','%',$keyword);
			//$where = " AND a.title like '%".$keyword."%' ";
			$where = "  ";
			$on = "";
			$on = "  ( a.title like '%".$keyword."%' or a.keyword like '%".$keyword."%'  or a.regard_ina like '%".$keyword."%'  or a.regard_eng like '%".$keyword."%') ";
			$where_index = " OR (MATCH(DocText) AGAINST ('".$keyword."') ) ";
		}else{
			
			if($this->subject !=''){
				$on .= " AND (( a.keyword like '%".str_replace('/\s+/','%',$this->subject)."%' ) ) ";
				$where_index = "  ";
			}
		
			if($this->title !=''){
				$on .= " AND (( a.title like '%".str_replace('/\s+/','%',$this->title)."%' ) ) ";
				$where_index = "  ";
			}
			
			if($this->regard_ina !=''){ // reg Ind
				$on .= " AND (( a.regard_ina like '%".str_replace('/\s+/','%',$this->regard_ina)."%') ) ";
				$where_index = "  ";
			}
			
			if($this->regard_eng !=''){ // reg Eng
				$on .= " AND ((a.regard_eng like '%".str_replace('/\s+/','%',$this->regard_eng)."%') ) ";
				$where_index = "  ";
			}
			
			if($this->reg_number !=''){ // reg Eng
				$on .= " AND ( (a.reg_number like '%".str_replace('/\s+/','%',$this->reg_number)."%')  AND a.keyword LIKE '%".$keyword."%') ";
				$where_index = "  ";
			}

			if($this->issue_date !=''){ // date issued 
				if($this->att_ind1 =='1'){  // &
					$on .= " AND ( date_format(a.issue_date,'%Y') = '".$this->issue_date."' OR date_format(a.issue_date,'%Y') = '".$this->year."' ) ";
					$where_index = "";
				}else if($this->att_ind1 =='2'){ // contoh 2014<>2017 =>mencari dari tahun 204 s/d 2017
					$on .= " AND ( (date_format(a.issue_date,'%Y') >= '".$this->issue_date."' AND date_format(a.issue_date,'%Y') <= '".$this->year."')  )  ";
					
					$where_index = "";
				}else{
					$on .= " AND ( date_format(a.issue_date,'%Y') = '".$this->issue_date."' ) ";
					$where_index = "  ";
				}
			}
			
			if($this->in_view_of !=""){
				$in_view_of = str_replace('/\s+/','%',$this->in_view_of);
				$on .= " AND ( a.in_view_of like '%".$in_view_of."%')  ";
				$where_index = "  ";
			}
		}
		if(isset($params['sort']) && $params['sort'] == '-issue_date'){
			$query->select (['a.*'])
				->from('tb_regulation a')
				//->join('LEFT JOIN', '(select regulation_id from tblmakarimindex WHERE 1=1 '.$where_index.' ) b', 'a.id_reg=b.regulation_id '.$on)
				->where($on)
				->groupBy('a.id_reg')
				->orderBy('a.issue_date asc')
				->all();
		}else{
			$query->select (['a.*'])
				->from('tb_regulation a')
				//->join('LEFT JOIN', '(select regulation_id from tblmakarimindex WHERE 1=1 '.$where_index.' ) b', 'a.id_reg=b.regulation_id '.$on)
				->where($on)
				->groupBy('a.id_reg')
				->orderBy('a.issue_date desc')
				->all();
		}
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => ['attributes'=>['title','regard_ina','regard_eng','reg_number','issue_date']],
			'pagination' => [
				'pageSize' => 10,
			]
        ]);
	
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
		/*$query->leftJoin('tblmakarimindex i', '`tb_regulation`.`id_reg` = `i`.`regulation_id`');
        $query->andFilterWhere([
            'id_reg' => $this->id_reg,
            'year' => $this->year,
            'issue_date' => $this->issue_date,
            'datetime_entry' => $this->datetime_entry,
        ]);*/
		
		/*$query->andFilterWhere(['like', 'title', $this->keyword])
            ->orFilterWhere(['like', 'regard_ina', $this->keyword])
            ->orFilterWhere(['like', 'regard_eng', $this->keyword])
            ->orFilterWhere(['like', 'reg_number', $this->keyword])
            ->orFilterWhere(['like', 'subject', $this->keyword])
            ->orFilterWhere(['like', 'keyword', $this->keyword])
            ->orFilterWhere(['like', 'issuer', $this->keyword])
            ->orFilterWhere(['like', 'i.DocText', $this->keyword]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'regard_ina', $this->regard_ina])
            ->andFilterWhere(['like', 'regard_eng', $this->regard_eng])
            ->andFilterWhere(['like', 'reg_number', $this->reg_number])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'issuer', $this->issuer])
            ->andFilterWhere(['like', 'source', $this->source])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'last_update', $this->last_update])
            ->andFilterWhere(['like', 'in_view_of', $this->in_view_of])
            ->andFilterWhere(['like', 'revoke', $this->revoke])
            ->andFilterWhere(['like', 'revoked_by', $this->revoked_by])
            ->andFilterWhere(['like', 'ammend', $this->ammend])
            ->andFilterWhere(['like', 'ammend_by', $this->ammend_by])
            ->andFilterWhere(['like', 'imp_reg', $this->imp_reg])
            ->andFilterWhere(['like', 'reference', $this->reference])
            ->andFilterWhere(['like', 'att_ind1', $this->att_ind1])
            ->andFilterWhere(['like', 'att_ind2', $this->att_ind2])
            ->andFilterWhere(['like', 'att_eng1', $this->att_eng1])
            ->andFilterWhere(['like', 'att_eng2', $this->att_eng2]);*/

        return $dataProvider;
    }
}
