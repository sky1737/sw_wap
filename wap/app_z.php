<?php
/**
 *  微网站首页
 */
require_once dirname(__FILE__) . '/global.php';
define('POINT', 1000);

if ($_GET['a'] == 'join') { // IS_GET
    $zid = intval($_GET['zid']);
    if (!$zid) redirect("app_z.php");

    $db_z = D('App_z');
    $z = $db_z->where(array('status' => 1, 'zid' => $zid))->find();
    if (empty($z)) redirect("app_z.php");

    $item_id = $_GET['itemid'];
    $db_z_item = D('App_z_item');
    $z_item = $db_z_item->where(array('zid' => $zid, 'item_id' => $item_id, 'status' => 1))->find();
    if (empty($z_item)) redirect('app_z.php');

    $wap_user = D('User')->where(array('uid' => $wap_user['uid'], 'status' => 1))->find();
    if ($_SESSION['user'] != $wap_user)
        $_SESSION['user'] = $wap_user;

    $balance = !empty($wap_user['balance']) ? $wap_user['balance'] : 0;

    if (IS_POST) {
        $min = floatval($z_item['minimum']);
        $invest = floatval($_POST['invest']);
        if ($invest < $min || $invest > floatval($z_item['maximum']) || ($invest - $min) % floatval($z_item['amount']) > 0)
            json_return(1, "错误的理财金额！");

        $amount = floatval($_POST['amount']);
        if ($amount < 0 || $amount > $balance)
            json_return(1, "使用余额数量错误！");

        $pay_money = $invest - $amount;
        $time = time();

        //$payType = 'weixin';
        $db_app_z_order = D('App_z_order');
        $nowOrder = array(
            // `uid`, `zid`, `agent_id`, `total`, `profit_status`, `agent_status`, `pay_type`, `status`, `add_time`, `paid_time`, `complate_time`,
            'order_no' => date('YmdHis', $_SERVER['REQUEST_TIME']) . mt_rand(100000, 999999),
            'trade_no' => date('YmdHis', $_SERVER['REQUEST_TIME']) . mt_rand(100000, 999999),
            'uid' => $wap_user['uid'],
            'zid' => $zid,
            'item_id' => $item_id,
            'agent_id' => $z['agent_id'],

            'total' => $invest,
            'point' => 0,
            'balance' => $balance,
            'pay_money' => $pay_money,

            'profit' => $invest * intval($z['profit_rate']) / 10000,
            'gift' => $invest * intval($z['gift_rate']) / 10000,
            'commission' => $invest * intval($z['commission_rate']) / 10000,

            'pay_type' => '',
            'status' => 1,
            'add_time' => time(),
            'remarks' => $z['title'] . ' 投资 ￥' . $invest . '。',
        );
        if (!$nowOrder['order_id'] = $db_app_z_order->data($nowOrder)->add()) {
            json_return(1, '生成理财订单失败，请重试！');
        }

        if ($pay_money > 0) {
            $payType = 'weixin';

            $payMethodList = M('Config')->get_pay_method();
            if (empty($payMethodList[$payType])) {
                json_return(1012, '您选择的支付方式不存在，请更新支付方式！');
            }

            $nowOrder['order_no_txt'] = option('config.orderid_prefix') . $nowOrder['order_no'];

            import('source.class.pay.Weixin');
            $payClass = new Weixin($nowOrder, $payMethodList[$payType]['config'], $wap_user['openid'], 'recharge');
            $result = $payClass->pay();
            logs('payInfo:' . json_encode($result), 'INFO');
            if ($result['err_code']) {
                json_return(1013, $result['err_msg']);
            } else {
                json_return(0, json_decode($result['pay_data']));
            }
        } else {
            $model_user = M('User');

            // 发放收益
            $db_user->where(array('uid' => $wap_user['uid']))->setInc('balance', $nowOrder['profit']);
            // 赠送消费金额
            $db_user->where(array('uid' => $wap_user['uid']))->setInc('balance', $nowOrder['gift']);
            $db_user->where(array('uid' => $wap_user['uid']))->setInc('consumer', $nowOrder['gift']);

            // 更改订单状态
            $db_app_z_order->where(array('order_id' => $nowOrder['order_id']))->data(array('pay_type' => 'account', 'paid_time' => $time, 'status' => 2))->save();
        }
    }
    //$point = !empty($wap_user['point']) ? $wap_user['point'] : 0;

    //$time = time();
    //$list = D('Recharge')->where(array(
    //    'start_time' => array('<', $time),
    //    'end_time' => array('>', $time),
    //    'status' => 1
    //))->select();

    include display('app_z_item_order');
    echo ob_get_clean();
//} else if ($_GET['a'] == 'issue') {
//    $issues = D('App_million')->where(array('uid' => $wap_user['uid']))->select();
//
//    include display('app_million_issue');
//} else if ($_GET['a'] == 'income') {
//    $issue = intval($_GET['issue']);
//    if (!$issue) redirect('./app_million.php?a=issue');
//
//    $uid = intval($_GET['uid']);
//    if (!$uid) $uid = $wap_user['uid'];
//
//    $incomes = D('App_million_income')->where(array('parent_uid' => $uid, 'issue' => $issue))->select();
//    $user_db = D('User');
//    foreach ($incomes as &$v) {
//        $v['nickname'] = $user_db->where(array('uid' => $v['uid']))->getField('nickname');
//    }
//    include display('app_million_income');
//} else if ($_GET['a'] == 'withdraw') {
//    include display('app_million_withdraw');
//} else if ($_GET['a'] == 'withdraw_detail') {
//    include display('app_million_withdraw_detail');
} else {
    $list = D('App_z')->where(array('status' => 1))->limit(0, 10)->select();
    $db_z_item = D('App_z_item');
    foreach ($list as &$z) {
        $z['items'] = $db_z_item->where(array('zid' => $z['zid']))->select();
    }

    //print_r($list);
    if (count($list) > 1) {
        include display('app_z');
    } else {
        $item = $list[0];
        include display('app_z_item');
    }
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
