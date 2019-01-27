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
    [
        'attribute' => 'start_time',
        'format' => ['date', 'php:H:i:s d-m-Y '],
    ],
    'start_price',
    'sell_price',
    [
        'class' => \yii\grid\ActionColumn::class,
        'template' => '{view}',
        'buttonOptions' => [
            'class' => 'btn btn-default',
        ],
    ],
];