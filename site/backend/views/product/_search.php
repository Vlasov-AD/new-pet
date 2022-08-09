<?php

use yii\helpers\Html;
use yii\jui\AutoComplete;
use common\models\Contact;
use common\models\Product;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use common\models\Category;

/* @var $this yii\web\View */
/* @var $model common\models\search\ProductSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-search">

    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'action' => \yii\helpers\Url::canonical()
    ]); ?>

    <div class="panel panel-primary">
        <div class="panel-heading">Фильтры <div class="btn btn-xs btn-default pull-right" onclick="$('.panel-body').slideToggle()">Свернуть/развернуть</div></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <?= $form->field($model, 'name')->widget(AutoComplete::classname(), [
                        'clientOptions' => [
                            'source' => $model->nameList(),
                        ],
                        'options' => [
                            'class' => 'form-control'
                        ]
                    ])->label('название') ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'category_id')->dropDownList(Category::categoryArray())->label('категория') ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'status')->dropDownList(Product::statusList(), ['prompt' => 'Выберите статус'])->label('статус') ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'available')->dropDownList(Product::availableList(), ['prompt' => 'Выберите статус'])->label('наличие') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Сбросить', ['index'], ['class' => 'btn btn-default']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>