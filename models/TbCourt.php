<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_court".
 *
 * @property integer $id_court
 * @property string $dec_no
 * @property string $decision_type
 * @property string $court_type
 * @property string $court_date
 * @property string $summary
 * @property string $keyword
 * @property string $subjek
 * @property string $reference
 * @property string $penggugat
 * @property string $tergugat
 * @property string $datetime_entry
 * @property string $attachment
 */
class TbCourt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_court';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dec_no', 'keyword', 'court_date'], 'required'],
            [['decision_type', 'court_type', 'summary', 'keyword', 'subjek', 'penggugat', 'tergugat', 'attachment','link'], 'string'],
            [['court_date', 'reference', 'datetime_entry'], 'safe'],
            [['dec_no'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_court' => 'Id Court',
            'dec_no' => 'Case Number',
            'decision_type' => 'Court Ruling',
            'court_type' => 'Type of Case',
            'court_date' => 'Decision Date',
            'summary' => 'Summary',
            'keyword' => 'Keyword',
            'subjek' => 'Court Level',
            'reference' => 'Reference',
            'penggugat' => 'Plainttiff',
            'tergugat' => 'Defendant',
            'datetime_entry' => 'Datetime Entry',
            'attachment' => 'Attachment',
            'link' => 'Website',
        ];
    }
}
