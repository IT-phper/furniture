<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Goods */

$this->title = '入库商品';
$this->params['breadcrumbs'][] = ['label' => '商品信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
