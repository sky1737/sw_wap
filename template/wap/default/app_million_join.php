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

<script src="<?php echo $config['oss_url']; ?>/static/js/jquery.min.js"></script>
<!--<script src="<?php echo $config['oss_url']; ?>/static/js/fastclick.js"></script>
<script src="<?php echo TPL_URL; ?>index_style/js/base.js"></script>-->
</head>
<body class="body-gray_1">
<div class="fixed tab-bar">
	<section class="left-small"><a href="./app_million.php" class="menu-icon"><span></span></a></section>
	<section class="middle tab-bar-section">
		<h1 class="title">十万大奖</h1>
	</section>
</div>
<div class="gg_dj">
	<div class="row_12">
		<div class="large-12">
			<label> 投资积分数量 </label>
			<input type="text" id="point" value="1000" onBlur="if(!chk(this.value)){this.value='';}" name="point" class="last" placeholder="填写投资积分的数量">
		</div>
	</div>
	<div class="gg_dj-a"><div class="gg_dj-a1"><a id="join" href="javascript:;" ></a></div>
		<h2>规则说明!</h2>
		<p>一、要求个人账户中最少有1000积分（积分比例：1元=100积分）可参与十万大奖游戏!<br />
			二、参与十万大奖游戏后1000积分将从您的个人账户中扣除!<br />
			三、规则说明!规则说明!规则说明!<br />
			四、规则说明!规则说明!规则说明!规则说明!</p>
	</div>
</div>
<script type="text/javascript" src="<?php echo TPL_URL; ?>app/million.js"></script>
<?php
//include display('public_menu');
echo $shareData;
?>
</body>
</html>