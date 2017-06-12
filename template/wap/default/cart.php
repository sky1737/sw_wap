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
	<title>购物车</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<link rel="stylesheet" href="<?php echo TPL_URL; ?>css/base.css"/>
	<link rel="stylesheet" href="<?php echo TPL_URL; ?>css/trade.css"/>
	<link rel="stylesheet" href="<?php echo TPL_URL; ?>css/cart.css"/>
	<script type="text/javascript">
		
		var noCart = true, 
			storeId =<?php echo $now_store['store_id']?>, 
			//storeUrl = "<?php echo $now_store['url'];?>",//2017/4/21修改为下行
			storeUrl = '<?php echo $config["wap_site_url"]?>',
			orderPrefix = "<?php echo $config['orderid_prefix']; ?>",
			mergePrefix = "<?php echo $config['mergeid_prefix']; ?>";
	</script>
	<script src="<?php echo $config['oss_url']; ?>/static/js/jquery.min.js"></script>
	<script src="<?php echo TPL_URL; ?>js/base.js"></script>
	<script src="<?php echo TPL_URL; ?>js/cart.js"></script>
</head>
<body class="body-fixed-bottom">

<div class="container">
	<div class="content">
		<div class="tabber tabber-n2 tabber-double-11 clearfix">
			<a class="active" href="./cart.php?id=<?php echo $now_store['store_id'] ?>">购物车</a>
			<a href="./trade.php">浏览记录</a>
		</div>
		<div id="cart-container" class="block block-order">
			<?php if (!empty($now_store)) { ?>
				<div class="header js-list-header">
					<span>店铺：<?php echo $now_store['name'] ?></span>
					<a href="javascript:;" class="js-edit-list pull-right c-blue font-size-12 edit-list">编辑</a>
				</div>
				<hr class="margin-0"/>
			<?php } ?>
				<?php
				if(!empty($cartData)) {
					foreach($cartData as $data) {
						echo '<div class="header js-list-header" data-id="'.$data[0]['agent_id'].'"><span>供应商：'.$data[0]['agent_name'].'</span></div>';
						echo '<ul class="list block block-list block-list-cart block-border-none">';
						foreach($data as $value) { ?>
						<li class="block-item block-item-cart relative clearfix item-<?php echo $value['id']; ?>"
							data-id="<?php echo $value['id']; ?>" 
							data-skuid="<?php echo $value['sku_id']; ?>" 
							data-proid="<?php echo $value['product_id']; ?>"
							data-num="<?php echo $value['pro_num']; ?>" 
							data-skunum="<?php echo $value['quantity']; ?>" 
							data-price="<?php echo floatval($value['price']); ?>"
							data-status="<?php echo floatval($value['status']); ?>">
							<div class="check-container">
								<span class="check checked"></span>
							</div>
							<div class="name-card name-card-3col clearfix">
								<a href="./good.php?id=<?php echo $value['product_id']; ?>" class="thumb"><img src="<?php echo $value['image'] ?>"></a>
								<div class="detail">
									<h3 class="js-ellipsis" style="height:32px;overflow:hidden;"><i><?php echo $value['name']; ?></i></h3>
									<p class="ellipsis">
										<?php if ($value['sku_data']) {
										$sku_data_arr = unserialize($value['sku_data']);
										foreach ($sku_data_arr as $v){ ?>
										<span class="c-gray ellipsis"><?php echo $v['name']; ?>：<?php echo $v['value']; ?></span>
									<?php }
									} ?>
									</p>
								</div>
								<div class="right-col price-num">
									<div class="price">￥<span><?php echo floatval($value['price']); ?></span>
									</div>
									<div class="num<?php if (!empty($value['buyer_quota'])) { echo ' buyer-quota'; } ?>" <?php if (!empty($value['buyer_quota'])) { echo 'data-buyer-quota="'.$value['buyer_quota'].'" data-buy-quantity="'.$value['buy_quantity'].'"'; } ?>>×<span class="num-txt"><?php echo $value['pro_num']; ?></span>
									</div>
									<div class="error-box"></div>
								</div>
							</div>
							<div class="delete-btn"><span>删除</span></div>
						</li>
						<?php }
						echo '</ul>';
						echo '<hr class="margin-0"/>';
					}
				}
				else {
					echo '<div class="list block block-list block-list-cart block-border-none"></div>';
				} ?>
			
			<div class="js-bottom-opts bottom-fix" style="padding:0;">
				<div class="bottom-cart clear-fix">
					<div class="select-all"><span class="check"></span>全选</div>
					<div class="total-price">合计：<span class="js-total-price">0</span>元</div>
					<button href="javascript:;" class="js-go-pay btn btn-orange-dark font-size-14" disabled="true">
						结算
					</button>
					<button href="javascript:;" class="js-delete-goods btn font-size-14 btn-red hide"
					        disabled="true">删除
					</button>
				</div>
			</div>
		</div>
	</div>
	<?php include display('footer'); ?>
	<?php echo $shareData; ?>
</div>
</body>
</html>
<?php Analytics($now_store['store_id'], 'ucenter', '会员主页', $now_store['store_id']); ?>