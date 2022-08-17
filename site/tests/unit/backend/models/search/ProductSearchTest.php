<?php


namespace tests\unit\backend\models\search;


use backend\models\search\ProductSearch;
use common\models\Product;

class ProductSearchTest extends \PHPUnit\Framework\TestCase
{
    private Product|null $product = null;
    private ProductSearch|null $searcher = null;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->product = Product::find()->one();
        $this->searcher = new ProductSearch();
        parent::__construct($name, $data, $dataName);
    }

    public function testSearchExisitngName()
    {
        $param = [
            'name' => $this->product->name
        ];

        $products = ($this->searcher->search($param))->getModels();

        $this->assertNotEmpty($products);

        foreach ($products as $product) {
            $this->assertSame(
                $this->product->name,
                $product->name
            );
        }
    }

    public function testSearchNotExisitngName()
    {
        $param = [
            'name' => 'not_exist_'.time()
        ];

        $products = ($this->searcher->search($param))->getModels();

        $this->assertEmpty($products);
    }

    public function testSearchExisitngCategory()
    {
        $param = [
            'category_id' => $this->product->main_category
        ];

        $products = ($this->searcher->search($param))->getModels();

        $this->assertNotEmpty($products);

        foreach ($products as $product) {
            $this->assertSame(
                $this->product->main_category,
                $product->main_category
            );
        }
    }

    public function testSearchNotExisitngCategory()
    {
        $param = [
            'category_id' => time()
        ];

        $products = ($this->searcher->search($param))->getModels();

        $this->assertEmpty($products);
    }

    public function testSearchIsNotEmptyWithDefaultCategory()
    {
        $param = [
            'category_id' => 1
        ];

        $products = ($this->searcher->search($param))->getModels();

        $this->assertNotEmpty($products);
    }

    public function testSearchExisitngStatus()
    {
        $param = [
            'status' => $this->product->status
        ];

        $products = ($this->searcher->search($param))->getModels();

        $this->assertNotEmpty($products);

        foreach ($products as $product) {
            $this->assertSame(
                $this->product->status,
                $product->status
            );
        }
    }

    public function testSearchNotExisitngStatus()
    {
        $param = [
            'status' => time()
        ];

        $products = ($this->searcher->search($param))->getModels();

        $this->assertEmpty($products);
    }

    public function testSearchExisitngAvailable()
    {
        $param = [
            'available' => $this->product->available
        ];

        $products = ($this->searcher->search($param))->getModels();

        $this->assertNotEmpty($products);

        foreach ($products as $product) {
            $this->assertSame(
                $this->product->available,
                $product->available
            );
        }
    }

    public function testSearchNotExisitngAvailable()
    {
        $param = [
            'available' => time()
        ];

        $products = ($this->searcher->search($param))->getModels();

        $this->assertEmpty($products);
    }

}