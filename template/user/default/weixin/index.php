<?php if(!defined('TWIKER_PATH')) exit('deny access!');?>
<!doctype html>
<html>
<head>
<meta charset="utf-8"/>
<title>微店系统 -<?php echo $store_session['name'] . '|' . $config['site_name']; ?></title>
<meta name="copyright" content="<?php echo $config['site_url'];?>"/>
<script type="text/javascript">var load_url="<?php dourl('load');?>";</script>
<script type="text/javascript" src="./static/js/jquery.min.js"></script>
<script type="text/javascript" src="./static/js/layer/layer.min.js"></script>
<script type="text/javascript" src="<?php echo TPL_URL;?>js/base.js"></script>
<script type="text/javascript" src="<?php echo TPL_URL;?>js/source_list.js"></script>
<link href="<?php echo TPL_URL;?>css/base.css" type="text/css" rel="stylesheet"/>
<link href="<?php echo TPL_URL;?>css/image_text.css" type="text/css" rel="stylesheet"/>
<style type="text/css">
.popover { display: block; }
</style>
</head>
<body class="font14 usercenter">
<?php include display('public:header');?>
<div class="wrap_1000 clearfix container">
	<?php include display('sidebar');?>
	<div class="app">
		<div class="app-inner clearfix">
			<div class="app-init-container">
				<div class="nav-wrapper--app"></div>
				<div class="app__content page-showcase-dashboard"></div>
			</div>
		</div>
	</div>
</div>
<?php include display('public:footer');?>
</body>
</html>