<?php
declare(strict_types=1);

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\form\Login;
use yii\filters\VerbFilter;

/**
 * Главный контроллер админки
 * @package backend\controllers
 */
class SiteController extends Controller
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
                    'index' => ['get'],
                    'logout' => ['get','post'],
                    'add' => ['get','post'],
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
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

	/**
	 * Страница авторизации
	 * @return string|\yii\web\Response
	 */
	public function actionLogin()
	{
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new Login();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->goBack();
		} else {
			return $this->render('login', [
				'model' => $model,
			]);
		}
	}

    /**
     * Отображение главной страницы
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Страница выхода
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionCache()
    {
        Yii::$app->cache->flush();
        Yii::$app->cacheFrontend->flush();
        Yii::$app->session->setFlash('success', 'Кэш успешно очищен!');

        return $this->redirect(['site/index']);
    }

}
