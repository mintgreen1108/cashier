<div class="navbar navbar-default navbar-fixed-top" role="navigation" style="background-color: #46C6A9;height:58px">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand " href="#">
                <?php if(!empty($merchant_name)){?>
                   <h4><?php echo $merchant_name;?></h4>
                <?php
                }else{
                    ?>
                    Wastsons
                <?php
                }?>
            </a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav" id="tab">
                <!--li><a href="<?php /*echo base_url();*/?>welcome"><span class="blackfont">首页</span></a></li>-->
                <li><a href="<?php echo base_url()?>consume"><span class="blackfont">消费</span></a></li>
                <li><a href="<?php echo base_url()?>order"><span class="blackfont">订单</span></a></li>
                <li><a href="<?php echo base_url()?>invoice"><span class="blackfont">交易流水</span></a></li>
                <li><a href="<?php echo base_url()?>member"><span class="blackfont">会员管理</span></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li style="padding-top: 20px;padding-right: 5px">
<!--                    <button type="button" class="btn btn-inverse btn-lg" id="logout">-->
                    <span id="logout" class="glyphicon glyphicon-off"></span>
<!--                    </button>-->
                </li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>
<script>
    $('#tab li').click(function(){
        $(this).addClass('bg_selected');
    });
    $('#logout').click(function(){
        var url=CGloabal.baseUrl+'login/getlogoutInfo';
        $.post(url,{},function(rsps){
            if(rsps.result){
                CGloabal.layerCheckOut(rsps.data);
            }
        },'json');
    });
</script>