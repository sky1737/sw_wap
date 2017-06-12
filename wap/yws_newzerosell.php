<?php
/**
 *  新零售
 */
require_once dirname(__FILE__).'/global.php';

$id = I('get.id');
if($id){
    dump($id,$_FILES);
}
include display('yws_newzerosell');
echo ob_get_clean();