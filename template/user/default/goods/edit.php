<?php if(!defined('TWIKER_PATH')) exit('deny access!');?>
<!doctype html>
<html>
<head>
<meta charset="utf-8"/>
<title>编辑商品 -<?php echo $store_session['name'];?>|
<?php if (empty($_SESSION['sync_store'])) { ?>
<?php echo $config['site_name'];?>
<?php } else { ?>
微店系统
<?php } ?>
</title>
<link href="<?php echo TPL_URL;?>css/base.css" type="text/css" rel="stylesheet"/>
<link href="<?php echo TPL_URL;?>css/goods_create.css" type="text/css" rel="stylesheet"/>
<link href="<?php echo TPL_URL;?>css/store_ucenter.css" type="text/css" rel="stylesheet"/>
<link href="./static/css/jquery.ui.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="./static/js/jquery.min.js"></script>
<script type="text/javascript" src="./static/js/layer/layer.min.js"></script>
<script type="text/javascript" src="./static/js/area/area.min.js"></script>
<?php include display('public:custom_header');?>
<script type="text/javascript" src="<?php echo TPL_URL;?>js/base.js"></script>
<script type="text/javascript">
var load_url="<?php dourl('goods_load', array('id' => $_GET['id']));?>",
	get_product_property_url="<?php dourl('get_product_property_list');?>",
	get_property_value_url="<?php dourl('get_property_value');?>",
	get_system_product_property_list="<?php dourl('get_system_product_property_list');?>",
	get_trade_delivery_url="<?php dourl('get_trade_delivery');?>",
	save_url="<?php dourl('edit');?>",
	get_propertyvaluebyid_url="<?php dourl('get_propertyvaluebyid');?>",
	get_goodsCategory_url="<?php dourl('get_goodsCategory');?>",
	referer="<?php echo !empty($_GET['referer']) ? trim($_GET['referer']) : ''; ?>";

var property_value_img = "<?php dourl('property_value_img');?>"; // http://d.pigcms.com/user.php?c=goods&a=";
//var get_propertyvaluebyid_url="<?php dourl('get_propertyvaluebyid');?>"; // http://d.pigcms.com/user.php?c=goods&a=get_propertyvaluebyid";

var is_sync_user = "";
</script>
<script type="text/javascript" src="<?php echo TPL_URL;?>js/goods_edit.js"></script>
</head>
<body class="font14 usercenter">
<?php include display('public:header');?>
<div class="wrap_1000 clearfix container">
	<?php include display('sidebar');?>
	<div class="app">
		<div class="app-inner clearfix">
			<div class="app-init-container">
				<div class="app__content js-app-main"></div>
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