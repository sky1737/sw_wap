<?php
/**
 *  微网站首页
 */
require_once dirname(__FILE__) . '/global.php';
$keyword = $_GET['keyword'];
$storeid = $_GET['storeid'];
//分享配置 start
$share_conf = array(
    'title'   => option('config.site_name'), // 分享标题
    'desc'    => str_replace(array("\r", "\n"), array('', ''), option('config.seo_description')), // 分享描述
    'link'    => getTwikerUrl($now_store['uid']), // 分享链接
    'imgUrl'  => option('config.site_logo'), // 分享图片
    'type'    => '', // 分享类型,music、video或link，不填默认为link
    'dataUrl' => '', // 如果type是music或video，则要提供数据链接，默认为空
);
import('WechatShare');
$share = new WechatShare();
$shareData = $share->getSgin($share_conf);

$boom = $_GET['boom'];

$cat_ids = $_GET['cat_ids'];
if($boom){
    //b爆款
    //dump(';boom');
    $product = D('Product')->field('product_id','sales','name')->where('sales>=1')->select();
    $is_boom = 1;
    include display('yws_index_booms');
}elseif($cat_ids){
    //个性定制
    $storeid = intval($storeid);
    include display('yws_mystyle_product');
}else{
    //厂家直供
    $storeid = intval($storeid);
    include display('index_category_detail2');
}

echo ob_get_clean();
