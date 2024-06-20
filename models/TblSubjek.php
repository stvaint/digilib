<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_subjek".
 *
 * @property integer $id_subjek
 * @property string $subjek
 */
class TblSubjek extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_subjek';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subjek'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_subjek' => 'Id Subjek',
            'subjek' => 'Subjek',
        ];
    }
}
