<?php

namespace app\controllers;

use app\components\BaseController;
use app\models\Item;
use app\models\ItemSearch;
use app\models\RegistrForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\LoginForm;
use app\models\ContactForm;

class ItemController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['buy', 'view'],
                        'roles' => ['client', 'edit_item_foreign'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['sell', 'view'],
                        'roles' => ['seller', 'edit_item_foreign'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['edit_item_foreign'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ]
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ItemSearch();
        $params = \Yii::$app->request->queryParams;

        $dataProvider = $searchModel->search($params);
        $type = 'buy';

        return $this->render('index', compact('dataProvider', 'searchModel', 'type'));
    }

    public function actionBuy()
    {
        $searchModel = new ItemSearch();
        $params = \Yii::$app->request->queryParams;
        $params['ItemSearch']['buyer_id'] = \Yii::$app->user->getId();

        $dataProvider = $searchModel->search($params);
        $type = 'buy';

        return $this->render('history', compact('dataProvider', 'searchModel', 'type'));
    }

    public function actionSell()
    {
        $searchModel = new ItemSearch();
        $params = \Yii::$app->request->queryParams;
        $params['ItemSearch']['seller_id'] = \Yii::$app->user->getId();

        $dataProvider = $searchModel->search($params);
        $type = 'sell';

        return $this->render('history', compact('dataProvider', 'searchModel', 'type'));
    }

    public function actionView($id)
    {
        $model = Item::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException(\Yii::t('app', 'Not Found Model'));
        }

        $isSell = $model->seller_id == \Yii::$app->user->getId();
        $isBuy = $model->buyer_id == \Yii::$app->user->getId();

        if (!$isSell && !$isBuy || \Yii::$app->user->can('edit_item_foreign')) {
            throw new ForbiddenHttpException(\Yii::t('app', 'Forbidden'));
        }

        return $this->render('view', compact('model'));
    }

}
