<?php
declare(strict_types=1);

namespace common\models;

use aquy\gallery\GalleryBehavior;
use common\components\CacheHelper;
use himiklab\sortablegrid\SortableGridBehavior;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Модель для таблицы "product"
 * Class Product
 * @package common\models
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property integer $price_current
 * @property integer $price_old
 * @property integer $available
 * @property integer $sort
 * @property string $description
 * @property integer $main_category
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $first_image
 * @property integer $status
 */
class Product extends ActiveRecord
{

    const STATUS_NOT_AVAILABLE = 0;
    const STATUS_AVAILABLE = 1;

    const STATUS_ACTIVE = 1;
    const STATUS_DRAFT = 0;

    public array $categories_list = [];

    public function behaviors()
    {
        return [
            'galleryProduct' => [
                'class' => GalleryBehavior::class,
                'type' => 'product',
                'tableName' => 'gallery',
                'directory' => Yii::getAlias('@statics') . '/images/product/gallery',
                'url' => '/statics/images/product/gallery',
                'ownerFirstSrc' => 'first_image',
            ],
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'name',
                'slugAttribute' => 'slug'
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
            'sort' => [
                'class' => SortableGridBehavior::class,
                'sortableAttribute' => 'sort'
            ],
        ];
    }

    /**
     * @return string имя таблицы
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    /**
     * Правила валидации
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'slug', 'first_image'], 'string', 'max' => 255],
            [['name'], 'required'],
            ['name', 'unique'],
            ['main_category', 'default', 'value' => 1],
            ['status', 'default', 'value' => 0],
            [['main_category', 'categories_list', 'price_current'], 'required', 'when' => function ($model) {
                return !$model->isNewRecord;
            }, 'message' => 'Необходимо выбрать категорию'],
            ['description', 'string'],
            ['main_category', function ($attribute) {
                $categoriesArray = ArrayHelper::map(Category::find()->all(), 'id', 'id');;
                if (!in_array($this->$attribute, $categoriesArray)) {
                    $this->addError($attribute, "Выбранной категории не существует");
                }
            }],
            [['created_at', 'updated_at', 'available', 'price_current', 'price_old', 'sort', 'main_category', 'status'], 'integer'],
        ];
    }

    /**
     * Расшифровка атрибутов
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id товара',
            'name' => 'название товара',
            'slug' => 'slug товара',
            'price_current' => 'текущая цена',
            'price_old' => 'старая цена',
            'available' => 'наличие товара',
            'sort' => 'порядок сортировки',
            'description' => 'описание',
            'main_category' => 'главная категория',
            'created_at' => 'время создания',
            'updated_at' => 'время обновления',
            'categories_list' => 'список категорий',
            'first_image' => 'название первой фотки из галлереи',
            'status' => 'статус товара',
        ];
    }

    public function beforeSave($insert): bool
    {
        if (!$this->isNewRecord) {
            (new CategoryProduct())->updateCategoryList($this);
        }

        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        //заполняем массив категорий из связей
        $categories = $this->categories;
        foreach ($categories as $category) {
            $this->categories_list[] = $category->id;
        }
        parent::afterFind();
    }

    public function afterDelete(): void
    {
        //удаляем связи из таблиц
        (new CategoryProduct())->removeFromChildRelation($this->id);
        (new ProductAttr())->removeFromParentRelation($this->id);

        parent::afterDelete();
    }

    public static function availableList(): array
    {
        return [
            self::STATUS_NOT_AVAILABLE => 'Под заказ',
            self::STATUS_AVAILABLE => 'В наличии'
        ];
    }

    public function availableType(): string
    {
        return $this->availableList()[$this->available];
    }

    public static function statusList(): array
    {
        return [
            self::STATUS_DRAFT => 'Черновик',
            self::STATUS_ACTIVE => 'Активен'
        ];
    }

    public function statusType(): string
    {
        return $this->statusList()[$this->status];
    }

    public function getCategories()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])
            ->viaTable('{{%category_product}}', ['product_id' => 'id']);
    }

    public function getAttr()
    {
        return $this->hasMany(Attribute::class, ['id' => 'attribute_id'])
            ->viaTable('{{%product_attribute}}', ['product_id' => 'id']);
    }

    public function getAttrRelations()
    {
        return $this->hasMany(ProductAttr::class, ['product_id' => 'id']);
    }

    public function getMainCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'main_category']);
    }

    public function getImages()
    {
        return $this->hasMany(Gallery::class, ['ownerId' => 'id'])->andWhere(['or', ['type' => 'product'], ['type' => 'product_fields']])->select(['ownerId', 'src', 'type'])->orderBy(['sort' => SORT_ASC]);
    }

    public static function nameList(): array
    {
        return ArrayHelper::getColumn(
            self::find()->select('name')->groupBy('name')->asArray()->all(),
            'name'
        );
    }

    public function getUrl($schema = true): string
    {
        return Url::to('/product/'.$this->slug, $schema);
    }

    public static function getList(string $order, int $current_id = 0): array
    {
        $key = self::class . 'list';
        $data = \Yii::$app->cache->get($key);
        if (!$data) {
            $query = self::find()
                ->where(['<>', 'id', $current_id])
                ->with('mainCategory')
                ->orderBy($order)
                ->asArray()
                ->all();

            $data = ArrayHelper::map($query, 'id', 'name',  function ($item) {
                return $item['mainCategory']['name'];
            });

            \Yii::$app->cache->set($key, $data, 2*60, CacheHelper::getGlobalDependency());
        }

        return $data;
    }

    public function getImagePath(string $image): string
    {
        if ($image) {
            $path = $this->getBehavior('galleryProduct')->getFilePath($image);
            if (file_exists($path)) {
                return $path;
            }
        }
        return Yii::getAlias('@entry').'/statics/images/card-wide__placeholder.jpg';
    }

}
