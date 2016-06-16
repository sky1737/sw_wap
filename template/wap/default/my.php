<?php if (!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8"/>
<meta name="keywords" content="<?php echo $config['seo_keywords']; ?>"/>
<meta name="description" content="<?php echo $config['seo_description']; ?>"/>
<link rel="icon" href="<?php echo $config['site_url']; ?>/favicon.ico"/>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/common.css" />
<title>个人中心</title>
<meta name="format-detection" content="telephone=no"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
<meta name="apple-mobile-web-app-capable" content="yes"/>
<meta name="apple-mobile-web-app-status-bar-style" content="default"/>
<meta name="applicable-device" content="mobile"/>
<link rel="stylesheet" href="<?php echo TPL_URL; ?>index_style/css/my.css"/>
<script src="<?php echo $config['oss_url']; ?>/static/js/fastclick.js"></script>
<script src="<?php echo $config['oss_url']; ?>/static/js/jquery.min.js"></script>
<script src="<?php echo TPL_URL; ?>index_style/js/base.js"></script>
</head>
<body style="padding-bottom:70px;">
<div class="wx_wrap">
	<div class="head-img"> 
		<!-- S 账户信息 --> 
		<img class="my-img" src="<?php echo $wap_user['avatar']; ?>"/>
		<p><?php echo $wap_user['nickname']; ?></p>
	</div>
	<!-- S 账户/积分 -->
	<div class="row count" >
		<div class="small-6 large-3 columns mid" style="width:50%; float: left;"> <a href="./balance.php?a=index" class="count-a">
			<p class="count-dis-mony"><?php echo $wap_user['balance']; ?></p>
			<span class="count-title">帐户余额(元)</span></a> </div>
		<div class="small-6 large-3 columns mid" style="width:50%; float: left;"> <a href="./balance.php?a=index" class="count-a">
			<p class="count-dis-mony"><?php echo $wap_user['point']; ?></p>
			<span class="count-title">我的积分</span></a> </div>
	</div>
	
	<!-- S 账户/积分 -->
	<div class="my_menu">
		<ul>
			<li class="tiao"><a href="./my_order.php" class="menu_1">
				<p><?php echo $result[0]['c1']; ?></p>
				全部订单</a> </li>
			<li class="tiao"> <a href="./my_order.php?action=unpay" >
				<p><?php echo $result[0]['c2']; ?></p>
				待付款</a> </li>
			<li class="tiao"> <a href="./my_order.php?action=unsend" class="menu_4">
				<p><?php echo $result[0]['c3']; ?></p>
				待发货</a> </li>
			<li class="tiao"> <a href="./my_order.php?action=send" >
				<p><?php echo $result[0]['c4']; ?></p>
				待收货</a> </li>
		</ul>
	</div>
	<!-- E 入口菜单 --> 
	
	<!-- S 入口列表 -->
	<ul class="my_list">
		<li class="tiao"><a href="./cart.php"><img src="<?php echo TPL_URL; ?>images/tb//gwc.png" alt="">我的购物车</a></li>
		<li class="tiao"><a href="./trade.php?id=<?php echo $now_store['store_id']; ?>"><img src="<?php echo TPL_URL; ?>images/tb//lljl.png" alt="">我的浏览记录</a></li><!---->
		<li class="hr"></li>
		<li class="tiao"><a href="./my_address.php"><img src="<?php echo TPL_URL; ?>images/tb//shdz.png" alt="">收货地址管理</a></li>
		<!--<li class="hr"></li>
		<li class="tiao"><a href="./app_million.php"><img src="<?php echo TPL_URL; ?>images/tb/bwdj.png" alt="">十万大奖</a></li>-->
	</ul>
	<!-- E 入口列表 --> 
	<!--div class="my_links">
		<a href="tel:4006560011" class="link_tel">致电客服</a>
		<a href="#" class="link_online">在线客服</a>
	</div--> 
</div>
<?php
include display('public_menu');
echo $shareData;
?>
</body>
</html>