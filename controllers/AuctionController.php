<?php

namespace app\controllers;

use app\components\BaseController;
use app\models\Item;
use app\models\ItemSearch;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\grid\GridView;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

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
                        'actions' => ['index', 'view', 'updater'],
                        'roles' => ['@'],
                        'allow' => true,
                    ], [
                        'actions' => ['buy',],
                        'roles' => ['client'],
                        'allow' => true,
                        'verbs' => ['POST'],
                    ], [
                        'actions' => ['sell',],
                        'roles' => ['create_item'],
                        'allow' => true,
                        'verbs' => ['POST'],
                    ], [
                        'actions' => ['close',],
                        'roles' => ['drop_item_foreign', 'drop_item'],
                        'allow' => true,
                        'verbs' => ['POST'],
                    ], [
                        'actions' => ['create', 'my-items'],
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
        $searchModel = new ItemSearch();
        $params = \Yii::$app->request->queryParams;
        $params['ItemSearch']['status'] = Item::STATUS_SELLING;

        $dataProvider = $searchModel->search($params);

        /** @var Item[] $models */
        $models = $dataProvider->getModels();
        $items = [];
        foreach ($models as $model) {
            $items[] = [
                'id' => $model->id,
                'start_time' => $model->start_time,
                'start_price' => floatval($model->start_price),
                'step_time' => $model->step_time,
                'step_price' => floatval($model->step_price),
                'end_price' => $model->end_price,
                'current_price' => $model->currentPrice,
            ];
        }


        return $this->render('index', compact('dataProvider', 'searchModel', 'items'));
    }

    public function actionMyItems()
    {
        $searchModel = new ItemSearch();
        $params = \Yii::$app->request->queryParams;
        if (\Yii::$app->user->can('edit_item')) {
            $params['ItemSearch']['seller_id'] = \Yii::$app->user->getId();
        }

        $dataProvider = $searchModel->search($params);
        $items = $dataProvider->getModels();

        return $this->render('my-items', compact('dataProvider', 'searchModel', 'items'));

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
            throw new NotFoundHttpException(\Yii::t('app', 'Not Found Model'));
        }

        return $this->render('view', compact('model'));
    }

    public function actionSell($id)
    {
        $model = Item::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException(\Yii::t('app', 'Not Found Model!'));
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;
        if ($model->setSelling()) {
            \Yii::$app->session->addFlash('success', \Yii::t('app', 'Success change status to Sell!'));
            return $this->goBack();
        } else {
            throw new ForbiddenHttpException($model->getFirstError('status'));
        }
    }

    public function actionUpdater()
    {
        ignore_user_abort(false);

        $delay = 10;
        $endTime = time() + $delay;

        $ids = \Yii::$app->request->get('ids', []);

        /**
         * Because request lock session file,
         * another request same user will not pass, until this action is end
         */
        \Yii::$app->session->close();
        while ($endTime > time()) {
            $results = [];
            $toRemove = $ids;

            /** @var Item[] $models */
            $models = Item::findAll(['status' => Item::STATUS_SELLING]);
            foreach ($models as $model) {
                if (in_array($model->id, $ids)) {
                    $keys = array_flip($toRemove);
                    unset($toRemove[$keys[$model->id]]);

                    /**
                     * Check is finish time item
                     */
                    $isSell = $model->isSelling();
                    if (!$isSell) {
                        $results[] = [
                            'id' => $model->id,
                            'action' => 'drop',
                        ];
                    }
                } else {
                    $grid = new GridView([
                        'dataProvider' => new ArrayDataProvider(['allModels' => [$model]]),
                        'columns' => require \Yii::getAlias('@app/views/auction/_columns.php'),
                    ]);

                    $results[] = [
                        'action' => 'new_item',
                        'item' => [
                            'id' => $model->id,
                            'start_time' => $model->start_time,
                            'start_price' => floatval($model->start_price),
                            'step_time' => $model->step_time,
                            'step_price' => floatval($model->step_price),
                            'end_price' => $model->end_price,
                            'current_price' => $model->currentPrice,
                            'status' => $model->status,
                        ],
                        'html' => $grid->renderTableRow($model, $model->id,0),
                    ];

                }
            }

            if ($toRemove) {
                foreach ($toRemove as $id) {
                    $results[] = [
                        'id' => $id,
                        'action' => 'drop',
                    ];
                }
            }

            if ($results) {

                break;
            }

            sleep(1);
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $results;
    }

    public function actionBuy($id)
    {
        $model = Item::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException(\Yii::t('app', 'Not Found Model!'));
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;
        if ($model->buy()) {
            \Yii::$app->session->addFlash('success', \Yii::t('app', 'Success Buy!'));
            return $this->goBack();
        } else {
            throw new ForbiddenHttpException($model->getFirstError('status'));
        }

    }

    public function actionClose($id)
    {
        $model = Item::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException(\Yii::t('app', 'Not Found Model!'));
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;
        if ($model->close()) {
            return [
                'title' => \Yii::t('app', 'Success'),
                'body' => '<div class="alert alert-success">'.\Yii::t('app', 'Congrats!').' '.\Yii::t('app', 'Success Close!').'</div>',
            ];
        } else {
            throw new ForbiddenHttpException($model->getFirstError('status'));
        }
    }
}
