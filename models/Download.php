<?php

namespace app\models;

use Yii;

class Download extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $download_file;
    public $document_page;

    public static function tableName()
    {
        return 'download';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'page_id', 'download_page','created_date'], 'required'],
            [['username','email','registration_ip'], 'integer'],
        ];
    }
    public static function primaryKey()
    {
        return ['download_id'];
    }
    public function getLang(){
        $return = '';
        if($this->language == 1){
            $return='Indonesia';
        }else if($this->language == 2){
            $return='English';
        }else{
            $return = 'Bilingual';
        }
        return $return;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'download_id' => 'ID',
            'user_id' => 'Name User',
            'page_id' => 'Page ID',
            'download_page' => 'Name Page',
            'created_date' => 'Date',
            'username' => 'Username',
            'email' => 'Email',
            'registration_ip' => 'Regis IP'
        ];
    }
}
