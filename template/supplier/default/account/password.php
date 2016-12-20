<?php if(!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<title>首页 -<?php echo $store_session['name'] . '|' . $config['site_name']; ?></title>
	<meta name="copyright" content="<?php echo $config['site_url']; ?>" />
	<link href="<?php echo TPL_URL; ?>css/base.css" type="text/css" rel="stylesheet" />
	<link href="<?php echo TPL_URL; ?>css/store.css" type="text/css" rel="stylesheet" />
	<link href="./static/css/jquery.ui.css" type="text/css" rel="stylesheet" />
	<script type="text/javascript" src="./static/js/jquery.min.js"></script>
	<script type="text/javascript" src="./static/js/layer/layer.min.js"></script>
	<script type="text/javascript" src="./static/js/area/area.min.js"></script>
	<script type="text/javascript" src="<?php echo TPL_URL; ?>js/base.js"></script>
	<script type="text/javascript">
		var load_url = "<?php dourl('load');?>",
			save_url = "<?php dourl('password'); ?>";

		$(function () {
			load_page('.app__content', load_url, {page: 'password_content'}, '');

			$("input[type=password]").live("focus", function () {
				$(this).closest(".control-group").removeClass("error");
				$(this).closest(".control-group").find(".error-message").remove();
			});

			// 提交
			$('.js-btn-submit').live('click', function () {
				var current = $("input[name=current]").val();
				var password = $("input[name=password]").val();
				var confirm = $('input[name=confirm]').val();

				if (current == '') {
					$("input[name=current]").closest('.control-group').addClass('error');
					$("input[name=current]").parent().append('<span class="error-message">当前密码不能为空！</span>');
					return false;
				}

				if (password=='') {
					$('input[name=password]').parent().append('<span class="error-message">请输入新密码！</span>');
					$('input[name=password]').closest(".control-group").addClass("error");
					return false;
				}

				if (password!=confirm) {
					$('input[name=confirm]').parent().append('<span class="error-message">两次密码输入不一致！</span>');
					$('input[name=confirm]').closest(".control-group").addClass("error");
					return false;
				}

				if(password==current){
					$('input[name=password]').parent().append('<span class="error-message">新密码和当前密码不能相同！</span>');
					$('input[name=password]').closest(".control-group").addClass("error");
					return false;
				}

				$.post(save_url, {
					'current': current,
					'password': password,
					'confirm': confirm,
				}, function (data, textStatus, jqXHR) {
					if (!data.err_code) {
						$('.notifications').html('<div class="alert in fade alert-success">'+data.err_msg+'</div>');
						t = setTimeout('msg_hide()', 2000);
					}
					else {
						$('.notifications').html('<div class="alert in fade alert-error">'+data.err_msg+'</div>');
						t = setTimeout('msg_hide()', 2000);
					}
				}, 'json');
			});
		});
	</script>
</head>
<body class="font14 usercenter">
<?php include display('public:header'); ?>
<div class="wrap_1000 clearfix container">
	<?php include display('sidebar'); ?>
	<div class="app">
		<div class="app-inner clearfix">
			<div class="app-init-container">
				<div class="nav-wrapper--app"></div>
				<div class="app__content page-showcase-dashboard"></div>
			</div>
		</div>
	</div>
</div>
<?php include display('public:footer'); ?>
<div id="nprogress">
	<div class="bar" role="bar">
		<div class="peg"></div>
	</div>
	<div class="spinner" role="spinner">
		<div class="spinner-icon"></div>
	</div>
</div>
</body>
</html>