<div class="main ">
    <div class="row">
        <div class="col-lg-12">
            <h4 class="page-header"><i class="fa fa-heart-o"></i>Profile</h4>
        </div>
    </div>

    <div class="row profile">
        <div class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3 class="text-center"><strong><?php echo $emp_info['emp_name'];?></strong></h3>

                    <hr>

                    <h4><strong>基本信息</strong></h4>

                    <ul class="profile-details">
                        <li>
                            <div><i class="fa fa-thumbs-up"></i>员工号</div>
                            <?php echo $emp_info['emp_no'];?>
                        </li>
                        <li>
                            <div><i class="fa fa-building-o"></i> 门店 </div>
                            <?php echo $merchant_name?>
                        </li>
                    </ul>

                    <hr>

                    <h4><strong>联系信息</strong></h4>

                    <ul class="profile-details">
                        <li>
                            <div><i class="fa fa-tablet"></i> 手机号</div>
                            <?php echo $emp_info['mobile'];?>
                        </li>
                        <li>
                            <div><i class="fa fa-envelope"></i> 邮箱</div>
                            <?php echo $emp_info['email'];?>
                        </li>
                        <li>
                            <div><i class="fa fa-map-marker"></i> 住址</div>
                            <?php echo $emp_info['address'];?>
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
                            <label class="control-label">ID</label>
                            <input type="text" class="form-control" value="<?php echo $emp_info['emp_no'];?>" disabled>
                        </div>
                        <div class="form-group">
                            <label class="control-label">昵称</label>
                            <input id="nickname" type="text" class="form-control" value="<?php echo $emp_info['emp_name'];?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">邮箱</label>
                            <input id="email" type="email" class="form-control" value="<?php echo $emp_info['email'];?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">手机号</label>
                            <input id="mobile" type="text" class="form-control" value="<?php echo $emp_info['mobile'];?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">住址</label>
                            <input id="address" type="text" class="form-control" value="<?php echo $emp_info['address'];?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">新密码</label>
                            <input id="password" type="password" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="control-label">确认密码</label>
                            <input id="pwd_con" type="password" class="form-control">
                        </div>
                        <div class="form-group pull-right">
                            <button class="btn btn-primary btn-ls" onclick="profile.modifyInfo()">修改</button>
                        </div>

                    </form>
                </div>
            </div>
        </div><!--/.col-->
    </div><!--/.row profile-->
</div>

<script>
    var profile={
        checkPwd:function(){
            if($('#password').val()!=$('#pwd_con').val()){
                CGloabal.layerInfor('两次密码不一致，请确认！');
                return false;
            }
            return true;
        },
        modifyInfo:function(){
            if(!profile.checkPwd()) return false;
            var url=CGloabal.baseUrl+'admin/profile/modifyEmpInfo';
            var data={
                nickname:$('#nickname').val(),
                email:$('#email').val(),
                mobile:$('#mobile').val(),
                address:$('#address').val(),
                password:$('#password').val()
            };
            $.post(url,data,function(rsps){
                if(rsps.result){
                    CGloabal.layerInfor('修改成功');
                }else{
                    CGloabal.layerInfor('服务器出错，修改失败。请重试！')
                }
            },'json');
        }
    };
</script>