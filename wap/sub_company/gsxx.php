<?php
require_once dirname(dirname(__FILE__)) . '/global.php';

$userInfo    = D('User')->where(array('uid' => $wap_user['uid']))->find();
$storeInfo   = D('Store')->where(array('uid' => $wap_user['uid']))->find();
$companyInfo = D('Company')->where(array('uid' => $wap_user['uid']))->find();

$info = array(
'name' => isset($storeInfo['name']) ?  $storeInfo['name'] : $userInfo['nickname'],
'uName' => isset($userInfo['name']) ?  $userInfo['name'] : $storeInfo['nickname'],
'serviceTel' => isset($userInfo['name']) ?  $userInfo['name'] : $storeInfo['service_tel'],
);
//var_dump($userInfo,$storeInfo,$companyInfo);
//var_dump($info);exit;

$tpl = implode(DIRECTORY_SEPARATOR, array_slice(explode(DIRECTORY_SEPARATOR, __FILE__), -2));
$tpl = str_replace(DIRECTORY_SEPARATOR, '/', $tpl);
include display("wap/$tpl");