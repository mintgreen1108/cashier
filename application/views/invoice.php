<div style="padding-top: 5.3%">
    <div class="memeber_add bg-white">
       <li>
           <div class="btn-toolbar">
               <div class="btn-group">
                   <a class="btn btn-primary bg_selected" onclick="tableList.switchTable(this)" >交易流水</a>
                   <a class="btn btn-primary" onclick="tableList.switchTable(this)">已售商品</span></a>
               </div>
           </div>
       </li>
        <li style="float: right">
            <span style="margin-left: 10px" class="glyphicon glyphicon-search" onclick="tableList.search()"></span>
        </li>
        <li>
            <input type="text" id="in_keyword" style="display: inline" class="form-control" placeholder="订单号/卡号">
            <input type="text" id="go_keyword" style="display: inline" class="form-control hidden" placeholder="商品名/订单号">
        </li>
    </div>
    <div id="table_invoice" style="padding-top: 0.5%;height: 80%">
        <table data-toggle="table" class="bg-white">
            <thead>
            <tr>
                <th>消费者（卡号）</th>
                <th>订单号</th>
                <th>交易机构</th>
                <th>实际收款</th>
                <th>原价收款</th>
                <th>支付方式</th>
                <th>操作员</th>
                <th>操作时间</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody id="invoice">
            <?php
            foreach($invoice as $key){
                ?>
                <tr>
                    <td><?php echo empty($key['card_no'])?'游客':$key['card_no'];?></td>
                    <td><?php echo $key['order_id'];?></td>
                    <td><?php echo $key['merchant_name'];?></td>
                    <td><?php echo $key['paid'];?></td>
                    <td><?php echo $key['total'];?></td>
                    <td><?php if($key['cash']!=0.00){
                            echo '现金';
                        }else{
                            switch($key['bank_type']){
                                case 1:echo '银行卡';break;
                                case 2:echo '微信';break;
                                case 3:echo '支付宝';break;
                            }
                        }?></td>
                    <td><?php echo $key['emp_name'];?></td>
                    <td><?php echo $key['create_time'];?></td>
                    <td><?php if($key['reverse_flag']==0)
                        echo '<a id="'.$key['id'].'" class= "btn btn-danger btn-xs" onclick="tableList.revokeInvoice(this)"><span class="glyphicon glyphicon-remove"><span></a>';
                        else echo '已撤销';?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <div style="float: right" class="pagination">
            <p id="inpage_text"></p>
            <ul id="inpage">
            </ul>
        </div>
    </div>
    <div id="table_goods" style="padding-top: 0.5%;height: 80%" class="hidden">
        <table data-toggle="table" class="bg-white">
            <thead>
            <tr>
                <th>消费者（卡号）</th>
                <th>交易机构</th>
                <th>商品名称</th>
                <th>商品价格</th>
                <th>商品数量</th>
                <th>操作员</th>
                <th>操作时间</th>
            </tr>
            </thead>
            <tbody id="goods_sold">
            </tbody>
        </table>
        <div style="float: right" class="pagination">
            <p id="gopage_text"></p>
            <ul id="gopage">
            </ul>
        </div>
    </div>
</div>
<script src="<?php echo base_url();?>assets/js/jqPaginator.js"></script>
<script>

    var tableList={
        //分页获取交易流水列表
        getInPageList:function(page){
            var url=CGloabal.baseUrl+'invoice/getInvoiceList';
            var data={
                page:page
            };
            $.post(url,data,function(rsps){
                if(rsps.result){
                    tableList.showInvoiceList(rsps.data);
                }
            },'json');
        },
        //撤销交易流水
        revokeInvoice:function(obj){
            CGloabal.layerQue('确定撤销这条流水？',function(){
                var url=CGloabal.baseUrl+'invoice/revokeInvoice';
                var data={
                    invoice_id:$(obj).attr('id')
                }
                $.post(url,data,function(rsps){
                    if(rsps.result){
                        CGloabal.layerInfor('撤销成功');
                        $(obj).parent().text('已撤销');
                        $(obj).remove();
                    }else{
                        CGloabal.layerInfor('撤销失败');
                    }
                },'json');
            });
        },
        //已售商品表操作
        switchTable:function(obj){
            $('.bg_selected').removeClass('bg_selected');
            $(obj).addClass('bg_selected');
            if($(obj).text()!='交易流水'){
                $('#table_invoice').addClass('hidden');
                $('#table_goods').removeClass('hidden');
                $('#in_keyword').addClass('hidden');
                $('#go_keyword').removeClass('hidden');
                tableList.getGoPageList(1);
            }else{
                $('#table_goods').addClass('hidden');
                $('#table_invoice').removeClass('hidden');
                $('#in_keyword').removeClass('hidden');
                $('#go_keyword').addClass('hidden');
                tableList.getInPageList(1);
            }
        },
        //分页获取已售商品列表
        getGoPageList:function(page){
            var url=CGloabal.baseUrl+'invoice/getGoodsSoldList';
            var data={page:page};
            $.post(url,data,function(rsps){
                if(rsps.result){
                    tableList.showGoodsList(rsps.data);
                }
            },'json');
        },
        //搜索交易流水
        searchInvoice:function(){
            var url=CGloabal.baseUrl+'invoice/searchInvoice';
            var data={keyword:$('#in_keyword').val()};
            $.post(url,data,function(rsps){
                if(rsps.result){
                    tableList.showInvoiceList(rsps.data);
                }else{
                    CGloabal.layerInfor('查询无果，请重新输入');
                }
            },'json');
        },
        //搜索已售商品
        searchGoodsSold:function(){
            var url=CGloabal.baseUrl+'invoice/searchGoodsSold';
            var data={keyword:$('#go_keyword').val()};
            $.post(url,data,function(rsps){
                if(rsps.result){
                    tableList.showGoodsList(rsps.data);
                }else{
                    CGloabal.layerInfor('查询无果，请重新输入');
                }
            },'json');
        },
        showInvoiceList:function(data){
            $('#invoice').find('tr').remove();
            var str='';
            for(var i=0;i<data.length;i++){
                str+='<tr>'
                if(data[i]['card_no']!=''){
                    str+='<td>'+data[i]['card_no']+'</td>';
                }else{
                    str+='<td>游客</td>'
                }
                str+='<td>'+data[i]['order_id']+'</td>';
                str+='<td>'+data[i]['merchant_name']+'</td>';
                str+='<td>'+data[i]['paid']+'</td>';
                str+='<td>'+data[i]['total']+'</td>';
                var pay_way='';
                switch(parseInt(data[i]['bank_type'])){
                    case 0:pay_way='现金';break;
                    case 1:pay_way='银行卡';break;
                    case 2:pay_way='微信';break;
                    case 3:pay_way='支付宝';break;
                }
                str+='<td>'+pay_way+'</td>';
                str+='<td>'+data[i]['emp_name']+'</td>';
                str+='<td>'+data[i]['create_time']+'</td>';
                if(data[i]['reverse_flag']==0){
                    str+='<td><a id="'+data[i]['id']+'" class= "btn btn-danger btn-xs" onclick="tableList.revokeInvoice(this)"><span class="glyphicon glyphicon-remove"><span></a></td>';
                }else{
                    str+='<td>已撤销</td>';
                }
                str+='</tr>';
            }
            $('#invoice').append(str);
        },
        showGoodsList:function(data){
            $('#goods_sold').find('tr').remove();
            var str='';
            for(var i=0;i<data.length;i++){
                str+='<tr>'
                if(data[i]['card_no']!=''){
                    str+='<td>'+data[i]['card_no']+'</td>';
                }else{
                    str+='<td>游客</td>'
                }
                str+='<td>'+data[i]['merchant_name']+'</td>';
                str+='<td>'+data[i]['goods_name']+'</td>';
                str+='<td>'+data[i]['sold_price']+'</td>';
                str+='<td>'+data[i]['sold_total']+'</td>';
                str+='<td>'+data[i]['emp_name']+'</td>';
                str+='<td>'+data[i]['create_time']+'</td>';
                str+='</tr>';
            }
            $('#goods_sold').append(str);
        },
        search:function(){
            if($('.bg_selected').text()!='交易流水'){
                tableList.searchGoodsSold();
            }else{
                tableList.searchInvoice();
            }
        }
    }

    //交易流水列表分页
    $('#inpage').jqPaginator({
        totalPages: <?php echo $in_count;?>,
        visiblePages: 10,
        currentPage: 1,
        onPageChange: function (num, type) {
            $('#inpage_text').html('当前第' + num + '页');
            tableList.getInPageList(num);
        }
        });
    //已售商品列表分页
    $('#gopage').jqPaginator({
        totalPages: <?php echo $goods_count;?>,
        visiblePages: 10,
        currentPage: 1,
        onPageChange: function (num, type) {
            $('#gopage_text').html('当前第' + num + '页');
            tableList.getGoPageList(num);
        }
    });


</script>