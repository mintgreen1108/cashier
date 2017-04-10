
<div class="col-md-5"  style="margin-top: 6%">
    <div class="memeber_add bg-white" style="position: fixed;width:40%;height: 60px;top: 70px;">
        <li><img src="<?php echo base_url();?>assets/img/order_list.png" style="margin-top:-5px;"> 全部订单</li>
        <li style="float: right">
            <span id="search_button" onclick="orderList.queryOrder()" style="margin-left: 10px;margin-right: 10px" class="glyphicon glyphicon-search"></span>
            <i class="glyphicon glyphicon-refresh" onclick="orderList.refresh()"></i>
        </li>
        <li style="margin-top:6px;">
            <input type="text" id="search" class="form-control" placeholder="手机号/订单号" value="<?php echo $cmo;?>">
        </li>
    </div>
    <div class="memeber_user bg-white" style="position: fixed;height:750px;width:40%;top:130px;">
        <div class="cart" id="order_list" style="overflow-y:auto; height: 100%;width:100%">
            <?php
              $count=1;
              foreach($order_list as $item){
            ?>
            <div name="order_detail" style="height: 100px" onclick="orderList.selectOrder(this)" >
                <ul style="width: 8%">
                    <li><span class="vipfont" name="no"><?php echo $count++?></span></li>
                </ul>
                <ul style="width: 70%">
                    <li>
                        <span>总金额：<span class="vipfont" name="total_price"><?php echo $item['total']?></span></span>
                        <span class="marginleft">数量： <span class="vipfont" name="num"><?php echo $item['total_num'];?></span></span>
                    </li>
                    <li name="order_create_time">挂单日期：<?php echo $item['create_time'];?></li>
                    <li name="order_no">订单号：<?php echo $item['order_no'];?></li>
                    <li>
                        <span>卡号：<?php echo !empty($item['card_no'])?$item['card_no']:'游客';?></span>
                        <span>手机号：<?php echo !empty($item['phone'])?$item['phone']:'--';?></span>
                    </li>
                </ul>
                <ul class="vcenter">
                    <li onclick="orderList.cancleOrder(this)" style="cursor:pointer;"><img src="<?php echo base_url();?>assets/img/delet.png"></li>
                </ul>
                <ul></ul>
            </div>
            <?php }?>
        </div>
    </div>
    <div class="cart_btn" style="width: 57%">
        <div>
            <li><strong>第<span id="order_no" class="blackfont"></span> 号账单</strong></li>
            <li id="order_total" class="cartfont">￥0</li>
        </div>
        <div style="float:right;">
            <li><button class="cartbtn bg_grey" onclick="orderList.goPay()"><img src="<?php echo base_url();?>assets/img/cart_1.png" />收款</button></li>
            <li><button class="cartbtn bg_orange" onclick="orderList.modifyOrder()"><img src="<?php echo base_url();?>assets/img/cart_2.png" />取单</button></li>
        </div>
    </div>
</div>
<div style="margin-top:71px">
    <table class="col-md-7 bg-white">
        <thead class="gdthead">
        <tr style="height:60px">
            <th class="table-title">&nbsp;&nbsp;&nbsp;商品条形码</th>
            <th class="table-title">商品名称</th>
            <th class="table-title">原价（￥）</th>
            <th class="table-title">折扣&nbsp;&nbsp;&nbsp;</th>
            <th class="table-title">现价（￥）</th>
            <th class="table-type">数量</th>
            <th class="table-author am-hide-sm-only">小计（￥）</th>
        </tr>
        </thead>

        <tbody id="note_order_detail">
        <tr id="template_order_detail" class="hidden">
            <td><a href="#" name="goods_code">&nbsp;&nbsp;&nbsp;23415643215</a></td>
            <td name="goods_name">桃子</td>
            <td name="goods_price">￥10</td>
            <td name="discount">80%</td>
            <td name="sold_price">￥8</td>
            <td name="goods_num">1</td>
            <td name="sold"></td>
        </tr>
        </tbody>
    </table>
</div>
<script src="<?php echo base_url();?>assets/js/pay.js"></script>
<script>
    var orderList={
        current_order_no:'',
        //点击订单
        selectOrder:function(obj){
            $('.bg_select').removeClass('bg_select');
            $(obj).addClass('bg_select');
            $('.bg_select').removeClass('bg-select');
            $(obj).addClass('bg-select');
            var order_no=$(obj).find('[name="order_no"]').text().substr(4);
            orderList.current_order_no=order_no;
            var url=CGloabal.baseUrl+'order/orderDetail';
            var total=$(obj).find('[name="total_price"]').text();
            var no=$(obj).find('[name="no"]').text();
            $('#order_no').text(no);
            $('#order_total').text(total);
            $.post(url,{order_no:order_no},function(rsps){
                if(rsps.result){
                    $('#template_order_detail').nextAll().remove();
                  for(var i=0;i<rsps.data.length;i++){
                      var template=$('#template_order_detail').clone();
                      template.removeClass('hidden');
                      template.find('[name="goods_code"]').text(+rsps.data[i]['goods_code']);
                      template.find('[name="goods_name"]').text(rsps.data[i]['goods_name']);
                      template.find('[name="goods_price"]').text(rsps.data[i]['price']);
                      template.find('[name="discount"]').text(rsps.data[i]['discount']);
                      template.find('[name="sold_price"]').text(rsps.data[i]['sold_price']);
                      template.find('[name="goods_num"]').text(rsps.data[i]['sold_total']);
                      template.find('[name="sold"]').text(rsps.data[i]['sold_price']*rsps.data[i]['sold_total']);
                      $('#note_order_detail').append('<tr>'+template.html()+'</tr>');
                  }
                }
            },'json');
        },
        //取单
        modifyOrder:function(){
            if(orderList.current_order_no==''){
                CGloabal.layerInfor('缺少订单号!');
                return;
            }
            var url='<?php echo base_url();?>order/detail/'+orderList.current_order_no;
            location.href=url;
        },
        //取消订单
        cancleOrder:function(obj){
            var order_no=$(obj).parents('[name="order_detail"]').find('[name="order_no"]').text().substr(4);
            var url=CGloabal.baseUrl+'order/cancelOrder';
            $.post(url,{order_no:order_no},function(rsps){
                if(rsps.result){
                    $(obj).parents('[name="order_detail"]').remove();
                }
            },'json')
        },
        //查询订单
        queryOrder:function(){
            var cmo=$('#search').val();
            var url='<?php echo base_url();?>order?cmo='+cmo;
            location.href=url;
        },
        //付款
        goPay:function(){
            if(orderList.current_order_no==''){
                CGloabal.layerInfor('缺少订单号!');
                return;
            }
            CGloabal.layer($('.bg_select').find('[name="total_price"]').text());
        },
        refresh:function(){
            var url='<?php echo base_url();?>order';
            location.href=url;
        }
    }
</script>