<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use himiklab\sortablegrid\SortableGridView as GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php
            if(\Yii::$app->user->can('admin')) {
                echo Html::a('Добавить товар', ['create'], ['class' => 'btn btn-success']);
            }
        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'name',
            [
                'attribute' => 'main_category',
                'value' => function ($model) {
                    return $model->mainCategory->name;
                }
            ],
            'price_current',
            'price_old',
            [
                'attribute' => 'available',
                'value' => function($model) {
                    return $model->availableList()[$model->available];
                }
            ],
            [
                'attribute' => 'status',
                'value' => function($model) {
                    return $model->statusList()[$model->status];
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {open} {update} {delete}',
                'visible' => Yii::$app->user->can('admin'),
                'options' => [
                    'width' => '90px'
                ],
                'buttons' => [
                    'open' => function ($url, $model, $key) {
                        if (empty($model->category_id)) {
							return null;
                        }

                        /** @var $urlManager */
                        $urlManager = \Yii::$app->frontendUrlManager;
                        $url = $urlManager->createAbsoluteUrl(['site/product', 'slug' => $model->slug], true);
						return Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-link']), $url, ['title' => 'Открыть на сайте', 'target' => '_blank']);
                    },
                    'delete' => function ($url, $model, $key) {
                        if (Yii::$app->user->can('admin') === false) return '';

                        return Html::a(
                            '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>',
                            ['delete', 'id' => $key],
                            [
                                'data-toggle' => 'tooltip',
                                'title' => 'Удалить',
                                'data' => [
                                    'method' => 'post',
                                    'confirm' => 'Вы уверены что хотите удалить : ' . $model->name . '?'
                                ]
                            ]
                        );
                    },
                ],
                'buttonOptions' => [
                    'data-toggle' => 'tooltip',
                ]
            ],
        ],
    ]); ?>

</div>
<style>
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

echo Modal::widget(['header' => '<h2>Карточка контакта</h2>']);

$js = <<<JS
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('.contact__js').on('click', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var name = $(this).text();
        $.ajax({
            method: 'post',
            url: url,
            success: function (data) {
                console.log(data);
                $('.modal-header h2').html(name);
                $('.modal-body').html(data);
                $('.modal').modal()
            }
        });
    });

    $('.jsSwitchProduct').on('change', function(event, state) {
        $.post('/backend/product/switch',
            {
                id   : $(this).data('id'),
                type :  $(this).data('type')
            },
            function(data) {
            if (data=='error') {alert('Состояние продукта не может быть изменено');}
        });
    });

    /* Редактирование видео */

    $('[data-role="video-edit-trigger"]').on('dblclick', function () {
        let row = $(this),
            editBlock = row.next();

        row.hide();
        editBlock.addClass('active');

    });

    $('[data-role="video-edit-cancel"]').on('click', function () {
        var btn = $(this),
            editBlock = btn.parent(),
            text = editBlock.prev();

        editBlock.removeClass('active');
        text.show();
    });

    $('[data-role="video-edit-save"]').on('click', function () {
        var btn = $(this),
            editBlock = btn.parent(),
            text = editBlock.prev(),
            textarea = editBlock.find('textarea');

        var items = textarea.val().trim().split('\\n'),
            id = editBlock.data('id');

        var data = {
            id: id,
            items: items
        };

        var newText = items.map(function (item) {
            return '<div>' + item + '</div>';
        });

        $.ajax({
            url: '/backend/product/edit-video',
            data: data,
            method: 'POST',
            success: function (response) {
                editBlock.removeClass('active');
                text.html(newText);
                text.show();
            },
            error: function (e) {

            }
        });

        console.log(textarea.val().split('\\n'));
    });

    /* Редактирование цен в таблице просмотра */

    var editModeActive = false,
        cooldown = false,
        activeBtn = null;

    $('[data-role="price-value"]').on('dblclick', function () {

        var span = $(this);

        var value = span.attr('data-value'),
            editBlock = span.next(),
            input = editBlock.find('input');
        input.val(value);

        span.hide();
        editBlock.addClass('active');

        activeBtn = editBlock.find('[data-role="price-edit-save"]');

        editModeActive = true;
    });

    $('[data-role="price-edit-cancel"]').on('click', function () {
        var btn = $(this),
            editBlock = btn.parent(),
            span = editBlock.prev();

        editBlock.removeClass('active');
        span.show();

        editModeActive = false;
        activeBtn = null;
    });

    function sendRequest(saveBtn) {
        var btn = saveBtn,
            editBlock = btn.parent(),
            span = editBlock.prev(),
            input = editBlock.find('input');

        var data = {
            id: span.data('id'),
            type: span.data('type'),
            val: input.val()
        };

        $.ajax({
            method: 'POST',
            url: '/backend/product/edit-price',
            data: data,
            beforeSend: function () {
                cooldown = true;
            },
            success: function (response) {
                editBlock.removeClass('active');
                span.text(response.newPrice);
                span.attr('data-value', input.val())
                span.show();
                editModeActive = false;
                cooldown = false;
                activeBtn = null;
            },
            error: function (e) {
                console.log(e);
                alert('Возникла ошибка');
                editModeActive = false;
                cooldown = false;
                activeBtn = null;
            }
        });
    }

    $('[data-role="price-edit-save"]').on('click', function () {
        sendRequest($(this));
    });

    $(document).on('keyup', function (event) {
        if (event.keyCode === 13 && editModeActive && cooldown === false) {
            sendRequest(activeBtn);
        }
    });
})
JS;

$this->registerJs($js);
