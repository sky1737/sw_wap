<?php
/**
 * 分销用户中心
 * User: pigcms_21
 * Date: 2015/4/18
 * Time: 14:35
 */
require_once dirname(__FILE__) . '/drp_check.php';

//if ($now_store) {
//    $store = $now_store;
//} else {
//    redirect('./ucenter.php?id=' . intval(trim($_COOKIE['wap_store_id'])));
//}

//分享配置 start
$share_conf = array(
	'title'   => $now_store['name'] . '-分销管理', // 分享标题
	'desc'    => str_replace(array("\r", "\n"), array('', ''), $now_store['intro']), // 分享描述
	'link'    => getTwikerUrl($now_store['uid']), // 分享链接
	'imgUrl'  => $now_store['logo'], // 分享图片链接
	'type'    => '', // 分享类型,music、video或link，不填默认为link
	'dataUrl' => '', // 如果type是music或video，则要提供数据链接，默认为空
);
import('WechatShare');
$share = new WechatShare();
$shareData = $share->getSgin($share_conf);
// 分享配置 end

if(IS_GET && $_GET['a'] == 'personal') {
	$store_model = M('Store');

//    $drp_approve = true;
//    //供货商
//    if (!empty($store['drp_supplier_id'])) {
//        $supplier = $store_model->getStore($store['drp_supplier_id']);
//        $store = $store_model->getStore($store['store_id']);
//        $store['supplier'] = $supplier['name'];
//
//        if (!empty($supplier['open_drp_approve']) && empty($store['drp_approve'])) { //需要审核，但未审核
//            $drp_approve = false;
//        }
//    }

	include display('drp_ucenter_personal');
	echo ob_get_clean();
}
else if($_GET['a'] == 'profile') {
	$user_model = M('User');
	$user = $_SESSION['user'];
	if($_POST['type'] == 'nickname') {
		$nickname = trim($_POST['nickname']);
		if($user_model->setField(array('uid' => $user['uid']), array('nickname' => $nickname))) {
			$_SESSION['user']['nickname'] = $nickname;
			echo 0;
		}
		else {
			echo 1;
		}
		exit;
	}
	else if($_POST['type'] == 'password') {
		$password = md5(trim($_POST['newpassword']));
		if($user_model->setField(array('uid' => $user['uid']), array('password' => $password))) {
			echo 0;
		}
		else {
			echo 1;
			
		}
		exit;
	}
	else if($_POST['type'] == 'phone') {
		$phone = trim($_POST['phone']);
		if($user_model->setField(array('uid' => $user['uid']), array('phone' => $phone))) {
			$_SESSION['user']['phone'] = $phone;
			echo 0;
		}
		else {
			echo 1;
		}
		exit;
	}
	else if($_POST['type'] == 'intro') {
		$intro = trim($_POST['intro']);
		if($user_model->setField(array('uid' => $user['uid']), array('intro' => $intro))) {
			$_SESSION['user']['intro'] = $intro;
			echo 0;
		}
		else {
			echo 1;
		}
		exit;
	}

	include display('drp_ucenter_profile');
	echo ob_get_clean();

}
//else if (IS_POST && $_POST['type'] == 'check_pwd') {
//    $user_model = M('User');
//    $user = $_SESSION['user'];
//    $password = !empty($_POST['password']) ? md5(trim($_POST['password'])) : '';
//    $userinfo = $user_model->checkUser(array('uid' => $user['uid'], 'password' => $password));
//    if (!empty($userinfo)) {
//        echo 0;
//    } else {
//        echo 1;
//    }
//    exit;
//}
else {
	//$store_model = M('Store');
	$order = M('Order');
	//$store_supplier = M('Store_supplier');
//	$financial_record = M('Financial_record');

//    if (!empty($_GET['id'])) {
//        $store_info = $store_model->getUserDrpStore($_SESSION['user']['uid'], intval(trim($_GET['id']), 0));
//        if (!empty($store_info)) {
//            $store = $now_store = $store_info;
//        }
//    } else {
//        $store = $store_model->getStore($store['store_id']);
//    }
//    $store_id = $store['store_id'];

	//店铺销售额
	$sales = $order->getSales(array('store_id' => $store['store_id'], 'status' => array('in', array(2, 3, 4))));
	$now_store['sales'] = number_format($sales, 2, '.', '');
	// 佣金总额
	// $balance = $financial_record->drpProfit(array('store_id' => $store['store_id']));
	$balance = !empty($now_store['drp_profit']) ? $now_store['drp_profit'] : 0;
	$now_store['balance'] = number_format($balance, 2, '.', '');

	//$drp_approve = $store['drp_approve'];
	//供货商
//    if (!empty($store['drp_supplier_id'])) {
//        $supplier = $store_model->getStore($store['drp_supplier_id']);
//        $store['supplier'] = $supplier['name'];
//
//        if (!empty($supplier['open_drp_approve']) && empty($store['drp_approve'])) { //需要审核，但未审核
//            $drp_approve = false;
//        }
//    }

//    //最大分销级别
//    $max_store_drp_level = option('config.max_store_drp_level');
//    //当前分销商级别
//    $seller = $store_supplier->getSeller(array('seller_id' => $store_id));
//    $current_drp_level = $seller['level'];
//    $sub_drp_level = $max_store_drp_level - $current_drp_level;
//    $level_alias = array(
//        1 => '一',
//        2 => '二',
//        3 => '三',
//        4 => '四',
//        5 => '五',
//        6 => '六',
//        7 => '七',
//        8 => '八',
//        9 => '九',
//        10 => '十'
//    );
//    $sub_drp_levels = array();
//    if ($sub_drp_level > 0) {
//        for ($i=1; $i <= $sub_drp_level; $i++) {
//            $sub_drp_levels[$i] = $level_alias[$i];
//        }
//    }

	//店铺
	$uid = $store['uid'];
	//$stores = $store_model->getUserDrpStores($uid);

	include display('supplier_ucenter');
	echo ob_get_clean();
}