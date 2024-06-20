<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "search_history".
 *
 * @property integer $id
 * @property string $keyword
 * @property integer $hits
 */
class SearchHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'search_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hits'], 'integer'],
            [['keyword'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'keyword' => 'Keyword',
            'hits' => 'Hits',
        ];
    }
}
