<?php

namespace app\controllers;

use app\components\BaseController;
use app\models\Item;
use yii\data\ActiveDataProvider;

class AuctionController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        return $behaviors;
    }

    public function actionIndex()
    {
        $dataProvider  = new ActiveDataProvider([
            'query' => Item::find(),
        ]);

        return $this->render('index', compact('dataProvider'));
    }

}
