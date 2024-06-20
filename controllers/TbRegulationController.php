<?php

namespace app\controllers;

use Yii;
use app\models\Download;
use app\models\TbRegulation;
use app\models\TbRegulationSearch;
use app\models\TbRegulationAttachment;
use app\models\Tblmakarimindex;
use app\models\Parameter;
use app\models\TbComment;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use harrytang\fineuploader\FineuploaderHandler;
use yii\helpers\Json;


/**
 * TbRegulationController implements the CRUD actions for TbRegulation model.
 */
class TbRegulationController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all TbRegulation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TbRegulationSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
		
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    /**
     * Displays a single TbRegulation model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $hits = $model->hits == '' ? 0 : $model->hits;
        $model->hits = $hits + 1;
        $model->save();
        
        $comment = new TbComment;
        $comment->nama = Yii::$app->user->isGuest ? 'Guest' : Yii::$app->user->identity->username;
        $comment->id_collection = $id;
        $comment->type_collection = 2;
        
        $allComment = TbComment::find()->where(['type_collection'=>2, 'id_collection'=>$model->id_reg])->orderBy('id_comment desc')->all();

        if ($comment->load(Yii::$app->request->post()) && $comment->save()) {
            return $this->redirect(['view', 'id' => $model->id_reg]);
        } else {
            return $this->render('view', ['model' => $model, 'allComment'=>$allComment, 'comment'=>$comment]);
        }

    }

    /**
     * Creates a new TbRegulation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionJsonsubject($search = NULL)
    {
        header('Content-type: application/json');
        $clean['more'] = false;
        $query = (new \yii\db\Query());
        if(!is_Null($search))
        {
            $mainQuery = $query->select('subjek as id, subjek AS text')
                ->from('tb_subjek2')
                ->where('UPPER(subjek) LIKE "%'.strtoupper($search).'%" limit 10');

            $command = $mainQuery->createCommand();
            $rows = $command->queryAll();
            $clean['results'] = array_values($rows);
        }
        else
        {           
            $clean['results'] = [['id'=> '','text' => 'None found']];
        }
        echo Json::encode($clean);
        exit();
    }
    public function actionJsonlist($search = NULL)
    {
        header('Content-type: application/json');
        $clean['more'] = false;
        $query = (new \yii\db\Query());
        if(!is_Null($search))
        {
            $search = str_replace(" ","%", $search);
            $mainQuery = $query->select('title as id, title AS text')
                ->from('tb_regulation')
                // ->where('UPPER(title) LIKE "%'.strtoupper($search).'%" order by title asc limit 10');
                ->where('title LIKE "%'.$search.'%" limit 10');
				

            $command = $mainQuery->createCommand();
            $rows = $command->queryAll();
            if (count($rows) > 0){
                $clean['results'] = array_values($rows);
            }else{
                $clean['results'] = [['id'=> '1','text' => 'Create New']];
            }
        }
        // else
        // {           
        //     $clean['results'] = [['id'=> '','text' => 'None found']];
        // }
        echo Json::encode($clean);
        exit();
    }
    public function actionCreate()
    {
        $model = new TbRegulation();
		$model->created_by = Yii::$app->user->getID();
		$model->create_date = date('Y-m-d H:i:s');
		$model->datetime_entry = date('Y-m-d H:i:s');
        // echo var_dump($model); return;
        if ($model->load(Yii::$app->request->post())) {
		    $cektitle = TbRegulation::find()->where(['title'=>$model->title])->count();
            if ($cektitle == 0){
    			if(!empty($model->in_view_of)){
                $model->in_view_of = implode(',', $model->in_view_of);
    			}
    			/*
    			if(!empty($model->revoke)){
                $model->revoke = implode(',', $model->revoke);
    			}
    			if(!empty($model->revoked_by)){
                $model->revoked_by = implode(',', $model->revoked_by);
    			}
    			if(!empty($model->ammend)){
                $model->ammend = implode(',', $model->ammend);
    			}
    			if(!empty($model->ammend_by)){
                $model->ammend_by = implode(',', $model->ammend_by);
    			}
    			if(!empty($model->imp_reg)){
                $model->imp_reg = implode(',', $model->imp_reg);
    			}
    			if(!empty($model->reference)){
                $model->reference = implode(',', $model->reference);
    			}
    			*/
                if($model->save()){
        			if($model->att_ind1 != ''){
                        $a = Yii::$app->PDF2Text;
        				$ind = explode(",",$model->att_ind1);
        				foreach($ind as $key=>$val){
                            // $a->setFilename('http://mts-lib02.makarim.com/Attachment_reg_ind/'.$value['attachment_name']);
                            // $a->decodePDF();
                            // $haha = $a->output();
                            // $model = new Tblmakarimindex;
                            // $model->regulation_id = $value['reg_id'];
                            // $model->DocName = $value['attachment_name'];
                            // $model->DocText = $haha == '' ? '-' : $haha; 
                            // if ($model->DocText != "-"){
                            //     echo $model->DocText; return;
                            // }

                            $attind = new TbRegulationAttachment;
                            $attind->reg_id = $model->id_reg;
                            $attind->type = 1;
                            $attind->attachment_name = $val;
        					$attind->save();
                            $extention = explode(".",$val);
                            if ($extention[1] == 'pdf' || $extention[1] == 'PDF'){
                                $a->setFilename('http://mts-lib02.makarim.com/Attachment_reg_ind/'.$val); 
                                $a->decodePDF();
                                $haha = $a->output();

            					$tbindex = new Tblmakarimindex;
            					$tbindex->DocName = $val;
                                $tbindex->DocText = $haha == '' ? '-' : $haha;
            					$tbindex->regulation_id = $model->id_reg;
            					$tbindex->save();
                                $fname = explode('/', $val);
                                $ha = count($fname) == 2 ? $fname[1] : $fname[0];
                                $ext = explode('.', $ha);
                                if(count($ext) >= 2){
                                    $pesan = $ha;
                                }else{
                                    $pesan = $val;
                                }
                                if($tbindex->DocText == '-'){
                                    echo $pesan." -> OCR Conversion -> <b>Unsuccesful OCR</b>. <i> Please Convert your PDF to OCR </i></br>";
                                }else{
                                    echo $pesan." -> OCR Conversion -> <b>Successful OCR</b>.<br/>";
                                }
                            }
        				}
        			}
        			if($model->att_eng1 != ''){
                        $a = Yii::$app->PDF2Text;
        				$eng = explode(",",$model->att_eng1);
        				foreach($eng as $key=>$val){
        					$atteng = new TbRegulationAttachment;
        					$atteng->reg_id = $model->id_reg;
        					$atteng->type = 2;
        					$atteng->attachment_name = $val;
        					$atteng->save();

                            $extention = explode(".",$val);
                            if ($extention[1] == 'pdf' || $extention[1] == 'PDF'){
            					$a->setFilename('http://mts-lib02.makarim.com/Attachment_reg_eng/'.$val); 
            					$a->decodePDF();
                                $haha = $a->output();

            					$tbindex = new Tblmakarimindex;
            					$tbindex->DocName = $val;
                                $tbindex->DocText = $haha == '' ? '-' : $haha;
            					$tbindex->regulation_id = $model->id_reg;
            					$tbindex->save();
                                $fname = explode('/', $val);
                                $ha = count($fname) == 2 ? $fname[1] : $fname[0];
                                $ext = explode('.', $ha);
                                if(count($ext) >= 2){
                                    $pesan = $ha;
                                }else{
                                    $pesan = $val;
                                }
                                if($tbindex->DocText == '-'){
                                    echo $pesan." -> OCR Conversion -> <b>Unsuccesful OCR</b>. <i> Please Convert your PDF to OCR </i></br>";
                                }else{
                                    echo $pesan." -> OCR Conversion -> <b>Successful OCR</b>.<br/>";
                                }
                            }
        				}
        			}
                    echo "<br/><br/>Your <b>PDF Attachment</b> Has Been Uploaded Successful & <b>VERIFIED as OCR</b> <br/>";
                    echo "<a href='index.php?r=tb-regulation'> Back to Regulation </a>";

                    return $this->redirect(['view', 'id' => $model->id_reg]);
                }else{
                    var_dump($model->getErrors());die;
                }
            }else{
             return "Title already exist!";
            }
        } else {
            
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TbRegulation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $reg=null)
    {
        $model = $this->findModel($id);
        if ($reg == "ind"){
            $attachind = $model->att_ind1;
            $model->att_ind1 = "";
        }elseif ($reg == "eng"){
            $attacheng = $model->att_eng1;
            $model->att_eng1 = "";
        }
        $model->in_view_of = explode(',', $model->in_view_of);
		/*
        $model->revoke = explode(',', $model->revoke);
        $model->revoked_by = explode(',', $model->revoked_by);
        $model->ammend = explode(',', $model->ammend);
        $model->ammend_by = explode(',', $model->ammend_by);
        $model->imp_reg = explode(',', $model->imp_reg);
        $model->reference = explode(',', $model->reference);
		*/
        if ($model->load(Yii::$app->request->post())) {
   //          if(!empty($model->in_view_of)){
   //          $model->in_view_of = implode(',', $model->in_view_of);
			// }
            // $query = TbRegulationAttachment::findOne($model->id_reg);
            // if($attachind == $model->att_ind1){
            //     $model->att_ind1 = $attachind;
            // }
            // if($attacheng == $model->att_eng1){
            //     $model->att_eng1 = $attacheng;
            // }
            $name = TbRegulation::findOne($model->id_reg);
            if(!empty($model->title)){
                $name->title = $model->title;
            }
            if(!empty($model->regard_ina)){
                $name->regard_ina = $model->regard_ina;
            }
            // if(!empty($model->regard_eng)){
                $name->regard_eng = $model->regard_eng;
            // }
            if(!empty($model->reg_number)){
                $name->reg_number = $model->reg_number;
            }
            if(!empty($model->category)){
                $name->category = $model->category;
            }
            if(!empty($model->year)){
                $name->year = $model->year;
            }
            if(!empty($model->subject)){
                $name->subject = $model->subject;
            }
            if(!empty($model->issue_date)){
                $name->issue_date = $model->issue_date;
            }
            if(!empty($model->issuer)){
                $name->issuer = $model->issuer;
            }
            if(!empty($model->keyword)){
                $name->keyword = $model->keyword;
            }
            // if(!empty($model->source)){
                $name->source = $model->source;
            // }
            $name->note = $model->note;
            if(!empty($model->last_update)){
                $name->last_update = $model->last_update;
            }
            // if(!empty($model->in_view_of)){
                $name->in_view_of = implode(',', $model->in_view_of);
            // }
            // if(!empty($model->revoke)){
                // $name->revoke = implode(',', $model->revoke);
            // }
            $name->revoke = $model->revoke;
            
            // if(!empty($model->revoked_by)){
                $name->revoked_by = $model->revoked_by;
            // }
            // if(!empty($model->ammend)){
                $name->ammend = $model->ammend;
            // }
            // if(!empty($model->ammend_by)){
                $name->ammend_by = $model->ammend_by;
            // }
            // if(!empty($model->imp_reg)){
                $name->imp_reg = $model->imp_reg;
            // }
            // if(!empty($model->reference)){
                $name->reference = $model->reference;
            // }
            $ind = "";
            if(!empty($model->att_ind1)){
                $a = Yii::$app->PDF2Text;
                $ind = explode(",",$model->att_ind1);
                foreach($ind as $key=>$val){
                    $attind = new TbRegulationAttachment;
                    $attind->reg_id = $model->id_reg;
                    $attind->type = 1;
                    $attind->attachment_name = $val;
                    $attind->save();

                    $extention = explode(".",$val);
                    if ($extention[1] == 'pdf' || $extention[1] == 'PDF'){
                        $a->setFilename('http://mts-lib02.makarim.com/Attachment_reg_ind/'.$val); 
                        $a->decodePDF();
                        $haha = $a->output();

                        $tbindex = new Tblmakarimindex;
                        $tbindex->DocName = $val;
                        $tbindex->DocText = $haha == '' ? '-' : $haha;
                        $tbindex->regulation_id = $model->id_reg;
                        $tbindex->save();
                        $fname = explode('/', $val);
                        $ha = count($fname) == 2 ? $fname[1] : $fname[0];
                        $ext = explode('.', $ha);
                        if(count($ext) >= 2){
                            $pesan = $ha;
                        }else{
                            $pesan = $val;
                        }
                        if($tbindex->DocText == '-'){
                            echo $pesan." -> OCR Conversion -> <b>Unsuccesful OCR</b>. <i> Please Convert your PDF to OCR </i></br>";
                        }else{
                            echo $pesan." -> OCR Conversion -> <b>Successful OCR</b>.<br/>";
                        }
                        $ind = $val;
                        $name->att_ind1 = $val;
                    }
                }
            }
            // if(!empty($model->att_ind2)){
              echo  $name->att_ind2 = $model->att_ind2;
            // }
            $eng = "";
            if(!empty($model->att_eng1)) {
                $a = Yii::$app->PDF2Text;
                $eng = explode(",",$model->att_eng1);
                foreach($eng as $key=>$val){
                    $atteng = new TbRegulationAttachment;
                    $atteng->reg_id = $model->id_reg;
                    $atteng->type = 2;
                    $atteng->attachment_name = $val;
                    $atteng->save();

                    $extention = explode(".",$val);
                    if ($extention[1] == 'pdf' || $extention[1] == 'PDF'){
                        $a->setFilename('http://mts-lib02.makarim.com/Attachment_reg_eng/'.$val); 
                        $a->decodePDF();
                        $haha = $a->output();

                        $tbindex = new Tblmakarimindex;
                        $tbindex->DocName = $val;
                        $tbindex->DocText = $haha == '' ? '-' : $haha;
                        $tbindex->regulation_id = $model->id_reg;
                        $tbindex->save();
                        $fname = explode('/', $val);
                        $ha = count($fname) == 2 ? $fname[1] : $fname[0];
                        $ext = explode('.', $ha);
                        if(count($ext) >= 2){
                            $pesan = $ha;
                        }else{
                            $pesan = $val;
                        }
                        if($tbindex->DocText == '-'){
                            echo $pesan." -> OCR Conversion -> <b>Unsuccesful OCR</b>. <i> Please Convert your PDF to OCR </i></br>";
                        }else{
                            echo $pesan." -> OCR Conversion -> <b>Successful OCR</b>.<br/>";
                        }
                        $eng = $val;
                        $name->att_eng1 = $val;
                    }
                }
            }
            // if(!empty($model->att_eng2)) {
            $name->att_eng2 = $model->att_eng2;
            // }
            // if(!empty($model->datetime_entry)){
                $name->datetime_entry = $model->datetime_entry;
            // }
            // if(!empty($model->hits)){
                $name->hits = $model->hits;
            // }
                // echo "Test"; return;

            $name->update();
                if ($ind != "" || $eng != ""){
                    echo "<br/><br/>Your Form Data & <b>PDF Attachment</b> Has Been Uploaded Successful & <b>VERIFIED as OCR</b> <br/>";
                    echo "<a href='index.php?r=tb-regulation%2Fview&id=".$model->id_reg."'> Back to Regulation </a>";
                return;
                }
            return $this->redirect(['view', 'id' => $model->id_reg]);
            
            // }else{
            //     echo "Error";
            // }
            // }else{
            //     var_dump($model->getErrors());die;
            // }
       //      if($model->save()){
    			// TbRegulationAttachment::deleteAll('reg_id='.$model->id_reg);

    			// if($model->att_ind1 != ''){
        //             $a = Yii::$app->PDF2Text;
    				// $ind = explode(",",$model->att_ind1);
    				// foreach($ind as $key=>$val){
    				// 	$attind = new TbRegulationAttachment;
    				// 	$attind->reg_id = $model->id_reg;
    				// 	$attind->type = 1;
    				// 	$attind->attachment_name = $val;
    				// 	$attind->save();

        //                 $a->setFilename('http://mts-lib02.makarim.com/Attachment_reg_ind/'.$val); 
        //                 $a->decodePDF();
        //                 $haha = $a->output();

        //                 $tbindex = new Tblmakarimindex;
        //                 $tbindex->DocName = $val;
        //                 $tbindex->DocText = $haha == '' ? '-' : $haha;
        //                 $tbindex->regulation_id = $model->id_reg;
        //                 if($tbindex->save()){
        //                     echo "Success Uploaded Indonesia";
        //                 }
    				// }
    			// }
    			// if($model->att_eng1 != ''){
       //              $a = Yii::$app->PDF2Text;
    			// 	$eng = explode(",",$model->att_eng1);
    			// 	foreach($eng as $key=>$val){
    			// 		$atteng = new TbRegulationAttachment;
    			// 		$atteng->reg_id = $model->id_reg;
    			// 		$atteng->type = 2;
    			// 		$atteng->attachment_name = $val;
    			// 		$atteng->save();

       //                  $a->setFilename('http://mts-lib02.makarim.com/Attachment_reg_eng/'.$val); 
       //                  $a->decodePDF();
       //                  $haha = $a->output();

       //                  $tbindex = new Tblmakarimindex;
       //                  $tbindex->DocName = $val;
       //                  $tbindex->DocText = $haha == '' ? '-' : $haha;
       //                  $tbindex->regulation_id = $model->id_reg;
       //                  if($tbindex->save()){
       //                      echo "Success Uploaded English";
       //                  }

       //              }
    			// }
       //          return $this->redirect(['view', 'id' => $model->id_reg]);
       //      }else{
       //          var_dump($model->getErrors());die;
       //      }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TbRegulation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		Tblmakarimindex::deleteAll(['regulation_id'=>$id]);
		$this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionBulkDelete()
    {
        $ids = $_POST['id'];

        for($i=0;$i<count($ids);$i++){
        Tblmakarimindex::deleteAll(['regulation_id'=>$ids[$i]]);
        $this->findModel($ids[$i])->delete();
        }
        
        return \yii\helpers\Json::encode(['rc'=>'00']);
    }
	
	// public function actionChecknomor(){
	// 	$model = TbRegulation::find()->where(['reg_number'=>$_POST['reg_number']])->count();
	// 	return \yii\helpers\Json::encode(['hasil'=>$model]);
	// }

    public function actionChecktitle(){
        $model = TbRegulation::find()->where(['title'=>$_POST['title']])->count();
        return \yii\helpers\Json::encode(['hasil'=>$model]);
    }

    /**
     * Finds the TbRegulation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TbRegulation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TbRegulation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionUploading()
    {
        $uploader = new FineuploaderHandler();
        $uploader->allowedExtensions = ['jpeg', 'jpg', 'png', 'bmp', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'rar']; // all files types allowed by default
        $uploader->sizeLimit = 51200000;
        $uploader->inputName = "qqfile"; // matches Fine Uploader's default inputName value by default
        //$uploader->chunksFolder = "chunks";
        if (Yii::$app->request->isPost) {
            // upload file
            $result = $uploader->handleUpload(Parameter::find()->where(['keys'=>'url_ind'])->one()->value);
            if (isset($result['success']) && $result['success'] == true) {
                // do something more
            }
            echo json_encode($result);            
        }
    }
	
	public function actionUploading2()
    {
        $uploader = new FineuploaderHandler();
        $uploader->allowedExtensions = ['jpeg', 'jpg', 'png', 'bmp', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'rar']; // all files types allowed by default
        $uploader->sizeLimit = 51200000;
        $uploader->inputName = "qqfile"; // matches Fine Uploader's default inputName value by default
        //$uploader->chunksFolder = "chunks";
        if (Yii::$app->request->isPost) {
            // upload file
            $result = $uploader->handleUpload(Parameter::find()->where(['keys'=>'url_eng'])->one()->value);
            if (isset($result['success']) && $result['success'] == true) {
                // do something more
            }
            echo json_encode($result);            
        }
    }
	
	public function actionDatatitle($query){
		$query = str_replace(" ","%", $query);
		$model = TbRegulation::find()->where("title like '%".$query."%'")->limit(10)->all();
		$arr = [];
		$data = [];
		$result = [];
		foreach($model as $item):
			$arr['value'] = $item->title;
			$arr['data'] = $item->title;
			array_push($data, $arr);
		endforeach;
		$result['suggestions'] = $data;
		return \yii\helpers\Json::encode($result);
	}
	
	public function actionTest(){
		
		$a = Yii::$app->PDF2Text;
		$sql = "select reg_id,attachment_name from tb_regulation_attachment where type=1 and reg_id > 50468 and id > 76943";
		$data = Yii::$app->db->createCommand($sql)->queryAll();
        $no = 1;
		foreach($data as $value){
			$a->setFilename('http://mts-lib02.makarim.com/Attachment_reg_ind/'.$value['attachment_name']);
			$a->decodePDF();
			$haha = $a->output();
			$model = new Tblmakarimindex;
			$model->regulation_id = $value['reg_id'];
			$model->DocName = $value['attachment_name'];
			$model->DocText = $haha == '' ? '-' : $haha; 
			if ($model->DocText != "-"){
                echo $value['reg_id'];echo"<br/>";
                echo $model->DocText; return;
            }else{
                echo $no++;echo"<br/>";
            }
            // if($model->save()){
			// 	echo 'success<br />';
			// }else{
			// 	var_dump($model->getErrors());die;
			// }
		}
		
	}
	
	public function actionMigrateFile(){
		$sql = "select attachment_name from tb_regulation_attachment where type=1 and id > 76943";
		$data = Yii::$app->db->createCommand($sql)->queryAll();
		//foreach($data as $value){
			copy("http://www.pdf995.com/samples/pdf.pdf",'D:\xampp\htdocs\testind\pdf33.pdf');
		//}
	}
	
	public function actionDeleteAttachment($id, $p, $reg){
        // $query = TbRegulationAttachment::findOne($id);
            // unlink(); <--- Tidak bisa dijalankan
        // if ($reg == "ind"){
            //$test = unlink(Yii::app()->basePath."/Attachment_reg_ind/".$query->attachment_name);
          // unlink("http://" . $_SERVER['SERVER_NAME']."/Attachment_reg_ind/".$query->attachment_name);
        // }elseif($reg == "eng"){
            // unlink(Yii::app()->basePath."/Attachment_reg_eng/".$query->attachment_name);
           // unlink("http://" . $_SERVER['SERVER_NAME']."/Attachment_reg_eng/".$query->attachment_name);
        // }    
        // TbRegulationAttachment::deleteAll('reg_id='.$p);
		$model = TbRegulationAttachment::findOne($id)->delete();
        return $this->redirect(['update', 'id'=>$p, 'reg'=>$reg]);
        // return $this->redirect(['view', 'id' => $p]);
	}

    public function actionDownload($id)
    {   
        $get = explode('/',Yii::$app->getRequest()->getQueryParam('r'));
        if(Yii::$app->user->isGuest != ""){
            $model = $this->findModel($id);
            return $this->render('read', ['model' => $model]);
        }else{
            $data = Download::find()
                    ->where(['MONTH(created_date)' => date("m")])
                    ->andWhere(['user_id'=> Yii::$app->user->getId()])
                    // ->andWhere(['page_id'=> $id])
                    // ->andWhere(['download_page'=> $get[0]])
                    ->All();
            
            if(count($data) < 5){               
                $search = $this->findModel($id);
                $model = new Download;
                $model->created_date = date('Y-m-d H:i:s');
                $model->user_id = Yii::$app->user->getId();
                $model->page_id = $id;
                $model->download_page = $get[0];
                $model->download_file = $search->attachment;
                if($model->save()){
                    // return $this->redirect(['read', 'id' => $search->id]);
                    $model = $this->findModel($id);
                    return $this->render('read', ['model' => $model]);
                }
                echo print_r($model->getErrors());
            }else{
                echo "Download Maximum 5/Month";
            }
        }
    }
}
