<?php if(!defined('TWIKER_PATH')) exit('deny access!');?>
<!doctype html>
<html>
<head>
<meta charset="utf-8"/>
<title>应用营销 -<?php echo $store_session['name']; ?>|<?php echo $config['site_name'];?></title>
<meta name="author" content="状元分销CMS"/>
<meta name="generator" content="状元分销CMS微店程序"/>
<meta name="copyright" content="pigcms.com"/>
<link href="<?php echo TPL_URL;?>css/base.css" type="text/css" rel="stylesheet"/>
<link href="<?php echo TPL_URL;?>css/freight.css" type="text/css" rel="stylesheet"/>
<link href="<?php echo TPL_URL;?>css/order.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="./static/js/jquery.min.js"></script>
<script type="text/javascript" src="./static/js/layer/layer.min.js"></script>
<script type="text/javascript" src="./static/js/area/area.min.js"></script>
<script type="text/javascript" src="./static/js/echart/echarts.js"></script>
<script type="text/javascript" src="<?php echo TPL_URL;?>js/base.js"></script>
<script type="text/javascript">var load_url="<?php dourl('load');?>";</script>
<script type="text/javascript" src="<?php echo TPL_URL;?>js/dashboard.js"></script>
</head>
<body class="font14 usercenter">
<?php include display('public:header');?>
<div class="wrap_1000 clearfix container">
	<?php include display('public:yx_sidebar');?>
	<div class="app">
		<div class="app-inner clearfix">
			<div class="app-init-container">
				<div class="nav-wrapper--app"></div>
				<div class="app__content"></div>
			</div>
		</div>
	</div>
</div>
<?php include display('public:footer');?>
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