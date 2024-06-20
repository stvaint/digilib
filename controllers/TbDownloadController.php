<?php

namespace app\controllers;

use Yii;
use app\models\DownloadReal;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\TbComment;

class TbDownloadController extends Controller
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

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        // return $this->redirect(['download']);
        return $this->redirect(Yii::$app->urlManager->createUrl("download"));
    }
    protected function findModel($id)
    {
        if (($model = DownloadReal::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
