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
<meta class="foundation-data-attribute-namespace"/>
<meta class="foundation-mq-xxlarge"/>
<meta class="foundation-mq-xlarge"/>
<meta class="foundation-mq-large"/>
<meta class="foundation-mq-medium"/>
<meta class="foundation-mq-small"/>
<title>切换店铺 -<?php echo $now_store['name']; ?></title>
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
<div class="mask"></div>
<div id="myStore" class="reveal-modal alert-header radius" data-reveal="">
	<ul class="side-nav">
		<li class="first"><a href="./drp_store.php?a=edit"><i class="icon-edit-w"></i>编辑我的微店</a></li>
		<li class="second"><a href="./home.php?id=<?php echo $now_store['store_id']; ?>"><i
				class="icon-eye-w"></i>进入我的微店</a></li>
	</ul>
</div>
<div class="fixed">
	<nav class="tab-bar">
		<section class="left-small"> <a class="menu-icon" href="./drp_ucenter.php"><span></span></a> </section>
		<section class="middle tab-bar-section">
			<h1 class="title">切换店铺</h1>
		</section>
	</nav>
</div>
<div class="panel memberhead">
	<div class="header-l"> <i class="icon-level-dis"></i> </div>
	<div class="header-r"> <a href="./drp_ucenter.php?a=profile"> <span class="name"><?php echo $now_store['name']; ?></span>
		<?php if ($drp_approve) { ?>
		<i class="try-tip">正式分销商</i>
		<?php }
		else { ?>
		<i class="try-tip">待审核分销商</i>
		<?php } ?>
		<span class="header-tip-text">
		<?php if (!empty($now_store['supplier'])) { ?>
		供货商：<?php echo $now_store['supplier']; ?>
		<?php }
			else { ?>
		您已成为正式分销商...
		<?php } ?>
		</span> <i class="arrow"></i> </a> </div>
	<a href="./drp_ucenter.php?a=profile"></a></div>
<a href="./drp_ucenter.php?a=profile"></a>
<div class="row count"><a href="./drp_ucenter.php?a=profile"></a>
	<div class="small-4 large-3 columns mid"><a href="./drp_ucenter.php?a=profile"> </a><a href="./drp_store.php?a=sales" class="count-a">
		<p class="count-dis-mony"><?php echo $now_store['sales']; ?></p>
		<span class="count-title">本店销售总额(元)</span></a> </div>
	<div class="small-4 large-3 columns mid"> <a href="./balance.php?a=statistics" class="count-a">
		<p class="count-dis-mony"><?php echo $now_store['balance']; ?></p>
		<span class="count-title">佣金余额(元)</span></a> </div>
</div>
<?php if (!empty($stores)) { ?>
<div class="panel member-nav">
	<ul class="side-nav">
		<?php foreach ($stores as $key => $store_info) { ?>
		<li <?php if ($key == (count($store_info) - 1)) { ?>class="last"<?php } ?>><a
					href="./drp_store.php?a=select&id=<?php echo $store_info['store_id']; ?>"><i
						class="icon-shop"></i><span class="text"><?php echo $store_info['name']; ?></span><i
						class="arrow"></i></a></li>
		<?php } ?>
	</ul>
</div>
<?php } ?>
<div class="h50"></div>
<?php echo $shareData; ?>
</body>
</html>