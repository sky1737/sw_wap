<?php
/**
 * 供应商品
 * User: pigcms_21
 * Date: 2015/4/17
 * Time: 16:32
 */
require_once dirname(__FILE__) . '/agent_check.php';

$product = M('Product');
if (IS_POST && $_POST['type'] == 'get') {
    $where = array(
        'uid' => $wap_user['uid'],
        'store_id' => $now_store['store_id'],
        'status' => 1);
    if ($_GET['fx']) {
        $where['is_fx'] = 1;
    }

    $product_count = $product->getCount($where);

    import('source.class.user_page');
    $page_size = !empty($_POST['pagesize']) ? intval($_POST['pagesize']) : 20;
    $page = new Page($product_count, $page_size);
    $products = $product->getProducts($where, $page->firstRow, $page->listRows);

    $data = '';
    if ($products) {
        foreach ($products as $product) {
            $class = '';

            if ($product['is_fx'])   $class = ' current';

            //var_dump();exit;
            $data .= '<div class="item' . $class .
                '" name="columns" style="margin-bottom: 10px; zoom: 1; opacity: 1;" pid="' .
                $product['product_id'] . '">' .
                '<div><img src="' . $product['image'] . '" />' .
                '<h5 style="font-size: 12px;height:50px;text-align:left;padding: 0 5px;">' . $product['name'] .
                '</h5><div id="off-shelves" data-id="'.$product["product_id"].'" style="font-size: 13px; text-align: right; padding-right: 12px">下架</div>
                <ul class="percent" style="width:auto;">' .
                '<li style="padding: 0 5px;font-size:12px">成本价: ￥' . number_format($product['cost_price']) . '</li>' .
                '<li style="padding: 0 5px;font-size:12px">会员价: ￥' . number_format($product['price']) . '</li>' .
                '<li style="padding: 0 5px;font-size: 12px">市场价：￥' . number_format($product['market_price']) . '</li>' .
                '</ul></div></div>';
        }
    }
    echo $data;
    exit;
}


$all_count = $product->getCount(array('store_id' => $now_store['store_id'], 'status' => 1));
$drp_count = $product->getCount(array('store_id' => $now_store['store_id'], 'is_fx' => 1, 'status' => 1));

include display('supplier_agent_products');
echo ob_get_clean();