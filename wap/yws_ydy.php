<?php
/**
 *  引导页
 */
require_once dirname(__FILE__).'/global.php';
/*//$page =     I('get.page');
$page = $_GET['page'];
define('TWIKER_PATH', dirname(__FILE__) . '/../');
define('GROUP_NAME', 'wap');
define('IS_SUB_DIR', true);
//    require_once dirname(__FILE__) . '/global.php';
require_once TWIKER_PATH . 'source/init.php';*/
$page = $_GET['page'];
if($page==1){
    include display('yws_ydy1');
}else{
    include display('yws_ydy');
}

echo ob_get_clean();