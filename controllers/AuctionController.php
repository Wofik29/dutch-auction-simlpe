<?php

namespace app\controllers;

use app\components\BaseController;
use app\models\Item;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

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
                        'actions' => ['edit'],
                        'roles' => ['edit_item', 'edit_item_foreign'],
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

    public function actionEdit($id)
    {
        $model = Item::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException(\Yii::t('app', 'Not Found Model'));
        }

        if (!in_array($model->status, [Item::STATUS_DRAFT, Item::STATUS_TEMPLATE])) {
            \Yii::$app->session->addFlash('warning', \Yii::t('app', 'Not allow edit no draft or no template'));
            $this->goBack();
        }

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                $this->redirect(['/auction/view', 'id' => $model->id]);
            }
        }

        return $this->render('edit', compact('model'));
    }

    public function actionView($id)
    {
        $model = Item::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException(404, \Yii::t('app', 'Not Found Model'));
        }

        return $this->render('view', compact('model'));
    }

}
