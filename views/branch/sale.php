<?php

use yii\helpers\Html;

$this->title = '商品销售管理';
$this->params['breadcrumbs'][] = $this->title;

?>

<table class="table  table-hover general-table">
    <thead>
    <tr>
        <th> 商品名</th>
        <th class="hidden-phone">图片</th>
        <th>单价</th>
        <th>库存</th>
        <th>购买数量</th>
    </tr>
    </thead>
    <tbody>
    <form method="post" action="/branch/do_sale" id="sale">
    	<input type="hidden" name="shop_id" value="<?= $shop_id?>">
   		<input type="hidden" name="_csrf" value="<?= Yii::$app->getRequest()->getCsrfToken()?>">
   		<input type="hidden" name="total">
	<?php 
		foreach ($model as $shop) {
	?>
	<tr>
        <td><?=$shop->goods->name?></td>
        <td class="hidden-phone">
			<?php 
                if ($shop->goods->picture) {
                    echo Html::img("/uploads/{$shop->goods->picture}", ['width' => 100 , 'height' => 45]);
                }
            ?>
        </td>
        <input type="hidden" name="Sale[<?=$shop->goods->id?>][id]" value="<?=$shop->id?>">
        <input type="hidden" name="Sale[<?=$shop->goods->id?>][retail_price]" value="<?=$shop->sale_price?>">
        <td><?=$shop->sale_price?></td>
        <td><?=$shop->sale_num?></td>
        <td><input type="number" class="sale" min="0" name="Sale[<?=$shop->goods->id?>][order_num]"></td>
    </tr>
	<?php
		}
	?>
	<tr>
   		<td></td>
   		<td></td>
   		<td></td>
   		<td style="font-weight:bold;font-size:1.2em">总价：</td>
   		<td style="font-weight:bold;font-size:1.2em" class="total">0</td>
   	</tr>
    </tbody>
</table>
<p style="text-align: center">
	<button type="submit" class="btn btn-primary">确定</button>
</p>
</form>

<script type="text/javascript">
$('.sale').on('change', function() {
	var all = parseInt($(this).parent().prev('td').html());
	var num = parseInt($(this).val());
	if (num>all) {
		swal("选择数量有误", "", "error");
   		$(this).val(0);
	}
	var num = 0;
	$('.sale').each(function(){
		var calc_num = parseInt($(this).val());
			if (calc_num) {
				var calc_price = parseInt($(this).parent().prev('td').prev('td').html());
				num += calc_num*calc_price;
			} 
	});
	$('.total').html(num);
	$("input[name='total']").val(num);
})

$('#sale').submit(function(){
	var tot = $('.total').html();
	if (tot == 0) {
		swal("未选择商品", "", "error");
		return false;
	}
});
</script>