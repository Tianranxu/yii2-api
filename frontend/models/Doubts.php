<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_doubts".
 *
 * @property int $doubt_id
 * @property string $title
 * @property string $reply
 * @property string $edit_agree
 * @property string $source
 * @property int $encourge_count
 * @property int $agree_count
 * @property string $author
 * @property string $author_avatar
 * @property int $creat_at
 * @property string $update_at
 */
class Doubts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_doubts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reply'], 'string'],
            [['encourge_count', 'agree_count', 'creat_at'], 'integer'],
            [['update_at'], 'safe'],
            [['title', 'author', 'author_avatar'], 'string', 'max' => 255],
            [['edit_agree'], 'string', 'max' => 20],
            [['source'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'doubt_id' => 'Doubt ID',
            'title' => 'Title',
            'reply' => 'Reply',
            'edit_agree' => 'Edit Agree',
            'source' => 'Source',
            'encourge_count' => 'Encourge Count',
            'agree_count' => 'Agree Count',
            'author' => 'Author',
            'author_avatar' => 'Author Avatar',
            'creat_at' => 'Creat At',
            'update_at' => 'Update At',
        ];
    }
}
