<div class="main ">
    <div class="row">
        <div class="col-lg-12">
            <h4 class="page-header"><i class="fa fa-heart-o"></i>Setting</h4>
        </div>
    </div>

    <div class="row profile">
        <div class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4><strong>营业</strong></h4>

                    <ul class="profile-details">
                        <li>
                            <div><i class="fa fa-thumbs-up"></i>开始时间</div>
                            <?php echo $config['bus_start_time'];?>
                        </li>
                        <li>
                            <div><i class="fa fa-thumbs-down"></i>结束时间</div>
                            <?php echo $config['bus_end_time'];?>
                        </li>
                    </ul>
                    <hr>
                    <h4><strong>积分</strong></h4>
                    <ul class="profile-details">
                        <li>
                            <div><i class="fa fa-trash-o"></i>积分过期时间</div>
                            <?php echo $config['point_expire_days'];?>
                        </li>
                        <li>
                            <div><i class="fa fa-sitemap"></i>积分率</div>
                            <?php echo $config['point_rate'];?>
                        </li>
                    </ul>

                </div>

            </div>

        </div><!--/.col-->

        <div class="col-md-7">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2><i class="fa fa-heart-o red"></i><strong>编辑信息</strong></h2>
                </div>
                <div class="panel-body">
                    <form class="form-vertical hover-stripped" role="form">
                        <div class="form-group">
                            <label class="control-label">开始时间</label>
                            <input id="start_time" type="text" class="form-control" value="<?php echo $config['bus_start_time'];?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">结束时间</label>
                            <input id="end_time" type="text" class="form-control" value="<?php echo $config['bus_end_time'];?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">积分过期时间</label>
                            <input id="expire_days" type="text" class="form-control" value="<?php echo $config['point_expire_days'];?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">积分比率</label>
                            <input id="rate" type="text" class="form-control" value="<?php echo $config['point_rate'];?>">
                        </div>
                        <div class="form-group pull-right">
                            <button class="btn btn-primary btn-ls" onclick="config.updateConfig()">修改</button>
                        </div>

                    </form>
                </div>
            </div>
        </div><!--/.col-->
    </div><!--/.row profile-->
</div>
<script>
    var config={
      updateConfig:function(){
          var url=CGloabal.baseUrl+'admin/setting/update';
          var data={
              start_time:$('#start_time').val(),
              end_time:$('#end_time').val(),
              expire_days:$('#expire_days').val(),
              rate:$("#rate").val()
          }
          $.post(url,data,function(rsps){
              if(rsps.result){
                  CGloabal.layerInfor('修改成功');
                  window.location.reload(true);
              }else
                  CGloabal.layerInfor('服务器出错，请重试');
          },'json');
      }
    };
</script>