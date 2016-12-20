<?php
/**
 * 分销店铺
 * User: pigcms_21
 * Date: 2015/4/18
 * Time: 9:01
 */

require_once dirname(__FILE__) . '/drp_check.php';

//分享配置 start
$share_conf = array(
	'title'   => $now_store['name'] . '-分销管理', // 分享标题
	'desc'    => str_replace(array("\r", "\n"), array('', ''),
		!empty($now_store['intro']) ? $now_store['intro'] : $now_store['name']), // 分享描述
	'link'    => getTwikerUrl($now_store['uid']), // 分享链接
	'imgUrl'  => $now_store['logo'], // 分享图片链接
	'type'    => '', // 分享类型,music、video或link，不填默认为link
	'dataUrl' => '', // 如果type是music或video，则要提供数据链接，默认为空
);

import('WechatShare');
$share = new WechatShare();
$shareData = $share->getSgin($share_conf);
//分享配置 end

if($_GET['a'] == 'view') {
	$parent_id = I('get.parent');
	if($parent_id) {
		$parent = D('User')->where(array('uid' => $parent_id))->find();
	}

	$result =
		D('')->query('SELECT u.uid, u.nickname, s.store_id, s.name, SUM(CASE WHEN o.order_id is null THEN 0 ELSE o.total END) totals FROM
tp_user u LEFT JOIN tp_store s ON u.uid = s.uid AND s.status =1 LEFT JOIN tp_order o ON o.store_id = s.store_id AND o
.status in (2,3,4) WHERE u.parent_uid = ' .
			($parent_id ? $parent_id : $wap_user['uid']) .
			' AND u.status =1 GROUP BY u.uid, u.nickname, s.store_id, s.name ORDER BY u.uid DESC');

	include display('drp_team_view');
	echo ob_get_clean();
}
else {
//	if(empty($now_store)) {
//		redirect('./ucenter.php?id=' . intval(trim($_COOKIE['wap_store_id'])));
//	}
//	$store_supplier = M('Store_supplier');
//	$order = M('Order');
//	$user = M('User');
//	$fx_order = M('Fx_order');
//	$product = M('Product');
//
//	$level = isset($_GET['level']) ? trim(trim($_GET['level'])) : 1;
//
//	$store = $now_store;
//	$levels = array(1 => '一', 2 => '二', 3 => '三');
////当前分销商级别
//	$store_id = $now_store['store_id'];
//	$seller = $store_supplier->getSeller(array('seller_id' => $store_id));
//	$seller_level = $seller['level'];
//	$sub_level = $seller_level + $level;
//	$where = array();
//	$where['level'] = $sub_level;
//	$where['_string'] = 'FIND_IN_SET(' . $store_id . ', supply_chain)';
//	$sub_sellers = $store_supplier->getSellers($where);
//	$order_count = 0; //订单数量（已支付）
//	$fans_count = 0; //粉丝数量
//	$order_total = 0; //订单总额（已支付）
//	foreach ($sub_sellers as $sub_seller) {
//		//$tmp_order_count = $order->getOrderCount(array('store_id' => $sub_seller['seller_id'], 'status' => array('in', array(2,3,4))));
//		$tmp_order_count = $fx_order->getOrderCount(array('store_id' => $sub_seller['seller_id'],
//		                                                  'status'   => array('in', array(2, 3, 4))));
//		$order_count += $tmp_order_count;
//		$tmp_product_count = $product->getSellingTotal(array('store_id' => $sub_seller['seller_id']));
//		$product_count += $tmp_product_count;
//		//$tmp_order_total = $order->getOrderAmount(array('store_id' => $sub_seller['seller_id'], 'status' => array('in', array(2,3,4))));
//		$tmp_order_total = $fx_order->getSales(array('store_id' => $sub_seller['seller_id'],
//		                                             'status'   => array('in', array(1, 2, 3, 4))));
//		$order_total += $tmp_order_total;
//	}
//	$sub_sellers = count($sub_sellers);

	$db_pre = option('system.DB_PREFIX');

	$users = D('User')->where(array('parent_uid' => $wap_user['uid'], 'status' => 1))->count('uid');
	$result = array('users' => $users, 'orders' => 0, 'totals' => 0.00, 'pros' => 0);

	$orderCount = D('Order')
		->where("`uid` in (select `uid` from `{$db_pre}user` where `parent_uid` = {$wap_user['uid']}) and `status` = 1")
		->field("SUM(CASE WHEN status in (2,3,4) THEN 1 ELSE 0 END) AS orders, SUM(CASE WHEN status in (2,3,4) THEN total ELSE 0 END) totals, SUM(CASE WHEN status in (2,3,4) THEN pro_count ELSE 0 END) AS pros")
		->find();
	if($orderCount) {
		$result['orders'] = $orderCount[0];
		$result['totals'] = $orderCount[0];
		$result['pros'] = $orderCount[0];
	}

	include display('drp_team');
	echo ob_get_clean();
}