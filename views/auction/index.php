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
    <?=
    \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'seller.username',
            'name',
            'current_price',
        ],
    ])
    ?>

</div>
