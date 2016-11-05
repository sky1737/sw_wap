<?php
require_once dirname(dirname(__FILE__)) . '/global.php';


$tpl = implode(DIRECTORY_SEPARATOR, array_slice(explode(DIRECTORY_SEPARATOR, __FILE__), -2));
$tpl = str_replace(DIRECTORY_SEPARATOR, '/', $tpl);
include display("wap/$tpl");