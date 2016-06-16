<?php if (!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8"/>
<title>百万大奖 - 营销中心|<?php echo $config['site_name']; ?></title>
<meta name="keywords" content="<?php echo $config['seo_keywords']; ?>"/>
<meta name="description" content="<?php echo $config['seo_description']; ?>"/>
<meta name="format-detection" content="telephone=no"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
<meta name="apple-mobile-web-app-capable" content="yes"/>
<meta name="apple-mobile-web-app-status-bar-style" content="default"/>
<meta name="applicable-device" content="mobile"/>

<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/foundation.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/common.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>app/million.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/drp_dis.css"/>
	
<!--<script src="<?php echo $config['oss_url']; ?>/static/js/fastclick.js"></script>
<script src="<?php echo $config['oss_url']; ?>/static/js/jquery.min.js"></script>
<script src="<?php echo TPL_URL; ?>index_style/js/base.js"></script>-->
</head>
<body class="body-gray_1">
<div class="mask"></div>
<div class="fixed">
	<nav class="tab-bar">
		<section class="left-small"> <a class="menu-icon" href="./my.php"><span></span></a> </section>
		<section class="middle tab-bar-section">
			<h1 class="title">十万大奖</h1>
		</section>
	<section class="right-xq right-btn-brokerage"> <a class="a-xq-detail" href="./app_million.php?a=issue">详情</a> </section>
	</nav>
</div>
<div class="bro-awards">
	<p class="tip-txt">投资积分数量</p>
	<span class="number-big"><?php echo $point;?></span>
	<p class="field-2">收益积分数量</p>
	<span class="number-big"><?php echo $income;?></span>
	<div class="bro-box">
		<div class="awards-biaoti-left"> <a href="./app_million.php?a=join">我要投资</a> </div>
		<div class="awards-biaoti-right"><a href="./app_million.php?a=withdraw">申请提现</a></div>
	</div>
</div>
<?php
//include display('public_menu');
echo $shareData;
?>
</body>
</html>