<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\GoodsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '商品信息';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('商品入库', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
            [
                'attribute' => 'picture',
                'format'=>'raw',
                'value' => function($m) {
                    if ($m->picture) {
                        return Html::img("/uploads/{$m->picture}", ['width' => 100 , 'height' => 45]);
                    }
                }
            ],
            'num',
            'price',
            // 'spec',
            // 'intro',
            'created',
            [
                'attribute' => 'status',
                'value' => function($m) {
                    switch ($m->status) {
                        case 1:
                            return '在售';
                        case 2:
                            return '下架';
                    }
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template'=> '{view} {update} {delete} {allocation}',
                'buttons' => [
                    'view' => function($url, $model, $key) {
                        return Html::a('<i class="fa fa-vimeo-square"></i> 查看', ['view', 'id' => $key]);
                    },
                    'update' => function($url, $model, $key) {
                        return Html::a('<i class="fa fa-edit"></i> 修改', ['update', 'id' => $key]);
                    },
                    'delete' => function($url, $model, $key) {
                        return Html::a('<i class="fa fa-ban"></i> 删除', ['delete', 'id' => $key]);
                    },
                    'allocation' => function($url, $model, $key) {
                        return Html::a('<i class="fa fa-angle-double-right"></i> 派发', ['allocation', 'id' => $key]);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
