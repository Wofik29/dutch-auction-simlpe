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
 * @var $items array of models
 */

use yii\bootstrap\Modal;
\app\assets\ModalRemoteAsset::register($this);
\app\assets\AuctionAsset::register($this);

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
                'filterModel' => $searchModel,
                'id' => 'auction-items',
                'columns' => require __DIR__ . '/_columns.php',
            ])
            ?>
        </div>
    </div>
</div>

<script>
    var items = <?= json_encode($items) ?>;
</script>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "footer" => "",// always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>
