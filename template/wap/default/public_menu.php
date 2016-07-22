<div style="height: 50px;"></div>
<div class="wx_nav">
	<a href="./index.php" class="nav_index <?php if ($php_self == 'index.php') {
		echo 'on';
	} ?>">首页</a>
	<a href="./category.php" class="nav_search <?php if ($php_self == 'category.php') {
		echo 'on';
	} ?>">分类</a>

	<?php
	$url = $isSupplier ? 'supplier' : 'drp';
	$urlName = $isSupplier ? '供应' : '分销';
	?>

	<a href="./<?php echo $url ?>_ucenter.php" class="nav_shopcart <?php if ($php_self == 'weidian.php') {
		echo 'on';
	} ?>"><?php echo $urlName ?></a>
	<a href="./my.php" class="nav_me <?php if ($php_self == 'my.php') {
		echo 'on';
	} ?>">个人中心</a>
</div>