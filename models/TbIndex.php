<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_index".
 *
 * @property integer $id_index
 * @property string $title
 * @property string $titlesatu
 * @property string $titledua
 * @property string $titletiga
 * @property string $titleempat
 * @property string $titlelima
 * @property integer $title_id
 * @property integer $index_type
 */
class TbIndex extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_index';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'titlesatu', 'titledua', 'titletiga', 'titleempat', 'titlelima'], 'string'],
            [['title_id', 'index_type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_index' => 'Id Index',
            'title' => 'Title',
            'titlesatu' => 'Titlesatu',
            'titledua' => 'Titledua',
            'titletiga' => 'Titletiga',
            'titleempat' => 'Titleempat',
            'titlelima' => 'Titlelima',
            'title_id' => 'Title ID',
            'index_type' => 'Index Type',
        ];
    }
}
