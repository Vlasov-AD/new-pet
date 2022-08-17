<?php
declare(strict_types=1);

namespace common\models;

use common\models\interface\RelationInterface;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Модель для таблицы "category_product"
 * Class CategoryProduct
 * @package common\models
 *
 * @property integer $category_id
 * @property integer $product_id
 */
class CategoryProduct extends ActiveRecord implements RelationInterface
{

    /**
     * @return string имя таблицы
     */
    public static function tableName()
    {
        return '{{%category_product}}';
    }

    /**
     * Правила валидации
     * @return array
     */
    public function rules()
    {
        return [
            ['category_id', function ($attribute) {
                $categoriesArray = ArrayHelper::map(Category::find()->all(), 'id', 'id');;
                if (!in_array($this->$attribute, $categoriesArray)) {
                    $this->addError($attribute,'Выбранной категории не существует');
                }
            }],
            ['product_id', function ($attribute) {
                $categoriesArray = ArrayHelper::map(Product::find()->all(), 'id', 'id');;
                if (!in_array($this->$attribute, $categoriesArray)) {
                    $this->addError($attribute,'Выбранного товара не существует');
                }
            }],
            [['category_id', 'product_id'], 'unique', 'targetAttribute' => ['category_id', 'product_id']],
            [['category_id', 'product_id'], 'integer']
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
            'category_id' => 'id категории'
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    private function addFewCategory(Product $product, array $currentArray): void
    {
        foreach ($product->categories_list as $category_id) {
            if (!in_array($category_id, $currentArray)) {
                (new static([
                    'category_id' => $category_id,
                    'product_id' => $product->id
                ]))->save();

                //добавляем родительскую категорию,
                // если её нет в списке текущем и на добавление
                $this->addParentCategory(
                    (int) $category_id,
                    $currentArray,
                    $product
                );
            }
        }
    }

    private function removeFewCategory(Product $product, array $currentArray): void
    {
        foreach ($currentArray as $category_id_current) {
            if (!in_array($category_id_current, $product->categories_list)) {
                $this->deleteByFields($category_id_current, $product->id);
            }
        }
    }

    public function updateCategoryList(Product $product): void
    {
        //добавляем главную категорию в список
        if (!in_array($product->main_category, $product->categories_list)) {
            $product->categories_list[] = $product->main_category;
        }

        //получаем все связанные с товаром категории
        $currentArray = $this->findRelations($product->id);

        //добавляем новые, которые добавили
        $this->addFewCategory($product, $currentArray);

        //удаляем старые, которые отключили
        $this->removeFewCategory($product ,$currentArray);

        //если удалены все категории, записываем главную
        if (!static::find()->where(['product_id' => $product->id])->one()) {
            (new static([
                'category_id' => $product->main_category,
                'product_id' => $product->id
            ]))->save();
        }
    }

    public function addOrUpdateRelation():void
    {
        if ($relation = $this->findRelation()) {
            $this->updateRelation($relation);
            return;
        }
        $this->save();
    }

    public function findRelations(int $id): array|null
    {
        $currentCategories = static::find()
            ->where(['product_id' => $id])
            ->asArray()
            ->all();

        return ArrayHelper::map($currentCategories, 'category_id', 'category_id');
    }

    public function updateRelation(RelationInterface $relation): void
    {
        // TODO: Implement updateRelation() method.
    }

    public function findRelation(): self|null
    {
        return null;
    }

    public function deleteByPrimary(int $id): void
    {
        // TODO: Implement deleteByPrimary() method.
    }

    public function deleteByFields(int $parent_id, int $child_id): void
    {
        static::deleteAll([
            'category_id' => $parent_id,
            'product_id' => $child_id
        ]);
    }

    public function removeFromParentRelation(int $id): void
    {
        $category_products = static::find()
            ->where(['category_id' => $id])
            ->all();
        foreach ($category_products as $category_product) {
            $category_product->delete();
        }
    }

    public function removeFromChildRelation(int $id): void
    {
        $category_products = static::find()
            ->where(['product_id' => $id])
            ->all();
        foreach ($category_products as $category_product) {
            $category_product->delete();
        }
    }

    private function addParentCategory(int $category_id, array $currentArray, Product $product): void
    {
        $category = Category::find()->where(['id' => $category_id])->one();
        if (!$category) {
            return;
        }

        $parents = $category->parents(1)->one();
        if (
            $parents
            && !in_array($parents->id, $currentArray)
            && !in_array($parents->id, $product->categories_list)
        ) {
            (new static([
                'category_id' => $parents->id,
                'product_id' => $product->id
            ]))->save();
        }
    }

}
