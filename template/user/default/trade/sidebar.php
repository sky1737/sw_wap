<?php $select_sidebar=isset($select_sidebar)?$select_sidebar:ACTION_NAME;?>

<aside class="ui-sidebar sidebar">
	<nav>
		<ul>
			<li <?php if(in_array($select_sidebar,array('dashboard','statistics'))) echo 'class="active"';?>> <a href="<?php dourl('order:dashboard');?>">订单概况</a> </li>
			<li <?php if($select_sidebar == 'all') echo 'class="active"';?>> <a href="<?php dourl('order:all');?>">所有订单</a> </li>
			<?if($now_store['buyer_selffetch']) {?>
			<li <?php if($select_sidebar == 'selffetch') echo 'class="active"';?>> <a href="<?php dourl('order:selffetch'); ?>"><?php echo $store_session['buyer_selffetch_name'] ? $store_session['buyer_selffetch_name'] : '到店自提' ?>订单</a> </li>
			<li <?php if($select_sidebar == 'codpay') echo 'class="active"';?>> <a href="<?php dourl('order:codpay'); ?>">货到付款订单</a> </li>
			<?php }?>
			<?php /*<li <?php if($select_sidebar == 'pay_agent') echo 'class="active"';?>><a href="<?php dourl('pay_agent'); ?>">代付的订单</a></li>*/?>
			<li <?php if($select_sidebar == 'star') echo 'class="active"';?>> <a href="<?php dourl('order:star'); ?>">加星订单</a> </li>
		</ul>
		<ul>
			<?php /*<li><a href="<?php dourl('trade:delivery');?>">物流工具</a></li>
			<li><a href="<?php dourl('trade:setting');?>">交易物流通知</a></li>
			<li><a href="<?php dourl('trade:selffetch');?>">买家上门自提</a></li>
			<li><a href="<?php dourl('trade:offline_payment');?>">货到付款设置</a></li>
			<li><a href="<?php dourl('trade:pay_agent');?>">找人代付</a></li>*/?>
			<li><a href="<?php dourl('trade:income'); ?>">收入/提现</a></li>
		</ul>
		<h4>对账信息</h4>
		<ul>
			<li><a href="<?php dourl('order:check');?>">未对帐/已对账</a></li>
		</ul>
	</nav>
</aside>
