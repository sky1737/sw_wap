<?php
require_once dirname(__FILE__) . '/global.php';

$order_no = $_GET['orderNo'];

$nowOrder = M('Order')->find($order_no);
if (empty($nowOrder)) pigcms_tips('该订单号不存在', 'none');

if($nowOrder['status'] < 2)
{
    pigcms_tips('订单未支付，不能退款', 'none');
} elseif($nowOrder['status'] == 2)
{
    pigcms_tips('订单未发货，请直接退款', 'none');
}elseif($nowOrder['status'] == 3 || $nowOrder['status'] == 4)
{
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
} elseif($nowOrder['status'] == 5)
{
    pigcms_tips('订单已取消，不能退款', 'none');
}elseif($nowOrder['status'] == 6)
{
    pigcms_tips('已退款中的订单哟！', 'none');
} else
{
    pigcms_tips('订单状态异常，请联系管理员！', 'none');
}
echo ob_get_clean();
exit;


