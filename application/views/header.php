<head>
    <meta charset="utf-8">
    <title>收银系统</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Loading Bootstrap -->
    <link href="<?php echo base_url();?>assets/css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/vendor/bootstrap/css/main.css" rel="stylesheet">

    <!-- Loading Flat UI -->
    <link href="<?php echo base_url();?>assets/css/flat-ui.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/new.css" rel="stylesheet">

    <link rel="shortcut icon" href="<?php echo base_url();?>assets/img/favicon.ico">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap-table.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap-table.min.css">

    <!-- jQuery (necessary for Flat UI's JavaScript plugins) -->
    <script src="<?php echo base_url();?>assets/js/vendor/jquery.min.js"></script>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
    <script src="<?php echo base_url();?>assets/js/vendor/html5shiv.js"></script>
    <script src="<?php echo base_url();?>assets/js/vendor/respond.min.js"></script>
    <![endif]-->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo base_url();?>assets/js/vendor/video.js"></script>
    <script src="<?php echo base_url();?>assets/js/flat-ui.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/layer_mobile/layer.js"></script>
    <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/bootstrap-table.js"></script>
    <script src="<?php echo base_url();?>assets/js/login.js"></script>
</head>
<script>
    CGloabal={
        baseUrl:'<?php echo base_url();?>',
        //加载框
        layerLoading:function(msg){
            layer.open({
                type: 2,
                time:1,
                content: msg
            });
        },
        //提示框
        layerInfor:function(msg){
            layer.open({
                content:msg,
                btn:'确认',
                style:'boder:2px solid #1abc9c;width:40%'
            });
        },
        //询问框
        layerQue:function(msg,callback){
            layer.open({
                content:msg,
                btn:['确认','取消'],
                style:'width:40%',
                yes:callback
            });
        },
        //页面收款
        layer:function(money){
            layer.open({
                type: 1
                ,content: ['<div class="panel-body" data-money='+money+'>',
                    '        <label>',
                    '            <h5>收款金额：￥'+money+'</h5>',
                    '        </label>',
                    '        <label style="margin-left: 10%" class="radio">',
                    '            <input name="radio" type="radio" id="pay_cash" onchange="pay.checkChange()">',
                    '            <img src="<?php echo base_url();?>assets/img/cash.png">',
                    '            现金支付',
                    '        </label>',
                    '        <label style="margin-left: 10%" class="radio" id="zfb">',
                    '            <input name="radio" type="radio" id="pay_zfb" onchange="pay.checkChange()" >',
                    '            <img src="<?php echo base_url();?>assets/img/zfb.png">',
                    '            支付宝支付',
                    '        </label>',
                    '        <button class="col-md-5 bg-primary" style="margin-left: 25%;margin-top: 5px" onclick="pay.payConfirm()">确认收款</button>',
                    '    </div>'].join("")
                ,anim: 'up'
                ,style: 'margin:10% 37.5%;z-index: 3000;border-radius:10px'
            });
        },
        //页面退出层
        layerCheckOut:function(data){
            layer.open({
                type:1,
                content:['<div class="panel-body"> ' +
                '<label><h6>核对收银详情：</h6></label>' +
                '<p>收银人员：'+data['emp_name']+'</p>' +
                '<p id="member_add">新增会员数：'+data['member_add']+'</p>' +
                '<p id="cash_total">现金交易金额：￥'+data['invoice']['cash_total']+'</p>' +
                '<p id="zfb_total">支付宝交易金额：￥'+data['invoice']['zfb_total']+'</p>' +
                '<p id="cash_count">现金交易笔数：'+data['invoice']['cash_count']+'</p>' +
                '<p id="zfb_count">支付宝交易笔数：'+data['invoice']['zfb_count']+'</p>' +
                '<p>备注：</p><input id="remark" type="text" style="width: 80%" class="form-control"><br>' +
                '<button onclick="loginAction.logout()" class="btn btn-inverse btn-sm" style="float: right">确认对账</button><br>' +
                '</div>'].join(""),
                anim:'up',
                style:''
            });
        }
    }
</script>
<body>
