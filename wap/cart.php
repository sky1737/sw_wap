<?php
/**
 *  订单信息
 */
require_once dirname(__FILE__) . '/global.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
if($action == 'quantity') {
	if(empty($_POST['id'])) json_return(1, '数据异常');
	if(!empty($_POST['skuId'])) {
		$condition_product_sku['sku_id'] = $_POST['skuId'];
		$product_sku = D('Product_sku')->field('`quantity`')->where($condition_product_sku)->find();
		$quantity = $product_sku['quantity'];
	}
	else if(!empty($_POST['proId'])) {
		$condition_product['product_id'] = $_POST['proId'];
		$product = D('Product')->field('`quantity`')->where($condition_product)->find();
		$quantity = $product['quantity'];
	}
	else {
		json_return(1, '数据异常');
	}
	$condition_user_cart['id'] = $_POST['id'];
	$condition_user_cart['uid'] = $wap_user['uid'];
	$data_user_cart['pro_num'] = $_POST['num'] < $quantity ? intval($_POST['num']) : $quantity;
	D('User_cart')->where($condition_user_cart)->data($data_user_cart)->save();
	json_return(0, $quantity);
}
else if($action == 'del') {
	if(empty($_POST['ids'])) {
		json_return(1000, '请勾选一些内容');
	}
	$condition_user_cart['id'] = array('in', $_POST['ids']);
	$condition_user_cart['store_id'] = $_POST['storeId'];
	$condition_user_cart['uid'] = $wap_user['uid'];
	if(D('User_cart')->where($condition_user_cart)->delete()) {
		json_return(0, '删除成功');
	}
	else {
		json_return(1001, '删除失败，请重试');
	}
}
else if($action == 'pay') {
	$ids = array();
	foreach ($_POST['ids'] as $s) {
		if(!intval($s)) continue;
		$ids[] = intval($s);
	}

	if(empty($ids))
		json_return(1000, '请勾选一些内容！');

	$sql =
		"SELECT uc.*," .
		"p.name, p.image, p.quantity, p.weight, p.price, p.cost_price,p.factory_price, p.postage, p.status, " .
		"ps.quantity sku_quantity, ps.weight sku_weight, ps.price sku_price, ps.cost_price sku_cost_price, ps.factory_price sku_factory_factory, " . //
		"a.name agent_name " .
		"FROM tp_user_cart uc " .
		"INNER JOIN tp_product p ON uc.product_id = p.product_id " .
		"LEFT JOIN tp_product_sku ps ON uc.sku_id = ps.sku_id " .
		"INNER JOIN tp_store a ON uc.agent_id = a.store_id " .
		"WHERE p.status = 1 AND a.status = 1 AND uc.store_id = {$now_store['store_id']} " .
		"AND uc.uid = {$wap_user['uid']} AND uc.id in (" . implode(',', $ids) . ") " .
		"ORDER BY uc.agent_id ASC , uc.id DESC";
	$cart_data = D('')->query($sql);
	if(empty($cart_data))
		json_return(1000, '您选中的商品已下架！');

	$cart_list = array();
	foreach ($cart_data as $value) {
		$quantity = $value['sku_quantity'] ? $value['sku_quantity'] : $value['quantity'];
		if($quantity < $value['pro_num']) {
			json_return(1001, '您选中的商品库存不足！');
		}

		$cart_list[$value['agent_id']][] = $value;
	}

	// 多商家
	$data_merge = array();
	$db_order_merge = D('Order_merge');
	if(count($cart_list) > 1) {
		// SELECT `merge_id`, `uid`, `order_no`, `trade_no`, `pay_type`, `third_id`, `balance`, `point`, `total`, `payment_method`, `status`, `add_time`, `paid_time`, `sent_time`, `cancel_time`, `complate_time`, `pay_money`, `float_amount`, `is_check` FROM `tp_order_merge` WHERE 1
		$data_merge['uid'] = $wap_user['uid'];
		$data_merge['order_no'] = $data_merge['trade_no'] = date('YmdHis', $_SERVER['REQUEST_TIME']) .
			mt_rand(100000, 999999);
		$data_merge['add_time'] = $_SERVER['REQUEST_TIME'];
		$data_merge['merge_id'] = $db_order_merge->data($data_merge)->add();
		if(!$data_merge['merge_id'])
			json_return(1000, '合并付款记录生成失败！');
	}

	$db_order = D('Order');
	$db_order_product = D('Order_product');
	$order_no = '';

	// 产生提醒
	import('source.class.Notify');

	foreach ($cart_list as $value) {
		$data_order = array();
		$data_order['agent_id'] = $value[0]['agent_id'];
		$data_order['store_id'] = $value[0]['store_id'];
		$data_order['merge_id'] = $data_merge['merge_id'];
		$rnd = mt_rand(100000, 999999);
		$rnd++; // 防止生成重复订单号
		$order_no = date('YmdHis', $_SERVER['REQUEST_TIME']) . $rnd;
		$data_order['order_no'] = $data_order['trade_no'] = $order_no;
		$data_order['uid'] = $wap_user['uid'];
		$data_order['add_time'] = $_SERVER['REQUEST_TIME'];
		// insert.
		$data_order['order_id'] = $db_order->data($data_order)->add();
		if(!$data_order['order_id'])
			json_return(1000, '添加订单失败！');

		// 遍历商品
		foreach ($value as $item) {
			$data_order['sub_total'] += round(($item['pro_num'] * 1) * ($item['pro_price'] * 1.00), 2);
			$data_order['pro_num'] = $item['pro_num'];
			$data_order['pro_count'] += 1;
			if($item['sku_price'] && $item['sku_cost_price']) {
				$data_order['profit'] += round(($item['sku_price'] * 1.00 - $item['sku_cost_price'] * 1.00) * ($item['pro_num'] * 1), 2);
			}
			else {
				$data_order['profit'] += round(($item['price'] * 1.00 - $item['cost_price'] * 1.00) * ($item['pro_num'] * 1), 2);
			}
			
			if($item['sku_price'] && $item['sku_factory_price']) {
				$data_order['factory'] += round($item['sku_factory_price'] * 1.00 * $item['pro_num'], 2);
			}
			else {
				$data_order['factory'] += round($item['factory_price'] * 1.00 * $item['pro_num'], 2);
			}

			if($data_order['postage'] * 1 == 0 && $item['postage'] * 1 > 0)
				$data_order['postage'] = $item['postage'] * 1.00;

			// 添加订单产品记录
			$data_order_product = array();
			$data_order_product['order_id'] = $data_order['order_id'];
			$data_order_product['product_id'] = $item['product_id'];
			$data_order_product['sku_id'] = $item['sku_id'];
			$data_order_product['sku_data'] = $item['sku_data'];
			$data_order_product['pro_num'] = $item['pro_num'];
			$data_order_product['pro_price'] = $item['pro_price'];
			$data_order_product['comment'] = !empty($item['comment']) ? $item['comment'] : '';
			$data_order_product['is_fx'] = $item['is_fx']; //是否是分销商品
			//$data_order_product['supplier_id'] = $supplier_id; //供货商id
			//$data_order_product['original_product_id'] = $original_product_id;
			//$data_order_product['user_order_id'] = $order_id;
			$data_order_product['pro_weight'] = $item['weight'];
			$db_order_product->data($data_order_product)->add();
		}

		if($data_order['postage'] > 0 && $data_order['sub_total'] >= option('config.free_postage') * 1.00)
			$data_order['postage'] = 0.00;

		$data_order['total'] = $data_order['sub_total'] * 1.00 + $data_order['postage'] * 1.00;
		// save
		$db_order->where(array('order_id' => $data_order['order_id']))->data($data_order)->save();

		//Notify::createNoitfy($_POST['storeId'], option('config.orderid_prefix') . $order_no);
		Notify::getInstance()->orderUpdate($wap_user['openid'],
			$config['wap_site_url'] . '/my_order.php',
			'你好，你已下单成功。',
			$data_order['order_no'],
			'下单成功，待付款',
//			$value[0]['name'] . (count($value) > 1 ? '等' : ''),
			'请及时付款，如有疑问请联系客服。');

		// 合并付款的总金额
		$data_merge['total'] += $data_order['total'];
	}

	// Save Merge
	if($data_merge['merge_id']) {
		$db_order_merge->where(array('merge_id' => $data_merge['merge_id']))->data($data_merge)->save();
	}

//	$cartList = array();
//	$pro_num = $pro_count = $pro_money = 0;
//	$profit = 0.00;
//	$postage = 0.00;
//
//	foreach ($_POST['ids'] as $value) {
//		$now_cart =
//			D('')->field('uc.*,p.`quantity`,p.`status`,p.`weight`,p.`price`,p.`cost_price`,p.`postage`')
//				->table(
//					array('User_cart' => 'uc',
//					      'Product'   => 'p'))
//				->where($cart_where . " AND `uc`.`id`='$value'")->find();
//		if(empty($now_cart)) {
//			json_return(1001, '您选中的商品已下架');
//		}
//
//		if($now_cart['postage'] > 0 && $postage == 0)
//			$postage = $now_cart['postage'] * 1.00;
//
//		$quantity = $now_cart['quantity'];
//		$now_profit = $now_cart['price'] * 1.00 - $now_cart['cost_price'] * 1.00;
//
//		// 检测库存
//		if(!empty($now_cart['sku_id'])) {
//			$condition_product_sku['sku_id'] = $now_cart['sku_id'];
//			$product_sku = D('Product_sku')->field('`quantity`,`price`,`cost_price`')->where($condition_product_sku)
//				->find();
//			$quantity = $product_sku['quantity'];
//			$now_profit = $product_sku['price'] * 1.00 - $product_sku['cost_price'] * 1.00;
//		}
//
//		if($quantity < $now_cart['pro_num']) {
//			json_return(1001, '您选中的商品库存不足！');
//		}
//
//		$cartList[] = $now_cart;
//		$pro_num += $now_cart['pro_num'];
//		$pro_money += ($now_cart['pro_price'] * 100) * $now_cart['pro_num'] / 100;
//		$profit += $now_profit * $pro_num;
//		$pro_count++;
//	}
//
//	if($pro_money >= option('config.free_postage') * 1.00)
//		$postage = 0.00;
//
//
//	$database = D('Order');
//	$order_id = $database->data($data_order)->add();
//	if(empty($order_id)) {
//		json_return(1004, '订单产生失败，请重试');
//	}
//	if(!empty($wap_user['uid'])) {
//		M('Store_user_data')->upUserData($data_order['store_id'], $wap_user['uid'], 'unpay');
//	}
//	$database_order_product = D('Order_product');
//	$database_product = D('Product');
//	$data_order_product['order_id'] = $order_id;
//	$suppliers = array();
//	foreach ($cartList as $value) {
//		$product_info =
//			$database_product->field('store_id,original_product_id')->where(array('product_id' => $value['product_id']))
//				->find();
//		if(!empty($product_info['original_product_id'])) {
//			$tmp_product_info =
//				$database_product->field('store_id')->where(array('product_id' => $product_info['original_product_id']))
//					->find();
//			$supplier_id = $tmp_product_info['store_id'];
//			$original_product_id = $product_info['original_product_id'];
//		}
//		else {
//			$supplier_id = $product_info['store_id'];
//			$original_product_id = $value['product_id'];
//		}
//		$suppliers[] = $supplier_id;
//		$data_order_product['product_id'] = $value['product_id'];
//		$data_order_product['sku_id'] = $value['sku_id'];
//		$data_order_product['sku_data'] = $value['sku_data'];
//		$data_order_product['pro_num'] = $value['pro_num'];
//		$data_order_product['pro_price'] = $value['pro_price'];
//		$data_order_product['comment'] = !empty($value['comment']) ? $value['comment'] : '';
//		$data_order_product['is_fx'] = $value['is_fx']; //是否是分销商品
//		$data_order_product['supplier_id'] = $supplier_id; //供货商id
//		$data_order_product['original_product_id'] = $original_product_id;
//		$data_order_product['user_order_id'] = $order_id;
//		$data_order_product['pro_weight'] = $value['weight'];
//		$database_order_product->data($data_order_product)->add();
//	}

	//删除购物车商品
	D('User_cart')->where(array('uid' => $wap_user['uid'], 'id' => array('in', $ids)))->delete();

	if($data_merge['merge_id']) {
		json_return(0, $config['mergeid_prefix'] . $data_merge['order_no']);
	}
	json_return(0, $config['orderid_prefix'] . $order_no);
}
else {
	$sql =
		"SELECT uc.*,
		p.name, p.image, p.quantity, p.weight, p.price, p.cost_price, p.postage, p.status, " .
		"ps.quantity sku_quantity, ps.weight sku_weight, ps.price sku_price, ps.cost_price sku_cost_price, " . //
		"a.name agent_name " .
		"FROM tp_user_cart uc " .
		"INNER JOIN tp_product p ON uc.product_id = p.product_id " .
		"LEFT JOIN tp_product_sku ps ON uc.sku_id = ps.sku_id " .
		"INNER JOIN tp_store a ON uc.agent_id = a.store_id " .
		"WHERE uc.store_id = {$now_store['store_id']} " .
		"AND uc.uid = {$wap_user['uid']} " .
		"ORDER BY uc.agent_id ASC , uc.id DESC";
	$cartList = D('')->query($sql);

	$cartData = array();
	$database_product_sku = D('Product_sku');
	foreach ($cartList as $key => $value) {
//		// 限购
//		if(!empty($value['buyer_quota'])) {
//			$uid = $_SESSION['user']['uid'];
//			$orders = D('Order')->field('order_id')->where(array('uid' => $uid))->select();
//			$quantity = 0;
//			if(!empty($orders)) {
//				foreach ($orders as $order) {
//					$products = D('Order_product')->field('pro_num')
//						->where(array('product_id' => $value['product_id'],
//						              'order_id'   => $order['order_id']))
//						->select();
//					foreach ($products as $product) {
//						$quantity += $product['pro_num']; //购买数量
//					}
//				}
//			}
//			$cartList[$key]['buy_quantity'] = $quantity;
//		}
//		else {
//			$cartList[$key]['buy_quantity'] = 0;
//		}

		if($value['sku_quantity'])
			$value['quantity'] = $value['sku_quantity'];

		if($value['sku_price'])
			$value['price'] = $value['sku_price'];


		$value['image'] = getAttachmentUrl($value['image']);

		$cartData[$value['agent_id']][] = $value;
	}

	include display('cart');
}
echo ob_get_clean();
