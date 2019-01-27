<?php
/**
 * Created by PhpStorm.
 * User: wolf
 * Date: 26.01.19
 * Time: 20:19
 */

/**
 * @var $this \yii\web\View
 * @var $model \app\models\Item
 */

use yii\bootstrap\Modal;

\app\assets\ModalRemoteAsset::register($this);

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auction'), 'url' => ['/auction']];
$this->params['breadcrumbs'][] = $this->title;

$notSet = '<span class="not-set">' . Yii::t('app', 'Not Set') . '</span>';
?>

<div class="auction-view">
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4><?= Yii::t('app', 'Item') ?></h4>
            </div>
            <div class="panel-body">
                <div class="col-md-6">
                    <dl class="dl-horizontal">
                        <dt><?= Yii::t('app', 'Name') ?></dt>
                        <dd><?= \yii\helpers\HtmlPurifier::process($model->name) ?></dd>
                        <dt><?= Yii::t('app', 'Status') ?></dt>
                        <dd><?= (\app\models\Item::getStatusLabels()[$model->status]) ?></dd>
                        <dt><?= Yii::t('app', 'Start Sell') ?></dt>
                        <dd><?= $model->start_time ? date('H:i:s d-m-Y', $model->start_time) : $notSet ?></dd>
                        <dt><?= Yii::t('app', 'Buyer') ?></dt>
                        <dd><?= $model->buyer_id ? $model->buyer->username : $notSet ?></dd>
                        <dt><?= Yii::t('app', 'Sell Price') ?></dt>
                        <dd><?= $model->sell_price ? $model->sell_price : $notSet ?></dd>
                    </dl>
                    <dl>
                        <dt><?= Yii::t('app', 'Desc') ?></dt>
                        <dd><?= \yii\helpers\HtmlPurifier::process($model->desc) ?></dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="dl-horizontal">
                        <dt><?= Yii::t('app', 'Start Price') ?></dt>
                        <dd><?= \yii\helpers\HtmlPurifier::process($model->start_price) ?></dd>
                        <dt><?= Yii::t('app', 'End Price') ?></dt>
                        <dd><?= \yii\helpers\HtmlPurifier::process($model->end_price) ?></dd>
                        <dt><?= Yii::t('app', 'Step Price') ?></dt>
                        <dd><?= \yii\helpers\HtmlPurifier::process($model->step_price) ?></dd>
                        <dt><?= Yii::t('app', 'Step Time') ?></dt>
                        <dd><?= \yii\helpers\HtmlPurifier::process($model->step_time) ?></dd>
                    </dl>
                </div>
            </div>
            <div class="panel-footer">
                <div class="btn btn-group">
                    <?php if ($model->status == \app\models\Item::STATUS_DRAFT) {
                        echo \yii\helpers\Html::a(Yii::t('app', 'Selling'), ['/auction/sell', 'id' => $model->id], [
                            'class' => 'btn btn-primary',
                            'data-method' => false,
                            'role' => 'modal-remote',
                            'data-request-method' => 'POST',
                            'data-toggle' => 'tooltip',
                        ]);
                        echo \yii\helpers\Html::a(Yii::t('app', 'Edit'), ['/auction/edit', 'id' => $model->id], ['class' => 'btn btn-primary']);
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "footer" => "",// always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>
