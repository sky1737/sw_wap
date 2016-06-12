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
<title>余额提现 - <?php echo $config['site_name']; ?></title>
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
	<div class="commision-total"><span class="span-title">可提现金额</span><span
		class="number">￥<?php echo $balance; ?></span><i class="icon-horn"></i></div>
	<div class="horn-text" style="display:;">可提现金额为交易成功且未提现的金额</div>
	<div class="extract-number"> <span class="span-title">已提现金额</span> <span class="number" id="MaxCashAmount">￥<?php echo $withdrawal; ?></span> </div>
	<div class="panel extract-account">
		<ul class="side-nav">
			<?php /*<li> <span class="bank-info">银行卡：</span><?php echo $card['card_no']; ?><br/>
				<span class="bank-info">持卡人：</span><?php echo $card['card_user']; ?><br/>
				<span class="bank-info">开户行：</span><?php echo $card['bank_name']; ?><a
				href="./balance.php?a=withdraw_account" style="display:inline"><i class="arrow"></i></a><br/>
				<span class="bank-info">发卡银行：</span><?php echo $card['bank']; ?>
				<input type="hidden" name="bank_id" class="bank-id" value="<?php echo $card['bank_id']; ?>"/>
				<input type="hidden" name="card_id" class="card-id" value="<?php echo $card['card_id']; ?>"/>
			</li>*/?>
			<li style="font-size: 12px; color:#ccc;padding-bottom:5px;">提现到当前微信零钱帐户，需要验证姓名和手机号码，请确认正确后提交！</li>
			<li><span class="bank-info">店主姓名：</span><?php echo $now_store['linkman']; ?><br/>
				<span class="bank-info">店主手机：</span><?php echo $now_store['tel']; ?></li>
		</ul>
	</div>
	<?php /*<div class="row extract-monynumber">
		<div class="large-12 columns">
			<input type="text" class="truename" id="truename" value="<?php echo $wap_user['truename']; ?>" placeholder="输入真实姓名">
			<span class="close-input" style="display: ;"></span> </div>
	</div>
	<div class="row extract-monynumber">
		<div class="large-12 columns">
			<input type="text" class="mobile" id="mobile" value="<?php echo $wap_user['phone']; ?>" placeholder="输入手机号码">
			<span class="close-input" style="display: ;"></span> </div>
	</div>*/?>
	<div class="row extract-monynumber">
		<div class="large-12 columns">
			<input type="text" class="" id="CashAmount" placeholder="输入提现金额">
			<span class="close-input" style="display: ;"></span> </div>
	</div>
	<?php if ($withdrawal_min_amount > 0) { ?>
	<div class="tip-text">最低提现金额为<b id="MinaAmountCash"><?php echo number_format($withdrawal_min, 2, '.', ''); ?></b>元 </div>
	<?php } ?>
	<a href="javascript:void(0)" onclick="btnSave()" class="button [radius round] red">立即提取</a>
</div>
<script type="text/javascript">
function btnSave() {
	var BalanceAmount = parseFloat("<?php echo $balance; ?>");
	var MinaAmountCash = parseFloat('<?php echo $withdrawal_min; ?>');
/*
	var bank_id = $.trim($('.bank-id').val());
	var card_id = $.trim($('.card-id').val());
	if (bank_id == '' || card_id == '') {
		ShowMsg("收款信息填写不完整，无法提现");
		return false;
	}
	var truename = $.trim($('#truename').val());
	if(truename=='') {
		ShowMsg("请输入真实姓名！");
		return false;
	}
	var mobile = $.trim($('#mobile').val());
	if(!/^1\d{10}$/.test(mobile)){
		ShowMsg("请输入真实有效手机号码！");
		return false;
	}
*/
	
	var CashAmount = $.trim($("#CashAmount").val());
	if (!/^[0-9]+(\.[0-9]{1,2})?$/.test(CashAmount)) {
		ShowMsg("输入金额不合法！");
		return false;
	}
	CashAmount = Number(CashAmount);
	if (isNaN(CashAmount) || CashAmount < 0) {
		ShowMsg("请输入提现金额！");
		return false;
	}
	if (CashAmount > BalanceAmount) {
		ShowMsg("可提现金额为:￥" + BalanceAmount.toFixed(2));
		return false;
	}
	if (CashAmount < MinaAmountCash) {
		ShowMsg("提现金额必须大于￥" + MinaAmountCash.toFixed(2));
		return false;
	}
	
	$.post('./balance.php?a=withdrawal', {'amount': CashAmount}, function (result) {
		if (result.err_code == 0) {
			$("#errerMsg").html('提现成功！请到微信零钱账户中查看...');
			$("#errerMsg").show(300, "linear");
			setTimeout(function () {
				$("#errerMsg").animate({ height: 'toggle', opacity: 'toggle' }, { duration: "slow" });
				location.href = './balance.php?a=detail';
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