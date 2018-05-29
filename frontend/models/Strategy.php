<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_strategy".
 *
 * @property int $strategy_id
 * @property string $title
 * @property string $content
 * @property string $head_picture
 * @property int $share_type
 * @property int $view_count
 * @property int $uid
 * @property int $create_at
 * @property string $update_at
 */
class Strategy extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_strategy';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['share_type', 'view_count', 'uid', 'create_at'], 'integer'],
            [['update_at'], 'safe'],
            [['title', 'head_picture'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'strategy_id' => 'Strategy ID',
            'title' => 'Title',
            'content' => 'Content',
            'head_picture' => 'Head Picture',
            'share_type' => 'Share Type',
            'view_count' => 'View Count',
            'uid' => 'Uid',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }

    /*public function getUsers() {
        return $this->hasOne(Users::className(), ['uid' => 'strategy_id']);
    }*/
}
