<div class="col-md-4" style="padding-top: 5.1%">
    <div class="memeber_add bg-white" style="border:0px !important;" id="member">
        <li>
            <input id="member_id" type="text" class="form-control" minlength="8" placeholder="卡号或手机号"
                   style="width: 70%;display: inline">
            <img src="<?php echo base_url();?>assets/img/card.png" style="margin-left: 15px;cursor:pointer;" onclick="cart.memberLogin()">
        </li>
    </div>
    <div class="memeber_add bg-white" style="margin-top: 5px">
        <li><img src="<?php echo base_url();?>assets/img/cart.png" style="margin-top:-5px;"> 购物车</li>
        <li style="margin-right:5%"><a href="javascript:cart.prodClear()">清空</a></li>
    </div>
    <div>
        <div class="memeber_user cart bg-white" id="cart_goods_list">
            <?php
            $total_price=0;
            $total_num=0;
            foreach($goods_list as $item){
                $total_num+=$item['sold_total'];
                $total_price+=$item['sold_price'];
          ?>
            <div class="memeber_user cart" name="cart_goods">
                <ul name="goods_info">
                    <li>
                    <span class="cartfont">
                        <span name="goods_name"><?php echo $item['goods_name'];?></span>
                    </span>
                    </li>
                    <li>
                    <span class="vipfont">
                        <span>￥</span>
                        <span name="goods_price"><?php echo $item['sold_price'];?></span>　
                        <span name="modifyprice" class="hidden">
                            <input type="text" class="editinput" style="width:70px !important;">
                            <i class="fui-check-circle" style="cursor:pointer;" onclick="cart.prodPriceModify(this)"></i>
                        </span>
                        <a href="#" onclick="cart.showPriceEdit(this)">
                            <img src="<?php echo base_url();?>assets/img/edit.png" style="width:20px;">
                        </a>
                    </span>
                    </li>
                </ul>
                <ul class="vcenter">
                    <li><img src="<?php echo base_url();?>assets/img/add.png" onclick="cart.prodNumAdd(this)" style="cursor:pointer;"></i></li>
                </ul>
                <ul class="vcenter" name="display_num">
                    <li><input type="text" class="am-radius" value="<?php echo $item['sold_total'];?>" name="display_count" onblur="cart.prodNumModify(this)"></li>
                </ul>
                <ul class="vcenter">
                    <li><img src="<?php echo base_url();?>assets/img/sub.png" style="cursor:pointer" onclick="cart.prodNumSub(this)"></li>
                </ul>
                <ul></ul>
            </div>
            <?php } ?>
        </div>
    </div>
    <footer class="cart_btn" style="width:31%">
        <div>
            <li>共<span id="total_num" style="font-weight: 900"><?php echo $total_num;?></span>件商品</li>
            <li class="cartfont" id="total_price">￥<?php echo $total_price;?></li>
        </div>
        <div style="float:right;">
            <li><button class="cartbtn bg_orange" onclick="Order_detail.goPay()"><img src="<?php echo base_url();?>assets/img/cart_1.png">收款</button></li>
            <li><button class="cartbtn bg_grey" onclick="Order_detail.goOrder()"><img src="<?php echo base_url();?>assets/img/cart_2.png">挂单</button></li>
        </div>
    </footer>
</div>
<script src="<?php echo base_url();?>assets/js/cart.js"></script>
<script src="<?php echo base_url();?>assets/js/pay.js"></script>
<script>
    window.onload=function(){
        // 将当前订单商品赋值给Cart goodItem
        var goods_list = $.parseJSON('<?php echo json_encode($goods_list);?>');
        Order_detail.initOrder(goods_list);
    }
    var Order_detail={
        order_id:'<?php echo $order_no?>',
        initOrder:function(goods_list){
            for(var i in goods_list){
                var goods=goods_list[i];
                var item=new cart.prodFactory(goods.goods_id,goods.goods_name,goods.sold_price,goods.sold_total);
                goodsItem.push(item);
            }
        },
        //生成订单
        _genOrder:function(callback){
            if(!goodsItem.length){
                CGloabal.layerInfor('购物车不能为空');
                return;
            }
            var url=CGloabal.baseUrl+'consume/modifyOrder';
            var mem;
            if($("#mem_info").length!=0){
                var mem_info=$("#mem_info").data('minfo');
                mem=mem_info.split(',');
            }
            var data={
                order_no:Order_detail.order_id,
                member_id:$("#mem_info").length!=0?mem[0]:'',
                card_no:$("#mem_info").length!=0?mem[1]:'',
                mobile:$("#mem_info").length!=0?mem[2]:'',
                goods_list:goodsItem
            };
            $.post(url,data,function(rsps){
                if(rsps.result){
                    callback(rsps);
                }else{
                    CGloabal.layerInfor(msg);
                }
            },'json');

        },
        goOrder:function(){
            Order_detail._genOrder(function(rsps){
                CGloabal.layerQue('订单号:'+rsps.data.order_no,function(){
                    window.location.href=CGloabal.baseUrl+'order';
                })
            });
        },
        //付款
        goPay:function(){
            Order_detail._genOrder(function(rsps){
                CGloabal.layer();
            });
        }
    }
</script>