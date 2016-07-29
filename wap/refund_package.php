<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/15
 * Time: 15:11
 */
require_once dirname(__FILE__) . '/global.php';

if(IS_POST)
{

    $order_info = D('Order')->where(array('order_id' => $_POST['order_id']))->find();
    $data = array(
        'express_code'=> $_POST['express_code'],
        'express_no'=> $_POST['express_no'],
        'express_company'=> $_POST['express_company'],
        'store_id'=> $order_info['agent_id'],
        'order_id'=> $order_info['order_id'],
        'products'=> $_POST['products'],
        'refund_reason'=> $_POST['refund_reason'],
        'is_take'=> $_POST['is_take'],
        'status'=> 0,
        'add_time'=> time()
    );

    $result  = M('Refund_package')->add($data);

    if($result['err_code'] == 0 )
    {
        //改变订单状态 退款中
        M('Order')->setOrderStatus($order_info['store_id'],$order_info['order_id'],array('status' => 6));
        Notify::getInstance()->orderUpdate($_SESSION['user']['openid'],
            option('config.wap_site_url') . '/order.php?orderid=' . $order_info['order_id'],
            '你好，退款申请已发送成功！',
            $order_info['order_no'],
            '退款中，等待商家确认',
            '您的订单退款申请已发送成功，等待供货商确认！');
        $store_info   = D('Store')->where(array('store_id' => $order_info['agent_id']))->find();
        $user_info = D('User')->where(array('uid' => $store_info['uid']))->find();
        Notify::getInstance()->notifyAgentRefund($user_info['openid'], option('config.site_url'),
            '有退货订单，请尽快登录代理平台处理',
            $order_info['order_no'],
            '登录代理平台查看',
            $order_info['total'],
            '平台地址：'.option('config.site_url').'，扫码登录即可。');

        json_return(0,'退款申请成功，待管理员确认后即可退款，可在退款订单中查看！');

    } else
    {
        json_return($result['err_code'],$result['err_msg']);
    }
    exit;

}