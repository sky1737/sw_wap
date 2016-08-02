<style type="text/css">
	.platform-tag {
		display: inline-block;
		vertical-align: middle;
		padding: 3px 7px 3px 7px;
		background-color: #f60;
		color: #fff;
		font-size: 12px;
		line-height: 14px;
		border-radius: 2px;
	}
</style>
<script type="text/javascript">
	var return_money = "<?php echo $return_money?>",
		cost = "<?php echo $cost?>";;
	
</script>
<h1 class="order-title">订单号：<?php echo $order['order_no']; ?></h1>
<ul class="order-process clearfix">
	<?php
	if ($order['status'] == 5) {
		?>
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
			<p class="order-process-state"><?php if ($order['cancel_method'] == 1) { ?>卖家取消<?php }
				else if ($order['cancel_method'] == 2) { ?>买家取消<?php }
				else { ?>自动过期<?php } ?></p>
			<p class="bar"><i class="square">√</i></p>
			<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['cancel_time']); ?></p>
		</li>
		<?php
	}
	else {
		?>
		<li class="active">
			<p class="order-process-state">买家已下单</p>
			<p class="bar"><i class="square">√</i></p>
			<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['add_time']); ?></p>
		</li>
		<?php
		if ($order['shipping_method'] == 'selffetch') {
			if ($order['payment_method'] == 'codpay') {
				?>
				<li <?php if (in_array($order['status'], array(3, 4))) { ?>class="active"<?php } ?>>
					<p class="order-process-state">买家<?php echo $store_session['buyer_selffetch_name']
							? $store_session['buyer_selffetch_name'] : '上门自提' ?></p>
					<p class="bar"><i class="square">2</i></p>
					<?php
					if (in_array($order['status'], array(3, 4))) {
						?>
						<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['complate_time']); ?></p>
						<?php
					}
					?>
				</li>
				<li <?php if ($order['status'] == 4) { ?>class="active"<?php } ?>>
					<p class="order-process-state">交易完成</p>
					<p class="bar"><i class="square">3</i></p>
					<?php if ($order['status'] == 4) { ?>
						<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['complate_time']); ?></p>
					<?php } ?>
				</li>
				<?php
			}
			else {
				?>
				<li <?php if (in_array($order['status'], array(2, 3, 4))) { ?>class="active"<?php } ?>>
					<p class="order-process-state"><?php if (in_array($order['status'],
							array(2, 3, 4))) { ?>买家已付款<?php }
						else { ?>等待买家付款<?php } ?></p>
					<p class="bar"><i class="square">2</i></p>
					<?php if (in_array($order['status'], array(2, 3, 4))) { ?>
						<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['paid_time']); ?></p>
					<?php } ?>
				</li>
				<li <?php if (in_array($order['status'], array(3, 4))) { ?>class="active"<?php } ?>>
					<p class="order-process-state">买家<?php echo $store_session['buyer_selffetch_name']
							? $store_session['buyer_selffetch_name'] : '上门自提' ?></p>
					<p class="bar"><i class="square">3</i></p>
					<?php
					if (in_array($order['status'], array(3, 4))) {
						?>
						<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['complate_time']); ?></p>
						<?php
					}
					?>
				</li>
				<li <?php if ($order['status'] == 4) { ?>class="active"<?php } ?>>
					<p class="order-process-state">交易完成</p>
					<p class="bar"><i class="square">4</i></p>
					<?php if ($order['status'] == 4) { ?>
						<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['complate_time']); ?></p>
					<?php } ?>
				</li>
				<?php
			}
		}
		else {
			?>
			<?php
			if ($order['payment_method'] != 'codpay') {
				?>
				<li <?php if (in_array($order['status'], array(2, 3, 4))) { ?>class="active"<?php } ?>>
					<p class="order-process-state"><?php if (in_array($order['status'],
							array(2, 3, 4))) { ?>买家已付款<?php }
						else { ?>等待买家付款<?php } ?></p>
					<p class="bar"><i class="square">2</i></p>
					<?php if (in_array($order['status'], array(2, 3, 4))) { ?>
						<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['paid_time']); ?></p>
					<?php } ?>
				</li>
				<?php
			}
			else {
				?>
				<li <?php if (in_array($order['status'], array(2, 3, 4))) { ?>class="active"<?php } ?>>
					<p class="order-process-state">等待卖家发货</p>
					<p class="bar"><i class="square">2</i></p>
					<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['add_time']); ?></p>
				</li>
				<?php
			}
			?>
			<li <?php if (in_array($order['status'], array(3, 4))) { ?>class="active"<?php } ?>>
				<p class="order-process-state">卖家已发货</p>
				<p class="bar"><i class="square">3</i></p>
				<?php if (in_array($order['status'], array(3, 4))) { ?>
					<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['sent_time']); ?></p>
				<?php } ?>
			</li>
			<li <?php if ($order['status'] == 4) { ?>class="active"<?php } ?>>
				<p class="order-process-state">交易完成</p>
				<p class="bar"><i class="square">4</i></p>
				<?php if ($order['status'] == 4) { ?>
					<p class="order-process-time"><?php echo date('Y-m-d H:i:s', $order['complate_time']); ?></p>
				<?php } ?>
			</li>

			<?php
		}
	}
	?>
</ul>
<div class="section">
	<h2 class="section-title clearfix">
		订单概况 <?php echo $order['shipping_method'] == 'friend' ? '<span style="color:red;">送朋友订单</span>' : '' ?>
		<div class="js-memo-star-container memo-star-container pull-right">
			<div class="opts">
				<div class="td-cont message-opts">
					<div class="m-opts">
						<a class="js-memo-it" rel="popover" href="javascript:;" data-bak="<?php echo $order['bak']; ?>"
						   data-id="<?php echo $order['order_id']; ?>">备注</a>
						<span>-</span>
						<?php if (empty($order['star'])) { ?>
							<a class="js-stared-it" href="javascript:;">加星</a>
						<?php }
						else { ?>
							<span class="js-stared-it stared"><img
									src="<?php echo TPL_URL; ?>images/star-on.png"> x <?php echo $order['star']; ?></span>
						<?php } ?>
					</div>
					<div id="raty-action-<?php echo $order['order_id']; ?>" class="raty-action"
						 style="display: none; cursor: pointer;">
						<img src="<?php echo TPL_URL; ?>images/cancel-custom-off.png" alt="x" title="去星"
							 data-id="<?php echo $order['order_id']; ?>" class="raty-cancel"/>&nbsp;
						<img src="<?php echo TPL_URL; ?>images/star-off.png" data-id="<?php echo $order['order_id']; ?>"
							 class="star" alt="1" title="一星"/>
						<img src="<?php echo TPL_URL; ?>images/star-off.png" data-id="<?php echo $order['order_id']; ?>"
							 class="star" alt="2" title="二星"/>
						<img src="<?php echo TPL_URL; ?>images/star-off.png" data-id="<?php echo $order['order_id']; ?>"
							 class="star" alt="3" title="三星"/>
						<img src="<?php echo TPL_URL; ?>images/star-off.png" data-id="<?php echo $order['order_id']; ?>"
							 class="star" alt="4" title="四星"/>
						<img src="<?php echo TPL_URL; ?>images/star-off.png" data-id="<?php echo $order['order_id']; ?>"
							 class="star" alt="5" title="五星"/>
					</div>
				</div>
			</div>
		</div>
	</h2>
	<div class="section-detail clearfix">
		<div class="pull-left">
			<table>
				<tbody>
				<tr>
					<td>订单状态：</td>
					<td><?php echo $status[$order['status']]; ?> <?php if ($order['status'] ==
							5
						) { ?><?php if ($order['cancel_method'] == 1) { ?>(卖家取消)<?php }
						else if ($order['cancel_method'] == 2) { ?>(买家取消)<?php }
						else { ?>(自动过期)<?php } ?><?php } ?></td>
				</tr>
				<tr>
					<td>应付金额：</td>
					<td><strong
							class="ui-money-income">￥<?php echo $order['total']; ?></strong>（含运费 <?php echo $order['postage']; ?>
						）
					</td>
				</tr>
				<tr>
					<td>下单用户：</td>
					<td><?php if (empty($order['is_fans'])) { ?>非粉丝<?php }
						else { ?><?php if ($order['buyer']) { ?><?php echo $order['buyer']; ?><?php }
						else { ?>匿名用户<?php } ?><?php } ?></td>
				</tr>
				<tr>
					<td>付款方式：</td>
					<td><?php if (array_key_exists($order['payment_method'], $payment_method)) {
							echo $payment_method[$order['payment_method']];
						}
						else if ($order['payment_method'] == 'codpay') {
							echo '货到付款';
						} ?><?php if ($order['useStorePay']) {
							echo ' <span style="color:#999;">(自有支付)</span>';
						} ?></td>
				</tr>
				<tr>
					<td>物流方式：</td>
					<td>
						<?php
						if ($order['shipping_method'] == 'express' || $order['shipping_method'] == 'friend') {
							?>
							快递配送
							<?php
						}
						else if ($order['shipping_method'] == 'selffetch') {
							echo $store_session['buyer_selffetch_name'] ? $store_session['buyer_selffetch_name']
								: '上门自提';
						}
						?>
					</td>
				</tr>
				<?php $address = !empty($order['address']) ? unserialize($order['address']) : array(); ?>
				<?php if ($order['shipping_method'] == 'express' || $order['shipping_method'] == 'friend') { ?>
					<tr>
						<td>收货信息：</td>
						<td><?php echo $address['province']; ?><?php echo $address['city']; ?><?php echo $address['area']; ?><?php echo $address['address']; ?><?php echo $order['address_user']; ?><?php echo $order['address_tel']; ?></td>
					</tr>
				<?php } ?>
				<?php if ($order['shipping_method'] == 'selffetch') { ?>
					<tr>
						<td>门店：</td>
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
				<tr>
					<td>买家留言：</td>
					<td style="color:red"><?php echo $order['comment']; ?></td>
				</tr>
				</tbody>
			</table>
		</div>
		<div class="pull-right section-sidebar">
			<p>卖家备注：</p>
			<div class="js-memo-text memo-text"><?php echo $order['bak']; ?></div>
			<?php if (in_array($order['status'], array(0, 1))) { ?>
				<div class="order-action-btns">
					<a href="javascript:;" data-id="<?php echo $order['order_id']; ?>"
					   class="ui-btn ui-btn-primary js-cancel-order">关闭订单</a>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<?php if (!empty($packages)) { ?>
	<div class="section section-express">
		<div class="section-title clearfix">
			<h2>物流状态</h2>
			<ul class="js-express-tab">
				<?php foreach ($packages as $key => $package) { ?>
					<li <?php if ($key == 0) { ?>class="active"<?php } ?>
						data-pack-id="<?php echo $package['package_id']; ?>">包裹<?php echo $key + 1; ?></li>
				<?php } ?>
			</ul>
		</div>
		<div class="section-detail">
			<?php if ($packages) { ?>
				<?php foreach ($packages as $key => $package) { ?>
					<div class="js-express-tab-content <?php if ($key > 0) { ?>hide<?php } ?>"
						 data-pack-id="<?php echo $package['page_id']; ?>"
						 data-express-no="<?php echo $package['express_no']; ?>">
						<p><?php echo $package['express_company']; ?>
							运单号：<?php echo $package['express_no']; ?>                                                    </p>
						<!--<div class="js-express-detail express-detail" data-number="1"><p>2015-03-10 16:13:23 已签收,签收人是本人签收</p></div>
						<a class="toggler js-toggle-express hide" href="javascript:;">展开▼</a>-->
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
		<!--<th class="tb-price">商品优惠</th>-->
		<th class="tb-num">数量</th>
		<th class="tb-total">小计（元）</th>
		<th class="tb-state">状态</th>
		<th class="tb-postage">运费（元）</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$start_package = false; //订单已经有商品开始打包
	foreach ($products as $key => $product) {
		if (!$start_package && $product['is_packaged']) {
			$start_package = true;
		}
		$skus = !empty($product['sku_data']) ? unserialize($product['sku_data']) : '';
		$comments = !empty($product['comment']) ? unserialize($product['comment']) : ''; ?>
		<tr data-order-id="<?php echo $order['order_id']; ?>">
			<td class="tb-thumb" <?php if (!empty($comments)) { ?>rowspan="2"<?php } ?>><img
					src="<?php echo $product['image']; ?>" width="60" height="60"/></td>
			<td class="tb-name">
				<a href="<?php echo $config['wap_site_url']; ?>/good.php?id=<?php echo $product['product_id']; ?>"
				   class="new-window" target="_blank"><?php echo $product['name']; ?></a>
				<?php
				if ($product['is_present']) {
					echo '<span style="color:#f60;">赠品</span>';
				}
				if ($product['is_fx']) {
					echo '<span class="platform-tag">分销</span>';
				}
				if ($skus) {
					echo '<p><span class="goods-sku">';
					foreach ($skus as $sku) {
						echo $sku['name'] . ': ' . $sku['value'] . '&nbsp;';
					}
					echo '</span></p>';
				} ?>
			</td>
			<td class="tb-price"><?php echo $product['pro_price']; ?></td>
			<!--<td class="tb-price"></td>-->
			<td class="tb-num"><?php echo $product['pro_num']; ?></td>
			<td class="tb-total"><?php
				echo number_format($product['pro_num'] * $product['pro_price'], 2, '.', ''); ?></td>
			<td class="tb-state" <?php echo empty($comments) ? '' : 'rowspan="2"'; ?>>
				<?php
				if ($product['is_packaged'] || $start_package) {
					if ($product['in_package_status'] == 0) {
						echo '待发货';
					}
					else if ($product['in_package_status'] == 1) {
						echo '已发货';
					}
					else if ($product['in_package_status'] == 2) {
						echo '已到店';
					}
					else if ($product['in_package_status'] == 3) {
						echo '已签收';
					}
				}
				else {
					if ($order['shipping_method'] == 'selffetch') {
						echo '未' .
							($store_session['buyer_selffetch_name'] ? $store_session['buyer_selffetch_name'] : '上门自提');
					}
					else {
						echo '未打包';
					}
				}
				?>
			</td>
			<?php
			if (count($comment_count) > 0 && $key == 0) {
				echo '<td class="tb-postage" rowspan="' . $rows . '">';
				echo $order['postage'];
				if (in_array($order['status'], array(0, 1))) {
					echo '<p class="text-center"><a href="javascript:;" class="js-change-price" data-id="' .
						$order['order_id'] . '">修改价格</a></p>';
				}
				echo '</td>';
			} ?>
		</tr>
		<?php
		if (!empty($comments)) {
			foreach ($comments as $comment) {
				echo '<tr class="msg-row"><td colspan="5">' . $comment['name'] . '：' . $comment['value'] .
					'<br></td></tr>';
			}
		}
	} ?>
	</tbody>
</table>
<div class="clearfix section-final">
	<div class="pull-right text-right">
		<table>
			<tbody>
			<tr>
				<td>商品小计：</td>
				<td>￥<?php echo $order['sub_total']; ?></td>
			</tr>
			<tr>
				<td>实付金额：</td>
				<td>￥<span class="order-postage"><?php echo $order['pay_money']; ?></span></td>
			</tr>
			<tr>
				<td>余额抵现：</td>
				<td>￥<span class="order-postage"><?php echo $order['balance']; ?></span></td>
			</tr>
			<tr>
				<td>积分抵现：</td>
				<td>￥<span class="order-postage"><?php echo round($order['point'] * 1.00 / $config['point_exchange'], 2); ?> </span></td>
			</tr>
			<tr>
				<td>运费：</td>
				<td>￥<span class="order-postage"><?php echo $order['postage']; ?></span></td>
			</tr>
			<?php
			if (!empty($order['float_amount']) && $order['float_amount'] != '0.00') {
				echo '<tr><td>卖家改价：</td>';
				if ($order['float_amount'] > 0) {
					echo '<td>+￥' . $order['float_amount'] . '</td>';
				}
				else {
					echo '<td>-￥' . number_format(abs($order['float_amount']), 2, '.', '') . '</td>';
				}
				echo '</tr>';
			}

			$money = 0;
			if ($order_ward_list) {
				foreach ($order_ward_list as $order_ward) {
					$money += $order_ward['content']['cash'];
					echo '<tr><td>满减：</td><td>' . getRewardStr($order_ward['content']) . '</td></tr>';
				}
			}
			if ($order_coupon) {
				$money += $order_coupon['money'];
				?>
				<tr>
					<td>优惠券:</td>
					<td>
						<span><i><?php echo $order_coupon['name'] ?></i></span>
						<span>优惠金额:<i><?php echo $order_coupon['money'] ?>元</i></span>
					</td>
				</tr>
				<?php
			}
			?>
			<tr>
				<td>应收款：</td>
				<td><span class="ui-money-income">￥<span
							class="order-total"><?php echo $order['total']; ?></span></span></td>
			</tr>
			</tbody>
		</table>
		<?php
		// 暂时注释，店主发货
		// 需要修改为代理发货，暂定为订单增加代理商store_id，看是否需要增加字段还是使用Supplier_id
		//if ($order['is_supplier']) {
		if ($order['status'] == 2 && $order['agent_id'] == $store_session['store_id']) { //商品未发货
			echo '<p><a href="javascript:;" class="btn js-express-goods detail-send-goods btn-primary" data-id="' . $order['order_id'] . '">发 货</a></p>';
		} elseif($order['status'] == 6 && $order['agent_id'] == $store_session['store_id'])
		{
			echo '<p><a href="javascript:;" class="btn js-refund-express detail-send-goods btn-primary"  data-id="' .
				$order['order_id'] . '">确认退款</a></p>';
			if($refundPackage['is_take'] || $order['add_time'] < strtotime("-7 days "))
			{
				echo '<p><a href="javascript:;" class="btn js-refuse-sign detail-send-goods btn-primary"  data-id="' .
					$order['order_id'] . '">拒绝签收</a></p>';
			}
			echo '<p><a href="javascript:;" class="btn js-refuse-sign detail-send-goods btn-primary"  data-id="' .
				$order['order_id'] . '">拒绝签收</a></p>';
		}
		//} ?>
	</div>
</div>