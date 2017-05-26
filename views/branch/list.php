<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = '商品销售记录';
$this->params['breadcrumbs'][] = $this->title;

?>

<div>
    <form id="localSearch"  method="get">
        <table id="j-server-table" class="table table-bordered table-hover">
            <tr>
                <th class="">流水号</th>
                <th style="width:150px;">处理人</th>
                <th class="width:150px">创建时间</th>
                <th style="width:100px;">总金额</th>
                <th style="width:150px;">操作</th>
            </tr>
            <tr class="send">
                <td><input type="text" class="form-control" name="order_id"  value="<?= Yii::$app->request->queryParams['order_id'] ?>" ></td>
                <td><input type="text" class="form-control" name="username" value="<?= Yii::$app->request->queryParams['username'] ?>"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <?php foreach ($orders as $order) { ?>
            <tr>
            	<td data-canEdit="true" data-type="input"><span><?= $order['order_id'] ?></span></td>
                <td data-canEdit="true" data-type="input"><span><?= $order->user->real_name ?></span></td>
                <td data-canEdit="true" data-type="input"><span><?= $order['created']?></span></td>
                <td data-canEdit="true" data-type="input"><span><?= $order['total']?></span></td>
                <td>
                    <div class="btn-group btn-group-xs">
                        <button class="btn btn-default">
							<a href="/branch/detail?id=<?=$order['id']?>">
                        		查看详情
                        	</a>
                        </button>
                    </div>
                </td>
            </tr>
            <?php } ?>
        </table>
        <div class="pagination-box pull-right">
            <div class="input-group pull-left">
                <label class="control-label">每页显示</label>
                <div class="pageSize-box">
                    <select id="pageSize" class="form-control" name="per-page">
                        <?php 
                            $page = Yii::$app->params['per-page'];
                            foreach ($page as $per_page) {
                                $default = Yii::$app->request->queryParams['per-page'] == $per_page ? 'selected' : '';
                                echo "<option value= {$per_page} {$default}>{$per_page}</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>
            <nav class="pull-right">
               <?php
                    echo LinkPager::widget([
                        'pagination' => $pagination,
                    ]);
               ?>
            </nav>
        </div>
    </form>
</div>

<script type="text/javascript">
	//搜索功能实现
    // 回车键搜索
    $(document).on('keydown', function(event) {
        var code = event.keyCode;
        if(code === 13) {
            $('#localSearch').submit();
        }
    });
    // 改变select多选框搜索
    $('#j-server-table .send .form-control:not("input")').on('change', function() {
        $('#localSearch').submit();
    });
    //改变分页时搜索
    $('#pageSize').on('change', function() {
        $('#localSearch').submit();
    });
</script>