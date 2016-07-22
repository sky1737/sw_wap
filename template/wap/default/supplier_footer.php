<div class="h50"></div>
<div class="fixed bottom">
	<dl class="sub-nav nav-b5">
		<dd>
			<div class="nav-b5-relative"> <a href="./"><i class="icon-nav-bag"></i>商城</a></div>
			<!--home.php?id=<?php echo $now_store['store_id']; ?>--> 
		</dd>
		<dd<?php //echo strpos($_SERVER['REQUEST_URI'], 'drp_ucenter') ? ' class="active"' : ''; ?>>
			<div class="nav-b5-relative"> <a href="./supplier_ucenter.php"><i class="icon-nav-cart"></i>供应</a> </div>
		</dd>
		<dd<?php //echo strpos($_SERVER['REQUEST_URI'], 'drp_store') ? ' class="active"' : ''; ?>>
			<div class="nav-b5-relative"> <a href="./qrcode.php"><i class="icon-nav-store"></i>推广</a> </div>
		</dd>
		<dd<?php //echo strpos($_SERVER['REQUEST_URI'], 'drp_ucenter.php?a=personal') ? ' class="active"' : ''; ?>>
			<div class="nav-b5-relative"> <a href="./my.php"><i class="icon-nav-heart"></i>会员</a> </div>
		</dd>
		<!--<dd>
		<div class="nav-b5-relative">
			<a href="./drp_store.php?a=logout"><i class="icon-nav-search"></i>退出</a>
		</div>
	</dd>-->
	</dl>
</div>
