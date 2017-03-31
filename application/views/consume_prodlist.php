
<div class="col-md-6" style="padding-top: 5.1%;padding-left: 0px">
    <div class="memeber_add bg-white">
        <li>
            <img src="<?php echo base_url();?>assets/img/consume.png" style="margin-top:-5px;">
            <span class="vipfont">消费</span>
        </li>
        <li>
            <input id="goods_key" type="text" class="form-control" minlength="8" placeholder="商品名/首字母/条形码"
                   style="width: 70%;display: inline">
            <span style="margin-left: 10px" class="glyphicon glyphicon-search" onclick="producList.exploreProd()"></span>
        </li>
    </div>
    <div class="block">
        <ul>
            <div id="class_tem">
                <li style="float: left;list-style: none;cursor:pointer;" class="hidden" onclick="producList.selectProd(this) ">
                    <div id="">
                        <img height="50" width="50">
                        <span>桃子</span>
                        </br>
                        <span>￥10.00</span>
                    </div>
                </li>
            </div>
        </ul>
    </div>
</div>
<script>
    var producList={
        showProducts:function(data){
            $('.block ul').children('li').remove();
            for(i=0;i<data.length;i++){
                var template=$('#class_tem').clone();
                template.find('li').removeClass('hidden');
                template.find('img').attr('src',CGloabal.baseUrl+'assets/img/goods/'+data[i]['img_path']);
                template.find('div').attr('id',data[i]['id']);
                template.find('span').first().text(data[i]['goods_name']);
                template.find('span').first().nextAll('span').text('￥'+data[i]['price']);
                template.html();
                $('.block ul').append(template.html());
            }
        },
        selectProd:function(obj){
            var data={
                gid:$(obj).children().attr('id'),
                gname:$(obj).find('span').first().text(),
                gprice:$(obj).find('span').first().nextAll('span').text().substr(1)
            }
            cart.prodSelect(data);
        },
        exploreProd:function(){
            var key=$('#goods_key').val();
            var url=CGloabal.baseUrl+'consume/productsQuery';
            $.post(url,{key:key},function(rsps){
                if(rsps.result){
                    producList.showProducts(rsps.data);
                }
            },'json');
        }
    }
</script>