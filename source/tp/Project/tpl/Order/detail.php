<include file="Public:header" />
<style type="text/css">
	body,
	table,
	td,
	th { font-family: "microsoft yahei", Arial; color: #434343; background-color: white; font-size: 12px !important; }
	em,
	h1,
	h2,
	h3,
	h4,
	input,
	li,
	ol,
	p,
	span,
	textarea,
	ul { margin: 0; padding: 0; font-weight: 400; list-style: none; font-style: normal; }
	table { border-collapse: collapse; border-spacing: 0; }
	.table { width: 100%; font-size: 12px; text-align: left; margin-bottom: 0; }
	.table th { font-weight: bold; padding: 0; }
	.order-goods th,
	.order-goods td { -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; }
	.table thead th { vertical-align: bottom; }
	.table th,
	.table td { padding: 8px; line-height: 20px; text-align: left; vertical-align: top; border-top: 1px solid #dddddd; }
	.order-goods tr > th,
	.order-goods tr > td { padding: 5px 0 5px 5px; word-break: break-all; }
	.table thead tr > th { color: #000; background-color: #f5f5f5; border-bottom: 1px solid #999; }
	.order-goods thead tr > th { height: 20px; line-height: 20px; border-bottom: 1px solid #E4E4E4; }
	.order-goods .tb-thumb { width: 70px; text-align: center; padding: 5px; }
	.table caption + thead tr:first-child th,
	.table caption + thead tr:first-child td,
	.table colgroup + thead tr:first-child th,
	.table colgroup + thead tr:first-child td,
	.table thead:first-child tr:first-child th,
	.table thead:first-child tr:first-child td { border-top: 0; }
	.order-goods { table-layout: fixed; border: 1px solid #E4E4E4; margin-bottom: 10px; }
	.order-title { font-size: 16px; color: #FF6600; height: 40px; line-height: 40px; background-color: #F2F2F2; padding: 0 10px; margin: 10px 0; }
	.clearfix:before,
	.clearfix:after { display: table; line-height: 0; content: ""; }
	.order-process li { float: left; width: 23%; text-align: center; overflow: hidden; }
	.order-process .active .order-process-state { color: #80CCFF; }
	p { margin: 0 0 10px; }
	.order-process .bar { position: relative; height: 20px; }
	.order-process .square { display: inline-block; width: 20px; height: 20px; border-radius: 10px; background-color: #E6E6E6; color: #FFF; font-style: normal; position: absolute; left: 50%; z-index: 2; top: 50%; margin: -10px 0 0 -10px; }
	.order-process .bar:after { content: " "; display: block; width: 100%; height: 4px; background-color: #E6E6E6; position: absolute; top: 50%; margin-top: -2px; z-index: 1; }
	.order-process .active .square,
	.order-process .active .bar:after { background-color: #80CCFF; }
	.order-process li:first-child .bar:after { margin-left: 94px; }
	.order-process .order-process-time { color: #CCC; }
	.section { border: 1px solid #E4E4E4; margin: 0 0 10px; }
	.section-title { font-size: 14px; border-bottom: 1px solid #E4E4E4; margin: 0; padding: 10px; line-height: 1em; background-color: #F2F2F2; height: 14px; }
	.clearfix:after { clear: both; }
	.section-detail { margin: 10px; position: relative; display: -webkit-box; display: -webkit-flex; display: -moz-box; display: -ms-flexbox; display: flex; }
	.section-detail div.pull-left { width: 75%; padding-right: 20px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; border-right: 1px solid #E4E4E4; }
	.section-detail table { width: 100%; }
	.section-detail td:first-child { width: 70px; }
	caption,
	th,
	td { font-weight: normal; vertical-align: middle; }
	.section-sidebar { position: relative; }
	.pull-right { float: right; }
	.section-detail div.pull-right { width: 25%; padding-left: 20px; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; }
	.section { border: 1px solid #E4E4E4; margin: 0 0 10px; }
	.section-express .section-title { padding: 0 10px; height: auto; }
	.section-title > h2 { font-size: 14px; }
	.section-express .section-title h2,
	.section-express .section-title ul,
	.section-express .section-title li { float: left; height: 34px; line-height: 34px; min-width: 124px; }
	.section-express .section-title h2 { font-weight: bold; }
	.section-express .section-title h2,
	.section-express .section-title ul,
	.section-express .section-title li { float: left; height: 34px; line-height: 34px; min-width: 124px; }
	.section-express .section-title li { text-align: center; border-left: 1px solid #e5e5e5; border-right: 1px solid #e5e5e5; margin-left: -1px; cursor: pointer; }
	.section-express .section-title li.active { background: #fff; }
	.section-express .section-detail { display: block; }
	.order-goods .tb-name { width: 220px; }
	.order-goods .tb-price { width: 80px; }
	.order-goods .tb-num { width: 80px; }
	.order-goods .tb-total { width: 90px; }
	.order-goods .tb-state { width: 80px; }
	.order-goods tr > .tb-coupon,
	.order-goods tr > .tb-postage { width: 108px; text-align: center; padding-left: 0; }
	.order-goods tr > .tb-postage { vertical-align: middle; }
	a { color: #07d; text-decoration: none; }
	a.new-window { color: #00f; }
	.order-goods .msg-row td { border-top: 1px dashed #EEE; }
	.order-goods th,
	.order-goods td { -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; }
	.order-goods tr > th,
	.order-goods tr > td { padding: 5px 0 5px 5px; word-break: break-all; }
	.order-goods .msg-row td { border-top: 1px dashed #EEE; }
	.order-goods tbody > tr { border-bottom: 0 none; }
	.order-goods tbody .tb-state,
	.order-goods tbody .tb-coupon { border-right: 1px solid #E4E4E4; }
	p { margin: 0 0 10px; }
	.order-process li:last-child .bar:after { margin-left: -94px; }
	.hide { display: none; }
</style>
<script type="text/javascript">
	$(function () {
		//切换包裹选项卡
		$('.js-express-tab > li').click(function () {
			var index = $(this).index('.js-express-tab > li');
			$(this).siblings('li').removeClass('active');
			$(this).addClass('active');
			$('.section-detail > .js-express-tab-content').eq(index).siblings('div').addClass('hide')
			$('.section-detail > .js-express-tab-content').eq(index).removeClass('hide');
		});

		//修改订单号
		var inputElm =  $(".js-order-express-no");
		inputElm.blur(function(){
			inputElm.css("background-color","#D6D6FF");
			$.post("<?php echo U('Order/changeexpressno'); ?>", {
				'package_id': inputElm.data('id'),
				'no': inputElm.val()
			}, function (data) {
			});
		});

		$(".js-order-express-company").change(function () {
			var package_id = $(this).closest("div").data("pack-id");
			var code = $(this).val();
			var name = $(this).find("option:selected").text();
			$.post("<?php echo U('Order/changeexpresscom'); ?>", {
				'name': name,
				'code': code,
				'package_id': package_id
			}, function (data) {
			})
		});
	});
</script>
<h1 class="order-title">订单号：{pigcms{$order.order_no}</h1>
<ul class="order-process clearfix">
	<?php
	// 订单状态 0临时订单 1未支付 2未发货 3已发货 4已完成 5已取消 6退款中
	if($order['status'] == 5) { ?>
		<li class="active">
			<p class="order-process-state">买家已下单</p>
			<p class="bar"><i class="square">√</i></p>
			<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['add_time']); ?></p>
		</li>
		<li class="active">
			<p class="order-process-state">&nbsp;</p>
			<p class="bar">&nbsp;</p>
			<p class="order-process-time"></p>
		</li>
		<li class="active">
			<p class="order-process-state">&nbsp;</p>
			<p class="bar">&nbsp;</p>
			<p class="order-process-time"></p>
		</li>
		<li class="active">
			<p class="order-process-state">
				<?php
				if($order['cancel_method'] == 1) {
					echo '卖家取消';
				}
				else if($order['cancel_method'] == 2) {
					echo '买家取消';
				}
				else {
					echo '自动过期';
				} ?>
			</p>
			<p class="bar"><i class="square">√</i></p>
			<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['cancel_time']); ?></p>
		</li>
	<?php }
	else { ?>
		<li class="active">
			<p class="order-process-state">买家已下单</p>
			<p class="bar"><i class="square">√</i></p>
			<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['add_time']); ?></p>
		</li>
		<li <?php if(in_array($order['status'], array(2, 3, 4))) { ?>class="active"<?php } ?>>
			<p class="order-process-state">
				<?php if(in_array($order['status'], array(2, 3, 4))) {
					echo '买家已付款';
				}
				else {
					echo '等待买家付款';
				} ?>
			</p>
			<p class="bar"><i class="square">2</i></p>
			<?php if(in_array($order['status'], array(2, 3, 4))) { ?>
				<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['paid_time']); ?></p>
			<?php } ?>
		</li>
		<li <?php if(in_array($order['status'], array(3, 4))) { ?>class="active"<?php } ?>>
			<p class="order-process-state">卖家已发货</p>
			<p class="bar"><i class="square">3</i></p>
			<?php if(in_array($order['status'], array(3, 4))) { ?>
				<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['sent_time']); ?></p>
			<?php } ?>
		</li>
		<li <?php if($order['status'] == 4) { ?>class="active"<?php } ?>>
			<p class="order-process-state">交易完成</p>
			<p class="bar"><i class="square">4</i></p>
			<?php if($order['status'] == 4) { ?>
				<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['complate_time']); ?></p>
			<?php } ?>
		</li>
	<?php } ?>
</ul>
<div class="section">
	<h2 class="section-title clearfix"> 订单概况
		<!--<div class="js-memo-star-container memo-star-container pull-right">
			<div class="opts">
				<div class="td-cont message-opts">
					<div id="raty-action-14" class="raty-action" style="display: none; cursor: pointer;">
						<img src="./template/user/default/images/cancel-custom-off.png" alt="x" title="去星" data-id="14"
						     class="raty-cancel">&nbsp;
						<img src="./template/user/default/images/star-off.png" data-id="14" class="star" alt="1"
						     title="一星">
						<img src="./template/user/default/images/star-off.png" data-id="14" class="star" alt="2"
						     title="二星">
						<img src="./template/user/default/images/star-off.png" data-id="14" class="star" alt="3"
						     title="三星">
						<img src="./template/user/default/images/star-off.png" data-id="14" class="star" alt="4"
						     title="四星">
						<img src="./template/user/default/images/star-off.png" data-id="14" class="star" alt="5"
						     title="五星"></div>
				</div>
			</div>
		</div>-->
	</h2>
	<div class="section-detail clearfix">
		<div class="pull-left">
			<table>
				<tbody>
				<tr>
					<td>订单状态：</td>
					<td><?php echo $status[$order['status']];
						if($order['status'] == 5) { ?>
							<?php if($order['cancel_method'] == 1) {
								echo '(卖家取消)';
							}
							else if($order['cancel_method'] == 2) {
								echo '(买家取消)';
							}
							else {
								echo '(自动过期)';
							}
						} ?></td>
				</tr>
				<tr>
					<td>应付金额：</td>
					<td><strong
							class="ui-money-income">￥<?php echo $order['total']; ?></strong>（含运费 <?php echo $order['postage']; ?> ）
					</td>
				</tr>
				<tr>
					<td>下单用户：</td>
					<td><?php echo $order['nickname']; ?></td>
				</tr>
				<tr>
					<td>付款方式：</td>
					<td><?php if(array_key_exists($order['payment_method'], $payment_method)) { ?>
							<?php echo $payment_method[$order['payment_method']]; ?>
						<?php } ?></td>
				</tr>
				<tr>
					<td>物流方式：</td>
					<td><?php if($order['shipping_method'] == 'express') { ?>
							快递配送
						<?php }
						else if($order['shipping_method'] == 'selffetch') { ?>
							上门自提
						<?php } ?></td>
				</tr>
				<?php $address = !empty($order['address']) ? unserialize($order['address']) : array(); ?>
				<?php if($order['shipping_method'] == 'express') { ?>
					<tr>
						<td>收货信息：</td>
						<td><?php echo $address['province']; ?><?php echo $address['city']; ?><?php echo $address['area']; ?><?php echo $address['address']; ?><?php echo $address['name']; ?><?php echo $address['tel']; ?></td>
					</tr>
				<?php } ?>
				<?php if($order['shipping_method'] == 'selffetch') { ?>
					<tr>
						<td>自提网点：</td>
						<td><?php echo $address['name']; ?><?php echo $address['province']; ?><?php echo $address['city']; ?><?php echo $address['area']; ?><?php echo $address['address']; ?><?php echo $order['address_tel']; ?></td>
					</tr>
					<tr>
						<td>预约人：</td>
						<td><?php echo $order['address_user']; ?></td>
					</tr>
					<tr>
						<td>联系方式：</td>
						<td><?php echo $order['address_tel']; ?></td>
					</tr>
					<tr>
						<td>预约时间：</td>
						<td><?php echo $address['date']; ?><?php echo $address['time']; ?></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="pull-right section-sidebar">
			<p>卖家备注：</p>
			<div class="js-memo-text memo-text">{pigcms{$order.bak}</div>
		</div>
	</div>
</div>
<?php

//var_dump($packages);exit;

if(!empty($packages)) { ?>
	<div class="section section-express">
		<div class="section-title clearfix">
			<h2>物流状态</h2>
			<ul class="js-express-tab">
				<?php foreach ($packages as $key => $package) { ?>
					<li <?php if($key == 0) { ?>class="active"<?php } ?>
					    data-pack-id="<?php echo $package['package_id']; ?>">包裹<?php echo $key + 1; ?></li>
				<?php } ?>
			</ul>
		</div>
		<div class="section-detail">
			<?php if($packages) { ?>
				<?php foreach ($packages as $key => $package) { ?>
					<div class="js-express-tab-content <?php if($key > 0) { ?>hide<?php } ?>"
					     data-pack-id="<?php echo $package['package_id']; ?>"
					     data-express-no="<?php echo $package['express_no']; ?>">
						<p>
							<select class="js-order-express-company"">
								<?php
								foreach ($express as $e){
									echo "<option value=\"{$e['code']}\"" .($e['code'] == $package['express_code'] ? 'selected' : '') ."  >{$e['name']}</option>";
								}
								?>
							</select>
							 运单号：
							<input class="js-order-express-no" type="text" data-id="<?php echo $package['package_id'];?>" name="express_no" value="<?php echo $package['express_no']; ?>"/>
						</p>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
<?php } ?>
<table class="table order-goods">
	<thead>
	<tr>
		<th class="tb-thumb"></th>
		<th class="tb-name">商品名称</th>
		<th class="tb-price">单价（元）</th>
		<th class="tb-num">数量</th>
		<th class="tb-total">小计（元）</th>
		<th class="tb-state">状态</th>
		<th class="tb-postage">运费（元）</th>
	</tr>
	</thead>
	<tbody>
	<?php $start_package = false; //订单已经有商品开始打包?>
	<?php foreach ($products as $key => $product) { ?>
		<?php if(!$start_package && $product['is_packaged']) {
			$start_package = true;
		} ?>
		<?php $skus = !empty($product['sku_data']) ? unserialize($product['sku_data']) : ''; ?>
		<?php $comments = !empty($product['comment']) ? unserialize($product['comment']) : ''; ?>
		<tr data-order-id="<?php echo $order['order_id']; ?>">
			<td class="tb-thumb" <?php if(!empty($comments)) { ?>rowspan="2"<?php } ?>><img
					src="<?php echo $product['image']; ?>" width="60" height="60"></td>
			<td class="tb-name">
				<a href="<?php echo $config['wap_site_url']; ?>/good.php?id=<?php echo $product['product_id']; ?>"
				   class="new-window" target="_blank"><?php echo $product['name']; ?></a>
				<?php if($skus) { ?>
					<p> <span
							class="goods-sku">
					<?php foreach ($skus as $sku) { ?>
						<?php echo $sku['name']; ?>: <?php echo $sku['value']; ?>&nbsp;
					<?php } ?>
					</span></p>
				<?php } ?></td>
			<td class="tb-price"><?php echo $product['pro_price']; ?></td>
			<td class="tb-num"><?php echo $product['pro_num']; ?></td>
			<td class="tb-total"><?php echo number_format($product['pro_num'] * $product['pro_price'], 2, '.',
					''); ?></td>
			<td class="tb-state"
			    <?php if(!empty($comments)) { ?>rowspan="2"<?php } ?>><?php if($product['is_packaged'] ||
					$start_package
				) { ?>
					<?php if($product['in_package_status'] == 0) { ?>
						待发货
					<?php }
					else if($product['in_package_status'] == 1) { ?>
						已发货
					<?php }
					else if($product['in_package_status'] == 2) { ?>
						已到店
					<?php }
					else if($product['in_package_status'] == 3) { ?>
						已签收
					<?php } ?>
				<?php } ?></td>
			<?php if(count($comment_count) > 0 && $key == 0) { ?>
				<td class="tb-postage" rowspan="<?php echo $rows; ?>"><?php echo $order['postage']; ?></td>
			<?php } ?>
		</tr>
		<?php if(!empty($comments)) { ?>
			<?php foreach ($comments as $comment) { ?>
				<tr class="msg-row">
					<td colspan="5"><?php echo $comment['name']; ?>：<?php echo $comment['value']; ?><br></td>
				</tr>
			<?php } ?>
		<?php } ?>
	<?php } ?>
	</tbody>
</table>
<include file="Public:footer" />