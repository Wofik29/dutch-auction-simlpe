<?php
/**
 * Created by PhpStorm.
 * User: wolf
 * Date: 26.01.19
 * Time: 19:45
 */

/**
 * @var $model \app\models\Item
 */

?>

<?php $form = \yii\widgets\ActiveForm::begin() ?>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'name')->textInput() ?>
        <?= $form->field($model, 'desc')->textarea(['rows' => 10]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'start_price')->textInput() ?>
        <?= $form->field($model, 'end_price')->textInput() ?>
        <?= $form->field($model, 'step_price')->textInput() ?>
        <?= $form->field($model, 'step_time')->textInput() ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?= \yii\helpers\Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn pull-right ' . ($model->isNewRecord ? 'btn-success' : 'btn-primary')]) ?>
    </div>
</div>

<?php \yii\widgets\ActiveForm::end() ?>