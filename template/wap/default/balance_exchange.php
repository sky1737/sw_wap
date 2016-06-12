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
<title>积分兑换余额 - <?php echo $config['site_name']; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/foundation.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/normalize.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/common.css"/>
<script src="<?php echo TPL_URL; ?>js/jquery.js"></script>
<script src="<?php echo TPL_URL; ?>js/drp_common.js"></script>
<style type="text/css">
.side-nav li { padding: 5px; }
.bank-info { display: inline-block; font-weight: bold; width: 80px; font-family: "微软雅黑", "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif !important; color: #999; }
.arrow { margin-top: -15px !important; }
.commision-total .icon-horn { float: right; margin: 0; }
</style>
</head>
<body>
<div data-alert="" class="alert-box alert" id="errerMsg" style="display: none;"><a href="#" class="close">×</a></div>
<div class="fixed">
	<nav class="tab-bar">
		<section class="left-small"> <a href="./balance.php?a=index" class="menu-icon"><span></span></a> </section>
		<section class="middle tab-bar-section">
			<h1 class="title">余额提现</h1>
		</section>
	</nav>
</div>
<div class="panel extract">
	<div class="commision-total">
		<span class="span-title">可兑换积分数量</span><br />
		<span class="number" style="line-height: 2em;"><?php echo $point; ?></span><!--<i class="icon-horn"></i>-->
	</div>
	<!--<div class="horn-text" style="display:;">可提现金额为交易成功且未提现的金额</div>-->
	<div class="extract-number">
		<span class="span-title">已兑换积分数量</span><br />
		<span class="number" id="MaxPointAmount" style="line-height: 2em;"><?php echo $exchanged; ?></span>
	</div>
	<div class="row extract-monynumber">
		<div class="large-12 columns">
			<input type="text" class="" id="PointAmount" placeholder="输入兑换积分数量">
			<span class="close-input" style="display: ;"></span> </div>
	</div>
	<?php if ($rate > 0) { ?>
	<div class="tip-text">最低兑换积分数量为<b id="ExchangeRate"><?php echo $rate; ?></b>分</div>
	<?php } ?>
	<a href="javascript:void(0)" onclick="btnSave()" class="button [radius round] red">立即提取</a>
</div>
<script type="text/javascript">
function btnSave() {
	var MaxPoint = parseFloat("<?php echo $point; ?>");
	var ExchangeRate = parseFloat('<?php echo $rate; ?>');
	var PointAmount = $.trim($("#PointAmount").val());
	if (!/^[0-9]+$/.test(PointAmount)) {
		ShowMsg("输入积分数量不正确！");
		return false;
	}
	PointAmount = Number(PointAmount);
	if (isNaN(PointAmount) || PointAmount < 0) {
		ShowMsg("请输入兑换积分数量！");
		return false;
	}
	if (PointAmount > MaxPoint) {
		ShowMsg("可兑换积分数量为:" + MaxPoint+"分");
		return false;
	}
	if (PointAmount < ExchangeRate) {
		ShowMsg("兑换积分数量必须大于" + ExchangeRate+"分");
		return false;
	}
	
	if (PointAmount % ExchangeRate > 0) {
		ShowMsg("兑换积分数量必须为" + ExchangeRate+"倍数！");
		return false;
	}
	
	$.post('./balance.php?a=exchange', {'point': PointAmount}, function (result) {
		if (result.err_code == 0) {
			$("#errerMsg").html('兑换成功！请到账户余额中查看...');
			$("#errerMsg").show(300, "linear");
			setTimeout(function () {
				$("#errerMsg").animate({ height: 'toggle', opacity: 'toggle' }, { duration: "slow" });
				location.href = './balance.php?a=index';
			}, 3000);
		} else {
			ShowMsg(result.err_msg);
			return false;
		}
	},'JSON');
}

</script> 
<?php echo $shareData; ?>
</body>
</html>