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
    <a class="navbar-tit center-block">业绩统计</a>
</nav>
<br/>
<ul id="myTab" class="nav nav-tabs">
    <li class="active"><a href="#sp1" data-toggle="tab">销售信息</a>
    </li>
    <li><a href="#sp2" data-toggle="tab">业绩报表</a></li>
</ul>

<div id="myTabContent" class="tab-content">
    <div class="tab-pane fade in active" id="sp1">
        <ul class="ddlist">
            <div class="order-detail">
                <li><span>当月销售额：</span>￥<?php echo $monthSubTotals ?></li>
                <li><span>总计销售额：</span>￥<?php echo $allSubTotals ?></li>
            </div>
            <!--<div class="record-ye">
	   	  <p>销售总额</p>
	   	  <b>￥53544.21</b>
	    </div>
	    <div class="re-chong">
	      <a href="integralexchange.html"><img src="images/gift.png" width="22"/><span>兑换</span></a>
	    </div-->
            <li>
                <form action="" method="get">
                    <div class="form-group m-r-10">
                        <select class="form-control" name="month">
                            <option value="0">本月</option>
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
        <!--
              <ul class="ddlist">
                 <li>
                    <a href="ddinfo.html">
                        <p>某某供货商</p>
                       <p>订单时间：2015-09-18 21:00:35</p>
                       <p>某某商品</p>
                       <p><span>价格：1200</span></p>
                       <p><span>利润：200</span></p>
                    </a>
                 </li>
                 <li>
                    <a href="ddinfo.html">
                        <p>某某供货商</p>
                       <p>订单时间：2015-09-18 21:00:35</p>
                       <p>某某商品</p>
                       <p><span>价格：1200</span></p>
                       <p><span>利润：200</span></p>
                    </a>
                 </li>
                 <li>
                    <a href="ddinfo.html">
                        <p>某某供货商</p>
                       <p>订单时间：2015-09-18 21:00:35</p>
                       <p>某某商品</p>
                       <p><span>价格：1200</span></p>
                       <p><span>利润：200</span></p>
                    </a>
                 </li>
                 <li>
                    <a href="ddinfo.html">
                        <p>某某供货商</p>
                       <p>订单时间：2015-09-18 21:00:35</p>
                       <p>某某商品</p>
                       <p><span>价格：1200</span></p>
                       <p><span>利润：200</span></p>
                    </a>
                 </li>
              </ul>
               -->
    </div>
    <div class="tab-pane fade" id="sp2">
        <ul class="ddlist">
            <li>
                <form action="" method="get">
                    <div class="form-group m-r-10">
                        <select class="form-control" name="month" id="ddlist_select">
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
                                <button type="button" id="ddlist_btn" class="btn btn-danger btn-block btn-lg">查询</button>
                            </div>
                        </div>
                    </div>
                </form>
            </li>
        </ul>

        <div class="chart-container">
            <p>销售总量: <span> ￥</span><span id="saleTotal">0</span></p>
            <canvas id="saleChart" width="400" height="400"></canvas>
        </div>
    </div>
</div>

<script>
    function intiChartModel() {
        return $.parseJSON('<?php echo $data ?>');
    }
</script>

<script src="js/classie.js"></script>
<script src="js/chart.min.js"></script>
<script src="js/main3.js"></script>

</body>
</html>