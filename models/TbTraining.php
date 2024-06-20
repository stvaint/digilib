<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_training".
 *
 * @property integer $id_training
 * @property string $topic
 * @property string $tra_date
 * @property string $facilitator
 * @property string $attachment
 * @property string $datetime_entry
 */
class TbTraining extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_training';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['topic', 'facilitator', 'attachment'], 'string'],
            [['tra_date', 'datetime_entry'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_training' => 'Id Training',
            'topic' => 'Topic',
            'tra_date' => 'Tra Date',
            'facilitator' => 'Facilitator',
            'attachment' => 'Attachment',
            'datetime_entry' => 'Datetime Entry',
        ];
    }
}
