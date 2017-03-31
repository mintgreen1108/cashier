/**
 * Created by mintgreen on 2016/11/7.
 */
var pay={
    checkChange:function(){
        var id=$('[name="radio"]:checked').attr('id');
        var base_url=CGloabal.baseUrl;
        if(id=='pay_cash'){
            $('#img_zfb').remove();
            $('#zfb').after( '<input id="pay_total" type="text" class="form-control" placeholder="实际支付" onblur="pay.change()" style="width:70%;margin-left:10%;height:50%">');
        }else{
            $('#pay_total').nextAll('span').remove();
            $('#pay_total').remove();
            $('#zfb').after( '        <label >',
                '            <img id="img_zfb" src="'+base_url+'assets/img/code.jpg" style="margin-left: 20%">',
                '        </label>');
        }
    },
    change:function(){
        $('#pay_total').nextAll('span').remove();
        var money= $('#pay_total').val()-$('.panel-body').data('money');
        var str='';
        str+='<span class="vipfont">伍拾：'+Math.floor(money/50)+'</span>'
        str+='<span style="margin-left: 13%" class="vipfont">拾元：'+Math.floor((money%50)/10)+'</span>';
        str+='<span style="margin-left: 13%" class="vipfont">伍元：'+Math.floor(((money%50)%10)/5)+'</span>';
        str+='<br>';
        str+='<span class="vipfont">壹元：'+Math.floor((((money%50)%10)%5)/1)+'</span>'
        str+='  <span style="margin-left: 13%" class="vipfont">壹角：'+((((money%50)%10)%5)%1)/0.1+'</span>';
        $('#pay_total').after(str);
    },
    payConfirm:function(){
        var url=CGloabal.baseUrl+'invoice/genInvoice';
        var order_no;
        if(typeof(Order_detail)!="undefined"){
            order_no=Order_detail.order_id;
        }else if(typeof(orderList)!= "undefined"){
            order_no=orderList.current_order_no;
        }else if(typeof(cart)!= "undefined"){
            order_no=cart.current_oid;
        }
        var data={
            order_no:order_no,
            pay_way:$('[name="radio"]:checked').attr('id')
        }
        $.post(url,data,function(rsps){
            if(rsps.result){
                CGloabal.layerQue('付款成功！',function(){
                    location.reload();
                })
            }else{
                CGloabal.layerInfor('付款失败！');
            }
        },'json');
    }
}
