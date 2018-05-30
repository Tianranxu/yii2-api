<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_open_courses".
 *
 * @property int $course_id
 * @property string $title
 * @property string $subtitle
 * @property string $preview_picture
 * @property string $introduction
 * @property int $viewed_count
 * @property int $comment_count
 * @property int $like_count
 * @property string $duration
 * @property int $create_at
 * @property string $update_at
 */
class OpenCourses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_open_courses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_id'], 'required'],
            [['course_id', 'viewed_count', 'comment_count', 'like_count', 'create_at'], 'integer'],
            [['introduction'], 'string'],
            [['update_at'], 'safe'],
            [['title', 'subtitle', 'preview_picture'], 'string', 'max' => 255],
            [['duration'], 'string', 'max' => 20],
            [['course_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'course_id' => 'Course ID',
            'title' => 'Title',
            'subtitle' => 'Subtitle',
            'preview_picture' => 'Preview Picture',
            'introduction' => 'Introduction',
            'viewed_count' => 'Viewed Count',
            'comment_count' => 'Comment Count',
            'like_count' => 'Like Count',
            'duration' => 'Duration',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
}
