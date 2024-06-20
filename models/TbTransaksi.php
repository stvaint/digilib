<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_transaksi".
 *
 * @property integer $id_circ
 * @property string $noacak
 * @property string $bookid
 * @property string $id_user
 * @property string $tgl_sewa
 * @property string $tgl_akhir
 * @property string $status
 * @property string $tgl_kembali
 */
class TbTransaksi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_transaksi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['noacak'], 'string'],
            [['tgl_sewa', 'tgl_akhir', 'tgl_kembali'], 'safe'],
            [['bookid', 'id_user'], 'string', 'max' => 10],
            [['status'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_circ' => 'Id Circ',
            'noacak' => 'Noacak',
            'bookid' => 'Bookid',
            'id_user' => 'Id User',
            'tgl_sewa' => 'Tgl Sewa',
            'tgl_akhir' => 'Tgl Akhir',
            'status' => 'Status',
            'tgl_kembali' => 'Tgl Kembali',
        ];
    }
}
