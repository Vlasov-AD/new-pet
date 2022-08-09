<?php
declare(strict_types=1);

namespace common\models;

use common\components\CacheHelper;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Модель для таблицы "attribute"
 * Class Attribute
 * @package common\models
 *
 * @property integer $id
 * @property string $name
 */
class Attribute extends ActiveRecord
{

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * @return string имя таблицы
     */
    public static function tableName()
    {
        return '{{%attribute}}';
    }

    /**
     * Правила валидации
     * @return array
     */
    public function rules()
    {
        return [
            ['name', 'string', 'max' => 255],
            ['name', 'unique'],
            ['name', 'required'],
        ];
    }

    /**
     * Расшифровка атрибутов
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id характеристики',
            'name' => 'название характеристики'
        ];
    }

    public function afterDelete()
    {
        (new ProductAttr())->removeFromChildRelation($this->id);
        parent::afterDelete();
    }

    public function getProducts()
    {
        return $this->hasMany(Product::class, ['id' => 'product_id'])->viaTable('{{%product_attribute}}', ['attribute_id' => 'id']);
    }

    public static function getList(array $params, string $order): array
    {
        $key = self::class . 'list';
        $data = \Yii::$app->cache->get($key);
        if (!$data) {
            $query = self::find()
                ->orderBy($order)
                ->asArray()
                ->all();

            $data = ArrayHelper::map($query, ...$params);
            \Yii::$app->cache->set($key, $data, 2*60, CacheHelper::getGlobalDependency());
        }

        return $data;
    }


}
