<?php
/**
 *  分销商注册
 */
require_once dirname(__FILE__) . '/drp_check.php';

function jump($msg)
{
	logs($msg, 'Redpack');
	header('Location: ./drp_ucenter.php');
	exit;
}

if ($_GET['type'] == 'redpack') {
	$order_no = I('get.order_no');
	if (empty($order_no)) {
		jump('参数错误！');
	}

	$db_payfor_redpack = D('Payfor_redpack');
	$redpacks = $db_payfor_redpack->where(array('order_no' => $order_no, 'status' => 0))->select();
	if (empty($redpacks)) {
		jump('红包记录为空，无法发红包！');
	}

	// 微信提现
	$payType = 'weixin';
	$payMethodList = M('Config')->get_pay_method();
	if (empty($payMethodList[$payType])) {
		jump('您选择的支付方式不存在，请更新支付方式！');
	}

	import('source.class.pay.Weixin');
	foreach ($redpacks as $rp) {
		if (empty($rp['openid']))
			continue;

		$payClass = new Weixin($rp, $payMethodList[$payType]['config'], $rp['openid'], '');
		$result = $payClass->redpack();
		logs('Redpack_info:' . json_encode($result), 'INFO');
		if (!$result['err_code']) {
			$db_payfor_redpack->where(array('id' => $rp['id']))
				->data(array(
					'status' => 1,
					'trade_no' => $result['err_msg']
				))
				->save();
		}
	}

	$_SESSION['user'] = D('User')->where(array('uid' => $wap_user['uid']))->find();
	$_SESSION['store'] = D('Store')->where(array('uid' => $wap_user['uid']))->find();
	jump($result['err_msg']);
}

if (IS_POST) {
	if ($_POST['type'] == 'check_store') {
		$name = isset($_POST['name']) ? trim($_POST['name']) : '';
		if ($store->checkStoreExist(array('name' => $name, 'status' => 1))) {
			echo false;
		}
		else {
			echo true;
		}
	}
	else if ($_POST['type'] == 'check_phone') {
		$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
		$user = M('User');
		if ($user->checkUser(array('phone' => trim($_POST['phone']), 'uid' => array('!=', $_SESSION['user']['uid'])))) {
			echo false;
		}
		else {
			echo true;
		}
	}
	else if ($_POST['type'] == 'payfor') {
		$parent_uid = D('User')->where(array('uid' => $wap_user['uid']))->getField('parent_uid');
		if (!$parent_uid) {
			json_return(1, '请先获取推广码，扫码后成为' . option('config.site_name') . '的代理商！');
		}

		$payType = 'weixin';
		$balance = $config['payfor_store'] * 1.00;
		$db_Payfor_order = D('Payfor_order');
		$nowOrder = $db_Payfor_order->where(array('uid' => $wap_user['uid'],
			'total' => $balance,
			'status' => 1))->find();
		if (empty($nowOrder)) {
			$nowOrder = array(
				'order_no' => date('YmdHis', $_SERVER['REQUEST_TIME']) . mt_rand(100000, 999999),
				'trade_no' => date('YmdHis', $_SERVER['REQUEST_TIME']) . mt_rand(1000, 9999),
				'uid' => $wap_user['uid'],
				'total' => $balance,
				'pay_type' => $payType,
				'status' => 1,
				'add_time' => time(),
				'remarks' => '付款￥' . $balance . '开店。',
				'pay_money' => $balance);
			if (!$nowOrder['order_id'] = $db_Payfor_order->data($nowOrder)->add()) {
				json_return(1, '生成支付订单失败，请重试！');
			}
		}

		$payMethodList = M('Config')->get_pay_method();
		if (empty($payMethodList[$payType])) {
			json_return(1012, '您选择的支付方式不存在，请更新支付方式！');
		}

		$nowOrder['order_no_txt'] = option('config.orderid_prefix') . $nowOrder['order_no'];

		import('source.class.pay.Weixin');
		$payClass = new Weixin($nowOrder, $payMethodList[$payType]['config'], $wap_user['openid'], 'payfor');
		$result = $payClass->pay();
		logs('payInfo:' . json_encode($result), 'INFO');
		if ($result['err_code']) {
			json_return(1013, $result['err_msg']);
		}
		else {
			//$result['order_no'] = $nowOrder['order_no'];
			json_return(0, json_decode($result['pay_data']), $nowOrder['order_no']);
		}
	}
	exit;
}


$store = M('Store');
$store_count = $store->getStoreCountByUid($_SESSION['user']['uid'], 1);
if (option('config.user_store_num_limit') > 0 && $store_count >= option('config.user_store_num_limit')) {
	$wap_user['stores'] = $store_count;
	$_SESSION['user'] = $wap_user;
	header('location: ./drp_ucenter.php');
	exit;
}

if (option('config.payfor_store') * 1 > 0) {
	// 判断用户没有上线不能开店
	$parent_uid = D('User')->where(array('uid' => $wap_user['uid']))->getField('parent_uid');
	if ($parent_uid) {
		include display('drp_payfor_store');
	}
	else {
		include display('drp_payfor_no');
	}
}
else {
	include display('drp_register');
}

//分享配置 start
$share_conf = array(
	'title' => $now_store['name'] . '-分销管理', // 分享标题
	'desc' => str_replace(array("\r", "\n"), array('', ''),
		!empty($now_store['intro']) ? $now_store['intro'] : $now_store['name']), // 分享描述
	'link' => getTwikerUrl($now_store['uid']), // 分享链接
	'imgUrl' => $now_store['logo'], // 分享图片链接
	'type' => '', // 分享类型,music、video或link，不填默认为link
	'dataUrl' => '', // 如果type是music或video，则要提供数据链接，默认为空
);
import('WechatShare');
$share = new WechatShare();
$shareData = $share->getSgin($share_conf);
//分享配置 end

echo ob_get_clean();