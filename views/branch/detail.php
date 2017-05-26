<?php

use yii\helpers\Html;

$this->title = '商品销售详情';
$this->params['breadcrumbs'][] = ['label' => '商品销售列表', 'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;
?>

<table class="table table-bordered">
    <thead>
    <tr>
        <th>流水号</th>
        <th>创建时间</th>
        <th class="text-center">总金额</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td style="font-weight:bold;font-size:1.2em"><?=$order->order_id?></td>
        <td><?=$order->created?></td>
        <td class="text-center"><?=$order->total?></td>
    </tr>
    </tbody>
</table>

<table class="table  table-hover general-table">
    <thead>
    <tr>
        <th>商品名</th>
        <th class="hidden-phone">商品图片</th>
        <th>商品数量</th>
        <th>单价</th>
    </tr>
    </thead>
    <tbody>
	<?php 
		foreach ($model as $detail) {
	?>
		<tr>
        <td><?=$detail->goods->id?></td>
        <td class="hidden-phone">
        	<?php 
                if ($detail->goods->picture) {
                    echo Html::img("/uploads/{$detail->goods->picture}", ['width' => 100 , 'height' => 45]);
                }
            ?>
        </td>
        <td><?=$detail['order_num']?></td>
        <td><?=$detail['retail_price']?></td>
    </tr>
	<?php
		}
	?>
    </tbody>
</table>