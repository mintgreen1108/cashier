<div class="main">
    <div class="row">
        <form>
            <div class="col-sm-3 control-group">
                <input id="start" type="text" class="form-control"
                       data-beatpicker="true" data-beatpicker-position="['*','*']"  data-beatpicker-module="today,clear">
            </div>
            <div class="col-sm-3 control-group">
                <input id="end" type="text" class="form-control"
                       data-beatpicker="true" data-beatpicker-position="['*','*']"  data-beatpicker-module="today,clear">
            </div>
            <div class="col-sm-1 control-group">
                <button type="button" class="btn" onclick="rank.getData()">
                    <span class="fui-search"></span>
                </button>
            </div>
        </form>
    </div>
    <div class="row" style="margin-top: 2%">
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2><i class="fa fa-random red"></i><span class="break"></span><strong>商品分类销售情况(金额)</strong></h2>
                    <div class="panel-actions">
                        <a href="charts-flot.html#" class="btn-close"><i class="fa fa-times"></i></a>
                    </div>
                </div>
                <div class="panel-body">
                    <div id="classchart1" style="height:300px"></div>

                </div>
            </div>
        </div><!--/col-->
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2><i class="fa fa-random red"></i><span class="break"></span><strong>商品分类销售情况(销量)</strong></h2>
                    <div class="panel-actions">
                        <a href="charts-flot.html#" class="btn-close"><i class="fa fa-times"></i></a>
                    </div>
                </div>
                <div class="panel-body">
                    <div id="classchart2" style="height:300px"></div>

                </div>
            </div>
        </div><!--/col-->
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2><i class="fa fa-random red"></i><span class="break"></span><strong>十大热销商品</strong></h2>
                    <div class="panel-actions">
                        <a href="charts-flot.html#" class="btn-close"><i class="fa fa-times"></i></a>
                    </div>
                </div>
                <div class="panel-body" id="template">
                   <!-- <div class="progress progress-striped active">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 49.18032786885246%">
                            <p>哈哈哈 50%</p>
                        </div>
                    </div>-->
                </div>
            </div>
        </div><!--/col-->
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2><i class="fa fa-random red"></i><span class="break"></span><strong>十大热销商品</strong></h2>
                    <div class="panel-actions">
                        <a href="charts-flot.html#" class="btn-close"><i class="fa fa-times"></i></a>
                    </div>
                </div>
                <div class="panel-body" id="hot_goods">
                </div>
            </div>
        </div><!--/col-->
    </div>
</div>
<link href="<?php echo base_url(); ?>assets/css/BeatPicker.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>assets/js/BeatPicker.js"></script>
<script>
    window.onload = function () {
        rank.getData();
    }
    var rank = {
        showData: function (data) {
            var string1=JSON.parse(JSON.stringify(data['class1']));
            $('#classchart1').highcharts({
                title: {
                    text: '分类销售金额分布'
                },
                series: [{
                    type: 'pie',
                    name: '分类销售金额百分比',
                    data: string1
                }]
            });
            var string2=JSON.parse(JSON.stringify(data['class2']));
            $('#classchart2').highcharts({
                title: {
                    text: '分类销量分布'
                },
                series: [{
                    type: 'pie',
                    name: '分类销售金额百分比',
                    data: string2
                }]
            });

            var color = ['progress-bar-success', 'progress-bar-warning', 'progress-bar-info', 'progress-bar-danger'];
            var str = '';
            for (var i = 0; i < data['goodPrice'].length;i++) {
                str += '<div class="progress progress-striped active">' +
                    ' <div class="progress-bar ' + color[i%data['goodPrice'].length] + '" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: ' + data['goodPrice'][i].rate + '%">' +
                    '<span>'+data['goodPrice'][i].goods_name+'</span></div> </div>';
            }
            $('#template').html(str);

            str = '';
            for (var i = 0; i < data['goodsNum'].length;i++) {
                str += '<div class="progress progress-striped active">' +
                    ' <div class="progress-bar ' + color[i%data['goodsNum'].length] + '" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: ' + data['goodsNum'][i].rate + '%">' +
                    '<span>'+data['goodPrice'][i].goods_name+'</span></div> </div>';
            }
            $('#hot_goods').html(str);
        },
        getData:function(){
            var url=CGloabal.baseUrl+'admin/sold/callData';
            var data={
                start_time:$('#start').val(),
                end_time:$('#end').val()
            };
            $.post(url,data,function(rsps){
                if(rsps.result)
                    rank.showData(rsps.data);
                else
                    CGloabal.layerInfor('服务器出错，请重试');
            },'json');
        }
    };
</script>
