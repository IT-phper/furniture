<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Goods */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '商品信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            [
                'attribute' => 'picture',
                'format' => ['image', ['width' => '160', 'height' => '100',]],
                'value' => function ($model) {
                    if ($model->picture) {
                        return "/uploads/{$model->picture}";
                    }
                }
            ],
            'num',
            'price',
            'spec',
            'intro',
            'created',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    switch ($model->status) {
                        case 1:
                            return '在售';
                        case 2:
                            return '下架';
                        default:
                            return '删除';
                    }
                }
            ],
        ],
    ]) ?>

</div>
