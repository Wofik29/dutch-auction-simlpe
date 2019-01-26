<?php
/**
 * Created by PhpStorm.
 * User: wolf
 * Date: 26.01.19
 * Time: 18:50
 */

/**
 * @var $this \yii\web\View
 */

$this->title = Yii::t('app', 'Create');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auction'), 'url' => ['/auction']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="auction-create">
    <?= $this->render('form', compact('model')) ?>
</div>