<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>收银后台</title>

    <!-- Import google fonts - Heading first/ text second -->
<!--    <link rel='stylesheet' type='text/css' href='http://fonts.useso.com/css?family=Open+Sans:400,700|Droid+Sans:400,700' />-->


    <!-- Fav and touch icons -->

    <link href="<?php echo base_url();?>assets/css/flat-ui.css" rel="stylesheet">

    <!-- Css files -->
    <link href="<?php echo base_url();?>assets/css/style.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/new.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/add-ons.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/jquery.mmenu.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/climacons-font.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link rel="shortcut icon" href="<?php echo base_url();?>assets/img/favicon.ico">

    <script src="<?php echo base_url();?>assets/js/vendor/jquery.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/vendor/video.js"></script>
    <script src="<?php echo base_url();?>assets/js/flat-ui.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/layer_mobile/layer.js"></script>
    <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/bootstrap-table.js"></script>
    <script src="<?php echo base_url();?>assets/js/highcharts.js"></script>
    <script src="<?php echo base_url();?>assets/js/CGloabal.js"></script>
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
    }
</script>
<body>
