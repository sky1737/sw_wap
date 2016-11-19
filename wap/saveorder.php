<?php
/**
 *  处理订单
 */
require_once dirname(__FILE__) . '/global.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'add';

switch ($action) {
    case 'add':
        $db_order = D('Order');
        $nowProduct = D('Product')
            ->field('`product_id`, `store_id`, `name`, `price`, `cost_price`, `has_property`, `status`,
			`supplier_id`, `buyer_quota`, `weight`, `postage`,`quantity`')
            ->where(array('product_id' => $_POST['proId'], 'status' => 1))
            ->find();
        if (empty($nowProduct) || !$nowProduct['status']) {
            json_return(1000, '商品不存在');
        }

        $quantity = intval($_POST['quantity']);
        $skuId = intval($_POST['skuId']);

        //限购
//		$profit = 0;
//		$buy_quantity = 0;
//		if(!empty($nowProduct['buyer_quota'])) {
//			$uid = $_SESSION['user']['uid'];
//			$orders = $db_order->field('order_id')->where(array('uid' => $uid))->select();
//			if(!empty($orders)) {
//				foreach ($orders as $order) {
//					$products = D('Order_product')->field('pro_num')
//						->where(array('product_id' => $nowProduct['product_id'],
//						              'order_id'   => $order['order_id']))->select();
//					foreach ($products as $product) {
//						$buy_quantity += $product['pro_num']; //购买数量
//					}
//				}
//			}
//
//			$tmp_quantity = intval(trim($_POST['quantity']));
//			if(($buy_quantity + $tmp_quantity) > $nowProduct['buyer_quota']) { //限购
//				json_return(1001, '商品限购，请修改购买数量');
//			}
//		}

        $postage = $nowProduct['postage'];
        $profit = $nowProduct['price'] * 1.00 - $nowProduct['cost_price'] * 1.00;
        $factory = $nowProduct['factory_price'] * 1.00;

        if (empty($nowProduct['has_property'])) {
            $skuId = 0;
            $propertiesStr = '';
            $product_price = $nowProduct['price'];
            if ($nowProduct['quantity'] < $quantity) json_return(1001, '商品库存不足');
        } else {
            if (!$skuId) json_return(1001, '请选择商品属性');
            //判断库存是否存在
            $nowSku = D('Product_sku')->field('`sku_id`,`product_id`,`properties`,`price`,`cost_price`,`quantity`')
                ->where(array('sku_id' => $skuId))->find();
            if ($nowSku['quantity'] < $quantity) json_return(1001, '商品库存不足');

            $tmpPropertiesArr = explode(';', $nowSku['properties']);
            $properties = $propertiesValue = $productProperties = array();
            foreach ($tmpPropertiesArr as $value) {
                $tmpPro = explode(':', $value);
                $properties[] = $tmpPro[0];
                $propertiesValue[] = $tmpPro[1];
            }
            if (count($properties) == 1) {
                $findPropertiesArr =
                    D('Product_property')->field('`pid`,`name`')->where(array('pid' => $properties[0]))->select();
                $findPropertiesValueArr =
                    D('Product_property_value')->field('`vid`,`value`')->where(array('vid' => $propertiesValue[0]))
                        ->select();
            } else {
                $findPropertiesArr =
                    D('Product_property')->field('`pid`,`name`')->where(array('pid' => array('in', $properties)))
                        ->select();
                $findPropertiesValueArr =
                    D('Product_property_value')->field('`vid`,`value`')->where(array('vid' => array('in', $propertiesValue)))->select();
            }
            foreach ($findPropertiesArr as $value) {
                $propertiesArr[$value['pid']] = $value['name'];
            }
            foreach ($findPropertiesValueArr as $value) {
                $propertiesValueArr[$value['vid']] = $value['value'];
            }
            foreach ($properties as $key => $value) {
                $productProperties[] =
                    array('pid' => $value, 'name' => $propertiesArr[$value], 'vid' => $propertiesValue[$key],
                        'value' => $propertiesValueArr[$propertiesValue[$key]]);
            }
            $propertiesStr = serialize($productProperties);
            if ($nowProduct['product_id'] != $nowSku['product_id']) json_return(1002, '商品属性选择错误');

            $product_price = $nowSku['price'];
            $profit = $nowSku['price'] * 1.00 - $nowSku['cost_price'] * 1.00;
            $factory = $nowSku['factory_price'] * 1.00;
        }

//		if($_POST['activityId']) {
//			$nowActivity = M('Product_qrcode_activity')->getActivityById($_POST['activityId']);
//			if($nowActivity['product_id'] == $nowProduct['product_id']) {
//				if($nowActivity['type'] == 0) {
//					$product_price = round($product_price * $nowActivity['discount'] / 10, 2);
//				}
//				else {
//					$product_price = $product_price - $nowActivity['price'];
//				}
//			}
//		}

        if (!$quantity) json_return(1003, '请输入购买数量');
        $store_id = $now_store ? $now_store['store_id'] : $nowProduct['store_id'];
        if (empty($_POST['isAddCart'])) {    // 立即购买
            $order_no = date('YmdHis', $_SERVER['REQUEST_TIME']) . mt_rand(100000, 999999);
            $data_order['agent_id'] = $nowProduct['store_id'];
            $data_order['store_id'] = $store_id;
            $data_order['order_no'] = $data_order['trade_no'] = $order_no;
            $data_order['uid'] = $wap_user['uid'];
            $data_order['sub_total'] = ($product_price * 100) * $quantity / 100;
            if ($postage > 0 && $data_order['sub_total'] >= option('config.free_postage') * 1.00)
                $postage = 0.00;
            $data_order['total'] = $data_order['sub_total'] + $postage;

            $data_order['pro_num'] = $quantity;
            $data_order['profit'] = $profit * $quantity;
            $data_order['factory'] = $factory * $quantity;
            $data_order['pro_count'] = '1';
            $data_order['postage'] = $postage;
            $data_order['type'] = $_POST['type'] ? (int)$_POST['type'] : 0;
            $data_order['bak'] = $_POST['bak'] ? serialize($_POST['bak']) : '';
            $data_order['add_time'] = $_SERVER['REQUEST_TIME'];

            $order_id = $db_order->data($data_order)->add();
            if (empty($order_id)) {
                json_return(1004, '订单产生失败，请重试'); // .$db_order->last_sql
            }

            $data_order_product['order_id'] = $order_id;
            $data_order_product['product_id'] = $nowProduct['product_id'];
            $data_order_product['sku_id'] = $skuId;
            $data_order_product['sku_data'] = $propertiesStr;
            $data_order_product['pro_num'] = $quantity;
            $data_order_product['pro_price'] = $product_price;
            $data_order_product['comment'] = !empty($_POST['custom']) ? serialize($_POST['custom']) : '';
            $data_order_product['pro_weight'] = $nowProduct['weight'];

            if (D('Order_product')->data($data_order_product)->add()) {
                /**
                 * @var $storeUserDataM store_user_data_model
                 */
                $storeUserDataM = M('Store_user_data');
                $storeUserDataM->upUserData($supplier_id, $wap_user['uid'], 'unpay');

                // 产生提醒
                import('source.class.Notify');
                $retult = Notify::getInstance()->orderUpdate($wap_user['openid'],
                    $config['wap_site_url'] . '/order.php?orderid=' . $order_id,
                    '你好，你已下单成功。',
                    $data_order['order_no'],
                    '下单成功，待付款',
                    '请及时付款，如有疑问请联系客服。');

                json_return(0, $config['orderid_prefix'] . $order_no);
            } else {
                $db_order->where(array('order_id' => $order_id))->delete();
                json_return(1005, '订单产生失败，请重试');
            }
        } else {
            $data_user_cart = D('User_cart')
                ->where(array(
                    'uid' => $wap_user['uid'],
                    'store_id' => $store_id,
                    'product_id' => $nowProduct['product_id'],
                    'sku_id' => $skuId
                ))
                ->find();
            if (empty($data_user_cart)) {
                $data_user_cart['uid'] = $wap_user['uid'];
                $data_user_cart['store_id'] = $store_id;
                $data_user_cart['agent_id'] = $nowProduct['store_id'];
                $data_user_cart['product_id'] = $nowProduct['product_id'];
                $data_user_cart['sku_id'] = $skuId;
                $data_user_cart['sku_data'] = $propertiesStr;
                $data_user_cart['pro_num'] = $quantity;
                $data_user_cart['pro_price'] = $product_price;
                $data_user_cart['add_time'] = $_SERVER['REQUEST_TIME'];
                $data_user_cart['comment'] = !empty($_POST['custom']) ? serialize($_POST['custom']) : '';

                $isOK = D('User_cart')->data($data_user_cart)->add();
                if ($isOK) json_return(0, '添加成功');
            } else {
                if (D('User_cart')->where(array('id' => $data_user_cart['id']))->setInc('pro_num', $quantity))
                    json_return(0, '添加成功');
            }

            json_return(1005, '订单产生失败，请重试');
        }
        break;
    case 'cart_count':
        $condition_user_cart['uid'] = $wap_user['uid'];
        $condition_user_cart['store_id'] = $now_store['store_id'];
        $return['count'] = D('User_cart')->where($condition_user_cart)->count('id');
        $return['store_id'] = $now_store['store_id'];
        json_return(0, $return);
    case 'pay':
        $model_order = M('Order');
        $db_order = D('Order');
        $nowOrder = $model_order->find($_POST['orderNo']);
        if (empty($nowOrder['total']))
            json_return(1006, '订单异常，请稍后再试');

        $trade_no = date('YmdHis', $_SERVER['REQUEST_TIME']) . mt_rand(100000, 999999);
//		if($nowOrder['status'] > 1 && $nowOrder['payment_method'] == 'codpay')
//			json_return(1008, './order.php?orderid=' . $nowOrder['order_id']);

        if ($nowOrder['status'] > 1)
            json_return(1007, '该订单已支付或关闭，不再允许付款！');

        //TODO  验证
        if ($_POST['isImport']) {
            if ($_POST['real_name'] == '') {
                json_return(1007, '全球购商品必须提交真实姓名！');
            }
            if ($_POST['id_card'] == '') {
                json_return(1007, '全球购商品必须提交真实身份证号！');
            }
        }

        $offline_payment = false;
        if (empty($nowOrder['status'])) {
            if (empty($nowOrder['order_id']))
                json_return(1008, '该订单不存在');

            $order_store = M('Store')->wap_getStore($nowOrder['store_id']);

            $condition_order['order_id'] = $nowOrder['order_id'];
            $condition_order['uid'] = $wap_user['uid'];

            $data_order = array();
            $address = M('User_address')->getAdressById('', $wap_user['uid'], $_POST['address_id']);
            if (empty($address)) json_return(1009, '该地址不存在');
            $data_order['shipping_method'] = 'express'; //快递发货
            $data_order['address_user'] = $address['name'];
            $data_order['address_tel'] = $address['tel'];
            $data_order['address'] = serialize(array('address' => $address['address'], 'province' => $address['province_txt'], 'province_code' => $address['province'], 'city' => $address['city_txt'], 'city_code' => $address['city'], 'area' => $address['area_txt'], 'area_code' => $address['area'],));

            $data_order['status'] = '1';

            if (!empty($_POST['msg'])) {
                $data_order['comment'] = $_POST['msg'];
            }
            $data_order['trade_no'] = $trade_no;
            if (!$db_order->where($condition_order)->data($data_order)->save()) {
                json_return(1010, '订单信息保存失败');
            }

            // 减少库存 因为支付的特殊性，不处理是否有过修改
            $orderProductList = D('Order_product')->where(array('order_id' => $nowOrder['order_id']))->select();
            $product_model = D('Product');
            $product_sku_model = D('Product_sku');

            foreach ($orderProductList as $value) {
                if ($value['sku_id']) {
                    $condition_product_sku['sku_id'] = $value['sku_id'];
                    $product_sku_model->where($condition_product_sku)->setInc('sales', $value['pro_num']);
                    $product_sku_model->where($condition_product_sku)->setDec('quantity', $value['pro_num']);
                }
                $condition_product['product_id'] = $value['product_id'];
                $product_model->where($condition_product)->setInc('sales', $value['pro_num']);
                $product_model->where($condition_product)->setDec('quantity', $value['pro_num']);
            }

            // 保存满减，优惠券信息
            // 优惠活动
//			$product_id_arr = array();
//			$store_id = 0;
//			$uid = 0;
//			$total_price = 0;
//			$product_price_arr = array();
//			foreach ($nowOrder['proList'] as $product) {
//				// 分销商品不参与满赠和使用优惠券
//				if($product['is_fx']) {
//					//$offline_payment = false;
//					continue;
//				}
//
//				$product_id_arr[] = $product['product_id'];
//				$store_id = $product['store_id'];
//				$uid = $product['uid'];
//				// 单个商品总价
//				$product_price_arr[$product['product_id']]['price'] = $product['pro_price'];
//				// 每个商品购买数量
//				$product_price_arr[$product['product_id']]['pro_num'] = $product['pro_num'];
//				// 所有商品价格
//				$total_price += $product['pro_price'] * $product['pro_num'];
//			}
//
//			$reward_arr = array();
//			$reward_arr['product_id_arr'] = $product_id_arr;
//			$reward_arr['store_id'] = $store_id;
//			$reward_arr['uid'] = $uid;
//
//			$product_arr = array();
//			$product_arr['product_price_arr'] = $product_price_arr;
//			$product_arr['total_price'] = $total_price;
//
//			import('source.class.Appmarket');
//			$reward_list = Appmarket::getAeward($reward_arr, $product_arr);
//			// 第一步抽出用户购买的产品有哪些优惠券
//			$user_coupon_list =
//				M('User_coupon')->getListByProductId($reward_list['product_price_list'], $store_id, $uid);
//			// 第二步计算出用户购买原产品可以使用哪些优惠券
//			$user_coupon_list = Appmarket::getCoupon($reward_list, $user_coupon_list);
//
//			// 用户享受的优惠券
//			$money = 0;
//			$pro_num = 0;
//			$pro_count = 0;
//			if($wap_user['uid']) {
//				foreach ($reward_list as $key => $reward) {
//					if($key === 'product_price_list') {
//						continue;
//					}
//
//					// 积分
//					if($reward['score'] > 0) {
//						M('Store_user_data')->changePoint($nowOrder['store_id'], $wap_user['uid'], $reward['score']);
//					}
//
//					// 送赠品
//					if(is_array($reward['present']) && count($reward['present']) > 0) {
//						foreach ($reward['present'] as $present) {
//							$data_order_product = array();
//							$data_order_product['order_id'] = $nowOrder['order_id'];
//							$data_order_product['product_id'] = $present['product_id'];
//
//							// 是否有属性，有则随机挑选一个属性
//							if($present['has_property']) {
//								$sku_arr = M('Product_sku')->getRandSku($present['product_id']);
//								$data_order_product['sku_id'] = $sku_arr['sku_id'];
//								$data_order_product['sku_data'] = $sku_arr['propertiey'];
//							}
//
//							$data_order_product['pro_num'] = 1;
//							$data_order_product['pro_price'] = 0;
//							$data_order_product['is_present'] = 1;
//
//							$pro_num++;
//							if(!in_array($present['product_id'], $product_id_arr)) {
//								$pro_count++;
//							}
//
//							D('Order_product')->data($data_order_product)->add();
//							unset($data_order_product);
//						}
//					}
//
//					// 送优惠券
//					if($reward['coupon']) {
//						$data_user_coupon = array();
//						$data_user_coupon['uid'] = $wap_user['uid'];
//						$data_user_coupon['store_id'] = $reward['coupon']['store_id'];
//						$data_user_coupon['coupon_id'] = $reward['coupon']['id'];
//						$data_user_coupon['card_no'] = String::keyGen();
//						$data_user_coupon['cname'] = $reward['coupon']['name'];
//						$data_user_coupon['face_money'] = $reward['coupon']['face_money'];
//						$data_user_coupon['limit_money'] = $reward['coupon']['limit_money'];
//						$data_user_coupon['start_time'] = $reward['coupon']['start_time'];
//						$data_user_coupon['end_time'] = $reward['coupon']['end_time'];
//						$data_user_coupon['is_expire_notice'] = $reward['coupon']['is_expire_notice'];
//						$data_user_coupon['is_share'] = $reward['coupon']['is_share'];
//						$data_user_coupon['is_all_product'] = $reward['coupon']['is_all_product'];
//						$data_user_coupon['is_original_price'] = $reward['coupon']['is_original_price'];
//						$data_user_coupon['description'] = $reward['coupon']['description'];
//						$data_user_coupon['timestamp'] = time();
//						$data_user_coupon['type'] = 2;
//						$data_user_coupon['give_order_id'] = $nowOrder['order_id'];
//
//						D('User_coupon')->data($data_user_coupon)->add();
//					}
//
//					$data = array();
//					$data['order_id'] = $nowOrder['order_id'];
//					$data['uid'] = $wap_user['uid'];
//					$data['rid'] = $reward['rid'];
//					$data['name'] = $reward['name'];
//					$data['content'] = serialize($reward);
//					$money += $reward['cash'];
//					D('Order_reward')->data($data)->add();
//				}
//
//				// 用户使用的优惠券
//				$coupon_id = $_POST['user_coupon_id'];
//				foreach ($user_coupon_list as $user_coupon) {
//					if($user_coupon['id'] == $coupon_id) {
//						$data = array();
//						$data['order_id'] = $nowOrder['order_id'];
//						$data['uid'] = $wap_user['uid'];
//						$data['coupon_id'] = $user_coupon['coupon_id'];
//						$data['name'] = $user_coupon['cname'];
//						$data['user_coupon_id'] = $coupon_id;
//						$data['money'] = $user_coupon['face_money'];
//
//						$money += $user_coupon['face_money'];
//						D('Order_coupon')->data($data)->add();
//
//						// 将用户优惠券改为已使用
//						$data = array();
//						$data['is_use'] = 1;
//						$data['use_time'] = time();
//						$data['use_order_id'] = $nowOrder['order_id'];
//						D('User_coupon')->where(array('id' => $coupon_id))->data($data)->save();
//						break;
//					}
//				}
//			}
//
//			// 更改订单金额
//			$total = max(0, $nowOrder['sub_total'] + $nowOrder['postage'] + $nowOrder['float_amount'] - $money);
//			$pro_count = $nowOrder['pro_count'] + $pro_count;
//			$pro_num = $nowOrder['pro_num'] + $pro_num;

            $data = array();
            // 实时的帐户信息
            $wap_user = D('User')->where(array('uid' => $wap_user['uid'], 'status' => 1))->find();
            if (empty($wap_user)) {
                json_return(1009, '获取账户余额和积分失败！');
            }
            $balance = is_numeric($_POST['balance']) ? floatval($_POST['balance']) : 0.00;
            if ($balance > 0 && $balance > $wap_user['balance']) {
                json_return(1009, '使用余额数量超过账户拥有余额数量！');
            }
            $point = is_numeric($_POST['point']) ? intval($_POST['point']) : 0;
            if ($point > 0 && $point > $wap_user['point'] * 1) {
                json_return(1009, '使用积分数量超过账户拥有积分数量！');
            }
//            $total = $nowOrder['total'] * 1.00 - ($balance + $point * 1.00 / $config['point_exchange']);
            $total = round(($nowOrder['sub_total'] * 1.00 + $nowOrder['postage'] * 1.00) -
                ($balance + $point * 1.00 / $config['point_exchange'] * 1), 2);
            if ($total < 0) {
                json_return(1009, '使用余额数量和积分抵现金总额超出订单总额啦！');
            }
            $data['point'] = $point;

            // 专属积分优先使用。
            $excl_point = $wap_user['excl_point'] * 1;
            $excl_point = $excl_point - $point;
            if ($excl_point < 0) $excl_point = 0;

            $data['balance'] = $balance;
            //$data['total'] = $total;
            $data['pay_money'] = $nowOrder['pay_money'] = $total;
            //$data['total'] = $total;
            //$data['pro_count'] = $pro_count;
            //$data['pro_num'] = $pro_num;
            if ($total == 0) {
                $data['paid_time'] = time();
                $data['status'] = 2;
                $data['payment_method'] = 'account';

                $nowOrder['status'] = 2;
            } else {
                $data['status'] = 1;
            }

//			if($_POST['payType'] == 'offline' && $offline_payment) {
//				$data['status'] = 2;
//				$data['payment_method'] = 'codpay';
//			}
            if (!$db_order->where($condition_order)->data($data)->save()) {
                json_return(1010, '保存订单金额失败！');
            }
            if ($point > 0 || $balance > 0) {
                if ($point > 0) {
                    // 减少专属积分的数量
                    D('User')->where(array('uid' => $wap_user['uid'], 'status' => 1))
                        ->data(array('excl_point' => $excl_point))
                        ->save();
                }

                $nowOrder['point'] = $point;
                $nowOrder['balance'] = $balance;
                // 余额支付和积分抵现
                $model_income = M('User_income');
                $model_income->buyOff($nowOrder);
                if ($total == 0) {
//					Notify::getInstance()->notifyAgent($wap_user['openid'],
//						$config['wap_site_url'] . '/order.php?orderid=' . $order_id,
//						// 'first', 'keyword1', 'keyword2', 'keyword3', 'keyword4', 'remark'
//						'有新的订单付款成功了，快去发货吧',
//						$data_order['order_no'],
//						'支付成功，待发货',
//						'您的订单已支付成功，已通知供商发货啦！');

                    // 通过帐户余额支付成功
                    $model_income->buyReturn($nowOrder);

                    // D('Activity_lottery_log')->data(array('uid' => $wap_user['uid'], 'order_id' => $nowOrder['order_id'],))->add();

                    json_return(0, '/wap/order.php?orderid=' . $nowOrder['order_id']);
                    exit;
                }
            }
            //$nowOrder['total'] = $total;
        } else {
            $order_store = M('Store')->wap_getStore($nowOrder['store_id']);
//			if($order_store['offline_payment']) {
//				$offline_payment = true;
//			}

//			foreach ($nowOrder['proList'] as $product) {
//				// 分销商品不参与满赠和使用优惠券
//				if($product['is_fx']) {
//					$offline_payment = false;
//					break;
//				}
//			}

            $data_order = array();
//			if($_POST['payType'] == 'offline' && $offline_payment) {
//				$data_order['status'] = 2;
//				$data_order['payment_method'] = 'codpay';
//			}

            $condition_order['order_id'] = $nowOrder['order_id'];
            $data_order['trade_no'] = $trade_no;

            $data_order['real_name'] = $_POST['real_name'];
            $data_order['id_card'] = $_POST['id_card'];
            if (!$db_order->where($condition_order)->data($data_order)->save()) {
                json_return(1010, '订单信息保存失败');
            }
        }

        $nowOrder['trade_no'] = $trade_no;
        $payType = $_POST['payType'];

//		if($_POST['payType'] == 'offline' && !$offline_payment) {
//			json_return(1012, '对不起，订单不支付货到付款');
//		}
//		else if($_POST['payType'] == 'offline' && $offline_payment) {
//			json_return(0, '/wap/order.php?orderid=' . $nowOrder['order_id']);
//		}

        $payMethodList = M('Config')->get_pay_method();
        if (empty($payMethodList[$payType])) {
            json_return(1012, '您选择的支付方式不存在<br/>请更新支付方式');
        }
        $nowOrder['order_no_txt'] = option('config.orderid_prefix') . $nowOrder['order_no'];
        switch ($payType) {
            case 'yeepay':
                import('source.class.pay.Yeepay');
                $payClass = new Yeepay($nowOrder, $payMethodList[$payType]['config'], $wap_user);
                $payInfo = $payClass->pay();
                if ($payInfo['err_code']) {
                    json_return(1013, $payInfo['err_msg']);
                } else {
                    json_return(0, $payInfo['url']);
                }
                break;
            case 'tenpay':
                import('source.class.pay.Tenpay');
                $payClass = new Tenpay($nowOrder, $payMethodList[$payType]['config'], $wap_user);
                $payInfo = $payClass->pay();
                if ($payInfo['err_code']) {
                    json_return(1013, $payInfo['err_msg']);
                } else {
                    json_return(0, $payInfo['url']);
                }
                break;
            case 'weixin':
                import('source.class.pay.Weixin');
//				if ($nowOrder['useStorePay']) {
//					$weixin_bind_info = D('Weixin_bind')->where(array('store_id' => $nowOrder['store_id']))->find();
//					if (empty($weixin_bind_info) || empty($weixin_bind_info['wxpay_mchid']) ||
//						empty($weixin_bind_info['wxpay_key'])
//					) {
//						json_return(1014, '商家未配置正确微信支付');
//					}
//					$payMethodList[$payType]['config'] =
//						array('pay_weixin_appid' => $weixin_bind_info['authorizer_appid'],
//						      'pay_weixin_mchid' => $weixin_bind_info['wxpay_mchid'],
//						      'pay_weixin_key'   => $weixin_bind_info['wxpay_key']);
//					$openid = $nowOrder['storeOpenid'];
//				}
//				else {
                $openid = $_SESSION['openid'];
//				}
                logs($openid . ',' .
                    var_export($nowOrder, true) . ',' .
                    var_export($payMethodList[$payType]['config'], true) . ',' .
                    var_export($wap_user, true), 'INFO');
                $payClass = new Weixin($nowOrder, $payMethodList[$payType]['config'], $wap_user['openid'], 'pay');
                $payInfo = $payClass->pay();
                logs('payInfo:' . json_encode($payInfo), 'INFO');
                if ($payInfo['err_code']) {
                    json_return(1013, $payInfo['err_msg']);
                } else {
                    json_return(0, json_decode($payInfo['pay_data']));
                }
                break;
        }
        break;
    case 'mergepay':
        $count = 0;
        $order_no = preg_replace('#' . option('config.mergeid_prefix') . '#', '', $_POST['orderNo'], 1, $count);
        if ($count == 0)
            pigcms_tips('订单号不存在！', 'none');

        $db_merge = D('Order_merge');
        $nowMerge = $db_merge->where(array('order_no' => $order_no))->find();
        if (empty($nowMerge))
            json_return(1008, '该合并订单不存在！');

        if (empty($nowMerge['total']))
            json_return(1006, '订单异常，请稍后再试');

        $trade_no = date('YmdHis', $_SERVER['REQUEST_TIME']) . mt_rand(100000, 999999);
        if ($nowMerge['status'] > 1)
            json_return(1007, '该订单已支付或关闭，不再允许付款！');

        $db_order = D('Order');
        if (empty($nowMerge['status'])) {
            // status < 1 时保存收货地址信息并且减少库存
            $address = M('User_address')->getAdressById('', $wap_user['uid'], $_POST['address_id']);
            if (empty($address)) json_return(1009, '该地址不存在');

            $data_order = array(
                'shipping_method' => 'express',
                'address_user' => $address['name'],
                'address_tel' => $address['tel'],
                'address' => serialize(array(
                    'address' => $address['address'],
                    'province' => $address['province_txt'],
                    'province_code' => $address['province'],
                    'city' => $address['city_txt'],
                    'city_code' => $address['city'],
                    'area' => $address['area_txt'],
                    'area_code' => $address['area'],
                )),
                'status' => 1,
                'trade_no' => $trade_no
            );

            // 分配给代理商
            // 目前只给平台商城
            // SELECT store_id FROM `tp_store` where
            //$data_order['agent_id'] = M('Store')->getAgentId($nowMerge, $address);

            $data_merge = array('trade_no' => $trade_no, 'status' => 1);

            if (!empty($_POST['msg'])) {
                $arrMsg = explode('||', $_POST['msg']);
                foreach ($arrMsg as $msg) {
                    if (empty($msg)) continue;

                    $arr = explode('|', $msg);
                    if (intval($arr[0]) == 0 || empty($arr[1])) continue;

                    $db_order->where(array('order_id' => $arr[0]))->data(array('comment' => $arr[1]))->save();
                }
            }

            $where = array('uid' => $wap_user['uid'], 'merge_id' => $nowMerge['merge_id']);
            // 将交易号等信息保存到订单
            if (!$db_order->where($where)->data($data_order)->save() ||
                !$db_merge->where($where)->data($data_merge)->save()
            ) {
                json_return(1010, '订单信息保存失败');
            }

            // 减少库存 因为支付的特殊性，不处理是否有过修改
            $product_model = D('Product');
            $product_sku_model = D('Product_sku');
            $orderProductList = D('Order_product')
                ->where("`order_id` in (select `order_id` from `tp_order` where `uid`=" .
                    $wap_user['uid'] . " and `merge_id`=" . $nowMerge['merge_id'] . ")")
                ->select();
            foreach ($orderProductList as $value) {
                if ($value['sku_id']) {
                    $condition_product_sku['sku_id'] = $value['sku_id'];
                    $product_sku_model->where($condition_product_sku)->setInc('sales', $value['pro_num']);
                    $product_sku_model->where($condition_product_sku)->setDec('quantity', $value['pro_num']);
                }
                $condition_product['product_id'] = $value['product_id'];
                $product_model->where($condition_product)->setInc('sales', $value['pro_num']);
                $product_model->where($condition_product)->setDec('quantity', $value['pro_num']);
            }

            $data = array();
            // 实时的帐户信息
            $wap_user = D('User')->where(array('uid' => $wap_user['uid'], 'status' => 1))->find();
            if (empty($wap_user)) {
                json_return(1009, '获取账户余额和积分失败！');
            }

            $balance = is_numeric($_POST['balance']) ? round($_POST['balance'], 2) : 0.00;
            if ($balance > 0 && $balance > $wap_user['balance']) {
                json_return(1009, '使用余额数量超过账户拥有余额数量！');
            }

            $point = is_numeric($_POST['point']) ? intval($_POST['point']) : 0;
            if ($point > 0 && $point > $wap_user['point'] * 1) {
                json_return(1009, '使用积分数量超过账户拥有积分数量！');
            }

            $total = round($nowMerge['total'] * 1.00 - ($balance + $point * 1.00 / $config['point_exchange'] * 1), 2);
            if ($total < 0) {
                json_return(1009, '使用余额数量和积分抵现金总额超出订单总额啦！');
            }

            $data['point'] = $point;
            $data['balance'] = $balance;
            // 减掉消费钱包余额
            $data['consumer'] = $wap_user['consumer'] - $balance;
            if ($data['consumer'] < 0) $data['consumer'] = 0;
            $data['pay_money'] = $nowMerge['pay_money'] = $total;
            if ($total == 0) {
                $data['paid_time'] = time();
                $data['status'] = 2;
                $data['payment_method'] = 'account';

                $nowMerge['status'] = 2;
            } else {
                $data['status'] = 1;
            }

            if (!$db_order->where($where)->data($data)->save() || !$db_merge->where($where)->data($data)->save()) {
                json_return(1010, '保存订单金额失败！');
            }

            if ($point > 0 || $balance > 0) {
                $nowMerge['point'] = $point;
                $nowMerge['balance'] = $balance;
                // 余额支付和积分抵现
                $model_income = M('User_income');
                $model_income->buyOff($nowMerge);
                if ($total == 0) {
                    // 通过帐户余额支付成功
                    // 批量 立返操作
                    $orders = $db_order->where($where)->select();
                    foreach ($orders as $order) {
                        $model_income->buyReturn($order);
                    }

                    json_return(0, '/wap/my_order.php'); // 合并付款返回订单中心
                    exit;
                }
            }
        } else {
            $data_order = array();
            $condition_order['order_id'] = $nowMerge['order_id'];
            $data_order['trade_no'] = $trade_no;
            if (!$db_order->where($condition_order)->data($data_order)->save()) {
                json_return(1010, '订单信息保存失败');
            }
        }

        $nowMerge['trade_no'] = $trade_no;
        $payType = $_POST['payType'];
        if ($payType != 'weixin') $payType = 'weixin';

        $payMethodList = M('Config')->get_pay_method();
        if (empty($payMethodList[$payType])) {
            json_return(1012, '您选择的支付方式不存在<br/>请更新支付方式');
        }
        $nowMerge['order_no_txt'] = option('config.orderid_prefix') . $nowMerge['order_no'];

        import('source.class.pay.Weixin');
        $openid = $_SESSION['openid'];
        logs($openid . ',' .
            var_export($nowMerge, true) . ',' .
            var_export($payMethodList[$payType]['config'], true) . ',' .
            var_export($wap_user, true), 'INFO');
        $payClass = new Weixin($nowMerge, $payMethodList[$payType]['config'], $wap_user['openid'], 'mergepay');
        $payInfo = $payClass->pay();
        logs('payInfo:' . json_encode($payInfo), 'INFO');
        if ($payInfo['err_code']) {
            json_return(1013, $payInfo['err_msg']);
        } else {
            json_return(0, json_decode($payInfo['pay_data']));
        }

        break;
    case 'refund' : //退款
        //1 根据订单状态确认是否可以直接退款
        //1.1 订单未发货  直接退款
        //1.2 订单已发货  要用户拒收，我们这边确认后才能给他退款
        //1.3 订单已收货  需要用户提交货运单，我们收到货之后确认退款
        //查看是否使用了积分和余额
        //购物立返的积分或者现金要扣除
        $model_order = M('Order');
        $model_income = M('User_income');
        $db_user = D('User');
        $nowOrder = $model_order->find($_POST['orderNo']);


        if ($nowOrder['status'] < 2) {
            json_return(1, '订单未支付，不能退款');
        } elseif ($nowOrder['status'] == 2) {
            if ($nowOrder['point'] / option('config.point_exchange') > $nowOrder['sub_total'] / 2) {
                json_return(1, '积分支付超过订单金额的一半，无法退货，请联系管理员');
            }
            //1 计算应退款额
            //2 支付
            //3 改变各种状态
            $result = $model_order->refundFee($nowOrder, $wap_user);
            json_return($result['err_code'], $result['err_msg']);
        } elseif ($nowOrder['status'] == 3 || ($nowOrder['status'] == 4 && strtotime("-7 days ") <= $nowOrder['sent_time'])) {
            json_return(1, '订单已发货，不能直接退款，请走退货流程');
        } elseif ($nowOrder['status'] == 5) {
            json_return(1, '订单已取消，不能退款');
        } elseif ($nowOrder['status'] == 6) {
            json_return(1, '已退款中的订单哟');
        } else {
            json_return(1, '订单状态异常，请联系管理员');
        }
//		$order_no = preg_replace('#' . option('config.orderid_prefix') . '#', '', $_POST['orderNo'], 1);
//		$income = $model_income->getPointAndIncomeByOrderNo(array('order_no' => $order_no,'type' => 1));
//		if(option('config.default_point'))
//		{
//			//默认为反积分
//			$diff_money = $income['point']/option('config.point_exchange');
//		} else
//		{
//			$diff_money = $income['income'];
//		}
//
//        //实际退回的钱  实际支付 - 返现的
//		if($nowOrder['pay_money']!= '0.00')
//		{
//			$refund_fee = $nowOrder['pay_money'] - $diff_money; //实际退回的钱
//			import('source.class.pay.Weixin');
//			$openid = $_SESSION['openid'];
//			$payType =  'weixin';//$_POST['payType'];
//			$payMethodList = M('Config')->get_pay_method();
//			$nowOrder['refund_fee'] =  $refund_fee;
//			//调用微信退款接口
//			logs($openid . ',' .
//				var_export($nowOrder, true) . ',' .
//				var_export($payMethodList[$payType]['config'], true) . ',' .
//				var_export($wap_user, true), 'INFO');
//			$weixin = new Weixin($nowOrder, $payMethodList[$payType]['config'], $wap_user['openid']);
//			$result = $weixin->refund();
//			logs('refundInfo:' . json_encode($result), 'INFO');
//
//			$model_order->refundOrder($nowOrder);
//
//			json_return($result['err_code'], $result['err_msg']);
//		} else
//		{
//			if($nowOrder['balance'] != '0.00')
//			{
//				$nowOrder['refund_fee'] = $nowOrder['balance'] = $nowOrder['balance'] - $diff_money; //实际退回的钱
//			}else
//			{
//				//全额积分付款 不能退款 直接返回
//				json_return(1, '全额积分付款，不允许退款！');
//			}
//			$model_order->refundOrder($nowOrder);
//			json_return(0, '退款成功！');
//		}
        break;
}
