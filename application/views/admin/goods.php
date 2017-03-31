<!-- start: Content -->
<div class="main ">

    <div class="row">
        <div class="col-lg-12">
            <h4 class="page-header"><i class="fa fa-pencil-square-o"></i>classes list</h4>
        </div>
    </div>

    <div class="row inbox">

        <div class="col-md-3">

            <div class="panel panel-default">

                <div class="panel-body inbox-menu">
                    <input id="class" type="text" class="form-control hidden" value="分类名" name="0">

                    <a class="btn btn-primary btn-block" style="margin-top: 20px"
                       onclick="classes.addClass()">添加/更新分类</a>

                    <ul>
                        <?php
                        $color = ['label-danger', 'label-info', 'label-primary', 'label-success', 'label-warning'];
                        foreach ($class as $key => $value) { ?>
                            <li onclick="classes.clickDetail(this)">
                                <a style="font-weight: 500"
                                   id="<?php echo $value['id']; ?>"><?php echo $value['class_name'] ?>
                                    <i class="fa fa-pencil" onclick="classes.editClass(this)"></i>
                                    <span class="label <?php echo $color[$key % 5] ?>"><?php echo $value['goodsNum']; ?></span>
                                    <i class="fa fa-minus-circle btn-danger" onclick="classes.deleteClass(this)"></i>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>

                </div>

            </div>

        </div><!--/.col-->

        <div class="col-md-9">

            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table table-striped table-responsive">
                        <thead>
                        <tr>
                            <th class="center">商品ID</th>
                            <th>名称</th>
                            <th>简拼</th>
                            <th class="center">全拼</th>
                            <th class="right">商品编码</th>
                            <th class="right">分类</th>
                            <th class="right">图片</th>
                            <th class="right">商品价格</th>
                            <th class="right">商品折扣</th>
                        </tr>
                        </thead>
                        <tbody id="list">

                        </tbody>
                    </table>
                </div>
            </div>

        </div><!--/invoice-->
        <div style="float: right" class="pagination">
            <p id="inpage_text"></p>
            <ul id="inpage">
            </ul>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2><i class="fa fa-indent red"></i><strong>编辑商品</strong></h2>
                    </div>
                    <div class="panel-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" id="form">
                            <div class="form-group">
                                <label class="col-md-3 control-label">商品ID</label>
                                <div class="col-md-9">
                                    <input type="text" id="goods_id" name="goods_id" class="form-control"
                                           placeholder="ID">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">商品名称</label>
                                <div class="col-md-9">
                                    <input type="email" id="goods_name" name="goods_name" class="form-control"
                                           placeholder="Goods name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">简拼</label>
                                <div class="col-md-9">
                                    <input type="text" id="full_word" name="simple_word" class="form-control"
                                           placeholder="Simple words">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">全拼</label>
                                <div class="col-md-9">
                                    <input type="text" id="simple_word" name="full_word" class="form-control"
                                           placeholder="Full words">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">商品编码</label>
                                <div class="col-md-9">
                                    <input type="email" id="goods_code" name="goods_code" class="form-control"
                                           placeholder="Goods code">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">商品价格</label>
                                <div class="col-md-9">
                                    <input type="email" id="goods_price" name="price" class="form-control"
                                           placeholder="Goods price">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">商品折扣</label>
                                <div class="col-md-9">
                                    <input type="email" id="goods_discount" name="discount" class="form-control"
                                           placeholder="Goods discount">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="select">分类</label>
                                <div class="col-md-9">
                                    <select id="select_class" name="class_id" class="form-control" size="1">
                                        <?php foreach ($class as $key => $value) { ?>
                                            <option value=<?php echo $value['id']; ?>><?php echo $value['class_name'] ?> </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="file-input">图片</label>
                                <div class="col-md-9">
                                    <input type="file" id="image" name="image">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="panel-footer">
                        <button type="submit" class="btn btn-sm btn-success" onclick="classes.saveGoods()"><i
                                    class="fa fa-dot-circle-o"></i> 保存
                        </button>
                    </div>
                </div>
            </div>
        </div><!--/.row-->

    </div><!--/row-->

</div>
<!-- end: Content -->
<script src="<?php echo base_url(); ?>assets/js/jqPaginator.js"></script>

<script>
    var classes = {
        addClass: function () {
            if ($('#class').hasClass('hidden')) {
                $('#class').removeClass('hidden');
            } else if ($('#class').attr('name') == 0) {
                var $url = CGloabal.baseUrl + 'admin/goods/addGoodsClass';
                var $data = {
                    class_name: $('#class').val()
                }
                $.post($url, $data, function (rsps) {
                    if (rsps.result) {
                        CGloabal.layerInfor('分类添加成功');
                        window.location.reload(true);
                    } else
                        CGloabal.layerInfor('服务器出错，请重试');
                }, 'json');
            } else {
                var url = CGloabal.baseUrl + 'admin/goods/updateGoodsClass';
                var data = {
                    class_name: $('#class').val(),
                    id: $('#class').attr('name')
                };
                $.post(url, data, function (rsps) {
                    if (rsps.result) {
                        CGloabal.layerInfor('分类名更新成功');
                        window.location.reload(true);
                    } else {
                        CGloabal.layerInfor('服务器出错，请重试');
                    }
                }, 'json');
            }
        },
        editClass: function (obj) {
            $('#class').removeClass('hidden');
            $('#class').val($(obj).parent().text());
            $('#class').attr('name', $(obj).parent().attr('id'));
        },
        deleteClass: function (obj) {
            var url = CGloabal.baseUrl + 'admin/goods/deletGoodsClass';
            var data = {
                id: $(obj).parent().attr('id')
            };
            $.post(url, data, function (rsps) {
                if (rsps.result) {
                    CGloabal.layerInfor('删除成功');
                    $(obj).parents('li').remove();
                } else {
                    CGloabal.layerInfor('服务器出错，请重试');
                }
            }, 'json');
        },
        clickDetail: function (obj) {
            $('.bg_selected').removeClass('bg_selected');
            $(obj).addClass('bg_selected');
            classes.getProduct(1);
        },
        getProduct: function (num) {
            var url = CGloabal.baseUrl + 'admin/goods/classDetail';
            var data = {
                class_id: $('.bg_selected').children('a').attr('id'),
                page: num
            };
            $.post(url, data, function (rsps) {
                var data = rsps.data;
                var str = '';
                for (var i = 0; i < data.length; i++) {
                    str += '<tr onclick="classes.editGoods(this)"> <td class="center" name="goods_id">' + data[i]['id'] + '</td>' +
                        '<td class="left" name="goods_name">' + data[i]['goods_name'] + '</td>' +
                        '<td class="left" name="simple_words">' + data[i]['simple_word'] + '</td>' +
                        '<td class="center" name="full_words">' + data[i]['full_word'] + '</td>' +
                        '<td class="right" name="goods_code">' + data[i]['goods_code'] + '</td>' +
                        '<td class="right" name="class_name" data-class=' + data[i]['class_id'] + '>' + data[i]['class_name'] + '</td>' +
                        '<td class="right" name="imag"><img height="50" width="50" src="<?php echo base_url();?>assets/img/goods/' + data[i]['img_path'] + '"></td>' +
                        '<td class="right" name="price">' + data[i]['price'] + '</td>' +
                        '<td class="right" name="discount">' + data[i]['discount'] + '</td>' +
                        '</tr>';
                }
                $('#list').html(str);
            }, 'json');
        },
        editGoods: function (obj) {
            $('#goods_id').val($(obj).find('[name="goods_id"]').text());
            $('#goods_name').val($(obj).find('[name="goods_name"]').text());
            $('#goods_id').attr('readonly', 'readonly');
            var select = $(obj).find('[name="class_name"]').data('class');
            $('#select_class option[value=' + select + ']').attr("selected", true);
            $('#simple_word').val($(obj).find('[name="simple_words"]').text());
            $('#full_word').val($(obj).find('[name="full_words"]').text());
            $('#goods_code').val($(obj).find('[name="goods_code"]').text());
            $('#goods_price').val($(obj).find('[name="price"]').text());
            $('#goods_discount').val($(obj).find('[name="discount"]').text());
        },
        updateGoods: function () {
            var url = CGloabal.baseUrl + 'admin/goods/updateGoodsInfo';
            var form = new FormData(document.getElementById("form"));
            $.ajax({
                type: 'POST',
                url: CGloabal.baseUrl + 'admin/goods/updateGoodsInfo',
                data: form,
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function () {
                    CGloabal.layerInfor('修改成功');
                    window.location.reload(true);
                },
                error: function () {
                    CGloabal.layerInfor('服务器出错，请重试');
                }
            });
        },
        addGoods: function () {
            var url = CGloabal.baseUrl + 'admin/goods/addGoods';
            var form = new FormData(document.getElementById("form"));
            $.ajax({
                type: 'POST',
                url: CGloabal.baseUrl + 'admin/goods/addGoods',
                data: form,
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function () {
                    CGloabal.layerInfor('添加成功');
                    window.location.reload(true);
                },
                error: function () {
                    CGloabal.layerInfor('服务器出错，请重试');
                }
            });
        },
        saveGoods: function () {
            if (typeof($('#goods_id').attr('readonly')) == 'undefined') {
                classes.addGoods();
            } else {
                classes.updateGoods();
            }
        }
    };
    $('#inpage').jqPaginator({
        totalPages: $('.bg_selected').children('span').text() / 10 + 1,
        visiblePages: 10,
        currentPage: 1,
        onPageChange: function (num, type) {
            $('#inpage_text').html('当前第' + num + '页');
            classes.getProduct(num);
        }
    });
</script>