<?php $select_nav = isset($select_nav) ? $select_nav : MODULE_NAME; ?>

<div id="hd" class="wrap rel">
	<div class="wrap_1000 clearfix"> 
		<nav class="ui-header-nav">
			<ul class="clearfix">
				<?php if ($config['pc_shopercenter_logo'] != '') { ?>
				<li><a href="<?php dourl('index:index:index'); ?>"> <img src="<?php echo $config['pc_shopercenter_logo']; ?>" height="35"
					     alt="<?php echo $config['site_name']; ?>" style="height:40px;width:auto;margin-top:-8px;"/> </a></li>
				<?php } ?>
				<li <?php if ($select_nav == 'account') echo 'class="active"'; ?>> <a href="<?php dourl('account:index'); ?>">账户</a> </li>
				<?php if($user_session['stores']) { ?>
				<li class="divide">|</li>
				<li <?php if (in_array($select_nav, array('case', 'store', 'setting'))) echo 'class="active"'; ?>> <a href="<?php dourl('store:index'); ?>">店铺</a> </li>

					<?php if($store_session['agent']['is_agent']) {?>
						<li class="divide">|</li>
				<li <?php if ($select_nav == 'fx') echo 'class="active"'; ?>> <a href="<?php echo dourl('fx:index'); ?>">市场</a> </li>
				<?php } ?>
				<?php if($store_session['agent']['open_self']) {?>
				<li class="divide">|</li>
				<li <?php if ($select_nav == 'goods') echo 'class="active"'; ?>> <a href="<?php dourl('goods:index'); ?>">自营</a> </li>
				<?php } ?>
				<?php /*<li class="divide">|</li>
				<li <?php if (in_array($select_nav, array('order', 'trade'))) echo 'class="active"'; ?> > <a href="<?php echo dourl('order:dashboard'); ?>">订单</a> </li>*/?>
				<?php if (empty($_SESSION['sync_store'])) {
				/*<li class="divide">|</li>
				<li <?php if (in_array($select_nav, array('appmarket', 'reward', 'preferential', 'wxapp'))) echo 'class="active"'; ?> > <a href="<?php echo dourl('appmarket:dashboard'); ?>">营销</a> </li>
				<li class="divide">|</li>
					<li class="js-weixin-notify <?php if($select_nav == 'weixin') echo 'active';?>">
						<a href="<?php echo dourl('weixin:info'); ?>">微信</a>
					</li>*/
				}
				}
				/*<li class="divide">|</li>
				<li> <a href="<?php echo dourl('index:account:yj'); ?>">佣金</a> </li>*/?>
				
				<!--li class="js-weibo-notify">
					<a href="#">微博<sup class="notify-counter" style="visibility: hidden;"></sup></a>

				</li>
				<li class="divide">|</li>
				<li>
					<a href="">应用和营销</a>
				</li>
				<li class="divide">|</li>

				<li>
					<a href="#">数据中心</a>
				</li-->
				<?php /*if($config['bbs_url']){ ?>
							<li class="divide">|</li>
							<li><a href="<?php echo $config['bbs_url'];?>" target="_blank">交流社区</a></li>
						<?php }*/
				?>
				
				<li class="usertips"> <a href="javascript:void(0)" class="mycenter">用户设置</a>
					<div class="downmenu1">
						<ul class="userlinks">
							<li><a href="<?php echo dourl('store:select'); ?>" class="links1">切换店铺</a></li>
							<!--<li><a href="<?php echo dourl('setting:store'); ?>" class="links2">店铺设置</a></li>
							<li><a href="<?php echo dourl('account:company'); ?>" class="links3">公司设置</a></li>-->
							<li><a href="<?php echo dourl('account:info'); ?>" class="links4">帐号设置</a></li>
							<?php if (empty($_SESSION['sync_store'])) { ?>
							<li class="divide"></li>
							<li><a href="<?php echo url('Index:account:logout') ?>">退出登录</a></li>
							<?php } ?>
						</ul>
					</div>
				</li>
							<div class="user_name"> <?php echo $store_session['name']; ?></div>
			</ul>
		</nav>
	</div>
</div>
<script type="text/javascript">
	/*$(function () {
		if ("<?php echo !empty($open_store) ? $open_store : ''; ?>" == '') {
			window.location.href = '<?php echo $store_select; ?>';
		}
	})*/
</script>