<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_fee".
 *
 * @property integer $id_fee
 * @property integer $fee
 */
class TbFee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_fee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fee'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_fee' => 'Id Fee',
            'fee' => 'Fee',
        ];
    }
}
