<div class="panel col-md-4" style="position: absolute;margin:10% 37.5%;height: 40%" >
    <div class="panel-body">
        <label>
            <h5>收款金额：￥30.00</h5>
        </label>
        <label style="margin-left: 10%" class="radio">
            <input name="radio" type="radio" id="pay_cash" onchange="pay.checkChange()">
            <img src="<?php echo base_url();?>assets/img/cash.png">
            现金支付
        </label>
        <label style="margin-left: 10%" class="radio">
            <input name="radio" type="radio" id="pay_zfb" onchange="pay.checkChange()" >
            <img src="<?php echo base_url();?>assets/img/zfb.png">
            支付宝支付
        </label>
        <input type="text" class="form-control hidden" placeholder="实际支付">
        <div id="small" class="hidden">
            <span class="vipfont">伍拾：</span>
            <span style="margin-left: 13%" class="vipfont">贰拾：</span>
            <span style="margin-left: 13%" class="vipfont">拾元：</span>
            <br>
            <span class="vipfont">伍元：</span>
            <span style="margin-left: 13%"  class="vipfont">壹元：</span>
            <span style="margin-left: 13%" class="vipfont">伍角：</span>
            <br>
            <span class="vipfont">壹角：</span>
        </div>
        <button class="col-md-5 bg-primary" style="margin-left: 25%;">收款</button>
    </div>
</div>
<script>
   var pay={
       checkChange:function(){
           var id=$('[name="radio"]:checked').attr('id');
           if(id=='pay_cash'){
               $('.panel').css('height','60%');
               $('.form-control').removeClass('hidden');
               $('#small').removeClass('hidden');
           }else{
               $('.form-control').addClass('hidden');
               $('#small').addClass('hidden');
           }
       }
   }
</script>