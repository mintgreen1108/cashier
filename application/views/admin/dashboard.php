<div class="main">
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><i class="fa fa-sun-o"></i>今日</li>
                <li><i class="fa fa-calendar-o"></i>历史</li>
            </ol>
        </div>
    </div>

    <div class="row" id="today">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <div class="info-box red-bg">
                <i class="fa fa-group"></i>
                <div class="count"><?php echo empty($today['member_add_today']) ? '0' : $today['member_add_today']; ?></div>
                <div class="title">新增会员数</div>
            </div><!--/.info-box-->
        </div><!--/.col-->

        <div class="col-lg-4 col-md-3 col-sm-12 col-xs-12">
            <div class="info-box green-bg">
                <i class="fa fa-money"></i>
                <div class="count"><?php echo empty($today['invoice']['cash_total']['bank']) ? '0' : $today['invoice']['cash_total']['bank']; ?></div>
                <div class="title">现金交易额</div>
            </div><!--/.info-box-->
        </div><!--/.col-->

        <div class="col-lg-4 col-md-3 col-sm-12 col-xs-12">
            <div class="info-box blue-bg">
                <i class="fa fa-credit-card"></i>
                <div class="count"><?php echo empty($today['invoice']['zfb_total']['bank']) ? '0' : $today['invoice']['zfb_total']['bank']; ?></div>
                <div class="title">支付宝交易额</div>
            </div><!--/.info-box-->
        </div><!--/.col-->
    </div><!--/.row-->

    <div class="row hidden" id="history">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <div class="info-box orange-bg">
                <i class="fa fa-group"></i>
                <div class="count"><?php echo $history['member_count'];?></div>
                <div class="title">会员总数</div>
            </div><!--/.info-box-->
        </div><!--/.col-->

        <div class="col-lg-4 col-md-3 col-sm-12 col-xs-12">
            <div class="info-box teal-bg">
                <i class="fa fa-money"></i>
                <div class="count"><?php echo $history['cash_total'];?></div>
                <div class="title">现金总交易额</div>
            </div><!--/.info-box-->
        </div><!--/.col-->

        <div class="col-lg-4 col-md-3 col-sm-12 col-xs-12">
            <div class="info-box brown-bg">
                <i class="fa fa-credit-card"></i>
                <div class="count"><?php echo $history['zfb_total'];?></div>
                <div class="title">支付宝总交易额</div>
            </div><!--/.info-box-->
        </div><!--/.col-->
    </div><!--/.row-->

    <div class="row">

        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2><i class="fa fa-bar-chart-o red"></i><strong>会员增长情况</strong></h2>
                    <div class="panel-actions">
                        <a name="week" onclick="dashboar.selectDateType(this)" class="btn-setting">&nbsp;&nbsp;周&nbsp;&nbsp;</a>
                        <a name="month" onclick="dashboar.selectDateType(this)" class="btn-setting">&nbsp;&nbsp;月&nbsp;&nbsp;</a>
                        <a name="prev" onclick="dashboar.changeDateRage(this)" class="btn-setting"><i class="fa fa-chevron-circle-left"></i></a>
                        <a name="next" onclick="dashboar.changeDateRage(this)" class="btn-setting"><i class="fa fa-chevron-circle-right"></i></a>
                        <a href="" class="btn-minimize"><i class="fa fa-chevron-up"></i></a>
                    </div>
                </div>
                <div id="member" class="panel-body">
                    <div id="member_chart" style="min-width:400px;height:400px"></div>
                </div>
            </div>

        </div><!--/col-->

    </div><!--/row-->

    <div class="row">

        <div class="col-sm-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2><i class="fa fa-bar-chart-o red"></i><strong>交易金额</strong></h2>
                    <div class="panel-actions">
                        <a name="week" onclick="dashboar.selectDateType(this)" class="btn-setting">&nbsp;&nbsp;周&nbsp;&nbsp;</a>
                        <a name="month" onclick="dashboar.selectDateType(this)" class="btn-setting">&nbsp;&nbsp;月&nbsp;&nbsp;</a>
                        <a name="prev" onclick="dashboar.changeDateRage(this)" class="btn-setting"><i class="fa fa-chevron-circle-left"></i></a>
                        <a name="next" onclick="dashboar.changeDateRage(this)"  class="btn-setting"><i class="fa fa-chevron-circle-right"></i></a>
                        <a href="" class="btn-minimize"><i class="fa fa-chevron-up"></i></a>
                    </div>
                </div>
                <div id="invoice" class="panel-body">
                    <div id="invoice_chart" style="min-width:400px;height:400px"></div>
                </div>
            </div>

        </div><!--/col-->

    </div><!--/row-->
</div>
<script>
    $(function () {
        var dateList=<?php echo '["'.implode('","',$data['dateList']).'"]'; ?>;
        var memberList=<?php echo '['.implode(',',$data['data']['memberList']).']';?>;
        var zfbList=<?php echo '['.implode(',',$data['data']['zfbList']).']';?>;
        var cashList=<?php echo '['.implode(',',$data['data']['cashList']).']';?>;
        dashboar.showMemberChart(dateList,memberList);
        dashboar.showInvoiceChart(dateList,zfbList,cashList);
        $('.main').find('[name="week"]').addClass('bg-primary');
    });

    var dashboar={
        start_date:'<?php echo $data['dateList'][0];?>',
        selectDateType:function(obj){
            $(obj).parent().children('.bg-primary').removeClass('bg-primary');
            $(obj).addClass('bg-primary');
            var dateRange=$(obj).parents('.panel').children('.panel-body').attr('id');
            var url=CGloabal.baseUrl+'admin/home/getInStatisAction';
            $.post(url,{date_type:$(obj).attr('name')},function(rsps){
                dashboar.start_date=rsps.data['dateList'][0];
                if(dateRange=='invoice'){
                    dashboar.showInvoiceChart(rsps.data['dateList'],rsps.data['data']['zfbList'],rsps.data['data']['cashList']);
                }else{
                    dashboar.showMemberChart(rsps.data['dateList'],rsps.data['data']['memberList']);
                }
            },'json');
        },
        showMemberChart:function(dateList,memberList){
            $('#member_chart').highcharts({
                title:{
                    text:'会员增长趋势图'
                },
                xAxis: {
                    categories:dateList
                },
                yAxis: {
                    title: {
                        text: '人数（个）'
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    borderWidth: 0
                },
                series: [{
                    name: '会员增量',
                    data: memberList,
                    color:'#46c6a9',
                }]
            });
        },
        showInvoiceChart:function(dateList,zfbList,cashList){
            $('#invoice_chart').highcharts({
                title:{
                    text:'交易额增长趋势图'
                },
                xAxis: {
                    categories:dateList
                },
                yAxis: {
                    title: {
                        text: '金额（元）'
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    borderWidth: 0
                },
                series: [{
                    name: '支付宝交易额',
                    data: zfbList,
                    color:'#46c6a9',
                }, {
                    name: '现金交易额',
                    data: cashList,
                    color:'#222732',
                }
                ]
            });
        },
        changeDateRage:function(obj){
            var dateRange=$(obj).parents('.panel').children('.panel-body').attr('id');
            var url=url=CGloabal.baseUrl+'admin/home/getInStatisAction';
            var data={
                date:dashboar.start_date,
                date_op:$(obj).attr('name'),
                date_type:$(obj).parents('.panel-actions').children('.bg-primary').attr('name')
            };
            $.post(url,data,function(rsps){
                dashboar.start_date=rsps.data['dateList'][0];
                if(dateRange=='invoice'){
                    dashboar.showInvoiceChart(rsps.data['dateList'],rsps.data['data']['zfbList'],rsps.data['data']['cashList']);
                }else{
                    dashboar.showMemberChart(rsps.data['dateList'],rsps.data['data']['memberList']);
                }
            },'json');
        }
    };

    $('.fa-sun-o').click(function(){
        $('#today').removeClass('hidden');
        $('#history').addClass('hidden');
    });
    $('.fa-calendar-o').click(function(){
        $('#history').removeClass('hidden');
        $('#today').addClass('hidden');
    });

</script>