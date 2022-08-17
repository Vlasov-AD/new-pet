<?php
declare(strict_types=1);

namespace tests\unit\common\models;

use common\models\Product;
use PHPUnit\Framework\TestCase;
use Yii;


class ProductTest extends TestCase
{
    private Product|null $product = null;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->product = Product::find()->where(['available' => Product::STATUS_AVAILABLE])->one();
        parent::__construct($name, $data, $dataName);
    }

    public function testProductHasMainCategory()
    {
        $this->assertNotEmpty($this->product->mainCategory);
    }

    public function testGetImagePathIfEmpty()
    {
        $this->assertSame(
            Yii::getAlias('@entry').'/statics/images/card-wide__placeholder.jpg',
            $this->product->getImagePath('')
        );
    }

    public function testGetImagePathIfNotExists()
    {
        $this->assertSame(
            Yii::getAlias('@entry').'/statics/images/card-wide__placeholder.jpg',
            $this->product->getImagePath('not_exist_'.time().'.jpg')
        );
    }
}