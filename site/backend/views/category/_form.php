<?php

use aquy\gallery\GalleryManager;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \common\models\Category;

/** @var Category $model*/

// $categories = Category::find()->where(['<>', 'id', '1'])->indexBy('id')->all();
$notice = ' - подкатегории игнорируются (категория считается подкатегорией если есть родительский объект)';
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 300, 'placeholder' => 'название категории']) ?>

    <?php if (!$model->isNewRecord): ?>
        <?= $form->field($model, 'slug')->textInput(['maxlength' => 300, 'placeholder' => 'slug']) ?>
    <?php endif; ?>


    <?= $form->field($model, 'parent_id')->dropDownList($model->categoryArray())->label('Родительская категория') ?>

    <?= $form->field($model, 'status')->dropDownList($model->statusList()) ?>

    <?php if (!$model->isNewRecord): ?>
        <?= GalleryManager::widget([
            'model' => $model,
            'behaviorName' => 'galleryBehavior',
            'apiRoute' => 'category/galleryApi'
        ])?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>