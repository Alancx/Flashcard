<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><meta name="renderer" content="webkit">

    <title>推广 - 登录</title>

    <link href="/Public/Admin/Admin/css/bootstrap.min.css?v=3.4.0" rel="stylesheet">
    <link href="/Public/Admin/Admin/font-awesome/css/font-awesome.css?v=4.3.0" rel="stylesheet">

    <link href="/Public/Admin/Admin/css/animate.css" rel="stylesheet">
    <link href="/Public/Admin/Admin/css/style.css?v=2.2.0" rel="stylesheet">

</head>


<body class="gray-bg">

    <div class="middle-box text-center loginscreen  animated fadeInDown">
        <div>

            <form class="m-t" role="form" action="" method="post">
            <div style="width:100%;margin-bottom:10%;">
            <img src="/Public/Admin/Admin/img/logo.png" alt="" style="width:60%;">
            <!-- <h1>Lvs</h1> -->
            </div>
            <h3>欢迎使用 推广平台</h3>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="用户名" name="username" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="密码" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">登 录</button>
            </form>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="/Public/Admin/Admin/js/jquery-2.1.1.min.js"></script>
    <script src="/Public/Admin/Admin/js/bootstrap.min.js?v=3.4.0"></script>


</body>

</html>