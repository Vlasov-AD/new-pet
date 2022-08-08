<?php

use common\models\Category;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var Category $model
 * @var Category[] $parents
 */

$this->title = $model->name;

if ($parents) {
    foreach ($parents as $parent) {
        if ($parent->id === 1) {
            $this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
        } else {
            $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['index', 'id' => $parent->id]];
        }
    }
}

$this->params['breadcrumbs'][] = $this->title;
?>
<div>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'attribute' => 'slug',
                'format' => 'raw',
                'value' => Html::a($model->slug, $model->getUrl())
            ],
            'title',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->statusType();
                }
            ],
            'lft',
            'rgt',
            'level',
            'created_at',
            'updated_at'
        ],
    ]) ?>

</div>