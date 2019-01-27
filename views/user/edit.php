<?php
/**
 * Created by PhpStorm.
 * User: wolf
 * Date: 27.01.19
 * Time: 22:45
 */

/**
 * @var $this \yii\web\View
 * @var $model \app\models\User
 */

$this->title = Yii::t('app', 'Profile');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-edit">
    <?php $form = \yii\widgets\ActiveForm::begin() ?>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <dl class="dl-horizontal">
                        <dt><?= Yii::t('app', 'Username') ?></dt>
                        <dd><?= \yii\helpers\HtmlPurifier::process($model->username) ?></dd>
                        <dt><?= Yii::t('app', 'Account') ?></dt>
                        <dd><?= \yii\helpers\HtmlPurifier::process($model->account) ?></dd>
                    </dl>
                    <div class="form-group field-item-end_price required">
                        <label class="control-label" for="account"><?= Yii::t('app', 'Account') ?> </label>
                        <?= \yii\helpers\Html::input('number', 'account', '0', [
                            'id' => 'account',
                            'class' => 'form-control'
                        ]); ?>
                    </div>
                    <?= \yii\helpers\Html::submitButton(Yii::t('app', 'Up Account'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
    </div>
    <?php \yii\widgets\ActiveForm::end(); ?>
</div>
