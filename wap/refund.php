<?php
require_once dirname(__FILE__) . '/global.php';

$order_no = $_GET['orderNo'];

$nowOrder = M('Order')->find($order_no);
if (empty($nowOrder)) pigcms_tips('该订单号不存在', 'none');

$_SESSION['user'] = $wap_user = D('User')->where(array('uid' => $wap_user['uid'], 'status' => 1))->find();

$product_ids = array();
foreach ($nowOrder['proList'] as $key => $value)
{
    $product_ids[] =  $value['product_id'];
}

$product_ids  = implode(',',$product_ids);

$express = M('Express');
$express = $express->getExpress();

include display('refund');
echo ob_get_clean();
exit;
