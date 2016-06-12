<?php
/* 验证码页面 */

define('TWIKER_PATH', dirname(__FILE__) . '/../');
require_once TWIKER_PATH . 'source/init.php';

$type = isset($_GET['type']) ? $_GET['type'] : redirect($_G['config']['site_url'] . '/static/images/no_qrcode.png');
$id = is_numeric($_GET['id']) ? $_GET['id'] : redirect($_G['config']['site_url'] . '/static/images/no_qrcode.png');
switch ($type) {
	case 'store':
	case 'home':
		$result = M('Recognition')->get_tmp_qrcode('store', $id, $id);
		if(!$result['err_code']) {
			header('location:' . $result['qrcode']);
			exit;
		}
		//$url = 'http://' . $id . '.' . $_G['config']['site_domain'];
		// $_G['config']['wap_site_url'] . '/home.php?id=' . $id;
		break;
	case 'agent':
		$result = M('Recognition')->get_agent_qrcode('agent', $id, $id);
		if(!$result['err_code']) {
			header('location:' . $result['qrcode']);
			exit;
		}
		//$url = 'http://' . $id . '.' . $_G['config']['site_domain'];
		// $_G['config']['wap_site_url'] . '/home.php?id=' . $id;
		break;
	case 'page_cat':
		$url = 'http://' . $store . '.' . $_G['config']['site_domain'] . '/wap/pagecat.php?id=' . $id;
		break;
	case 'page':
		$url = 'http://' . $store . '.' . $_G['config']['site_domain'] . '/wap/page.php?id=' . $id;
		break;
	case 'good_cat':
		$url = 'http://' . $store . '.' . $_G['config']['site_domain'] . '/wap/goodcat.php?id=' . $id;
		break;
	case 'good':
		$result = M('Recognition')->get_tmp_qrcode('product', $id, $store['store_id']);
		if(!$result['err_code']) {
			header('location:' . $result['qrcode']);
			exit;
		}
//		$url = 'http://' . $store . '.' . $_G['config']['site_domain'] . '/wap/good.php?id=' . $id .
//			(isset($_GET['activity']) ? '&activity=' . $_GET['activity'] : '');
//		break;
	case 'ucenter':
		$url = 'http://' . $store . '.' . $_G['config']['site_domain'] . '/wap/ucenter.php?id=' . $id;
		break;
	case 'activity':
		$url = urldecode($_GET['url']);
		break;
	default:
		redirect($_G['config']['site_url'] . '/static/images/no_qrcode.png');
}

import('phpqrcode');
QRcode::png(urldecode($url), false, 0, 7, 2);