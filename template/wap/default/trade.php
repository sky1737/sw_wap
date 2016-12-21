<?php if (!defined('TWIKER_PATH')) exit('deny access!'); ?>
	<!DOCTYPE html>
	<html class="no-js admin <?php if ($_GET['ps'] <= 320) { ?>responsive-320<?php }
	elseif ($_GET['ps'] >= 540) { ?>responsive-540<?php } ?> <?php if ($_GET['ps'] > 540) { ?> responsive-800<?php } ?>"
	      lang="zh-CN">
	<head>
		<meta charset="utf-8"/>
		<meta name="keywords" content="<?php echo $config['seo_keywords']; ?>"/>
		<meta name="description" content="<?php echo $config['seo_description']; ?>"/>
		<meta name="HandheldFriendly" content="true"/>
		<meta name="MobileOptimized" content="320"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta http-equiv="cleartype" content="on"/>
		<link rel="icon" href="<?php echo $config['site_url']; ?>/favicon.ico"/>
		<title>浏览记录</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
		<link rel="stylesheet" href="<?php echo TPL_URL; ?>css/base.css"/>
		<link rel="stylesheet" href="<?php echo TPL_URL; ?>index_style/css/category_detail.css"/>
		<link rel="stylesheet" href="<?php echo TPL_URL; ?>css/order_list.css"/>
		<script src="<?php echo $config['oss_url']; ?>/static/js/jquery.min.js"></script>
		<script src="<?php echo TPL_URL; ?>js/base.js"></script>
		<script src="<?php echo TPL_URL; ?>js/order.js"></script>
		<script>
			$(function () {
				$("#pages a").click(function () {
					var page = $(this).attr("data-page-num");
					location.href = "trade.php?id=<?php echo $store_id ?>&page=" + page;
				});
			});
		</script>
	</head>
	<body>
	<div class="container">
		<div class="content">
			<div class="tabber tabber-n2 tabber-double-11 clearfix">
				<a href="./cart.php?id=<?php echo $now_store['store_id'] ?>">购物车</a>
				<a class="active" href="./trade.php">浏览记录</a>
			</div>
			<p style="height:10px;">&nbsp;</p>
			<div id="order-list-container">
				<div class="mod_itemgrid">
					<?php if(count($products)) :?>
						<?php foreach ($products as $product):?>
							<div class="hproduct item_long_cover">
								<a href="./good.php?id=<?php echo $product['product_id']?>&platform=1">
									<p class="cover"><img src="<?php echo $product['image']?>"></p>
									<p class="fn"><?php echo $product['name']?></p>
									<p class="prices"><strong><em>¥&nbsp;<?php echo $product['price']?></em></strong>&nbsp;&nbsp;<del><em>¥&nbsp;<?php echo $product['market_price']?></em></del></p>
									<p class="back"><a>购买立返</a> <strong><em> <?php echo $product['point'] == 0 ? '￥'.$product['rebate'] : ($product['point'] < 0 ?  0 : $product['point'].'积分') ?> </em></strong></p>
									<p class="sku"><span class="comment_num  <?php echo $product['sales'] == 0 ? 'hide' : '' ?>">销量 <span> <?php echo $product['sales']?></span></span>&nbsp;<span class="stock hide">无货</span></p>
								</a>
							</div>
						<?php endforeach;?>
						<div class="bottom" id="pages">
							<?php echo $pages ?>
						</div>
					<?php else:?>
						<div class="empty-list list-finished" style="padding-top:60px;display:none;">
							<div>
								<h4>暂时没有任何浏览记录</h4>

								<p class="font-size-12">好东西，手慢无</p>
							</div>
							<div><a href="<?php echo $now_store['url']; ?>" class="tag tag-big tag-orange"
									style="padding:8px 30px;">去逛逛</a></div>
						</div>
					<?php endif;?>
				</div>

			</div>
			<p style="height:10px;">&nbsp;</p>
			<span style="font-weight:bolder;font-size: large">你可能感兴趣的</span>
			<div id="order-list-container">
				<div class="mod_itemgrid">
					<?php if(count($recProducts)) :?>
						<?php foreach ($recProducts as $product):?>
							<div class="hproduct item_long_cover">
								<a href="./good.php?id=<?php echo $product['product_id']?>&platform=1">
									<p class="cover"><img src="<?php echo $product['image']?>"></p>
									<p class="fn"><?php echo $product['name']?></p>
									<p class="prices"><strong><em>¥&nbsp;<?php echo $product['price']?></em></strong>&nbsp;&nbsp;<del><em>¥&nbsp;<?php echo $product['market_price']?></em></del></p>
									<p class="back"><a>购买立返</a> <strong><em> <?php echo $product['point'] == 0 ? '￥'.$product['rebate'] : ($product['point'] < 0 ?  0 : $product['point'].'积分') ?> </em></strong></p>
									<p class="sku"><span class="comment_num  <?php echo $product['sales'] == 0 ? 'hide' : '' ?>">销量 <span> <?php echo $product['sales']?></span></span>&nbsp;<span class="stock hide">无货</span></p>
								</a>
							</div>
						<?php endforeach;?>
					<?php endif;?>
				</div>

			</div>
		</div>
		<?php include display('footer'); ?>
	</div>
	<?php echo $shareData; ?>
	</body>
	</html>
<?php Analytics($now_store['store_id'], 'ucenter', '会员主页', $now_store['store_id']); ?>