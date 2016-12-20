<?php if (!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8"/>
<title>十万大奖<?php echo $config['site_name']; ?></title>
<meta name="keywords" content="<?php echo $config['seo_keywords']; ?>"/>
<meta name="description" content="<?php echo $config['seo_description']; ?>"/>
<meta name="format-detection" content="telephone=no"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
<meta name="apple-mobile-web-app-capable" content="yes"/>
<meta name="apple-mobile-web-app-status-bar-style" content="default"/>
<meta name="applicable-device" content="mobile"/>

<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/foundation.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/common.css"/>

<!--<script src="<?php echo $config['oss_url']; ?>/static/js/fastclick.js"></script>
<script src="<?php echo $config['oss_url']; ?>/static/js/jquery.min.js"></script>
<script src="<?php echo TPL_URL; ?>index_style/js/base.js"></script>-->
</head>
<body class="body-gray">
<div class="fixed">
	<nav class="tab-bar">
		<section class="left-small"><a class="menu-icon" href="./app_million.php" onclick=""><span></span></a></section>
		<section class="middle tab-bar-section">
			<h1 class="title">投资详情</h1>
		</section>
	</nav>
</div>
<div class="panel disstore mt-45">
	<div id="distributor">
		<table width="100%">
			<thead>
				<tr>
					<th style="text-align: left">期数</th>
					<th style="text-align: left">投资积分</th>
					<th style="text-align: left">收益积分</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach($issues as $k=>$v) {
					echo '<tr><td><a href="./app_million.php?a=income&issue='.$v['issue'].'">第'.$v['issue'].'期</a></td><td style="text-align: left"><a>'.$v['point'].'</a></td><td style="text-align: left">'.$v['income'].'</td></tr>';
				}
				?>
			</tbody>
		</table>
	</div>
</div>
<?php
//include display('public_menu');
echo $shareData;
?>
</body>
</html>