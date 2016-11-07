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
    <a class="navbar-tit center-block">利润报表</a>
</nav>

<div class="jfcont">
    <ul class="ddlist">
        <div class="order-detail">
            <li><span>总销售额：</span>￥<?php echo $monthSubTotals ?></li>
            <li><span>产品利润：</span>￥<?php echo $monthProfit ?></li>
        </div>
        <li>
            <form action="">
                <div class="form-group m-r-10">
                    <select class="form-control" name="month">
                        <option value="0">按月查询</option>
                        <option value="1">1个月</option>
                        <option value="3">3个月</option>
                        <option value="6">6个月</option>
                        <option value="12">12个月</option>
                    </select>
                </div>
                <div class="form-group m-r-10">
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-10">
                            <button type="submit" class="btn btn-danger btn-block btn-lg">查询</button>
                        </div>
                    </div>
                </div>
            </form>
        </li>
    </ul>
    <div class="col-md-10">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>状态</th>
                <th>公司名称</th>
                <th>日期</th>
                <th>价格</th>
                <th>利润</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($sellInfo as $info)
            {
                ?>
                <tr>
                    <td>售出</td>
                    <td><?php echo $agentID2Name[$info['agent_id']]?></td>
                    <td><?php echo date('Y-m-d',$info['complate_time'])?></td>
                    <td class="text-danger"><?php echo $info['sub_total']?></td>
                    <td class="text-danger"><?php echo $info['profit']?></td>
                </tr>
            <?
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<script src="js/classie.js"></script>
<script src="js/main3.js"></script>
</body>
</html>