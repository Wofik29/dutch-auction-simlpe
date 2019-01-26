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
 */

$this->title = Yii::t('app', 'Auction');

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
                'columns' => [
                    [
                        'attribute' => 'seller_id',
                        'value' => 'seller.username',
                    ],
                    'name',
                    'currentPrice',
                ],
            ])
            ?>
        </div>
    </div>
</div>
