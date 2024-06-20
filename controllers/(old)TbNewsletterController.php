<?php

namespace app\controllers;

use Yii;
use app\models\TbNewsletter;
use app\models\TbNewsletterSearch;
use app\models\Parameter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\TbComment;

/**
 * TbNewsletterController implements the CRUD actions for TbNewsletter model.
 */
class TbNewsletterController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all TbNewsletter models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TbNewsletterSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single TbNewsletter model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $comment = new TbComment;
        $comment->nama = Yii::$app->user->isGuest ? 'Guest' : Yii::$app->user->identity->username;
        $comment->id_collection = $id;
        $comment->type_collection = 5;
        $allComment = TbComment::find()->where(['type_collection'=>5, 'id_collection'=>$model->id_newsletter])->orderBy('id_comment desc')->all();
        if ($comment->load(Yii::$app->request->post()) && $comment->save()) {
            return $this->redirect(['view', 'id' => $model->id_newsletter]);
        } else {
            return $this->render('view', ['model' => $model, 'allComment'=>$allComment, 'comment'=>$comment]);
        }
    }

    /**
     * Creates a new TbNewsletter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TbNewsletter;
        $model->datetime_entry = date('Y-m-d H:i:s');
        if ($model->load(Yii::$app->request->post())) {
            $attachment        = UploadedFile::getInstance($model, 'attachment');
            if(!empty($attachment->name)){
                $model->attachment = crc32(date('YmdHis'))."_".$attachment->name;
                $path              = Parameter::find()->where(['keys'=>'url_newsletter'])->one()->value . $model->attachment;
                $attachment->saveAs($path);
            }

            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id_newsletter]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
//$fileName         = crc32(date('Ymd'));
    /**
     * Updates an existing TbNewsletter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $cur = $model->attachment;
        if ($model->load(Yii::$app->request->post())) {
            $name   = TbNewsletter::findOne($id);
            $unlink = Parameter::find()->where(['keys'=>'url_newsletter'])->one()->value.$name->attachment;
            if($_FILES['TbNewsletter']['name']['attachment'] != ''){
              if(!empty($name->attachment)){
                unlink($unlink);
              }
              $attachment       = UploadedFile::getInstance($model, 'attachment');
              $model->attachment= crc32(date('YmdHis'))."_".$attachment->name;
              $path             = Parameter::find()->where(['keys'=>'url_newsletter'])->one()->value . $model->attachment;
              $attachment->saveAs($path);
                }else{
                    $model->attachment = $cur;
                }
            $name = $model;
            $name->update();
            return $this->redirect(['view', 'id' => $model->id_newsletter]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TbNewsletter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $name   = TbNewsletter::findOne($id);
        $unlink = Parameter::find()->where(['keys'=>'url_newsletter'])->one()->value.$name->attachment;
        if(!empty($name->attachment)){
            @unlink($unlink);
        }
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the TbNewsletter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TbNewsletter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TbNewsletter::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
