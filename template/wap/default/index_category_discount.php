<?php if (!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8"/>
<meta name="keywords" content="<?php echo $config['seo_keywords']; ?>"/>
<meta name="description" content="<?php echo $config['seo_description']; ?>"/>
<link rel="icon" href="<?php echo $config['site_url']; ?>/favicon.ico"/>
<title>折扣商品</title>
<meta name="format-detection" content="telephone=no"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
<meta name="apple-mobile-web-app-capable" content="yes"/>
<meta name="apple-mobile-web-app-status-bar-style" content="default"/>
<meta name="applicable-device" content="mobile"/>
<link rel="stylesheet" href="<?php echo TPL_URL; ?>css/base.css"/>
<link rel="stylesheet" href="<?php echo TPL_URL; ?>css/main.css"/>
<link rel="stylesheet" href="<?php echo TPL_URL; ?>css/prop.css"/>
<link rel="stylesheet" href="<?php echo TPL_URL; ?>index_style/css/category_detail.css"/>
<link rel="stylesheet" href="<?php echo TPL_URL; ?>css/gonggong.css"/>
<script src="<?php echo $config['oss_url']; ?>/static/js/fastclick.js"></script>
<script src="<?php echo $config['oss_url']; ?>/static/js/jquery.min.js"></script>
<script src="<?php echo $config['oss_url']; ?>/static/js/idangerous.swiper.min.js"></script>
<script src="<?php echo TPL_URL; ?>js/base.js"></script>
<script>var keyword = '<?php echo $keyword;?>', key_id = '<?php echo $key_id;?>';</script>
<script src="<?php echo TPL_URL; ?>index_style/js/category_discount.js"></script>
</head>
<body>
<div class="mid-autumn-img">
	<?php
	if(empty($banner[0])) {
		echo '请添加标签为 wap_lottery_top 的广告。';
	} else {
		$value = $banner[0];
		echo '<a href="'.$value['url'].'"><img src="'.$value['pic'].'" alt="'.$value['name'].'" /></a>';
	} ?>
</div>
<div class="wx_wrap">
	<div id="searchResBlock">
		<div class="s_null hide" id="sNull01">
			<h5>抱歉，没有找到符合条件的商品。</h5>
		</div>
		<div class="mod_itemgrid hide" id="itemList"></div>
		<div class="wx_loading2"><i class="wx_loading_icon"></i></div>
	</div>
</div>
<div class="mid-autumn-img">
	<?php
	if(empty($footer[0])) {
		echo '请添加标签为 wap_lottery_footer 的广告。';
	} else {
		$value = $footer[0];
		echo '<a href="'.$value['url'].'"><img src="'.$value['pic'].'" alt="'.$value['name'].'" /></a>';
	} ?>
</div>
<?php
$noFooterLinks = true;
$noFooterCopy = true;
include display('footer'); ?>
<?php
echo $shareData;
include display('public_menu'); ?>

</body>
</html>