<?php
require_once dirname(dirname(__FILE__)) . '/global.php';

$userInfo    = D('User')->where(array('uid' => $wap_user['uid']))->find();
$storeInfo   = D('Store')->where(array('uid' => $wap_user['uid']))->find();
$companyInfo = D('Company')->where(array('uid' => $wap_user['uid']))->find();

$info = array(
    'comName'    => isset($companyInfo['name']) ? $companyInfo['name'] : $userInfo['nickname'],
    'comTel'     => isset($storeInfo['service_tel']) ? $storeInfo['service_tel'] : $userInfo['tel'],
    'comAddress' => isset($companyInfo['address']) ? $companyInfo['address'] : $userInfo['province'] . $userInfo['city'],
    'uName'      => isset($userInfo['truename']) && $userInfo['truename']? $userInfo['truename'] : $storeInfo['name'],
    'tel'        => isset($userInfo['phone']) ? $userInfo['phone'] : $storeInfo['tel'],
);
//var_dump($userInfo, $storeInfo, $companyInfo,$info); exit;

$tpl = implode(DIRECTORY_SEPARATOR, array_slice(explode(DIRECTORY_SEPARATOR, __FILE__), -2));
$tpl = str_replace(DIRECTORY_SEPARATOR, '/', $tpl);
include display("wap/$tpl");