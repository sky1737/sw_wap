<?php
require_once dirname(__FILE__) . '/global.php';

$order_id = $_GET['orderid'];

if(!$order_id) {
    echo json_encode(array('status' => false, 'msg' => '参数错误！'));
    exit;
}
$order_model = M('Order');
$nowOrder = $order_model->findOrderById($order_id);

$_SESSION['user'] = $wap_user = D('User')->where(array('uid' => $wap_user['uid'], 'status' => 1))->find();

include display('refund');
echo ob_get_clean();
exit;
