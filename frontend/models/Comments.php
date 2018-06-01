<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_comments".
 *
 * @property int $comment_id
 * @property string $content
 * @property int $course_id
 * @property int $uid
 * @property int $create_at
 * @property string $update_at
 */
class Comments extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_comments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'comment_id', 'course_id', 'like_count', 'uid', 'create_at'], 'integer'],
            [['content'], 'string'],
            [['update_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'comment_id' => 'Comment ID',
            'content' => 'Content',
            'course_id' => 'Course ID',
            'like_count' => 'Like Count',
            'uid' => 'Uid',
            'status' => 'Status',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }

    public function getUsers() {
        return $this->hasOne(Users::className(), ['uid' => 'uid']);
    }
}
