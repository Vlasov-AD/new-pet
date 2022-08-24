<?php

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <?php
    NavBar::begin([
        'brandLabel' => 'krepost93',
        'brandUrl' => '/',
        'innerContainerOptions' => [
	        'class' => 'container-fluid'
        ],
        'options' => [
            'class' => 'navbar navbar-default navbar-static-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            [
                'label' => 'Товары',
                'url' => ['/product/index'],
                'visible' => Yii::$app->user->can('admin')
            ],
            [
                'label' => 'Категории',
                'url' => ['/category/index'],
                'visible' => Yii::$app->user->can('admin')
            ],
            [
                'label' => 'Характеристики',
                'url' => ['/attribute/index'],
                'visible' => Yii::$app->user->can('admin')
            ],
            [
                'label' => 'Пользователи',
                'url' => ['/user/index'],
                'visible' => !Yii::$app->user->isGuest
            ],
            [
                'label' => 'Очистить кэш',
                'url' => ['/site/cache'],
                'visible' => Yii::$app->user->can('admin')
            ],
            [
                'label' => 'Страницы',
                'visible' => Yii::$app->user->can('admin'),
                'items' => [
                    [
                        'label' => 'Блок кэш',
                        'url' => ['/page/block-cache'],
                        'visible' => Yii::$app->user->can('admin')
                    ]
                ]
            ],
            [
                'label' => 'Выход',
                'url' => ['/site/logout'],
                'visible' => !Yii::$app->user->isGuest
            ],
        ],
    ]);
    NavBar::end();
    ?>
    <div class="section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    echo Breadcrumbs::widget([
                        'homeLink' => [
                            'label' => 'Мои виджеты',
                            'url' => ['/site/index']
                        ],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]);
                    $flashes = Yii::$app->session->allFlashes;
                    foreach ($flashes as $type => $flash) {
                        echo Alert::widget([
                            'options' => [
                                'class' => 'alert-' . $type,
                            ],
                            'body' => $flash
                        ]);
                    }
                    ?>
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
