<?php
//print_r($store_session);
$select_sidebar=isset($select_sidebar)?$select_sidebar:ACTION_NAME; ?>
<aside class="ui-sidebar sidebar" style="min-height: 100%;">
	<nav>
		<ul>
			<li>
				<h4>分销</h4>
			</li>
		</ul>
		<ul>
			<li <?php if ($select_sidebar == 'index') { ?>class="active"<?php } ?>><a href="<?php dourl('index'); ?>">分销概况</a></li>
			<li <?php if ($select_sidebar == 'market') { ?>class="active"<?php } ?>><a href="<?php dourl('market'); ?>">分销市场</a></li>
			<li <?php if ($select_sidebar == 'order') { ?>class="active"<?php } ?>><a href="<?php dourl('order'); ?>">分销订单</a></li>
			<?php /*<li <?php if ($select_sidebar == 'goods') { ?>class="active"<?php } ?>><a href="<?php dourl('goods'); ?>">已分销商品</a></li>*/?>
			<?php /*<li <?php if ($select_sidebar == 'orders') { ?>class="active"<?php } ?>><a href="<?php dourl('orders'); ?>">采购清单</a></li>
			<li <?php if ($select_sidebar == 'supplier') { ?>class="active"<?php } ?>><a href="<?php dourl('supplier'); ?>">我的供货商</a></li>*/?>
		</ul>
		<?php
		if ($store_session['agent_id']) { ?>
		<ul>
			<li>
				<h4>代理</h4>
			</li>
		</ul>
		<ul>
			<li <?php if ($select_sidebar == 'supplier') { ?>class="active"<?php } ?>><a href="<?php dourl('supplier'); ?>">代理概况</a></li>
			<li <?php if ($select_sidebar == 'supplier_goods') { ?>class="active"<?php } ?>><a href="<?php dourl('supplier_goods'); ?>">我的商品</a></li>
			<li <?php if ($select_sidebar == 'supplier_market') { ?>class="active"<?php } ?>><a href="<?php dourl('supplier_market'); ?>">我分销的商品</a></li>
			<li <?php if ($select_sidebar == 'supplier_order') { ?>class="active"<?php } ?>><a href="<?php dourl('supplier_order'); ?>">代理订单</a></li>
			<?php /*<li <?php if ($select_sidebar == 'seller') { ?>class="active"<?php } ?>><a href="<?php dourl('seller'); ?>">我的分销商</a></li>
			<li <?php if ($select_sidebar == 'setting') { ?>class="active"<?php } ?>><a href="<?php dourl('setting'); ?>">分销配置</a></li>*/?>
		</ul>
		<?php
		} ?>
	</nav>
</aside>
