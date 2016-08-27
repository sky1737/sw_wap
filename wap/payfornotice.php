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
$payClass = new Weixin(array(), $payMethodList[$payType]['config'], '', 'payfor');
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
        $database_order = D('Payfor_order');

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
                $user = $db_user->where(array('uid' => $nowOrder['uid'], 'status' => 1))
                    ->find();
                if ($user) {
                    $agent_id = D('Agent')->where(array('open_self' => 0, 'is_agent' => 0, 'is_editor' => 0))->getField('agent_id');
                    logs($agent_id, 'VipId');
                    $store_data = array('uid' => $nowOrder['uid'],
                        'agent_id' => $agent_id,
                        'name' => $user['nickname'] . '的商城',
                        'logo' => $user['avatar'],
                        'date_added' => time(),
                        'drp_supplier_id' => 0,
                        'open_logistics' => 1,
                        'offline_payment' => 0,
                        'open_friend' => 0,
                        'status' => 1);
                    $store_id = D('Store')->data($store_data)->add();
                    logs($store_id, 'CreateStore');
                    if ($store_id) {
                        $db_user->where(array('uid' => $nowOrder['uid']))
                            ->data(array('stores' => 1))
                            ->save();
                    }
                }

                // 充值立返，往上三级
                /**
                 * @var $model_user user_model
                 */
                $model_user = M('User');
                // 添加红包记录
                $model_user->payforRedpack($nowOrder['uid'], $nowOrder['order_no'], $nowOrder['pay_money'], 0);

                // 发红包
                $db_payfor_redpack = D('Payfor_redpack');
                $redpacks = $db_payfor_redpack->where(array('order_no' => $nowOrder['order_no'], 'status' => 0))
                    ->select();
                if (!empty($redpacks)) {
                    // 微信提现
                    $payType = 'weixin';
                    $payMethodList = M('Config')->get_pay_method();
                    if (!empty($payMethodList[$payType])) {
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
                                    ))->save();
                            }
                        }
                    }
                }

                // 代理分佣
                $model_user->payforAgent($nowOrder['uid'], $nowOrder['order_no'], $nowOrder['pay_money'], 1);

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

