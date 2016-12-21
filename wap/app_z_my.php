<?php
/**
 *  微网站首页
 */
require_once dirname(__FILE__) . '/global.php';
define('POINT', 1000);

if ($_GET['a'] == 'tx') { // IS_GET
    $id = intval($_GET['id']);
    if (!$id) redirect('./app_z_my.php');

    $db_app_z_order = D('App_z_order');
    $order = $db_app_z_order->where(array('status' => 2, 'expire_time' => array('<', time())))->find();
    if (empty($order)) redirect('./app_z_my.php');

    $db_user_income = D('User_income');
    // 发放收益
    $db_user->where(array('uid' => $wap_user['uid']))->setInc('balance', $order['profit']);
    // 增加收益记录
    $db_user_income->data(array('uid' => $wap_user['uid'], 'order_no' => $order['order_no'], 'income' => $order['profit'], 'point' => 0, 'type' => 8, 'add_time' => time(), 'status' => 1, 'remarks' => '理财收益结算'))->add();

    // 提现到余额
    $db_user->where(array('uid' => $wap_user['uid']))->setInc('balance', $order['total']);
    // 增加记录
    $db_user_income->data(array('uid' => $wap_user['uid'], 'order_no' => $order['order_no'], 'income' => $order['total'], 'point' => 0, 'type' => 11, 'add_time' => time(), 'status' => 1, 'remarks' => '理财本金结算'))->add();

    // 更改订单状态
    $db_app_z_order->where(array('order_id' => $id))->data(array('status' => 3))->save();

    pigcms_tips('提现成功！', './app_z_order.php');
} else if ($_GET['a' == 'xt']) {
    $id = intval($_GET['id']);
    if (!$id) redirect('./app_z_my.php');

    $db_app_z_order = D('App_z_order');
    $order = $db_app_z_order->where(array('status' => 2, 'expire_time' => array('<', time())))->find();
    if (empty($order)) redirect('./app_z_my.php');

    // 结算上期
    $db_user_income = D('User_income');
    // 发放收益
    $db_user->where(array('uid' => $wap_user['uid']))->setInc('balance', $order['profit']);
    // 增加收益记录
    $db_user_income->data(array('uid' => $wap_user['uid'], 'order_no' => $order['order_no'], 'income' => $order['profit'], 'point' => 0, 'type' => 8, 'add_time' => time(), 'status' => 1, 'remarks' => '理财收益结算'))->add();

    // 提现到余额
    $db_user->where(array('uid' => $wap_user['uid']))->setInc('balance', $order['total']);
    // 增加记录
    $db_user_income->data(array('uid' => $wap_user['uid'], 'order_no' => $order['order_no'], 'income' => $order['total'], 'point' => 0, 'type' => 11, 'add_time' => time(), 'status' => 1, 'remarks' => '理财本金结算'))->add();

    // 更改订单状态
    $db_app_z_order->where(array('order_id' => $id))->data(array('status' => 3))->save();

    // 开始续投下期
    $nowOrder = array(
        // `uid`, `zid`, `agent_id`, `total`, `profit_status`, `agent_status`, `pay_type`, `status`, `add_time`, `paid_time`, `complate_time`,
        'order_no' => date('YmdHis', $_SERVER['REQUEST_TIME']) . mt_rand(100000, 999999),
        'trade_no' => date('YmdHis', $_SERVER['REQUEST_TIME']) . mt_rand(100000, 999999),
        'uid' => $wap_user['uid'],
        'zid' => $order['zid'],
        'item_id' => $order['item_id'],
        'agent_id' => $order['agent_id'],

        'total' => $order['total'], // 投资金额
        'expire_time' => strtotime("tomorrow +" . $z['days'] . " days"),
        'point' => 0,
        'pay_money' => 0, // 需支付金额
        'balance' => $order['total'], // 使用余额金额

        'profit' => $order['total'] * intval($z['profit_rate']) / 10000, // 投资收益
        'gift' => $order['total'] * intval($z['gift_rate']) / 10000,    // 赠送消费金额
        'commission' => $order['total'] * intval($z['commission_rate']) / 10000,    // 分佣金额

        'pay_type' => '', // 支付方式
        'status' => 1, // 订单状态
        'add_time' => time(), // 下单时间
        'remarks' => $order['remarks'],
    );

    if (!$nowOrder['order_id'] = $db_app_z_order->data($nowOrder)->add()) {
        json_return(1, '生成理财订单失败，请重试！');
    }

    // 开始支付
    $db_user->where(array('uid' => $wap_user['uid']))->setDec('balance', $nowOrder['balance']);
    // 增加消费记录
    $db_user_income->data(array('uid' => $wap_user['uid'], 'order_no' => $nowOrder['order_no'], 'income' => $nowOrder['balance'] * -1, 'point' => 0, 'type' => -1, 'add_time' => time(), 'status' => 1, 'remarks' => '购买理财'))->add();

    // 发放收益 ************* 修改为结束时发放收益
    //$db_user->where(array('uid' => $wap_user['uid']))->setInc('balance', $nowOrder['profit']);

    // 赠送消费金额
    $db_user->where(array('uid' => $wap_user['uid']))->setInc('balance', $nowOrder['gift']);
    $db_user->where(array('uid' => $wap_user['uid']))->setInc('consumer', $nowOrder['gift']);
    $db_user_income->data(array('uid' => $wap_user['uid'], 'order_no' => $nowOrder['order_no'], 'income' => $nowOrder['gift'], 'point' => 0, 'type' => 1, 'add_time' => time(), 'status' => 1, 'remarks' => '购买理财赠送消费金额'))->add();

    // 更改订单状态
    $db_app_z_order->where(array('order_id' => $nowOrder['order_id']))->data(array('pay_type' => 'account', 'paid_time' => $time, 'status' => 2))->save();

    $model_user = M('User');
    $model_user->investCommission($wap_user['uid'], $nowOrder['order_no'], $nowOrder['commission']);
    $model_user->investAgent($wap_user['uid'], $nowOrder['order_no'], $nowOrder['commission']);

    pigcms_tips('续投成功！', './app_z_order.php');
} else {
    $db_z_order = D('App_z_order');
    $list = $db_z_order->where(array('uid' => $wap_user['uid'], 'status' => array('>=', 2)))->order('`order_id` DESC')->select();
    //$point = M('App_million')->getPoint($wap_user['uid']);
    //if(!$point) redirect('./app_million.php?a=join');
    //
    //$income = M('App_million')->getIncome($wap_user['uid']);
    include display('app_z_my');
}
////分享配置 start  
//$share_conf = array(
//	'title'   => '用户中心 - '.empty($now_store)?$config['site_name']:$now_store['name'], // 分享标题
//	'desc'    => str_replace(array("\r", "\n"), array('', ''), option('config.seo_description')), // 分享描述
//	'link'    => getTwikerUrl($now_store['uid']), // 分享链接
//	'imgUrl'  => option('config.site_logo'), // 分享图片链接
//	'type'    => '', // 分享类型,music、video或link，不填默认为link
//	'dataUrl' => '', // 如果type是music或video，则要提供数据链接，默认为空
//);
//import('WechatShare');
//$share = new WechatShare();
//$shareData = $share->getSgin($share_conf);
////分享配置 end


echo ob_get_clean();
