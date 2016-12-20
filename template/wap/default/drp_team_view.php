<?php if (!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
<meta name="apple-mobile-web-app-capable" content="yes"/>
<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
<meta name="format-detection" content="telephone=no"/>
<meta class="foundation-data-attribute-namespace"/>
<meta class="foundation-mq-xxlarge"/>
<meta class="foundation-mq-xlarge"/>
<meta class="foundation-mq-large"/>
<meta class="foundation-mq-medium"/>
<meta class="foundation-mq-small"/>
<title><?php echo $parent ? $parent['nickname'] : '我'; ?>的粉丝列表 -<?php echo $now_store['name']; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/foundation.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/normalize.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/common.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/drp_dis.css">
<script src="<?php echo TPL_URL; ?>js/jquery.js"></script>
</head>
<body class="body-gray">
<div class="fixed">
	<nav class="tab-bar">
		<section class="left-small"><a class="menu-icon" onclick="window.history.go(-1)"><span></span></a></section>
		<section class="middle tab-bar-section">
			<h1 class="title"><?php echo $parent ? $parent['nickname'] : '我'; ?>的粉丝列表</h1>
		</section>
	</nav>
</div>
<div class="panel disstore mt-45">
	<?php if (!empty($result)) { ?>
		<div id="distributor">
			<table width="100%">
				<thead>
				<tr>
					<th style="text-align: left">称呼</th>
					<th style="text-align: left">分销店铺</th>
					<!--<th style="text-align: left">联系方式</th>-->
					<th style="text-align: right">销售额（元）</th>
				</tr>
				</thead>
				<tbody>
				<?php
				foreach ($result as $fansInfo) {
					?>
					<tr>
						<td><?php echo $fansInfo['nickname']; ?></td>
						<td style="text-align: left"><a href="./drp_team.php?&a=view&parent=<?php echo $fansInfo['uid']; ?>"><?php echo $fansInfo['name']; ?></a></td>
						<td style="text-align: right"><?php echo number_format($fansInfo['totals'], 2, '.',''); ?></td>
					</tr>
				<?php
					} ?>
				</tbody>
			</table>
		</div>
	<?php } ?>
	<div class="nocontent-tip <?php if (!empty($result)) { ?>hide<?php } ?>"><i class="icon-nocontent-laugh"></i>

		<p class="nocontent-tip-text"> 您还没有粉丝，<br>
			别人都开始数钱啦，快发展粉丝赚钱去！ </p>
	</div>
	<!--<div class="bro-help">
	<h3>没有分销商，试试分享店铺</h3>
	<ul class="bro-help-list">
		<li class="bro-help-list-li">
			<a class="bro-help-list-a" href="javascript:void(0)" onclick="showShare()">
				<i class="icon-share-circle"></i>
				<span>分享店铺很重要</span>
				<i class="arrow"></i>
			</a>
		</li>
	</ul>
</div>-->
	<!--<div class="h50"></div>

<div class="fixed bottom">
	<div class="shareList">
		<ul class="small-block-grid-4" id="sharelist">
			<li shareid="tsina"><i class="icon-sina"></i><span class="name">新浪微博</span></li>
			<li shareid="qzone"><i class="icon-qqzone"></i><span class="name">QQ空间</span></li>
			<li shareid="tqq"><i class="icon-txwb"></i><span class="name">腾讯微博</span></li>
			<li class="no-line" shareid="xiaoyou"><i class="icon-friend"></i><span class="name">朋友网</span></li>
		</ul>
	</div>
</div>-->
	<!--<script>
	function showShare() {
		$(".shareList").slideToggle("slow");
		$(".mask").toggle();
	}

	$(document).ready(function () {
		$(".mask").click(function () {
			$(".shareList").slideToggle("slow");
			$(".mask").toggle();
		})
	})
</script>

<script type="text/javascript">
	$("#sharelist li").click(function () {
		var shareurl = "<?php /*echo $store_url; */ ?>";
		var title = "微店:<?php /*echo $store['name']; */ ?>";
		var shareid = $(this).attr("shareid");
		window.location.href = "http://www.jiathis.com/send/?webid=" + shareid + "&url=" + encodeURIComponent(shareurl) + "&title=" + title + "&shortUrl=false&hideMore=false";
	})
</script>-->
</div>
</body>
</html>