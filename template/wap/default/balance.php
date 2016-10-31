<?php if (!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta charset="utf-8"/>
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
    <title>账户余额 - <?php echo $config['site_name']; ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/foundation.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/normalize.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/common.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/drp_dis.css"/>
    <script src="<?php echo TPL_URL; ?>js/jquery.js"></script>
    <script src="<?php echo TPL_URL; ?>js/drp_foundation.js"></script>
</head>
<body>
<div class="mask"></div>
<div class="fixed">
    <nav class="tab-bar">
        <?php $url = $isSupplier ? 'supplier' : 'drp'; ?>
        <section class="left-small"><a class="menu-icon" href="./<?php echo $url ?>_ucenter.php"><span></span></a>
        </section>
        <section class="middle tab-bar-section">
            <h1 class="title">账户余额</h1>
        </section>
        <section class="right-small right-btn-brokerage"><a class="a-borkerage-detail" href="javascript:;"
                                                            onclick="window.location.href='./balance.php?a=detail'">明细</a>
        </section>
    </nav>
</div>
<div class="bro-spare">
    <p class="tip-txt"><i class="icon-money"></i>账户余额（元）</p>
    <span class="number-big"><?php printf('%.2f', $balance); ?></span>
    <p class="field-2">消费钱包余额：<big>￥<?php printf('%.2f', $consumer); ?></big></p>
    <p class="field-2">积分：<big><?php echo $point; ?></big> &nbsp;(<a style="color:#999;font-size: .9em;"
                                                                     href="./balance.php?a=exchange">兑换余额</a>)</p>
    <p class="field-2">已提现总额：<big>￥<?php printf('%.2f', $cash); ?></big></p>
</div>
<ul class="maneylist" style="display: ;">
</ul>
<div class="bro-extract-an"><a href="javascript:;" onclick="window.location.href='./balance.php?a=withdrawal'"
                               class="button [radius round] red">申请提现</a></div>
<div class="bro-extract-an"><a href="javascript:;" onclick="window.location.href='./balance.php?a=recharge'"
                               class="button [radius round] red">账户充值</a></div>

<!-- <div class="bro-help">
<h3>没有收入，试试分享店铺</h3>
<ul class="bro-help-list">
	<li class="bro-help-list-li">
		<a class="bro-help-list-a" href="javascript:void(0)" onclick="showShare()">
			<i class="icon-share-circle"></i><span>分享店铺很重要</span><i class="arrow"></i>
		</a>
	</li>
</ul>
</div>

<div class="h50"></div>

<div class="fixed bottom">
<div class="shareList">
	<ul class="small-block-grid-4" id="sharelist">
		<li shareid="tsina"><i class="icon-sina"></i><span class="name">新浪微博</span></li>
		<li shareid="qzone"><i class="icon-qqzone"></i><span class="name">QQ空间</span></li>
		<li shareid="tqq"><i class="icon-txwb"></i><span class="name">腾讯微博</span></li>
		<li class="no-line" shareid="xiaoyou"><i class="icon-friend"></i><span class="name">朋友网</span></li>
	</ul>
</div>
</div>
<script>
$(document).foundation().foundation('joyride', 'start');

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
	var shareurl = "<?php /* echo $store_url;*/ ?>";
	var title = "微店:<?php /* echo $store['name']; */ ?>";
	var shareid = $(this).attr("shareid");
	window.location.href = "http://www.jiathis.com/send/?webid=" + shareid + "&url=" + encodeURIComponent(shareurl) + "&title=" + encodeURIComponent(title) + "&shortUrl=false&hideMore=false";
})

</script>
-->
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>index_style/css/my.css"/>
<?php
include display('public_menu');
echo $shareData;
?>
</body>
</html>