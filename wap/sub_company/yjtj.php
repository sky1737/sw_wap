<?php
require_once dirname(dirname(__FILE__)) . '/global.php';

$mySuppliersUIds = D('User')->field('uid')->where(array('parent_uid' => $wap_user['uid']))->select();
$sUIds           = array(); //下级 供应商 uid
foreach ($mySuppliersUIds as $sUInfo) $sUIds[] = $sUInfo['uid'];
$sUidStr = implode(',', $sUIds);

//时间段 计算
$startMonth     = intval($_GET['month']);
$endMonth       = $startMonth - 1;
$startTimestamp = strtotime(date('Y-m-1') . " -$startMonth month");
$endTimestamp   = strtotime(date('Y-m-1') . " +1 month");
$timeTerm       = " AND  $startTimestamp<complate_time AND  complate_time<$endTimestamp";

$db_pre = option('system.DB_PREFIX');
$sql    = "SELECT SUM(sub_total) AS sub_totals FROM {$db_pre}order where `agent_id` in (91,194) and status=4 AND complate_time";
//var_dump($sql . $timeTerm);exit;
$monthResult    = D('')->query($sql . $timeTerm);
$monthSubTotals = intval(isset($monthResult[0]['sub_totals']) ? $monthResult[0]['sub_totals'] : 0);

$allResult    = D('')->query($sql);
$allSubTotals = intval(isset($allResult[0]['sub_totals']) ? $allResult[0]['sub_totals'] : 0);

$data = array(
    '1'  => array(
        'x'     => array('1-3日', '4-6日', '7-9日', '10-12日', '13-15日', '16-18日', '19-21日', '22-24日', '25-27日', '28-30日'),
        'y'     => array(65, 59, 80, 81, 56, 55, 40, 90, 23, 56),
        'total' => 560,
    ),
    '3'  => array(
        'x'     => array('8月上', '8月中', '8月下', '9月上', '9月中', '9月下', '10月上', '10月中', '10月下'),
        'y'     => array(124, 178, 240, 243, 168, 165, 120, 255, 101),
        'total' => 1560,
    ),
    '6'  => array(
        'x'     => array('5月上', '5月下', '6月上', '6月下', '7月上', '7月下', '8月上', '8月下', '9月上', '9月下', '10月上', '10月下'),
        'y'     => array(201, 345, 480, 486, 336, 320, 240, 510, 202, 102, 277, 178),
        'total' => 2560,
    ),
    '12' => array(
        'x'     => array('1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'),
        'y'     => array(402, 690, 960, 1020, 672, 640, 480, 1020, 404, 204, 56, 0),
        'total' => 3560,
    ),
    '0'  => array(
        'x'     => array(),
        'y'     => array(),
        'total' => 0,

    ),
);
$data = json_encode($data);


$tpl = implode(DIRECTORY_SEPARATOR, array_slice(explode(DIRECTORY_SEPARATOR, __FILE__), -2));
$tpl = str_replace(DIRECTORY_SEPARATOR, '/', $tpl);
include display("wap/$tpl");