<style type="text/css">
.platform-tag { display: inline-block; vertical-align: middle; padding: 3px 7px 3px 7px; background-color: #f60; color: #fff; font-size: 12px; line-height: 14px; border-radius: 2px; }
</style>
<?php if (!empty($orders)) { ?>
<table class="ui-table-order">
	<thead class="js-list-header-region tableFloatingHeaderOriginal">
		<tr>
			<th class="" colspan="2">商品</th>
			<th class="state-cell-cell">单价/数量</th>
			<th class="aftermarket-cell">售后</th>
			<!--<th class="customer-cell">买家</th>-->
			<th class="time-cell"> <a href="javascript:;" class="orderby orderby_add_time" data-orderby="add_time">下单时间<span class="orderby-arrow desc"></span></a> </th>
			<th class="state-cell">订单状态</th>
			<th class="pay-price-cell"><a href="javascript:;" class="orderby orderby_total" data-orderby="total">总价/实付金额</a></th>
		</tr>
	</thead>
	<?php foreach ($orders as $order) { ?>
	<tbody>
		<tr class="separation-row">
			<td colspan="8"></td>
		</tr>
		<tr class="header-row">
			<td colspan="5"><div> 订单号: <?php echo $order['order_no']; ?>
					<?php 
					if ($order['payment_method'] == 'codpay') {
						echo '<span>支付方式 ：货到付款</span>';
					} ?>
					<div class="help" style="display:inline-block;"> <span class="js-help-notes c-gray" data-class="bottom" style="cursor: help;">
						<?php
						if (array_key_exists($order['payment_method'], $payment_method)) {
							echo $payment_method[$order['payment_method']];
						} ?>
						</span>
						<div class="js-notes-cont hide"> 该订单通过代销服务完成交易，请进入“收入/提现”页面，“微信支付”栏目查看收入或提现 </div>
					</div>
					<?php
					if ($order['type'] == 3) {
						echo '<span class="platform-tag">分销</span> <span class="c-gray"> 分销商：'.$order['seller'].'</span>';
					} ?>
				</div>
				<div class="clearfix">
					<!--<?php
					if (!empty($order['trade_no'])) {
						echo '<div style="margin-top: 4px;margin-right: 20px;" class="pull-left"> 外部订单号: <span class="c-gray">'.$order['trade_no'].'</span> </div>';
					} ?>-->
					<?php if (!empty($order['third_id'])) { ?>
					<div style="margin-top: 4px;" class="pull-left"> 支付流水号: <span class="c-gray"><?php echo $order['third_id']; ?></span> </div>
					<?php } ?>
				</div></td>
			<td colspan="3" class="text-right"><div class="order-opts-container">
					<div class="js-memo-star-container memo-star-container">
						<div class="opts">
							<div class="td-cont message-opts">
								<div class="m-opts"> <a href="<?php dourl('detail', array('id' => $order['order_id'])); ?>" class="js-order-detail new-window" target="_blank">查看详情</a> <span>-</span> <a class="js-memo-it" rel="popover" href="javascript:;" data-bak="<?php echo $order['bak']; ?>" data-id="<?php echo $order['order_id']; ?>">备注</a>
								</div>

							</div>
						</div>
					</div>
				</div></td>
		</tr>
		<?php foreach ($order['products'] as $key => $product) { ?>
		<tr class="content-row">
			<td class="image-cell"><img src="<?php echo $product['image']; ?>" /></td>
			<td class="title-cell"><p class="goods-title"> <a href="<?php echo $config['wap_site_url'];?>/good.php?id=<?php echo $product['product_id'];?>" target="_blank" class="new-window" title="<?php echo $product['name']; ?>"> <?php echo $product['name']; ?> </a> </p>
				<p>
					<?php
					$skus = !empty($product['sku_data']) ? unserialize($product['sku_data']) : '';
					if ($skus) {
						foreach ($skus as $sku) {
							echo '<span class="goods-sku">'.$sku['value'].'</span>';
						}
					}
					if ($product['is_fx']) {
						echo '<span class="platform-tag">分销</span>';
					} ?>
				</p></td>
			<td class="price-cell"><p><?php echo $product['pro_price']; ?></p>
				<p>(<?php echo $product['pro_num']; ?>件)</p></td>
			<?php if (count($order['products']) > 0 && $key == 0) { ?>
			<td class="aftermarket-cell" rowspan="<?php echo count($order['products']); ?>"></td>
			<!--<td class="customer-cell" rowspan="<?php echo count($order['products']); ?>"><?php
			if (empty($order['is_fans'])) {
				echo '<p>非粉丝</p>';
			} else { 
				echo '<p>'.$order['buyer'].'</p>';
			}
			if (!empty($order['address_user'])) {
				echo '<p class="user-name">'.$order['address_user'].'</p>';
				echo $order['address_tel'];
			} ?></td>-->
			<td class="time-cell" rowspan="<?php echo count($order['products']); ?>"><div class="td-cont"> <?php echo date('Y-m-d H:i:s', $order['add_time']); ?> </div></td>
			<td class="state-cell" rowspan="<?php echo count($order['products']); ?>"><div class="td-cont">
					<p class="js-order-state">
						<?php 
						if ($order['shipping_method'] == 'selffetch' && $order['status'] <= 2) {
							$address = unserialize($order['address']);
							echo '门店：' . $address['name'];
						} else {
							echo $order_status[$order['status']];
						} ?>
					</p>
					<?php
					if (in_array($order['status'], array(0, 1))) {
						echo '<p> <a href="javascript:;" data-id="'.$order['order_id'].'" class="btn btn-small js-cancel-order">取消订单</a> </p>';
					}
					if ($order['is_supplier']) {
						if ($order['status'] == 2) {
							if ($order['shipping_method'] == 'selffetch') {
								echo '<p> <a href="javascript:;" data-id="'.$order['order_id'].'" class="btn btn-small js-complate-order">交易完成</a> </p>';
							} else {
								echo '<p> <a href="javascript:;" class="btn btn-small js-express-goods js-express-goods-'.$order['order_id'].'" data-id="'.$order['order_id'].'">发&nbsp;&nbsp;货</a> </p>';
							}
						}
						if ($order['status'] == 3) {
							echo '<p> <a href="javascript:;" data-id="'.$order['order_id'].'" class="btn btn-small js-complate-order">交易完成</a> </p>';
						}
					}
					if ($order['shipping_method'] == 'friend') {
						echo '<span style="color:red">送朋友订单</span>';
					}
					?>
				</div></td>
			<td class="pay-price-cell" rowspan="<?php echo count($order['products']); ?>">
					<div class="td-cont" style="text-align: left; margin-left:10px; height: 75px;">
						<div style=" float:left;"><a class="c-gray">总价：</a></div>
						<div class="c-gray" style="float:left; ">
							<p><span class="order-total">￥<?php echo $order['total']; ?></span>
							<p>￥<?php echo $order['sub_total']; ?></p>
							<p>￥<?php echo $order['postage']; ?> <span class="c-gray">运费</span></p>
						</div>
						<div style="float:left;"><a class="c-gray">实付：</a></div>
						<div class="c-gray" style="float:left;">
							<p class="order-total">￥<?php echo $order['pay_money']; ?></span> </p>
							<!--<p>￥<?php echo $order['total']; ?></p>-->
							<p>￥<?php echo $order['balance']; ?> <span class="c-gray">余额抵现</span></p>
							<p>￥<?php echo round($order['point'] * 1.00 / $config['point_exchange'], 2); ?> <span class="c-gray"><?php //echo $order['point']; ?>积分抵现</span></p>
						</div>
					</div>
					</div>
					<div class="hyzx-zt"><span>
									<?php
										if($order['shipping_method'] == 'selffetch' && $order['status'] <= 2) {
											$address = unserialize($order['address']);
											echo '门店：' . $address['name'];
										}
										else {
											echo $order_status[$order['status']];
										} ?>
								</span></div>
				</td>
			<?php } ?>
		</tr>
		<?php }
		if ($order['bak'] != '') {
			echo '<tr class="remark-row"><td colspan="8">卖家备注：'.$order['bak'].'</td></tr>';
		} ?>
	</tbody>
	<?php } ?>
</table>
<?php } ?>
