<?php if (!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!DOCTYPE html>
<html class="no-js" lang="zh-CN">
<head>
<meta charset="utf-8"/>
<meta name="keywords" content="<?php echo $config['seo_keywords']; ?>"/>
<meta name="description" content="<?php echo $config['seo_description']; ?>"/>
<meta name="HandheldFriendly" content="true"/>
<meta name="MobileOptimized" content="320"/>
<meta name="format-detection" content="telephone=no"/>
<meta http-equiv="cleartype" content="on"/>
<link rel="icon" href="<?php echo $config['site_url']; ?>/favicon.ico"/>
<title><?php echo $pageTitle; ?></title>
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
<link rel="stylesheet" href="<?php echo TPL_URL; ?>css/base.css"/>
<link rel="stylesheet" href="<?php echo TPL_URL; ?>css/order_list.css"/>
<link rel="stylesheet" href="<?php echo $config['site_url']; ?>/template/wap/default/css/gonggong.css"/>
<script src="<?php echo $config['oss_url']; ?>/static/js/jquery.min.js"></script>
<script src="<?php echo TPL_URL; ?>js/base.js"></script>
<script src="<?php echo TPL_URL; ?>js/order.js"></script>
<script src="<?php echo TPL_URL; ?>js/order_paid.js"></script>
<script>
	$(function () {
		$("#pages a").click(function () {
			var page = $(this).attr("data-page-num");
			location.href = "<?php echo $page_url ?>&page=" + page;
		});
	});
</script>
</head>
<body>
<div class="container">
	<div class="content">
		<div id="order-list-container">
			<div class="b-list">
				<?php
				foreach ($order_list as $order) {
				?>
				<li class="block block-order animated">
					<div class="header"> <span class="font-size-12">订单号：<?php echo $order['order_no_txt']; ?></span><span class="font-size-12 pull-right">订单状态：<?php echo $order['status_text'];?></span>
					</div>
					<hr class="margin-0 left-10"/>
					<?php
					$i = 0;
					foreach ($order['product_list'] as $product) {
					?>
					<div class="block block-list block-border-top-none block-border-bottom-none">
						<div class="block-item name-card name-card-3col clearfix"> <a href="<?php echo $product['url']; ?>" class="thumb"> <img src="<?php echo $product['image']; ?>"/> </a>
							<div class="detail">
								<a href="<?php echo $product['url']; ?>">
								<h3 style="margin-bottom:6px;"><?php echo $product['name']; ?></h3>
								</a>
								<?php
								if ($product['sku_data_arr']) {
									foreach ($product['sku_data_arr'] as $v) {
										echo '<p class="c-gray ellipsis">'.$v['name'].' ：'.$v['value'].'</p>';
									}
								}
								?>
							</div>
							<div class="right-col">
								<div class="price"> ¥&nbsp;<span><?php echo $product['pro_price']; ?></span><?php
								echo $product['is_present'] == 1 ? ' <span style="color:#f60;">赠品</span>' : '' ?></div>
								<div class="num">×<span class="num-txt"><?php echo $product['pro_num']; ?></span>
									<div style="font-weight: bold;">
										<?php
										if(4 == $order['status']) { ?>
											<a  class="btn btn-in-order-list btn-orange" style="padding: 2px 12px;" href="./comment_add.php?id=<?php echo $order['product_list'][$i++]['product_id'] ?>&type=PRODUCT">评价</a>
										<?php } ?>
									</div>
								</div>
							</div>

						</div>
					</div>
					<?php
					}
					if ($order['shipping_method'] == 'selffetch') {
					?>
					<hr class="margin-0 left-10"/>
					<div class="bottom">
						<?php
							if ($order['address']['physical_id']) { ?>
						<?php echo $physical_list[$order['address']['physical_id']]['buyer_selffetch_name'] ?> (<?php echo $physical_list[$order['address']['physical_id']]['name'] ?>)
						<div class="opt-btn"> <a class="btn btn-in-order-list" href="./physical_detail.php?id=<?php echo $order['address']['physical_id'] ?>">查看</a> </div>
						<?php
							}
							else if ($order['address']['store_id']) { ?>
						<?php echo $store_contact_list[$order['address']['store_id']]['buyer_selffetch_name'] ?> (<?php echo $store_contact_list[$order['address']['store_id']]['name'] ?>)
						<div class="opt-btn"> <a class="btn btn-in-order-list" href="./physical_detail.php?store_id=<?php echo $order['address']['store_id'] ?>">查看</a> </div>
						<?php
							}
							?>
					</div>
					<?php
					}
					?>
					<hr class="margin-0 left-10"/>
					<div class="bottom">
						<?php if ($order['total']) { ?>
						总价：<span class="c-orange">￥<?php echo $order['total']; ?></span>
						<?php }
						else { ?>
						商品价格：<span class="c-orange">￥<?php echo $order['sub_total']; ?></span>
						<?php } ?>
						<div class="opt-btn">
							<?php if ($order['status'] < 2) { ?>
							<a class="btn btn-orange btn-in-order-list" href="<?php echo $order['url']; ?>">付款</a>
							<a class="btn js-cancel-order pull-right font-size-12 c-blue" href="javascript:;" data-id="<?php echo $order['order_id'] ?>">取消</a>
							<?php } ?>
							<a class="btn btn-in-order-list" href="<?php echo $order['url']; ?>">详情</a>

							<?php if($order['status'] == 2){?>
								<a class="btn btn-in-order-list js-refund-it"  data-id="<?php echo $order['order_no_txt']; ?>" href="<?php echo '#'/*$order['refund_url']*/; ?>">退款</a>
							<?php }elseif((2 < $order['status'] && $order['status'] <= 4)){?>
								<?php if($order['sent_time'] < strtotime("-15 days")){?>
									<a class="btn btn-in-order-list js-after-sales"  data-id="<?php echo $store_contact_list[$order['store_id']]['service_tel']; ?>" href="<?php echo '#'/*$order['refund_url']*/; ?>">售后</a>
								<?php } else{?>
									<a class="btn btn-in-order-list js-refund-it"  data-id="<?php echo $order['order_no_txt']; ?>" href="<?php echo '#'/*$order['refund_url']*/; ?>">退款</a>
								<?php }?>
							<?php }?>

<!--							--><?php //if( $order['add_time'] > strtotime("-7 days ")){ ?>
<!--								--><?php //if((1 < $order['status'] && $order['status'] <= 4)){?>
<!--									<a class="btn btn-in-order-list js-refund-it"  data-id="--><?php //echo $order['order_no_txt']; ?><!--" href="--><?php //echo '#'/*$order['refund_url']*/; ?><!--">退款</a>-->
<!--								--><?php //}?>
<!--							--><?php //} elseif($order['add_time'] > strtotime("-15 days")){?>
<!--								--><?php //if((1 < $order['status'] && $order['status'] < 4)){?>
<!--									<a class="btn btn-in-order-list js-refund-it"  data-id="--><?php //echo $order['order_no_txt']; ?><!--" href="--><?php //echo '#'/*$order['refund_url']*/; ?><!--">退款</a>-->
<!--								--><?php //}elseif($order['status'] ==4){?>
<!--									<a class="btn btn-in-order-list js-after-sales"  data-id="--><?php //echo $store_contact_list[$order['store_id']]['service_tel']; ?><!--" href="--><?php //echo '#'/*$order['refund_url']*/; ?><!--">售后</a>-->
<!--								--><?php //}?>
<!--							--><?php //}?>
							<?php if($uid == $order['uid'] && $order['status'] == 3){?>
								<a href="javascript:;" data-id="<?php echo $order['order_id']; ?>" class="btn btn-xxsmall btn-pink js-order_confirm">确认收货</a>
							<?php }?>
						</div>
					</div>
				</li>
				<?php
			}
			?>
				<div class="bottom" id="pages"> <?php echo $pages ?> </div>
			</div>
			<div class="empty-list list-finished" style="padding-top:60px;display:none;">
				<div>
					<h4>居然还没有订单</h4>
					<p class="font-size-12">好东西，手慢无</p>
				</div>
				<div><a href="./index.php?ywsydy=true" class="tag tag-big tag-orange" style="padding:8px 30px;">去逛逛</a></div>
			</div>
		</div>
	</div>
	<?php
$noFooterLinks = true;
$noFooterCopy = true;
include display('footer'); ?>
</div>
<?php
include display('public_menu');
echo $shareData;
?>
</body>
</html>
<?php Analytics($now_store['store_id'], 'ucenter', '会员主页', $now_store['store_id']); ?>