<?php
/**
 * 供应商 报表
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
if($_GET['a'] == 'sales') {
//	if(empty($now_store)) {
//		redirect('./ucenter.php?id=' . intval(trim($_COOKIE['wap_store_id'])));
//	}
    /**
     * @var $order order_model
     */
	$order = M('Order');
	$type = !empty($_GET['type']) ? trim($_GET['type']) : '';
	if(strtolower($type) == 'today') { //今日销售额
		//今日销售额 00:00-6:00 6:00-12:00 12:00-18:00 18:00-24:00
		//00:00-6:00
		$starttime = strtotime(date('Y-m-d') . ' 00:00:00');
		$stoptime = strtotime(date('Y-m-d') . ' 06:00:00');
		$where = array();
		$where['store_id'] = $now_store['store_id'];
		$where['status'] = array('in', array(1, 2, 3, 4));
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
		$where['status'] = array('in', array(1, 2, 3, 4));
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
		$where['status'] = array('in', array(1, 2, 3, 4));
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
		$where['status'] = array('in', array(1, 2, 3, 4));
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
		$where['status'] = array('in', array(1, 2, 3, 4));
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
		$where['status'] = array('in', array(1, 2, 3, 4));
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
		$where['status'] = array('in', array(1, 2, 3, 4));
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
		$where['status'] = array('in', array(1, 2, 3, 4));
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
		$where['status'] = array('in', array(1, 2, 3, 4));
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
		$where['status'] = array('in', array(1, 2, 3, 4));
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
		$where['status'] = array('in', array(1, 2, 3, 4));
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
		$where['status'] = array('in', array(1, 2, 3, 4));
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
		$where['status'] = array('in', array(1, 2, 3, 4));
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
		$where['status'] = array('in', array(1, 2, 3, 4));
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
		$where['status'] = array('in', array(1, 2, 3, 4));
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
		$where['status'] = array('in', array(1, 2, 3, 4));
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
		$where['status'] = array('in', array(1, 2, 3, 4));
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
		$where['status'] = array('in', array(1, 2, 3, 4));
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
		$where['status'] = array('in', array(1, 2, 3, 4));
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
	$sales = $order->getSales(array('store_id' => $now_store['store_id'], 'status' => array('in', array(1, 2, 3, 4))));
	$now_store['sales'] = number_format($sales, 2, '.', '');

	include display('supplier_store_report');
	echo ob_get_clean();
}