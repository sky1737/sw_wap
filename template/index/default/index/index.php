<?php if(!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title><?php echo $config['site_name']; ?></title>
<meta name="keywords" content="<?php echo $config['seo_keywords'] ?>" />
<meta name="description" content="<?php echo $config['seo_description'] ?>" />
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link href="<?php echo TPL_URL; ?>css/style.css" type="text/css" rel="stylesheet">
<link href="<?php echo TPL_URL; ?>css/index.css" type="text/css" rel="stylesheet">
<link href="<?php echo TPL_URL; ?>css/public.css" type="text/css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/index-slider.v7062a8fb.css">
<script type="text/javascript" src="<?php echo $config['site_url']; ?>/static/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $config['site_url']; ?>/static/js/jquery.lazyload.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/animate.css">
<script src="<?php echo TPL_URL; ?>js/jquery.nav.js"></script>
<script src="<?php echo TPL_URL; ?>js/distance.js"></script>
<script src="<?php echo TPL_URL; ?>js/common.js"></script>
<script src="<?php echo TPL_URL; ?>js/index.js"></script>
<script src="<?php echo TPL_URL; ?>js/myindex.js"></script>
<script src="<?php echo TPL_URL; ?>js/index2.js"></script>
<!--[if lt IE 9]>
<script src="<?php echo TPL_URL;?>js/html5shiv.min-min.v01cbd8f0.js"></script>
<![endif]-->
<!--[if IE 6]>
<script src="<?php echo TPL_URL;?>js/DD_belatedPNG_0.0.8a.js" mce_src="<?php echo TPL_URL;?>js/DD_belatedPNG_0.0.8a.js"></script>
<script type="text/javascript">DD_belatedPNG.fix('*');</script>
<style type="text/css">
	/*body {behavior: url("csshover.htc");}*/
</style>
<![endif]-->
<script type="text/javascript">
	$(function () {
		$.get("index.php?c=index&a=user", function (data) {
			try {
				if (data.status == true) {
					var login_info = '<em>Hi，' + data.data.nickname + '</em>';
					login_info += '<a target="_top" href="index.php?c=account&a=logout" class="sn-register">退出</a>';
					$("#login-info").html(login_info);

					$("#header_cart_number").html(data.data.cart_number);

					if (ParesInt(data.data.cart_number) > 99) {
						data.data.cart_number = "99";
					}

					$(".mui-mbar-tab-sup-bd").html(data.data.cart_number);
				}
			} catch (e) {

			}
		}, "json");

		if ($("#location_long_lat").length > 0) {
			$.getJSON("/index.php?c=index&a=ajax_loaction", function (data) {
				try {
					if (data.status == true) {
						$("#location_long_lat").html(data.msg);
						$("#location_long_lat").attr("title", data.msgAll[0]);
					}
				} catch (e) {
				}
			});
		}
	});
</script>
<style type="text/css">
.content .content_shear .content_list_shear ul li .content_txt { word-wrap: break-word }
.content .content_list_nameplat .content_nameplate_left img { width: 305px; }
.content .content_shear .content_list_shear ul li .content_txt { word-wrap: break-word }
.content .content_list_nameplat .content_nameplate_left img { width: 305px; }
.content .content_activity_asid.hot_activity dl dd .content_asit_img { margin-top: 5px; }
.content .content_activity_asid.hot_activity dl dd .content_asit_img img { width: 65px; height: 65px; }
.content .content_activity_asid.hot_activity dl dd { padding: 12px 5px; border-top: 1px dashed #d9d9d9; border-bottom: 0; }
.banner .banner_content .banner_content_asid .banner_content_txt { padding-bottom: 6px; }
.banner .banner_content .banner_content_asid .banner_content_asid_bottom { padding: 6px 12px 0; }
/*.banner .banner_content .banner_content_asid .banner_content_asid_center img{height:167px;width:auto}*/
.fontcolor { color: #C81623 !important; }
.daohang { float: left; font-size: 12px; line-height: 23px; width: auto; display: block; white-space: nowrap; text-decoration: none; }
</style>
</head>

<body>
<div class="header">
	<div role="navigation" id="site-nav">
		<div id="sn-bg">
			<div class="sn-bg-right"></div>
		</div>
		<div id="sn-bd"> <b class="sn-edge"></b>
			<div class="sn-container">
				<p class="sn-back-home"><i class="mui-global-iconfont"></i><a href="#"></a></p>
				<p class="sn-login-info" id="login-info"> <em>Hi，欢迎来<?php echo option('config.site_name'); ?></em> <a target="_top" href="<?php echo url('account:login') ?>" class="sn-login">请登录</a> <a target="_top" href="<?php echo url('account:login') ?>" class="sn-register">免费注册</a> </p>
				<ul class="sn-quick-menu">
					<li class="sn-cart mini-cart"><i class="mui-global-iconfont"></i> <a rel="nofollow" href="<?php echo url('cart:one') ?>" class="sn-cart-link"
					   id="mc-menu-hd">购物车<span class="mc-count mc-pt3" id="header_cart_number">0</span>件</a> </li>
					<li class="sn-mobile"> <a href="<?php echo url('user:account:order') ?>">我的订单</a> </li>
					<li class="sn-mytaobao menu-item j_MyTaobao">
						<div class="sn-menu"> <a rel="nofollow" target="_top" href="<?php echo url('user:account:index') ?>" class="menu-hd"
						   tabindex="0" aria-haspopup="menu-2" aria-label="右键弹出菜单，tab键导航，esc关闭当前菜单">我的账户<b></b></a>
							<div class="menu-bd" role="menu" aria-hidden="true" id="menu-2">
								<div id="myTaobaoPanel" class="menu-bd-panel"> <a rel="nofollow" target="_top" href="<?php echo url('user:account:info') ?>">个人设置</a><!-- <a rel="nofollow" target="_top" href="<?php echo url('user:account:password') ?>">修改密码</a>--> <a rel="nofollow" target="_top" href="<?php echo url('user:account:address') ?>">收货地址</a> </div>
							</div>
						</div>
					</li>
					<li class="sn-favorite menu-item">
						<div class="sn-menu"> <a rel="nofollow" href="<?php echo url('user:account:collect_store') ?>" class="menu-hd" tabindex="0">我的收藏<b></b></a>
							<div class="menu-bd" role="menu" aria-hidden="true" id="menu-4">
								<div class="menu-bd-panel"> <a rel="nofollow" href="<?php echo url('user:account:collect_goods') ?>">收藏的宝贝</a> <a rel="nofollow" href="<?php echo url('user:account:collect_store') ?>">收藏的店铺</a> </div>
							</div>
						</div>
					</li>
					<li class="sn-mobile"> <a href="javascript:" class="sn-mobile-hover"> <i class="mui-global-iconfont-mobile"></i>微信版
						<div class="sn-qrcode">
							<div class="sn-qrcode-content"> <img src="<?php echo option('config.wechat_qrcode'); ?>" width="175px" height="175px"></div>
							<p>扫一扫，定制我的微店！</p>
							<b></b> </div>
						</a> </li>
					<li class="sn-separator"></li>
					<li class="sn-favorite menu-item">
						<div class="sn-menu"> <span rel="nofollow" href="javascript:void(0)" class="menu-hd" tabindex="0">卖家中心<b></b></span>
							<div class="menu-bd" role="menu" aria-hidden="true" id="menu-4">
								<div class="menu-bd-panel"> <a rel="nofollow" href="<?php echo url('user:store:select') ?>">我的店铺</a> <a rel="nofollow" href="<?php echo url('user:store:index') ?>">管理店铺</a> </div>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="header_nav">
		<div class="header_logo cursor"
		 onclick="javascript:location.href='<?php echo option('config.site_url'); ?>'"><img
			src="<?php echo $config['site_logo'] ?>"></div>
		<div class="header_search">
			<form class="pigSearch-form clearfix" onsubmit="return false" name="searchTop" action="" target="_top">
				<input type="hidden" name="st" id="searchType" value="product" />
				<!--<div class="header_search_left"><font>商品</font><span></span>
				  <div class="header_search_left_list">
					<ul>
					  <li listfor="product" <?php if(MODULE_NAME != 'search' && ACTION_NAME != 'store') {echo 'selected="selected"';} ?>><a href="javascript:">商品</a></li>
					  <li listfor="shops" <?php if(MODULE_NAME == 'search' && ACTION_NAME == 'store') {echo 'selected="selected"';} ?>><a href="javascript:void(0)">店铺</a></li>
					</ul>
				  </div>
				</div>-->
				<div class="header_search_input">
					<input class="combobox-input" name="" class="input" type="text" placeholder="请输入商品名、称地址等">
				</div>
				<div class="header_search_button sub_search">
					<button><span></span> 搜索</button>
				</div>
				<div style="clear:both"></div>
			</form>
			<ul class="header_search_list">
				<?php
		if(count($search_hot)) {
			foreach ($search_hot as $k => $v) {
				echo '<li' . ($v['type'] ? ' class="hotKeyword"' : '') . '><a href="' . $v['url'] . '">' .
					$v['name'] . '</a></li>';
			}
		}
		?>
			</ul>
		</div>
		<div class="header_shop">
			<?php
			if(empty($pc_top_right)) {
				echo '请添加标识为 pc_top_right 的广告';
			} else {
				echo '<a href="' . $pc_top_right['url'] . '"><img src="' . $pc_top_right['pic'] . '"></a>';
			}
			?>
		</div>
	</div>
</div>
<div class="nav indexpage">
	<div class="nav_top">
		<div class="nav_nav ">
			<div class="nav_nav_mian"><span></span>所有商品分类</div>
			<ul class="nav_nav_mian_list nav_nav_mian_list_1" style="top:37px; height: 328px;">
				<?php
				foreach ($categoryList as $k => $v) {
					echo '<li><a href="' . url_rewrite('category:index', array('id' => $v['cat_id'])) .
						'">' . $v['cat_name'] . //<span class="woman" style="background:url(' . $v[cat_pc_pic] . ')"></span>
						'</a><div class="nav_nav_subnav"><div class="nav_nav_mian_list_left">' .
							'<div><a style="font-size: 14px; color:#C81623;text-decoration:none; font-weight: bold;" href="' . url_rewrite('category:index', array('id' => $v['cat_id'])) . '">' .
						$v['cat_name'] . ' </a></div>';
					if($v['larray']) {
						foreach ($v['larray'] as $k1 => $v1) {
							echo '<em><a class="daohang" href="' . url_rewrite('category:index', array('id' => $v1['cat_id'])) . '">' .
								$v1['cat_name'] . '&nbsp;&nbsp;|&nbsp;&nbsp;</a></em>';
						}
					}
					echo '
					</div></div></li>';
				}
				?>
			</ul>
		</div>
		<ul class="nav_list">
			<li><a href="<?php echo option('config.site_url'); ?>" class="nav_list_curn">首页</a></li>
			<?php
			foreach ($navList as $k => $v) {
				if($k < 7) {
					echo '<li><a href="' . $v['url'] . '" target="_blank">' . $v['name'] . '</a></li>';
				}
			} ?>
		</ul>
	</div>
</div>
<script>
	$(function(){
		$('.nav_nav_subnav').on('mouseover',function(){
			$(this).prev('a').addClass('fontcolor');
		});
		$('.nav_nav_subnav').on('mouseout',function(){
			$(this).prev('a').removeClass("fontcolor");
		})
	});
</script>
<div class="banner">
	<div class="banner_arct">
		<div class="banner_content">
			<div class="banner_content_main"> 
				<!--  -->
				<div class="content__cell content__cell--slider">
					<div class="component-index-slider">
						<div class="index-slider ui-slider log-mod-viewed"> 
							<!--<div class="pre-next">
							<a style="opacity: 0.8; display: none;" href="javascript:;" hidefocus="true" class="mt-slider-previous sp-slide--previous"></a>
							<a style="opacity: 0.8; display: none;" href="javascript:;" hidefocus="true" class="mt-slider-next sp-slide--next"></a></div>-->
							<div class="head ccf">
								<ul class="trigger-container ui-slider__triggers mt-slider-trigger-container">
									<?php
									if(empty($adList)) {
										echo '<li>请添加标识为 pc_slide 的广告</li>';
									} else {
										foreach ($adList as $adk => $adv) {
											echo '<li class="mt-slider-trigger ' .
												($adk == '0' ? 'mt-slider-current-trigger' : '') . '">' . $adv['name'] .
												'</li>';
										}
									} ?>
									<div style="clear:both"></div>
								</ul>
							</div>
							<ul class="content">
								<?php
								if(empty($adList)) {
										echo '<li>请添加标识为 pc_slide 的广告</li>';
								} else {
									foreach ($adList as $adk => $adv) {
										echo '<li class="cf" style="opacity: 1; ' .
											($adk == '0' ? 'display: block;' : 'display:none;') . '"><a href="' .
											$adv['url'] . '"><img src="' . $adv['pic'] . '"></a></li>';
									}
								} ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="banner_content_asid">
				<div class="banner_content_asid_center">
					<?php
					if(empty($adList_right)) {
						echo '请添加标识为 pc_slide_right 的广告';
					} else {
						echo '<a href="' . $adList_right[0]['url'] . '"><img src="' . $adList_right[0]['pic'] .
							'" /></a>';
					}
					?>
				</div>
			</div>
		</div>
		<div style="clear:both;"></div>
		<div id="lunbo_top">
			<div id="lunbo_left">
				<ul class="list">
					<?php if(empty($ad_activity_left)) {
						echo '<li>请添加标识为 pc_activity_left 的广告</li>';
						} else {
						echo '<li><a href="'.$ad_activity_left[0]['url'].'"><img src="'.$ad_activity_left[0]['pic'].'"></a></li>';	
						}?>
				</ul>
			</div>
			<div id="lunbo"><a class="prev" href="javascript:;"><</a> <a class="next" href="javascript:;">></a>
				<div class="wrap">
					<ul class="list">
						<?php
					if(empty($ad_activity_list)){
						echo '<li>请添加标识为 pc_activity 的广告</li>';
					} else {
						foreach ($ad_activity_list as $act) {
							echo '<li><a href="' . $act['url'] . '"><img src="' . $act['pic'] . '"/></a></li>';
						}	
					}
					?>
					</ul>
				</div>
				<div style="clear:both"></div>
			</div>
		</div>
	</div>
</div>
<div class="content index_content"> 
	<!--边栏楼层导航-->
	<div class="gd_box" style="position: absolute; top: 0;  margin-left: -80px;  z-index:99;">
		<div id="gd_box">
			<div id="gd_box1">
				<div id="nav">
					<ul>
						<li class="gd_nameplate"><a class="f4" onclick="scrollToId('#f4');"><span>热门活动</span></a></li>
						<?php
						foreach ($hot_products['category'] as $k => $v) {
							echo '<li class="gd_hot"><a style="background:url(' . $v['cat_pic'] .
								') no-repeat center center;" class="f0' . $v['cat_id'] . '" onclick="scrollToId(\'#f0' .
								$v['cat_id'] . '\');"><span>' . $v['cat_name'] . '</span></a></li>';
						}
						/*if(!empty($store_session)) {
							echo '<li class="gd_self "><a class="f1" onclick="scrollToId(\'#f1\');"><span>自营商品</span></a></li>';;
						}*/
						?>
						<li class="gd_shop"><a class="f2" onclick="scrollToId('#f2');"> <span>周边店铺</span></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!--边栏楼层导航--> 
	<!--热门品牌/最新活动-->
	<style type="text/css">
	.brands {height:430px;overflow:hidden;text-align:left;}
	.brands h3 {height:28px;padding:10px;line-height:28px;font-size: 20px;font-weight:normal;}
	.brands .brands_big {width:380px;float:left;height:382px;}
	.brands .brands_list {float:right;height:381;width:823px;}
	.brands .brands_list ul {list-style:none;background:#eee;padding:1px 0 0 1px;}
	.brands .brands_list ul li {float:left; height:126px;width:136px; margin: 0 1px 1px 0; background:#fff; }
	.brands .brands_list ul li img {width:136px;height:126px;}
	</style>
	<div class="content_commodity content_nameplate" id="f4" style="margin-top: 30px; margin-bottom:0px;">
		<div class="brands">
			<h3>品牌盛宴</h3>
			<?php
			if($hot_brands) {
			?>
			<div class="brands_big"><img src="<?php echo $hot_brands[0]['qrcode']; ?>" width="100%" height="100%" /></div>
			<div class="brands_list">
				<ul>
					<?php
					foreach ($hot_brands as $key => $val) {
						$key++;
						echo '<li><a href="' . $val['url'] . '""><img src="' . $val['pic'] . '" alt="' . $val['name'] . '" data-url="'.$val['qrcode'].'" /></a></li>';
					}
					?>
				</ul>
			</div>
			<script type="text/javascript">
			$(function() {
				$('.brands_list ul li img').each(function(){
					var src = $(this).attr('src');
					var qrcode = $(this).data('url');
					$(this).hover(function(){
						$('.brands_big img').attr('src',qrcode);
					},function(){
						$('.brands_big img').attr('src', src);
					});
				});
			});
		</script>
			<?php } else {
				echo '请添加标识为 pc_hot_brand 的广告！';
			}?>
		</div>
		<!--<div class="content_list_nameplat" style="height:430px;">
      <div class="readme">
        <div class="lrtk">
          <?php
		// print_r($hotbrands);
		if($hot_brands) {
			foreach ($hot_brands as $key => $val) {
				$key++;
				echo '<a href="' . $val['url'] . '" class="box' . ($key < 10 ? '0' . $key : $key) . '"><img src="' . $val['pic'] . '" alt="' . $val['name'] . '" data-url="'.$val['qrcode'].'" /></a>';
			}
		}
		?>
        </div>
        
      </div>
    </div>--> 
	</div>
	<!--热门品牌/最新活动-->
	<?php
	foreach ($hot_products['category'] as $k => $v) {
	//echo '<li class="hot_li_category" data_li_id="' . $v['cat_id'] . '"><a href="javascript:void(0)">' . $v['cat_name'] . '</a></li>';?>
	<div class="content_commodity content_woman hot_category_content" data-id="<?php echo $v['cat_id']; ?>" id="f0<?php echo $v['cat_id']; ?>">
		<div class="content_commodity_title">
			<div class="content_commodity_title_right"> <a href="<?php echo url_rewrite('category:index', array('id' => $v['cat_id'])); ?>">更多&gt;&gt;</a> 
				<!--<a href="<?php echo url('store:store_list',	array('order' => 'collect')) ?>">更多&gt;&gt;</a> --> 
			</div>
			<div class="content_commodity_title_left"> <a href="<?php echo url_rewrite('category:index', array('id' => $v['cat_id'])); ?>"><span></span><?php echo $v['cat_name']; ?></a> </div>
			<div class="hot_category_sales_category content_commodity_title_content" data-id="<?php echo $v['cat_id']; ?>">
				<ul class="tab tabs">
					<?php
					echo '<li class="hot_li_category content_curn" data_li_id="'.$v['cat_id'].'"><a href="javascript:void(0)">全部热卖</a></li>';
					//var_dump($v['larray']);
					foreach ($v['larray'] as $l => $ll) {
						echo '<li class="hot_li_category"><a href="' . url_rewrite('category:index', array('id' => $ll['cat_id'])) . '">' . $ll['cat_name'] .'</a></li>';
						// 切换修改为跳转
						//echo '<li class="hot_li_category" data_li_id="'.$ll['cat_id'].'"><a href="javascript:void(0)">' . $ll['cat_name'] .'</a></li>';
						if($l > 8)
							break;
					}
					?>
				</ul>
			</div>
		</div>
		<div class="content_list">
			<div style="height:100%;">
				<ul class="content_list_ul hot_ul_product hot_ul_product_<?php echo $v['cat_id']; ?>">
				</ul>
				<?php
				// <li class="content_curn"><a href="javascript:void(0)">全部热卖</a></li>
				//var_dump($v['larray']);
				foreach ($v['larray'] as $l => $ll) {
					echo '<ul class="content_list_ul hot_ul_product hot_ul_product_'.$ll['cat_id'].'" style="height:530px"></ul>';
					if($l > 8)
						break;
				}
				?>
			</div>
		</div>
	</div>
	<?php
	}
	/*if(!empty($store_session)) {?>
  <div class="content_commodity content_woman hot_category_content" id="f1">
    <div class="content_commodity_title">
      <div class="content_commodity_title_right"> <a href="<?php echo url_rewrite('store:index', array('id' => $store_session['store_id'])); ?>">更多&gt;&gt;</a> 
        <!--<a href="<?php echo url('store:store_list',	array('order' => 'collect')) ?>">更多&gt;&gt;</a> --> 
      </div>
      <div class="content_commodity_title_left"> <a href="<?php echo url_rewrite('store:index', array('id' => $store_session['store_id'])); ?>"><span></span>自营区域</a> </div>
      <div class="hot_category_sales_category content_commodity_title_content"">
        <ul class="tab tabs">
          <li class="hot_li_category content_curn"><a href="<?php echo url_rewrite('store:index', array('id' => $store_session['store_id'])); ?>">全部自营</a></li>
        </ul>
      </div>
    </div>
    <div class="content_list">
      <div style="height:100%;">
        <ul class="content_list_ul hot_ul_product hot_ul_product_self">
        </ul>
      </div>
    </div>
  </div>
  <?php	
	}*/
	?>
	<style type="text/css">
	.near_left,
	.near_right { width: 46px;height: 66px;background: url(template/index/default/images/weidian_icon.png);position: absolute;}
	.near_left { left: -56px;top: 154px;background-position: 430px -175px;}
	.near_right{ right:-56px;top: 154px; background-position: 386px -175px;}
	</style>
	<div class="content_commodity content_shop" id="f2" style="overflow: visible;position: relative;">
		<div class="content_commodity_title ">
			<div class="content_commodity_title_left"><a target="_blank" href="<?php echo url('search:store') ?>"><span></span>周边店铺</a> </div>
			<div class="content_commodity_title_content"> </div>
			<div class="content_commodity_title_right"><a target="_blank" href="<?php echo url('search:store') ?>">更多&gt;&gt;</a> </div>
		</div>
		<div class="near_left"></div>
		<div class="near_right"></div>
		<div class="content_list nearshop" style="height:341px; overflow:hidden; position:relative;">
			<ul class="content_list_ul" style="height:341px;position:absolute;left:0;">
				<?php
				foreach ($nearshops as $k => $v) {
					echo '<li><a href="' . $v['pcurl'] .
						'"><div class="content_list_img"><img class="lazys" data-original="' . TPL_URL .
						'images/ico/grey.gif" src="' . $v['logo'] .
						'" onloads="AutoResizeImage(217,144,this)" width="217px" height="144px">  <div class="content_list_erweima"><div class="content_list_erweima_img"><img src="' .
						$config['site_url'] . '/source/qrcode.php?type=home&id=' . $v['store_id'] .
						'"></div><div class="content_shop_name">' . $v['name'] .
						'</div></div></div><div class="content_list_txt"><div class="content_shop_name">' . $v['name'] .
						'</div></div>';
	
					if($WebUserInfo['lng']) {
						if($v['juli']) {
							echo '<div class="content_list_txt"><div class="content_list_distance">周边' .
								ceil($v['juli'] / 1000) . 'km内</div><div class="content_list_add"><span></span>' .
								sprintf("%.2f", $v['juli'] / 1000) . 'km </div></div>';
						}
					}
					else {
						echo '<div class="content_list_txt"><div class="content_list_distance">请设置您的位置</div><div class="content_list_add"><span></span>0km</div></div>';
					}
					echo '</a></li>';
				}
				?>
			</ul>
		</div>
		<script type="text/javascript">
		$(function(){
			var oPic = $('.nearshop').find('ul');
			var oImg = oPic.find('li');
			var oLen = oImg.length;
			var oLi = oImg.width();
			var prev = $(".near_left");
			var next = $(".near_right");
			
			oPic.width(oLen * 226);//计算总长度
			var iNow = 0;
			var iTimer=null;
			prev.click(function(){
				if(iNow > 0){  
					iNow--;
				}
				ClickScroll();
			});
			next.click(function(){
				if(iNow < oLen-3){ 
					iNow++
				}
				ClickScroll();
			});
			
			function ClickScroll(){
				oPic.animate({left:-iNow*226});
			}
		});
		</script>
	</div>
	<?php /*<div class="content_list_shear">
		<?php if(is_array($comment)) { ?>
		<ul class="content_list_ul content_shear_left">
			<?php foreach ($comment as $k => $v) { ?>
			<?php if($k < 3) { ?>
			<li <?php if($k == 0) { ?>class="content_shear_left_big"<?php } ?>> <a href="<?php echo $v['ilink']; ?>"> <img <?php if($k == 0) { ?>width="390px" height="390px" <?php } else { ?>width="185px" height="185px"<?php } ?> src="<?php echo $v['file'] ?>"> </a>
				<div class="content_show"> 
					<!--<div class="content_show_xin"><span></span>789</div>-->
					<div class="content_txt"><a href="<?php echo $v['ilink']; ?>"><?php echo msubstr($v['content'], 0, 29, 'utf-8'); ?></a></div>
				</div>
			</li>
			<?php } ?>
			<?php } ?>
		</ul>
		<ul class="content_list_ul content_shear_content">
			<?php foreach ($comment as $k => $v) { ?>
			<?php if($k > 2 && $k < 9) { ?>
			<li><a href="<?php echo $v['ilink']; ?>"><img width="185x" height="185x"
																  src="<?php echo $v['file'] ?>"></a>
				<div class="content_show">
					<div class="content_txt"><a
									href="<?php echo $v['ilink']; ?>"><?php echo msubstr($v['content'], 0, 29,
										'utf-8'); ?></a></div>
				</div>
			</li>
			<?php } ?>
			<?php } ?>
		</ul>
		<ul class="content_list_ul content_shear_right">
			<?php foreach ($comment as $k => $v) { ?>
			<?php if($k > 8) { ?>
			<li <?php if($k == 11) { ?> class="content_shear_left_big" <?php } ?>><a
							href="<?php echo $v['ilink']; ?>"><img <?php if($k == 11) { ?>width="390px"
																   height="390px"<?php }
							else { ?> width="185x" height="185x"<?php } ?>
																   src="<?php echo $v['file'] ?>"></a>
				<div class="content_show"> 
					<!--<div class="content_show_xin"><span></span>789</div>-->
					<div class="content_txt"><a
									href="<?php echo $v['ilink']; ?>"><?php echo msubstr($v['content'], 0, 29,
										'utf-8'); ?></a></div>
				</div>
			</li>
			<?php } ?>
			<?php } ?>
		</ul>
		<?php } ?>
	</div>*/ ?>
</div>
</div>
<?php include display('public:footer'); ?>
</body>
</html>