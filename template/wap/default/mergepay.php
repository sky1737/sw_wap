<?php
if(!defined('TWIKER_PATH'))
	exit('deny access!'); ?>
	<!DOCTYPE html>
	<html class="no-js" lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<meta name="keywords" content="<?php echo $config['seo_keywords']; ?>" />
		<meta name="description" content="<?php echo $config['seo_description']; ?>" />
		<meta name="HandheldFriendly" content="true" />
		<meta name="MobileOptimized" content="320" />
		<meta name="format-detection" content="telephone=no" />
		<meta http-equiv="cleartype" content="on" />
		<link rel="icon" href="<?php echo $config['site_url']; ?>/favicon.ico" />
		<title>待付款的订单</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<link rel="stylesheet" href="<?php echo TPL_URL; ?>css/base.css" />
		<link rel="stylesheet" href="<?php echo TPL_URL; ?>css/trade.css" />
		<link rel="stylesheet" href="<?php echo TPL_URL; ?>css/offline_shop.css">
		<script src="<?php echo $config['oss_url']; ?>/static/js/jquery.min.js"></script>
		<script src="<?php echo $config['oss_url']; ?>/static/js/area/area.min.js"></script>
		<script src="<?php echo TPL_URL; ?>js/base.js"></script>
		<script type="text/javascript">
			var noCart = true,
				orderPrefix = '<?php echo $config['orderid_prefix']; ?>',
				orderNo = '<?php echo $config['mergeid_prefix'] . $nowMerge['order_no']; ?>',
				pay_url = 'saveorder.php?action=mergepay';
		</script>
		<script src="<?php echo TPL_URL; ?>js/pay.js"></script>
		<script src="http://api.map.baidu.com/api?v=1.2" type="text/javascript"></script>
		<script type="text/javascript">
			var postage = '<?php echo $nowMerge['postage'] ?>';
			var is_logistics = true; //<?php //echo ($now_store['open_logistics'] || $is_all_supplierproduct)?'true':'false' ?>;
			//var is_selffetch = <?php echo ($now_store['buyer_selffetch'] && $is_all_selfproduct) ? 'true' : 'false' ?>
		</script>
	</head>
	<body>
	<div class="container js-page-content wap-page-order">
		<div class="content confirm-container">
			<div class="app app-order">
				<div class="app-inner inner-order" id="js-page-content">
					<!-- 通知 -->
					<!-- 商品列表 -->
					<div class="block block-order block-border-top-none">
						<div class="header">
							<span>店铺：<?php echo $now_store['name']; ?></span><a href="./?twid=<?php echo $now_store['store_id'] ?>" style="float:right;color:#aaa;">进入店铺</a>
						</div>
						<hr class="margin-0 left-10" />
						<div class="block block-list block-border-top-none block-border-bottom-none">
							<?php /*<div class="block-item name-card name-card-3col clearfix">
								<div class="detail" style="margin-left:0px;"><a href="javascript:void(0)">
										<h3>订单总价</h3>
									</a></div>
								<div class="right-col">
									<div class="price">￥<span><?php echo $nowMerge['total'] ?></span></div>
									<div class="num">×<span class="num-txt">1</span></div>
								</div>
							</div>*/ ?>
							<?php
							foreach ($nowMerge['orders'] as $value) { ?>
								<div class="block-item name-card name-card-3col clearfix">
									<div class="detail" style="margin-left:0px;"><a href="javascript:void(0)">
											<h3>由
												<b style="color: #f60;"><?php echo $value['agent']['name']; ?></b> 提供服务
											</h3>
										</a>
									</div>
								</div>
								<?php foreach ($value['products'] as $item) { ?>
									<div class="block-item name-card name-card-3col clearfix js-product-detail">
										<a href="javascript:;" class="thumb">
											<img class="js-view-image" src="<?php echo getAttachmentUrl($item['image']); ?>" alt="<?php echo
											$item['name']; ?>" />
										</a>
										<div class="detail">
											<a href="./good.php?id=<?php echo $item['product_id']; ?>">
												<h3><?php echo $item['name']; ?></h3>
											</a>
											<?php
											//											if($item['is_present']) {
											//												echo '<span style="color:#f60">赠品</span>';
											//											}
											?>
											<?php
											if($item['sku_data']) {
												foreach (unserialize($item['sku_data']) as $v) { ?>
													<p class="c-gray ellipsis"><?php echo $v['name']; ?>：<?php echo $v['value']; ?></p>
													<?php
												}
											}
											?>
										</div>
										<div class="right-col">
											<div class="price">￥<span><?php echo number_format($item['pro_num'] *
														$item['pro_price'],
														2); ?></span></div>
											<div class="num">×<span class="num-txt"><?php echo $item['pro_num']; ?></span>
											</div>
											<?php if($item['comment_arr']) { ?>
												<a class="link pull-right message js-show-message"
												   data-comment='<?php echo json_encode($item['comment_arr']) ?>'
												   href="javascript:;">查看留言</a>
											<?php } ?>
										</div>
									</div>
								<?php } ?>
								<div class="bottom" style="line-height: 1em;">
									总价<span class="pull-right">￥<span class="c-orange"><?php echo $value['sub_total'];
											?></span> + ￥<span class="c-orange"><?php echo
											$value['postage']; ?></span> = ￥<span class="c-orange"><?php echo
											$value['total']; ?></span></span></div>
								<?php
								if($value['status'] == 0) { ?>
									<div class="order-message clearfix" id="js-order-message">
										<textarea class="js-msg-container font-size-12" data-id="<?php echo
										$item['order_id']; ?>"
										          placeholder="给卖家留言..
										."></textarea>
									</div>
								<?php }
								else { ?>
									<div class="order-message clearfix"><span class="font-size-12">买家留言：</span>
										<p class="message-content font-size-12"><?php echo $value['comment']
												? $value['comment']
												: '无' ?></p>
									</div>
								<?php } ?>
								<div>&nbsp; </div>
							<?php } ?>
						</div>
						<div>&nbsp;</div>
						<div class="bottom">总价<span class="c-orange pull-right">￥<?php echo $nowMerge['total'] ?></span>
						</div>
					</div>
					<!-- 物流 -->
					<div class="block express" id="js-logistics-container">
						<div class="block-item logistics">
							<h4 class="block-item-title">配送方式</h4>
							<div class="pull-left js-logistics-select">
								<button data-type="express"
								        class="tag tag-big<?php echo ($nowMerge['shipping_method'] == 'express' ||
									        empty($nowMerge['shipping_method'])) ? ' tag-orange'
									        : ''; ?>" style="margin-top:-3px;">快递配送
								</button>
								<?php
								/*if($now_store['open_logistics'] || $is_all_supplierproduct) {
								}
								if($is_all_selfproduct && $now_store['buyer_selffetch'] && $selffetch_list) { ?>
									<button data-type="selffetch"
											class="tag tag-big js-tabber-self-fetch <?php if($nowMerge['shipping_method'] ==
												'selffetch'
											) {
												?>tag-orange<?php } ?>"
											style="margin-top:-3px;"><?php echo $now_store['buyer_selffetch_name']
											? $now_store['buyer_selffetch_name'] : '到店自提' ?></button>
									<?php
								}
								if($now_store['open_friend'] &&
									($now_store['open_logistics'] || $is_all_supplierproduct)
								) { ?>
									<button data-type="friend"
											class="tag tag-big js-tabber-friend <?php if($nowMerge['shipping_method'] ==
												'friend'
											) {
												?>tag-orange<?php } ?>" style="margin-top:-3px;">送朋友 </button>
									<?php
								}*/
								?>
							</div>
						</div>
						<?php
						if($nowMerge['status'] < 1) { ?>
							<div class="js-logistics-content logistics-content js-express">
								<?php
								if($userAddress && ($now_store['open_logistics'] || $is_all_supplierproduct)) {?>
									<?php
									foreach ($userAddress as $uAddr){?>
										<div class="js-order-address-item express-panel" style="padding-left:0;">
											<?php if($nowOrder['status'] == 0) { ?>
												<div class="opt-wrapper"><a href="javascript:;" class="btn btn-xxsmall btn-grayeee butn-edit-address js-edit-address">修改</a></div>
											<?php } ?>
											<ul>
												<li>
													<input type="radio" name="address_id" id="address_id" value="<?php echo $uAddr['address_id'];?>" <?php if($uAddr['default']){
														echo 'checked';
													};
													?>/>
													<span><?php echo $uAddr['name']; ?></span>, <?php echo $uAddr['tel']; ?>
												</li>
												<li><?php echo $uAddr['province_txt']; ?><?php echo $uAddr['city_txt']; ?><?php echo $uAddr['area_txt']; ?></li>
												<li><?php echo $uAddr['address']; ?></li>
											</ul>
										</div>
									<?php } ?>

<!--									<div>-->
<!--										<div class="block block-form block-border-top-none block-border-bottom-none">-->
<!--											<div class="js-order-address express-panel" style="padding-left:0;">-->
<!--												--><?php //if($nowMerge['status'] == 0) { ?>
<!--													<div class="opt-wrapper">-->
<!--														<a href="javascript:;" class="btn btn-xxsmall btn-grayeee butn-edit-address js-edit-address">修改</a>-->
<!--													</div>-->
<!--												--><?php //} ?>
<!--												<ul>-->
<!--													<li>-->
<!--														<span>--><?php //echo $userAddress['name']; ?><!--</span>, --><?php //echo $userAddress['tel']; ?>
<!--													</li>-->
<!--													<li>--><?php //echo $userAddress['province_txt']; ?><!----><?php //echo $userAddress['city_txt']; ?><!----><?php //echo $userAddress['area_txt']; ?><!--</li>-->
<!--													<li>--><?php //echo $userAddress['address']; ?><!--</li>-->
<!--												</ul>-->
<!--											</div>-->
<!--										</div>-->
<!--										<div class="js-logistics-tips logistics-tips font-size-12 c-orange hide">很抱歉，该地区暂不支持配送。</div>-->
<!--									</div>-->
									<?php
								}
								/*else { */?>
									<div class="js-order-address express-panel">
										<?php
										if($now_store['open_logistics'] || $is_all_supplierproduct) { ?>
											<div class="js-edit-address address-tip"><span>添加收货地址</span></div>
											<?php
										}
										else if($is_all_selfproduct && $now_store['buyer_selffetch'] &&
											$selffetch_list
										) { ?>
											<div class="js-selffetch-address address-tip"><span>选择门店</span></div>
											<?php
										} ?>
									</div>
								<?php/* } */?>
							</div>
							<div class="js-logistics-content logistics-content js-self-fetch hide"></div>
<!--							<input type="hidden" name="address_id" id="address_id" value="--><?php //echo intval($userAddress['address_id']); ?><!--" />-->
							<input type="hidden" name="selffetch_id" id="selffetch_id" value="0" />
						<?php }
						else {
							/*if($nowMerge['shipping_method'] == 'selffetch') { ?>
								<div class="block block-form block-border-top-none block-border-bottom-none">
									<div class="js-order-address express-panel" style="padding-left:0;">
										<ul>
											<li> 门店：<span><?php echo $nowMerge['address']['name']; ?></span>, <?php echo $nowMerge['address']['tel']; ?> , 营业时间：<?php echo $nowMerge['address']['business_hours'] ?>
											</li>
											<li><?php echo $nowMerge['address']['province']; ?><?php echo $nowMerge['address']['city']; ?><?php echo $nowMerge['address']['area']; ?> </li>
											<li><?php echo $nowMerge['address']['address']; ?></li>
										</ul>
									</div>
									<div class="clearfix block-item self-fetch-info-show">
										<label>预约人</label>
										<input class="txt txt-black ellipsis js-name" placeholder="到店人姓名" readonly value="<?php echo $nowMerge['address_user']; ?>" />
									</div>
									<div class="clearfix block-item self-fetch-info-show">
										<label>联系方式</label>
										<input type="text" class="txt txt-black ellipsis js-phone" placeholder="用于短信接收和便于卖家联系" readonly value="<?php echo $nowMerge['address_tel']; ?>" />
									</div>
									<div class="clearfix block-item self-fetch-info-show">
										<label class="pull-left">预约时间</label>
										<input style="width:125px" class="txt txt-black js-time pull-left date-time" type="date" placeholder="日期" value="<?php echo $nowMerge['address']['date']; ?>" readonly />
										<input style="width:70px" class="txt txt-black js-time pull-left date-time" type="time" placeholder="时间" value="<?php echo $nowMerge['address']['time']; ?>" readonly />
									</div>
								</div>
								<?php
							}
							else {

							} */ ?>
							<div class="block block-form block-border-top-none block-border-bottom-none">
								<div class="js-order-address express-panel" style="padding-left:0;">
									<ul>
										<li>
											<span><?php echo $nowMerge['address_user']; ?></span>, <?php echo $nowMerge['address_tel']; ?>
										</li>
										<li><?php echo $nowMerge['address']['province']; ?><?php echo $nowMerge['address']['city']; ?><?php echo $nowMerge['address']['area']; ?></li>
										<li><?php echo $nowMerge['address']['address']; ?></li>
									</ul>
								</div>
							</div>
							<div class="js-logistics-tips logistics-tips font-size-12 c-orange hide">很抱歉，该地区暂不支持配送。</div>
							<?php

						} ?>
					</div>
					<?php
					/*// 满减送
					//var_dump($reward_list);
					if(!empty($reward_list) && !empty($reward_list['product_price_list'])) { ?>
						<div class="block">
							<div class="js-order-total block-item order-total" style="text-align:left;">
								<?php
								$reward_money = 0;
								foreach ($reward_list as $key => $reward) {
									if($key === 'product_price_list') {
										continue;
									}

									if(isset($reward['content'])) {
										$reward = $reward['content'];
									}
									$reward_money += $reward['cash'];
									?>
									<p><span
											style="padding:0px 5px; background:#f60; color:white; border-radius:3px;">满减</span><?php echo getRewardStr($reward) ?>
									</p>
									<?php
								}
								?>
							</div>
						</div>
						<?php
					}
					// 优惠券
					if(!empty($user_coupon_list)) { ?>
						<div class="block js-card-container">
							<h4 class="list-title">使用优惠券：</h4>
							<div class="js-user_coupon block-item order-total" style="text-align:left;">
								<p>
									<input type="radio" name="user_coupon_id" value="0" id="user_coupon_default" />
									<label
										for="user_coupon_default" style="cursor:pointer;">不使用优惠券 <span
											style="display:none;">0</span></label>
								</p>
								<?php
								$user_coupon_money = 0;
								foreach ($user_coupon_list as $key => $user_coupon_tmp) {
									$checked = '';
									if($key == '0') {
										$checked = 'checked="checked"';
										$user_coupon_money = $user_coupon_tmp['face_money'];
									} ?>
									<p>
										<input type="radio" name="user_coupon_id"
											   value="<?php echo $user_coupon_tmp['id'] ?>" <?php echo $checked ?>
											   id="user_coupon_<?php echo $user_coupon_tmp['id'] ?>" />
										<label
											for="user_coupon_<?php echo $user_coupon_tmp['id'] ?>"
											style="cursor:pointer;"><?php echo htmlspecialchars($user_coupon_tmp['cname']) ?>优惠券金额：￥<span><?php echo $user_coupon_tmp['face_money'] ?></span></label>
									</p>
									<?php
								}
								?>
							</div>
						</div>
						<?php
					}
					if(!empty($user_coupon)) {
						$user_coupon_money = $user_coupon['money']; ?>
						<div class="block js-card-container">
							<h4 class="list-title">使用优惠券：</h4>
							<div class="js-user_coupon block-item order-total" style="text-align:left;">
								<p> 优惠券金额：￥<span><?php echo $user_coupon_money ?></span></p>
							</div>
						</div>
						<?php
					}*/
					if($nowMerge['status'] < 1 && ($wap_user['balance'] > 0 || $wap_user['point'] > 0)) { ?>
						<div class="block js-card-container">
							<?php /*<h4 class="list-title">使用账户余额：</h4>*/ ?>
							<div class="js-user_coupon block-item order-total" style="text-align:left;">
								<?php
								if($wap_user['balance'] > 0) { ?>
									<p>账户余额：<span id="max_balance"><?php echo $wap_user['balance']; ?></span>元<br />
										使用余额支付：
										<input type="text" class="txt txt-ye" id="balance" name="balance" value="0" size="10" />
										元 </p>
								<? }
								if($wap_user['point']) { ?>
									<p<?php echo ($wap_user['balance'] && $wap_user['point']) ? '
								style="margin-top:10px;"' : ''; ?>>账户积分：<span id="max_point"><?php echo
											$wap_user['point'];
											?></span><br />
										使用积分抵现：
										<input type="text" class="txt txt-ye" id="point" name="point" value="0" size="10" />
										积分
										<span style="color:#ccc;"><?php echo $config['point_exchange']; ?> 积分抵现 1 元</span>
									</p>
								<? } ?>
							</div>
						</div>
					<? } ?>
					<!-- 支付 -->
					<div class="js-step-topay <?php echo ($nowMerge['total'] == '0.00' && $nowMerge['status'] == 0)
						? 'hide'
						: ''; ?>">
						<div class="block">
							<div class="js-order-total block-item order-total">
								<p> ￥<span id="js-sub_total"><?php
										echo $nowMerge['total']; ?>
							</span> <span id="js-balance-txt"><?php
										if($nowMerge['balance'] > 0)
											echo '<span id="js-balance">' . $nowMerge['balance'] .
												'</span> 账户余额支付'; ?></span> <span id="js-point-txt"><?php
										if($nowMerge['point'] > 0)
											echo '<span id="js-point">' .
												($nowMerge['point'] * 1.00 / $config['point_exchange']) . '</span> ' .
												$nowMerge['point'] . '抵现'; ?></span></p>
								<?php
								if($nowMerge['float_amount'] * 1 != 0) { ?>
									<strong class="js-real-pay c-red js-real-pay-temp">减免：￥<span id="js-float_amount"><?php
											echo number_format(abs($nowMerge['float_amount']), 2, '.', '');
											?></span></strong><br />
								<?php } ?>
								<strong class="js-real-pay c-orange js-real-pay-temp">需付：￥<span id="js-total">
						<?php
						echo $nowMerge['total'] - ($nowMerge['balance'] + ($nowMerge['point'] * 1.00 /
									$config['point_exchange']));
						// $reward_money + $user_coupon_money +
						?>
						</span></strong></div>
						</div>
						<div class="action-container" id="confirm-pay-way-opts">
							<?php
							/*if (!empty($sync_user)) { ?>
								<div style="margin-bottom:10px;">
									<button type="button" data-pay-type="<?php echo $value['type']; ?>"
											class="btn-pay btn btn-block btn-large btn-peerpay btn-green go-pay">去付款
									</button>
								</div>
							<?php
							}
							else {
							}*/
							if($payList) {
								$i = 1;
								foreach ($payList as $value) {
									if($value['type'] == 'offline' && $nowMerge['shipping_method'] == 'friend') {
										continue;
									}
									?>
									<div style="margin-bottom:10px;">
										<button type="button" data-pay-type="<?php echo $value['type']; ?>" class="btn-pay btn btn-block btn-large btn-peerpay <?php echo $i ==
										1 ? 'btn-green' : 'btn-white'; ?>"><?php echo $value['name'] ?></button>
									</div>
									<?php
									$i++;
								}
							}
							else {
								$i = 1;
								foreach ($payMethodList as $value) {
									if($value['type'] == 'offline' && $nowMerge['shipping_method'] == 'friend') {
										continue;
									} ?>
									<div style="margin-bottom:10px;">
										<button type="button" data-pay-type="<?php echo $value['type']; ?>" class="btn-pay btn btn-block btn-large btn-peerpay <?php echo $i ==
										1 ? 'btn-green' : 'btn-white'; ?>"><?php echo $value['name'] ?></button>
									</div>
									<?php
									$i++;
								}
							}
							?>
						</div>
						<div class="action-container hide" id="get-present-btn">
							<div style="margin-bottom: 10px;">
								<button type="button" data-pay-type="couponpay"
								        class="btn-pay btn btn-block btn-large btn-couponpay  btn-green">立即兑换
								</button>
							</div>
						</div>
						<div class="center action-tip js-pay-tip">支付完成后，如需退换货请及时联系卖家</div>
					</div>
				</div>
				<div class="app-inner inner-order peerpay-gift" style="display:none;" id="sku-message-poppage">
					<div class="js-list block block-list"></div>
					<h2>备注信息</h2>
					<ul class="block block-form js-message-container">
					</ul>
					<div class="action-container">
						<button class="btn btn-white btn-block js-cancel">查看订单详情</button>
					</div>
				</div>
			</div>
		</div>
		<div id="js-self-fetch-modal" class="modal order-modal"></div>
		<div class="js-modal modal order-modal">
			<?php
			/*<div class="js-scene-coupon-list scene hide">
				<div class="js-coupon-ui coupon-ui coupon-list">
					<div class="block">
						<div class="coupon-container">
							<div id="coupon--0" class="js-not-use block-item order-coupon order-coupon-item active">
								<label class="label-check">
									<div class="label-check-img"></div>
									<div class="coupon-info"><span>不使用优惠</span></div>
								</label>
							</div>
						</div>
					</div>
					<div class="js-code-container">
						<h4 class="list-title">使用优惠码：</h4>
						<div class="js-coupon-container coupon-container block">
							<div class="js-code-inputer block-item order-coupon order-coupon-item">
								<label class="label-check">
									<div class="label-check-img label-check-img-inputer"></div>
									<div class="coupon-inputer">
										<input class="js-code-txt txt txt-coupon-code" type="text" placeholder="请输入优惠码"
											   autocapitalize="off" maxlength="15" />
										<button class="js-valid-code tag tag-big tag-blue" type="button" disabled="">验证</button>
									</div>
									<div class="js-coupon-info coupon-info"></div>
								</label>
							</div>
							<div class="js-coupon-code-list"></div>
						</div>
					</div>
					<div class="js-card-container" style="display: none;">
						<h4 class="list-title">使用优惠券：</h4>
						<div class="js-coupon-container coupon-container block"></div>
					</div>
				</div>
			</div>*/ ?>
			<div class="js-scene-address-list scene">
				<div class="address-ui address-list">
					<h4 class="list-title text-right"><a class="js-cancel-address-list" href="javascript:;">取消</a></h4>
					<div class="block">
						<div class="js-address-container address-container">
							<div style="min-height: 80px;" class="loading"></div>
						</div>
						<div class="block-item">
							<h4 class="js-add-address add-address">增加收货地址</h4>
						</div>
					</div>
				</div>
			</div>
			<div class="js-scene-address-fm scene"></div>
		</div>
		<div class="js-confirm-use-coupon confirm-use-coupon" style="display:none;">
			<span class="js-total-privilege">总优惠：¥0.00</span>
			<button type="button" class="js-confirm-coupon btn btn-blue btn-xsmall font-size-14">确定</button>
		</div>
		<?php $noFooterLinks = true;
		include display('footer'); ?>
	</div>
	<script type="text/javascript">
		$(function () {
			var balance = parseFloat('<?php echo round($wap_user['balance'], 2); ?>');
			var point = parseInt('<?php echo intval($wap_user['point']); ?>')
			var total = parseFloat('<?php
				echo $nowMerge['total'] * 1 - ($nowMerge['balance'] +
						$nowMerge['point'] * 1.00 / $config['point_exchange']); ?>');
			var rate = parseFloat('<?php echo $config['point_exchange']; ?>');
			$('.txt-ye').blur(function () {
				// 使用余额
				var t = total;
				$('#js-total').text(t);

				var b = $('#balance').val();
				if (b == '' || isNaN(b)) {
					$('#balance').val('0');
					$('#js-balance-txt').text('');
				}
				else {
					b = parseFloat(b).toFixed(2);
					if (b <= 0) {
						$('#balance').val('0');
						$('#js-balance-txt').text('');
					}
					else {
						if (b > balance) {
							b = balance;
						}
						if (t <= b) {
							b = t;
						}
						$('#balance').val(b);
						$('#js-balance-txt').html(' - ￥<span id="js-balance">' + b + '</span> 账号余额');
						t = (t - b).toFixed(2);
						$('#js-total').text(t);
						if (t == 0) {
							$('.btn-pay').data('data-pay-type', 'account');
							$('.btn-pay').text('账户余额支付');
							return false;
						}
					}
				}

				$('#js-total').text(t);
				// 使用积分
				var p = $('#point').val();
				if (p == '' || isNaN(p)) {
					$('#point').val('0');
					$('#js-point-txt').text();
					return false;
				}
				p = parseInt(p);
				if (p <= 0) {
					$('#point').val('0');
					$('#js-point-txt').text();
					return false;
				}
				if (p > point) {
					p = point;
				}
				var tmp = (p / rate).toFixed(2);
				if (t - tmp <= 0) {
					tmp = t;
					p = Math.round(tmp * rate);
				}

				$('#point').val(p);
				$('#js-point-txt').html(' - <span id="js-point">' + p + '</span>分 积分抵现' + tmp + '元');
				t = (t - tmp).toFixed(2);
				$('#js-total').text(t);
				if (t == 0) {
					$('.btn-pay').data('pay-type', 'account');
					$('.btn-pay').text('使用' + (b > 0 ? '余额+' : '') + '积分支付');
				}
			});
		});
	</script>
	</body>
	</html>
<?php Analytics($nowMerge['store_id'], 'pay', '订单支付', $nowMerge['order_id']); ?>