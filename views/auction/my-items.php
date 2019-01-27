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

use yii\bootstrap\Modal;

$this->title = Yii::t('app', 'My Items');

$this->params['breadcrumbs'][] = $this->title;

\app\assets\ModalRemoteAsset::register($this);
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
                        'template' => '{view} {update} {delete} {sell} {close}',
                        'buttons' => [
                            'sell' => function ($url, $model, $key) {
                                return \yii\helpers\Html::a(\Yii::t('app', 'Selling'), [
                                    '/auction/sell', 'id' => $model['id'],
                                ], [
                                    'data-confirm' => false,
                                    'data-method' => false,
                                    'role' => 'modal-remote',
                                    'data-request-method' => 'POST',
                                    'data-toggle' => 'tooltip',
                                ]);
                            },
                            'close' => function ($url, $model, $key) {
                                return \yii\helpers\Html::a(\Yii::t('app', 'Close'), [
                                    '/auction/close', 'id' => $model['id'],
                                ], [
                                    'data-method' => false,
                                    'role' => 'modal-remote',
                                    'data-request-method' => 'POST',
                                    'data-toggle' => 'tooltip',
                                ]);
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
                            },
                            'close' => function ($model, $key, $index) {
                                return Yii::$app->user->can('drop_item') && Yii::$app->user->can('drop_item_foreign') &&
                                    $model['status'] == \app\models\Item::STATUS_SELLING;
                            }
                        ]
                    ],
                ],
            ])
            ?>
        </div>
    </div>
</div>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "footer" => "",// always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>


