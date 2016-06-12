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

<link href="<?php echo TPL_URL; ?>css/order.css" type="text/css" rel="stylesheet">
<link href="<?php echo TPL_URL; ?>css/order_detail.css" type="text/css" rel="stylesheet">

<script type="text/javascript" src="./static/js/jquery.min.js"></script>
<script type="text/javascript" src="./static/js/layer/layer.min.js"></script>
<script type="text/javascript" src="./static/js/area/area.min.js"></script>
<script type="text/javascript" src="<?php echo TPL_URL; ?>js/base.js"></script>
<script type="text/javascript" src="<?php echo TPL_URL; ?>js/common.js"></script>
<script type="text/javascript">
	var load_url = "<?php dourl('load');?>",
		save_url = "<?php dourl('order'); ?>";

	$(function () {
		var status = '*';
		load_page('.app__content', load_url, {page: 'order_content', 'status': status}, '');
		
		$('.ui-nav > ul > li > a').live('click', function(){
			//var obj = this;
			//var class_name = $(this).attr('class');
			status = $(this).attr('data');
						
			load_page('.app__content', load_url, {page: 'order_content', 'status': status}, '', function() {
				//状态
				if (status) {
					//$("select[name='status']").find("option[value='" + status + "']").attr('selected', true);
					$('.ui-nav > ul > li').removeClass('active');
					if (status != '*') {
						$(".status-" + status).closest('li').addClass('active');
					} else {
						$(".all").closest('li').addClass('active');
					}
				}
				
			});
		});

		/*$("#pages a").click(function () {
		 var page = $(this).attr("data-page-num");
		 location.href = "<?php echo url('account:order') ?>&page=" + page;
		 });*/

		$(".cancel").live('click', function () {
			if (confirm('真的要取消此订单吗？')) {
				var order_id = $(this).attr('data-id');
				$.getJSON("<?php echo url('account:order_cancel') ?>&order_id=" + order_id, function (data) {
					showResponse(data);
				});
			}
		});

		// 确认收货
		$(".confirm").live('click',function () {
			if (confirm('您确定已经收到货了？')) {
				var order_id = $(this).attr('data-id');
				$.getJSON("<?php echo url('account:order_confirm') ?>&order_id=" + order_id, function (data) {
					showResponse(data);
				});
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