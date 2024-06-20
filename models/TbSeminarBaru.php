<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_seminar_baru".
 *
 * @property integer $id_seminar
 * @property string $barcode
 * @property string $call_no
 * @property string $seminar
 * @property string $venue
 * @property string $panitia
 * @property string $tgl_satu
 * @property string $tgl_dua
 * @property string $isi_seminar
 * @property string $datetime_entry
 * @property string $attachment
 */
class TbSeminarBaru extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_seminar_baru';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['seminar'], 'required'],
            [['seminar', 'venue', 'panitia', 'isi_seminar', 'barcode', 'call_no'], 'string'],
            [['tgl_satu', 'datetime_entry'], 'safe'],
            // [['barcode'], 'string', 'max' => 30],
            // [['call_no'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_seminar' => 'Id Seminar',
            'barcode' => 'Title',
            'call_no' => 'Participants',
            'seminar' => 'Keyword',
            'venue' => 'Venue',
            'panitia' => 'Hosted By',
            'tgl_satu' => 'Date',
            // 'tgl_dua' => 'End Date',
            'isi_seminar' => 'Notes',
            'datetime_entry' => 'Datetime Entry',
            // 'attachment' => 'Attachment',
        ];
    }
}
