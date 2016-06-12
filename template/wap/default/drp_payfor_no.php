<?php if(!defined('TWIKER_PATH')) exit('deny access!'); ?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="format-detection" content="telephone=no" />
	<title>请先获取推广码 - <?php echo option('config.site_name'); ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/foundation.css">
	<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/normalize.css">
	<link rel="stylesheet" type="text/css" href="<?php echo TPL_URL; ?>css/common.css">
	<script src="<?php echo TPL_URL; ?>js/jquery.js"></script>
</head>

<body style="max-width:640px;">
<div style="padding: 100px 30px;line-height:2em;font-size:1.5em;">
	请先获取推广码！<br/>扫码成为<?php echo option('config.site_name'); ?>的代理！
</div>
</body>
</html>