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
    <a class="navbar-tit center-block">我的供应商</a>
    <!--<button class="topnav" id="open-button"><span class="iconfont icon-1"></span></button>-->
</nav>
<div class="mendian ">
    <div class="table-responsive">
        <table class="table ">
            <thead>
            <tr>
                <th>编号</th>
                <th>公司名称</th>
                <th>地址</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($mySuppliers as $i => $s)
            { ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo $s['nickname'] ?></td>
                    <td><?php echo $s['province'] . $s['city'] ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="js/classie.js"></script>
<script src="js/main3.js"></script>
</body>
</html>