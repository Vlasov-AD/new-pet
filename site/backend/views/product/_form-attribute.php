<?php

use common\models\Product;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use himiklab\sortablegrid\SortableGridView as GridView;
use common\models\Attribute;

/** @var Product $model*/
/** @var ActiveDataProvider $productAttrs*/

?>
<div class="panel-body">
    <?php
    $formAttribute = ActiveForm::begin();
    echo $formAttribute->field($productAttrForm, 'product_id')->label(false)->hiddenInput([
        'value' => $model->id,
    ]);

    echo $formAttribute->field($productAttrForm, 'attribute_id')->dropDownList(Attribute::getList(array('id', 'name'), 'name'), [
        'class' => 'jsParamId form-control'
    ])->label('Имя характеристики');

    echo $formAttribute->field($productAttrForm, 'value')->textInput( [
        'class' => 'jsParamValue form-control'
    ])->label('Значение характеристики');

    echo Html::submitButton('Добавить/редактировать характеристику', ['class'=>'btn btn-success']);
    echo '<br>';
    ActiveForm::end();
    ?>
    <br>
    <?= GridView::widget([
        'dataProvider' => $productAttrs,
        'sortableAction' => 'attr',
        'columns' => [
            'attr.name',
            'value',
            [
                'label' => 'Управление',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>',
                            '',
                            [
                                'class' => 'btn btn-xs btn-primary jsEditParam',
                                'data' => [
                                    'product-id' => $data->product_id,
                                    'attr-id' => $data->attribute_id,
                                    'value' => $data->value
                                ]
                            ]
                        ). ' ' .
                        Html::a(
                            '<span class="glyphicon glyphicon-trash"></span>',
                            '',
                            [
                                'class' => 'btn btn-xs btn-danger',
                                'data' => [
                                    'confirm' => 'Удалить связь ' . $data->attr->name . 'с ' .$data->product->name .' ?',
                                    'method' => 'post',
                                    'params' => [
                                        'delete-attr' => $data->id
                                    ]
                                ]
                            ]
                        );
                },
            ],
        ],
    ]); ?>
</div>
