<?php 

$this->title = '销售额报表';
$this->params['breadcrumbs'][] = $this->title;

?>

<!-- 引入 ECharts 文件 -->
<script src="/js/echarts.js"></script>



<div class="form-group">
    <form  method="get" >
        <label class="control-label col-md-3">选择日期范围</label>
        <div class="col-md-4">
            <div class="input-group input-large custom-date-range">
                <input type="text" class="form-control dpd1" name="from" value=<?= \Yii::$app->request->get('from') ? \Yii::$app->request->get('from') : date('m/d/Y', time()-6*60*60*24) ?>>
                <span class="input-group-addon">To</span>
                <input type="text" class="form-control dpd2" name="to" value=<?= \Yii::$app->request->get('to') ? \Yii::$app->request->get('to') : date('m/d/Y', time()) ?>>
                
            </div>
        </div>
        <button class="btn btn-default" type="submit">搜索</button>
    </form>
</div>
<table class="table table-bordered">
    <th>日期范围总销售额：</th>
    <th><strong><?=$total?>元</strong></th>
</table>
<!-- 为ECharts准备一个具备大小（宽高）的Dom -->
<div id="main" style="width: 100%;height:400px;"></div>
<script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main'));

     // 指定图表的配置项和数据
    var option = {
        title: {
            text: '<?=$from?>一<?=$to?>销售额柱状图'
        },
        tooltip: {},
        legend: {
            data:['销售额']
        },
        xAxis: {
            data: [<?= $string ?>]
        },
        yAxis: {},
        series: [{
            name: '销售额',
            type: 'bar',
            legendHoverLink: true,
            coordinateSystem: 'cartesian2d',
            data: [
            	<?php
            		foreach ($data as $v) {
            			echo $v . ',';
            		} 
            	?>
            ]
        }]
    };
    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
</script>

