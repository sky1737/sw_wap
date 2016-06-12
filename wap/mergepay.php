<?php
/**
 *  支付订单
 */
require_once dirname(__FILE__) . '/global.php';

$merge_id = I('get.id');
$count = 0;
$order_no = preg_replace('#' . option('config.mergeid_prefix') . '#', '', $merge_id, 1, $count);
if($count == 0)
	pigcms_tips('订单号不存在！', 'none');

$db_merge = D('Order_merge');
$db_order_product = D('Order_product');
$db_store = D('Store');

$nowMerge = $db_merge->where(array('order_no' => $order_no))->find();

$orders = D('Order')->where(array('merge_id' => $nowMerge['merge_id']))->select();
foreach ($orders as &$ord) {
	$ord['agent'] = $db_store->where(array('store_id' => $ord['agent_id']))->find();
	//$ord['products'] = $db_order_product->where(array('order_id' => $ord['order_id']))->select();
	$sql =
		"SELECT uc.*, p.name, p.image, p.quantity, p.weight, p.price, p.cost_price, p.postage, p.status, ps.quantity
sku_quantity, ps.weight sku_weight, ps.price sku_price, ps.cost_price sku_cost_price FROM tp_order_product uc INNER JOIN tp_product p ON uc.product_id = p.product_id LEFT JOIN tp_product_sku ps ON uc.sku_id = ps.sku_id WHERE uc.order_id = {$ord['order_id']} ORDER BY uc.id DESC";
	$ord['products'] = D('')->query($sql);
}
$nowMerge['orders'] = $orders;
//var_export($nowMerge);

if(empty($nowMerge)) pigcms_tips('该订单号不存在！', 'none');

// 合并订单已付款，跳转到我的订单
if($nowMerge['status'] > 1) redirect('./my_order.php');

// 刷新用户数据
$_SESSION['user'] = $wap_user = D('User')->where(array('uid' => $wap_user['uid'], 'status' => 1))->find();

//// 货到付款
//$offline_payment = false;
//if($now_store['offline_payment']) {
//	$offline_payment = true;
//}
//$is_all_selfproduct = true;
//$is_all_supplierproduct = true;

if($nowMerge['status'] < 1) {
	// 用户地址
	$userAddress = M('User_address')->find('', $wap_user['uid']);
}
else {
	$nowMerge['address'] = unserialize($nowMerge['address']);
	$selffetch_list = true;
	// 查看满减送
	$reward_list = M('Order_reward')->getByOrderId($nowMerge['order_id']);
	// 使用优惠券
	$user_coupon = M('Order_coupon')->getByOrderId($nowMerge['order_id']);

	foreach ($nowMerge['proList'] as $product) {
		// 分销商品不参与满赠和使用优惠券
		if($product['is_fx']) {
			$is_all_selfproduct = false;
		}
		else {
			$is_all_supplierproduct = false;
		}
	}
}

if(!empty($nowMerge['float_amount'])) {
	$nowMerge['sub_total'] += $nowMerge['float_amount'];
	$nowMerge['sub_total'] = number_format($nowMerge['sub_total'], 2, '.', '');
}

// dump($nowMerge);
// 付款方式
$payMethodList = M('Config')->get_pay_method();
$payList = array();
$useStorePay = false;
$storeOpenid = '';
//if ($is_weixin && $_SESSION['openid']) {
//	if ($now_store['wxpay'] && (empty($nowMerge['suppliers']) || $nowMerge['suppliers'] == $now_store['store_id'])) {
//		// dump($_SESSION);
//		//$weixin_bind_info = D('Weixin_bind')->where(array('store_id' => $now_store['store_id']))->find();
//		// dump($weixin_bind_info);
//		if ($weixin_bind_info && $weixin_bind_info['wxpay_mchid'] && $weixin_bind_info['wxpay_key']) {
//			if (empty($_GET['code'])) {
//				$_SESSION['store_weixin_state'] = md5(uniqid());
//				// 代店铺发起获取openid
//				redirect('https://open.weixin.qq.com/connect/oauth2/authorize?appid=' .
//					$weixin_bind_info['authorizer_appid'] . '&redirect_uri=' .
//					urlencode($config['site_url'] . $_SERVER['REQUEST_URI']) .
//					'&response_type=code&scope=snsapi_base&state=' . $_SESSION['store_weixin_state'] .
//					'&component_appid=' . $config['wx_appid'] . '#wechat_redirect');
//			}
//			else if (isset($_GET['code']) && isset($_GET['state']) &&
//				($_GET['state'] == $_SESSION['store_weixin_state'])
//			) {
//				import('Http');
//				$component_access_token_arr = M('Weixin_bind')->get_access_token($now_store['store_id'], true);
//				if ($component_access_token_arr['errcode']) {
//					pigcms_tips('与微信通信失败，请重试。');
//				}
//				$result = Http::curlGet('https://api.weixin.qq.com/sns/oauth2/component/access_token?appid=' .
//					$weixin_bind_info['authorizer_appid'] . '&code=' . $_GET['code'] .
//					'&grant_type=authorization_code&component_appid=' . $config['wx_appid'] .
//					'&component_access_token=' . $component_access_token_arr['component_access_token']);
//				$result = json_decode($result, true);
//				if ($result['errcode']) {
//					pigcms_tips('微信返回系统繁忙，请稍候再试。微信错误信息：' . $result['errmsg']);
//				}
//				$storeOpenid = $result['openid'];
//				if (!D('Order')->where(array('order_id' => $nowMerge['order_id']))
//					->data(
//						array('useStorePay' => '1',
//						      'storeOpenid' => $storeOpenid,
//						      'trade_no'    => date('YmdHis', $_SERVER['REQUEST_TIME']) . mt_rand(100000, 999999)))
//					->save()
//				) {
//					pigcms_tips('订单信息保存失败，请重试。');
//				}
//				$payMethodList['weixin']['name'] = '微信安全支付';
//				$payList[0] = $payMethodList['weixin'];
//				$useStorePay = true;
//			}
//		}
//	}
//	else {
//if(!D('Order')->where(array('order_id' => $nowMerge['order_id']))
//	->data(array('useStorePay' => '0',
//	             'storeOpenid' => '0',
//	             'trade_no'    => date('YmdHis', $_SERVER['REQUEST_TIME']) . mt_rand(100000, 999999)))
//	->save()
//) {
//	pigcms_tips('订单信息保存失败，请重试。');
//}
//	}
if($payMethodList['weixin']) {
	$payMethodList['weixin']['name'] = '微信安全支付';
	$payList[0] = $payMethodList['weixin'];
}
//}
//else if ($payMethodList['alipay']) {
//	$payList[0] = $payMethodList['alipay'];
//}
//if (empty($useStorePay)) {
//	if ($payMethodList['tenpay']) {
//		$payList[1] = $payMethodList['tenpay'];
//	}
//	if ($payMethodList['yeepay']) {
//		$payList[2] = $payMethodList['yeepay'];
//	}
//	else if ($payMethodList['allinpay']) {
//		$payList[2] = $payMethodList['allinpay'];
//	}
//	if ($payList[2]) $payList[2]['name'] = '银行卡支付';
//
//	if ($now_store['pay_agent']) {
//		$payList[] = array('name' => '找人代付', 'type' => 'peerpay');
//	}
//}

//if ($offline_payment) {
//	$payList[] = array('name' => '货到付款', 'type' => 'offline');
//}

////同步到微店的用户
//if (!empty($_SESSION['sync_user'])) {
//	$sync_user = true;
//}

include display('mergepay');
echo ob_get_clean();
