<?php
require_once dirname(dirname(__FILE__)) . '/global.php';


$mySuppliersUIds = D('User')->field('uid')->where(array('parent_uid' => $wap_user['uid']))->select();
$sUIds           = $agentID2Name = array(); //下级 供应商 uid
foreach ($mySuppliersUIds as $sUInfo)
{
    $agentID2Name[ $sUInfo['uid'] ] = $sUInfo['nickname'];
    $sUIds[]                        = $sUInfo['uid'];
}
$sUidStr = implode(',', $sUIds);

//时间段 计算
$startMonth     = intval($_GET['month']);
$endMonth       = $startMonth - 1;
$startTimestamp = strtotime(date('Y-m-1') . " -$startMonth month");
$endTimestamp   = strtotime(date('Y-m-1') . " +1 month");
$timeTerm       = " AND  $startTimestamp<complate_time AND  complate_time<$endTimestamp";

$db_pre = option('system.DB_PREFIX');
$sql    = "SELECT profit, sub_total, complate_time FROM {$db_pre}order where `agent_id` in (91,194) and status=4 AND complate_time";
//var_dump($sql . $timeTerm);exit;
$sellInfo = D('')->query($sql . $timeTerm);
//var_dump($sql . $timeTerm,$sellInfo);exit;
$monthProfit = $monthSubTotals = 0;
foreach ($sellInfo as $info)
{
    $monthProfit += $info['profit'];
    $monthSubTotals += $info['sub_total'];
}

$tpl = implode(DIRECTORY_SEPARATOR, array_slice(explode(DIRECTORY_SEPARATOR, __FILE__), -2));
$tpl = str_replace(DIRECTORY_SEPARATOR, '/', $tpl);
include display("wap/$tpl");