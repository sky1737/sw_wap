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
<title>提现账号 - <?php echo $card['name']; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/foundation.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/normalize.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/common.css"/>
<script src="<?php echo TPL_URL; ?>js/jquery.js"></script>
<script src="<?php echo TPL_URL; ?>js/drp_common.js"></script>
<style type="text/css">
.default { color: #A9A9A9; }
</style>
</head>
<body class="body-gray">
<div data-alert="" class="alert-box alert" style="display: none;" id="errerMsg"><a href="#" class="close">×</a></div>
<div data-alert="" class="alert-box success" style="display: none;">保存成功！</div>
<div class="fixed">
	<nav class="tab-bar">
		<section class="left-small"> <a href="javascript:;" onclick="window.history.go(-1);" class="menu-icon"><span></span></a> </section>
		<section class="middle tab-bar-section">
			<h1 class="title">提现账号</h1>
		</section>
		<section class="right-small right-small-text2"> <a href="javascript:void(0)" id="btnSave" class="button [radius round] top-button">保存</a> </section>
	</nav>
</div>
<form class="mt-55 mlr-15">
	<div class="panel callout radius formstyle">
		<input type="hidden" value="<?php echo $card['card_id']; ?>" id="card_id" name="card_id" />
		<div class="row collapse">
			<div class="small-12 large-12 columns">
				<select name="bank_id" id="bank_id" <?php if (empty($card['bank_id'])) { ?>class="default"<?php } ?>>
					<option value="0" style="color: #A9A9A9;">选择发卡银行</option>
					<?php
					foreach ($banks as $bank) {
						echo '<option value="'.$bank['bank_id'].'"'.($bank['bank_id'] == $card['bank_id']?' selected="true"':'').'>'.$bank['name'].'</option>';
					} ?>
				</select>
			</div>
		</div>
		<div class="row collapse">
			<div class="small-12 large-12 columns">
				<input type="text" placeholder="开户行" id="bank_name" name="bank_name" value="<?php echo $card['bank_name']; ?>"/>
				<span class="close-input" style="display: none;"></span> </div>
		</div>
		<div class="row collapse">
			<div class="small-12 large-12 columns">
				<input type="text" placeholder="银行卡号" id="card_no" name="card_no" value="<?php echo $card['card_no']; ?>"/>
				<span class="close-input" style="display: none;"></span> </div>
		</div>
		<div class="row collapse">
			<div class="small-12 large-12 columns">
				<input type="text" placeholder="持卡人" id="card_user" name="card_user" value="<?php echo $card['card_user']; ?>"/>
				<span class="close-input" style="display: none;"></span> </div>
		</div>
	</div>
</form>
<script type="text/javascript">
$(function () {
	$('#bank_id').change(function () {
		if ($(this).val() > 0) {
			$(this).removeClass('default');
		} else {
			$(this).addClass('default');
		}
	})
	$("#btnSave").click(function () {
		$("#errerMsg").hide(300, "linear");
		var card_id = $('#card_id').val();
		var bank_id = $("#bank_id").val();
		if (bank_id == 0) {
			$("#bank_id").focus();
			ShowMsg("请选择发卡银行");
			return false;
		}
		var bank_name = $.trim($("#bank_name").val());
		if (bank_name == "") {
			$("#bank_name").focus();
			ShowMsg("请填写开户银行");
			return false;
		}
		var card_no = $.trim($("#card_no").val());
		if (card_no == "") {
			$("#card_no").focus();
			ShowMsg("请填写银行卡号");
			return false;
		}
		var card_user = $.trim($("#card_user").val());
		if (card_user == "") {
			$("#card_user").focus();
			ShowMsg("请填写持卡人");
			return false;
		}

		$.post('./balance.php?a=withdraw_account', {
			'card_id':card_id,
			'card_user': card_user,
			'card_no': card_no,
			'bank_name': bank_name,
			'bank_id': bank_id
		}, function (data) {
			if (data.err_code == 0) {
				window.location.href = './balance.php?a=applywithdrawal';
			} else {
				ShowMsg("保存失败,请稍候再试");
				return;
			}
		})
	})
})
</script> 
<?php echo $shareData; ?>
</body>
</html>