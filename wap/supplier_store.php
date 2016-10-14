<?php
/**
 * 分销店铺
 * User: pigcms_21
 * Date: 2015/4/18
 * Time: 9:01
 */

require_once dirname(__FILE__) . '/drp_check.php';

//分享配置 start
$share_conf = array(
	'title'   => $now_store['name'] . '-分销管理', // 分享标题
	'desc'    => str_replace(array("\r", "\n"), array('', ''),
		!empty($now_store['intro']) ? $now_store['intro'] : $now_store['name']), // 分享描述
	'link'    => getTwikerUrl($now_store['uid']), // 分享链接
	'imgUrl'  => $now_store['logo'], // 分享图片链接
	'type'    => '', // 分享类型,music、video或link，不填默认为link
	'dataUrl' => '', // 如果type是music或video，则要提供数据链接，默认为空
);
import('WechatShare');
$share = new WechatShare();
$shareData = $share->getSgin($share_conf);
//分享配置 end

if($_GET['a'] == 'edit') {
	if(IS_POST) {
		if(empty($now_store)) {
			json_return(1001, '店铺编辑失败');
		}

		$data = array();
		if(!$now_store['edit_name_count']) {
			$data['name'] = I('post.name');
			$data['edit_name_count'] = $now_store['edit_name_count'] + 1;
		}
		if(empty($now_store['linkman'])) {
			$name = I('post.linkman');
			if(empty($name)) {
				json_return(1, '请填写店主姓名！');
			}
			$data['linkman'] = I('post.linkman');
		}

		$tel = I('post.tel');
		if(empty($tel)) {
			json_return(1, '请填写手机号码！');
		}
		$data['tel'] = $tel;

		$intro = I('post.intro');
		if(empty($intro)) {
			json_return(1, '请填写店铺介绍！');
		}
		$data['intro'] = $intro;
		$data['last_edit_time'] = time();
		if(D('Store')->where(array('store_id' => $now_store['store_id']))->data($data)->save()) {
			// 更新SESSION
			unset($_SESSION['store']);
			json_return(0, './drp_ucenter.php');
		}
		else {
			json_return(1001, '店铺编辑失败');
		}
		exit;
	}

	include display('drp_store_edit');
	echo ob_get_clean();
}
else if(IS_POST && $_POST['type'] == 'add') { //添加分销店铺
	$store = M('Store');
	$store_count = $store->getStoreCountByUid($_SESSION['user']['uid'], 1);
	if(option('config.user_store_num_limit') > 0 && $store_count >= option('config.user_store_num_limit')) {
		$_SESSION['stores'] = $store_count;
		json_return(1001, '店铺创建失败，店铺数量超出系统限制！');
	}
//	$store_supplier = M('Store_supplier');
	$user = M('User');
//	$sale_category = M('Sale_category');
	$common_data = M('Common_data');

	//$supplier_id = isset($_POST['store_id']) ? intval(trim($_POST['store_id'])) : 0;
	$name = isset($_POST['name']) ? trim($_POST['name']) : '';
	//$pids = isset($_POST['pids']) ? explode(',', trim($_POST['pids'])) : array();
	$linkname = isset($_POST['truename']) ? trim($_POST['truename']) : '';
	$tel = isset($_POST['tel']) ? trim($_POST['tel']) : '';
	$qq = isset($_POST['qq']) ? trim($_POST['qq']) : '';
	$uid = $_SESSION['user']['uid'];
	//$haspassword = isset($_POST['haspassword']) ? trim($_POST['haspassword']) : '';
	$open_drp_approve = isset($_POST['open_drp_approve']) ? trim($_POST['open_drp_approve']) : false;
	// 供货商店铺
	//$supplier_store = $store->getStore($supplier_id);
	//获取供货商分销级别
	//$drp_level = !empty($supplier_store['drp_level']) ? $supplier_store['drp_level'] : 0;
	//创建店铺
	$avatar = $user->getAvatarById($_SESSION['user']['uid']);
	//$drp_level = ($drp_level + 1); // 分销级别
	$data = array();
	$data['uid'] = $uid;
	$data['name'] = $name;
	$data['linkman'] = $linkname;
	$data['tel'] = $tel;
	$data['status'] = 1;
	$data['qq'] = $qq;
	$data['drp_supplier_id'] = $supplier_id;
	$data['date_added'] = time();
	$data['drp_level'] = $drp_level;
	$data['logo'] = $avatar;

	$ischeck_store = option('config.ischeck_store');
	$data['status'] = 1;
	if($ischeck_store) {
		$data['status'] = 2; // 开店需要审批
	}

	$result = $store->create($data);
	if(!empty($result['err_code'])) { // 店铺添加成功
		// 店铺数量+1
		$common_data->setStoreQty();
		if($user->setField(array('uid' => $uid),
			array('phone' => $tel, 'password' => md5($tel)))
		) {
			$_SESSION['user']['phone'] = $tel;
			$_SESSION['user']['stores'] = 1;
		}
		$store_id = $result['err_msg']['store_id']; //分销商id

		// 用户店铺数加1
		$user->setStoreInc($_SESSION['user']['uid']);
		// 设置为卖家
		$user->setSeller($_SESSION['user']['uid'], 1);

		json_return(0, 'http://' . $store_id . '.' . option('config.site_domain') . '/wap/drp_ucenter.php');
	}
	else {
		json_return(1001, '店铺创建失败');
	}
}
else if($_GET['a'] == 'sales') {
//	if(empty($now_store)) {
//		redirect('./ucenter.php?id=' . intval(trim($_COOKIE['wap_store_id'])));
//	}
	$order = M('Order');
	$type = !empty($_GET['type']) ? trim($_GET['type']) : 'today';
	if(strtolower($type) == 'today') { //今日销售额
		//今日销售额 00:00-6:00 6:00-12:00 12:00-18:00 18:00-24:00
		//00:00-6:00
		$starttime = strtotime(date('Y-m-d') . ' 00:00:00');
		$stoptime = strtotime(date('Y-m-d') . ' 06:00:00');
		$where = array();
		$where['store_id'] = $now_store['store_id'];
		$where['status'] = array('in', array(2, 3, 4));
		$where['_string'] = "paid_time >= " . $starttime . " AND paid_time < " . $stoptime;
		$todaysaletotal_0_6 = $order->getSales($where);
		if(!$todaysaletotal_0_6) {
			$todaysaletotal_0_6 = 0;
		}
		//6:00-12:00
		$starttime = strtotime(date('Y-m-d') . ' 06:00:00');
		$stoptime = strtotime(date('Y-m-d') . ' 12:00:00');
		$where = array();
		$where['store_id'] = $now_store['store_id'];
		$where['status'] = array('in', array(2, 3, 4));
		$where['_string'] = "paid_time >= " . $starttime . " AND paid_time < " . $stoptime;
		$todaysaletotal_6_12 = $order->getSales($where);
		if(!$todaysaletotal_6_12) {
			$todaysaletotal_6_12 = 0;
		}
		//12:00-18:00
		$starttime = strtotime(date('Y-m-d') . ' 12:00:00');
		$stoptime = strtotime(date('Y-m-d') . ' 18:00:00');
		$where = array();
		$where['store_id'] = $now_store['store_id'];
		$where['status'] = array('in', array(2, 3, 4));
		$where['_string'] = "paid_time >= " . $starttime . " AND paid_time < " . $stoptime;
		$todaysaletotal_12_18 = $order->getSales($where);
		if(!$todaysaletotal_12_18) {
			$todaysaletotal_12_18 = 0;
		}
		//18:00-24:00
		$starttime = strtotime(date('Y-m-d') . ' 18:00:00');
		$stoptime = strtotime(date('Y-m-d') . ' 23:59:59');
		$where = array();
		$where['store_id'] = $now_store['store_id'];
		$where['status'] = array('in', array(2, 3, 4));
		$where['_string'] = "paid_time >= " . $starttime . " AND paid_time < " . $stoptime;
		$todaysaletotal_18_24 = $order->getSales($where);
		if(!$todaysaletotal_18_24) {
			$todaysaletotal_18_24 = 0;
		}

		$todaysaletotal = "[" . number_format($todaysaletotal_0_6, 2, '.', '') . ',' .
			number_format($todaysaletotal_6_12, 2, '.', '') . ',' . number_format($todaysaletotal_12_18, 2, '.', '') .
			',' . number_format($todaysaletotal_18_24, 2, '.', '') . "]";
		echo $todaysaletotal;
		exit;
	}
	else if(strtolower($type) == 'yesterday') { //昨日销售额
		//昨日销售额 00:00-6:00 6:00-12:00 12:00-18:00 18:00-24:00
		$date = date('Y-m-d', strtotime('-1 day'));
		//00:00-6:00
		$starttime = strtotime($date . ' 00:00:00');
		$stoptime = strtotime($date . ' 06:00:00');
		$where = array();
		$where['store_id'] = $now_store['store_id'];
		$where['status'] = array('in', array(2, 3, 4));
		$where['_string'] = "paid_time >= " . $starttime . " AND paid_time < " . $stoptime;
		$yesterdaysaletotal_0_6 = $order->getSales($where);
		if(!$yesterdaysaletotal_0_6) {
			$yesterdaysaletotal_0_6 = 0;
		}
		//6:00-12:00
		$starttime = strtotime($date . ' 06:00:00');
		$stoptime = strtotime($date . ' 12:00:00');
		$where = array();
		$where['store_id'] = $now_store['store_id'];
		$where['status'] = array('in', array(2, 3, 4));
		$where['_string'] = "paid_time >= " . $starttime . " AND paid_time < " . $stoptime;
		$yesterdaysaletotal_6_12 = $order->getSales($where);
		if(!$yesterdaysaletotal_6_12) {
			$yesterdaysaletotal_6_12 = 0;
		}
		//12:00-18:00
		$starttime = strtotime($date . ' 12:00:00');
		$stoptime = strtotime($date . ' 18:00:00');
		$where = array();
		$where['store_id'] = $now_store['store_id'];
		$where['status'] = array('in', array(2, 3, 4));
		$where['_string'] = "paid_time >= " . $starttime . " AND paid_time < " . $stoptime;
		$yesterdaysaletotal_12_18 = $order->getSales($where);
		if(!$yesterdaysaletotal_12_18) {
			$yesterdaysaletotal_12_18 = 0;
		}
		//18:00-24:00
		$starttime = strtotime($date . ' 18:00:00');
		$stoptime = strtotime($date . ' 23:59:59');
		$where = array();
		$where['store_id'] = $now_store['store_id'];
		$where['status'] = array('in', array(2, 3, 4));
		$where['_string'] = "paid_time >= " . $starttime . " AND paid_time < " . $stoptime;
		$yesterdaysaletotal_18_24 = $order->getSales($where);
		if(!$yesterdaysaletotal_18_24) {
			$yesterdaysaletotal_18_24 = 0;
		}

		$yesterdaysaletotal = "[" . number_format($yesterdaysaletotal_0_6, 2, '.', '') . ',' .
			number_format($yesterdaysaletotal_6_12, 2, '.', '') . ',' .
			number_format($yesterdaysaletotal_12_18, 2, '.', '') . ',' .
			number_format($yesterdaysaletotal_18_24, 2, '.', '') . "]";
		echo $yesterdaysaletotal;
		exit;
	}
	else if(strtolower($type) == 'week') {
		$date = date('Y-m-d');  //当前日期
		$first = 1; //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
		$w = date('w', strtotime($date));  //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
		$now_start =
			date('Y-m-d', strtotime("$date -" . ($w ? $w - $first : 6) . ' days')); //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
		$now_end = date('Y-m-d', strtotime("$now_start +6 days"));  //本周结束日期

		//周一销售额
		$starttime = strtotime($now_start . ' 00:00:00');
		$stoptime = strtotime($now_start . ' 23:59:59');
		$where = array();
		$where['store_id'] = $now_store['store_id'];
		$where['status'] = array('in', array(2, 3, 4));
		$where['_string'] = "paid_time >= " . $starttime . " AND paid_time < " . $stoptime;
		$weeksaletotal_1 = $order->getSales($where);
		if(!$weeksaletotal_1) {
			$weeksaletotal_1 = 0;
		}
		//周二销售额
		$starttime = strtotime(date("Y-m-d", strtotime($now_start . "+1 day")) . ' 00:00:00');
		$stoptime = strtotime(date("Y-m-d", strtotime($now_start . "+1 day")) . ' 23:59:59');
		$where = array();
		$where['store_id'] = $now_store['store_id'];
		$where['status'] = array('in', array(2, 3, 4));
		$where['_string'] = "paid_time >= " . $starttime . " AND paid_time < " . $stoptime;
		$weeksaletotal_2 = $order->getSales($where);
		if(!$weeksaletotal_2) {
			$weeksaletotal_2 = 0;
		}
		//周三销售额
		$starttime = strtotime(date("Y-m-d", strtotime($now_start . "+2 day")) . ' 00:00:00');
		$stoptime = strtotime(date("Y-m-d", strtotime($now_start . "+2 day")) . ' 23:59:59');
		$where = array();
		$where['store_id'] = $now_store['store_id'];
		$where['status'] = array('in', array(2, 3, 4));
		$where['_string'] = "paid_time >= " . $starttime . " AND paid_time < " . $stoptime;
		$weeksaletotal_3 = $order->getSales($where);
		if(!$weeksaletotal_3) {
			$weeksaletotal_3 = 0;
		}
		//周四销售额
		$starttime = strtotime(date("Y-m-d", strtotime($now_start . "+3 day")) . ' 00:00:00');
		$stoptime = strtotime(date("Y-m-d", strtotime($now_start . "+3 day")) . ' 23:59:59');
		$where = array();
		$where['store_id'] = $now_store['store_id'];
		$where['status'] = array('in', array(2, 3, 4));
		$where['_string'] = "paid_time >= " . $starttime . " AND paid_time < " . $stoptime;
		$weeksaletotal_4 = $order->getSales($where);
		if(!$weeksaletotal_4) {
			$weeksaletotal_4 = 0;
		}
		//周五销售额
		$starttime = strtotime(date("Y-m-d", strtotime($now_start . "+4 day")) . ' 00:00:00');
		$stoptime = strtotime(date("Y-m-d", strtotime($now_start . "+4 day")) . ' 23:59:59');
		$where = array();
		$where['store_id'] = $now_store['store_id'];
		$where['status'] = array('in', array(2, 3, 4));
		$where['_string'] = "paid_time >= " . $starttime . " AND paid_time < " . $stoptime;
		$weeksaletotal_5 = $order->getSales($where);
		if(!$weeksaletotal_5) {
			$weeksaletotal_5 = 0;
		}
		//周六销售额
		$starttime = strtotime(date("Y-m-d", strtotime($now_start . "+5 day")) . ' 00:00:00');
		$stoptime = strtotime(date("Y-m-d", strtotime($now_start . "+5 day")) . ' 23:59:59');
		$where = array();
		$where['store_id'] = $now_store['store_id'];
		$where['status'] = array('in', array(2, 3, 4));
		$where['_string'] = "paid_time >= " . $starttime . " AND paid_time < " . $stoptime;
		$weeksaletotal_6 = $order->getSales($where);
		if(!$weeksaletotal_6) {
			$weeksaletotal_6 = 0;
		}
		//周日销售额
		$starttime = strtotime(date("Y-m-d", strtotime($now_start . "+6 day")) . ' 00:00:00');
		$stoptime = strtotime(date("Y-m-d", strtotime($now_start . "+6 day")) . ' 23:59:59');
		$where = array();
		$where['store_id'] = $now_store['store_id'];
		$where['status'] = array('in', array(2, 3, 4));
		$where['_string'] = "paid_time >= " . $starttime . " AND paid_time < " . $stoptime;
		$weeksaletotal_7 = $order->getSales($where);
		if(!$weeksaletotal_7) {
			$weeksaletotal_7 = 0;
		}

		$weeksaletotal =
			"[" . number_format($weeksaletotal_1, 2, '.', '') . ',' . number_format($weeksaletotal_2, 2, '.', '') .
			',' . number_format($weeksaletotal_3, 2, '.', '') . ',' . number_format($weeksaletotal_4, 2, '.', '') .
			',' . number_format($weeksaletotal_5, 2, '.', '') . ',' . number_format($weeksaletotal_6, 2, '.', '') .
			',' . number_format($weeksaletotal_7, 2, '.', '') . "]";
		echo $weeksaletotal;
		exit;
	}
	else if(strtolower($type) == 'month') { //当月销售额
		$month = date('m');
		$year = date('Y');
		//1-7日
		$starttime = strtotime($year . '-' . $month . '-01' . ' 00:00:00');
		$stoptime = strtotime($year . '-' . $month . '-07' . ' 00:00:00');
		$where = array();
		$where['store_id'] = $now_store['store_id'];
		$where['status'] = array('in', array(2, 3, 4));
		$where['_string'] = "paid_time >= " . $starttime . " AND paid_time < " . $stoptime;
		$monthsaletotal_1_7 = $order->getSales($where);
		if(!$monthsaletotal_1_7) {
			$monthsaletotal_1_7 = 0;
		}
		//7-14日
		$starttime = strtotime($year . '-' . $month . '-07' . ' 00:00:00');
		$stoptime = strtotime($year . '-' . $month . '-14' . ' 00:00:00');
		$where = array();
		$where['store_id'] = $now_store['store_id'];
		$where['status'] = array('in', array(2, 3, 4));
		$where['_string'] = "paid_time >= " . $starttime . " AND paid_time < " . $stoptime;
		$monthsaletotal_7_14 = $order->getSales($where);
		if(!$monthsaletotal_7_14) {
			$monthsaletotal_7_14 = 0;
		}
		//14-21日
		$starttime = strtotime($year . '-' . $month . '-14' . ' 00:00:00');
		$stoptime = strtotime($year . '-' . $month . '-21' . ' 00:00:00');
		$where = array();
		$where['store_id'] = $now_store['store_id'];
		$where['status'] = array('in', array(2, 3, 4));
		$where['_string'] = "paid_time >= " . $starttime . " AND paid_time < " . $stoptime;
		$monthsaletotal_14_21 = $order->getSales($where);
		if(!$monthsaletotal_14_21) {
			$monthsaletotal_14_21 = 0;
		}
		//21-本月结束
		//当月最后一天
		$lastday = date('t', time());
		$starttime = strtotime($year . '-' . $month . '-21' . ' 00:00:00');
		$stoptime = strtotime($year . '-' . $month . '-' . $lastday . ' 23:59:59');
		$where = array();
		$where['store_id'] = $now_store['store_id'];
		$where['status'] = array('in', array(2, 3, 4));
		$where['_string'] = "paid_time >= " . $starttime . " AND paid_time < " . $stoptime;
		$monthsaletotal_21_end = $order->getSales($where);
		if(!$monthsaletotal_21_end) {
			$monthsaletotal_21_end = 0;
		}
		$data = array();
		$monthsaletotal = "[" . number_format($monthsaletotal_1_7, 2, '.', '') . ',' .
			number_format($monthsaletotal_7_14, 2, '.', '') . ',' . number_format($monthsaletotal_14_21, 2, '.', '') .
			',' . number_format($monthsaletotal_21_end, 2, '.', '') . "]";
		$data['monthsaletotal'] = $monthsaletotal;
		$data['lastday'] = $lastday;
		echo json_encode(array('data' => $data));
		exit;
	}

	//店铺销售额
	$sales = $order->getSales(array('store_id' => $now_store['store_id'], 'status' => array('in', array(2, 3, 4))));
	$now_store['sales'] = number_format($sales, 2, '.', '');

	include display('supplier_store_sales');
	echo ob_get_clean();
}
else if(strtolower($_GET['a']) == 'select') {
//	if(empty($now_store)) {
//		redirect('./ucenter.php?id=' . intval(trim($_COOKIE['wap_store_id'])));
//	}

	//$store = $now_store;
	$store_id = $now_store['store_id'];
	$store_model = M('Store');
	$order = M('Fx_order');
	$store_supplier = M('Store_supplier');
	$financial_record = M('Financial_record');
	if(!empty($_GET['id'])) {
		$store_info = $store_model->getUserDrpStore($now_store['uid'], intval(trim($_GET['id'])), 0);
		if($store_info = $store_model->getUserDrpStore($now_store['uid'], intval(trim($_GET['id'])), 0)
		) { // 已有分销店铺，跳转到分销管理页面
			$now_store = $store_info;
			redirect('./drp_ucenter.php');
		}
		else {
			redirect('./drp_ucenter.php');
		}
	}

	//店铺销售额
	$sales = $order->getSales(array('store_id' => $now_store['store_id'], 'status' => array('in', array(2, 3, 4))));
	$store['sales'] = number_format($sales, 2, '.', '');
	//店铺余额
	$balance = $financial_record->drpProfit(array('store_id' => $now_store['store_id']));
	$store['balance'] = number_format($balance, 2, '.', '');

	$drp_approve = true;
	//供货商
	if(!empty($store['drp_supplier_id'])) {
		$supplier = $store_model->getStore($store['drp_supplier_id']);
		$store['supplier'] = $supplier['name'];

		if(!empty($supplier['open_drp_approve']) && empty($store['drp_approve'])) { //需要审核，但未审核
			$drp_approve = false;
		}
	}
	$uid = $now_store['uid'];
	$stores = $store_model->getUserDrpStores($uid, 0, 0);

	include display('drp_store_select');
	echo ob_get_clean();

}
else if($_GET['a'] == 'account') {
//	if (empty($now_store)) {
//		redirect('./ucenter.php?id=' . intval(trim($_COOKIE['wap_store_id'])));
//	}
//	$store = $now_store;
//	$store = $now_store;
//	$store_id = $now_store['store_id'];
//	$store_model = M('Store');
	$order = M('Fx_order');
//	$store_supplier = M('Store_supplier');
//	$financial_record = M('Financial_record');
	$user = M('User');

	//$store_info = $store_model->getStore($store_id);

	//店铺销售额
	$sales =
		$order->getSales(array('store_id' => $now_store['store_id'], 'status' => array('in', array(2, 3, 4))));
	$now_store['sales'] = number_format($sales, 2, '.', '');
	//店铺余额
	//$balance = $financial_record->drpProfit(array('store_id' => $now_store['store_id']));
	//$store['balance'] = number_format($balance, 2, '.', '');
	$balance = !empty($now_store['drp_profit']) ? $now_store['drp_profit'] : 0;
	$now_store['balance'] = number_format($balance, 2, '.', '');

	$drp_approve = true;
//	//供货商
//	if (!empty($store['drp_supplier_id'])) {
//		$supplier = $store_model->getStore($store['drp_supplier_id']);
//		$store['supplier'] = $supplier['name'];
//
//		if (!empty($supplier['open_drp_approve']) && empty($store['drp_approve'])) { //需要审核，但未审核
//			$drp_approve = false;
//		}
//	}
//	$uid = $now_store['uid'];
//	$user_info = $user->checkUser(array('uid' => $uid));
//	$phone = $user_info['phone'];
//	$password = false;
//	if (md5($phone) != $user_info['password']) {
//		$password = true; //有新密码
//	}
//	$admin_url = '';
//	// 对接用户
//	if (!empty($now_store['source_site_url'])) {
//		$admin_url = $now_store['source_site_url'] . '/api/weidian.php';
//	}
//	else {
//		$admin_url = url('index:account:login');    // option('config.site_url') . '/account.php';
//	}

	include display('drp_store_account');
	echo ob_get_clean();
}
else if($_GET['a'] == 'check_name') { // 店铺名检测
	$store = M('Store');
	$where = array();
	if(!empty($now_store)) {
		$where['store_id'] = array('!=', $now_store['store_id']);
	}
	$where['name'] = isset($_POST['name']) ? trim($_POST['name']) : '';
	$where['status'] = 1;
	if($store->checkStoreExist($where)) {
		echo false;
	}
	else {
		echo true;
	}
	exit;
}
//else if(strtolower($_GET['a']) == 'reset_pwd') { //重置为初始密码
//	if(empty($now_store)) {
//		pigcms_tips('您没有权限访问，<a href="./home.php?id=' . $_COOKIE['wap_store_id'] . '">返回首页</a>', 'none');
//	}
//	$user = M('User');
//
//	$store = $now_store;
//	$user_info = $user->checkUser(array('uid' => $now_store['uid']));
//	if(D('User')->where(array('uid' => $user_info['uid']))->data(array('password' => md5($user_info['phone'])))
//		->save()
//	) {
//		redirect('./drp_ucenter.php');
//	}
//	else {
//		redirect('./drp_store.php?a=account');
//	}
//}
//else if(IS_GET && $_GET['a'] == 'logout') {
//	$store_id = $now_store['store_id'];
//	unset($now_store);
//	redirect('./ucenter.php?id=' . $store_id);
//}

function setHomePage($store_id, $page_id)
{
	$fx_products = $products =
		D('Product')->where(array('store_id' => $store_id, 'status' => 1, 'supplier_id' => array('>', 0)))
			->order('product_id DESC')->limit(15)->select();
	$home_products = array();
	if(!empty($fx_products)) {
		foreach ($fx_products as $fx_product) {
			$home_products[] = array(
				'id'    => $fx_product['product_id'],
				'title' => htmlspecialchars($fx_product['name'], ENT_QUOTES),
				'price' => $fx_product['price'],
				'url'   => option('config.wap_site_url') . '/good.php?id=' . $fx_product['product_id'],
				'image' => $fx_product['image']
			);
		}
	}
	$content = array();
	$content['size'] = 3;
	$content['buy_btn'] = 1;
	$content['buy_btn_type'] = 3;
	$content['price'] = 1;
	$content['goods'] = $home_products;
	$database_custom_field = D('Custom_field');
	$data_custom_field = array();
	$data_custom_field['store_id'] = $store_id;
	$data_custom_field['module_name'] = 'page';
	$data_custom_field['module_id'] = $page_id;
	$data_custom_field['field_type'] = 'goods';
	$data_custom_field['content'] = serialize($content);
	$search_module =
		$database_custom_field->where(array('module_id' => $page_id, 'store_id' => $store_id, 'field_type' => 'search'))
			->find();
	$home_module =
		$database_custom_field->where(array('module_id' => $page_id, 'store_id' => $store_id, 'field_type' => 'goods'))
			->find();
	if(empty($search_module) && empty($home_module)) {
		$database_custom_field->data(array('store_id'   => $store_id, 'module_name' => 'page', 'module_id' => $page_id,
		                                   'field_type' => 'search', 'content' => serialize(array())))->add();
	}
	if(empty($home_module)) {
		$database_custom_field->data($data_custom_field)->add();
	}
	else {
		$database_custom_field->where(array('field_id' => $home_module['field_id']))->data($data_custom_field)->save();
	}
}

/**
 * @param $store_id
 * @param $supplier
 * @param $page_id
 * @param $group_ids
 * @param $fx_products
 * @return bool|int|string
 */
function copyHomePage($store_id, $supplier, $page_id, $group_ids, $fx_products)
{
	//供货商微页面
	$homePage = D('Wei_page')->where(array('is_home' => 1, 'store_id' => $supplier['store_id']))->find();
	//微杂志的自定义字段
	if($homePage['has_custom']) {
		$field_list = M('Custom_field')->get_field($supplier['store_id'], 'page', $homePage['page_id']);
		if(!empty($field_list)) {
			$data_fields = array();
			foreach ($field_list as $key => $field) {
				switch ($field['field_type']) {
					case 'title': //标题
						$data_fields[$key]['store_id'] = $store_id;
						$data_fields[$key]['module_name'] = htmlspecialchars($field['module_name'], ENT_QUOTES);
						$data_fields[$key]['module_id'] = $page_id;
						$data_fields[$key]['field_type'] = $field['field_type'];
						$data_fields[$key]['content'] = serialize($field['content']);
						break;
					case 'rich_text': //富文本
						$data_fields[$key]['store_id'] = $store_id;
						$data_fields[$key]['module_name'] = htmlspecialchars($field['module_name'], ENT_QUOTES);
						$data_fields[$key]['module_id'] = $page_id;
						$data_fields[$key]['field_type'] = $field['field_type'];
						$data_fields[$key]['content'] = serialize($field['content']);
						break;
					case 'notice': //公告
						$data_fields[$key]['store_id'] = $store_id;
						$data_fields[$key]['module_name'] = htmlspecialchars($field['module_name'], ENT_QUOTES);
						$data_fields[$key]['module_id'] = $page_id;
						$data_fields[$key]['field_type'] = $field['field_type'];
						$data_fields[$key]['content'] = serialize($field['content']);
						break;
					case 'line': //辅助线
						$data_fields[$key]['store_id'] = $store_id;
						$data_fields[$key]['module_name'] = htmlspecialchars($field['module_name'], ENT_QUOTES);
						$data_fields[$key]['module_id'] = $page_id;
						$data_fields[$key]['field_type'] = $field['field_type'];
						$data_fields[$key]['content'] = serialize($field['content']);
						break;
					case 'white': //辅助空白
						$data_fields[$key]['store_id'] = $store_id;
						$data_fields[$key]['module_name'] = htmlspecialchars($field['module_name'], ENT_QUOTES);
						$data_fields[$key]['module_id'] = $page_id;
						$data_fields[$key]['field_type'] = $field['field_type'];
						$data_fields[$key]['content'] = serialize($field['content']);
						break;
					case 'search': //商品搜索
						$data_fields[$key]['store_id'] = $store_id;
						$data_fields[$key]['module_name'] = htmlspecialchars($field['module_name'], ENT_QUOTES);
						$data_fields[$key]['module_id'] = $page_id;
						$data_fields[$key]['field_type'] = $field['field_type'];
						$data_fields[$key]['content'] = serialize($field['content']);
						break;
					case 'store': //进入店铺
						$data_fields[$key]['store_id'] = $store_id;
						$data_fields[$key]['module_name'] = htmlspecialchars($field['module_name'], ENT_QUOTES);
						$data_fields[$key]['module_id'] = $page_id;
						$data_fields[$key]['field_type'] = $field['field_type'];
						$data_fields[$key]['content'] = serialize($field['content']);
						break;
					case 'text_nav': //文本导航
						$data_fields[$key]['store_id'] = $store_id;
						$data_fields[$key]['module_name'] = htmlspecialchars($field['module_name'], ENT_QUOTES);
						$data_fields[$key]['module_id'] = $page_id;
						$data_fields[$key]['field_type'] = $field['field_type'];
						$data_fields[$key]['content'] = array();

						$text_navs = array();
						foreach ($field['content'] as $nav) {
							switch ($nav['prefix']) {
								case '商品分组':
									$params = convertUrlQuery($nav['url']);
									$group_id = !empty($params['id']) ? $params['id'] : '';
									if(!empty($group_id) && !empty($group_ids[$group_id])) { //分组id
										$my_group_id = $group_ids[$group_id];
										$nav['url'] =
											preg_replace('/goodcat\.php\?id=(\d+)/is', 'goodcat.php?id=' . $my_group_id,
												$nav['url']);
									}
									else {
										continue 2;
									}
									break;
								case '商品':
									$params = convertUrlQuery($nav['url']);
									if(!empty($params['id'])) { //商品id
										$product_id = $params['id'];
										if(empty($fx_products[$product_id])) {
											$my_product_id = $fx_products[$product_id];
											$nav['url'] =
												preg_replace('/good\.php\?id=(\d+)/is', 'good.php?id=' . $my_product_id,
													$nav['url']);
										}
										else { //商品不存在或非分销商品
											//$nav['url'] = '#';
											continue 2;
										}
									}
									break;
								case '店铺主页':
									$nav['url'] = preg_replace('/home\.php\?id=(\d+)/is', 'home.php?id=' . $store_id,
										$nav['url']);
									break;
								case '会员主页':
									$nav['url'] =
										preg_replace('/ucenter\.php\?id=(\d+)/is', 'ucenter.php?id=' . $store_id,
											$nav['url']);
									break;
								case '外链': //过滤站内链接
									if(stripos($nav['url'], option('config.site_url')) !== false) {
										//$nav['url'] = '#';
										continue 2;
									}
									break;
								case '微页面':
									//$nav['url'] = '#';
									continue 2;
									break;
								case '微页面分类':
									//$nav['url'] = '#';
									continue 2;
									break;
							}
							$text_navs[] = array(
								'title'  => $nav['title'],
								'name'   => htmlspecialchars($nav['name'], ENT_QUOTES),
								'prefix' => $nav['prefix'],
								'url'    => $nav['url']
							);
						}
						$data_fields[$key]['content'] = serialize($text_navs);
						break;
					case 'image_nav': //图片导航
						$data_fields[$key]['store_id'] = $store_id;
						$data_fields[$key]['module_name'] = htmlspecialchars($field['module_name'], ENT_QUOTES);
						$data_fields[$key]['module_id'] = $page_id;
						$data_fields[$key]['field_type'] = $field['field_type'];
						$data_fields[$key]['content'] = array();

						$image_navs = array();
						foreach ($field['content'] as $nav) {
							switch ($nav['prefix']) {
								case '商品分组':
									$params = convertUrlQuery($nav['url']);
									$group_id = !empty($params['id']) ? $params['id'] : '';
									if(!empty($group_id) && !empty($group_ids[$group_id])) { //分组id
										$my_group_id = $group_ids[$group_id];
										$nav['url'] =
											preg_replace('/goodcat\.php\?id=(\d+)/is', 'goodcat.php?id=' . $my_group_id,
												$nav['url']);
									}
									else {
										$nav['url'] = '#';
									}
									break;
								case '商品':
									$params = convertUrlQuery($nav['url']);
									if(!empty($params['id'])) { //商品id
										$product_id = $params['id'];
										if(empty($fx_products[$product_id])) {
											$my_product_id = $fx_products[$product_id];
											$nav['url'] =
												preg_replace('/good\.php\?id=(\d+)/is', 'good.php?id=' . $my_product_id,
													$nav['url']);
										}
										else { //商品不存在或非分销商品
											$nav['url'] = '#';
										}
									}
									break;
								case '店铺主页':
									$nav['url'] = preg_replace('/home\.php\?id=(\d+)/is', 'home.php?id=' . $store_id,
										$nav['url']);
									break;
								case '会员主页':
									$nav['url'] =
										preg_replace('/ucenter\.php\?id=(\d+)/is', 'ucenter.php?id=' . $store_id,
											$nav['url']);
									break;
								case '外链': //过滤站内链接
									if(stripos($nav['url'], option('config.site_url')) !== false) {
										$nav['url'] = '#';
									}
									break;
								case '微页面':
									$nav['url'] = '#';
									break;
								case '微页面分类':
									$nav['url'] = '#';
									break;
							}
							$image_navs[] = array(
								'title'  => $nav['title'],
								'name'   => htmlspecialchars($nav['name'], ENT_QUOTES),
								'prefix' => $nav['prefix'],
								'url'    => $nav['url'],
								'image'  => $nav['image']
							);
						}
						$data_fields[$key]['content'] = serialize($image_navs);
						break;
					case 'link': //关联链接
						$data_fields[$key]['store_id'] = $store_id;
						$data_fields[$key]['module_name'] = htmlspecialchars($field['module_name'], ENT_QUOTES);
						$data_fields[$key]['module_id'] = $page_id;
						$data_fields[$key]['field_type'] = $field['field_type'];
						$data_fields[$key]['content'] = array();

						$links = array();
						foreach ($field['content'] as $link) {
							switch ($link['prefix']) {
								case '微页面分类':
									$links[] = array(
										'id'     => $store_id,
										'number' => $link['number'],
										'name'   => $link['name'],
										'url'    => '#',
										'prefix' => $link['prefix'],
										'type'   => $link['type'],
										'widget' => $link['widget'],
										'title'  => $link['title']
									);
									break;
								case '商品分组':
									if(!empty($group_ids[$link['id']])) {
										$link['id'] = $group_ids[$link['id']];
										$link['url'] =
											preg_replace('/goodcat\.php\?id=(\d+)/is', 'goodcat.php?id=' . $link['id'],
												$link['url']);
									}
									else {
										$link['id'] = $store_id;
										$link['url'] = '#';
									}
									$links[] = array(
										'id'     => $link['id'],
										'number' => $link['number'],
										'name'   => $link['name'],
										'url'    => $link['url'],
										'prefix' => $link['prefix'],
										'type'   => $link['type'],
										'widget' => $link['widget'],
										'title'  => $link['title']
									);
									break;
								case '外链':
									if(stripos($link['url'], option('config.site_url')) !== false) {
										$link['url'] = '#';
									}
									$links[] = array(
										'name'   => $link['name'],
										'url'    => $link['url'],
										'prefix' => $link['prefix'],
										'type'   => $link['type'],
										'title'  => $link['title']
									);
									break;
								case '':
									$links[] = array(
										'name'   => $link['name'],
										'url'    => $link['url'],
										'prefix' => $link['prefix'],
										'title'  => $link['title']
									);
									break;
							}

						}
						$data_fields[$key]['content'] = serialize($links);
						break;
					case 'image_ad': //图片广告
						$data_fields[$key]['store_id'] = $store_id;
						$data_fields[$key]['module_name'] = htmlspecialchars($field['module_name'], ENT_QUOTES);
						$data_fields[$key]['module_id'] = $page_id;
						$data_fields[$key]['field_type'] = $field['field_type'];
						$data_fields[$key]['content'] = array();

						$image_ads = array();
						$image_ads['max_height'] = $field['content']['max_height'];
						$image_ads['max_width'] = $field['content']['max_width'];
						$image_ads['nav_list'] = array();
						foreach ($field['content']['nav_list'] as $nav) {
							switch ($nav['prefix']) {
								case '商品分组':
									$params = convertUrlQuery($nav['url']);
									$group_id = !empty($params['id']) ? $params['id'] : '';
									if(!empty($group_id) && !empty($group_ids[$group_id])) { //分组id
										$my_group_id = $group_ids[$group_id];
										$nav['url'] =
											preg_replace('/goodcat\.php\?id=(\d+)/is', 'goodcat.php?id=' . $my_group_id,
												$nav['url']);
									}
									else {
										$nav['url'] = '#';
									}
									break;
								case '商品':
									$params = convertUrlQuery($nav['url']);
									if(!empty($params['id'])) { //商品id
										$product_id = $params['id'];
										if(empty($fx_products[$product_id])) {
											$my_product_id = $fx_products[$product_id];
											$nav['url'] =
												preg_replace('/good\.php\?id=(\d+)/is', 'good.php?id=' . $my_product_id,
													$nav['url']);
										}
										else { //商品不存在或非分销商品
											$nav['url'] = '#';
										}
									}
									break;
								case '店铺主页':
									$nav['url'] = preg_replace('/home\.php\?id=(\d+)/is', 'home.php?id=' . $store_id,
										$nav['url']);
									break;
								case '会员主页':
									$nav['url'] =
										preg_replace('/ucenter\.php\?id=(\d+)/is', 'ucenter.php?id=' . $store_id,
											$nav['url']);
									break;
								case '微页面':
									$nav['url'] = '#';
									break;
								case '微页面分类':
									$nav['url'] = '#';
									break;
								case '外链':
									if(stripos($nav['url'], option('config.site_url')) !== false) {
										$nav['url'] = '#';
									}
									break;
							}
							$image_ads['nav_list'][] = $nav;
						}
						$data_fields[$key]['content'] = serialize($image_ads);
						break;
					case 'goods': //商品
						$data_fields[$key]['store_id'] = $store_id;
						$data_fields[$key]['module_name'] = htmlspecialchars($field['module_name'], ENT_QUOTES);
						$data_fields[$key]['module_id'] = $page_id;
						$data_fields[$key]['field_type'] = $field['field_type'];
						$data_fields[$key]['content'] = array();

						$content = array();
						$content['size'] = $field['content']['size']; //大小
						$content['buy_btn'] = $field['content']['buy_btn']; //是否显示购买按钮
						$content['buy_btn_type'] = $field['content']['buy_btn_type']; //购买按钮样式
						$content['price'] = $field['content']['price']; //是否显示价格

						$goods = array();
						if(!empty($field['content']['goods'])) {
							$good_qty = count($field['content']['goods']); //首页商品数量
							$not_fx_goods = array(); //首页非分销商品
							$is_fx_goods = array(); //所有分销商品
							$i = 0;
							foreach ($field['content']['goods'] as $key2 => $good) {
								if(!empty($fx_products[$good['id']])) { //分销商品
									$goods[$i]['id'] = $fx_products[$good['id']];
									$goods[$i]['title'] = htmlspecialchars($good['title'], ENT_QUOTES);
									$goods[$i]['image'] = $good['image'];
									$tmp_product =
										M('Product')->get(array('product_id' => $fx_products[$good['id']]), 'price');
									$goods[$i]['price'] =
										!empty($tmp_product['price']) ? $tmp_product['price'] : $good['price'];
									$goods[$i]['url'] = preg_replace('/good\.php\?id=(\d+)/is',
										'good.php?id=' . $fx_products[$good['id']], $good['url']);
									$is_fx_goods = $fx_products;
									unset($is_fx_goods[$good['id']]);
									$i++;
								}
								else { //首页非分销商品
									$not_fx_goods[] = $good['id'];
								}
							}
							if(!empty($not_fx_goods) && !empty($is_fx_goods)) { //首页有非分销商品
								$not_fx_good_qty = count($not_fx_goods); //首页非分销商品数量
								$j = 0;
								foreach ($is_fx_goods as $good) {
									if($j < $not_fx_good_qty) { //首页非分销商品所占位置使用非首页分销商品替换，同时保持首页原商品个数
										$tmp_product =
											M('Product')->get(array('product_id' => $good), 'price,name,image');
										if($tmp_product) {
											$goods[$i]['id'] = $good;
											$goods[$i]['title'] = htmlspecialchars($tmp_product['name'], ENT_QUOTES);
											$goods[$i]['image'] = $tmp_product['image'];
											$goods[$i]['price'] = $tmp_product['price'];
											$goods[$i]['url'] = option('config.wap_site_url') . '/good.php?id=' . $good;
											$i++;
										}
									}
									$j++;
								}
							}
						}
						$content['goods'] = $goods;
						$data_fields[$key]['content'] = serialize($content);
						break;
					case 'component': //自定义模块
						//暂不支持
						break;
				}
			}
			$result = false;
			if(!empty($data_fields)) {
				$result = D('Custom_field')->data($data_fields)->addAll();
			}
			$data_fields = array();
			//公共广告（仅支持图片广告）
			if(!empty($supplier['open_ad'])) {
				$ad_list = M('Custom_field')->get_field($supplier['store_id'], 'common_ad', $supplier['store_id']);
				if(!empty($ad_list)) {
					foreach ($ad_list as $key => $field) {
						switch ($field['field_type']) {
							case 'image_ad':
								$data_fields[$key]['store_id'] = $store_id;
								$data_fields[$key]['module_name'] = htmlspecialchars($field['module_name'], ENT_QUOTES);
								$data_fields[$key]['module_id'] = $store_id;
								$data_fields[$key]['field_type'] = $field['field_type'];
								$data_fields[$key]['content'] = array();

								$image_ads = array();
								$image_ads['max_height'] = $field['content']['max_height'];
								$image_ads['max_width'] = $field['content']['max_width'];
								$image_ads['nav_list'] = array();
								foreach ($field['content']['nav_list'] as $nav) {
									$nav['name'] = htmlspecialchars($nav['name'], ENT_QUOTES);
									switch ($nav['prefix']) {
										case '商品分组':
											$params = convertUrlQuery($nav['url']);
											$group_id = !empty($params['id']) ? $params['id'] : '';
											if(!empty($group_id) && !empty($group_ids[$group_id])) { //分组id
												$my_group_id = $group_ids[$group_id];
												$nav['url'] = preg_replace('/goodcat\.php\?id=(\d+)/is',
													'goodcat.php?id=' . $my_group_id, $nav['url']);
											}
											else {
												$nav['url'] = '#';
											}
											break;
										case '商品':
											$params = convertUrlQuery($nav['url']);
											if(!empty($params['id'])) { //商品id
												$product_id = $params['id'];
												if(empty($fx_products[$product_id])) {
													$my_product_id = $fx_products[$product_id];
													$nav['url'] = preg_replace('/good\.php\?id=(\d+)/is',
														'good.php?id=' . $my_product_id, $nav['url']);
												}
												else { //商品不存在或非分销商品
													$nav['url'] = '#';
												}
											}
											break;
										case '店铺主页':
											$nav['url'] =
												preg_replace('/home\.php\?id=(\d+)/is', 'home.php?id=' . $store_id,
													$nav['url']);
											break;
										case '会员主页':
											$nav['url'] = preg_replace('/ucenter\.php\?id=(\d+)/is',
												'ucenter.php?id=' . $store_id, $nav['url']);
											break;
										case '微页面':
											$nav['url'] = '#';
											break;
										case '微页面分类':
											$nav['url'] = '#';
											break;
										case '外链':
											if(stripos($nav['url'], option('config.site_url')) !== false) {
												$nav['url'] = '#';
											}
											break;
									}
									$image_ads['nav_list'][] = $nav;
								}
								$data_fields[$key]['content'] = serialize($image_ads);
								break;
						}
					}
					if(!empty($data_fields)) {
						if(D('Custom_field')->data($data_fields)->addAll()) {
							D('Store')->where(array('store_id' => $store_id))->data(array('open_ad'      => 1,
							                                                              'use_ad_pages' => $supplier['use_ad_pages']))
								->save();
						}
					}
				}
			}

			return $result;
		}
	}
}