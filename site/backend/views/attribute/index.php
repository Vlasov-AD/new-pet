<?php

use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var ActiveDataProvider[] $dataProvider*/

$this->title = 'Характеристики';



$this->params['breadcrumbs'][] = $this->title;

?>
<div>
    <div class="row">
        <div class="col-xs-12 col-md-3 col-lg-3 col-xl-3">
            <h1><?= Html::encode($this->title) ?> <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?></h1>
        </div>
    </div>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'attribute' => 'name',
                'format' => 'raw'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
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
