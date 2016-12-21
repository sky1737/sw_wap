<?php if (!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta charset="utf-8"/>
<meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
<meta name="apple-mobile-web-app-capable" content="yes"/>
<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
<meta name="format-detection" content="telephone=no"/>
<title>管理店铺 -<?php echo $now_store['name']; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/foundation.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/normalize.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/common.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/drp_dis.css"/>
<style type="text/css">
.header-r .try-tip { width: 100px; }
</style>
<script src="<?php echo TPL_URL; ?>js/jquery.js"></script>
<script src="<?php echo TPL_URL; ?>js/drp_foundation.js"></script>
<script src="<?php echo TPL_URL; ?>js/drp_foundation.reveal.js"></script>
<script src="<?php echo TPL_URL; ?>js/drp_func.js"></script>
<script src="<?php echo TPL_URL; ?>js/drp_common.js"></script>
</head>

<body class="body-gray">
<div class="fixed">
	<nav class="tab-bar">
		<section class="left-small"> <a class="menu-icon" href="./drp_ucenter.php"><span></span></a> </section>
		<section class="middle tab-bar-section">
			<h1 class="title">管理店铺</h1>
		</section>
	</nav>
</div>
<div class="panel memberhead">
	<div class="header-l"> <i class="icon-level-dis"></i> </div>
	<div class="header-r"> <a href="./drp_ucenter.php"> <span class="name"><?php echo $now_store['name']; ?></span>
		<br/>
		<i class="try-tip"><?php
		if($now_store['drp_approve']) {
			echo '分销商';
		}
		else {
			echo '待审核分销商';
		}
		if($now_store['agent_id']) {
			echo '、代理商';
		} ?></i>
		<i class="arrow"></i> </a> </div>
</div>
<div class="row count">
	<div class="small-6 large-3 columns mid"><a href="./drp_store.php?a=sales" class="count-a">
		<p class="count-dis-mony"><?php echo $now_store['sales']; ?></p>
		<span class="count-title">本店销售总额(元)</span></a> </div>
	<div class="small-6 large-3 columns mid"> <a href="./balance.php?a=statistics" class="count-a">
		<p class="count-dis-mony"><?php echo $now_store['balance']; ?></p>
		<span class="count-title">佣金余额(元)</span></a> </div>
</div>
<!--
<div class="panel member-nav">
	<ul class="side-nav">

		<?php if (count($stores) > 0) { ?>
		<li><a href="./drp_store.php?a=select"><i class="icon-dis"></i><span class="text">切换店铺</span><i
					class="arrow"></i></a></li>
		<?php } ?>
		<li class="last"><a href="./drp_store.php?a=account"><i class="icon-set"></i><span class="text">管理店铺</span><i
				class="arrow"></i></a></li>
	</ul>
</div>
-->
<div class="panel member-nav">
	<ul class="side-nav">
		<li><a href="./drp_store.php?a=edit"><i class="icon-shop"></i><span
					class="text">微店管理</span><i class="arrow"></i></a></li>
		<li><a href="./qrcode.php"><i
					class="icon-qrcode"></i><span class="text">店铺二维码</span><i class="arrow"></i></a></li>
		<li><a href="javascript:;"><span class="text">管理地址：<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?></span></a></li>
		<!--<li><a href="javascript:;"><span class="text">登录账户：<?php echo $phone; ?></span></a></li>
		<li <?php if (!$password) { ?>class="last"<?php } ?>><a href="javascript:;"><span
				class="text">初始密码：<?php echo $phone; ?></span></a></li>
		<?php if ($password) { ?>
		<li class="last"><a href="./drp_store.php?a=reset_pwd" class="button [radius red round]"
							style="padding: 0;margin-top:10px;margin-bottom: 10px;">重置为初始密码</a></li>
		<?php } ?>-->
	</ul>
</div>
<div class="h50"></div>
<?php
include display('drp_footer');
echo $shareData;
?>
</body>
</html>