<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_guide".
 *
 * @property integer $no
 * @property string $guide
 */
class TbGuide extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_guide';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['no'], 'integer'],
            [['guide'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'no' => 'No',
            'guide' => 'Guide',
        ];
    }
}
