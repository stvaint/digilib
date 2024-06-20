<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_memo".
 *
 * @property integer $id_memo
 * @property string $title
 * @property string $number
 * @property string $desc
 * @property string $author
 * @property string $date
 * @property string $keywords
 * @property string $updated
 * @property string $keywords
 * @property string $datetime_entry
 * @property string $attachment
 */
class TbMemo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_memo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['number', 'type', 'desc', 'author','keywords','attachment'], 'string'],
            [['date', 'updated','datetime_entry'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_memo' => 'Id memo',
            'title' => 'Title',
            'number' => 'Number',
            'desc' => 'Notes',
            'author' => 'Reviewer and/or Author',
            'date' => 'Year',
            'updated' => 'Last Updated/Updated by',
            'attachment' => 'Attachment',
            'keywords' => 'Keywords',
            'datetime_entry' => 'Datetime Entry',
        ];
    }
}
