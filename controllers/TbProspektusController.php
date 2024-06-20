<?php

namespace app\controllers;

use Yii;
use app\models\Download;
use app\models\TbProspektus;
use app\models\TbProspektusSearch;
use app\models\Parameter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * TbProspektusController implements the CRUD actions for TbProspektus model.
 */
class TbProspektusController extends Controller
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
     * Lists all TbProspektus models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TbProspektusSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single TbProspektus model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_prospektus]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new TbProspektus model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TbProspektus;

        $model->datetime_entry = date('Y-m-d H:i:s');
        if ($model->load(Yii::$app->request->post())) {
            $attachment  = UploadedFile::getInstance($model, 'attachment');
            if(!empty($attachment->name)){
                $model->attachment = crc32(date('YmdHis'))."_______".$attachment->name;
                $path              = Parameter::find()->where(['keys'=>'url_prospektus'])->one()->value . $model->attachment;
                $attachment->saveAs($path);
            }

            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id_prospektus]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TbProspektus model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
       $model = $this->findModel($id);
        $cur = $model->attachment;
        if ($model->load(Yii::$app->request->post())) {
            $name   = TbProspektus::findOne($id);
            $link   = Parameter::find()->where(['keys'=>'url_prospektus'])->one()->value;
            $unlink = $link.$name->attachment;
            if($_FILES['TbProspektus']['name']['attachment'] != ''){
              if(!empty($name->attachment)){
                @unlink($unlink);
              }
              $attachment       = UploadedFile::getInstance($model, 'attachment');
              $model->attachment= crc32(date('YmdHis'))."____".$attachment->name;
              $path             = $link.$model->attachment;
              $attachment->saveAs($path);
                }else{
                    $model->attachment = $cur;
                }
            $name = $model;
            $name->update();
            return $this->redirect(['view', 'id' => $model->id_prospektus]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TbProspektus model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $name   = TbProspektus::findOne($id);
        $unlink = Parameter::find()->where(['keys'=>'url_prospektus'])->one()->value.$name->attachment;
        if(!empty($name->attachment)){
            @unlink($unlink);
        }
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

     public function actionBulkDelete()
    {
      $ids = $_POST['id'];

        for($i=0;$i<count($ids);$i++){
        $name   = TbProspektus::findOne($ids[$i]);
        $unlink = Parameter::find()->where(['keys'=>'url_prospektus'])->one()->value.$name->attachment;
        if(!empty($name->attachment)){
            @unlink($unlink);
        }
        $this->findModel($ids[$i])->delete();
        }
        
        return \yii\helpers\Json::encode(['rc'=>'00']);
    }

    /**
     * Finds the TbProspektus model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TbProspektus the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TbProspektus::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

     public function actionDownload($id)
    {   
        $get = explode('/',Yii::$app->getRequest()->getQueryParam('r'));
        $split = explode('-',$get[0]);
        if(count($split) == 2){
          $url = $split[1];
        }else{
          $url = $split[0];
        }
        $model = $this->findModel($id);
        if(Yii::$app->user->isGuest != ""){
            return $this->render('read', ['model' => $model,'download' => 0]);
        }else{
            $data = Download::find()
                    ->where(['MONTH(created_date)' => date("m")])
                    ->andWhere(['user_id'=> Yii::$app->user->getId()])
                    // ->andWhere(['page_id'=> $id])
                    // ->andWhere(['download_page'=> $url])
                    ->All();
            
            $extention = explode(".",$model->attachment);
            if ($extention[1] == 'pdf' || $extention[1] == 'PDF'){
              if(count($data) < 9){      
                return $this->render('read', ['model' => $model,'download' => 1]);
              }else{
                return $this->render('read', ['model' => $model,'download' => 2]);
              }
            }else{
              if(count($data) < 10){               
                  $model = new Download;
                  $model->created_date = date('Y-m-d H:i:s');
                  $model->user_id = Yii::$app->user->getId();
                  $model->page_id = $id;
                  $model->download_page = $url;
                  if($model->save()){
                    $model = $this->findModel($id);
                    $link = Parameter::find()->where(['keys'=>'library_url'])->one()->value.'Attachment_prospektus/'.$model->attachment;
                   $this->redirect($link);
                  }else{
                    echo json_encode(array("feedback"=>0, "error"=>$model->getErrors())); return;
                  }
              }else{
                echo "Maks. Download 10/Month";
              }
            }
        }
    }
    public function actionAttachment($id=null){
      $model = $this->findModel($id);
      $link   = Parameter::find()->where(['keys'=>'url_prospektus'])->one()->value;
      $unlink = $link.$model->attachment;
        if(!empty($model->attachment)){
          @unlink($unlink);
        }
      $model->attachment = "";
      $model->save();
      return $this->redirect(['update', 'id' => $model->id_prospektus]);
  }
}
