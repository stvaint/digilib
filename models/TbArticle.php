<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_article".
 *
 * @property integer $id_article
 * @property string $title
 * @property string $subjek
 * @property string $art_date
 * @property string $source
 * @property string $keyword
 * @property string $website
 * @property string $attachment
 * @property string $datetime_entry
 */
class TbArticle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'subjek', 'keyword','attachment'], 'string'],
            [['art_date', 'datetime_entry'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_article' => 'Id Article',
            'title' => 'Title',
            'subjek' => 'Subject',
            'art_date' => 'Issue',
            // 'source' => 'Source',
            'keyword' => 'Type',
            // 'website' => 'Website',
            'attachment' => 'Attachment',
            'datetime_entry' => 'Datetime Entry',
        ];
    }
}
