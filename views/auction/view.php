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

$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="auction-view">
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4><?= Yii::t('app', 'Item') ?></h4>
            </div>
            <div class="panel-body">
                <div class="col-md-6">
                    <dl>
                        <dt><?= Yii::t('app', 'Name') ?></dt>
                        <dd><?= \yii\helpers\HtmlPurifier::process($model->name) ?></dd>
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
                <?= \yii\helpers\Html::a(Yii::t('app', 'Edit'), ['/auction/edit', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
</div>