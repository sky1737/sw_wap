<?php if (!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<title>编辑微店 -<?php echo $now_store['name']; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/foundation.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/normalize.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/common.css"/>
<script src="<?php echo TPL_URL; ?>js/jquery.js"></script>
<script src="<?php echo TPL_URL; ?>js/drp_foundation.js"></script>
<script src="<?php echo TPL_URL; ?>js/drp_func.js"></script>
<script src="<?php echo TPL_URL; ?>js/drp_common.js"></script>
</head>

<body class="body-gray">
<div data-alert="" class="alert-box alert" style="display: none;" id="errerMsg">请输入微店名！<a href="#" class="close">×</a> </div>
<div class="fixed">
	<nav class="tab-bar">
		<section class="left-small"><a class="menu-icon" href="javascript:window.history.go(-1);"><span></span></a> </section>
		<section class="middle tab-bar-section">
			<h1 class="title">编辑微店</h1>
		</section>
		<section class="right-small right-small-text2" id="saveBtn"><a href="javascript:void(0)" onclick="btnSave()"
		                                                               class="button [radius round] top-button">保存</a> </section>
	</nav>
</div>
<div class="storeedit mlr-15">
	<form>
		<div class="row">
			<div class="large-12 columns">
				<label>店铺名称 <span style="color:red;font-size:12px;">只能修改一次，请您谨慎操作</span></label>
				<input type="text" id="name" name="name" placeholder="微店名称" value="<?php echo $now_store['name']; ?>" <?php if (!empty($now_store['edit_name_count'])) { ?>readonly="true"<?php } ?> />
			</div>
		</div>
		<div class="row">
			<div class="large-12 columns">
				<label>店主姓名<br><span style="color:red;font-size:12px;">请与当前微信账号认证姓名保持一致，一经填写无法修改！</span></label>
				<input type="text" id="linkman" name="linkman" placeholder="微店店主姓名" value="<?php echo $now_store['linkman']; ?>"<?php if(!empty($now_store['linkman'])) echo ' readonly="true"'; ?> />
			</div>
		</div>
		<div class="row">
			<div class="large-12 columns">
				<label>手机号码 <span style="color:red;">紧急联系方式</span></label>
				<input type="text" id="tel" name="tel" placeholder="联系电话" value="<?php echo $now_store['tel']; ?>" />
			</div>
		</div>
		<div class="row">
			<div class="large-12 columns">
				<label>微店描述 </label>
				<textarea style="resize: none;margin: 0 0 1rem 0;" name="intro" id="intro" placeholder="微店描述"><?php echo $now_store['intro']; ?></textarea>
			</div>
		</div>
		<?php if(!$now_store['status']) {?>
		<div class="row">
			<div class="large-12 columns">
				<label>店铺状态 </label>
				<span class="input">已关闭</span>
			</div>
		</div>
		<?php }
		if($now_store['approve']) {?>
		<div class="row">
			<div class="large-12 columns">
				<label> 认证状态 </label>
				<span class="input">已认证</span>
			</div>
		</div>
		<?php }?>
		<div class="row">
			<div class="large-12 columns">
				<label> 入驻时间 </label>
				<span class="input"><?php echo date('Y-m-d H:i:s', $now_store['date_added']); ?></span>
			</div>
		</div>

		<?php
		if($isSupplier) {?>

		<div class="row">
			<div class="large-12 columns">
				<label> 供应商编号 </label>
				<span class="input"><?php echo $now_store['supplier_code']; ?></span>
			</div>
		</div>
		<?php }?>
	</form>
</div>
<script type="text/javascript">
function chkName(name) {
	if(name == '') {
		ShowMsg("店铺名称不可为空！");
		return false;
	}
	// 检测店铺名称是否已存在
	var b = false;
	$.ajax({
		url: "./drp_store.php?a=check_name",
		data: {"name": name},
		async: false,
		cache: false,
		type: 'POST',
		success: function (res) {
			b = res;
		}
	});
	if(!b) {
		ShowMsg("店铺名称已存在");
		return false;
	}
	return true;
}
	function btnSave() {
		var name = $('input#name').val().trim();
		if(!chkName(name)){
			return false;
		}
		var linkman = $('input#linkman').val().trim();
		if(linkman == '') {
			ShowMsg('请填写店主姓名！');
			return false;
		}
		var tel = $('input#tel').val().trim();
		if(tel == '') {
			ShowMsg('请填写手机号码！');
			return false;
		}
		var intro = $('#intro').val().trim();
		if (intro == "") {
			ShowMsg("请填写店铺介绍！");
			return;
		} 
		
		$.post('./drp_store.php?a=edit', {"name": name, 'linkman': linkman, 'tel': tel, 'intro': intro}, function (data) {
			if (data.err_code == 0) {
				window.location.href = data.err_msg;
			} else {
				ShowMsg(data.err_msg);
				return;
			}
		},'JSON');

	}
</script> 
<?php echo $shareData; ?>
</body>
</html>