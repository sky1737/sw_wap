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
		<section class="left-small"><a class="menu-icon" href="./app_million.php?a=issue"><span></span></a></section>
		<section class="middle tab-bar-section">
			<h1 class="title">收益详情</h1>
		</section>
	</nav>
</div>
<div class="panel disstore mt-45">
	<?php
	if(!empty($incomes)) { ?>
	<div id="distributor">
		<table width="100%">
			<thead>
				<tr>
					<th style="text-align: left">投资者</th>
					<th style="text-align: left">投资额（元）</th>
					<th style="text-align: right">收益额（元）</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach($incomes as $k=>$v) {
					echo '<tr>
					<td><a href="./app_million.php?a=income&issue='.$issue.'&uid='.$v['uid'].'">'.$v['nickname'].'</a></td>
					<td style="text-align: left"><a>'.$v['point'].'</a></td>
					<td style="text-align: left">'.$v['income'].'</td>
				</tr>';
				}
				?>
			</tbody>
		</table>
	</div>
	<?php } else { ?>
	<div class="nocontent-tip"><i class="icon-nocontent-laugh"></i>
		<p class="nocontent-tip-text"> 您还没有分店收益哦，<br>
			别人都开始数钱啦，快去邀请别人加入百万大奖添加收益吧！ </p>
	</div>
	<?php }?>
</div>
<?php
//include display('public_menu');
echo $shareData;
?>
</body>
</html>