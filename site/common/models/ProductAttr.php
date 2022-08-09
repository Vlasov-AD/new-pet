<?php
declare(strict_types=1);

namespace common\models;

use common\models\interface\RelationInterface;
use himiklab\sortablegrid\SortableGridBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Модель для таблицы "product_attribute"
 * Class ProductProduct
 * @package common\models
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $attribute_id
 * @property string $value
 */
class ProductAttr extends ActiveRecord implements RelationInterface
{
    public function behaviors()
    {
        return [
            'sort' => [
                'class' => SortableGridBehavior::class,
                'sortableAttribute' => 'sort'
            ]
        ];
    }

    /**
     * @return string имя таблицы
     */
    public static function tableName()
    {
        return '{{%product_attribute}}';
    }

    /**
     * Правила валидации
     * @return array
     */
    public function rules()
    {
        return [
            ['product_id', function ($attribute) {
                $products = ArrayHelper::map(Product::find()->all(), 'id', 'id');;
                if (!in_array($this->$attribute, $products)) {
                    $this->addError($attribute,'Выбранного товара не существует');
                }
            }],
            ['attribute_id', function ($attribute) {
                $attr = ArrayHelper::map(Attribute::find()->all(), 'id', 'id');;
                if (!in_array($this->$attribute, $attr)) {
                    $this->addError($attribute,'Выбранной характеристики не существует');
                }
            }],
            [['product_id', 'attribute_id'], 'integer'],
            ['value', 'string'],
            ['value', 'required']
        ];
    }

    /**
     * Расшифровка атрибутов
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'id товара',
            'attribute_id' => 'id характеристики',
            'value' => 'значение'
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    public function getAttr()
    {
        return $this->hasOne(Attribute::class, ['id' => 'attribute_id']);
    }

    public function addOrUpdateRelation():void
    {
        if ($relation = $this->findRelation()) {
            $this->updateRelation($relation);
            return;
        }
        $this->save();
    }

    public function updateRelation(RelationInterface $relation): void
    {
        /** @var $relation self*/
        $relation->value = $this->value;
        $relation->save();
    }

    public function findRelation(): RelationInterface|null
    {
        return self::find()
            ->where([
                'product_id'=>$this->product_id,
                'attribute_id'=>$this->attribute_id,
            ])
            ->one();
    }

    public function deleteByPrimary(int $id): void
    {
        $relation = self::find()->where(['id' => $id])->one();
        if (!$relation) {
            throw new NotFoundHttpException('Произошла ошибка! Товар не найден!');
        }
        $relation->delete();
    }

    public function deleteByFields(int $parent_id, int $child_id): void
    {
        // TODO: Implement deleteByFields() method.
    }

    public function removeFromParentRelation(int $id): void
    {
        $product_attributes = self::find()
            ->where(['product_id' => $id])
            ->all();
        foreach ($product_attributes as $product_attribute) {
            $product_attribute->delete();
        }
    }

    public function removeFromChildRelation(int $id): void
    {
        $product_attributes = self::find()
            ->where(['attribute_id' => $id])
            ->all();
        foreach ($product_attributes as $product_attribute) {
            $product_attribute->delete();
        }
    }
}
