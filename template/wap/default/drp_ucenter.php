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
<title>分销管理 -<?php echo $now_store['name']; ?></title>
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
			<h1 class="title">分销管理</h1>
		</section>
	</nav>
</div>
<div class="panel memberhead">
	<div class="header-l"> <i class="icon-level-dis"></i> </div>
	<div class="header-r"> <!--<a href="./drp_store.php?a=account">--><span class="name"><?php echo $now_store['name']; ?></span>
		<br/>
		<!--<i class="try-tip"><?php
		if($now_store['drp_approve']) {
			echo '分销商';
		}
		else {
			echo '待审核分销商';
		}
		if($now_store['agent_approve']) {
			echo '、代理商';
		} ?></i>
		<i class="arrow"></i>--> </a> </div>
</div>
<div class="row count">
	<div class="small-6 large-3 columns mid">
		<a href="./drp_store.php?a=sales" class="count-a">
		<p class="count-dis-mony"><?php echo $now_store['sales']; ?></p>
		<span class="count-title">本店销售总额(元)</span></a> </div>
	<div class="small-6 large-3 columns mid">
		<a href="./balance.php?a=index" class="count-a">
		<p class="count-dis-mony"><?php echo $wap_user['balance']; ?></p>
		<span class="count-title">账户余额(元)</span></a> </div>
</div>
			<div class="menu-list">
			 <ul>
        			<li>
                			<a  href="./drp_store.php?a=edit">
        						<img src="<?php echo TPL_URL; ?>images/tb/dpgl.png" alt="">
                				<p>店铺信息</p>
                			</a>
                		</li>
			    <li>
                			<a href="./balance.php?a=recharge">
        						<img src="<?php echo TPL_URL; ?>images/tb/cz.png" alt="">
                				<p>账户充值</p>
                			</a>
                		</li>
        			<li>
                			<a  href="./balance.php?a=withdrawal">
        						<img src="<?php echo TPL_URL; ?>images/tb/wdgz.png" alt="">
                				<p>佣金提现</p>
                			</a>
                		</li>
        			<li>
                			<a  href="./qrcode.php">
        						<img src="<?php echo TPL_URL; ?>images/tb/ewm.png" alt="">
                				<p>店铺二维码</p>
                			</a>
                		</li>
        			        	</ul></div>
<!--<div class="panel member-nav">
	<ul class="side-nav">
		<li class="last"><a href="./drp_ucenter.php?a=profile"> <i class="icon-personal"></i> <span class="text">我的资料</span> <span id="personStatus" class=""></span> <i class="arrow"></i> </a> </li>
	</ul>
</div>-->
<div class="panel member-nav">
	<ul class="side-nav">
		<li> <a href="./drp_team.php"><i class="icon-lowLevel"></i><span class="text">我的团队</span><i class="arrow"></i></a>
		<li ><a href="./drp_order.php?a=index"><i class="icon-disorder"></i><span class="text">分销订单</span><i class="arrow"></i></a></li>
		<li class="last"><a href="./balance.php?a=statistics"><i class="icon-commission"></i><span class="text">我的佣金</span><i class="arrow"></i></a></li>
	</ul>
</div>
<?php
if($now_store['agent_id']){
?>
<div class="panel member-nav">
	<ul class="side-nav">
		<li id="brokerage" class="last"><a href="./agent.php"><i class="icon-client"></i><span
					class="text">代理中心</span><i class="arrow"></i></a></li>
	</ul>
</div>
<?php
}
include display('drp_footer');
echo $shareData;
?>
<script>
//$(document).foundation().foundation('joyride', 'start');
</script>
</body>
</html>