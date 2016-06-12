<?php if(!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta charset="utf-8" />
<meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="format-detection" content="telephone=no" />
<title>代理中心 - <?php echo $now_store['name']; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/foundation.css" />
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/normalize.css" />
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/common.css" />
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/drp_dis.css" />
<style type="text/css">
.header-r .try-tip {width: 100px;}
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
		<section class="left-small"> <a class="menu-icon" href="./"><span></span></a> </section>
		<section class="middle tab-bar-section">
			<h1 class="title">代理中心</h1>
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
	<div class="small-6 large-3 columns mid">
		<a href="./drp_store.php?a=sales" class="count-a">
		<p class="count-dis-mony"><?php echo $now_store['sales']; ?></p>
		<span class="count-title">本店销售总额(元)</span></a> </div>
	<div class="small-6 large-3 columns mid">
		<a href="./balance.php?a=statistics" class="count-a">
		<p class="count-dis-mony"><?php echo $now_store['balance']; ?></p>
		<span class="count-title">佣金总额(元)</span></a> </div>
</div>
<div class="panel member-nav">
	<ul class="side-nav">
		<li><a href="./agent_products.php"><i class="icon-product"></i><span class="text">商品管理</span><i class="arrow"></i></a></li>
		<li><a href="./agent_order.php"><i class="icon-disorder"></i><span class="text">发货订单</span><i class="arrow"></i></a></li>
	</ul>
</div>
<div class="panel member-nav">
	<ul class="side-nav">
		<li id="brokerage" class="last"><a href="./agent_commission.php"><i class="icon-commission"></i><span class="text">代理佣金</span><i class="arrow"></i></a></li>
	</ul>
</div>
<?php
/*if($now_store['agent_approve']){
?>
<div class="panel member-nav">
	<ul class="side-nav">
		<li id="brokerage" class="last"><a href="./agent.php"><i class="icon-client"></i><span
					class="text">代理中心</span><i class="arrow"></i></a></li>
	</ul>
</div>
<?php
}*/
include display('drp_footer');
?>
<script>
//$(document).foundation().foundation('joyride', 'start');
</script>
</body>
</html>