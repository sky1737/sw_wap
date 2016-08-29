<?php
/**
 *  订单信息
 */
require_once dirname(__FILE__) . '/global.php';

// if(empty($wap_user)) redirect('./login.php?referer='.urlencode($_SERVER['REQUEST_URI']));

// 获取基本参数
$page = max(1, $_GET['page']);
$limit = 5;

//店铺资料
$action = isset($_GET['action']) ? $_GET['action'] : 'all';
$uid = $wap_user['uid'];
$where_sql = "`uid` = '{$uid}'";

$page_url = 'my_order.php?action=' . $action;

$beg_tiem = strtotime('20160909');
$end_tiem = strtotime('20161008');
$where_sql .= " AND status >=2 AND status<=4";
$where_sql .= " AND add_time >= " . $beg_tiem . "AND add_time<=" . $end_tiem;
$where_sql .= " AND store_id in(156,157) ";

/**
 * @var order_model $order_model
 */
$order_model = M('Order');
// 查询订单总数
$count = $order_model->getOrderTotal($where_sql);

// 修正页码
$total_pages = ceil($count / $limit);
$page = min($page, $total_pages);
$offset = ($page - 1) * $limit;

// 查找相应的订单
$order_list = array();
$pages = '';
$physical_id_arr = array();
$store_id_arr = array();
$physical_list = array();
$store_contact_list = array();
if ($count > 0) {
    $order_list = $order_model->getOrders($where_sql, 'order_id desc', $offset, $limit); //status asc,
    $order_product_model = M('Order_product');
    // 将相应的产品放到订单数组里
    foreach ($order_list as &$order_tmp) {
        $order_product_list = $order_product_model->orderProduct($order_tmp['order_id']);

        if ($order_tmp['total'] == '0.00') {
            $order_tmp['total'] = $order_tmp['sub_total'];
        }
        $order_tmp['address'] = unserialize($order_tmp['address']);
        $order_tmp['order_no_txt'] = option('config.orderid_prefix') . $order_tmp['order_no'];
        $order_tmp['url'] = './order.php?orderid=' . $order_tmp['order_id'];
        $order_tmp['refund_url'] = './refund.php?orderid=' . $order_tmp['order_id'];

        // 获取图片地址
        foreach ($order_product_list as &$order_product) {
            $order_product['url'] = 'good.php?id=' . $order_product['product_id'];
        }

        $order_tmp['product_list'] = $order_product_list;

        $store_id_arr[$order_tmp['store_id']] = $order_tmp['store_id'];

    }

    // 分页
    import('source.class.user_page');

    $user_page = new Page($count, $limit, $page);
    $pages = $user_page->show();


    if (!empty($store_id_arr)) {
        $store_contact_list = M('Store_contact')->storeContactList($store_id_arr);
    }
    if (!empty($physical_id_arr)) {
        $physical_list = M('Store_physical')->getListByIDList($physical_id_arr);
    }
}

$db_banner = M('Adver');
$banner = $db_banner->get_adver_by_key('wap_lottery_top', 0);
//print_r($banner);
$footer = $db_banner->get_adver_by_key('wap_lottery_footer', 0);
//print_r($footer);

include display('my_order_act');
echo ob_get_clean();