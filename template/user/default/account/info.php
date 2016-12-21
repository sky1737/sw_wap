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
		check_url = "<?php dourl('check'); ?>",
		save_url = "<?php dourl('info'); ?>";

	$(function () {
		load_page('.app__content', load_url, {page: 'info_content'}, '');

		$('.js-add-picture').live('click', function () {
			upload_pic_box(1, true, function (pic_list) {
				if (pic_list.length > 0) {
					for (var i in pic_list) {
						$('.avatar-img').attr('src', pic_list[i]);
					}
				}
			}, 1);
		});

		//店铺名唯一性检测
		$(".js-name").live('blur', function () {
			if ($(".js-name").val() != '' && $(".js-name").val() != $(".js-name").attr('data')) {
				$.post(check_url, {'key': 'nickname', 'value': $.trim($(".js-name").val())}, function (data) {
					if (!data) {
						$(".js-name").parent().append('<span class="error-message js-name-message">昵称已存在</span>');
						$(".js-name").closest(".control-group").addClass("error");
					}
				});
			}
		});

		$(".js-mobile").live("focus", function () {
			$(this).closest(".control-group").removeClass("error");
			$(this).closest(".control-group").find(".js-mobile-message").remove();
		});

		$(".js-name").live("focus", function () {
			$(this).closest(".control-group").removeClass("error");
			$(this).closest(".control-group").find(".js-name-message").remove();
		});

		$(".js-mobile").live('blur', function () {
			var mobile = $(".js-mobile").val();
			if (!/^1[0-9]{10}$/.test(mobile)) {
				$(this).parent().append('<span class="error-message js-mobile-message">手机号码格式不正确！</span>');
				$(this).closest(".control-group").addClass("error");
				return false;
			}
			if (mobile != $(".js-mobile").attr('data')) {
				$.post(check_url, {'key': 'phone', 'value': mobile}, function (data) {
					if (data) return false;

					$(this).parent().append('<span class="error-message js-name-message">手机号码已存在！</span>');
					$(this).closest(".control-group").addClass("error");
				});
			}
		});

		//店铺配置
		$('.js-btn-submit').live('click', function () {
			var name = $(".js-name").val();
			var mobile = $(".js-mobile").val();
			var avatar = $('.avatar-img').attr('src');
			var intro = $('.js-intro').val();

			if (name == '') {
				$(".js-name").closest('.control-group').addClass('error');
				$(".js-name").append('<span class="error-message js-name-message">昵称不能为空</span>');
				return false;
			}

			if (!/^1[0-9]{10}$/.test(mobile)) {
				$(this).parent().append('<span class="error-message js-mobile-message">手机号码格式不正确！</span>');
				$(this).closest(".control-group").addClass("error");
				return false;
			}

			if (!$('.error').length) {
				$.post(save_url, {
					'name': name,
					'phone': mobile,
					'avatar': avatar,
					'intro': intro,
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
			}
			else {
				return false;
			}
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