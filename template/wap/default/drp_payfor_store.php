<?php if(!defined('TWIKER_PATH')) exit('deny access!'); ?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="format-detection" content="telephone=no" />
	<title>开个微店成为大赢家 - <?php echo option('config.site_name'); ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/foundation.css">
	<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/normalize.css">
	<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/common.css">
	<script src="<?php echo TPL_URL; ?>js/jquery.js"></script>
	<script src="<?php echo TPL_URL; ?>js/base.js"></script>
	<style type="text/css">
		.bottom {
			height: 50px;
			border-top: solid 1px #eee;
			background: #fff;
			line-height: 30px;
		}
		.bottom .left {
			float: left;
			padding: 10px;
			font-size: 12px;
		}
		.bottom .left span {
			float: left;
			height: 30px;
			line-height: 30px;
		}
		.bottom .left span.price {
			font-size: 15px;
			padding: 0 3px 0 0;
		}
		.bottom .right {
			float: right;
			padding: 10px;
		}
		.bottom .right .payfor {
			border-radius: 3px;
			height: 30px;
			line-height: 30px;
			padding: 0 8px;
			background-color: #D73C6B;
			font-size: 14px;
			display: block;
			color: #fff;
		}
	</style>
</head>

<body style="max-width:640px;background:#9de7fc;">
<div>
	<img src="<?php echo TPL_URL; ?>images/payfor.png" alt="开个微店吧" style="width: 100%;" />
</div>
<div class="h50"></div>
<div class="fixed bottom" style="">
	<div class="left">
		<span>友情价：</span>
		<span class="price">￥<?php printf("%.2f", $config['payfor_store'] * 1); ?></span>
		<span>(数量有限)</span>
	</div>
	<div class="right">
		<a href="javascript:;" class="payfor">立即开店</a>
	</div>
</div>

<script type="text/javascript">
	$(function () {
		$('.payfor').click(function () {
			var loadingCon = $('<div style="overflow:hidden;visibility:visible;position:absolute;z-index:1100;transition:opacity 300ms ease;-webkit-transition:opacity 300ms ease;opacity:1;top:' + (($(window).height() - 100) / 2) + 'px;left:' + (($(window).width() - 200) / 2) + 'px;"><div class="loader-container"><div class="loader center">处理中</div></div></div>');
			var loadingBg = $('<div style="height:100%;position:fixed;top:0px;left:0px;right:0px;z-index:1000;opacity:1;transition:opacity 0.2s ease;-webkit-transition:opacity 0.2s ease;background-color:rgba(0,0,0,0.901961);"></div>');
			$('html').css({'position': 'relative', 'overflow': 'hidden', 'height': $(window).height() + 'px'});
			$('body').css({
				'overflow': 'hidden',
				'height': $(window).height() + 'px',
				'padding': '0px'
			}).append(loadingCon).append(loadingBg);
			nowScroll = $(window).scrollTop();

			$.post('./drp_register.php', {'type': 'payfor'}, function (result) {
				loadingBg.css('opacity', 0);
				setTimeout(function () {
					loadingCon.remove();
					loadingBg.remove();
				}, 200);

				if (result.err_code) {
					alert(result.err_msg);
					return false;
				}
				else {
					if (window.WeixinJSBridge) {
						window.WeixinJSBridge.invoke("getBrandWCPayRequest", result.err_msg, function (res) {
							WeixinJSBridge.log(res.err_msg);
							if (res.err_msg == "get_brand_wcpay_request:ok") {
								//window.location.href='drp_register.php?type=redpack&order_no='+result.err_dom;
//								//alert('支付成功！');
//								$.post('./drp_register.php', {
//									'type': 'redpack',
//									'order_no': result.err_dom
//								}, function (data) {
//									if (data.err_code) {
//										alert(data.err_msg + '(' + data.err_msg + ')')
//									}
								window.location.href = './drp_ucenter.php';
//								}, 'JSON');
							}
							else {
								if (res.err_msg == "get_brand_wcpay_request:cancel") {
									var err_msg = "您取消了微信支付";
								}
								else if (res.err_msg == "get_brand_wcpay_request:fail") {
									var err_msg = "微信支付失败<br/>错误信息：" + res.err_desc;
								}
								else {
									var err_msg = res.err_msg + "<br/>" + res.err_desc;
								}
								alert(err_msg);
							}
						});
					}
					else {
						alert('请在微信中发起支付请求！')
						//alert(result.err_msg);
					}
					return false;
				}
			}, 'JSON');
		});
	});
</script>
</body>
</html>