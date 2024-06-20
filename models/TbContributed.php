<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_contributed".
 *
 * @property integer $id_contributed
 * @property string $title_article
 * @property string $title_book
 * @property string $publisher
 * @property string $date
 * @property string $edition
 * @property string $author
 * @property string $notes
 * @property string $datetime_entry
 * @property string $attachment
 */
class TbContributed extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_contributed';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title_article'], 'required'],
            [['title_book', 'publisher', 'date', 'edition', 'author','notes','attachment'], 'string'],
            [['date','datetime_entry'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_contributed' => 'Id Contributed',
            'title_article' => 'Title (Articles)',
            'title_book' => 'Title (Serial/Journal/Book)',
            'publisher' => 'Publisher',
            'edition' => 'Edition [Month/Year]',
            'date' => 'Date',
            'author' => 'Author(s)',
            'notes' => 'Notes',
            'attachment' => 'Attachment',
            'datetime_entry' => 'Datetime Entry',
        ];
    }
}
