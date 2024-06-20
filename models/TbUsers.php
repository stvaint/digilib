<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_users".
 *
 * @property integer $id
 * @property integer $level
 * @property string $uname
 * @property string $upassword
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $lastlogin
 */
class TbUsers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level'], 'integer'],
            [['lastlogin'], 'safe'],
            [['uname', 'email'], 'string', 'max' => 50],
            [['upassword'], 'string', 'max' => 255],
            [['firstname'], 'string', 'max' => 20],
            [['lastname'], 'string', 'max' => 40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'level' => 'Level',
            'uname' => 'Uname',
            'upassword' => 'Upassword',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'email' => 'Email',
            'lastlogin' => 'Lastlogin',
        ];
    }
}
