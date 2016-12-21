<?php
/**
 * Created by PhpStorm.
 * User: Ray
 * Date: 2016/11/19
 * Time: 10:10
 */
require_once dirname(__FILE__) . '/global.php';

function payfor($payInfo, $ok_msg = 'success', $err_msg = 'fail')
{
    $database_order = D('Payfor_order');

    $condition_order['trade_no'] = $payInfo['order_param']['trade_no'];
    $nowOrder = $database_order->where($condition_order)->find();

    if ($nowOrder && $nowOrder['status'] == 1) {
        $data_order['third_id'] = $payInfo['order_param']['third_id'];
        $data_order['pay_money'] = $payInfo['order_param']['pay_money'];
        $data_order['paid_time'] = $payInfo['order_param']['paid_time'];
        $data_order['status'] = 2;

        if ($database_order->where($condition_order)->data($data_order)->save()) {
            // 充值+充值记录
            // M('User')->payfor($nowOrder['uid'], $nowOrder['total'], $nowOrder['point']);
            $db_user = D('User');
            $user = $db_user->where(array('uid' => $nowOrder['uid'], 'status' => 1))->find();
            if ($user) {
                //$agent_id = D('Agent')->where(array('open_self' => 0, 'is_agent' => 0, 'is_editor' => 0))->getField('agent_id');
                $agent_id = $nowOrder['agent_id'];
                $agent = D('Agent')->where(array('agent_id' => $agent_id))->find();
                //if(empty($agent)){
                //}
                logs($agent_id, 'VipId');

                $db_store = D('Store');
                $store = $db_store->where(array('uid' => $nowOrder['uid'], 'status' => 1))->find();
                // 添加或更新店铺级别
                if (empty($store)) {
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
                    $store_id = $db_store->data($store_data)->add();
                    logs($store_id, 'CreateStore');
                    if ($store_id) {
                        $db_user->where(array('uid' => $nowOrder['uid']))
                            ->data(array('stores' => 1))
                            ->save();
                    }
                } else {
                    $db_store->where(array('uid' => $nowOrder['uid']))->data(array('agent_id' => $agent_id))->save();
                }

                /**
                 * @var $model_user user_model
                 */
                $model_user = M('User');
                if ($agent['consumer']) {
                    $db_user->where(array('uid' => $nowOrder['uid'], 'status' => 1))->setInc('balance', $agent['consumer']);
                    $db_user->where(array('uid' => $nowOrder['uid'], 'status' => 1))->setInc('consumer', $agent['consumer']);

                    //$model_user->income($nowOrder['uid'], $agent['consumer'], 0);
                    D('User_income')->data(array('uid' => $nowOrder['uid'], 'order_no' => $nowOrder['order_no'], 'income' => $agent['consumer'], 'point' => 0, 'type' => 8, 'add_time' => time(), 'status' => 1, 'remarks' => '实体店加盟赠送消费金额！'))->add();

                    $model_user->payforCommission($nowOrder['uid'], $nowOrder['order_no'], $agent['commission'], 0);
                } else {
                    // consumer 为 0 时
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
                }
                // 代理分佣
                $model_user->payforAgent($nowOrder['uid'], $nowOrder['order_no'], $agent['commission'], 1);
            }

            exit($ok_msg);
        } else {
            exit($err_msg);
        }
    } else {
        exit($err_msg);
    }
}

$pay1 = array('order_param' => array('trade_no' => '201611181902428072', 'third_id' => '4009762001201611180119168745', 'pay_money' => 99.00, 'paid_time' => strtotime('2016-11-18 19:02:49')));
$pay2 = array('order_param' => array('trade_no' => '201611181900511576', 'third_id' => '4002132001201611180119113898', 'pay_money' => 99.00, 'paid_time' => strtotime('2016-11-18 19:01:04')));
$pay3 = array('order_param' => array('trade_no' => '201611180946362184', 'third_id' => '4007882001201611180066448167', 'pay_money' => 99.00, 'paid_time' => strtotime('2016-11-18 09:46:44')));

//var_dump($pay1);

$pay = $_GET['a'];
if ($pay) {
	//var_dump($$pay);
    payfor($$pay);
}
