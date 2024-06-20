<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "parameter".
 *
 * @property integer $id
 * @property string $keys
 * @property string $value
 */
class Parameter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parameter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['keys', 'value'], 'required'],
            [['keys', 'value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'keys' => 'Keys',
            'value' => 'Value',
        ];
    }
}
