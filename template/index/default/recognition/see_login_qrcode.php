<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
</head>
<script type="text/javascript" src="./static/js/jquery.min.js"></script>
<link href="<?php echo TPL_URL; ?>css/login.css" type="text/css" rel="stylesheet">
<script type="text/javascript">
	var redirect_url = <?php echo empty($_GET['referer']) ? 'window.top.location.href'
		: '"' . str_replace('&amp;', '&', htmlspecialchars_decode($_GET['referer'])) . '"'; ?>;
	window.setTimeout(function () {
		window.location.href = window.location.href;
	}, 1200000);
	window.setTimeout(function () {
		ajax_weixin_login();
	}, 3000);

	function ajax_weixin_login() {
		$.get("<?php dourl('index:wxlogin:ajax_weixin_login');?>", {qrcode_id:<?php echo $login_qrcode_id;?>}, function (result) {
			if(result == 'reg_user'){
				$('#login_status').html('已扫描！请在微信公众号中点击授权登录。').css('color','green').show();
				ajax_weixin_login();
			}else if(result == 'no_user'){
				$('#login_status').html('没有查找到此用户，请重新扫描二维码！').css('color','red').show();
				window.location.href = redirect_url;
			}else if(result!=='true'){
				ajax_weixin_login();
			}else{
				$('#login_status').html('登录成功！正在跳转。').css('color','green').show();
				window.top.location.href = redirect_url;
			}
		});
	}
</script>
<body>
<div class="login">
	<!--<p style="<?php if (empty($_GET['mt'])) { echo 'margin-top:50px;'; } ?>margin-bottom:0px;text-align:center;">
		请使用微信扫描二维码登录</p>-->
	<p style="text-align:center;"><img src="<?php echo $qrcode; ?>"
	                                   style="width:200px;height:200px;background:url('<?php echo $config['oss_url']; ?>/static/images/lightbox-ico-loading.gif') no-repeat center"/>
	</p>
	<p id="login_status" style="margin-top:20px;display:none;text-align:center;font-size:14px;"></p>
	<?php
//	echo $config['web_login_show'] == 2
//		? '<p style="' . (empty($_GET['mt']) ? 'margin-top:50px;' : '') .
//		'margin-bottom:0px;text-align:center;cursor:pointer" class="zh">选择账号密码登录</p>'
//		: '';
	?>
</div>

<!--<div id="zhdl" class="login" style="display:none">
	<form id="login" action="<?php echo url('account:login') ?>" method="post">
<input name="login_type" id="login_type" value="P-N" type="hidden">
<table class="login-table">
	<tbody>
	<tr>
		<td class="login-tip">
			<div id="login_error" class="error login_error" style="display: none;"></div>
		</td>
	</tr>
	<tr id="line_4" class="line_4">
		<td>
			<div class="input_div">
				<input name="phone" id="phone" autocomplete="off" tabindex="1"
				       class="txt txt-m txt-focus cgrey2" style="font-size:12px;" placeholder="请输入手机号"
				       type="text">
			</div>
		</td>
	</tr>
	<tr id="line_2" class="line_2">
		<td>
			<div class="input_div">
				<input name="password" autocomplete="off" tabindex="2" class="txt txt-m" id="password"
				       type="password">
			</div>
		</td>
	</tr>
	<tr>
		<td class="line2">
			<input type="hidden" name="referer" value="<?php echo $referer ?>"/>
			<input name="current_login_url" value="" type="hidden">
			<input value="登录" name="submit_login" tabindex="5" id="J_Login" class="sub" type="submit">
		</td>
	</tr>

	</tbody>
</table>

</form>
<div class="partner-login">
	<div class="oauth-wrapper" style="width:280px;">
		<h3 class="title-wrapper"><span class="title" style="left:40%">用手机微信扫码登录</span></h3>
		<div style="text-align:center" class="oauth cf">
			<a href="javascript:void(0);" id="wx_login" class="oauth__link oauth__link--weixin"></a>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">
	$(".zh").click(function () {
		$(".login").hide();
		$("#zhdl").show();

	})
	$("#wx_login").click(function () {
		$(".login").hide();
		$("#wxdl").show();

	})
</script>-->
</body>
</html>