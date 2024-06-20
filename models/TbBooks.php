<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tb_books".
 *
 * @property integer $id_book
 * @property string $call_no
 * @property string $book_id
 * @property string $title
 * @property string $author
 * @property string $subject
 * @property string $edition
 * @property string $publisher
 * @property integer $year
 * @property string $isbn
 * @property string $remark
 * @property string $source
 * @property integer $price
 * @property string $attachment_cover
 * @property string $attachment_ebook
 * @property string $attachment_toc
 * @property string $datetime_entry
 * @property string $comment
 */
class TbBooks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tb_books';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'author', 'subject', 'edition', 'publisher', 'isbn', 'remark', 'source', 'comment'], 'string'],
            [['year', 'price'], 'integer'],
            [['datetime_entry'], 'safe'],
            [['call_no'], 'string', 'max' => 50],
            [['book_id'], 'string', 'max' => 30],
            [['attachment_cover', 'attachment_ebook', 'attachment_toc'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_book' => 'Id Book',
            'call_no' => 'Call No',
            'book_id' => 'Book ID',
            'title' => 'Title',
            'author' => 'Author',
            'subject' => 'Subject',
            'edition' => 'Edition',
            'publisher' => 'Publisher',
            'year' => 'Date Issued',
            'isbn' => 'Isbn',
            'remark' => 'Remark',
            'source' => 'Source',
            'price' => 'Price',
            'attachment_cover' => 'Keyword',
            'attachment_ebook' => 'Attachment Ebook',
            'attachment_toc' => 'Attachment Toc',
            'datetime_entry' => 'Datetime Entry',
            'comment' => 'Comment',
        ];
    }
}
