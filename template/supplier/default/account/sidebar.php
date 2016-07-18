<?php $select_sidebar = isset($select_sidebar) ? $select_sidebar : ACTION_NAME; ?>

<aside class="ui-sidebar sidebar" style="min-height:100%;">
	<nav>
		<?php
		if (true || $store_session['agent_id']) { ?>
			<ul>
				<li>
					<h4>供应商</h4>
				</li>
			</ul>
			<ul>
				<li <?php if ($select_sidebar == 'supplier') { ?>class="active"<?php } ?>><a href="<?php dourl('supplier'); ?>">代理概况</a></li>
				<li <?php if ($select_sidebar == 'supplier_order') { ?>class="active"<?php } ?>><a href="<?php dourl('supplier_order'); ?>">代理订单</a></li>
				<?php /*<li <?php if ($select_sidebar == 'seller') { ?>class="active"<?php } ?>><a href="<?php dourl('seller'); ?>">我的分销商</a></li>
			<li <?php if ($select_sidebar == 'setting') { ?>class="active"<?php } ?>><a href="<?php dourl('setting'); ?>">分销配置</a></li>*/?>
			</ul>
			<ul class="dianpu_left">
				<?php /*<li<?php if($select_sidebar == 'coupon') echo ' class="active"';?>><a href="<?php echo dourl('account:coupon') ?>">优惠劵</a></li>*/ ?>
				<li<?php if($select_sidebar == 'income') echo ' class="active"'; ?>>
					<a href="<?php echo dourl('account:income') ?>">收支记录</a></li>
			</ul>
			<?php
		} ?>
	</nav>
</aside>
<?php
/*<aside class="ui-sidebar sidebar" style="min-height: 500px;">
	<nav>
		<ul>
			<li <?php if ($select_sidebar == 'index') { echo 'class="active"'; } ?>><a href="<?php dourl('store:index'); ?>">微店铺概况</a></li>
		</ul>
		<h4>页面管理</h4>
		<ul>
			<li <?php if ($select_sidebar == 'wei_page') { echo 'class="active"'; } ?>><a href="<?php dourl('store:wei_page'); ?>">店铺模板/杂志</a></li>
			<li <?php if ($select_sidebar == 'wei_page_category') { echo 'class="active"'; } ?>><a href="<?php dourl('store:wei_page_category'); ?>">
				<?php if($_SESSION['user']['admin_id']){?>
				行业分类
				<?php }else{?>
				微页面分类
				<?php } ?>
				</a></li>
			<li <?php if ($select_sidebar == 'ucenter') { echo 'class="active"'; } ?>><a href="<?php dourl('store:ucenter'); ?>">会员主页</a></li>
			<!--<li <?php if ($select_sidebar == 'storenav') { echo 'class="active"'; } ?>><a href="<?php dourl('store:storenav'); ?>">店铺底部模板</a></li>-->
		</ul>
		<h4>通用模块</h4>
		<ul>
			<li><a href="<?php dourl('case:ad'); ?>">公共广告设置</a></li>
			<li><a href="<?php dourl('case:page'); ?>">自定义页面模块</a></li>
			<li><a href="<?php dourl('case:attachment'); ?>">我的文件</a></li>
		</ul>
		<?php if (empty($_SESSION['sync_store'])) { ?>
		<h4>店铺服务</h4>
		<ul>
			<li <?php if ($select_sidebar == 'service') { echo 'class="active"'; } ?>><a href="<?php dourl('store:service'); ?>">客服列表</a></li>
		</ul>
		<?php } ?>
		<h4>店铺信息</h4>
		<ul class="dianpu_left">
			<li class="<?php if ($select_sidebar == 'store') { ?>active<?php } ?> info"><a href="<?php dourl('setting:store'); ?>#info">店铺信息</a></li>
			<li class="<?php if ($select_sidebar == 'store') { ?>active<?php } ?> contact"><a href="<?php dourl('setting:store'); ?>#contact">联系我们</a></li>
			<li class="<?php if ($select_sidebar == 'store') { ?>active<?php } ?> list"><a href="<?php dourl('setting:store'); ?>#list">门店管理</a></li>
			<li <?php if ($select_sidebar == 'config') { echo 'class="active"'; } ?>><a href="<?php dourl('setting:config'); ?>">物流配置</a></li>
		</ul>
		</ul>
	</nav>
</aside>
*/ ?>
