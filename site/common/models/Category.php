<?php
declare(strict_types=1);

namespace common\models;

use aquy\gallery\GalleryBehavior;
use creocoder\nestedsets\NestedSetsBehavior;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Модель для таблицы "category"
 * Class Category
 * @package common\models
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $title
 * @property integer $status
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $first_image
 */
class Category extends ActiveRecord
{
    public $meta;

    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;

    public int $parent_id = 1;

    public function behaviors()
    {
        return [
            [
                'class' => NestedSetsBehavior::class,
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'depthAttribute' => 'depth',
            ],
            'galleryBehavior' => [
                'class' => GalleryBehavior::class,
                'type' => 'category',
                'tableName' => 'gallery',
                'directory' => Yii::getAlias('@statics') . '/images/category/gallery',
                'url' => '/statics/images/category/gallery',
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
        ];
    }

    /**
     * @return string имя таблицы
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * Правила валидации
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'title', 'slug', 'first_image'], 'string', 'max' => 255],
            ['name', 'required'],
            ['name', 'unique'],
            [['created_at', 'updated_at', 'status', 'lft', 'rgt', 'depth', 'parent_id'], 'integer']
        ];
    }

    /**
     * Расшифровка атрибутов
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id категории',
            'name' => 'название категории',
            'slug' => 'slug категории',
            'title' => 'заголовок категории',
            'status' => 'статус категории',
            'lft' => 'позиция влево',
            'rgt' => 'позиция вправо',
            'level' => 'глубина',
            'first_image' => 'название первой фотки из галлереи'
        ];
    }

    public static function categoryArray(): array
    {
        $array = self::find()
            ->select(['id', 'name'])
            ->orderBy('depth')
            ->asArray()
            ->all();
        return ArrayHelper::map($array, 'id', 'name');
    }

    public static function statusList():array
    {
        return [
            self::STATUS_ACTIVE => 'Активна',
            self::STATUS_DRAFT => 'Черновик'
        ];
    }

    public function statusType(): string
    {
        return $this->statusList()[$this->status];
    }

    public function getUrl($schema = true): string
    {
        return Url::to('/catalog/'.$this->slug, $schema);
    }

    /*public function getProducts()
    {
        return $this->hasMany(Product::class, ['id' => 'product_id'])
            ->viaTable('{{%category_product}}', ['category_id' => 'id'])
            ->orderBy(['sort' => SORT_ASC]);
    }*/

    public function getImages()
    {
        return $this->hasMany(Gallery::class, ['ownerId' => 'id'])->andWhere(['type' => 'category'])->select(['ownerId', 'src', 'type'])->orderBy(['sort' => SORT_ASC]);
    }

    public function getImagePath(string $image): string
    {
        if ($image) {
            $path = $this->getBehavior('galleryBehavior')->getFilePath($image);
            if (file_exists($path)) {
                return $path;
            }
        }
        return Yii::getAlias('@entry').'/statics/images/card-wide__placeholder.jpg';
    }

}
