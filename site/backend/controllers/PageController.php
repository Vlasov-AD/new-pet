<?php
declare(strict_types=1);

namespace backend\controllers;

use backend\models\search\ProductSearch;
use common\models\Product;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\widgets\Menu;

/**
 * Главный контроллер админки
 * @package backend\controllers
 */
class PageController extends Controller
{

    /**
     * Подключенные поведения
     * @return array
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'block-cache' => ['get'],
                ],
            ],
        ];
    }

    /**
     * Подключенные внешние экшены
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    public function actionBlockCache()
    {
        $products = Product::find()
            ->limit(200)
            ->with(['categories', 'mainCategory'])
            ->orderBy(['created_at' => SORT_ASC])
            ->all();

        return $this->render('block-cache', [
            'products' => $products
        ]);
    }
}
