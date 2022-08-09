<?php
declare(strict_types=1);

namespace backend\controllers;

use common\models\Product;
use common\models\ProductAttr;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use aquy\gallery\GalleryManagerAction;
use backend\models\search\ProductSearch;
use himiklab\sortablegrid\SortableGridAction;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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
                            'galleryApi',
                            'delete',
                            'sort',
                            'child',
                            'attr',
                            'coord-update'
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'galleryApi' => ['post'],
                    'sort' => ['post'],
                    'child' => ['post'],
                    'attr' => ['post'],
                    'coord-update' => ['post']
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
            'sort' => [
                'class' => SortableGridAction::class,
                'modelName' => Product::class,
            ],
            'galleryApi' => [
                'class' => GalleryManagerAction::class,
                'types' => [
                    'product' => Product::class,
                    'product_fields' => Product::class
                ]
            ],
            'attr' => [
                'class' => SortableGridAction::class,
                'modelName' => ProductAttr::class,
            ],
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionView(int $id)
    {
        $model = $this->findModel($id);

        if (!$model) {
            throw new NotFoundHttpException('Товар не найден!');
        }

        return $this->render('view', [
            'model' =>$model,
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product([
            'available' => Product::STATUS_AVAILABLE,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['update', 'id' => $model->id]);
        }

        $error = $model->errors;

        return $this->render('create', [
            'model' => $model,
        ]);

    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate(int $id)
    {
        $model = Product::find()
            ->where(['id' => $id])
            ->one();

        if (!$model) {
            throw new NotFoundHttpException('Товар не найден!');
        }

        $productAttrForm = new ProductAttr(['product_id' => $model->id]);

        $productAttrs = new ActiveDataProvider([
            'query' => ProductAttr::find()
                ->where(['product_id' => $id])
                ->orderBy('sort')
        ]);
        $productAttrs->pagination = false;


        if (Yii::$app->request->isPost && Yii::$app->request->post('Product') && $model->load(Yii::$app->request->post()) && $model->save()) {

            Yii::$app->session->setFlash('success', 'Оборудование успешно отредактировано');
            return $this->redirect([
                'update',
                'id' => $model->id,
            ]);
        }


        //редактирование характеристик
        if (Yii::$app->request->isPost && Yii::$app->request->post('ProductAttr') && $productAttrForm->load(Yii::$app->request->post()) && $productAttrForm->validate()) {
            $productAttrForm->addOrUpdateRelation();
            return $this->redirect([
                'update',
                'id' => $model->id,
            ]);
        }


        //удаление характеристик
        if (Yii::$app->request->isPost && Yii::$app->request->post('delete-attr')) {
            (new ProductAttr())->deleteByPrimary((int) Yii::$app->request->post('delete-attr'));
            return $this->redirect([
                'update',
                'id' => $model->id,
            ]);
        }

        return $this->render('update', [
            'model' => $model,
            'productAttrForm' => $productAttrForm,
            'productAttrs' => $productAttrs
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete(int $id)
    {
        $model = $this->findModel($id);

        if (!$model) {
            throw new NotFoundHttpException('Товар не найден!');
        }
        $model->delete();

        return $this->redirect(['index']);
    }


    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
