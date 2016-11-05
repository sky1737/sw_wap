<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>html</title>
    <link rel="stylesheet" href="">
    <link rel="stylesheet" type="text/css" href="css/font_1459473269_4751618.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!--必要样式-->
    <link rel="stylesheet" type="text/css" href="css/menu_elastic.css"/>
    <script src="js/snap.svg-min.js"></script>
    <!--[if IE]>
    <script src="js/html5.js"></script>
    <![endif]-->
</head>
<body class="huibg">
<nav class="navbar text-center">
    <a href="index.php" class="topleft"><span class="iconfont icon-fanhui"></span></a>
    <a class="navbar-tit center-block">公司信息</a>
    <!--<button class="topnav" id="open-button"><a href="gsxxxg.html"><span class="iconfont icon-1"></span></a></button>-->
</nav>
<div class="dingdan">
    <div class="ddlist">
        <div class="dtit">公司信息</div>
        <div class="dz"><p class="ziku">名 称：</p><?php echo $info['comName']; ?></div>
        <div class="dz"><p class="ziku">电 话：</p><?php echo $info['comTel']; ?></div>
        <!--<div class="dz"><p class="ziku">邮 箱：</p><?php /*echo $info['name']; */ ?></div>-->
        <div class="dz"><p class="ziku">地 址：</p><span><?php echo $info['comAddress']; ?></span></div>
    </div>
    <!--
    <div class="ddlist">
        <div class="dtit">企业组织机构代码</div>
        <div class="dz noblord">123123123</div>
    </div>
    -->
    <div class="ddlist">
        <div class="dtit">负责人</div>
        <div class="dz"><p class="ziku">名 称：</p><?php echo $info['uName']; ?></div>
        <div class="dz"><p class="ziku">电 话：</p><?php echo $info['tel']; ?></div>
        <!--<div class="dz"><p class="ziku">邮 箱：</p><?php /*echo $info['name']; */ ?></div>-->
    </div>
</div>


<script src="js/classie.js"></script>
<script src="js/main3.js"></script>
</body>
</html>