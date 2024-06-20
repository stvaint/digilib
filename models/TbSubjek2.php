<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_subjek2".
 *
 * @property integer $id_subjek
 * @property string $subjek
 */
class TbSubjek2 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_subjek2';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subjek'], 'string', 'max' => 100],
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
