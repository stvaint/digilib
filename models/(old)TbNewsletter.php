<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_newsletter".
 *
 * @property integer $id_newsletter
 * @property string $title
 * @property string $issue
 * @property string $highlight
 * @property string $source
 * @property string $keyword
 * @property string $attachment
 * @property string $datetime_entry
 */
class TbNewsletter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_newsletter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['issue'], 'required'],
            [['title', 'issue', 'source', 'keyword', 'attachment', 'highlight', 'notes'], 'string'],
            [['datetime_entry'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_newsletter' => 'Id Newsletter',
            'title' => 'Title(s)',
            'issue' => 'Issue No.',
            'highlight' => 'Month/Year',
            'source' => 'Author',
            'keyword' => 'Keyword',
            'attachment' => 'Attachment',
            'notes' => 'Description/Notes',
            'datetime_entry' => 'Datetime Entry',
        ];
    }
}
