<?php if (!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
<meta name="apple-mobile-web-app-capable" content="yes"/>
<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
<meta name="format-detection" content="telephone=no"/>
<title>账户充值 -<?php echo $config['site_name']; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/foundation.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/common.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/trade.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>app/cz.css"/>
<script src="<?php echo TPL_URL; ?>js/jquery.js"></script>
<style type="text/css">
/*.side-nav li { padding: 5px; }
.bank-info { display: inline-block; font-weight: bold; width: 80px; font-family: "微软雅黑", "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif !important; color: #999; }
.arrow { margin-top: -15px !important; }
.commision-total .icon-horn { float: right; margin: 0; }
*/
</style>
</head>
<body>
<div class="mask"></div>
<div class="fixed">
	<nav class="tab-bar">
		<section class="left-small"> <a class="menu-icon" href="./my.php"><span></span></a> </section>
		<section class="middle tab-bar-section">
			<h1 class="title">账户充值</h1>
		</section>
		<section class="right-small right-btn-brokerage"> <a class="a-borkerage-detail" href="javascript:;"
		   onclick="window.location.href='./balance.php?a=detail'">明细</a> </section>
	</nav>
</div>
<div class="bro-spare">
	<p class="tip-txt"><i class="icon-money"></i>账户余额（元）</p>
	<span class="number-big"><?php echo $balance; ?></span>
	<p class="field-2">我的积分：<big><?php echo $point; ?></big></p>
</div>
<div class="my_menu">
	<?php foreach($list as $v) {
		echo '<ul>
		<li><a href="javascript:;" data-value="'.intval($v['amount']).'">
			<p>'.intval($v['amount']).' 元</p>
			赠送 '.$v['point'].' 积分</a> </li>
	</ul>';	
	}?>
	<div class="clear"></div>
</div>
<script type="text/javascript">
$(function(){
	$('.my_menu ul li a').click(function(){
		var value = parseInt($(this).data('value'));
		if(isNaN(value) || value <= 0) {
			alert('有错误发生，请刷新后重试！');
			return false;
		}
		
		var loadingCon = $('<div style="overflow:hidden;visibility:visible;position:absolute;z-index:1100;transition:opacity 300ms ease;-webkit-transition:opacity 300ms ease;opacity:1;top:' + (($(window).height() - 100) / 2) + 'px;left:' + (($(window).width() - 200) / 2) + 'px;"><div class="loader-container"><div class="loader center">处理中</div></div></div>');
		var loadingBg = $('<div style="height:100%;position:fixed;top:0px;left:0px;right:0px;z-index:1000;opacity:1;transition:opacity 0.2s ease;-webkit-transition:opacity 0.2s ease;background-color:rgba(0,0,0,0.901961);"></div>');
		$('html').css({'position': 'relative', 'overflow': 'hidden', 'height': $(window).height() + 'px'});
		$('body').css({
			'overflow': 'hidden',
			'height': $(window).height() + 'px',
			'padding': '0px'
		}).append(loadingCon).append(loadingBg);
		nowScroll = $(window).scrollTop();
		
		$.post('./balance.php?a=recharge',{'balance':value},function(result){
			loadingBg.css('opacity', 0);
			setTimeout(function () {
				loadingCon.remove();
				loadingBg.remove();
			}, 200);
			
			if(result.err_code) {
				alert(result.err_msg);
				return false;
			} else {
				if (window.WeixinJSBridge) {
					window.WeixinJSBridge.invoke("getBrandWCPayRequest", result.err_msg, function (res) {
						WeixinJSBridge.log(res.err_msg);
						if (res.err_msg == "get_brand_wcpay_request:ok") {
							window.location.href = './balance.php?a=detail';
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
							motify.log(err_msg);
						}
					});
				}
				else {
					alert(result.err_msg);
				}
				return false;
			}
		},'JSON');
	});
});
</script>
<?php echo $shareData; ?>
</body>
</html>