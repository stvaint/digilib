<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_website".
 *
 * @property integer $id_website
 * @property string $title
 * @property string $website
 * @property string $description
 * @property string $attachment
 * @property string $datetime_entry
 */
class TbWebsite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_website';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title', 'website', 'description', 'attachment'], 'string'],
            [['datetime_entry'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_website' => 'Id Website',
            'title' => 'Title',
            'website' => 'Website',
            'description' => 'Keyword',
            'attachment' => 'Attachment',
            'datetime_entry' => 'Datetime Entry',
        ];
    }
}
