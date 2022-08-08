<?php

use common\models\Category;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var Category $model*/
/** @var ActiveDataProvider[] $dataProvider*/
/** @var Category[] $parents*/

if ($parents) {
    foreach ($parents as $parent) {
        if ($parent->id === 1) {
            $this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
        } else {
            $this->params['breadcrumbs'][] = ['label' => $parent->name, 'url' => ['index', 'id' => $parent->id]];
        }
    }
    $this->title = $model->name;
    $id = $model->id;
} else {
    $this->title = 'Категории';
    $id = 1;
}

$this->params['breadcrumbs'][] = $this->title;

$update = '';
if ($id <> 1) {
    $update = Html::a('Редактировать', ['update', 'id' => $id], ['class' => 'btn btn-primar']) . ' ';
}

?>
<div>
    <div class="row">
        <div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">
            <h1><?= Html::encode($this->title) ?> <?= $update . Html::a('Создать', ['create', 'id' => $id], ['class' => 'btn btn-success']) ?></h1>
        </div>
    </div>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model->name, ['index', 'id' => $model->id]);
                }
            ],
            [
                'attribute' => 'name',
                'label' => 'Потомков',
                'format' => 'raw',
                'value' => function ($model) {
                    return count($model->children()->all());
                }
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->statusType();
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{up} {down} {view} {update} {delete}',
                'buttons' => [
                    'up' => function ($url, $model, $key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>',
                            ['move', 'type' => 'up', 'id' => $key],
                            [
                                'title' => 'Вверх',
                                'data-toggle' => 'tooltip',
                                'data' => [
                                    'method' => 'post'
                                ]
                            ]
                        );
                    },
                    'down' => function ($url, $model, $key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>',
                            ['move', 'type' => 'down', 'id' => $key],
                            [
                                'title' => 'Вниз',
                                'data-toggle' => 'tooltip',
                                'data' => [
                                    'method' => 'post'
                                ]
                            ]
                        );
                    }
                ],
                'buttonOptions' => [
                    'data-toggle' => 'tooltip',
                ]
            ],
        ],
    ]); ?>

</div>

    <style>
        .show_array_p {
            padding-top: 30px;
            padding-right: 10px;
            font-size: 20px;
            display: inline-block;
        }
        .show_array_switch {
            display: inline-block;
        }

        .material-switch > input[type="checkbox"] {
            display: none;
        }

        .material-switch > label {
            cursor: pointer;
            height: 0px;
            position: relative;
            width: 40px;
        }

        .material-switch > label::before {
            background: rgb(0, 0, 0);
            box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
            border-radius: 8px;
            content: '';
            height: 16px;
            margin-top: -8px;
            position: absolute;
            opacity: 0.3;
            transition: all 0.4s ease-in-out;
            width: 40px;
        }

        .material-switch > label::after {
            background: rgb(255, 255, 255);
            border-radius: 16px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
            content: '';
            height: 24px;
            left: -4px;
            margin-top: -8px;
            position: absolute;
            top: -4px;
            transition: all 0.3s ease-in-out;
            width: 24px;
        }

        .material-switch > input[type="checkbox"]:checked + label::before {
            background: inherit;
            opacity: 0.5;
        }

        .material-switch > input[type="checkbox"]:checked + label::after {
            background: inherit;
            left: 20px;
        }

        .quick-edit-row {
            align-items: center;
            display: none;
        }

        .quick-edit-row.active {
            display: flex;
        }

        .quick-edit-row input {
            width: 100px;
        }

        .quick-edit-row textarea {
            width: 350px;
            resize: none;
        }

        .quick-edit-row .btn {
            margin-left: 10px;
        }

        .video-edit-trigger {
            cursor: pointer;
            min-height: 40px;
        }
    </style>

<?php

$js = <<<JS
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
})

$('.jsSwitchCategoryPrice').on('change', function(event, state) {
        $.post('/backend/category/show-price',
            {
                id   : $(this).data('id'),
                type :  $(this).data('type')
            },
            function(data) {
            if (data=='error') {alert('Состояние продукта не может быть изменено');}
        });
    });

$('.jsSwitchAwayForm').on('change', function(event, state) {
        $.post('/backend/setting/away-form',
            {
                value : 'value'
            },
            function(data) {
            if (data=='success') {window.location.href = window.location;}
            if (data=='error') {alert('Состояние настроек не может быть изменено');}
        });
    });
JS;

$this->registerJs($js);
