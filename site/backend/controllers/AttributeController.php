<?php
namespace backend\controllers;

use aquy\gallery\GalleryManagerAction;
use common\models\Attribute;
use common\models\Category;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;


class AttributeController extends Controller
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
                            'delete',
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
     * Отображение главной страницы
     * @return string
     */
    public function actionIndex()
    {
        $params = Attribute::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $params,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Attribute();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create',
            ['model' => $model]
        );
    }

    public function actionView(int $id)
    {
        $model = Attribute::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Указанная характеристика не найдена');
        }

        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionUpdate(int $id)
    {
        $model = Attribute::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Характеристика не найдена!');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $id]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete(int $id)
    {
        $model = Attribute::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Категория не найдена!');
        }

        $model->delete();
        return $this->redirect(['index']);
    }

}
