var loginAction={
    logout:function(data){
        var url=CGloabal.baseUrl+'login/logoutAction';
        var data={
            member_add:$('#member_add').text().substr(6),
            cash_total:$('#cash_total').text().substr(8),
            zfb_total:$('#zfb_total').text().substr(9),
            cash_count:$('#cash_count').text().substr(7),
            zfb_count:$('#zfb_count').text().substr(8),
            remark:$('#remark').val(),
        };
        $.post(url,data,function(rsps){
            if(rsps.result){
                window.location.href='http://www.cashier.com/login';
            }else{
                CGloabal.layerInfor('操作失败，请重试');
            }
        },'json');
    }
};
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
                window.location.href=CGloabal.baseUrl+'consume';
            }else{
                CGloabal.layerInfor('输入有误，请重新输入');
            }
        },'json');
    }
};
