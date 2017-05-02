<?php

$this->title = '派发商品';
$this->params['breadcrumbs'][] = ['label' => '商品信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<table class="table table-bordered">
    <thead>
    <tr>
        <th>商品ID</th>
        <th>商品名</th>
        <th class="text-center">总库存</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td class="text-center" id="total" style="font-weight:bold;font-size:1.2em"><?=$model->num?></td>
    </tr>
    </tbody>
</table>
<table class="table  table-hover general-table">
    <thead>
    <tr>
        <th> 分店ID</th>
        <th class="hidden-phone">分店Name</th>
        <th>分店库存</th>
        <th>分配</th>
    </tr>
    </thead>
    <tbody>
    <form method="post" action="/goods/exec-allo">
   		<input type="hidden" name="_csrf" value="<?= Yii::$app->getRequest()->getCsrfToken()?>">
   		<input type="hidden" name="fid" value="<?=$model->id?>">
	<?php 
		foreach ($shops as $shop) {
	?>
		<tr>
        <td><?=$shop['id']?></td>
        <td class="hidden-phone"><?=$shop['name']?></td>
        <td><?=$shop['number']?></td>
        <input type="hidden" name="Goods[<?=$shop['id']?>][shop_id]" value="<?= $shop['id'] ?>">
        <td><input type="number" min="0" class="allo" name="Goods[<?=$shop['id']?>][sale_num]?>"></td>
    </tr>
	<?php
		}
	?>
    </tbody>
</table>
<p style="text-align: center">
	<button type="submit" class="btn btn-primary">确定</button>
</p>
</form>

<script type="text/javascript">
	 $('.allo').on('change', function() {
	 	var total = parseInt($('#total').html());
        // a = $(this).val();
        var sum = 0;
   		$('.allo').each(function(){
   			every = $(this).val(); 
   			if (every) {
   				sum += parseInt(every);
   			}	
   		});
   		if (sum>total) {
   			swal("配发数不能大于总库存数!", "", "error");
   			$(this).val(0);
   		}

    });
</script>