<div class="col-md-2" style="padding-right: 0px;padding-top: 5.1%">
    <div class="share mrl" style="background-color: #ffffff;margin-right: 0px">
        <p class="btn btn-primary btn-block btn-large"><span class="whitefont">全部商品分类</span></p>
        <ul style="padding: 0px;">
            <?php
            foreach($category as $value){
            ?>
            <li onclick="con_class.getProducts(this)">
                <label id="<?php echo $value['id'];?>" style="margin-left: 30%;cursor:pointer" class="share-label" for="share-toggle2"><p class="psize"><?php echo $value['class_name'];?></p></label>
            </li>
            <?php } ?>
        </ul>
    </div>
</div>
<script>
    var con_class={
        merchant_code:'<?php echo $merchant_code;?>',
        getProducts:function(obj){
            $('.bg_select').removeClass('bg_select');
            $(obj).addClass('bg_select');
            var url=CGloabal.baseUrl+'consume/productsList';
            var data={
                merchant_code:con_class.merchant_code,
                classId:$(obj).children().attr('id')
            };
            $.post(url,data,function(rsps){
                if(rsps.result){
                    producList.showProducts(rsps.data);
                }
            },'json');
        }
    }
</script>
