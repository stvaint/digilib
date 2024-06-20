<?php

namespace app\models;

use Yii;

class DownloadReal extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'tb_download';
    }

    public function rules()
    {
        return [
            [['download_page'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'download_id' => 'ID',
        ];
    }
}
