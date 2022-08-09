<?php

use common\models\Product;
use vova07\imperavi\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use aquy\gallery\GalleryManager;

/** @var Product $model*/
?>

<?php if(!$model->isNewRecord): ?>

<style>

.save-button {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 3;
}
.profit_icon {
    width: 60px;
    height: 60px;
    margin-bottom: 10px;
    margin-left: 10px;
}

.profit_icon img {
    max-height: 100%;
    max-width: 100%;
}

</style>

<?php endif; ?>
<div class="product-form">
    <div class="col-md-6">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?php if (!$model->isNewRecord) : ?>
            <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

            <div class="panel panel-info">
                <div class="panel-body">
                    <?= $this->render('_form-category', [
                        'form' => $form,
                        'model' => $model,
                    ]) ?>
                </div>
            </div>

            <?= $form->field($model, 'description')->widget(Widget::class, [
                'settings' => [
                    'lang' => 'ru',
                    'minHeight' => 200
                ]
            ]) ?>


            <?= $form->field($model, 'price_current')->textInput() ?>
            <?= $form->field($model, 'price_old')->textInput() ?>

            <?= $form->field($model, 'available')->dropDownList(Product::availableList()) ?>
            <?= $form->field($model, 'status')->dropDownList(Product::statusList())?>

        <?php endif; ?>

        <?php if (!$model->isNewRecord) : ?>
            <?= GalleryManager::widget(
                [
                    'model' => $model,
                    'behaviorName' => 'galleryProduct',
                    'apiRoute' => 'product/galleryApi'
                ]
            ); ?>
        <?php endif; ?>

        <br>
        <div>
            <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

    <div class="col-md-6">
        <?php if (!$model->isNewRecord): ?>
            <div class="panel panel-info">
                <div class="panel-body">
                    <?= $this->render('_form-attribute', [
                        'model' => $model,
                        'productAttrForm' => $productAttrForm,
                        'productAttrs' => $productAttrs
                    ]) ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php
$js = <<<JS

$(function() {
    $('.jsEditParam').on('click', function(e) {
        e.preventDefault();
        $('.jsParamId').val($(this).data('attr-id'));
        $('.jsParamValue').val($(this).data('value'));
    });
});

JS;

$this->registerJs($js, \yii\web\View::POS_END);
