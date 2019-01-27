<?php
/**
 * Created by PhpStorm.
 * User: wolf
 * Date: 26.01.19
 * Time: 14:15
 */

/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $searchModel \app\models\ItemSearch
 */

$this->title = Yii::t('app', 'My Items');

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="auction-index">
    <div class="row">
        <div class="col-md-12">
            <?php if (Yii::$app->user->can('create_item')): ?>
                <?= \yii\helpers\Html::a(Yii::t('app', 'Create'), '/auction/create', ['class' => 'btn btn-success']) ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?=
            \yii\grid\GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'name',
                    'start_price',
                    'end_price',
                    'step_time',
                    'step_price',
                    [
                        'attribute' => 'status',
                        'value' => function ($model, $key, $index, $column) {
                            return \app\models\Item::getStatusLabels()[$model['status']];
                        },
                        'filter' => \app\models\Item::getStatusLabels(),
                    ],
                    [
                        'class' => \yii\grid\ActionColumn::class,
                        'template' => '{view} {update} {delete} {sell}',
                        'buttons' => [
                            'sell' => function ($url, $model, $key) {
                                return \yii\helpers\Html::a(\Yii::t('app', 'Selling'), '/auction/sell');
                            }
                        ],
                        'visibleButtons' => [
                            'view' => true,
                            'edit' => function ($model, $key, $index) {
                                return in_array($model['status'], [\app\models\Item::STATUS_DRAFT, \app\models\Item::STATUS_TEMPLATE]);
                            },
                            'delete' => function ($model, $key, $index) {
                                return in_array($model['status'], [\app\models\Item::STATUS_DRAFT, \app\models\Item::STATUS_TEMPLATE]);
                            },
                            'sell' => function ($model, $key, $index) {
                                return Yii::$app->user->can('seller') && $model['status'] == \app\models\Item::STATUS_DRAFT;
                            }
                        ]
                    ],
                ],
            ])
            ?>
        </div>
    </div>
</div>
