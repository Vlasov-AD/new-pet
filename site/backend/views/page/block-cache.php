<?php

use backend\models\search\ProductSearch;
use common\models\Product;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ProductSearch */
/* @var $products Product[] */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    table, th, td {
        border: 1px solid black;
        padding-top: 15px;
        text-align: center;
    }
</style>

<div class="product-index">

    <h1><?= Html::encode($this->title) . ' - страница для работы с кэшем(блок)' ?></h1>

    <table style="width: 1200px;">
        <tr>
            <th>id</th>
            <th>Имя</th>
            <th>Название категории</th>
            <th>Цена текущая</th>
            <th>Цена старая</th>
            <th>Доступность</th>
            <th>Статус</th>
        </tr>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= $product->id ?></td>
                <td><?= $product->name ?></td>
                <td><?= $product->mainCategory->name ?></td>
                <td><?= $product->price_current ?></td>
                <td><?= $product->price_old ?></td>
                <td><?= $product->available ? 'Да' : 'Нет' ?></td>
                <td><?= $product->status ? 'Активен' : 'Черновик' ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

