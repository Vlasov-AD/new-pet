<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use common\models\Product;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = 'Редактировать товары: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="product-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'productAttrForm' => $productAttrForm,
        'productAttrs' => $productAttrs,
    ]) ?>

</div>
