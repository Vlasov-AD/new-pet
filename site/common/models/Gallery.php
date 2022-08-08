<?php
declare(strict_types=1);

namespace common\models;

use aquy\thumbnail\Thumbnail;

/**
 * This is the model class for table "gallery".
 *
 * @property int $id
 * @property string $type
 * @property string $ownerId
 * @property string $src
 * @property int $sort
 * @property string $name
 * @property string $description
 */
class Gallery extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gallery';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ownerId'], 'required'],
            [['sort'], 'integer'],
            [['description'], 'string'],
            [['type', 'ownerId', 'src', 'name'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'ownerId' => 'Owner ID',
            'src' => 'Src',
            'sort' => 'Sort',
            'name' => 'Name',
            'description' => 'Description',
        ];
    }
}
