<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Parameter;
use yii\data\SqlDataProvider;
use app\models\SearchHistory;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
	public function actionGuide()
    {

        return $this->render('guide');
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
	
	function spellCheck($v){
		$tags = 'id_ID';
		$tags2 ='en_US';
		$r = enchant_broker_init();
		$rs = [];
		$indo = [];
		$eng = [];
		//indo
				$d = enchant_broker_request_dict($r, $tags);
				$dprovides = enchant_dict_describe($d);
				$wordcorrect = enchant_dict_check($d, $v);
				if (!$wordcorrect) {
					$suggs = enchant_dict_suggest($d, $v);
					$sugest2 = count($suggs) > 2 ? $suggs[1] : $suggs[0];
					array_push($indo,$suggs[0], $sugest2);
				}else{
					array_push($indo,$v, $v);
				}
				$indos = [
					'status'=>$wordcorrect,
					'result'=>$indo
				];
		//english
				$d2 = enchant_broker_request_dict($r, $tags2);
				$dprovides2 = enchant_dict_describe($d2);
				$wordcorrect2 = enchant_dict_check($d2, $v);
				if (!$wordcorrect2) {
					$suggs2 = enchant_dict_suggest($d2, $v);
					$sugest22 = count($suggs2) > 2 ? $suggs2[1] : $suggs2[0];
					array_push($eng,$suggs2[0], $sugest22);
				}else{
					array_push($eng,$v, $v);
				}
				$engs = [
					'status'=>$wordcorrect2,
					'result'=>$eng
				];
				array_push($rs, $indos, $engs);
		return $rs;
	}
	
	public function actionSearch($q='',$page=1,$year=null, $c="off", $tb="", $title="", $category="" ,$ys="",$op="",$ye="",$status="all",$ref="", $sort = "all"){
		$q = $q == '' ? ' ' : trim($q);
		$url = Parameter::find()->where(['keys'=>'library'])->one()->value;
		$k = str_ireplace('"', '', $q);
		
		$searcHistory = SearchHistory::find()->orderby('hits desc')->limit(10)->all();
        if($k != ''){
            $chist = SearchHistory::find()->where(['keyword'=>$k]);
            if($chist->count() != 0){
                $hist = $chist->one();
                $hist->hits = $hist->hits + 1;
                $hist->save();
            }else{
                $hist = new SearchHistory;
                $hist->keyword = $k;
                $hist->hits = 1;
                $hist->save();
            }
        }
		
		$sugs = [];
		
		$keys = explode(" ", trim(str_replace(",","",$k)));
		$keysug = '';
		$keysug2 = '';
		$keysug3 = '';
		$keysug4 = '';
		$kcount = count($keys);
		foreach($keys as $ke=>$v):
			//array_push($sugs, $this->spellCheck($v));
			$h = $this->spellCheck($v);
			if($h[0]['status'] == true || $h[1]['status'] == true){
				$keysug .= $v.' ';
				$keysug2 .= $v.' ';
				$keysug3 .= $v.' ';
				$keysug4 .= $v.' ';
			}else{
				$keysug .= $h[0]['result'][0].' ';
				$keysug2 .= $h[0]['result'][1].' ';
				$keysug3 .= $h[1]['result'][0].' ';
				$keysug4 .= $h[1]['result'][1].' ';
			}
		endforeach;
		if($k!=trim($keysug)){
			if($keysug == $keysug2){
				array_push($sugs, $keysug);
			}else{
				array_push($sugs, $keysug,$keysug2);
			}
			if($keysug3 == $keysug4){
				array_push($sugs, $keysug3);
			}else{
				array_push($sugs, $keysug3,$keysug4);
			}
		}
		$kon = "";
		if(strpos(trim($k), ' -') !== false){
			$kw = explode(" ", $k);
			for($i=0;$i<count($kw);$i++){
				if(strpos(trim($kw[$i]), '-') === false){
					$kon .= $kw[$i].' ';
				}
			}
		}else{
			$kon=$k;
		}
		//var_dump($kon);die;
		$keyword = str_replace(' ', '%', trim($kon));
		
		$key_nana = explode(" ",trim($kon));
		$keyword_nana = " ";
		//var_dump($keyword_nana);die;
		$ct="";
		if($category != ''){
			$ct =  "and r.category like '%".$category."%' ";
		}
		//for($k=0;$k<count($key_nana);$k++){
		//	$keyword_nana .= $ct." and ( (r.title like '%".$keyword."%' or r.regard_ina like '%".$keyword."%' or r.regard_eng like '%".$keyword."%' or r.reg_number like '%".$keyword."%' or r.subject like '%".$keyword."%' or r.issuer like '%".$keyword."%' or r.keyword like '%".$keyword."%' or r.year like '%".$keyword."%' or r.in_view_of like '%".$keyword."%' or r.issue_date = STR_TO_DATE('".$keyword."', '%d-%m-%Y'))) ";
		//}
		
		for($k=0;$k<count($key_nana);$k++){
			$keyword_nana .= $ct."  and ( (r.title like '%".$key_nana[$k]."%' or r.regard_ina like '%".$key_nana[$k]."%' or r.regard_eng like '%".$key_nana[$k]."%' or r.subject like '%".$key_nana[$k]."%' or r.keyword like '%".$key_nana[$k]."%' or r.year like '%".$key_nana[$k]."%' or r.issue_date = STR_TO_DATE('".$key_nana[$k]."', '%d-%m-%Y'))) ";
		}
		
		
		if(strpos(trim($q), '"') !== false){
			$keyword = $kon;
			$m = "DocText like '%".$kon."%'";
		}else{
			$keyword = str_replace(' ', '%', $kon);
			$m = "MATCH (DocText) AGAINST ('".$kon."')";
		}
		$tahun="";
		if($ys != "" && $ye != ""){
			if($op=="or"){
				$tahun = "and (YEAR(r.issue_date)='".$ys."' or YEAR(r.issue_date)='".$ye."')";
			}else{
				$tahun = "and (YEAR(r.issue_date) between '".$ys."' and '".$ye."')";
			}
		}else if($ys == "" && $ye != ""){
			$tahun = "and YEAR(r.issue_date)='".$ye."'";
		}else if($ys != "" && $ye == ""){
			$tahun = "and YEAR(r.issue_date)='".$ys."'";
		}
		$st = "";
		if($status == "valid"){
			$st =" and (trim(revoked_by) = '' OR trim(revoked_by) is NULL) ";
		}else if($status == "not"){
			$st =" and trim(revoked_by) != ''";
		}
			
		switch($tb){
			case "regulation":
				$link = $url."/index.php?r=tb-regulation/view&id=";
				
				if($c=="off"){
					$query = "select id_reg, keyword, title, regard_ina, regard_eng as in_view_of,reference, subject, YEAR(issue_date) as `year`,att_ind1, i.doctext as DocText from (select regulation_id,doctext from tblmakarimindex where ".$m.") i 
		right join tb_regulation r on r.id_reg=i.regulation_id 
		where 1=1 ".$keyword_nana." ".$tahun." ".$st;
				}else{
					$query = "select id_reg, keyword, title, regard_ina, regard_eng as in_view_of,reference, subject, YEAR(issue_date) as `year`,att_ind1, i.doctext as DocText from (select regulation_id,doctext from tblmakarimindex where ".$m.") i 
		left join tb_regulation r on r.id_reg=i.regulation_id";
				}
		
			break;
			case "court":
				$link = $url."/index.php?r=tb-court%2Fview&id=";
				$th="";
				if($ys != "" && $ye != ""){
					if($op=="or"){
						$th = "and (YEAR(court_date)='".$ys."' or YEAR(court_date)='".$ye."')";
					}else{
						$th = "and (YEAR(court_date) between '".$ys."' and '".$ye."')";
					}
				}else if($ys == "" && $ye != ""){
					$th = "and YEAR(court_date)='".$ye."'";
				}else if($ys != "" && $ye == ""){
					$th = "and YEAR(court_date)='".$ys."'";
				}
				$query = "select id_court as id_reg, keyword, concat('penggugat : ',penggugat,', tergugat : ', tergugat) as title, summary as regard_ina, keyword as in_view_of,reference as DocText, reference, subjek as subject, YEAR(court_date) as `year`, attachment as att_ind1 from tb_court where 1=1 and (penggugat like '%".$keyword."%' or tergugat like '%".$keyword."%' or summary like '%".$keyword."%' or keyword like '%".$keyword."%' or subjek like '%".$keyword."%') ".$th;
			break;
			case "article":
				$link = $url."/index.php?r=tb-article%2Fview&id=";
				$th = "";
				if($ys != "" && $ye != ""){
					if($op=="or"){
						$th = "and (YEAR(art_date)='".$ys."' or YEAR(art_date)='".$ye."')";
					}else{
						$th = "and (YEAR(art_date) between '".$ys."' and '".$ye."')";
					}
				}else if($ys == "" && $ye != ""){
					$th = "and YEAR(art_date)='".$ye."'";
				}else if($ys != "" && $ye == ""){
					$th = "and YEAR(art_date)='".$ys."'";
				}
				$query = "select id_article as id_reg, keyword, '' as reference, title, subjek as subject, YEAR(art_date) as `year`, keyword as regard_ina, website as in_view_of, '' as DocText, attachment as att_ind1 from tb_article where 1=1 and (title like '%".$keyword."%' or subjek like '%".$keyword."%' or keyword like '%".$keyword."%') ".$th;
			break;
			case "newsletter":
				$link = $url."/index.php?r=tb-newsletter%2Fview&id=";
				$query = "select id_newsletter as id_reg, keyword, '' as reference, title, source as subject, '' as `year`, highlight as regard_ina, keyword as in_view_of, attachment as att_ind1, '' as DocText from tb_newsletter where title like '%".$keyword."%' or source like '%".$keyword."%' or keyword like '%".$keyword."%' or highlight like '%".$keyword."%'";
			break;
			case "website":
				$link = $url."/index.php?r=precedent%2Fview&id=";
				$query = "select id as id_reg, keyword, document_description as reference, document_title as title, document_description as subject, YEAR(last_update) as `year`, document_description as regard_ina, document_description as in_view_of, attachment as att_ind1, '' as DocText from precedent where document_title like '%".$keyword."%' or document_description like '%".$keyword."%' or keyword like '%".$keyword."%'";
			break;
			case "seminar":
				$th = "";
				if($ys != "" && $ye != ""){
					if($op=="or"){
						$th = "and (YEAR(tgl_satu)='".$ys."' or YEAR(tgl_satu)='".$ye."')";
					}else{
						$th = "and (YEAR(tgl_satu) between '".$ys."' and '".$ye."')";
					}
				}else if($ys == "" && $ye != ""){
					$th = "and YEAR(tgl_satu)='".$ye."'";
				}else if($ys != "" && $ye == ""){
					$th = "and YEAR(tgl_satu)='".$ys."'";
				}
				$link = $url."/index.php?r=tb-seminar-baru%2Fview&id=";
				$query = "select id_seminar as id_reg, '' as keyword, '' as reference, seminar as title, venue as subject, YEAR(tgl_satu) as `year`, isi_seminar as regard_ina, panitia as in_view_of, attachment as att_ind1,'' as DocText from tb_seminar_baru where 1=1 and (seminar like '%".$keyword."%' or venue like '%".$keyword."%' or panitia like '%".$keyword."%' or isi_seminar like '%".$keyword."%') ".$th;
			break;
			case "book":
				$th = "";
				if($ys != "" && $ye != ""){
					if($op=="or"){
						$th = "and (`year`=".$ys." or `year`=".$ye.")";
					}else{
						$th = "and (`year` between ".$ys." and ".$ye.")";
					}
				}else if($ys == "" && $ye != ""){
					$th = "and `year`=".$ye;
				}else if($ys != "" && $ye == ""){
					$th = "and `year`=".$ys;
				}
				$link = $url."/index.php?r=tb-books%2Fview&id=";
				$query = "select id_book as id_reg, '' as keyword, '' as reference, title, subject, `year`, author as regard_ina, publisher as in_view_of, attachment_toc as att_ind1,'' as DocText from tb_books where 1=1 and (title like '%".$keyword."%' or subject like '%".$keyword."%' or author like '%".$keyword."%' or publisher like '%".$keyword."%') ".$th;
			break;
			case "prospektus":
				$th = "";
				if($ys != "" && $ye != ""){
					if($op=="or"){
						$th = "and (YEAR(tanggal)='".$ys."' or YEAR(tanggal)='".$ye."')";
					}else{
						$th = "and (YEAR(tanggal) between '".$ys."' and '".$ye."')";
					}
				}else if($ys == "" && $ye != ""){
					$th = "and YEAR(tanggal)='".$ye."'";
				}else if($ys != "" && $ye == ""){
					$th = "and YEAR(tanggal)='".$ys."'";
				}
				$link = $url."/index.php?r=tb-prospektus%2Fview&id=";
				$query = "select id_prospektus as id_reg, '' as keyword, '' as reference, company as title, bisnis as subject, YEAR(tanggal) as `year`, content as regard_ina, legal_advisor as in_view_of, address as DocText, attachment as att_ind1 from tb_prospektus where 1=1 and (company like '%".$keyword."%' or bisnis like '%".$keyword."%' or content like '%".$keyword."%' or legal_advisor like '%".$keyword."%' or address like '%".$keyword."%') ".$th;
			break;
			default:
				$link = $url."/index.php?r=tb-regulation/view&id=";
				
				if($c=="off"){
					$query = "select id_reg, keyword, title, regard_ina, regard_eng as in_view_of,reference, subject, YEAR(issue_date) as `year`,att_ind1, i.doctext as DocText from (select regulation_id,doctext from tblmakarimindex where ".$m.") i 
		right join tb_regulation r on r.id_reg=i.regulation_id 
		where 1=1 ".$keyword_nana." ".$tahun." ".$st;
		}else{
					$query = "select id_reg, keyword, title, regard_ina, regard_eng as in_view_of,reference, subject, YEAR(issue_date) as `year`,att_ind1, i.doctext as DocText from (select regulation_id,doctext from tblmakarimindex where ".$m.") i 
		INNER join tb_regulation r on r.id_reg=i.regulation_id";
				}
			break;
		}
		
		if($title != "" || $ref != ""){
			if(strpos(trim($title), '"') !== false){
				$t = str_replace('"','',$title);
			}else{
				$t = str_replace(' ', '%',str_replace('"', '', $title));
			}
			if(strpos(trim($ref), '"') !== false){
				$r = str_replace('"','',$ref);
			}else{
				$r = str_replace(' ', '%',str_replace('"', '', $ref));
			}
			$ttl = $title == ""? "" : " and a.title like '%".$t."%'";
			$rfq = $ref == ""? "" : " and a.keyword like '%".$r."%'";
			
			$query = "select * from (".$query.") a where 1=1 ".$ttl.$rfq.$st;
		}
		if(strpos(trim($k), ' -') !== false){
			$query .= " AND( 1=1 ";
			$kw = explode(" ", $k);
			for($i=0;$i<count($kw);$i++){
				$ii = 0;
				if(strpos(trim($kw[$i]), '-') === 0){
					$kwr = str_replace('-','',$kw[$i]);
					$kwr .= '%'.$kwr.'%';
						$query .= " AND keyword NOT LIKE ('%".$kwr."%') ";
					$query .= " AND title NOT LIKE ('%".$kwr."%') ";
					$query .= " AND subject NOT LIKE ('%".$kwr."%') ";
					$query .= " AND DocText NOT LIKE ('%".$kwr."%') ";
					$query .= " AND in_view_of NOT LIKE ('%".$kwr."%') ";
					$query .= " AND regard_ina NOT LIKE ('%".$kwr."%') ";
				}
			}
			//$kwr2 .= $kwr.' ';
					/* $kwr2 = '%'.str_replace(' ', '%',trim($kwr2)).'%';
					$query .= " AND keyword NOT LIKE ('".$kwr2."') ";
					$query .= " AND title NOT LIKE ('".$kwr2."') ";
					$query .= " AND subject NOT LIKE ('".$kwr2."') ";
					$query .= " AND DocText NOT LIKE ('".$kwr2."') ";
					$query .= " AND in_view_of NOT LIKE ('".$kwr2."') ";
					$query .= " AND regard_ina NOT LIKE ('".$kwr2."') ";
					$query .= " AND reference NOT LIKE ('".$kwr2."') "; */
			$query .= ")"; //nutup 
		}
		//var_dump($query);die;
		$sorting = "";
		if($sort == "asc"){
			//$sorting = " order by year asc";
			$sorting = " order by year desc";
		//}else if($sort == "desc"){
		}else{
			$sorting = " order by year desc";
			//$sorting = " order by year desc";
		}
		//var_dump("select * from (".$query." group by id_reg) b ".$sorting." limit ".($page == 1 ? 0 : ($page * 10)).",10");die;
		$data = Yii::$app->db->createCommand("select * from (".$query." group by id_reg) b ".$sorting." limit ".($page == 1 ? 0 : ($page * 10)).",10")->queryAll();
		$jjj = Yii::$app->db->createCommand($query." group by id_reg limit 1000")->queryAll();
		$jmlData = (round(count($jjj)/10))-1;
		$jdt = count($jjj) == 1000 ? "<p style='margin:0 0 -11px 0;padding:0;'><i>Found : more than 1000 Regulations</i></p>" : "<p style='margin:0 0 -11px 0;padding:0;'><i>Found : ". count($jjj) ." Regulations</i></p>";
		return $this->render('search',['searcHistory'=>$searcHistory, 'data'=>$data, 'jdt'=>$jdt, 'page'=>$page,'q'=>$q, 'c'=>$c, 'year'=>$year, 'tb'=>$tb, 'sugs'=>$sugs, 'link'=>$link, 'jmlData'=>$jmlData, 'title'=>$title , 'category'=>$category, 'ys'=>$ys,'op'=>$op,'ye'=>$ye,'status'=>$status,'ref'=>$ref, 'sort'=>$sort]);
	}
}
