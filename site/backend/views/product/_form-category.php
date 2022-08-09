<?php

use yii\helpers\Html;
use common\models\Category;

$categories = Category::find()
    ->where(['<>', 'id', 1])
    ->andWhere(['status' => Category::STATUS_ACTIVE])
    ->indexBy('id')
    ->orderBy('lft')
    ->all();
foreach ($categories as $category) {
    $space = '';
    for ($i = 2; $i < $category->depth; $i++) {
        $space .= '  ';
    }
    $categories_drop_down[$category->id] = $space . $category->name;
}

?>

<div class="row">
    <div class="col-md-8">
        <?= $form->field($model, 'categories_list')->checkboxList(
            $categories,
            [
                'item' => function($index, $label, $name, $checked, $value) {
                        return Html::checkbox($name, $checked, [
                            'value' => $label->id,
                            'label' => $label->name,
                            'labelOptions' => [
                                'style' => 'margin-left: ' . 20 * ($label->depth - 2) . 'px;'
                            ]
                        ]);
                    },
                'multiple' => true,
                'separator' => '<br>'
            ]) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'main_category')->dropDownList($categories_drop_down, [
                'prompt' => 'Выберите главную категорию',
                'encodeSpaces' => true
            ]
        ) ?>
    </div>
</div>

<?php

$js = <<<JS

$(function(){
    $('#product-categories_list input').on('click', function(){
        if (!$('#product-category_id').val()) {
            $('#product-category_id').val($(this).val());
        }
        if (!$('#product-categories_list input:checked').length) {
            $('#product-category_id').val('');
        }
    });
});

JS;

$this->registerJs($js, \yii\web\View::POS_END);

?>