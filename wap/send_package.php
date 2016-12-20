<?php
require_once dirname(__FILE__) . '/global.php';

$order_no = $_GET['orderNo'];

$nowOrder = M('Order')->find($order_no);
if (empty($nowOrder)) pigcms_tips('该订单号不存在', 'none');

if($nowOrder['status'] < 2)
{
    pigcms_tips('订单未支付，不能发货', 'none');
} elseif($nowOrder['status'] == 2)
{

    //收获地址
    $addressAry = unserialize($nowOrder['address']);
    $address = "{$addressAry['province']},{$addressAry['city']},{$addressAry['area']},{$addressAry['address']},{$addressAry['area_code']}";

    $_SESSION['user'] = $wap_user = D('User')->where(array('uid' => $wap_user['uid'], 'status' => 1))->find();
    $product_ids = array();
    foreach ($nowOrder['proList'] as $key => $value)
    {
        $product_ids[] =  $value['product_id'];
    }
    $product_ids  = implode(',',$product_ids);
    //物流信息获取
    $express = M('Express');
    $express = $express->getExpress();

    include display('send_package');

}elseif($nowOrder['status'] == 3) {

    pigcms_tips('订单已发货', 'none');
}elseif($nowOrder['status'] == 4)
{
    pigcms_tips('订单已完成', 'none');

} elseif($nowOrder['status'] == 5)
{
    pigcms_tips('订单已取消，不能发货', 'none');
}elseif($nowOrder['status'] == 6)
{
    pigcms_tips('已退款中的订单哟！', 'none');
} else
{
    pigcms_tips('订单状态异常，请联系管理员！', 'none');
}
echo ob_get_clean();
exit;


