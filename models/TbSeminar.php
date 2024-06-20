<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_seminar".
 *
 * @property integer $id_seminar
 * @property string $barcode
 * @property string $seminar
 * @property string $venue
 * @property string $tgl_satu
 * @property string $tgl_dua
 * @property string $panitia
 * @property string $topik1
 * @property string $pembicara1
 * @property string $topik2
 * @property string $pembicara2
 * @property string $topik3
 * @property string $pembicara3
 * @property string $topik4
 * @property string $pembicara4
 * @property string $topik5
 * @property string $pembicara5
 * @property string $topik6
 * @property string $pembicara6
 * @property string $topik7
 * @property string $pembicara7
 * @property string $topik8
 * @property string $pembicara8
 * @property string $topik9
 * @property string $pembicara9
 * @property string $topik10
 * @property string $pembicara10
 * @property string $topik11
 * @property string $pembicara11
 * @property string $topik12
 * @property string $pembicara12
 * @property string $topik13
 * @property string $pembicara13
 * @property string $topik14
 * @property string $pembicara14
 * @property string $topik15
 * @property string $pembicara15
 * @property string $topik16
 * @property string $pembicara16
 * @property string $topik17
 * @property string $pembicara17
 * @property string $topik18
 * @property string $pembicara18
 * @property string $topik19
 * @property string $pembicara19
 * @property string $topik20
 * @property string $pembicara20
 * @property string $topik21
 * @property string $pembicara21
 * @property string $datetime_entry
 */
class TbSeminar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_seminar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['seminar', 'venue', 'panitia', 'topik1', 'pembicara1', 'topik2', 'pembicara2', 'topik3', 'pembicara3', 'topik4', 'pembicara4', 'topik5', 'pembicara5', 'topik6', 'pembicara6', 'topik7', 'pembicara7', 'topik8', 'pembicara8', 'topik9', 'pembicara9', 'topik10', 'pembicara10', 'topik11', 'pembicara11', 'topik12', 'pembicara12', 'topik13', 'pembicara13', 'topik14', 'pembicara14', 'topik15', 'pembicara15', 'topik16', 'pembicara16', 'topik17', 'pembicara17', 'topik18', 'pembicara18', 'topik19', 'pembicara19', 'topik20', 'pembicara20', 'topik21', 'pembicara21'], 'string'],
            [['tgl_satu', 'tgl_dua', 'datetime_entry'], 'safe'],
            [['barcode'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_seminar' => 'Id Seminar',
            'barcode' => 'Barcode',
            'seminar' => 'Seminar',
            'venue' => 'Venue',
            'tgl_satu' => 'Tgl Satu',
            'tgl_dua' => 'Tgl Dua',
            'panitia' => 'Panitia',
            'topik1' => 'Topik1',
            'pembicara1' => 'Pembicara1',
            'topik2' => 'Topik2',
            'pembicara2' => 'Pembicara2',
            'topik3' => 'Topik3',
            'pembicara3' => 'Pembicara3',
            'topik4' => 'Topik4',
            'pembicara4' => 'Pembicara4',
            'topik5' => 'Topik5',
            'pembicara5' => 'Pembicara5',
            'topik6' => 'Topik6',
            'pembicara6' => 'Pembicara6',
            'topik7' => 'Topik7',
            'pembicara7' => 'Pembicara7',
            'topik8' => 'Topik8',
            'pembicara8' => 'Pembicara8',
            'topik9' => 'Topik9',
            'pembicara9' => 'Pembicara9',
            'topik10' => 'Topik10',
            'pembicara10' => 'Pembicara10',
            'topik11' => 'Topik11',
            'pembicara11' => 'Pembicara11',
            'topik12' => 'Topik12',
            'pembicara12' => 'Pembicara12',
            'topik13' => 'Topik13',
            'pembicara13' => 'Pembicara13',
            'topik14' => 'Topik14',
            'pembicara14' => 'Pembicara14',
            'topik15' => 'Topik15',
            'pembicara15' => 'Pembicara15',
            'topik16' => 'Topik16',
            'pembicara16' => 'Pembicara16',
            'topik17' => 'Topik17',
            'pembicara17' => 'Pembicara17',
            'topik18' => 'Topik18',
            'pembicara18' => 'Pembicara18',
            'topik19' => 'Topik19',
            'pembicara19' => 'Pembicara19',
            'topik20' => 'Topik20',
            'pembicara20' => 'Pembicara20',
            'topik21' => 'Topik21',
            'pembicara21' => 'Pembicara21',
            'datetime_entry' => 'Datetime Entry',
        ];
    }
}
