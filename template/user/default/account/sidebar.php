<?php $select_sidebar = isset($select_sidebar) ? $select_sidebar : ACTION_NAME; ?>

<aside class="ui-sidebar sidebar" style="min-height:100%;">
	<nav>
		<ul>
			<li<?php if($select_sidebar == 'index') echo ' class="active"'; ?>>
				<a href="<?php echo dourl('account:index') ?>">个人账户</a></li>
		</ul>
		<h4>订单中心</h4>
		<ul>
			<li<?php if($select_sidebar == 'order') echo ' class="active"'; ?>>
				<a href="<?php echo dourl('account:order') ?>">我的订单</a></li>
		</ul>
		<?php /*<h4>推客中心</h4>
		<ul>
			<li><a href="<?php echo dourl('account:rebate') ?>">佣金记录</a></li>
			<li><a href="<?php echo dourl('account:fans') ?>">我的粉丝</a></li>
			<li><a href="<?php echo dourl('account:cash') ?>">佣金提现</a></li>
		</ul>*/ ?>
		<h4>收藏中心</h4>
		<ul>
			<li<?php if($select_sidebar == 'collect_goods') echo ' class="active"'; ?>>
				<a href="<?php echo dourl('account:collect_goods') ?>">商品收藏</a></li>
			<li<?php if($select_sidebar == 'collect_store') echo ' class="active"'; ?>>
				<a href="<?php echo dourl('account:collect_store') ?>">店铺收藏</a></li>
			<li<?php if($select_sidebar == 'attention_store') echo ' class="active"'; ?>>
				<a href="<?php echo dourl('account:attention_store') ?>">关注店铺</a></li>
		</ul>
		<h4>个人中心</h4>
		<ul class="dianpu_left">
			<li<?php if($select_sidebar == 'address') echo ' class="active"'; ?>>
				<a href="<?php echo dourl('account:address') ?>">收货地址</a></li>
			<?php /*<li<?php if($select_sidebar == 'password') echo ' class="active"';?>><a href="<?php echo dourl('account:password') ?>">修改密码</a></li>*/ ?>
			<li<?php if($select_sidebar == 'info') echo ' class="active"'; ?>>
				<a href="<?php echo dourl('account:info') ?>">个人资料</a></li>
		</ul>
		<h4>资产中心</h4>
		<ul class="dianpu_left">
			<?php /*<li<?php if($select_sidebar == 'coupon') echo ' class="active"';?>><a href="<?php echo dourl('account:coupon') ?>">优惠劵</a></li>*/ ?>
			<li<?php if($select_sidebar == 'income') echo ' class="active"'; ?>>
				<a href="<?php echo dourl('account:income') ?>">收支记录</a></li>
		</ul>
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
