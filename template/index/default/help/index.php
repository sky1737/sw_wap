<?php if(!defined('TWIKER_PATH')) exit('deny access!');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<title><?php echo ($help_categories[$cat_fid]['children'][$cat_id]['cat_name']); ?>-<?php echo ($help_categories[$cat_fid]['cat_name']); ?>-<?php echo $config['seo_title'] ?></title>
<meta name="keywords" content="<?php echo $config['seo_keywords'] ?>" />
<meta name="description" content="<?php echo $config['seo_description'] ?>" />
<link rel="icon"  href="favicon.ico" type="image/x-icon" />
<link href="<?php echo TPL_URL;?>css/public.css" type="text/css" rel="stylesheet" />
<link href="<?php echo TPL_URL;?>css/style.css" type="text/css" rel="stylesheet" />
<link href="<?php echo TPL_URL;?>css/index.css" type="text/css" rel="stylesheet" />
<link href="<?php echo TPL_URL;?>css/fancybox.css" type="text/css" rel="stylesheet" />
<link href="<?php echo TPL_URL;?>css/comm.css" rel="stylesheet" type="text/css">
<link href="<?php echo TPL_URL;?>css/article.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo $config['site_url'];?>/static/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $config['site_url'];?>/static/js/jquery.form.min.js"></script>
<script type="text/javascript" src="<?php echo TPL_URL;?>js/common.js"></script>
<script src="<?php echo TPL_URL;?>js/jquery.fly.min.js"></script>
<script src="<?php echo TPL_URL;?>js/bootstrap.min.js"></script>
<script src="<?php echo TPL_URL;?>js/jquery.fancybox-1.3.1.pack.js"></script>
<script type="text/javascript" src="<?php echo TPL_URL;?>js/jquery.nav.js"></script>
<!--[if lt IE 9]>
<script src="js/html5shiv.min-min.v01cbd8f0.js"></script>
<![endif]-->
<!--[if IE 6]>
<script  src="js/DD_belatedPNG_0.0.8a.js" mce_src="js/DD_belatedPNG_0.0.8a.js"></script>
<script type="text/javascript">DD_belatedPNG.fix('*');</script>
<style type="text/css"> 
body{ behavior:url("csshover.htc");}
</style>
<![endif]-->
<style>
.shopping .shopping_left .shopping_menu .shopping_menu_right .selected { border: 1px solid #F63b3b; color: #f63b3b; }
.item_property_detail .normal { cursor: pointer; }
.shopping .shopping_left .shopping_menu .shopping_menu_right .notallowed { cursor: not-allowed; background: #FFF; color: #e5e5e5; border: 1px #e5e5e5 dashed; }
.shopping .shopping_left .shopping_menu .shopping_menu_right .notallowed img { opacity: 0.3 }
</style>
<script>
var has_property = "<?php echo $product['has_property'] ?>";
var product_sku = <?php echo json_encode($product_sku_list) ?>;
var is_sku = "<?php echo $product['show_sku'] ?>";
var comment_url = "<?php echo url('comment:index') ?>";
var comment_add = "<?php echo url('comment:add') ?>";
var is_login = <?php echo $_SESSION['user'] ? 'true' : 'false' ?>;
var is_logistics = <?php echo $order_store['open_logistics'] ? 'true' : 'false' ?>;
var is_selffetch = <?php echo $order_store['buyer_selffetch'] ? 'true' : 'false' ?>;
</script>
</head>

<body style="background:#fff;">
<?php include display( 'public:header');?>

<!-- 主体部分开始 -->
<div class="help_wrap">
	<div class="help_top clearfix"> <span class="f18 fwr fl help_tit"><a  href="#">帮助中心</a></span> <span class="deep_red f13 fwr fl">Hi~，欢迎您来到帮助中心。</span>
		<div class="fr u_search">
			<form action="<?php url('help:index'); ?>" method="GET">
				<input type="text" class="J_sch_in u_sch_in fl" value="请输入关键字" name="keyword">
				<input type="submit" class="btns btn_gray_5128 fl u_sch_btn" value="搜索">
			</form>
		</div>
	</div>
	<div class="help_main clearfix">
		<div class="help_leftside fl">
			<?php
			foreach($help_categories as $val) {
				echo '<div class="h_menu_item"><div class="h_menu_tit">'.$val['cat_name'].'</div><div class="h_menu_con">';
				if(!empty($val['children'])) {
					echo '<div class="h_multi_menu">';
					foreach($val['children'] as $c) {
						echo '<div class="multi_menu_item"><a href="/help/'.$c['cat_fid']. '/'.$c['cat_id'].'.html" class="menu_multi_title"><span class="unfold"></span>'.$c['cat_name'].'</a></div>';
						/*if(!empty($c['helps'])){
							echo '<ul class="menu_multi_sublist">';
							foreach($c['helps'] as $h){
								echo '<li> <a title="'.$h['title'].'"  href="'.url('help:index', array('fid'=>$c['cat_fid'], 'cat'=>$c['cat_id'],'help'=>$h['help_id'])).'"> '.$h['title'].' </a> </li>';
							}
							echo '</ul>';
						}*/
					}
					echo '</div>';
				}
				/*if(!empty($val['helps'])){
					echo '<ul class="h_menu_list">';
					foreach($val['helps'] as $h) {
						echo '<li> <a title="'.$h['title'].'"  href="'.url('help:index', array('cat'=>$val['cat_id'],'help'=>$h['help_id'])).'"> '.$h['title'].' </a> </li>';
					}
					echo '</ul>';
				}*/
				echo '</div></div>';
			}
			?>
		</div>
		<div class="help_rightside fr">
			<div class="u_crumb"> <a  href="./help.html">帮助中心</a> &gt; <a  href="./help.html"><?php echo ($help_categories[$cat_fid]['cat_name']); ?></a> &gt; <a href="./help.html"><?php echo ($help_categories[$cat_fid]['children'][$cat_id]['cat_name']); ?></a><!--&gt; <strong>唯品币</strong>--> </div>
			<div class="u_tab_con">
				<div class="u_tab_con_item">
					<ul class="h_comm_faq J_comm_faq">
						<?php foreach($helps as $help) { ?>
						<li> <a href="javascript:;" class="faq_question"><i class="ico_down"></i><span class="deep_red"> [<?php echo ($help_categories[$help['cat_fid']]['children'][$help['cat_id']]['cat_name']); ?>] </span> <?php echo $help['title']; ?></a>
							<div class="faq_answer"><!--<a href="javascript:;" class="close_faq"></a>--><span class="faq_arrow"><i class="layer1"></i><i class="layer2"></i></span>
								<?php echo $help['content']; ?>
							</div>
						</li>
						<?php } ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<!-- 主体部分结束 -->
<?php include display( 'public:footer');?>
</body>
</html>