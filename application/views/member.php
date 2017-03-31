<div>
    <div class="col-md-9" style="padding-top: 5.5%">
        <div class="memeber_add bg-white">
            <li>
                <span class="glyphicon glyphicon-th-list" style="font-size: 34px"></span>
                <span class="vipfont">会员列表</span>
            </li>
            <li style="float: right">
                <span style="margin-left: 10px" class="glyphicon glyphicon-search" onclick="memberOp.searchMember()"></span>
            </li>
           <!-- <li class="controls">
                <select class="input-xlarge btn btn-primary">
                    <option>卡号</option>
                    <option>会员名</option>
                    <option>手机号</option>
                </select>-->
            <li>
                <input type="text" id="keyword" style="display: inline" class="form-control" placeholder="会员名/卡号/手机号">
            </li>
           <!-- </li>-->
        </div>
        <table class="table table-hover bg-white">
            <thead>
            <tr>
                <th>卡号</th>
                <th>会员名</th>
                <th>性别</th>
                <th>手机号</th>
                <th>出生日期</th>
                <th>积分</th>
                <th>操作员</th>
                <th>注册时间</th>
                <th>   </th>
                <th>   </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($member_list as $key=>$value){ ?>
            <tr>
                <td id="card_no"><?php echo $value['card_no'] ?></td>
                <td id="member_name"><?php echo $value['member_name'] ?></td>
                <td id="sex"><?php echo ($value['gender']==0)?'男':'女';?></td>
                <td id="mobile"><?php echo $value['mobile'] ?></td>
                <td id="birthday"><?php echo $value['birthday'];?></td>
                <td id="point_total"><?php echo $value['point_total'] ?></td>
                <td id="emp_name"><?php echo $value['emp_name'] ?></td>
                <td id="create_time"><?php echo $value['create_time'] ?></td>
                <td>
                    <button type="button" class="btn btn-info btn-xs" onclick="memberOp.editMemberInfo(this)">
                        <span class="glyphicon glyphicon-edit"></span>
                    </button>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-xs" onclick="memberOp.deleteMember(this)">
                        <span class="glyphicon glyphicon-remove-sign"></span>
                    </button>
                </td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
        <div style="float: right" class="pagination">
            <p id="page_text"></p>
            <ul id="page">
            </ul>
        </div>
    </div>
    <div class="col-md-3" style="padding-top: 5.5%">
        <div class="memeber_add bg-white">
            <li id="member_op">
                <span class="glyphicon glyphicon-user" style="font-size: 30px"></span>
                <span class="vipfont">注册新会员</span>
            </li>
            <li style="float: right" class="hidden">
                <span class="fui-plus-circle" style="font-size: 25px" onclick="memberOp.addMember()"></span>
            </li>
        </div>
        <div class="bg-white login-form">
            <div class="control-group">
                姓名：
                <input id="e_name" type="text" class="form-control" placeholder="请输入姓名">
            </div>
            <div class="control-group">
                手机号：
                <input id="e_mobile" type="text" class="form-control" placeholder="请输入手机号" maxlength="11">
            </div>
            <div class="control-group">
                卡号：
                <input id="e_card_no" type="text" class="form-control" placeholder="请输入卡号">
            </div>
            <div class="control-group">
                出生日期：
                <input id="e_birthday" type="text" class="form-control"
                       data-beatpicker="true" data-beatpicker-position="['*','*']"  data-beatpicker-module="today,clear">
            </div>
            <div class="control-group" style="float: right">
                <button id="register" type="button" class="btn btn-inverse btn-ls" onclick="memberOp.registerMember()">
                    <span class="fui-check"></span>
                </button>
                <button id="update" type="button" class="hidden btn btn-inverse btn-ls" onclick="memberOp.updateMember()">
                    <span class="fui-check"></span>
                </button>
            </div>
            <div class="controls">
                性别：
                <select id="select_sex" class="input-xlarge btn btn-primary">
                    <option value="0">男</option>
                    <option value="1">女</option>
                </select>
            </div>
        </div>
    </div>
</div>
<link href="<?php echo base_url();?>assets/css/BeatPicker.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/js/BeatPicker.js"></script>
<script src="<?php echo base_url();?>assets/js/jqPaginator.js"></script>
<script>
     $('#page').jqPaginator({
         totalPages: <?php echo $member_page;?>,
         visiblePages: 10,
         currentPage: 1,
         onPageChange: function (num, type) {
            $('#page_text').html('当前第' + num + '页');
            var url=CGloabal.baseUrl+'member/getPageMemberList';
            var data={page:num};
            $.post(url,data,function(rsps){
                if(rsps.result){
                    var str='';
                    for(var i=0;i<rsps.data.length;i++){
                        var gender='女';
                        if(rsps.data[i]['gender']=='0'){
                            gender='男';
                        }
                        var template='<tr><td id="card_no">'+rsps.data[i]['card_no']+'</td>'+
                            '<td id="member_name">'+rsps.data[i]['member_name']+'</td>'+
                            '<td id="sex">'+gender+'</td>'+
                            '<td id="mobile">'+rsps.data[i]['mobile']+'</td>'+
                            '<td id="birthday">'+rsps.data[i]['birthday']+'</td>'+
                            '<td>'+rsps.data[i]['point_total']+'</td>'+
                            '<td>'+rsps.data[i]['emp_name']+'</td>'+
                            '<td>'+rsps.data[i]['create_time']+'</td>'+
                            '<td>'+
                            '<button type="button" class="btn btn-info btn-xs" onclick="memberOp.editMemberInfo(this)">'+
                            '<span class="glyphicon glyphicon-edit"></span>'+
                            '</button>'+
                            '</td> <td>'+
                            '<button type="button" class="btn btn-danger btn-xs">'+
                            '<span class="glyphicon glyphicon-remove-sign"></span>'+
                            '</button> </td> </tr>'
                        str+=template;
                    }
                    $('tbody').html(str);
                }else{
                    CGloabal.layerInfor(rsps.msg);
                }
            },'json');
         }
    });
    var memberOp={
        //编辑会员信息
        editMemberInfo:function(obj){
            $('.bg_select').removeClass('bg_select');
            $(obj).parents('tr').addClass('bg_select');
            $('#member_op').find('.vipfont').text('编辑会员');
            $('#member_op').next().removeClass('hidden');
            $('#e_name').val($(obj).parents('tr').children('#member_name').text());
            $('#e_mobile').val($(obj).parents('tr').children('#mobile').text());
            $('#e_card_no').val($(obj).parents('tr').children('#card_no').text());
            $('#e_card_no').attr('disabled','disabled');
            $('#e_birthday').val($(obj).parents('tr').children('#birthday').text());
            if($(obj).parents('tr').children('#sex').text()=='男'){
                $('#select_sex').val(0);
            }else{
                $('#select_sex').val(1);
            }
            $('#register').addClass('hidden');
            $('#update').removeClass('hidden');

        },
        //跳转到新建会员界面
        addMember:function(){
            $('.bg_select').removeClass('bg_select');
            $('#member_op').find('.vipfont').text('注册新会员');
            $('#member_op').next().addClass('hidden');
            $('#e_name').val('');
            $('#e_mobile').val('');
            $('#e_card_no').val('');
            $('#e_birthday').val('');
            $('#update').addClass('hidden');
            $('#register').removeClass('hidden');
        },
        //搜索会员
        searchMember:function(){
            var url=CGloabal.baseUrl+'member/searchMember';
            var data={keyword:$('#keyword').val()};
            $.post(url,data,function(rsps){
                if(rsps.result){
                    var gender='女';
                    if(rsps.data['gender']==0)
                        gender='男';
                    $('tbody').html('<tr><td id="card_no">'+rsps.data['card_no']+'</td>'+
                        '<td id="member_name">'+rsps.data['member_name']+'</td>'+
                        '<td id="sex">'+gender+'</td>'+
                        '<td id="mobile">'+rsps.data['mobile']+'</td>'+
                        '<td id="mobile">'+rsps.data['birthday']+'</td>'+
                        '<td>'+rsps.data['point_total']+'</td>'+
                        '<td>'+rsps.data['emp_name']+'</td>'+
                        '<td>'+rsps.data['create_time']+'</td>'+
                        '<td>'+
                        '<button type="button" class="btn btn-info btn-xs" onclick="memberOp.editMemberInfo(this)">'+
                        '<span class="glyphicon glyphicon-edit"></span>'+
                        '</button>'+
                    '</td> <td>'+
                    '<button type="button" class="btn btn-danger btn-xs">'+
                    '<span class="glyphicon glyphicon-remove-sign"></span>'+
                    '</button> </td> </tr>');
                }else{
                    CGloabal.layerInfor('查询无果，请重新输入');
                }
            },'json');
        },
        //删除会员
        deleteMember:function(obj){
            CGloabal.layerQue('确定删除该会员吗？',function(){
                var url=CGloabal.baseUrl+'member/deleteMember';
                var data={card_no:$(obj).parents('tr').children('#card_no').text()};
                $.post(url,data,function(rsps){
                    if(rsps.result){
                        CGloabal.layerInfor('删除成功');
                        $(obj).parents('tr').remove();
                    }else{
                        CGloabal.layerInfor('删除失败，请重新操作');
                    }
                },'json');
            });
        },
        //添加会员
        registerMember:function(){
            var url=CGloabal.baseUrl+'member/addMember';
            if($('#e_mobile').val()==''){
                $('#e_mobile').parents('.control-group').addClass('has-error');
                return;
            }
            if($('#e_card_no').val()==''){
                $('#e_card_no').parents('.control-group').addClass('has-error');
                return;
            }
            var data={
                card_no:$('#e_card_no').val(),
                member_name:$('#e_name').val(),
                mobile:$('#e_mobile').val(),
                birthday:$('#e_birthday').val(),
                gender:$('#select_sex option:selected').val()
            }
            $.post(url,data,function(rsps){
                if(rsps.result){
                    location.reload();
                }else{
                    CGloabal.layerInfor(rsps.msg);
                }
            },'json');
        },
        updateMember:function(){
            var url=CGloabal.baseUrl+'member/updateMember';
            if($('#e_mobile').val()==''){
                $('#e_mobile').parents('.control-group').addClass('has-error');
                return;
            }
            if($('#e_card_no').val()==''){
                $('#e_card_no').parents('.control-group').addClass('has-error');
                return;
            }
            var data={
                card_no:$('#e_card_no').val(),
                member_name:$('#e_name').val(),
                mobile:$('#e_mobile').val(),
                birthday:$('#e_birthday').val(),
                gender:$('#select_sex option:selected').val()
            }
            $.post(url,data,function(rsps){
                if(rsps.result){
                    location.reload();
                }else{
                    CGloabal.layerInfor(rsps.msg);
                }
            },'json');
        }

    };
</script>
