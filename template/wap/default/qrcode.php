<?php if (!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<title>店铺二维码 -<?php echo $now_store['name']; ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/foundation.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/normalize.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/common.css"/>
	<script src="<?php echo $config['site_url']; ?>/static/js/jquery.min.js"></script>
</head>

<body class="body-gray">
<div class="fixed tab-bar">
	<section class="left-small"><a href="./drp_ucenter.php" class="menu-icon"><span></span></a></section>
	<section class="middle tab-bar-section">
		<h1 class="title">我的推广码</h1>
	</section>
</div>
<div class="qrcode">
	<img src="<?php echo $qrcode; ?>"/>
	<!--<p>关注方式：长按二维码，<br/>选择“识别图中二维码”关注我！</p>
	<a class="qrcode-address" href="<?php echo $store_url; ?>"><?php echo $store_url; ?></a>
	<a href="<?php echo $store_url; ?>" class="qrcode-a">点击访问我的微店</a>-->
</div><!---->


<!--loading-->
<style type="text/css">
	/*loading*/
	.loading {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: 10001;
	}

	.loader {
		position: absolute;
		top: 50%;
		left: 50%;
		width: 28px;
		height: 12px;
		margin: -6px 0 0 -14px;
		background: transparent;
	}

	.dot-pink,
	.dot-blue {
		position: absolute;
		top: 0;
		width: 12px;
		height: 12px;
		border-radius: 100%;
		-webkit-animation: pink 1.5s ease infinite;
		animation: pink 1.5s ease infinite;
	}

	.dot-pink {
		background: #e77a7a;
		left: -2px;
	}

	.dot-blue {
		-webkit-animation-name: blue;
		animation-name: blue;
		background: #ffc54f;
		right: -2px;
	}

	@-webkit-keyframes pink {
		0% {
			z-index: 100;
		}
		50% {
			z-index: 0;
			-webkit-transform: translateX(24px);
			transform: translateX(24px);
		}
	}

	@keyframes pink {
		0% {
			z-index: 100;
		}
		50% {
			z-index: 0;
			-webkit-transform: translateX(24px);
			transform: translateX(24px);
		}
	}

	@-webkit-keyframes blue {
		50% {
			z-index: 0;
			-webkit-transform: translateX(-24px);
			transform: translateX(-24px);
		}
	}

	@keyframes blue {
		50% {
			z-index: 0;
			-webkit-transform: translateX(-24px);
			stransform: translateX(-24px);
		}
	}
</style>
<div class="loading">
	<div class="loader"><i class="dot-pink"></i> <i class="dot-blue"></i></div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		$(".loading").hide();
	});
//	$('.js-share-link').on('click', function () {
//		$(".js-dialog-img").Dialog('hide');
//		$(".js-dialog-link").show();
//	});
//	$('.js-dialog-link .body').on('click', function () {
//		$(".js-dialog-link").hide();
//	});
//	$(".js-share-img").on('click', function () {
//		$(".js-dialog-link").hide();
//		$(".js-dialog-img").Dialog();
//	});
</script>
<?php echo $shareData; ?>

</body>

</html>
