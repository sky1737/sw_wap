<div style="height: 50px;"></div>
<div class="wx_nav">
	<a href="./index.php" class="nav_index <?php if ($php_self == 'index.php') { echo 'on'; } ?>">首页</a>

	<a href="./category.php<?php //./category.php ?>" class="nav_search <?php if ($php_self == 'category.php') {
		echo 'on';
	} ?>">分类</a>

	<a href="./cart.php" class="nav_shopcart <?php if ($php_self == 'cart.php') {
		echo 'on';
	} ?>">购物车</a>

	<?php
	if($isSupplier){
		?>
		<a href="./supplier_ucenter.php" class="nav_shopcart<?php if ($php_self == 'weidian.php') { echo 'on'; } ?>">供应商</a>
		<?php
	}else{
		?>
		<a href="./my.php" class="nav_me <?php if ($php_self == 'my.php') { echo 'on'; } ?>">个人中心</a>
		<?php
	}
	?>

</div>