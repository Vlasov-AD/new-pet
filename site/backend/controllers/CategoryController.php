<?php
declare(strict_types=1);

namespace backend\controllers;

use aquy\gallery\GalleryManagerAction;
use common\models\Category;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;


class CategoryController extends Controller
{
    /**
     * Подключенные поведения
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['site/login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'move',
                            'delete',
                            'galleryApi'
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['get'],
                    'galleryApi' => ['post'],
                    'update' => ['get', 'post'],
                    'create' => ['get', 'post'],
                    'logout' => ['get','post'],
                    'login' => ['get','post'],
                    'delete' => ['get', 'post']
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
            'galleryApi' => [
                'class' => GalleryManagerAction::class,
                'types' => [
                    'category' => Category::class
                ]
            ],
        ];
    }

    /**
     * Отображение главной страницы
     * @return string
     */
    public function actionIndex(int $id = 1)
    {
        $currentCategory = Category::findOne($id);
        if (!$currentCategory) {
            throw new NotFoundHttpException('Категория не найдена!');
        }
        $childCategories = $currentCategory->children(1);
        $parentsCategories = $currentCategory->parents()->all();

        $dataProvider = new ActiveDataProvider([
            'query' => $childCategories,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model' => $currentCategory,
            'parents' => $parentsCategories
        ]);
    }

    public function actionCreate(int $id = 1)
    {
        $model = new Category();
        $model->parent_id = $id;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $root = Category::findOne($model->parent_id);
            $model->appendTo($root);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create',
            ['model' => $model]
        );
    }

    public function actionView(int $id)
    {
        $model = Category::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Указанная категория не найдена');
        }

        $parents = $model->parents()->all();

        return $this->render('view', [
            'model' => $model,
            'parents' => $parents
        ]);
    }

    public function actionUpdate(int $id)
    {
        $model = Category::findOne($id);
        if (!$model || $id <= 1) {
            throw new NotFoundHttpException('Категория не найдена!');
        }

        $parents = $model->parents()->all();
        $root = end($parents);
        $model->parent_id = $root->id;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($root->id == $model->parent_id) {
                $model->save();
            } else {
                $root = Category::findOne($model->parent_id);
                $model->appendTo($root);
            }
            return $this->redirect(['view', 'id' => $id]);
        }
        return $this->render('update', [
            'model' => $model,
            'parents' => $parents
        ]);
    }

    public function actionMove(string $type, int $id)
    {
        $model = Category::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Категория не найдена!');
        }

        if ($type == 'up' and ($root = $model->prev()->one())) {
            $model->insertBefore($root);
        }
        if ($type == 'down' and ($root = $model->next()->one())) {
            $model->insertAfter($root);
        }
        $this->redirect(['index', 'id' => $model->parents(1)->one()->id]);
    }

    public function actionDelete(int $id)
    {
        $model = Category::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Категория не найдена!');
        }

        $model->delete();
        return $this->redirect(['index']);
    }

}
