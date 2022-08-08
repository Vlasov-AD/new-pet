<?php

use yii\helpers\Html;

$this->title = 'Редактировать';

if ($parents) {
    foreach ($parents as $parent) {
        if ($parent->id === 1) {
            $this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
        } else {
            $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['index', 'id' => $parent->id]];
        }
    }
}

$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>