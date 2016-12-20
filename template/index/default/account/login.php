<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>登录/注册-<?php echo $config['site_name']; ?></title>
	<meta name="Keywords" content="<?php echo $config['seo_keywords']; ?>">
	<meta name="description" content="<?php echo $config['seo_description']; ?>">
	<script type="text/javascript" src="<?php echo $config['oss_url']; ?>/static/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo $config['oss_url']; ?>/static/js/jquery.form.min.js"></script>
	<script type="text/javascript" src="<?php echo TPL_URL; ?>js/common.js" type="text/css" rel="stylesheet"></script>
	<script type="text/javascript" src="<?php echo $config['oss_url']; ?>/static/js/layer/layer.min.js"></script>
	<link href="<?php echo TPL_URL; ?>css/style.css" type="text/css" rel="stylesheet">
	<link href="<?php echo TPL_URL; ?>css/index.css" type="text/css" rel="stylesheet">
	<link href="<?php echo TPL_URL; ?>css/login.css" type="text/css" rel="stylesheet">
	<link href="<?php echo TPL_URL; ?>css/public.css" type="text/css" rel="stylesheet">
	<script src="<?php echo TPL_URL; ?>js/index2.js"></script>
	<style type="text/css">
		.xubox_iframe{ position: static; }
	</style>
	<script type="text/javascript">
		$(function () {
			$('#login').ajaxForm({
				beforeSubmit: function () {
					var phone = $("#phone").val();
					var password = $("#password").val();
					if (phone.length == 0 && password.length == 0) {
						tusi("请填写手机号和密码");
						return false;
					}

					if (!checkMobile(phone)) {
						tusi("请正确填写您的手机号");
						return false;
					}

					if (password.length < 6) {
						tusi("密码不能少于六位，请正确填写");
						return false;
					}
					return true;
				},
				success: showResponse,
				dataType: 'json'
			});
			$('#register').ajaxForm({
				beforeSubmit: function () {
					var phone1 = $("#phoneNew").val();
					var password1 = $("#passwordNew").val();
					var nickname1 = $("#nicknameNew").val();

					if (phone1.length == 0 && password1.length == 0) {
						tusi("请填写手机号和密码");
						return false;
					}

					if (!checkMobile(phone1)) {
						tusi("请正确填写您的手机号");
						return false;
					}
					if (nickname1.length == 0) {
						tusi("昵称不能为空，请正确填写");
						return false;
					}
					if (password1.length < 6) {
						tusi("密码不能少于六位，请正确填写");
						return false;
					}
					return true;
				},
				success: showResponse,
				dataType: 'json'
			});

			$("#login-tab li").click(function () {
				var index_li = $("#login-tab li").index($(this));
				$("#login-tab li").removeClass("cur");
				$(".login-tab-content").hide().eq(index_li).show();
				$(this).addClass("cur");
			})
		})
	</script>
	<style>
		.content{
			width: auto;
		}

		.cent_link a{
			width: auto;
		}

		.login-content{
			right: 200px;
		}
	</style>
</head>

<body id="index1200" class="index1200">
<!-- common header start -->
<div class="header">
	<div class="header_common">
		<div class="header_inner"style="padding-top:50px;">
			<!--<div class="site_logo">
				<a href="<?php echo $config['site_url']; ?>">
					<img src="<?php echo $config['site_logo']; ?>" width="235" height="70">
				</a>
			</div>-->
		</div>
	</div>
</div>

<div id="loginWrap" class="wrap"
     style="background-image:url(<?php echo empty($ad['pic']) ? TPL_URL . 'images/ico/1yuan_2.jpg' : $ad['pic']; ?>)"
>
	<div id="content" class="content">
		<div id="cent_link" class="cent_link">
			<a id="link_1" href="<?php echo empty($ad['url']) ? 'javascript:void(0)' : $ad['url']; ?>"
			   style="display:block;"></a></div>

		<input id="errLoginType" value="P-N" type="hidden">
		<input id="errLoginMsg" value="" type="hidden">

		<div id="login-content" class="login-content" >
			<div id="login-box" class="login-box-inner">
				<!--<ul id="login-tab" class="login-tab">
					<li id="login-tab-user" <?php if ($type != 'register') { ?>class="cur"<?php } ?>>会员登录<b></b></li>
					<li <?php if ($type == 'register') { ?>class="cur"<?php } ?> id="login-tab-pass">会员注册<b></b></li>
				</ul>-->
				<div>
					<div <?php echo $type == 'register' ? 'style="display: none;"' : ''; ?> class="login-tab-content"
					                                                                        id="login-tab-content0">
						<?php if ($config['web_login_show'] != 2) { ?>
							<form id="login" action="<?php echo url('account:login') ?>" method="post">
								<input name="login_type" id="login_type" value="P-N" type="hidden">
								<table class="login-table">
									<tbody>
									<tr>
										<td class="login-tip">
											<div id="login_error" class="error login_error"
											     style="display: none;"></div>
										</td>
									</tr>
									<tr id="line_4" class="line_4">
										<td>
											<div class="input_div">
												<input name="phone" id="phone" autocomplete="off" tabindex="1"
												       class="txt txt-m txt-focus cgrey2" style="font-size:12px;"
												       placeholder="请输入手机号" type="text">
											</div>
										</td>
									</tr>
									<tr id="line_2" class="line_2">
										<td>
											<div class="input_div">
												<input name="password" autocomplete="off" tabindex="2" class="txt txt-m"
												       id="password" type="password">
											</div>
										</td>
									</tr>
									<!--
									 <tr class="line_4">
										<td class="cgrey2 line2">
											<label style="vertical-align:middle"><input class="remember_me" tabindex="4" name="remember_me" id="rememberme2" value="1" style="vertical-align:middle" type="checkbox">&nbsp;两周内自动登录</label>
											<a target="_blank" href="" class="search_psw">忘记密码？</a>
										</td>
									</tr>
									-->
									<tr>
										<td class="line2">
											<input type="hidden" name="referer" value="<?php echo $referer ?>"/>
											<input name="current_login_url" value="" type="hidden">
											<input value="登录" name="submit_login" tabindex="5" id="J_Login" class="sub"
											       type="submit">
										</td>
									</tr>

									</tbody>
								</table>

							</form>

							<?php if ($config['web_login_show'] != 1) { ?>
								<div class="partner-login">
									<div class="oauth-wrapper">
										<h3 class="title-wrapper"><span class="title">用手机微信扫码登录</span></h3>
										<div class="oauth cf">
											<a href="javascript:void(0);" id="wx_login"
											   class="oauth__link oauth__link--weixin"></a>
										</div>
									</div>
								</div>
							<?php } ?>
						<?php }
						else { ?>
							<iframe
								src="./index.php?c=recognition&a=see_login_qrcode&mt=none&referer=<?php echo urlencode($referer); ?>"
								style="border:none;height:540px; width:220px;padding-top:110px;"></iframe>
						<?php } ?>
					</div>

					<!--注册-- start-->
					<div <?php echo $type == 'register' ? '' : 'style="display: none;"'; ?>
						class="login-tab-content " id="login-tab-content1">

						<?php if ($config['web_login_show'] != 2) { ?>
							<form id="register" action="<?php echo url('account:register') ?>" method="post">
								<input name="login_type" value="P-M" type="hidden">
								<table class="login-table">
									<tbody>
									<tr>
										<td class="login-tip">
											<div id="login_error" class="error login_error"
											     style="display: none;"></div>
										</td>
									</tr>
									<tr id="line_4" class="line_4">
										<td>
											<div class="input_div">
												<input name="phone" id="phoneNew" autocomplete="off" tabindex="1"
												       class="txt txt-m txt-focus cgrey2" style="font-size:12px;"
												       placeholder="请输入手机号" type="text"/>
											</div>
										</td>
									</tr>
									<tr id="line_1" class="line_1">
										<td>
											<div class="input_div">
												<input name="nickname" id="nicknameNew" autocomplete="off" tabindex="2"
												       class="txt txt-m" placeholder="请输入昵称" type="text"/>
											</div>
										</td>
									</tr>
									<tr id="line_2" class="line_2">
										<td>
											<div class="input_div">
												<input id="passwordNew" name="password" autocomplete="off" tabindex="2"
												       class="txt txt-m" type="password" placeholder="请输入密码">
											</div>
										</td>
									</tr>
									<!--
									<tr class="line_4">
										<td class="cgrey2 line2">
											<label style="vertical-align:middle"><input class="remember_me" tabindex="4" name="remember_me" id="rememberme2" value="1" style="vertical-align:middle" type="checkbox">&nbsp;两周内自动登录</label>
											<a target="_blank" href="" class="search_psw">忘记密码？</a>
										</td>
									</tr>-->
									<tr>
										<td class="line2">
											<input name="current_login_url" value="" type="hidden">
											<input type="hidden" name="referer" value="<?php echo $referer ?>"/>
											<input class="sub" tabindex="5" onclick="" name="submit_login" value="注册"
											       type="submit">
										</td>
									</tr>
									</tbody>
								</table>
							</form>
						<?php }
						else { ?>
							<iframe
								src="./index.php?c=recognition&a=see_register_qrcode&mt=none&referer=<?php echo urlencode($referer); ?>"
								style="border:none;height:460px;"></iframe>

						<?php } ?>
					</div>
					<!--注册--end-->
				</div>
			</div>
		</div>
	</div>

	<!--start foot-->
	<?php include display('public:footer'); ?>
	<!--end foot-->
	<!-- common footer end -->

</body>
</html>