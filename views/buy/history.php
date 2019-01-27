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

$this->title = Yii::t('app', 'Buy History');
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
                'columns' => require __DIR__ . '/_columns.php',
            ])
            ?>
        </div>
    </div>
</div>
