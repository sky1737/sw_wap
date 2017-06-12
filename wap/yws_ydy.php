<?php
/**
 *  引导页
 */
require_once dirname(__FILE__).'/global.php';
$page =     I('get.page');
if($page==1){
    include display('yws_ydy1');
}else{
    include display('yws_ydy');
}

echo ob_get_clean();