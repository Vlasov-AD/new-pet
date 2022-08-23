<?php


namespace console\controllers;


use common\models\Product;
use Faker\Factory;
use yii\console\Controller;

class FakerController extends Controller
{
    public function actionGenerateProducts()
    {
        $faker = Factory::create('Ru_RU');
        for ($i = 0; $i < 1000; $i++) {
            $product = new Product([
                'name' => $faker->unique()->text(40),
                'price_current' => $faker->numberBetween(100, 2000),
                'price_old' => $faker->numberBetween(2001, 4000),
                'available' => $faker->numberBetween(0, 1),
                'status' => $faker->numberBetween(0, 1),
                'description' => $faker->text(),
                'main_category' => $faker->randomElements([2,4,5,6])[0]
            ]);
            if ($product->save()) {
                var_dump("Товар $i успешно сохранен");
            } else {
                var_dump($product->getErrors());
                var_dump($product->main_category);
            }
        }
    }
}