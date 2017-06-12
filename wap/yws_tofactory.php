<?php
/**
 *  引导页
 */
require_once dirname(__FILE__).'/global.php';

//$stores = M('store')->where()->select('name','logo');
$stores = D('Store')->field('`name` ,`date_added` ,`logo`')->limit(10)->order('date_added desc')->where('approve=1')->select();

include display('yws_tofactory');
echo ob_get_clean();