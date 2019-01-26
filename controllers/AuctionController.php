<?php

namespace app\controllers;

use app\components\BaseController;
use app\models\Item;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

class AuctionController extends BaseController
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
                        'actions' => ['index'],
                        'roles' => ['@'],
                        'allow' => true,
                    ], [
                        'actions' => ['create'],
                        'roles' => ['create_item'],
                        'allow' => true,
                    ], [
                        'allow' => false,
                        'roles' => ['?'],
                    ]
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $dataProvider  = new ActiveDataProvider([
            'query' => Item::find(),
        ]);

        return $this->render('index', compact('dataProvider'));
    }

}
