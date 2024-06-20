<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_comment".
 *
 * @property integer $id_comment
 * @property string $nama
 * @property integer $id_collection
 * @property string $comment
 * @property integer $type_collection
 */
class TbComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_collection', 'type_collection'], 'integer'],
            [['comment'], 'string'],
            [['nama'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_comment' => 'Id Comment',
            'nama' => 'Nama',
            'id_collection' => 'Id Collection',
            'comment' => 'Comment Text',
            'type_collection' => 'Type Collection',
        ];
    }
}
