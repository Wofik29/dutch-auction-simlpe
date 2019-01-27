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

class BuyController extends BaseController
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
                        'actions' => ['index', 'view'],
                        'roles' => ['client', 'edit_item_their'],
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
        $params['ItemSearch']['buyer_id'] = \Yii::$app->user->getId();

        $dataProvider = $searchModel->search($params);

        return $this->render('history', compact('dataProvider', 'searchModel'));
    }

    public function actionView($id)
    {
        $model = Item::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException(\Yii::t('app', 'Not Found Model'));
        }

        if ($model->buyer_id != \Yii::$app->user->getId()) {
            throw new ForbiddenHttpException(\Yii::t('app', 'Forbidden'));
        }

        return $this->render('view', compact('model'));
    }

}
