<?php
/**
 * Created by PhpStorm.
 * User: wolf
 * Date: 27.01.19
 * Time: 19:24
 */

return [
    [
        'attribute' => 'seller_id',
        'value' => 'seller.username',
        'filter' => \yii\helpers\ArrayHelper::map(\app\models\User::getSellers(), 'id', 'username'),
    ],
    'name',
    'start_price',
    [
        'value' => 'currentPrice',
        'attribute' => 'currentPrice',
        'options' => ['data' => ['type' => 'current-price']],
        'headerOptions' => ['data' => ['type' => 'current-price']],
        'contentOptions' => ['data' => ['type' => 'current-price']],
    ],
    [
        'class' => \yii\grid\ActionColumn::class,
        'template' => '{view} {close} {buy}',
        'buttonOptions' => [
            'class' => 'btn btn-default btn-sm',
        ],
        'buttons' => [
            'close' => function ($url, $model, $key) {
                return \yii\helpers\Html::a('<span class="glyphicon glyphicon-remove"></span>', [
                    '/auction/close', 'id' => $model['id'],
                ], [
                    'title' => \Yii::t('app', 'Close'),
                    'class' => 'btn btn-danger btn-sm',
                    'data-method' => false,
                    'role' => 'modal-remote',
                    'data-request-method' => 'POST',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'buy' => function ($url, $model, $key) {
                return \yii\helpers\Html::a('<span class="glyphicon glyphicon-rub"></span>', [
                    '/auction/buy', 'id' => $model['id'],
                ], [
                    'title' => \Yii::t('app', 'Buy'),
                    'class' => 'btn btn-success',
                    'data-method' => false,
                    'role' => 'modal-remote',
                    'data-request-method' => 'POST',
                    'data-toggle' => 'tooltip',
                ]);
            }
        ],
        'visibleButtons' => [
            'view' => true,
            'close' => function ($model, $key, $index) {
                return (Yii::$app->user->can('drop_item') || Yii::$app->user->can('drop_item_foreign')) &&
                    $model['status'] == \app\models\Item::STATUS_SELLING;
            },
            'buy' => function ($model, $key, $index) {
                return !($model['seller_id'] == Yii::$app->user->getId()) && Yii::$app->user->can('client');
            }
        ]
    ],
];