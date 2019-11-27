<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "topic".
 *
 * @property int $id
 * @property string $title
 * @property string $href
 * @property int $count
 * @property string $status
 * @property int $offset
 */
class Topic extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'topic';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['count', 'offset'], 'integer'],
            [['title', 'href', 'status'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'href' => 'Href',
            'count' => 'Count',
            'status' => 'Status',
            'offset' => 'Offset',
        ];
    }
}
