<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta charset="utf-8"/>
<meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
<meta name="apple-mobile-web-app-capable" content="yes"/>
<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
<meta name="format-detection" content="telephone=no"/>
<meta class="foundation-data-attribute-namespace"/>
<meta class="foundation-mq-xxlarge"/>
<meta class="foundation-mq-xlarge"/>
<meta class="foundation-mq-large"/>
<meta class="foundation-mq-medium"/>
<meta class="foundation-mq-small"/>
<title>我的佣金</title>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/foundation.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/common.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>app/million.css"/>
<!--<script type="text/javascript" src="../default/js/jquery.js"></script>-->
</head>
<body>
<div class="mask"></div>
<div class="fixed">
	<nav class="tab-bar">
		<section class="left-small"> <a class="menu-icon" href="./app_million.php"><span></span></a> </section>
		<section class="middle tab-bar-section">
			<h1 class="title">大奖积分</h1>
		</section>
		<section class="right-xq right-btn-brokerage"> <a class="a-xq-detail" href="./app_million.php?a=withdraw_detail">明细</a> </section>
	</nav>
</div>
<div class="bro-tx">
	<p class="tip-txt">可提取的积分</p>
	<span class="number-big">0</span>
	<p class="field-2">已提取的积分总额：<big>0</big></p>
</div>
<ul class="maneylist" style="display: ;">
</ul>
<div class="tx-12">
	<label> 提取到我的积分</label>
	<input type="text" id="point" onBlur="if(!chk(this.value)){this.value='';}" name="point" class="last" placeholder="填写您要提取的积分">
</div>
<div class="bro-tx-btn"> <a href="javascript:;" onclick="window.location.href='#'" class="button [radius round] red">提取积分</a> </div>
<div class="h50"></div>
</body>
</html>