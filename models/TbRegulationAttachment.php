<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_regulation_attachment".
 *
 * @property integer $id
 * @property integer $reg_id
 * @property integer $type
 * @property string $attachment_name
 */
class TbRegulationAttachment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_regulation_attachment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reg_id', 'type', 'attachment_name'], 'required'],
            [['reg_id', 'type'], 'integer'],
            [['attachment_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reg_id' => 'Reg ID',
            'type' => 'Type',
            'attachment_name' => 'Attachment Name',
        ];
    }
}
