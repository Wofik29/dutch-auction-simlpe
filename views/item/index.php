<?php
/**
 * Created by PhpStorm.
 * User: wolf
 * Date: 27.01.19
 * Time: 23:41
 */

/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $searchModel \app\models\ItemSearch
 *
 */

$this->title = Yii::t('app', 'All Items');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="buy-history">
    <div class="row">
        <div class="col-md-12">
            <?=
            \yii\grid\GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'id' => 'auction-items',
                'columns' => [
                    [
                        'attribute' => 'seller_id',
                        'value' => 'seller.username',
                        'filter' => \yii\helpers\ArrayHelper::map(\app\models\User::getSellers(), 'id', 'username'),
                    ], [
                        'attribute' => 'buyer_id',
                        'value' => 'buyer.username',
                        'filter' => \yii\helpers\ArrayHelper::map(\app\models\User::getClients(), 'id', 'username'),
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
                ]
            ])
            ?>
        </div>
    </div>
</div>