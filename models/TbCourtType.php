<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_court_type".
 *
 * @property integer $id_courttype
 * @property string $court_type
 */
class TbCourtType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_court_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['court_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_courttype' => 'Id Courttype',
            'court_type' => 'Court Type',
        ];
    }
}
