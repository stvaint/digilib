<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "precedent".
 *
 * @property integer $id
 * @property integer $document_type
 * @property string $document_title
 * @property string $document_description
 * @property string $reviewer
 * @property integer $language
 * @property string $last_update
 * @property string $reviewed_by
 * @property string $keyword
 * @property string $attachment
 * @property string $datetime_entry
 */
class Precedent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'precedent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['document_type', 'document_title', 'language', 'keyword', 'datetime_entry'], 'required'],
            [['document_type', 'language','is_read_only'], 'integer'],
            [['document_description', 'keyword', 'last_update'], 'string'],
            [['last_update', 'datetime_entry'], 'safe'],
            [['document_title', 'reviewer', 'reviewed_by', 'attachment'], 'string', 'max' => 255],
            [['document_number'], 'string', 'max' => 50],
        ];
    }
	
	public function getLang(){
		$return = '';
		if($this->language == 1){
			$return='Indonesia';
		}else if($this->language == 2){
			$return='English';
		}else{
			$return = 'Bilingual';
		}
		return $return;
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'document_type' => 'Precedent Type',
            'document_number' => 'Precedent Number',
            'document_title' => 'Brief Description',
            'document_description' => 'Notes',
            'reviewer' => 'Reviewer',
            'language' => 'Language',
            'last_update' => 'Review date',
            'reviewed_by' => 'Reviewed By',
            'keyword' => 'Keyword',
            'attachment' => 'Attachment',
            'datetime_entry' => 'Datetime Entry',
        ];
    }
}
