<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_user_source".
 *
 * @property string $site_id
 * @property int $uid
 * @property int $army_id
 * @property int $group_id
 * @property string $source
 * @property int $create_at
 * @property string $update_at
 */
class UserSource extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_user_source';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['site_id', 'uid'], 'required'],
            [['uid', 'army_id', 'group_id', 'create_at'], 'integer'],
            [['update_at'], 'safe'],
            [['site_id', 'source'], 'string', 'max' => 100],
            [['site_id', 'uid'], 'unique', 'targetAttribute' => ['site_id', 'uid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'site_id' => 'Site ID',
            'uid' => 'Uid',
            'army_id' => 'Army ID',
            'group_id' => 'Group ID',
            'source' => 'Source',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
}
