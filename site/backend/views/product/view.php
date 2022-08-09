<?php

use yii\helpers\Html;
use common\models\Product;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотитет удалить элемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'category.name:text:главная категория (на сайт)',
            [
                'attribute' => 'categories_list',
                'value' => implode(', ', ArrayHelper::map($model->category, 'id', 'name'))
            ],
            'name',
            [
                'attribute' => 'slug',
                'format' => 'raw',
                'value' => ($model->available === Product::STATUS_AVAILABLE) ? Html::a(
                    $model->getUrl(),
                    $model->getUrl(),
                    ['target' => '_blank']
                    ) : $model->slug
            ],
            [
                'attribute' => 'description',
                'format' => 'raw',
                'value' => Html::tag('span', $model->description, ['style' => 'white-space:pre-line;'])
            ],
            'price_current',
            'price_old',
            [
                'attribute' => 'available',
                'value' => Product::availableList()[$model->available]
            ],
            [
                'attribute' => 'status',
                'value' => Product::statusList()[$model->status]
            ],
            'sort',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
