<?php
/**
 * 供应商品
 * User: pigcms_21
 * Date: 2015/4/17
 * Time: 16:32
 */
require_once dirname(__FILE__) . '/agent_check.php';

$product = M('Product');
if (IS_POST) {
    $where = array('status' => 1, 'is_fx' => 1);
    if ($_POST['keyword']) $where['name'] = array('like', "%{$_POST['keyword']}%");

    $product_count = $product->getCount($where);
    //$data = $product->_sql();

    import('source.class.user_page');
    $page_size = !empty($_POST['pagesize']) ? intval($_POST['pagesize']) : 20;
    $page = new Page($product_count, $page_size);
    $products = $product->getProducts($where, $page->firstRow, $page->listRows);

    $data = '';
    if ($products) {
        foreach ($products as $product) {
            $data .= '<tr>
<td><a href="./good.php?id=' . $product['product_id'] . '" style="color:#666;">' . $product['name'] . '</a></td>
<td>￥' . $product['price'] . '</td>
<td><!--('.$product['price'].'-'.$product['cost_price'].')*'.$config['promoter_ratio_1'].'/100.00-->￥' . round(($product['price'] - $product['cost_price']) * $config['promoter_ratio_1'] / 100.00, 2) . '</td>
</tr>';
        }
    }
    echo $data;
    exit;
}

//$all_count = $product->getCount(array('store_id' => $now_store['store_id'], 'status' => 1));
// 'store_id' => $now_store['store_id'],
$count = $product->getCount(array('is_fx' => 1, 'status' => 1));

include display('drp_products');
echo ob_get_clean();