<div class="col-md-4" style="padding-top: 5.1%">
    <div class="memeber_add bg-white" id="member">
        <li>
            <input id="member_id" type="text" class="form-control" minlength="8" placeholder="卡号或手机号"
                   style="width: 70%;display: inline">
            <img src="<?php echo base_url();?>assets/img/card.png" style="margin-left: 15px;cursor:pointer;"
                 onclick="cart.memberLogin()">
        </li>
    </div>
    <div class="memeber_add bg-white" style="margin-top: 5px">
        <li><img src="<?php echo base_url();?>assets/img/cart.png" style="margin-top:-5px;"> 购物车</li>
        <li style="margin-right:5%"><a href="javascript:cart.prodClear()">清空</a></li>
    </div>
    <div>
        <div class="memeber_user cart bg-white" id="cart_goods_list">
            <div class="hidden" id="goods_template">
                <div class="memeber_user cart" name="cart_goods">
                    <ul name="goods_info">
                        <li>
                    <span class="cartfont">
                        <span name="goods_name">桃子</span>
                    </span>
                        </li>
                        <li>
                    <span class="vipfont">
                        <span>￥</span>
                        <span name="goods_price">0.00</span>　
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
                        <li><input type="text" class="am-radius" value="1" name="display_count" onblur="cart.prodNumModify(this)"></li>
                    </ul>
                    <ul class="vcenter">
                        <li><img src="<?php echo base_url();?>assets/img/sub.png" style="cursor:pointer" onclick="cart.prodNumSub(this)"></li>
                    </ul>
                    <ul></ul>
                </div>
            </div>

        </div>
    </div>
    <footer class="cart_btn" style="width:31%">
        <div>
            <li>共<span id="total_num" style="font-weight: 900"> 0 </span>件商品</li>
            <li class="cartfont" id="total_price">￥0.00</li>
        </div>
        <div style="float:right;">
            <li><button class="cartbtn bg_grey" onclick="cart.goPay()"><img src="<?php echo base_url();?>assets/img/cart_1.png">收款</button></li>
            <li><button class="cartbtn bg_orange" onclick="cart.goOrder()"><img src="<?php echo base_url();?>assets/img/cart_2.png">挂单</button></li>
        </div>
    </footer>
</div>

<script src="<?php echo base_url();?>assets/js/cart.js"></script>
<script src="<?php echo base_url();?>assets/js/pay.js"></script>
