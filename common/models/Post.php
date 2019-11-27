<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string $post_id
 * @property string $author_href
 * @property string $author_thumb
 * @property string $author_name
 * @property string $datetime
 * @property string $content
 * @property string $phone
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['datetime'], 'safe'],
            [['content'], 'string'],
            [['post_id', 'author_href', 'author_thumb', 'author_name', 'phone'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'Post ID',
            'author_href' => 'Author Href',
            'author_thumb' => 'Author Thumb',
            'author_name' => 'Author Name',
            'datetime' => 'Datetime',
            'content' => 'Content',
            'phone' => 'Phone',
        ];
    }
}
