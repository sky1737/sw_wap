<?php
/**
 *  支付异步通知
 */
require_once dirname(__FILE__) . '/global.php';

$payType = 'weixin';
$payMethodList = M('Config')->get_pay_method();
logs($_SERVER['REQUEST_URI'], 'INFO');
logs(json_encode($_POST), 'INFO');
if(empty($payMethodList[$payType])) {
	json_return(1009, '微信支付未正确配置，请联系系统管理员！');
}

import('source.class.pay.Weixin');
$payClass = new Weixin(array(), $payMethodList[$payType]['config'], '', 'mergepay');
$payInfo = $payClass->notice();
if($payInfo['err_code'] === 0) {
	pay_notice_call($payInfo, $payInfo['echo_content']);
}
else {
	pay_notice_call($payInfo);
}


function getSign($data, $salt)
{
	foreach ($data as $key => $value) {
		if(is_array($value)) {
			$validate[$key] = getSign($value, $salt);
		}
		else {
			$validate[$key] = $value;
		}

	}
	$validate['salt'] = $salt;
	sort($validate, SORT_STRING);

	return sha1(implode($validate));
}

function pay_notice_call($payInfo, $ok_msg = 'success', $err_msg = 'fail')
{
	if($payInfo['err_code'] === 0) {
		$db_merge = D('Order_merge');
		$db_order = D('Order');

		$nowMerge = $db_merge->where(array('trade_no' => $payInfo['order_param']['trade_no']))->find();
		if(!empty($nowMerge) && $nowMerge['status'] == 1) {
			$data = array(
				'third_id'       => $payInfo['order_param']['third_id'],
				'payment_method' => $payInfo['order_param']['pay_type'],
				'pay_money'      => $payInfo['order_param']['pay_money'],
				'paid_time'      => $_SERVER['REQUEST_TIME'],
				'status'         => 2);
			$where = array('merge_id' => $nowMerge['merge_id']);
			if($db_merge->where($where)->data($data)->save()
				&& $db_order->where($where)->data($data)->save()
			) {
				// 批量立返
				$orders = $db_order->where($where)->select();
				foreach ($orders as $order) {
					M('User_income')->buyReturn($order);
				}
				exit($ok_msg);
			}
			else {
				exit($err_msg);
			}
		}
		else {
			exit($err_msg);
		}
	}
	else {
		exit($ok_msg);
	}
}

//function pay($data)
//{
//    $order = M('Order');
//    $order_product = M('Order_product');
//    $fx_order = M('Fx_order');
//    $fx_order_product = M('Fx_order_product');
//    $store = M('Store');
//    $financial_record = M('Financial_record');
//    $store_supplier = M('Store_supplier');
//    $product_model = M('Product');
//    $product_sku = M('Product_sku');
//
//    $total = $data['total']; //付款总金额
//
//    //付款给供货商
//    $fx_order_id = explode(',', $data['order_id']); //合并支付会出现多个订单ID
//    $supplier = $store->getStore($data['supplier_id']); //供货商
//    //如果store_supplier中的seller_id字段值中有当前供货商并且type分销类型为1，则表示当前供货商同时也是分销商，则为其供货商添加分销订单
//    $seller_info = $store_supplier->getSeller(array('seller_id' => $data['supplier_id'], 'type' => 1));
//    if (!empty($seller_info)) {
//        $is_supplier = false;
//    } else {
//        $is_supplier = true;
//    }
//    $seller = $store->getStore($data['seller_id']); //分销商
//    if ($total > 0) {
//        // 供货商不可用余额和收入加商品成本
//        if ($store->setUnBalanceInc($data['supplier_id'], $total) && $store->setIncomeInc($data['supplier_id'], $total)) {
//            foreach ($fx_order_id as $id) {
//                // 修改分销订单状态为等待供货商发货并且关联供货商订单id
//                $fx_order->edit(array('fx_order_id' => $id), array('status' => 2, 'paid_time' => time()));
//                $fx_order_info = $fx_order->getOrder($data['seller_id'], $id); //分销订单详细
//                $order_id = $fx_order_info['order_id']; //主订单ID
//                //主订单分销商品
//                $fx_products = $order_product->getFxProducts($order_id, $id, $is_supplier);
//                $order_info = $order->getOrder($data['seller_id'], $order_id);
//                $order_trade_no = $order_info['trade_no']; //主订单交易号
//                unset($order_info['order_id']);
//                $order_info['order_no']       = date('YmdHis',time()).mt_rand(100000,999999);
//                $order_info['store_id']       = $data['supplier_id'];
//                $order_info['trade_no']       = date('YmdHis',time()).mt_rand(100000,999999);
//                $order_info['third_id']       = '';
//                $order_info['uid']            = $seller['uid']; //下单用户（分销商）
//                $order_info['session_id']     = '';
//                $order_info['postage']        = $fx_order_info['postage'];
//                $order_info['sub_total']      = $fx_order_info['cost_sub_total'];
//                $order_info['total']          = $fx_order_info['cost_total'];
//                $order_info['status']         = 2; //未发货
//                $order_info['pro_count']      = 0; //商品种类数量
//                $order_info['pro_num']        = $fx_order_info['quantity']; //商品件数
//                $order_info['payment_method'] = 'balance';
//                $order_info['type']           = 3; //分销订单
//                $order_info['add_time']       = time();
//                $order_info['paid_time']      = time();
//                $order_info['sent_time']      = 0;
//                $order_info['cancel_time']    = 0;
//                $order_info['complate_time']  = 0;
//                $order_info['refund_time']    = 0;
//                $order_info['star']           = 0;
//                $order_info['pay_money']      = $fx_order_info['cost_total'];
//                $order_info['cancel_method']  = 0;
//                $order_info['float_amount']   = 0;
//                $order_info['is_fx']          = 0;
//                $order_info['fx_order_id']    = $id; //关联分销商订单id（fx_order）
//                $order_info['user_order_id']  = $fx_order_info['user_order_id'];
//                if ($new_order_id = $order->add($order_info)) { //向供货商提交一个新订单
//                    $suppliers = array();
//                    foreach ($fx_products as $key => $fx_product) {
//                        unset($fx_product['id']);
//                        //获取分销商品的来源
//                        $product_info = $product_model->get(array('product_id' => $fx_product['product_id']), 'source_product_id,original_product_id');
//                        if (!empty($product_info['source_product_id'])) {
//                            $fx_product['product_id'] = $product_info['source_product_id'];
//
//                            $properties = ''; //商品属性字符串
//                            if (!empty($fx_product['sku_data'])) {
//                                $sku_data = unserialize($fx_product['sku_data']);
//                                $skus = array();
//                                foreach($sku_data as $sku) {
//                                    $skus[] = $sku['pid'] . ':' . $sku['vid'];
//                                }
//                                $properties = implode(';', $skus);
//                            }
//                            if (!empty($properties)) { //有属性
//                                $sku = $product_sku->getSku($fx_product['product_id'], $properties);
//                                $fx_product['pro_price'] = $sku['cost_price']; //分销来源商品的成本价格
//                                $fx_product['sku_id'] = $sku['sku_id'];
//                            } else { //无属性
//                                $source_product_info = $product_model->get(array('product_id' => $fx_product['product_id']), 'price,cost_price');
//                                $fx_product['pro_price'] = $source_product_info['cost_price']; //分销来源商品的成本价格
//                            }
//                        }
//
//                        $fx_product['order_id']          = $new_order_id;
//                        $fx_product['pro_price']         = $fx_product['price'];
//                        $fx_product['is_packaged']       = 0;
//                        $fx_product['in_package_status'] = 0;
//                        //判断是否是店铺自有商品
//                        $super_product_info = $product_model->get(array('product_id' => $product_info['source_product_id']), 'source_product_id,original_product_id');
//                        if (empty($seller_info) || empty($super_product_info['source_product_id'])) { //供货商或商品供货商
//                            $fx_product['is_fx']             = 0;
//                        } else {
//                            $fx_product['is_fx']             = 1;
//                        }
//                        unset($fx_product['price']);
//                        $order_product->add($fx_product); //添加新订单商
//                        $fx_products[$key]['pro_price'] = $fx_product['pro_price'];
//                        $fx_products[$key]['source_product_id'] = $fx_product['product_id'];
//                        $suppliers[] = $fx_product['supplier_id'];
//                    }
//                    //修改订单供货商
//                    $suppliers = array_unique($suppliers);
//                    $suppliers = implode(',', $suppliers);
//                    D('Order')->where(array('order_id' => $new_order_id))->data(array('suppliers' => $suppliers))->save();
//
//                    //添加供货商财务记录（收入）
//                    $data_record = array();
//                    $data_record['store_id']         = $data['supplier_id'];
//                    $data_record['order_id'] 		 = $new_order_id;
//                    $data_record['order_no'] 		 = $order_info['order_no'];
//                    $data_record['income']  		 = $fx_order_info['cost_total'];
//                    $data_record['type']    		 = 5; //分销
//                    $data_record['balance']     	 = $supplier['income'];
//                    $data_record['payment_method']   = 'balance';
//                    $data_record['trade_no']         = $order_info['trade_no'];
//                    $data_record['add_time']         = time();
//                    $data_record['status']           = 1;
//                    $data_record['user_order_id']    = $order_info['user_order_id'];
//                    $financial_record_id = D('Financial_record')->data($data_record)->add();
//
//                    //判断供货商，如果上级供货商是分销商，添加分销订单
//                    if (!empty($seller_info)) {
//                        $cost_sub_total = 0;
//                        $sub_total = 0;
//                        $tmp_fx_products = array();
//                        foreach ($fx_products as $k => $fx_product) {
//                            $properties = ''; //商品属性字符串
//                            if (!empty($fx_product['sku_data'])) {
//                                $sku_data = unserialize($fx_product['sku_data']);
//                                $skus = array();
//                                foreach($sku_data as $sku) {
//                                    $skus[] = $sku['pid'] . ':' . $sku['vid'];
//                                }
//                                $properties = implode(';', $skus);
//                            }
//                            //获取分销商品的来源
//                            $product_info = $product_model->get(array('product_id' => $fx_product['product_id']), 'source_product_id,original_product_id');
//                            $source_product_id = $product_info['source_product_id']; //分销来源商品
//                            $original_product_id = $product_info['original_product_id'];
//                            if (empty($source_product_id) || $original_product_id == $source_product_id) { //商品供货商或商品供货商为上级分销商
//                                unset($fx_products[$k]);
//                                continue;
//                            }
//                            $tmp_fx_product = $fx_product;
//                            if (!empty($seller_info) && !empty($product_info['original_product_id'])) {
//                                $product_info = $product_model->get(array('product_id' => $source_product_id), 'source_product_id,original_product_id');
//                                $source_product_id = $product_info['source_product_id']; //分销来源商品
//                            }
//                            if (!empty($properties)) { //有属性
//                                $sku = $product_sku->getSku($source_product_id, $properties);
//                                //$price = $sku['price'];
//                                $cost_price = $sku['cost_price']; //分销来源商品的成本价格
//                                $sku_id = $sku['sku_id'];
//                            } else { //无属性
//                                $source_product_info = $product_model->get(array('product_id' => $source_product_id), 'price,cost_price');
//                                //$price = $source_product_info['price'];
//                                $cost_price = $source_product_info['cost_price']; //分销来源商品的成本价格
//                                $sku_id = 0;
//                            }
//                            $cost_sub_total += $cost_price;
//                            $sub_total += $fx_product['pro_price'];
//                            $tmp_fx_product['product_id'] = $source_product_id;
//                            $tmp_fx_product['price'] = $fx_product['pro_price'];
//                            $tmp_fx_product['cost_price'] = $cost_price;
//                            $tmp_fx_product['sku_id'] = $sku_id;
//                            $tmp_fx_product['original_product_id'] = $original_product_id;
//                            $tmp_fx_products[] = $tmp_fx_product;
//                        }
//                        if (!empty($fx_products)) {
//                            $fx_order_no = date('YmdHis',$_SERVER['REQUEST_TIME']).mt_rand(100000,999999); //分销订单号
//                            //运费
//                            $fx_postages = array();
//                            if (!empty($order_info['fx_postage'])) {
//                                $fx_postages = unserialize($order_info['fx_postage']);
//                            }
//                            $postage = !empty($fx_postages[$seller_info['supplier_id']]) ? $fx_postages[$seller_info['supplier_id']] : 0;
//                            $data2 = array(
//                                'fx_order_no'      => $fx_order_no,
//                                'uid'              => $order_info['uid'],
//                                'order_id'         => $new_order_id,
//                                'order_no'         => $order_info['order_no'],
//                                'fx_trade_no'      => $data['trade_no'],
//                                'supplier_id'      => $seller_info['supplier_id'],
//                                'store_id'         => $data['supplier_id'],
//                                'quantity'         => $fx_order_info['quantity'],
//                                'sub_total'        => $sub_total,
//                                'cost_sub_total'   => $cost_sub_total,
//                                'postage'          => $postage,
//                                'total'            => ($sub_total + $postage),
//                                'cost_total'       => ($cost_sub_total + $postage),
//                                'delivery_user'    => $order_info['address_user'],
//                                'delivery_tel'     => $order_info['address_tel'],
//                                'delivery_address' => $order_info['address'],
//                                'add_time'         => time(),
//                                'user_order_id'    => $order_info['user_order_id']
//                            );
//                            if ($fx_order_id = $fx_order->add($data2)) { //添加分销商订单
//                                foreach ($tmp_fx_products as $tmp_fx_product) {
//                                    if (!empty($tmp_fx_product['product_id'])) {
//                                        $product_info = D('Product')->field('store_id, original_product_id')->where(array('product_id' => $tmp_fx_product['original_product_id']))->find();
//                                        $tmp_supplier_id = $product_info['store_id'];
//                                        $fx_order_product->add(array('fx_order_id' => $fx_order_id, 'product_id' => $tmp_fx_product['product_id'], 'source_product_id' => $tmp_fx_product['source_product_id'], 'price' => $tmp_fx_product['price'], 'cost_price' => $tmp_fx_product['cost_price'], 'quantity' => $tmp_fx_product['pro_num'], 'sku_id' => $tmp_fx_product['sku_id'], 'sku_data' => $tmp_fx_product['sku_data'], 'comment' => $tmp_fx_product['comment']));
//                                    }
//                                }
//                                if (!empty($tmp_supplier_id)) { //修改订单，设置分销商
//                                    D('Fx_order')->where(array('fx_order_id' => $fx_order_id))->data(array('suppliers' => $tmp_supplier_id))->save();
//                                }
//                            }
//                            //获取分销利润
//                            if (!empty($financial_record_id) && !empty($data2['cost_total'])) {
//                                $profit = $data2['total'] - $data2['cost_total'];
//                                if ($profit > 0) {
//                                    D('Financial_record')->where(array('id' => $financial_record_id))->data(array('profit' => $profit))->save();
//                                }
//                            }
//                        }
//                    }
//
//                    //分销商不可用余额和收入减商品成本
//                    if ($store->setUnBalanceDec($data['seller_id'], $fx_order_info['cost_total']) && $store->setIncomeDec($data['seller_id'], $fx_order_info['cost_total'])) {
//                        //添加分销商财务记录（支出）
//                        $order_no = $order_info['order_no'];
//                        $data_record = array();
//                        $data_record['store_id']         = $data['seller_id'];
//                        $data_record['order_id'] 		 = $order_id;
//                        $data_record['order_no'] 		 = $order_no;
//                        $data_record['income']  		 = (0 - $fx_order_info['cost_total']);
//                        $data_record['type']    		 = 5; //分销
//                        $data_record['balance']     	 = $seller['income'];
//                        $data_record['payment_method']   = 'balance';
//                        $data_record['trade_no']         = $order_trade_no;
//                        $data_record['add_time']         = time();
//                        $data_record['status']           = 1;
//                        $data_record['user_order_id']    = $order_info['user_order_id'];
//                        D('Financial_record')->data($data_record)->add();
//                    } else { // 操作失败，记录日志文件
//                        $supplier_name = $supplier['name'];
//                        $seller_name = $seller['name'];
//                        $dir = './upload/pay/';
//                        if(!is_readable($dir))
//                        {
//                            is_file($dir) or mkdir($dir, 0777);
//                        }
//                        file_put_contents($dir . 'error.txt', '[' . date('Y-m-d H:i:s') . '] 付款给供货商失败，订单类型：分销，订单ID：' . $order_id . '，交易单号：' . $order_trade_no . '，供货商（收款方）：' . $supplier_name . '，分销商（付款方）：' . $seller_name . '，付款金额：' . $fx_order_info['cost_total'] . '元，请手动从 ' . $seller_name . ' 账户余额中减' . $fx_order_info['cost_total'] . '元' . PHP_EOL, FILE_APPEND );
//                        return array('err_code' => 1005, 'err_msg' => '付款失败，请联系客服处理，交易单号：' . $order_trade_no);
//                    }
//                }
//            }
//            return array('err_code' => 0, 'err_msg' => '付款成功，等待供货商发货');
//        } else {
//            return array('err_code' => 1004, 'err_msg' => '付款失败');
//        }
//    } else {
//        return array('err_code' => 1003, 'err_msg' => '付款金额无效');
//    }
//}


