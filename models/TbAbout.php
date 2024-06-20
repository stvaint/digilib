<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_about".
 *
 * @property string $about
 */
class TbAbout extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_about';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['about'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'about' => 'About',
        ];
    }
}
