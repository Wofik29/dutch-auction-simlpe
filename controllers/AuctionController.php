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
                        'actions' => ['index', 'view'],
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

    public function actionCreate()
    {
        $model = new Item();
        $model->status = Item::STATUS_DRAFT;
        $model->seller_id = \Yii::$app->user->getId();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                $this->redirect(['/auction/view', 'id' => $model->id]);
            }
        }

        return $this->render('create', compact('model'));
    }

    public function actionView($id)
    {
        $model = Item::findOne($id);

        return $this->render('view', compact('model'));
    }

}
