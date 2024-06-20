<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_regulation".
 *
 * @property integer $id_reg
 * @property string $title
 * @property string $regard_ina
 * @property string $regard_eng
 * @property string $reg_number
 * @property string $year
 * @property string $subject
 * @property string $issue_date
 * @property string $issuer
 * @property string $keyword
 * @property string $source
 * @property string $note
 * @property string $last_update
 * @property string $in_view_of
 * @property string $revoke
 * @property string $revoked_by
 * @property string $ammend
 * @property string $ammend_by
 * @property string $imp_reg
 * @property string $reference
 * @property string $att_ind1
 * @property string $att_ind2
 * @property string $att_eng1
 * @property string $att_eng2
 * @property string $datetime_entry
 */
class TbRegulation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $status;
    public static function tableName()
    {
        return 'tb_regulation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'regard_ina', 'reg_number'], 'required'],
            [['title', 'regard_ina', 'regard_eng', 'subject', 'issuer', 'keyword', 'source', 'note', 'att_ind1', 'category', 'att_eng1'], 'string'],
            [['year', 'issue_date', 'datetime_entry','in_view_of', 'revoke', 'revoked_by', 'ammend', 'ammend_by', 'imp_reg', 'reference'], 'safe'],
            [['reg_number', 'att_ind2', 'att_eng2'], 'string', 'max' => 100],
            [['last_update', 'status'], 'string', 'max' => 50],
            [['hits'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_reg' => 'Id Reg',
            'title' => 'Title',
            'regard_ina' => 'Regarding (Indonesia)',
            'regard_eng' => 'Regarding (English)',
            'reg_number' => 'Number',
            'year' => 'Year',
            'subject' => 'Subject',
            'issue_date' => 'Issuing Date',
            'issuer' => 'Issuer',
            'keyword' => 'Keyword',
            'source' => 'Source',
            'note' => 'Note',
            'last_update' => 'Last Update',
            'in_view_of' => 'In View Of',
            'revoke' => 'Revoke',
            'revoked_by' => 'Revoked By',
            'ammend' => 'Amend',
            'ammend_by' => 'Amended By',
            'imp_reg' => 'Implementing Regulation',
            'reference' => 'Reference',
            'att_ind1' => 'Op.',
            'att_ind2' => 'Att Ind2',
            'att_eng1' => 'Att Eng1',
            'att_eng2' => 'Att Eng2',
            'datetime_entry' => 'Datetime Entry',
			'status'=>'Status',
            'hits'=>'Hits'
        ];
    }
}
