<?php
/**
 *  微网站首页
 */
require_once dirname(__FILE__) . '/global.php';
define('POINT', 1000);

if ($_GET['a'] == 'join') { // IS_GET
//    if (IS_POST) {
//        $point = intval($_POST['point']);
//        if ($point < POINT) {
//            exit(json_encode(array('status' => 'fail', 'msg' => '投资积分数量不能少于 1000！')));
//        }
//        if ($point % POINT > 0) {
//            exit(json_encode(array('status' => 'fail', 'msg' => '积分数量只能是100的倍数！')));
//        }
//        // 实时的帐户信息
//        $wap_user = D('User')->where(array('uid' => $wap_user['uid'], 'status' => 1))->find();
//        if (empty($wap_user)) {
//            exit(json_encode(array('status' => 'fail', 'msg' => '获取账户积分查询失败！')));
//        }
//        if ($wap_user['point'] < $point) {
//            exit(json_encode(array('status' => 'fail', 'msg' => '账户积分余额不足！')));
//        }
//        // 按10000等分进行投资
//        $app_db = M('App_million');
//        for ($i = 0; $i < round($point / POINT); $i++) {
//            $issue = $app_db->getIssue($wap_user['uid']);
//
//            // SELECT `id`, `uid`, `issue`, `point`, `time` FROM `tp_app_million` WHERE 1
//            if (D('App_million')->data(array('uid' => $wap_user['uid'], 'issue' => $issue, 'point' => POINT, 'income' => 0, 'time' => time()))->add()) {
//                // 增加积分消费记录 && 用户积分数量减少
//                $app_db->investOff($wap_user['uid'], POINT);
//
//                // 递归计算上级用户投资收益
//                $app_db->investReturn($wap_user['uid'], $issue, POINT, 0);
//            }
//        }
//        exit(json_encode(array('status' => 'success', 'msg' => '')));
//
//        //exit(json_encode(array('status' => 'fail', 'msg' => '投资失败，请重试！')));
//    }
//    include display('app_million_join');
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
    $db_z_order = D('App_z_order');
    $list = $db_z_order->where(array('uid' => $wap_user['uid'], 'status' => 2))->select();
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
