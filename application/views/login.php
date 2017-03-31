<div style="padding-top: 60px">
    <div class="col-md-7">
        <div id="myCarousel" class="carousel slide">
            <!-- 轮播（Carousel）指标 -->
            <ol class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#myCarousel" data-slide-to="1"></li>
            </ol>
            <!-- 轮播（Carousel）项目 -->
            <div class="carousel-inner">
                <div class="item active">
                    <img src="http://cn.bing.com/az/hprichv/LondonTrainStation_GettyRR_139321755_ZH-CN742316019.jpg" alt="First slide">
                </div>
                <div class="item">
                    <img src="http://cn.bing.com/az/hprichv/LondonTrainStation_GettyRR_139321755_ZH-CN742316019.jpg" alt="Second slide">
                </div>
            </div>
            <!-- 轮播（Carousel）导航 -->
            <a class="carousel-control left" href="#myCarousel"
               data-slide="prev">&lsaquo;
            </a>
            <a class="carousel-control right" href="#myCarousel"
               data-slide="next">&rsaquo;
            </a>
        </div>

    </div>
    <div class="col-md-4" style="padding-top: 50px">
        <div>
            <h4>操作员登陆</h4>
        </div>
        <div class="login-form">
            <div class="control-group">
                <label class="login-field-icon fui-home" for="username"></label>
                <input type="text" class="form-control" id="username" placeholder="请输入账号" onblur="login.unameValidata()">
            </div>
            <div class="control-group" style="margin-top: 20px">
                <label class="login-field-icon fui-lock" for="password"></label>
                <input type="password" class="form-control" minlength="6" id="password" placeholder="请输入密码" onfocus="login.pwdInit()" onblur="login.pwdValidata() ">
            </div>
            <div class="control-group" style="margin-top: 20px">
                <label class="login-field-icon fui-credit-card" for="password"></label>
                <input type="text" class="form-control" id="token" placeholder="输入令牌" onblur="login.tokenValidata() ">
            </div>
            <div>
                <label class="checkbox checked" for="checkbox2">
                    <span class="icon"></span>
                    <span class="icon-to-fade"></span>
                    <input type="checkbox" checked="checked" value="" id="is_remember">记住密码
                </label>
            </div>
            <div class="control-group" style="margin-top: 20px">
                <a class= "btn btn-primary btn-large btn-block" onclick="login.loginAction()" style="font-size: large;font-weight: 600">登陆</a>
            </div>
        </div>
    </div>
<script>
    window.onload=function(){
        $("#myCarousel").carousel('cycle');
        $('#username').focus();//获取焦点
    }
    var login={
        //用户名验证
        unameValidata:function() {
            if ($('#username').val()== '') {
                $('#username').parents('.control-group').addClass('has-error');
                return;
            }
            if(!$('#username').val().match(/^[0-9]*$/)){
                $('#username').parents('.control-group').addClass('has-error');
                $('#username').val('请输入数字');
                return;
            }
            $('#username').parents('.control-group').removeClass('has-error');
        },
        //密码验证
        pwdValidata:function(){
            if ($('#password').val()== '') {
                $('#password').parents('.control-group').addClass('has-error');
                return;
            }
            if(!$('#password').val().match(/^\d{6,}$/)){
                $('#password').parents('.control-group').addClass('has-error');
                $('#password').attr('type','text');
                $('#password').val('至少6位数字');
                return;
            }
            $('#password').parents('.control-group').removeClass('has-error');
        },
        //密码框初始化
        pwdInit:function(){
            $('#password').attr('type','password');
        },
        tokenValidata:function(){
            if ($('#token').val()== '') {
                $('#token').parents('.control-group').addClass('has-error');
                return;
            }
        },
        loginAction:function(){
            login.unameValidata();
            login.pwdValidata();
            login.tokenValidata();
            var url=CGloabal.baseUrl+'login/loginAction';
            var data={};
            if($('#is_remember').attr('checked')){
                data={uname:$('#username').val(),pwd:$('#password').val(),token:$('#token').val(),is_rem:true};
            }else{
                data={uname:$('#username').val(),pwd:$('#password').val(),token:$('#token').val(),is_rem:false};
            }
            $.get(url,data,function(rsps){
                if(rsps.result){
                    CGloabal.layerLoading('登陆中');
                    if(rsps.data=='0'){
                        window.location.href=CGloabal.baseUrl+'consume';
                    }else{
                        window.location.href=CGloabal.baseUrl+'admin/home';
                    }
                }else{
                    CGloabal.layerInfor('输入有误，请重新输入');
                }
            },'json');
        }
    };
</script>