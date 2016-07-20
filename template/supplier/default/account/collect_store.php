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
		save_url = "<?php dourl('collect_store'); ?>";

	$(function () {
		load_page('.app__content', load_url, {page: 'collect_store_content'}, '');
		
		$(".js-delete").live('click', function () {
			if (!confirm("您确定要取消收藏此店铺吗？")) {
				return;
			}

			var url = "/index.php?c=collect&a=cancel&id=" + $(this).attr("data-id") + "&type=" + 2;
			$.getJSON(url, function (data) {
				if (data.status == true) {
					//当有信息提示时
					if (data.msg != '') {
						alert(data.msg);
					}
					location.reload();
					return;
				}
				else {
					if (data.msg != '') {
						alert(data.msg);
					}
				}
			});
		});

		$("#pages a").live('click',function () {
			var page = $(this).attr("data-page-num");
			var url = "<?php echo url("account:collect_store") ?>&page=" + page;
			location.href = url;
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