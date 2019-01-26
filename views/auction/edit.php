<?php
/**
 * Created by PhpStorm.
 * User: wolf
 * Date: 26.01.19
 * Time: 18:50
 */

/**
 * @var $this \yii\web\View
 * @var $model \app\models\Item
 */

$this->title = Yii::t('app', 'Edit');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auction'), 'url' => ['/auction']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['/auction/view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="auction-edit">
    <?= $this->render('form', compact('model')) ?>
</div>