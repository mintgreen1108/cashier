<!-- start: Content -->
<div class="main ">

    <div class="row">
        <div class="col-lg-12">
            <h4 class="page-header"><i class="fa fa-list-alt"></i>daily</h4>
        </div>
    </div>

    <div class="invoice">

        <div class="panel panel-default">
            <div class="panel-body">
                <table class="table table-striped table-responsive">
                    <thead>
                    <tr>
                        <th class="center">#</th>
                        <th>日期</th>
                        <th>商户</th>
                        <th class="center">会员增量</th>
                        <th class="right">支付宝交易金额</th>
                        <th class="right">支付宝交易笔数</th>
                        <th class="right">现金交易金额</th>
                        <th class="right">现金交易笔数</th>
                    </tr>
                    </thead>
                    <tbody id="list">
                    <?php foreach ($list as $key => $value) {
                        ?>
                        <tr>
                            <td class="center"><?php echo $key; ?></td>
                            <td class="left" ><?php echo $value['statistic_day']; ?></td>
                            <td class="left" ><?php echo $value['merchant_name']; ?></td>
                            <td class="center" ><?php echo $value['member_add_count']; ?> </td>
                            <td class="right" ><?php echo $value['zfb_sum']; ?></td>
                            <td class="right" ><?php echo $value['zfb_count']; ?></td>
                            <td class="right" ><?php echo $value['cash_sum']; ?></td>
                            <td class="right"><?php echo $value['cash_count']; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

                <div class="row">

                    <div class="col-lg-4 col-lg-offset-4 col-sm-5 col-sm-offset-2 recap pull-right">
                        <table class="table table-clear">
                            <tr>
                                <td class="left"><strong>合计</strong></td>
                                <td class="right"><strong><?php echo $sum;?></strong></td>
                            </tr>
                            </tbody>
                        </table>
                    </div><!--/col-->

                </div><!--/row-->
            </div>
        </div>

    </div><!--/invoice-->
    <div style="float: right" class="pagination">
        <p id="inpage_text"></p>
        <ul id="inpage">
        </ul>
    </div>

</div>
<script src="<?php echo base_url(); ?>assets/js/jqPaginator.js"></script>
<script>
    var statsList = {
        getStaPageList: function (page) {
            $url=CGloabal.baseUrl+'admin/daily/getListByPage';
            $data={
                page:page
            };
            $.post($url,$data,function(rsps){
                if(rsps.result){
                    statsList.showList(rsps.data);
                }else{
                    CGloabal.layerInfor(rsps.msg);
                }
            },'json');
        },
        showList: function (list) {
            $('#list').find('tr').remove();
            var str='';
            for(var i=0;i<data.length;i++) {
                str='<td class="center">'+i+'</td>';
                str='<td class="left" >'+data[i]['statistic_day']+'</td>';
                str='<td class="left" >'+data[i]['merchant_name']+'</td>';
                str='<td class="center" >'+data[i]['member_add_count']+'</td>';
                str='<td class="right" >'+data[i]['zfb_sum']+'</td>';
                str='<td class="right" >'+data[i]['zfb_count']+'</td>';
                str='<td class="right" >'+data[i]['cash_sum']+'</td>';
                str='<td class="right" >'+data[i]['cash_count']+'</td>';
                str+='</tr>';
            }
            $('#list').append(str);
        }
    };
    $('#inpage').jqPaginator({
        totalPages: <?php echo round(count($list)/10);?>,
        visiblePages: 10,
        currentPage: 1,
        onPageChange: function (num, type) {
            $('#inpage_text').html('当前第' + num + '页');
            statsList.getStaPageList(num);
        }
    });
</script>