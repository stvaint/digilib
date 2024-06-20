<?php

namespace app\controllers;

use Yii;
use app\models\TbWebsite;
use app\models\TbWebsiteSearch;
use app\models\Parameter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * TbWebsiteController implements the CRUD actions for TbWebsite model.
 */
class TbWebsiteController extends Controller
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
     * Lists all TbWebsite models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TbWebsiteSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single TbWebsite model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_website]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new TbWebsite model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TbWebsite;

        $model->datetime_entry = date('Y-m-d H:i:s');
        if ($model->load(Yii::$app->request->post())) {
			if($_FILES['TbWebsite']['name']['attachment'] != ''){
			// get the uploaded file instance. for multiple file uploads
            // the following data will return an array
            $attachment = UploadedFile::getInstance($model, 'attachment');

            // store the source file name
            $model->attachment = $attachment->name;
            //$ext = end((explode(".", $attachment->name)));

            // generate a unique file name
            //$model->attachment = Yii::$app->security->generateRandomString().".{$ext}";

            // the path to save file, you can set an uploadPath
            // in Yii::$app->params (as used in example below)
            $path = Parameter::find()->where(['keys'=>'url_website'])->one()->value . $model->attachment;
			}
            if($model->save()){
				if($_FILES['TbWebsite']['name']['attachment'] != ''){
                $attachment->saveAs($path);
				}
                return $this->redirect(['view', 'id' => $model->id_website]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TbWebsite model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $cur = $model->attachment;
        if ($model->load(Yii::$app->request->post())) {
			//var_dump(isset($_FILES['TbArticle']['name']['attachment']));die();
			if($_FILES['TbWebsite']['name']['attachment'] != ''){
				$attachment = UploadedFile::getInstance($model, 'attachment');

				// store the source file name
				$model->attachment = $attachment->name;
				//$ext = end((explode(".", $attachment->name)));

				// generate a unique file name
				//$model->attachment = Yii::$app->security->generateRandomString().".{$ext}";

				// the path to save file, you can set an uploadPath
				// in Yii::$app->params (as used in example below)
				$path = Parameter::find()->where(['keys'=>'url_website'])->one()->value . $model->attachment;
			}else{
				$model->attachment = $cur;
			}
            if($model->save()){
				if($_FILES['TbWebsite']['name']['attachment'] != ''){
                $attachment->saveAs($path);
				}
                return $this->redirect(['view', 'id' => $model->id_website]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TbWebsite model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TbWebsite model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TbWebsite the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TbWebsite::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
