<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_questions".
 *
 * @property int $question_id
 * @property string $content
 * @property string $option
 * @property int $status
 * @property int $sort
 * @property int $create_at
 * @property int $update_at
 */
class Questions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_questions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['status', 'sort', 'create_at', 'update_at'], 'integer'],
            [['content'], 'string', 'max' => 255],
            [['option'], 'string', 'max' => 1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'question_id' => 'Question ID',
            'content' => 'Content',
            'option' => 'Option',
            'status' => 'Status',
            'sort' => 'Sort',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
}
