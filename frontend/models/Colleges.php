<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_colleges".
 *
 * @property int $college_id
 * @property string $colleage_name
 * @property string $subject_ids
 * @property int $province_id
 * @property int $create_at
 * @property string $update_at
 */
class Colleges extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_colleges';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['province_id', 'create_at'], 'integer'],
            [['update_at'], 'safe'],
            [['colleage_name'], 'string', 'max' => 100],
            [['subject_ids'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'college_id' => 'College ID',
            'colleage_name' => 'Colleage Name',
            'subject_ids' => 'Subject Ids',
            'province_id' => 'Province ID',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
}
