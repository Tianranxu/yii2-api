<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_users".
 *
 * @property int $uid
 * @property string $openid
 * @property string $unionid
 * @property string $session_key
 * @property int $user_type
 * @property string $nickname
 * @property string $avatarUrl
 * @property int $gender
 * @property string $city
 * @property string $province
 * @property string $country
 * @property string $language
 * @property string $switch_staffs
 * @property string $push_token
 * @property int $is_question_done
 * @property int $switch_times
 * @property string $create_at
 * @property string $update_at
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['openid'], 'required'],
            [['user_type', 'gender', 'is_question_done', 'switch_times'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['openid', 'unionid', 'session_key', 'nickname'], 'string', 'max' => 50],
            [['avatarUrl'], 'string', 'max' => 100],
            [['city', 'province', 'country'], 'string', 'max' => 40],
            [['language', 'switch_staffs'], 'string', 'max' => 20],
            [['push_token'], 'string', 'max' => 255],
            [['openid'], 'unique'],
            [['unionid'], 'unique'],
            [['session_key'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'openid' => 'Openid',
            'unionid' => 'Unionid',
            'session_key' => 'Session Key',
            'user_type' => 'User Type',
            'nickname' => 'Nickname',
            'avatarUrl' => 'Avatar Url',
            'gender' => 'Gender',
            'city' => 'City',
            'province' => 'Province',
            'country' => 'Country',
            'language' => 'Language',
            'switch_staffs' => 'Switch Staffs',
            'push_token' => 'Push Token',
            'is_question_done' => 'Is Question Done',
            'switch_times' => 'Switch Times',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
}
