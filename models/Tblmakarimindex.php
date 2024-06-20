<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tblmakarimindex".
 *
 * @property integer $ID
 * @property string $DocName
 * @property string $DocText
 * @property integer $regulation_id
 */
class Tblmakarimindex extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tblmakarimindex';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['DocName', 'DocText'], 'required'],
            [['DocText'], 'string'],
            [['regulation_id'], 'integer'],
            [['DocName'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'DocName' => 'Doc Name',
            'DocText' => 'Doc Text',
            'regulation_id' => 'Regulation ID',
        ];
    }
	
	public function highlight($text, $words) {
		preg_match_all('~\w+~', $words, $m);
		if(!$m)
			return $text;
		$re = '~\\b(' . implode('|', $m[0]) . ')\\b~i';
		return preg_replace($re, '<b>$0</b>', $text);
	}
}
