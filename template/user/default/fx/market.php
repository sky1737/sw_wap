<?php if (!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8"/>
<title>批发市场 -<?php echo $config['site_name'].'分销平台|'.$config['site_name']; ?></title>
<meta name="copyright" content="<?php echo $config['site_url']; ?>"/>
<link href="<?php echo TPL_URL; ?>css/base.css" type="text/css" rel="stylesheet"/>
<link href="<?php echo TPL_URL; ?>css/fx.css" type="text/css" rel="stylesheet"/>
<link href="./static/css/jquery.ui.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="./static/js/jquery.min.js"></script>
<script type="text/javascript" src="./static/js/layer/layer.min.js"></script>
<script type="text/javascript" src="./static/js/area/area.min.js"></script>
<script type="text/javascript" src="./static/js/plugin/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo TPL_URL; ?>js/base.js"></script>
<script type="text/javascript">
var load_url = "<?php dourl('load');?>",
	delivery_address_url = "<?php dourl('delivery_address'); ?>",
	market_url = "<?php dourl('market'); ?>",
	soldout_url = "<?php dourl('goods:soldout'); ?>",
	service_url = "<?php dourl('service'); ?>";
</script>
<script type="text/javascript" src="<?php echo TPL_URL; ?>js/fx_common.js"></script>
<script type="text/javascript" src="<?php echo TPL_URL; ?>js/fx_market.js"></script>
</head>
<body class="font14 usercenter">
<?php include display('public:header'); ?>
<div class="wrap_1000 clearfix container">
	<?php include display('sidebar'); ?>
	<div class="app">
		<div class="app-inner clearfix">
			<div class="app-init-container">
				<div class="nav-wrapper--app"></div>
				<div class="app__content"></div>
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