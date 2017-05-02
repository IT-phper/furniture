<?php

use yii\widgets\LinkPager;
use yii\helpers\Html;

$this->title = '商品库存列表';
$this->params['breadcrumbs'][] = $this->title;

?>
<div>
    <form id="localSearch" action="/branch/index" method="get">
        <table id="j-server-table" class="table table-bordered table-hover">
            <tr>
                <th>商品名</th>
                <th style="width:105px;">图片</th>
                <th style="width:80px">库存</th>
                <th style="width:100px;">总部指导价</th>
                <th style="width:100px;">销售价</th>
                <th>规格</th>
                <th style="width:100px;">入店时间</th>
                <th style="width:206px;">操作</th>
            </tr>
            <tr class="send">
                <td><input type="text" class="form-control" name="username"  value="<?= Yii::$app->request->queryParams['username'] ?>" ></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <?php foreach ($s_goods as $good) { ?>
            <tr data-id=<?= $good['id'] ?>>
                <td data-canEdit="true" data-type="input"><span><?= $good->goods->name?></span></td>
                <td data-canEdit="true" data-type="input"><span>
                    <?php 
                        if ($good->goods->picture) {
                            echo Html::img("/uploads/{$good->goods->picture}", ['width' => 100 , 'height' => 45]);
                        }
                    ?>
                </span></td>
                <td data-canEdit="true" data-type="input" style=""><span><?= $good->sale_num?></span></td>
                <td data-canEdit="true" data-type="input" style=""><span><?= $good->goods->price ?></span></td>
                <td data-canEdit="true" data-type="input" style=""><span><?= $good->sale_price?></span></td>
                <td data-canEdit="true" data-type="input" style=""><span><?= $good->goods->spec?></span></td>
                <td data-canEdit="true" data-type="input" style=""><span><?= date('Y-m-d', strtotime($good->created))?></span></td>

                <td>
                    <div class="btn-group btn-group-xs">
                        <button class="btn btn-info" data-toggle="modal" data-target="#modal-edit" type="button">修改价格</button>
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

<div id="modal-edit" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
          <form action="/branch/update_price" class="form-horizontal" method="post">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">&times;</button>
                <h4 class="modal-title">修改商品价格</h4>
            </div>
            <div class="modal-body clearfix">
                <input type="hidden" name="id">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->getRequest()->getCsrfToken()?>">
                <div class="form-group">
                        <label class="control-label col-md-4" for="administrators-group">商品定价</label>
                        <div class="col-md-6">
                            <input id="administrators-group" class="form-control" type="number" min="0" name='sale_price'>                       
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="submit" class="btn btn-primary j-save-btn">保存</button>
            </div>
          </form>
        </div>
    </div>
</div>

<script type="text/javascript">

    //编辑价格 表单id信息
    $('#modal-edit').on('show.bs.modal', function (event) {
        var $trigger = $(event.relatedTarget);
        var id = $trigger.parents('tr').data('id');
        $(this).find('input[name="id"]').val(id);
    });

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