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

$db_pre         = option('system.DB_PREFIX');
$sql            = "SELECT SUM(sub_total) AS sub_totals FROM {$db_pre}order where `agent_id` in (91,194) and status=4 AND complate_time";
//var_dump($sql . $timeTerm);exit;
$monthResult    = D('')->query($sql . $timeTerm);
$monthSubTotals = intval(isset($monthResult[0]['sub_totals']) ? $monthResult[0]['sub_totals'] : 0);

$allResult    = D('')->query($sql);
$allSubTotals = intval(isset($allResult[0]['sub_totals']) ? $allResult[0]['sub_totals'] : 0);

$tpl = implode(DIRECTORY_SEPARATOR, array_slice(explode(DIRECTORY_SEPARATOR, __FILE__), -2));
$tpl = str_replace(DIRECTORY_SEPARATOR, '/', $tpl);
include display("wap/$tpl");