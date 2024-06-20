<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_pengarang".
 *
 * @property integer $id_pengarang
 * @property string $pengarang
 */
class TblPengarang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_pengarang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pengarang'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_pengarang' => 'Id Pengarang',
            'pengarang' => 'Pengarang',
        ];
    }
}
