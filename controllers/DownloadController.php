<?php

namespace app\controllers;

use Yii;
use app\models\Download;
use app\models\DownloadSearch;
use app\models\Parameter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


/**
 * DownloadController implements the CRUD actions for Download model.
 */
class DownloadController extends Controller
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
     * Lists all Download models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DownloadSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Download model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }
    
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    /**
     * Finds the Download model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Download the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Download::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionDownload($page,$id)
    {   
        if(Yii::$app->user->isGuest != ""){
            echo json_encode(array("feedback"=>0)); return;
        }else{
            $data = Download::find()
                    ->where(['MONTH(created_date)' => date("m")])
                    ->andWhere(['user_id'=> Yii::$app->user->getId()])
                    // ->andWhere(['page_id'=> $id])
                    // ->andWhere(['download_page'=> $page])
                    ->All();
            
            if(count($data) < 100){               
                $model = new Download;
                $model->created_date = date('Y-m-d H:i:s');
                $model->user_id = Yii::$app->user->getId();
                $model->page_id = $id;
                $model->download_page = $page;
                if($model->save()){
                    echo json_encode(array("feedback"=>1)); return;
                }else{
                  echo json_encode(array("feedback"=>0, "error"=>$model->getErrors())); return;
                }
            }else{
              echo json_encode(array("feedback"=>0)); return;
            }
        }
    }
}
