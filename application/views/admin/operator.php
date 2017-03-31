<!-- start: Content -->
<div class="main ">

    <div class="row">
        <div class="col-lg-12">
            <h4 class="page-header"><i class="fa fa-list-alt"></i>operator</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table table-striped table-responsive">
                        <thead>
                        <tr>
                            <th class="center">ID</th>
                            <th>操作员</th>
                            <th class="center">联系电话</th>
                            <th class="right">邮箱</th>
                            <th class="right">住址</th>
                            <th class="right">商户</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php for ($i = 0; $i < count($opList); $i++) {
                            ?>
                            <tr onclick="operator.clickItem(this)">
                                <td name="t_id" class="center"><?php echo $opList[$i]['emp_no'] ?></td>
                                <td name="t_name" class="left"><?php echo $opList[$i]['emp_name']; ?></td>
                                <td name="t_mobile" class="center"><?php echo $opList[$i]['mobile']; ?></td>
                                <td name="t_email" class="right"><?php echo $opList[$i]['email']; ?></td>
                                <td name="t_address" class="right"><?php echo $opList[$i]['address']; ?></td>
                                <td name="t_merchant" class="right"><?php echo $opList[$i]['merchant_name']; ?></td>
                            </tr>
                            <?php
                        } ?>
                        </tbody>
                    </table>
                    <div align="center">
                        <img src="<?php echo base_url() ?>assets/img/add2.png" onclick="operator.addOperator()">
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2><i class="fa fa-flag-o red"></i><strong>绩效排名---</strong></h2>
                    <h2 id="time"><strong><?php echo $time; ?></strong></h2>
                    <div class="panel-actions">
                        <a href="" class="btn-setting"><i class="fa fa-rotate-right"></i></a>
                        <a name="prev" onclick="operator.changeRange(this)" class="btn-setting">
                            <i class="fa fa-chevron-circle-left"></i></a>
                        <a name="next" onclick="operator.changeRange(this)" class="btn-setting">
                            <i class="fa fa-chevron-circle-right"></i></a>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table bootstrap-datatable countries">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>员工号</th>
                            <th>姓名</th>
                            <th>收银额</th>
                            <th>成交订单数</th>
                            <th>performance</th>
                        </tr>
                        </thead>
                        <tbody id="tbody">
                        <?php foreach ($performance as $key => $value) { ?>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <td><?php echo $value['emp_no']; ?></td>
                                <td><?php echo $value['emp_name']; ?></td>
                                <td><?php echo $value['money']; ?></td>
                                <td><?php echo $value['count']; ?></td>
                                <td>
                                    <div class="progress thin">
                                        <div class="progress-bar progress-bar-danger" role="progressbar"
                                             aria-valuenow="<?php empty($sold_total) ? '0' : $value['money'] / $sold_total * 100; ?>"
                                             aria-valuemin="0" aria-valuemax="100"
                                             style="width: <?php empty($sold_total) ? '0' : $value['money'] / $sold_total * 100; ?>%">
                                        </div>
                                    </div>
                                    <span class="sr-only"><?php empty($sold_total) ? '0' : $value['money'] / $sold_total * 100; ?>
                                        %</span>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-5">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2><i class="fa fa-heart-o red"></i><strong>编辑信息</strong></h2>
                </div>
                <div class="panel-body">
                    <form class="form-vertical hover-stripped" role="form">
                        <div class="form-group">
                            <label class="control-label">ID</label>
                            <input id="id" type="text" class="form-control" value="1001" disabled>
                        </div>
                        <div class="form-group">
                            <label class="control-label">昵称</label>
                            <input id="name" type="text" class="form-control" value="Jhon Smith">
                        </div>
                        <div class="form-group">
                            <label class="control-label">邮箱</label>
                            <input id="email" type="email" class="form-control" value="jhonsmith@mail.com">
                        </div>
                        <div class="form-group">
                            <label class="control-label">手机号</label>
                            <input id="mobile" class="form-control" value="315653212">
                        </div>
                        <div class="form-group">
                            <label class="control-label">住址</label>
                            <input id="address" class="form-control" value="浙江省宁波市">
                        </div>
                        <div class="form-group">
                            <label class="control-label">新密码</label>
                            <input id="password" type="password" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="control-label">确认密码</label>
                            <input id="pwd_confirm" type="password" class="form-control">
                        </div>
                        <div class="form-group pull-left">
                            <button class="btn btn-danger btn-ls" onclick="operator.deleteOperator()">删除</button>
                        </div>
                        <div class="form-group pull-right">
                            <button class="btn btn-primary btn-ls" onclick="operator.confirm()">确认</button>
                        </div>

                    </form>
                </div>
            </div>
        </div><!--/.col-->

    </div><!--/operator-->
</div>
<script src="<?php echo base_url(); ?>assets/js/jqPaginator.js"></script>
<script>
    var operator = {
        clickItem: function (obj) {
            $('#id').attr('disabled', 'disabled');
            $('#id').val($(obj).find('[name="t_id"]').text());
            $('#name').val($(obj).find('[name="t_name"]').text());
            $('#email').val($(obj).find('[name="t_email"]').text());
            $('#mobile').val($(obj).find('[name="t_mobile"]').text());
            $('#address').val($(obj).find('[name="t_address"]').text());
        },
        addOperator: function () {
            $('#id').val('');
            $('#id').removeAttr('disabled');
            $('#name').val('');
            $('#email').val('');
            $('#mobile').val('');
            $('#address').val('');

        },
        registerOperator: function () {
            if ($('#password').val() != $('#pwd_confirm').val())
                CGloabal.layerInfor('两次输入密码不同，请重新输入!');
            var url = CGloabal.baseUrl + 'admin/operator/addOperator';
            var data = {
                name: $('#name').val(),
                no: $('#id').val(),
                password: $('#password').val(),
                mobile: $('#mobile').val(),
                email: $('#email').val(),
                address: $('#address').val()
            };
            $.post(url, data, function (rsps) {
                if (rsps.result)
                    CGloabal.layerInfor('注册成功！');
                else
                    CGloabal.layerInfor('服务器出错，请重试');
            }, 'json');
        },
        confirm: function () {
            if (typeof($('#id').attr('disabled')) == 'undefined') {
                operator.registerOperator();
            } else {
                operator.modifyOperator();
            }
        },
        modifyOperator: function () {
            var url = CGloabal.baseUrl + 'admin/operator/updateOperator';
            var data = {
                name: $('#name').val(),
                no: $('#id').val(),
                password: $('#password').val(),
                mobile: $('#mobile').val(),
                email: $('#email').val(),
                address: $('#address').val()
            };
            $.post(url, data, function (rsps) {
                if (rsps.result)
                    CGloabal.layerInfor('更新成功！');
                else
                    CGloabal.layerInfor('服务器出错，请重试');
            }, 'json');
        },
        deleteOperator: function () {
            var url = CGloabal.baseUrl + 'admin/operator/deletOperator';
            var data = {
                no: $('#id').val()
            };
            $.post(url, data, function (rsps) {
                if (rsps.result)
                    CGloabal.layerInfor('删除成功');
                else
                    CGloabal.layerInfor('服务器出错，请重试');
            }, 'json');
        },
        changeRange: function (obj) {
            var url = CGloabal.baseUrl + 'admin/operator/getPerformance';
            var data = {
                time: $('#time').text(),
                op: ($(obj).attr('name') == 'prev') ? -1 : 1
            };
            $.post(url, data, function (rsps) {
                if (rsps.result) {
                    var str = '';
                    for (var i = 0; i < rsps.data.length; i++) {
                        str += '<tr> <td>' + i + 1 + '</td>' +
                            '<td>' + rsps.data[i]['emp_no'] + '</td>' +
                            '<td>' + rsps.data[i]['emp_name'] + '</td>' +
                            '<td>' + rsps.data[i]['money'] + '</td>' +
                            '<td>' + rsps.data[i]['count'] + '</td> <td>' +
                            '<div class="progress thin">';
                        if (rsps.data[i]['rate'] != 0) {
                            str += '<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="' + rsps.data[i]['rate'] + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + rsps.data[i]['rate'] + '%">  </div>';
                        }
                        str += '</div> </div> <span class="sr-only">' + rsps.data[i]['rate'] + '%</span>' +
                            '</td> </tr>';
                    }
                    $('#time').text(rsps.msg);
                    $('#tbody').html(str);
                }
            }, 'json');
        }
    };
</script>