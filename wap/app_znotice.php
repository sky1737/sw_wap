<?php
/**
 *  支付异步通知
 */
require_once dirname(__FILE__) . '/global.php';

$payType = 'weixin';
$payMethodList = M('Config')->get_pay_method();
//logs($_SERVER['REQUEST_URI'], 'INFO');
//logs(json_encode($GLOBALS), 'GLOBALS');

import('source.class.pay.Weixin');
$payClass = new Weixin(array(), $payMethodList[$payType]['config'], '', 'app_z');
$payInfo = $payClass->notice();
logs(json_encode($payInfo), 'PayInfo');
if ($payInfo['err_code'] === 0) {
    pay_notice_call($payInfo, $payInfo['echo_content']);
} else {
    pay_notice_call($payInfo);
}

function getSign($data, $salt)
{
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $validate[$key] = getSign($value, $salt);
        } else {
            $validate[$key] = $value;
        }

    }
    $validate['salt'] = $salt;
    sort($validate, SORT_STRING);

    return sha1(implode($validate));
}

function pay_notice_call($payInfo, $ok_msg = 'success', $err_msg = 'fail')
{
    if ($payInfo['err_code'] === 0) {
        $database_order = D('App_z_order');

        $condition_order['trade_no'] = $payInfo['order_param']['trade_no'];
        $nowOrder = $database_order->where($condition_order)->find();

        if ($nowOrder && $nowOrder['status'] == 1) {
            $data_order['third_id'] = $payInfo['order_param']['third_id'];
            $data_order['pay_money'] = $payInfo['order_param']['pay_money'];
            $data_order['paid_time'] = $_SERVER['REQUEST_TIME'];
            $data_order['status'] = 2;

            if ($database_order->where($condition_order)->data($data_order)->save()) {
                // 充值+充值记录
                //M('User')->payfor($nowOrder['uid'], $nowOrder['total'], $nowOrder['point']);


                $db_user = D('User');
                $user = $db_user->where(array('uid' => $nowOrder['uid'], 'status' => 1))->find();
                if ($user) {
                    $model_user = M('User');

                    // 发放收益
                    $db_user->where(array('uid' => $nowOrder['uid']))->setInc('balance', $nowOrder['profit']);
                    // 赠送消费金额
                    $db_user->where(array('uid' => $nowOrder['uid']))->setInc('balance', $nowOrder['gift']);
                    $db_user->where(array('uid' => $nowOrder['uid']))->setInc('consumer', $nowOrder['gift']);

                    // 更改订单状态
                    $database_order->where(array('order_id' => $nowOrder['order_id']))->data(array('pay_type' => 'account', 'paid_time' => time(), 'status' => 2))->save();

                    // 分佣
                    $model_user->investCommission($nowOrder['uid'], $nowOrder['order_no'], $nowOrder['commission']);
                    // 代理
                    $model_user->investAgent($nowOrder['uid'], $nowOrder['order_no'], $nowOrder['commission']);
                }

                exit($ok_msg);
            } else {
                exit($err_msg);
            }
        } else {
            exit($err_msg);
        }
    } else {
        exit($ok_msg);
    }
}

