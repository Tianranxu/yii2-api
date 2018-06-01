<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_classes".
 *
 * @property int $class_id
 * @property string $class_name
 * @property string $class_type
 * @property string $price
 * @property string $picture_url
 * @property int $create_at
 * @property string $update_at
 */
class Classes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_classes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_at'], 'integer'],
            [['update_at'], 'safe'],
            [['class_name'], 'string', 'max' => 100],
            [['class_type'], 'string', 'max' => 10],
            [['price'], 'string', 'max' => 40],
            [['picture_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'class_id' => 'Class ID',
            'class_name' => 'Class Name',
            'class_type' => 'Class Type',
            'price' => 'Price',
            'picture_url' => 'Picture Url',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
}
