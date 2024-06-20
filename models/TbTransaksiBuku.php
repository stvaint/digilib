<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_transaksi_buku".
 *
 * @property integer $id_book
 * @property integer $id_circ
 * @property string $tgl_sewa
 * @property string $tgl_akhir
 * @property string $id_user
 * @property string $bookid
 * @property string $title
 * @property string $status
 */
class TbTransaksiBuku extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_transaksi_buku';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_book', 'id_circ'], 'integer'],
            [['tgl_sewa', 'tgl_akhir'], 'safe'],
            [['title'], 'string'],
            [['id_user', 'bookid'], 'string', 'max' => 10],
            [['status'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_book' => 'Id Book',
            'id_circ' => 'Id Circ',
            'tgl_sewa' => 'Tgl Sewa',
            'tgl_akhir' => 'Tgl Akhir',
            'id_user' => 'Id User',
            'bookid' => 'Bookid',
            'title' => 'Title',
            'status' => 'Status',
        ];
    }
}
