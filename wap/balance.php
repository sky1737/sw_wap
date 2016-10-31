<?php
/**
 * 分销佣金
 * User: pigcms_21
 * Date: 2015/4/23
 * Time: 11:42
 */

//require_once dirname(__FILE__) . '/drp_check.php';
require_once dirname(__FILE__) . '/global.php';

//if (empty($now_store)) {
//    pigcms_tips('您没有权限访问，<a href="./home.php?id=' . $_COOKIE['wap_store_id'] . '">返回首页</a>','none');
//}

////分享配置 start  
//$share_conf = array(
//	'title'   => $now_store['name'] . '-分销管理', // 分享标题
//	'desc'    => str_replace(array("\r", "\n"), array('', ''), $now_store['intro']), // 分享描述
//	'link'    => getTwikerUrl($now_store['uid']), // 分享链接
//	'imgUrl'  => $now_store['logo'], // 分享图片链接
//	'type'    => '', // 分享类型,music、video或link，不填默认为link
//	'dataUrl' => '', // 如果type是music或video，则要提供数据链接，默认为空
//);
//import('WechatShare');
//$share = new WechatShare();
//$shareData = $share->getSgin($share_conf);
////分享配置 end

if ($_GET['a'] == 'statistics') {
//	$store_model = M('Store');
    $income_db = M('User_income');

//	$store = $now_store;
//	$store_info = $store_model->getStore($store['store_id']);

    if (IS_POST) {
        $type = trim($_GET['type']);
        if (strtolower($type) == 'today') { //今日佣金
            //今日佣金 00:00-6:00 6:00-12:00 12:00-18:00 18:00-24:00
            //00:00-6:00
            $starttime = strtotime(date('Y-m-d') . ' 00:00:00');
            $stoptime = strtotime(date('Y-m-d') . ' 06:00:00');
            $where = array();
            $where['uid'] = $wap_user['uid'];
            $where['_string'] = "add_time >= " . $starttime . " AND add_time < " . $stoptime;
            $todaycommissiontotal_0_6 = $income_db->sumProfit($where);
            if (!$todaycommissiontotal_0_6) {
                $todaycommissiontotal_0_6 = 0;
            }
            //6:00-12:00
            $starttime = strtotime(date('Y-m-d') . ' 06:00:00');
            $stoptime = strtotime(date('Y-m-d') . ' 12:00:00');
            $where = array();
            $where['uid'] = $wap_user['uid'];
            $where['_string'] = "add_time >= " . $starttime . " AND add_time < " . $stoptime;
            $todaycommissiontotal_6_12 = $income_db->sumProfit($where);
            if (!$todaycommissiontotal_6_12) {
                $todaycommissiontotal_6_12 = 0;
            }
            //12:00-18:00
            $starttime = strtotime(date('Y-m-d') . ' 12:00:00');
            $stoptime = strtotime(date('Y-m-d') . ' 18:00:00');
            $where = array();
            $where['uid'] = $wap_user['uid'];
            $where['_string'] = "add_time >= " . $starttime . " AND add_time < " . $stoptime;
            $todaycommissiontotal_12_18 = $income_db->sumProfit($where);
            if (!$todaycommissiontotal_12_18) {
                $todaycommissiontotal_12_18 = 0;
            }
            //18:00-24:00
            $starttime = strtotime(date('Y-m-d') . ' 18:00:00');
            $stoptime = strtotime(date('Y-m-d') . ' 23:59:59');
            $where = array();
            $where['uid'] = $wap_user['uid'];
            $where['_string'] = "add_time >= " . $starttime . " AND add_time < " . $stoptime;
            $todaycommissiontotal_18_24 = $income_db->sumProfit($where);
            if (!$todaycommissiontotal_18_24) {
                $todaycommissiontotal_18_24 = 0;
            }
            $todaycommissiontotal = "[" . number_format($todaycommissiontotal_0_6, 2, '.', '') . ',' .
                number_format($todaycommissiontotal_6_12, 2, '.', '') . ',' .
                number_format($todaycommissiontotal_12_18, 2, '.', '') . ',' .
                number_format($todaycommissiontotal_18_24, 2, '.', '') . "]";
            echo $todaycommissiontotal;
            exit;
        } else if (strtolower($type) == 'yesterday') { //昨日佣金
            //昨日佣金 00:00-6:00 6:00-12:00 12:00-18:00 18:00-24:00
            $date = date('Y-m-d', strtotime('-1 day'));
            //00:00-6:00
            $starttime = strtotime($date . ' 00:00:00');
            $stoptime = strtotime($date . ' 06:00:00');
            $where = array();
            $where['uid'] = $wap_user['uid'];
            $where['_string'] = "add_time >= " . $starttime . " AND add_time < " . $stoptime;
            $yesterdaycommissiontotal_0_6 = $income_db->sumProfit($where);
            if (!$yesterdaycommissiontotal_0_6) {
                $yesterdaycommissiontotal_0_6 = 0;
            }
            //6:00-12:00
            $starttime = strtotime($date . ' 06:00:00');
            $stoptime = strtotime($date . ' 12:00:00');
            $where = array();
            $where['uid'] = $wap_user['uid'];
            $where['_string'] = "add_time >= " . $starttime . " AND add_time < " . $stoptime;
            $yesterdaycommissiontotal_6_12 = $income_db->sumProfit($where);
            if (!$yesterdaycommissiontotal_6_12) {
                $yesterdaycommissiontotal_6_12 = 0;
            }
            //12:00-18:00
            $starttime = strtotime($date . ' 12:00:00');
            $stoptime = strtotime($date . ' 18:00:00');
            $where = array();
            $where['uid'] = $wap_user['uid'];
            $where['_string'] = "add_time >= " . $starttime . " AND add_time < " . $stoptime;
            $yesterdaycommissiontotal_12_18 = $income_db->sumProfit($where);
            if (!$yesterdaycommissiontotal_12_18) {
                $yesterdaycommissiontotal_12_18 = 0;
            }
            //18:00-24:00
            $starttime = strtotime($date . ' 18:00:00');
            $stoptime = strtotime($date . ' 23:59:59');
            $where = array();
            $where['uid'] = $wap_user['uid'];
            $where['_string'] = "add_time >= " . $starttime . " AND add_time < " . $stoptime;
            $yesterdaycommissiontotal_18_24 = $income_db->sumProfit($where);
            if (!$yesterdaycommissiontotal_18_24) {
                $yesterdaycommissiontotal_18_24 = 0;
            }
            $yesterdaycommissiontotal = "[" . number_format($yesterdaycommissiontotal_0_6, 2, '.', '') . ',' .
                number_format($yesterdaycommissiontotal_6_12, 2, '.', '') . ',' .
                number_format($yesterdaycommissiontotal_12_18, 2, '.', '') . ',' .
                number_format($yesterdaycommissiontotal_18_24, 2, '.', '') . "]";
            echo $yesterdaycommissiontotal;
            exit;
        } else if (strtolower($type) == 'week') {
            $date = date('Y-m-d');  //当前日期
            $first = 1; //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
            $w = date('w', strtotime($date));  //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
            $now_start =
                date('Y-m-d', strtotime("$date -" . ($w ? $w - $first : 6) . ' days')); //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
            $now_end = date('Y-m-d', strtotime("$now_start +6 days"));  //本周结束日期

            //周一佣金
            $starttime = strtotime($now_start . ' 00:00:00');
            $stoptime = strtotime($now_start . ' 23:59:59');
            $where = array();
            $where['uid'] = $wap_user['uid'];
            $where['_string'] = "add_time >= " . $starttime . " AND add_time < " . $stoptime;
            $weekcommissiontotal_1 = $income_db->sumProfit($where);
            if (!$weekcommissiontotal_1) {
                $weekcommissiontotal_1 = 0;
            }
            //周二佣金
            $starttime = strtotime(date("Y-m-d", strtotime($now_start . "+1 day")) . ' 00:00:00');
            $stoptime = strtotime(date("Y-m-d", strtotime($now_start . "+1 day")) . ' 23:59:59');
            $where = array();
            $where['uid'] = $wap_user['uid'];
            $where['_string'] = "add_time >= " . $starttime . " AND add_time < " . $stoptime;
            $weekcommissiontotal_2 = $income_db->sumProfit($where);
            if (!$weekcommissiontotal_2) {
                $weekcommissiontotal_2 = 0;
            }
            //周三佣金
            $starttime = strtotime(date("Y-m-d", strtotime($now_start . "+2 day")) . ' 00:00:00');
            $stoptime = strtotime(date("Y-m-d", strtotime($now_start . "+2 day")) . ' 23:59:59');
            $where = array();
            $where['uid'] = $wap_user['uid'];
            $where['_string'] = "add_time >= " . $starttime . " AND add_time < " . $stoptime;
            $weekcommissiontotal_3 = $income_db->sumProfit($where);
            if (!$weekcommissiontotal_3) {
                $weekcommissiontotal_3 = 0;
            }
            //周四佣金
            $starttime = strtotime(date("Y-m-d", strtotime($now_start . "+3 day")) . ' 00:00:00');
            $stoptime = strtotime(date("Y-m-d", strtotime($now_start . "+3 day")) . ' 23:59:59');
            $where = array();
            $where['uid'] = $wap_user['uid'];
            $where['_string'] = "add_time >= " . $starttime . " AND add_time < " . $stoptime;
            $weekcommissiontotal_4 = $income_db->sumProfit($where);
            if (!$weekcommissiontotal_4) {
                $weekcommissiontotal_4 = 0;
            }
            //周五佣金
            $starttime = strtotime(date("Y-m-d", strtotime($now_start . "+4 day")) . ' 00:00:00');
            $stoptime = strtotime(date("Y-m-d", strtotime($now_start . "+4 day")) . ' 23:59:59');
            $where = array();
            $where['uid'] = $wap_user['uid'];
            $where['_string'] = "add_time >= " . $starttime . " AND add_time < " . $stoptime;
            $weekcommissiontotal_5 = $income_db->sumProfit($where);
            if (!$weekcommissiontotal_5) {
                $weekcommissiontotal_5 = 0;
            }
            //周六佣金
            $starttime = strtotime(date("Y-m-d", strtotime($now_start . "+5 day")) . ' 00:00:00');
            $stoptime = strtotime(date("Y-m-d", strtotime($now_start . "+5 day")) . ' 23:59:59');
            $where = array();
            $where['uid'] = $wap_user['uid'];
            $where['_string'] = "add_time >= " . $starttime . " AND add_time < " . $stoptime;
            $weekcommissiontotal_6 = $income_db->sumProfit($where);
            if (!$weekcommissiontotal_6) {
                $weekcommissiontotal_6 = 0;
            }
            //周日佣金
            $starttime = strtotime(date("Y-m-d", strtotime($now_start . "+6 day")) . ' 00:00:00');
            $stoptime = strtotime(date("Y-m-d", strtotime($now_start . "+6 day")) . ' 23:59:59');
            $where = array();
            $where['uid'] = $wap_user['uid'];
            $where['_string'] = "add_time >= " . $starttime . " AND add_time < " . $stoptime;
            $weekcommissiontotal_7 = $income_db->sumProfit($where);
            if (!$weekcommissiontotal_7) {
                $weekcommissiontotal_7 = 0;
            }
            $weekcommissiontotal = "[" . number_format($weekcommissiontotal_1, 2, '.', '') . ',' .
                number_format($weekcommissiontotal_2, 2, '.', '') . ',' .
                number_format($weekcommissiontotal_3, 2, '.', '') . ',' .
                number_format($weekcommissiontotal_4, 2, '.', '') . ',' .
                number_format($weekcommissiontotal_5, 2, '.', '') . ',' .
                number_format($weekcommissiontotal_6, 2, '.', '') . ',' .
                number_format($weekcommissiontotal_7, 2, '.', '') . "]";
            echo $weekcommissiontotal;
            exit;
        } else if (strtolower($type) == 'month') { //当月佣金
            $month = date('m');
            $year = date('Y');
            //1-7日
            $starttime = strtotime($year . '-' . $month . '-01' . ' 00:00:00');
            $stoptime = strtotime($year . '-' . $month . '-07' . ' 00:00:00');
            $where = array();
            $where['uid'] = $wap_user['uid'];
            $where['_string'] = "add_time >= " . $starttime . " AND add_time < " . $stoptime;
            $monthcommissiontotal_1_7 = $income_db->sumProfit($where);
            if (!$monthcommissiontotal_1_7) {
                $monthcommissiontotal_1_7 = 0;
            }
            //7-14日
            $starttime = strtotime($year . '-' . $month . '-07' . ' 00:00:00');
            $stoptime = strtotime($year . '-' . $month . '-14' . ' 00:00:00');
            $where = array();
            $where['uid'] = $wap_user['uid'];
            $where['_string'] = "add_time >= " . $starttime . " AND add_time < " . $stoptime;
            $monthcommissiontotal_7_14 = $income_db->sumProfit($where);
            if (!$monthcommissiontotal_7_14) {
                $monthcommissiontotal_7_14 = 0;
            }
            //14-21日
            $starttime = strtotime(($year . '-' . $month . '-14') . ' 00:00:00');
            $stoptime = strtotime(($year . '-' . $month . '-21') . ' 00:00:00');
            $where = array();
            $where['uid'] = $wap_user['uid'];
            $where['_string'] = "add_time >= " . $starttime . " AND add_time < " . $stoptime;
            $monthcommissiontotal_14_21 = $income_db->sumProfit($where);
            if (!$monthcommissiontotal_14_21) {
                $monthcommissiontotal_14_21 = 0;
            }
            //21-本月结束
            //当月最后一天
            $lastday = date('t', time());
            $starttime = strtotime($year . '-' . $month . '-21' . ' 00:00:00');
            $stoptime = strtotime($year . '-' . $month . '-' . $lastday . ' 23:59:59');
            $where = array();
            $where['uid'] = $wap_user['uid'];
            $where['_string'] = "add_time >= " . $starttime . " AND add_time <= " . $stoptime;
            $monthcommissiontotal_21_end = $income_db->sumProfit($where);
            if (!$monthcommissiontotal_21_end) {
                $monthcommissiontotal_21_end = 0;
            }
            $data = array();
            $monthcommissiontotal = "[" . number_format($monthcommissiontotal_1_7, 2, '.', '') . ',' .
                number_format($monthcommissiontotal_7_14, 2, '.', '') . ',' .
                number_format($monthcommissiontotal_14_21, 2, '.', '') . ',' .
                number_format($monthcommissiontotal_21_end, 2, '.', '') . "]";
            $data['monthcommissiontotal'] = $monthcommissiontotal;
            $data['lastday'] = $lastday;
            echo json_encode(array('data' => $data));
            exit;
        }
    }

    //店铺余额
    //$balance = $income_db->drpProfit(array('store_id' => $now_store['store_id']));
    //$income = !empty($now_store['drp_profit']) ? $now_store['drp_profit'] : 0;
    //$withdrawal_amount = !empty($now_store['drp_profit_withdrawal']) ? $now_store['drp_profit_withdrawal'] : 0;
    //$balance = $income - $withdrawal_amount;
    $now_store['balance'] = number_format($wap_user['balance'], 2, '.', '');
    $now_store['income'] = $income_db->sumProfit(array('uid' => $wap_user['uid']));

    include display('balance_statistics');
    echo ob_get_clean();
} else if ($_GET['a'] == 'exchange') {
    if (IS_POST) {
        $data = array();
        $data['uid'] = $now_store['uid'];
        $data['point'] = isset($_POST['point']) ? floatval(trim($_POST['point'])) : 0;
        if ($data['point'] < option('config.point_exchange') * 1.00 ||
            ($data['point'] % option('config.point_exchange') != 0)
        ) {
            json_return(1000, '兑换积分数量为 ' . option('config.point_exchange') . ' 的倍数且大于零！');
        }

        $wap_user = D('User')->where(array('uid' => $wap_user['uid'], 'status' => 1))->find();
        if ($wap_user['point'] < $data['point']) {
            json_return(1002, '积分不足，兑换失败！');
        }

        $data['amount'] = round($data['point'] / (option('config.point_exchange') * 1.00), 2);
        $data['add_time'] = time();

        $user_exch = D('User_exch');
        if ($user_exch->data($data)->add()) {
            // 减少余额
            M('User')->applyExchange($data['uid'], $data['point'], $data['amount']);

            // 提现成功
            json_return(0, '');
        } else {
            json_return(1001, '写入日志失败，兑换不成功！');
        }
    }

//	if (empty($now_store['linkman']) || empty($now_store['tel'])) {
//		redirect('./drp_store.php?a=edit', 3, '请先善店铺信息再来提现！');
//	}

    $wap_user = D('User')->where(array('uid' => $wap_user['uid'], 'status' => 1))->find();
    if ($_SESSION['user'] != $wap_user)
        $_SESSION['user'] = $wap_user;

    $exchanged = $wap_user['exchanged'] * 1.00; //已提现
    $point = $wap_user['point'] * 1.00;
    $total = $point + $exchanged;

    $rate = option('config.point_exchange') * 1;

    include display('balance_exchange');
    echo ob_get_clean();
} else if ($_GET['a'] == 'withdrawal') { // 提现申请页面
    if (IS_POST) {
        $data = array();
        $data['uid'] = $now_store['uid'];
        $data['truename'] = $now_store['linkman'];
        $data['amount'] = isset($_POST['amount']) ? floatval(trim($_POST['amount'])) : 0;
        if ($data['amount'] < option('config.withdrawal_min') * 1.00) {
            json_return(1000, '最低提现金额为 ' . round(option('config.withdrawal_min') * 1.00, 2) . ' 元！');
        }
        $data['status'] = 0;
        $data['trade_no'] = date('YmdHis', $_SERVER['REQUEST_TIME']) . mt_rand(100000, 999999);
        $data['add_time'] = time();

        $wap_user = D('User')->where(array('uid' => $wap_user['uid'], 'status' => 1))->find();
        if ($wap_user['balance'] - $wap_user['consumer'] < $data['amount']) {
            json_return(1002, '操作失败，可提现余额不足！');
        }

        $user_cash = D('User_cash');
        if ($user_cash->data($data)->add()) {
            // 微信提现
            $payType = 'weixin';
            $payMethodList = M('Config')->get_pay_method();
            if (empty($payMethodList[$payType])) {
                json_return(1012, '提现方式无效，仅支持微信提现！');
            }

            import('source.class.pay.Weixin');
            $payClass = new Weixin($data, $payMethodList[$payType]['config'], $wap_user['openid'], '');
            $result = $payClass->transfers();
            logs('Transfer_info:' . json_encode($result), 'INFO');
            if ($result['err_code']) {
                json_return(1, $result['err_msg']);
            } else {
                // 减少余额
                M('User')->applywithdrawal($data['uid'], $data['amount']);

                $user_cash->where(array('uid' => $wap_user['uid'], 'trade_no' => $data['trade_no']))
                    ->data(array('status' => 1, 'complate_time' => time()))->save();

                // 提现成功
                json_return(0, $result['err_msg']);
            }
        } else {
            json_return(1001, '写入日志失败，提现不成功！');
        }
    }

    if (empty($now_store['linkman']) || empty($now_store['tel'])) {
        redirect('./drp_store.php?a=edit', 3, '<h2>请先完善店铺信息再来提现！<br>正在跳转。。。<h2>');
    }

//	$card = M('User_card')->getCard($wap_user['uid']);
//	if($card) {
//		$bank = M('Bank')->getBank($card['bank_id']);
//		$card['bank'] = $bank['name'];
//	}

    //$store = $store_model->getStore($now_store['store_id']);
    //可提现金额
    //$balance = $store_model->getBalance($store['store_id']);
    //$balance = number_format($balance, 2, '.', '');
    $wap_user = D('User')->where(array('uid' => $wap_user['uid'], 'status' => 1))->find();
    if ($_SESSION['user'] != $wap_user)
        $_SESSION['user'] = $wap_user;

    $withdrawal = $wap_user['withdrawal'] * 1.00; //已提现
    $balance = $wap_user['balance'] * 1.00 - $wap_user['consumer'] * 1.00;
    $income = $balance + $withdrawal;
    //可提现余额
    //$balance = $income_db->drpProfit(array('store_id' => $store['store_id'], 'status' => 3));

    //$balance = number_format($income - $cash, 2, '.', '');
    //佣金总额
    //$income = $store_model->getIncome($store['store_id']);
    //$income = number_format($income, 2, '.', '');
    //$income = $income_db->drpProfit(array('store_id' => $store['store_id']));
    //$income = number_format($income, 2, '.', '');
    //开户行

    // 提现最低金额
    $withdrawal_min = option('config.withdrawal_min');

    include display('balance_withdrawal');
    echo ob_get_clean();
}
//else if ($_GET['a'] == 'withdraw_account') { //提现账号
//	$db = M('User_card');
//	if (IS_POST) {
//		$card_id = isset($_POST['card_id']) ? intval(trim($_POST['card_id'])) : 0;
//		$bank_id = isset($_POST['bank_id']) ? intval(trim($_POST['bank_id'])) : 0;
//		$bank_name = isset($_POST['bank_name']) ? trim($_POST['bank_name']) : '';
//		$card_no = isset($_POST['card_no']) ? trim($_POST['card_no']) : '';
//		$card_user = isset($_POST['card_user']) ? trim($_POST['card_user']) : '';
//		if (!$bank_id) {
//			json_return(1, '请选择发卡银行！');
//		}
//		if (empty($bank_name)) {
//			json_return(1, '请填写开户行！');
//		}
//		if (!preg_match("/^\d{12,20}$/", $card_no)) {
//			json_return(1, '请填写银行卡号！');
//		}
//		if (!$card_user) {
//			json_encode('请填写持卡人！');
//		}
//		if (!$card_id) {
//			$card = $db->getCard($wap_user['uid']);
//			if (empty($card)) {
//				$db->add(array('uid'       => $wap_user['uid'],
//				               'bank_id'   => $bank_id,
//				               'bank_name' => $bank_name,
//				               'card_no'   => $card_no,
//				               'card_user' => $card_user,
//				               'add_time'  => time()));
//				json_return(0, '添加银行卡成功！');
//			}
//			$card_id = $card['card_id'];
//		}
//
//		$db->save(
//			array('uid'     => $wap_user['uid'],
//			      'card_id' => $card_id),
//			array('bank_id'   => $bank_id,
//			      'bank_name' => $bank_name,
//			      'card_no'   => $card_no,
//			      'card_user' => card_user)
//		);
//		json_return(0, '保存成功！');
//	}
//	$banks = M('Bank')->getEnableBanks();
//	$card = $db->getCard($wap_user['uid']);
//
//	include display('balance_withdrawal_account');
//	echo ob_get_clean();
//}
else if ($_GET['a'] == 'detail') { //佣金明细
    if (IS_AJAX && $_POST['type'] == 'brokeragetab') {
        $user_income = M('User_income');

        $page_size = !empty($_POST['pagesize']) ? intval(trim($_POST['pagesize'])) : 10;
        $count = $user_income->getTotal(array('uid' => $wap_user['uid']));
        import('source.class.user_page');
        $page = new Page($count, $page_size);
        $list =
            $user_income->getRecords(array('uid' => $wap_user['uid']), '`income_id` desc', $page->firstRow,
                $page->listRows);
        $html = '';
        foreach ($list as $item) {
            //SELECT `income_id`, `uid`, `order_id`, `income`, `point`, `type`, `add_time`, `status`, `remarks` FROM `tp_user_income` WHERE 1
            $html .= '<tr>';
            $html .= '<td style="text-align: left"><font style="color:#f00;">' . $user_income->typeTxt($item['type']) .
                '</font><br/>' .
                (empty($item['order_no']) ? '' : '订单号：' . $item['order_no'] . '<br/>') .
                ($item['income'] != 0 ? '金额：' . $item['income'] . '<br/>' : '') .
                ($item['point'] != 0 ? '积分：' . $item['point'] : '') . '</td>';
            $html .= '<td style="text-align: center">' . date('Y-m-d H:i:s', $item['add_time']) . '</td>';
            $html .= '</tr>';
        }
        echo json_encode(array('count' => $count, 'data' => $html));
        exit;
    }
    if (IS_AJAX && $_POST['type'] == 'extracttab') {
        $user_cash = M('User_cash');

        $page_size = !empty($_POST['pagesize']) ? intval(trim($_POST['pagesize'])) : 10;
        $count = $user_cash->getCount(array('c.uid' => $wap_user['uid']));
        import('source.class.user_page');
        $page = new Page($count, $page_size);
        $list =
            $user_cash->getRecords(array('c.uid' => $wap_user['uid']), $page->firstRow,
                $page->listRows);
        $html = '';
        foreach ($list as $cash) {
            $html .= '<tr>';
            $html .= '<td style="text-align: center">' . date('Y-m-d H:i:s', $cash['add_time']) . '</td>';
            $html .= '<td align="right">' . $cash['amount'] . '</td>';
            $html .= '<td style="text-align: center">' . $user_cash->getStatus($cash['status']) .
                '</td>';
            $html .= '</tr>';
        }
        echo json_encode(array('count' => $count, 'data' => $html));
        exit;
    }

    $income_db = M('User_income');
    $cash_db = M('User_cash');
    $order_db = D('Order');

    $where = array();
    $where['uid'] = $wap_user['uid'];
    $where['status'] = 1;

    $record_count = $income_db->getTotal(array('uid' => $wap_user['uid'], 'status' => 1));
    $cash_count = $cash_db->getTotal(array('uid' => $wap_user['uid']));

    // 订单状态
    $types = $income_db->typeTxt(0);

    include display('balance_detail');
    echo ob_get_clean();
}
//else if(IS_POST && $_POST['type'] == 'brokeragetab') {
//	$income_db = M('Financial_record');
//
//	//$store = $now_store;
//	$page_size = !empty($_POST['pagesize']) ? intval(trim($_POST['pagesize'])) : 10;
//	//$record_count = $income_db->getRecordCountByType(3, 5);
//	$where['store_id'] = $now_store['store_id'];
//	$date = strtolower(trim($_POST['date']));
//	if($date == 'today') { //今天佣金明细
//		$starttime = strtotime(date("Y-m-d") . ' 00:00:00');
//		$stoptime = strtotime(date("Y-m-d") . ' 23:59:59');
//		$where['_string'] = "add_time >= " . $starttime . " AND add_time <= " . $stoptime;
//	}
//	else if($date == 'yesterday') { // 昨天佣金明细
//		$date = date('Y-m-d', strtotime('-1 day'));
//
//		$starttime = strtotime($date . ' 00:00:00');
//		$stoptime = strtotime($date . ' 23:59:59');
//		$where['_string'] = "add_time >= " . $starttime . " AND add_time <= " . $stoptime;
//	}
//	else if($date == 'week') { //本周佣金明细
//		$date = date('Y-m-d');  //当前日期
//		$first = 1; //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
//		$w = date('w', strtotime($date));  //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
//		$now_start =
//			date('Y-m-d', strtotime("$date -" . ($w ? $w - $first : 6) . ' days')); //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
//		$now_end = date('Y-m-d', strtotime("$now_start +6 days"));  //本周结束日期
//
//		$starttime = strtotime($now_start . ' 00:00:00');
//		$stoptime = strtotime($now_end . ' 23:59:59');
//		$where['_string'] = "add_time >= " . $starttime . " AND add_time <= " . $stoptime;
//	}
//	else if($date == 'month') { //本月佣金明细
//		$month = date('m');
//		$year = date('Y');
//		//当月最后一天
//		$lastday = date('t', time());
//
//		$starttime = strtotime($year . '-' . $month . '-01' . ' 00:00:00');
//		$stoptime = strtotime($year . '-' . $month . '-' . $lastday . ' 23:59:59');
//		$where['_string'] = "add_time >= " . $starttime . " AND add_time <= " . $stoptime;
//	}
//	$record_count = $income_db->getProfitRecordCount($where);
//	import('source.class.user_page');
//	$page = new Page($record_count, $page_size);
//	//$records = $income_db->getRecordsByType(3, 5, $page->firstRow, $page->listRows);
//	$records = $income_db->getProfitRecords($where, $page->firstRow, $page->listRows);
//	$html = '';
//	foreach ($records as $record) {
//		$html .= '<tr>';
//		$html .= '<td>' . $record['order_id'] . '</td>';
//		$html .= '<td align="right">' . $record['profit'] . '</td>';
//		$html .= '<td style="text-align: center">' . date('Y-m-d', $record['add_time']) . '</td>';
//		if($record['status'] == 3) {
//			$html .= '<td style="text-align: center;color:green">交易完成</td>';
//		}
//		else {
//			$html .= '<td style="text-align: center;color:red">进行中</td>';
//		}
//		$html .= '</tr>';
//	}
//	echo json_encode(array('count' => count($records), 'data' => $html));
//	exit;
//}
//else if(IS_POST && $_POST['type'] == 'extracttab') { //提现记录
//	$user_cash = M('Store_withdrawal');
//
//	//$store = $now_store;
//	$page_size = !empty($_POST['pagesize']) ? intval(trim($_POST['pagesize'])) : 10;
//	$withdrawal_count = $user_cash->getCashCount(array('sw.store_id' => $now_store['store_id']));
//	import('source.class.user_page');
//	$page = new Page($withdrawal_count, $page_size);
//	$withdrawals =
//		$user_cash->getCashs(array('sw.store_id' => $now_store['store_id']), $page->firstRow,
//			$page->listRows);
//	$html = '';
//	foreach ($withdrawals as $cash) {
//		$html .= '<tr>';
//		$html .= '<td style="text-align: center">' . date('Y-m-d H:i:s', $cash['add_time']) . '</td>';
//		$html .= '<td align="right">' . $cash['amount'] . '</td>';
//		$html .= '<td style="text-align: center">' . $user_cash->getCashStatus($cash['status']) .
//			'</td>';
//		$html .= '</tr>';
//	}
//	echo json_encode(array('count' => count($records), 'data' => $html));
//	exit;
//}
else if ($_GET['a'] == 'recharge') {
    if (IS_POST) {
        $balance = $_POST['balance'];
        if ($balance <= 0) {
            json_return(1, '错误的充值金额！');
        }
        $time = time();
        $recharge = D('Recharge')->where(array('amount' => $balance, 'start_time' => array('<', $time),
            'end_time' => array('>', $time),
            'status' => 1))->find();
        if (empty($recharge)) {
            json_return(1, '错误的充值金额，请重新选择！');
        }

        $payType = 'weixin';
        $db_recharge_order = D('Recharge_order');
        $nowOrder = $db_recharge_order->where(array('uid' => $wap_user['uid'],
            'total' => $recharge['amount'],
            'point' => $recharge['point'],
            'status' => 1))->find();
        if (empty($nowOrder)) {
            $nowOrder = array(
                'order_no' => date('YmdHis', $_SERVER['REQUEST_TIME']) . mt_rand(100000, 999999),
                'trade_no' => date('YmdHis', $_SERVER['REQUEST_TIME']) . mt_rand(100000, 999999),
                'uid' => $wap_user['uid'],
                'total' => $recharge['amount'],
                'point' => $recharge['point'],
                'profit' => $recharge['profit'],
                'pay_type' => $payType,
                'status' => 1,
                'add_time' => time(),
                'remarks' => '充值 ' . $recharge['amount'] . ' 元赠送积分 ' . $recharge['point'] . '。',
                'pay_money' => $recharge['amount']);
            if (!$nowOrder['order_id'] = $db_recharge_order->data($nowOrder)->add()) {
                json_return(1, '生成支付订单失败，请重试！');
            }
        }

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
    }
    $wap_user = D('User')->where(array('uid' => $wap_user['uid'], 'status' => 1))->find();
    if ($_SESSION['user'] != $wap_user)
        $_SESSION['user'] = $wap_user;

    $balance = !empty($wap_user['balance']) ? $wap_user['balance'] : 0;
    $point = !empty($wap_user['point']) ? $wap_user['point'] : 0;

    $time = time();
    $list = D('Recharge')->where(array(
        'start_time' => array('<', $time),
        'end_time' => array('>', $time),
        'status' => 1
    ))->select();

    include display('balance_recharge');
    echo ob_get_clean();
} else {
    //$store_model = M('Store');
    //$income_db = M('Financial_record');

    //$store = $now_store;
    //$store_info = $store_model->getStore($store['store_id']);
    $wap_user = D('User')->where(array('uid' => $wap_user['uid'], 'status' => 1))->find();
    if ($_SESSION['user'] != $wap_user)
        $_SESSION['user'] = $wap_user;

    $consumer = !empty($wap_user['consumer']) ? $wap_user['consumer'] : 0;
    $balance = !empty($wap_user['balance']) ? $wap_user['balance'] : 0;

    //可提现余额
    //$balance = $income_db->drpProfit(array('store_id' => $now_store['store_id']));
    //已提现金额
    //$withdrawal_amount = $store_model->getCashAmount($now_store['store_id']);
//	$withdrawal_amount = !empty($now_store['drp_profit_withdrawal']) ? $now_store['drp_profit_withdrawal'] : 0;
//	$balance = $income - $withdrawal_amount;
//	$balance = number_format($balance, 2, '.', '');
//	$withdrawal_amount = number_format($withdrawal_amount, 2, '.', '');

    $point = !empty($wap_user['point']) ? $wap_user['point'] : 0;

    $cash = 0.00;
    //店铺url
    //$store_url = option('config.wap_site_url') . '/home.php?id=' . $now_store['store_id'];

    include display('balance');
    echo ob_get_clean();
}