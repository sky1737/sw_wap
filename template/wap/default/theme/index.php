<?php if (!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!--<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=0">-->
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="email=no">
<meta name="apple-mobile-web-app-title" content="twiker.win">
<title>首页 - <?php echo empty($now_store)?$config['site_name']:$now_store['name']; ?></title>
<meta name="keywords" content="<?php echo $config['seo_keywords']; ?>"/>
<meta name="description" content="<?php echo $config['seo_description']; ?>"/>
<link href="favicon.ico" rel="icon"/>
<link rel="stylesheet" href="<?php echo TPL_URL; ?>theme/css/style.css" type="text/css">
<link rel="stylesheet" href="<?php echo TPL_URL; ?>theme/css/swiper.min.css" type="text/css">
<link rel="stylesheet" href="<?php echo TPL_URL; ?>theme/css/index.css" type="text/css">
<link rel="stylesheet" href="<?php echo TPL_URL; ?>theme/css/gonggong.css" type="text/css">
<?php if ($is_mobile) { ?>
<link rel="stylesheet" href="<?php echo TPL_URL; ?>css/showcase.css"/>
<script>var is_mobile = true;</script>
<?php }else{ ?>
<link rel="stylesheet" href="<?php echo TPL_URL; ?>css/showcase_admin.css"/>
<script>var is_mobile = false;</script>
<?php } ?>
<script src="<?php echo $config['oss_url']; ?>/static/js/jquery.min.js"></script>
<script src="<?php echo TPL_URL; ?>theme/js/swiper.min.js"></script>
<!--<script async="" src="<?php echo TPL_URL; ?>theme/js/mobile-common.js"></script>
<script async="" src="<?php echo TPL_URL; ?>theme/js/app-m-main-common.js"></script>
<script async="" src="<?php echo TPL_URL; ?>theme/js/mobile-download-banner.js"></script>
<script async="" src="<?php echo TPL_URL; ?>theme/js/m-performance.js"></script>
<script async="" src="<?php echo TPL_URL; ?>theme/js/-mod-wepp-module-event-0.2.1-wepp-module-event.js,-mod-wepp-module-overlay-0.3.0-wepp-module-overlay.js,-mod-wepp-module-toast-0.3.0-wepp-module-toast.js"></script>
<script async="" src="<?php echo TPL_URL; ?>theme/js/mobile-common-search.js"></script>
<script async="" src="<?php echo TPL_URL; ?>theme/js/-mod-hippo-1.2.8-hippo.js,-mod-cookie-0.2.0-cookie.js,-mod-cookie-0.1.2-cookie.js"></script>
<script async="" src="<?php echo TPL_URL; ?>theme/js/app-m-dianping-index.js"></script>
<script async="" src="<?php echo TPL_URL; ?>theme/js/nugget-mobile.js"></script>
<script async="" src="<?php echo TPL_URL; ?>theme/js/swipe.js"></script>
<script async="" src="<?php echo TPL_URL; ?>theme/js/openapp.js"></script>
<script async="" src="<?php echo TPL_URL; ?>theme/js/app-m-style.js"></script>
<script async="" src="<?php echo TPL_URL; ?>theme/js/util-m-monitor.js"></script>
<script async="" src="<?php echo TPL_URL; ?>theme/js/xss.js"></script>
<script async="" src="<?php echo TPL_URL; ?>theme/js/whereami.js"></script>-->
<script async="" src="<?php echo TPL_URL; ?>theme/js/index.js?time=<?php echo time(); ?>"></script>
<!--<script type="text/javascript" src="--><?php //echo TPL_URL; ?><!--theme/js/example.js"></script>-->
<script src="http://api.map.baidu.com/api?v=1.2" type="text/javascript"></script>
<script>
</script>
</head>

<body style="max-width:640px;margin:0 auto;">
<header class="index-head" style="position:absolute;">
	<?php
	echo '<a class="logo" href="./index.php">';
	echo empty($now_store) ? '<img src="' . $config['site_logo'] . '" alt="' . $config['site_name'] . '" />'
		: '<img src="' . $now_store['logo'] . '" alt="' . $now_store['name'] . '" />';
	echo '</a>';
	?>
	<div class="search J_search"> <span class="js_product_search"></span>
		<input placeholder="输入商品名" class="search_input s-combobox-input" />
	</div>
	<a href="./my.php" class="me"></a> 
	<!--<div id="J_toast" class="toast ">你可以在这输入商品名称</div>--> 
</header>
<script type="text/javascript">
$(function () {
	/*$(".toast").fadeTo(5000, 0, function () {
		$(this).hide();
	});*/
	$(".s-combobox-input").val("");
	$('.s-combobox-input').keyup(function (e) {
		var val = $.trim($(this).val());
		if (e.keyCode == 13) {
			if (val.length > 0) {
				window.location.href = './category.php?keyword=' + encodeURIComponent(val);
			} else {
				return;
				motify.log('请输入搜索关键词');
			}
		}
		$('.j_PopSearchClear').show();
	});

	$(".js_product_search").click(function () {
		var val = $.trim($(".s-combobox-input").val());
		if (val.length == 0) {
			return;
		} else {
			window.location.href = './category.php?keyword=' + encodeURIComponent(val);
		}
	});
})
</script>
<div class="content">
	<div class="content-body">
		<?php
		if ($slide) { ?>
		<div class="banner">

			<div class="swiper-container s1 swiper-container-horizontal">
				<div class="swiper-wrapper">
					<?php
					foreach ($slide as $key => $value) {
						echo '<div class="swiper-slide blue-slide pulse '.
							($key==0?'swiper-slide-active':'swiper-slide-next').
							'"><a href="'.$value['url'].'"><img src="'.$value['pic'].'" alt="'.$value['name'].'"/></a></div>';
					}

				?>
				</div>
				<div class="swiper-pagination p1 swiper-pagination-clickable">
					<?php
					foreach ($slide as $key => $value) {
						echo '<span class="swiper-pagination-bullet '.($key==0?'swiper-pagination-bullet-active':'').'"></span>';
					}
					?>
				</div>
			</div>
		</div>
		<script type="text/javascript">
		var mySwiper = new Swiper('.s1',{
			loop: false,
			autoplay: 3000,
			pagination: '.p1',
			paginationClickable: true
		});
		</script>
		<?php
		}
		else {
			echo $pageAd;
		} ?>
	</div>
</div>
<?php
if ($categories) {
?>
<div class="content">
	<div class="content-body">
		<div class="index-category Fix">
			<div class="swiper-container s2 swiper-container-horizontal">
				<div class="swiper-wrapper">
					<?php
			$is_div_end = true;
			$i = 0;
			foreach ($categories as $key => $value) {
				$class = 'swiper-slide-next';
				if ($key == 0) {
					$class = 'swiper-slide-active';
				}

				if ($key % 10 == 0) {
					$i == 0;
					$is_div_end = false;
					echo '<div class="swiper-slide blue-slide   pulse ' . $class . '">';
					echo '<div class="Fix page icon_list" data-index="0" style="left: 0px; transition-duration: 300ms; -webkit-transition-duration: 300ms; -webkit-transform: translate(0px, 0px) translateZ(0px);">';
				}
				$i++;
				echo '<a href="./category.php?keyword='.$value['cat_name'].'&id='.$value['cat_id'].'" class="item"><div class="icon fadeInLeft yanchi'.$i.'" style="background:url('.$value['cat_pic'].'); background-size:40px 40px; background-repeat:no-repeat;"></div> '.$value['cat_name'].'</a>';
				
				if ($key % 10 == 9) {
					echo '</div></div>';
					$is_div_end = true;
				}
			}
			if (!$is_div_end) {
				echo '</div></div>';
			}
			
			echo '</div><div class="swiper-pagination p2 swiper-pagination-clickable">';
			
			for ($i = 0; $i < ceil(count($slider_nav) / 10); $i++) {
				$class = '';
				if ($i == 0) {
					$class = 'swiper-pagination-bullet-active';
				}
				echo '<span class="swiper-pagination-bullet '.$class.'"></span>';
			}
			?>
				</div>
			</div>
		</div>
	</div>
	<!--<script type="text/javascript">
		var mySwiper = new Swiper('.s2', {
			loop: false,
			autoplay: 6500,
			pagination: '.p2',
			paginationClickable: true
		});
	</script>--> 
</div>
<?php
}
/*if ($hot_brand_slide) {
?>
<div class="bord"></div>
<div class="index-rec J_reclist">
	<?php
		if ($cat) {
		?>
	<div class="home-tuan-list" id="home-tuan-list">
		<div class="market-floor" id="J_MarketFloor">
			<h3 class="modules-title"> 热门分类 </h3>
			<div class="modules-content market-list">
				<?php
					$is_ul_end = true;
					foreach ($cat as $key => $value) {
						if ($key % 2 == 0) {
							$is_ul_end = false;
							echo '<ul class="mui-flex">';
						}
						?>
				<li class="region-block cell"> <a href="./category.php?keyword=<?php echo $value['cat_name'] ?>&id=<?php echo $value['cat_id'] ?>"> <em class="main-title"><?php echo $value['cat_name'] ?></em> <span class="sub-title"> </span> <img class="market-pic" src="<?php echo $value['cat_pic'] ?>" width="50"
									 height="50"> </a> </li>
				<?php
						if ($key % 2 == 1) {
							echo '</ul>';
							$is_ul_end = true;
						}
					}
					if (!$is_ul_end) {
						echo '</ul>';
					}
					?>
			</div>
		</div>
	</div>
	<?php
	}
	?>
</div>
<?php
}*/
if ($pageContent){
?>
<div class="content" <?php echo $homePage['bgcolor'] ? 'style="background-color:' . $homePage['bgcolor'].';"':''; ?>>
	<div class="content-body">
		<?php echo $pageContent; ?>
	</div>
</div>
<?php
}
else {
?>
<style type="text/css">
.toutiao {background:#fff;}
.toutiao dt {width:135px;height:56px;float:left; background:url(/template/wap/default/theme/images/toutiao.jpg) no-repeat center left;}
.toutiao dd {padding:6px 0 6px 145px;height:44px;line-height:22px;}
.toutiao dd a { display:block;font-size:12px;background:url(/template/wap/default/theme/images/toutiao_dot.jpg) no-repeat 5px 10px;height:22px; padding: 0 5px 0 15px;float:left;overflow:hidden; }
</style>
<div class="content" style="padding:1px 0;height:56px;overflow:hidden;">
	<div class="content-body">
		<dl class="toutiao">
			<dt></dt>
			<dd>
				<div id="toutiao" style="width:100%;height:100%;">
					<?php
					if(empty($toutiao)) {
						echo '请添加标签为 wap_toutiao 的广告。';
					} else {
						foreach ($toutiao as $key => $value) {
							echo '<a href="'.$value['url'].'">'.$value['name'].'</a>';
						}
					}
					?>
				</div>
			</dd>
		</dl>
		<script type="text/javascript">
		$(function(){
			var width = $('#toutiao').width();
			$('#toutiao a').width(width/2-20);
			console.log(width);
		});
		</script>
	</div>
</div>
<style type="text/css">
.banner4 { display:block; border-bottom:1px solid #eee;}
.banner4 li:nth-child(1) {float:left;}
.banner4 li:nth-child(2) {float:left;}
.banner4 li:nth-child(3) {float:left;}
.banner4 li:nth-child(4) {clear:both;}
.banner4 li img {width:100%;height:100%;}
</style>
<div class="content">
	<div class="content-body" style="padding:10px;background:#fff;">
		<ul class="banner4">
			<?php
			if(empty($banner4)) {
				echo '请添加标签为 wap_banner 的广告。';
			} else {
				foreach ($banner4 as $key => $value) {
					echo '<li><a href="'.$value['url'].'"> <img src="'.$value['pic'].'" alt="'.$value['name'].'"/></a></li>';
				}
			}
			?>
		</ul>
		<script type="text/javascript">
		$(function(){
			var width = $(document).width()-20;
			$('.banner4').css({'width':width+'px','height':width+'px'});
			$('.banner4 li:eq(0)').css({'width':(width * 0.5)+'px', 'height':(width * 0.5 * 4 / 3)+'px'});
			$('.banner4 li:eq(1)').css({'width':(width * 0.5)+'px', 'height':(width * 0.5 * 4 / 3 / 2)+'px'});
			$('.banner4 li:eq(2)').css({'width':(width * 0.5)+'px', 'height':(width * 0.5 * 4 / 3 / 2)+'px'});
			$('.banner4 li:eq(3)').css({'width':width+'px', 'height':(width * 0.5 * 4 / 3 / 2)+'px'});
		});
		</script>
	</div>
</div>
<style type="text/css">
.banner8 {}
.banner8 li {float:left;}
.banner8 li img {width:100%;height:100%;
</style>
<div class="content">
	<div class="content-body" style="padding:10px;background:#fff;">
		<div style="text-align:center;padding: 10px 0;">
			<?php
			if(empty($titles[0])) {
				echo '请添加标签为 wap_title 的第 1 张广告。';
			} else {
				$value = $tites[0];
				echo '<img src="'.$value['pic'].'" alt="'.$value['name'].'" />';
			} ?>
		</div>
		<ul class="banner8">
			<?php
			if(empty($youxuan)) {
				echo '请添加标签为 wap_youxuan 的广告。';
			} else {
				foreach ($youxuan as $key => $value) {
					echo '<li><a href="'.$value['url'].'"> <img src="'.$value['pic'].'" alt="'.$value['name'].'"/></a></li>';
				}
			}
			?>
		</ul>
		<script type="text/javascript">
		$(function(){
			var width = $(document).width()-20;
			$('.banner8').css({'width':width+'px','height':(width/1.66)+'px'});
			
			$('.banner8 li').css({'width':(width / 5)+'px', 'height':(width / 1.66 / 2)+'px'});
			$('.banner8 li:eq(0)').css({'width':(width / 5 * 2)+'px'});
			$('.banner8 li:eq(4)').css({'width':(width / 5 * 2)+'px'});
		});
		</script>
	</div>
</div>
<style type="text/css">
.remai {}
.remai li {float:left;}
.remai li img {width:100%;height:100%;
</style>
<div class="content">
	<div class="content-body" style="padding:10px;background:#fff;">
		<div style="text-align:center;padding: 10px 0;">
			<?php
			if(empty($titles[1])) {
				echo '请添加标签为 wap_title 的第 2 张广告。';
			} else {
				$value = $tites[1];
				echo '<img src="'.$value['pic'].'" alt="'.$value['name'].'" />';
			} ?>
		</div>
		<ul class="remai">
			<?php
			if(empty($remai)) {
				echo '请添加标签为 wap_remai 的广告。';
			} else {
				foreach ($remai as $key => $value) {
					echo '<li><a href="'.$value['url'].'"> <img src="'.$value['pic'].'" alt="'.$value['name'].'"/></a></li>';
				}
			}
			?>
		</ul>
		<script type="text/javascript">
		$(function(){
			var width = $(document).width()-20;
			$('.remai').css({'width':width+'px','height':(width / 4 / 0.6)+'px'});
			$('.remai li').css({'width':(width / 4)+'px', 'height':(width / 4 / 0.6)+'px'});
		});
		</script>
	</div>
</div>

<style type="text/css">
.xinpin {}
.xinpin li {float:left;}
.xinpin li img {width:100%;height:100%;
</style>
<div class="content">
	<div class="content-body" style="padding:10px;background:#fff;">
		<div style="text-align:center;padding: 10px 0;">
			<?php
			if(empty($titles[2])) {
				echo '请添加标签为 wap_title 的第 3 张广告。';
			} else {
				$value = $tites[2];
				echo '<img src="'.$value['pic'].'" alt="'.$value['name'].'" />';
			} ?>
		</div>
		<ul class="xinpin">
			<?php
			if(empty($xinpin)) {
				echo '请添加标签为 wap_xinpin 的广告。';
			} else {
				foreach ($xinpin as $key => $value) {
					echo '<li><a href="'.$value['url'].'"> <img src="'.$value['pic'].'" alt="'.$value['name'].'"/></a></li>';
				}
			}
			?>
		</ul>
		<script type="text/javascript">
		$(function(){
			var width = $(document).width()-20;
			$('.xinpin').css({'width':width+'px','height':(width/1.53)+'px'});
			$('.xinpin li').css({'width':(width / 4)+'px', 'height':(width/1.53/2)+'px'});
		});
		</script>
	</div>
</div>
<div class="content">
	<div class="content-body">
		<div class="index-event">
			<div class="cnt">
				<?php
				foreach ($banners as $key => $value) {
					echo '<a class="item" href="'.$value['url'].'"> <img src="'.$value['pic'].'" alt="'.$value['name'].'"/> </a>';
				} ?>
			</div>
		</div>
	</div>
</div>
<div class="content">
	<div class="content-body" style="padding:10px;background:#fff;">
		<div style="text-align:center;padding: 10px 0;">
			<?php
			if(empty($titles[3])) {
				echo '请添加标签为 wap_title 的第 4 张广告。';
			} else {
				$value = $tites[3];
				echo '<img src="'.$value['pic'].'" alt="'.$value['name'].'" />';
			} ?>
		</div>
	</div>
</div>
<?php
foreach($categories as $key=>$val) {
	$category = $categories[$key];
	$products = $category_products[$key];
?>
<div class="content" <?php echo $homePage['bgcolor'] ? 'style="background-color:' . $homePage['bgcolor'].';"':''; ?>>
	<div class="content-body">
		<div>
			<div class="custom-title text-left">
				<h2 class="title"><?php echo $category['cat_name']; ?><a href="./category.php?keyword=<?php echo $category['cat_name']; ?>&id=<?php echo $category['cat_id']; ?>" style="float: right;margin-right: 10px; color: #999; font-size: 12px;">更多>></a></h2>
			</div>
		</div>
		<!-- 商品 -->
		<ul class="js-goods-list sc-goods-list pic clearfix size-1">
			<?php
			foreach($products as $value) {
			?>
			<li class="goods-card goods-list small-pic card">
				<a href="./good.php?id=<?php echo $value['product_id'] ?>" class="js-goods link clearfix" title="<?php echo $value['name'] ?>">
				<div class="photo-block"><img class="goods-photo js-goods-lazy" src="<?php echo $value['image'] ?>" style="display:inline;"></div>
				<div class="info clearfix info-title info-price btn1">
					<p class="goods-title"><?php echo $value['name'] ?></p>
					<p class="goods-sub-title c-black hide"></p>
					<p class="goods-price"><em>￥<?php echo $value['price']; ?></em></p>
					<p class="goods-price-taobao hide"></p>
				</div>
				<div class="goods-buy btn1 info-no-title"></div>
				<div class="js-goods-buy buy-response" data-id="<?php echo $value['product_id'] ?>"></div>
				</a></li>
			<?php
			}
			?>
		</ul>
	</div>
</div>
<div class="content">
	<div class="content-body" style="padding:10px;background:#fff;">
		<?php
		if(empty($category_banners[$val['cat_id']])) {
			echo '请在广告分类 wap_category_banner 下添加名称为 '.$val['cat_id'] .' 的广告！';
		} else {
			$value = $category_banners[$val['cat_id']];
			echo '<a href="'.$value['url'].'"><img src="'.$value['pic'].'" alt="" /></a>';
		}
		?>
	</div>
</div>

<?php	
}
}
?>
<!--<div class="content">
	<div class="content-body">
		<div>
			<div class="custom-title text-left">
				<h2 class="title">自营专区<a href="./category.php?keyword=母婴系列&amp;id=79" style="float: right;margin-right: 10px; color: #999; font-size: 12px;">更多&gt;&gt;</a></h2>
			</div>
		</div>
		<ul class="js-goods-list sc-goods-list pic clearfix size-1">
						<li class="goods-card goods-list small-pic card">
				<a href="./good.php?id=1526" class="js-goods link clearfix" title="小灵当乳木果润护儿童洗手液320g">
				<div class="photo-block"><img class="goods-photo js-goods-lazy" src="http://www.awamall.cn/upload/images/000/000/091/201512/56837fcdc35a7.jpg" style="display:inline;"></div>
				<div class="info clearfix info-title info-price btn1">
					<p class="goods-title">小灵当乳木果润护儿童洗手液320g</p>
					<p class="goods-sub-title c-black hide"></p>
					<p class="goods-price"><em>￥15.80</em></p>
					<p class="goods-price-taobao hide"></p>
				</div>
				<div class="goods-buy btn1 info-no-title"></div>
				<div class="js-goods-buy buy-response" data-id="1526"></div>
				</a></li>
						<li class="goods-card goods-list small-pic card">
				<a href="./good.php?id=1525" class="js-goods link clearfix" title="小灵当橄榄蛋白润护儿童洗沐二合一(泡泡)280g">
				<div class="photo-block"><img class="goods-photo js-goods-lazy" src="http://www.awamall.cn/upload/images/000/000/091/201512/56837fee64f2c.jpg" style="display:inline;"></div>
				<div class="info clearfix info-title info-price btn1">
					<p class="goods-title">小灵当橄榄蛋白润护儿童洗沐二合一(泡泡)280g</p>
					<p class="goods-sub-title c-black hide"></p>
					<p class="goods-price"><em>￥33.80</em></p>
					<p class="goods-price-taobao hide"></p>
				</div>
				<div class="goods-buy btn1 info-no-title"></div>
				<div class="js-goods-buy buy-response" data-id="1525"></div>
				</a></li>
						<li class="goods-card goods-list small-pic card">
				<a href="./good.php?id=1515" class="js-goods link clearfix" title="小灵当橄榄蛋白营养儿童舒润霜50g">
				<div class="photo-block"><img class="goods-photo js-goods-lazy" src="http://www.awamall.cn/upload/images/000/000/091/201512/568380ee77253.jpg" style="display:inline;"></div>
				<div class="info clearfix info-title info-price btn1">
					<p class="goods-title">小灵当橄榄蛋白营养儿童舒润霜50g</p>
					<p class="goods-sub-title c-black hide"></p>
					<p class="goods-price"><em>￥15.80</em></p>
					<p class="goods-price-taobao hide"></p>
				</div>
				<div class="goods-buy btn1 info-no-title"></div>
				<div class="js-goods-buy buy-response" data-id="1515"></div>
				</a></li>
						<li class="goods-card goods-list small-pic card">
				<a href="./good.php?id=1511" class="js-goods link clearfix" title="小灵当乳木果蛋白柔嫩滋养儿童洗面奶(软管)60g">
				<div class="photo-block"><img class="goods-photo js-goods-lazy" src="http://www.awamall.cn/upload/images/000/000/091/201512/56838170df99f.jpg" style="display:inline;"></div>
				<div class="info clearfix info-title info-price btn1">
					<p class="goods-title">小灵当乳木果蛋白柔嫩滋养儿童洗面奶(软管)60g</p>
					<p class="goods-sub-title c-black hide"></p>
					<p class="goods-price"><em>￥13.50</em></p>
					<p class="goods-price-taobao hide"></p>
				</div>
				<div class="goods-buy btn1 info-no-title"></div>
				<div class="js-goods-buy buy-response" data-id="1511"></div>
				</a></li>
					</ul>
	</div>
</div>-->
<div class="content">
	<div class="content-body">
		<div class="index-rec J_reclist">
			<div class="bord"></div>
			<div class=" title_list">
				<ul class="title_tab" id="example-one">
					<li class="nar_shop product_on current_page_item" style="width:50%"><a>附近店铺</a> </li>
					<li class="nar_product" style="width:50%"><a>附近商品</a></li>
				</ul>
			</div>
			<ul class="product_list js-near-content" style="overflow:hidden">
				<li class="pro_shop" style="display:block;">
					<div class="home-tuan-list js-store-list near-store-list" data-type="default">
						<?php
						/*if ($stores) {
						foreach ($stores as $key => $value) {
							if ($key >= 4) {
								break;
							}
							?>
								<a href="<?php echo $value['url'] ?>" class="item Fix">
								<div class="cnt"><img class="pic" src="<?php echo $value['logo'] ?>">
									<div class="wrap">
										<div class="wrap2">
											<div class="content">
												<div class="shopname"><?php echo $value['name'] ?></div>
												<div class="title"><?php echo msubstr($value['intro'], 0, 20,
														'utf-8') ?></div>
												<div class="info"><span><i></i>请设置位置</span></div>
											</div>
										</div>
									</div>
								</div>
								</a>
								<?php
						}
					}*/
						?>
					</div>
				</li>
				<li class="pro_product">
					<div class="home-tuan-list js-goods-list near-goods-list" data-type="default">
						<?php
						/*if ($product_list) {
							foreach ($product_list as $value) {
								?>
									<a href="./good.php?id=<?php echo $value['product_id'] ?>" class="item Fix">
									<div class="cnt"><img class="pic" src="<?php echo $value['image'] ?>">
										<div class="wrap">
											<div class="wrap2">
												<div class="content">
													<div class="shopname"><?php echo $value['name'] ?></div>
													<div class="title"><?php echo msubstr($value['intro'], 0, 20,
															'utf-8'); ?></div>
													<div class="info"> <span class="symbol">yen</span> <span class="price"><?php echo $value['price'] ?></span> <del class="o-price">yen
														<?php $value['market_price'] =
																($value['price'] >= $value['market_price'] ? $value['price']
																	: $value['market_price']);
															echo $value['market_price']; ?>
														</del> <span class="sale">立减<?php echo $value['market_price'] -
																$value['price'] ?>元</span> <span class="distance"></span> </div>
												</div>
											</div>
										</div>
									</div>
									</a>
									<?php
							}
						}*/
						?>
					</div>
				</li>
			</ul>
		</div>
	</div>
</div>
<script>
$(function () {
	$(".nar_shop").click(function () {
		aaa('pro_product', 'pro_shop');
		$(this).addClass("product_on").siblings().removeClass("product_on")
	});
//	$(".nar_activity").click(function () {
//		aaa('pro_product', 'pro_shop', 'pro_activity');
//		$(this).addClass("product_on").siblings().removeClass("product_on")
//	});
	$(".nar_product").click(function () {
		aaa('pro_shop', 'pro_product');
		$(this).addClass("product_on").siblings().removeClass("product_on")
	});

	function aaa(sClass1, sClass2) {
		$('.' + sClass1).hide();
		$('.' + sClass2).show();
	}
});
</script>
<?php include display('public_search'); ?>
<?php include display('public_menu'); ?>
<?php echo $shareData; ?>
</body>
</html>