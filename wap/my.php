<?php
/**
 *  微网站首页
 */
require_once dirname(__FILE__) . '/global.php';

// if(empty($wap_user)) redirect('./login.php?referer='.urlencode($_SERVER['REQUEST_URI']));

//$now_hour = date('H', $_SERVER['REQUEST_TIME']);
//if ($now_hour > 22 || $now_hour < 4) {
//	$time_tip = '午夜好';
//}
//else if ($now_hour < 9) {
//	$time_tip = '早上好';
//}
//else if ($now_hour < 12) {
//	$time_tip = '上午好';
//}
//else if ($now_hour < 19) {
//	$time_tip = '下午好';
//}
//else {
//	$time_tip = '晚上好';
//}

$db_pre = option('system.DB_PREFIX');
$result = D('')->query("SELECT COUNT(CASE WHEN STATUS >= 0 THEN 'un_c' END) AS c1, COUNT(CASE WHEN STATUS = 1 THEN 'un_zf' END) c2, COUNT(CASE WHEN STATUS = 2 THEN 'un_fh' END) c3, COUNT(CASE WHEN STATUS = 3 THEN 'un_sh' END) c4 FROM {$db_pre}order where `uid` = {$wap_user['uid']}");

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

include display('my');

echo ob_get_clean();
