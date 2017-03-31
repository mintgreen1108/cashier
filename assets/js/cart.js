/**
 * Created by mintgreen on 2016/10/31.
 */
var goodsItem=new Array();
var goodsId=0;
var cart={
    current_oid:'',
    //创建商品构造器
    prodFactory:function(id,name,price,num){
        this.gid=id;
        this.gname=name;
        this.gprice=price;
        this.gnum=num;
    },
    //判断商品是否已经选择
    prodExit:function(gname){
        for(var i=0;i<goodsItem.length;i++){
            if(gname==goodsItem[i].gname){
                return i;
            }
        }
        return false;
    },
    //选择商品
    prodSelect:function(data){
        var exit=cart.prodExit(data.gname);
        if(exit===false){
            var item=new cart.prodFactory(data.gid,data.gname,data.gprice,1);
            goodsItem.push(item);
            var template=$('#goods_template').clone();
            template.removeClass('hidden');
            template.find('[name="goods_name"]').text(data.gname);
            template.find('[name="goods_name"]').attr('id','gid'+goodsId)
            template.find('[name="goods_price"]').text(data.gprice);
            template.find('.editinput').val(data.gprice);
            $('#cart_goods_list').append(template.html());
            goodsId++;
        }else{
            goodsItem[exit].gnum++;
            $('#gid'+exit+'').parents('[name="cart_goods"]').find('[name="display_count"]').val(goodsItem[exit].gnum);
        }
        cart.prodTotalUpdate();
    },
    //商品数目增加
    prodNumAdd:function(obj){
        var gname=$(obj).parents('[name="cart_goods"]').find('[name="goods_name"]').text();
        var index=cart.prodExit(gname);
        goodsItem[index].gnum++;
        $(obj).parents('[name="cart_goods"]').find('[name="display_count"]').val(goodsItem[index].gnum);
        cart.prodTotalUpdate();
    },
    //商品数目减少
    prodNumSub:function(obj){
        var gname=$(obj).parents('[name="cart_goods"]').find('[name="goods_name"]').text();
        var index=cart.prodExit(gname);
        if(goodsItem[index].gnum>1){
            goodsItem[index].gnum--;
            $(obj).parents('[name="cart_goods"]').find('[name="display_count"]').val(goodsItem[index].gnum);
        }else{
            $(obj).parents('[name="cart_goods"]').remove();
            goodsItem.splice(index,1);
        }
        cart.prodTotalUpdate();
    },
    //自定义商品数量
    prodNumModify:function(obj){
        var num=$(obj).val();
        var gname=$(obj).parents('[name="cart_goods"]').find('[name="goods_name"]').text();
        var index=cart.prodExit(gname);
        goodsItem[index].gnum=num;
        $(obj).val(goodsItem[index].gnum);
        cart.prodTotalUpdate();
    },
    //自定义商品价格
    prodPriceModify:function(obj){
        var price=$(obj).prev().val();
        var gname=$(obj).parents('[name="cart_goods"]').find('[name="goods_name"]').text();
        var index=cart.prodExit(gname);
        goodsItem[index].gprice=price;
        $(obj).parents('[name="modifyprice"]').addClass('hidden');
        $(obj).parents('[name="cart_goods"]').find('[name="goods_price"]').text(price);
        cart.prodTotalUpdate();
    },
    //显示商品价格输入框
    showPriceEdit:function(obj){
        $(obj).prev().removeClass('hidden');
        var price=$(obj).prevAll('[name="goods_price"]').text();
        $(obj).prev().find('input').val(price);
    },
    //商品总数量和总价更新
    prodTotalUpdate:function(){
        var total_num=0;
        var total_price=0;
        for(var i=0;i<goodsItem.length;i++){
            total_num=parseInt(total_num)+parseInt(goodsItem[i].gnum);
            total_price=parseFloat(total_price)+parseFloat(goodsItem[i].gprice)*goodsItem[i].gnum;
        }
        var str_money = parseFloat(total_price).toFixed(2);
        total_price = parseFloat(str_money);
        $('#total_num').text(total_num);
        $('#total_price').text('￥'+total_price);
    },
    //清空购物车
    prodClear:function(){
        goodsItem.splice(0,goodsItem.length);
        $('#cart_goods_list').children('.memeber_user .cart').remove();
    },
    //生成订单
    _genOrder:function(callback){
        if(!goodsItem.length){
            CGloabal.layerInfor('购物车不能为空');
            return;
        }
        var url=CGloabal.baseUrl+'consume/createOrder';
        var mem;
        if($("#mem_info").length!=0){
            var mem_info=$("#mem_info").data('minfo');
            mem=mem_info.split(',');
        }
        var data={
            member_id:$("#mem_info").length!=0?mem[0]:'',
            card_no:$("#mem_info").length!=0?mem[1]:'',
            mobile:$("#mem_info").length!=0?mem[2]:'',
            goods_list:goodsItem
        };
        $.post(url,data,function(rsps){
            if(rsps.result){
                cart.current_oid=rsps.data.order_no;
                callback(rsps);
            }else{
                CGloabal.layerInfor(msg);
            }
        },'json');

    },
    goOrder:function(){
        cart._genOrder(function(rsps){
            CGloabal.layerQue('订单号:'+rsps.data.order_no,function(){
                window.location.href=CGloabal.baseUrl+'order';
            })
        });
    },
    //付款
    goPay:function(){
        cart._genOrder(function(rsps){
            if(rsps.result){
                CGloabal.layer($('#total_price').text().substr(1));
            }

        });
    },
    //会员登陆
    memberLogin:function(){
        var url=CGloabal.baseUrl+'consume/memberLogin';
        var data={
            member_id:$('#member_id').val(),
            merchant_code:cart.merchant_code
        }
        $.post(url,data,function(rsps){
            var pic_name='man';
            if(rsps.result){
                if(rsps.data['gender']==1) {
                    pic_name='women';
                }
                var mem_array=new Array([rsps.data['member_id'],rsps.data['card_no'],rsps.data['mobile']]);
                var base_url=CGloabal.baseUrl;
                var ht='<li id="mem_info" data-minfo='+mem_array+'> ' +
                    '<a style="float: left">' +
                    '<img src="'+base_url+'assets/img/'+pic_name+'.png">'+rsps.data['member_name']+'</a>'+
                    '<span style="color: #2c3e50;font-size: smaller;margin-left: 10px;margin-right: 15px">卡号：'+rsps.data['card_no']+'</span> '+
                    '<i class="fui-folder"></i> ' +
                    '</li>';
                $('#member').html(ht);
            }
        },'json');
    }
};
