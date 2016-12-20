<div class="js-list-filter-region clearfix">
	<div>
		<div class="ui-nav" style="margin-top:0;">
			<ul>
				<li class="active"><a href="javascript:;" class="all" data="*">全部 (不包含临时单)</a></li>
				<li><a href="javascript:;" class="wait-paid status-1" data="1">待付款</a></li>
				<li><a href="javascript:;" class="wait-send status-2" data="2">待发货</a></li>
				<li><a href="javascript:;" class="shipped status-3" data="3">待收货</a></li>
				<!--<li><a href="javascript:;" class="success status-4" data="4">已完成</a></li>
				<li><a href="javascript:;" class="canceled status-5" data="5">已关闭</a></li>-->
				<li><a href="javascript:;" class="refunding status-6" data="6">退款中</a></li>
				<li><a href="javascript:" class="temp status-0" data="0">临时单</a></li>
			</ul>
		</div>
	</div>
</div>
<?php include display('order_content_list'); ?>
