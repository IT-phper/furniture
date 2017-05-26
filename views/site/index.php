<!-- 引入 ECharts 文件 -->
<script src="/js/echarts.js"></script>
<?php 
$this->title = '家具销售管理系统首页';
$redis = \Yii::$app->redis;
?>
<h4>
<button type="button" class="btn btn-info">系统历史登录次数:<strong><?=$redis->get('login_count')?></strong></button>
</h4>
<h2>总店销售统计</h2>
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
<div id="bing" style="width: 60%;height:400px;"></div>
<script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('main'));
    var myBing = echarts.init(document.getElementById('bing'));
     // 指定图表的配置项和数据
    var option = {
        title: {
            text: '销售额柱状图',
            subtext: '<?=$from?>一<?=$to?>',
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


    var option1 = {
        title : {
            text: '各分店销售额统计',
            subtext: '<?=$from?>一<?=$to?>',
            x:'center'
        },
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            left: 'left',
        },
        series : [
            {
                name: '分店',
                type: 'pie',
                radius : '55%',
                center: ['50%', '60%'],
                data:<?=$shop_sale_count?>,
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };

    myBing.setOption(option1);
</script>