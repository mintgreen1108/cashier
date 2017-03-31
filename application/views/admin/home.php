<!-- start: Main Menu -->
<div class="sidebar ">

    <div class="sidebar-collapse">
        <div class="sidebar-header t-center">
            <span>
                 <?php if (!empty($photo_name)) { ?>
                     <img style="margin-top: -10px"
                          src="<?php echo base_url(); ?>assets/img/merchant_LOG/<?php echo $photo_name; ?>.png">
                     <?php
                 } else {
                     ?>
                     Your Logo
                     <?php
                 } ?>
                <i class="fa fa-shopping-cart fa-3x green"></i>
            </span>
        </div>
        <div class="sidebar-menu">
            <ul class="nav nav-sidebar">
                <li><a href="<?php echo base_url() ?>admin/home"><i class="fa fa-signal"></i><span class="">仪表盘</span></a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-gears"></i><span class="text"> 商家管理</span> <span
                                class="fa fa-angle-down pull-right"></span></a>
                    <ul class="nav sub">
                        <li><a href="<?php echo base_url(); ?>admin/setting"><i class="fa fa-cog"></i><span
                                        class="text"> 基本配置 </span></a></li>
                        <li><a href="<?php echo base_url(); ?>admin/goods"><i class="fa fa-shopping-cart"></i><span
                                        class="text"> 商品管理 </span></a></li>
                        <li><a href="<?php echo base_url(); ?>admin/operator"><i class="fa fa-group"></i><span
                                        class="text"> 收银人员管理 </span></a></li>
                        <li><a href="<?php echo base_url(); ?>admin/daily"><i class="fa fa-list-alt"></i><span
                                        class="text"> 每日统计 </span></a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="fa fa-sitemap"></i><span class="text"> 数据分析 </span><span
                                class="fa fa-angle-down pull-right"></span></a>
                    <ul class="nav sub">
                        <li><a href="<?php echo base_url(); ?>admin/sold"><i class="fa fa-tags"></i><span class="text"> 热销商品</span></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="sidebar-footer">

        <div class="sidebar-brand">
            <?php echo $merchant_name;?>
        </div>

    </div>

</div>
<!-- end: Main Menu -->

<!-- start: Header -->
<div class="navbar" role="navigation">

    <div class="container-fluid">

        <ul class="nav navbar-nav navbar-actions navbar-left">
            <li class="visible-md visible-lg"><a id="main-menu-toggle"><i class="fa fa-th-large"></i></a></li>
            <li class="visible-xs visible-sm"><a id="sidebar-menu"><i class="fa fa-navicon"></i></a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown visible-md visible-lg">
                <a href="#" class="dropdown-toggle"
                   data-toggle="dropdown"><?php echo !empty($emp_mobile) ? $emp_mobile : ''; ?></a>
                <ul class="dropdown-menu">
                    <li class="dropdown-menu-header">
                        <strong>账户</strong>
                    </li>
                    <li><a href="<?php echo base_url() ?>admin/profile"><i class="fa fa-user"></i>个人信息</a></li>
                </ul>
            </li>
            <li><a id="logout"><i class="fa fa-power-off"></i></a></li>
        </ul>

    </div>
</div>
<!-- end: Header -->
<script src="<?php echo base_url(); ?>assets/js/SmoothScroll.js"></script>
<script src="<?php echo base_url(); ?>assets/js/core.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.mmenu.min.js"></script>
<script>
    $('#logout').click(function () {
        var url = CGloabal.baseUrl + 'login/logoutAdmin';
        $.get(url, {}, function (rsps) {
            if (rsps.result) {
                window.location.href = CGloabal.baseUrl + 'login';
            }
        }, 'json');
    });
</script>