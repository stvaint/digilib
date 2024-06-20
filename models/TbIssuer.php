<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_issuer".
 *
 * @property integer $id_issuer
 * @property string $issuer
 */
class TbIssuer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_issuer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['issuer'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_issuer' => 'Id Issuer',
            'issuer' => 'Issuer',
        ];
    }
}
