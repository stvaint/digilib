<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_prospektus".
 *
 * @property integer $id_prospektus
 * @property string $barcode
 * @property string $company
 * @property string $bisnis
 * @property string $tanggal
 * @property string $content
 * @property string $remarks
 * @property string $legal_advisor
 * @property string $address
 * @property string $datetime_entry
 * @property string $attachment
 */
class TbProspektus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_prospektus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company', 'barcode', 'tanggal'], 'required'],
            [['company', 'bisnis', 'content', 'remarks', 'legal_advisor', 'address', 'attachment'], 'string'],
            [['tanggal', 'datetime_entry'], 'safe'],
            [['barcode'], 'string', 'max' => 100],
        ];

        // return [
        //     [['company', 'barcode', 'tanggal'], 'required'],
        //     [['company', 'bisnis', 'content', 'remarks', 'legal_advisor', 'address', 'attachment'], 'string'],
        //     [['tanggal', 'datetime_entry'], 'safe'],
        //     [['barcode'], 'string', 'max' => 100],
        // ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_prospektus' => 'Id Prospektus',
            'barcode' => 'Title',
            'company' => 'Author',
            'bisnis' => 'Subject',
            'tanggal' => 'Edition',
            'content' => 'Notes',
            // 'remarks' => 'Remarks',
            // 'legal_advisor' => 'Legal Advisor',
            // 'address' => 'Address',
            'datetime_entry' => 'Datetime Entry',
            // 'attachment' => 'Attachment',
        ];
    }
}
