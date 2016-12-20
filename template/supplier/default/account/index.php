<?php if(!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>首页 -<?php echo $store_session['name'] . '|' . $config['site_name']; ?></title>
<meta name="copyright" content="<?php echo $config['site_url']; ?>" />
<link href="<?php echo TPL_URL; ?>css/base.css" type="text/css" rel="stylesheet" />
<link href="<?php echo TPL_URL; ?>css/store.css" type="text/css" rel="stylesheet" />
<link href="<?php echo TPL_URL; ?>css/order.css" type="text/css" rel="stylesheet" />
<link href="./static/css/jquery.ui.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="./static/js/jquery.min.js"></script>
<script type="text/javascript" src="./static/js/layer/layer.min.js"></script>
<script type="text/javascript" src="./static/js/area/area.min.js"></script>
<script type="text/javascript" src="<?php echo TPL_URL; ?>js/base.js"></script>
<script type="text/javascript">
	var load_url = "<?php dourl('load');?>",
		save_url = "<?php dourl('index'); ?>";

	$(function () {
		load_page('.app__content', load_url, {page: 'index_content'}, '');
		
		$('.ui-store-board-logo').live('hover', function(event){
			if(event.type == 'mouseenter') {
				$(this).find('.hide').show();
			} else {
				$(this).find('.hide').hide();
			}
		});
	});

	function load_product() {
		$.get('<?php dourl('ajax_rand_product'); ?>',
			function (obj) {
				//每个子类
				var htmls = "";
				var ddsj = "";
				for (var ii in obj) {
					var weidian_code = '/source/qrcode.php?type=good&id=' + obj[ii].product_id;

					/*if (obj[ii].long) {
						var obj_location = getUserDistance();

						if (obj_location) {
							var long = obj_location.long;
							var lat = obj_location.lat;
							var juli = (getFlatternDistance(lat, long, obj[ii].lat, obj[ii].long) / 1000).toFixed(2) + 'km';
							var ddsj = expressTime2(obj[ii].lat, obj[ii].long);
						} else {
							juli = "0km";
							ddsj = "请设置您的位置";
						}
					} else {
						juli = "";
						ddsj = "请设置您的位置";
					}*/
					htmls += '<li> <a href="' + obj[ii].link + '"> <div class="content_list_img"><img width="224" height="159" src="' + obj[ii].image + '"> <div class="content_list_erweima"> <div class="content_list_erweima_img"><img src="' + weidian_code + '"></div> <div class="content_shop_name">' + obj[ii].name + '</div></div> </div><div class="content_list_txt"> <div class="content_list_pice">￥<span>' + obj[ii].price + '</span></div><div class="content_list_dec"><span>售' + obj[ii].sales + '/</span>分销' + obj[ii].drp_seller_qty + '</div> </div>'+
					//'<div class="content_list_txt"> <div class="content_list_day">' + ddsj + ' </div><div class="content_list_add"><span></span>' + juli + '</div></div>'+
					' </a> </li>';
				}
				$(".content_list_ul").html(htmls);
			},
			'json'
		)
	}
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