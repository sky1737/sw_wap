<?php
/**
 *  店铺主页
 */
require_once dirname(__FILE__) . '/global.php';

//var_dump($store);
// 模板类型
//var_dump($config['is_diy_template']);
//if ($config['is_diy_template']) {
// 微杂志的自定义字段
$homePage = D('Wei_page')->where(array('is_home' => 1, 'store_id' => $now_store['store_id']))->find();
if ($homePage['has_custom']) {
    $pageContent = '';
    $homeCustomField = M('Custom_field')->getParseFields(0, 'page', $homePage['page_id']);
    if ($homeCustomField) {
        foreach ($homeCustomField as $value) {
            $pageContent .= $value['html'];
        }
    }
} else {
    $banners = M('Adver')->get_adver_by_key('wap_index_banner', 10);
}
//var_dump($homeCustomField);

if ($now_store['has_ad'] && !empty($now_store['use_ad_pages'])) {
// 公共广告判断
    $pageAd = $_SESSION['page_add'];
    if (!$pageAd) {
        if (strpos($now_store['use_ad_pages'], '5') !== false) {
            $pageAdFieldArr =
                M('Custom_field')->getParseFields($now_store['store_id'], 'common_ad', $now_store['store_id']);
            if ($pageAdFieldArr) {
                foreach ($pageAdFieldArr as $value) {
                    $pageAd .= $value['html'];
                }
            }
        }
    }
} else {
// 首页幻灯片
    $slide = M('Adver')->get_adver_by_key('wap_index_slide_top', 5);
}

// 分类列表
$categories = M('Product_category')->getIndexCategories();
$category_products = array();
$db_product = M('Product');
foreach ($categories as $val) {
    $category_products[] = $db_product
        ->getSelling('status = 1 and is_fx = 1 AND is_recommend = 1 AND (category_fid = ' . $val['cat_id'] . ' OR category_id = ' .
            $val['cat_id'] . ')', '', '', 0, 4);
}

$db_banner = M('Adver');
$category_banners = $db_banner->get_adver_by_key('wap_category_banner', 0);
$category_banners2 = array();
foreach ($category_banners as $b) {
    $category_banners2[$b['name']] = $b;
}
$category_banners = $category_banners2;

$toutiao = $db_banner->get_adver_by_key('wap_toutiao',0);
$banner4 = $db_banner->get_adver_by_key('wap_banner',0);
$youxuan = $db_banner->get_adver_by_key('wap_youxuan',0);
$remai = $db_banner->get_adver_by_key('wap_remain',0);
$xinpin = $db_banner->get_adver_by_key('wap_xinpin',0);

//	// 首页自定义导航
//	$slider_nav = M('Slider')->get_slider_by_key('wap_index_nav', 16);

//	// 热门品牌下方广告
//	$hot_brand_slide = M('Adver')->get_adver_by_key('wap_index_brand', 2);

//	// 首页分类
//	$cat = D('Product_category')->where("cat_status = 1 and cat_level = 2 and cat_pic != ''")
//		->order('cat_sort ASC,cat_id DESC')->limit('12')->select();
//	foreach ($cat as $key => $value) {
//		$cat[$key]['cat_pic'] = getAttachmentUrl($value['cat_pic']);
//	}

//// 附近的店铺
//$stores = M('Store')->getStoreByRoundDistance(array('status' => 1, 'approve' => 1), 4, 0, '', '', false, true);
////	D('Store')->where(array('status' => 1, 'approve' => 1))->field('`store_id`, `name`, `logo`')
////	->order('`store_id` DESC')->limit(12)->select();
//foreach ($stores as $key => $value) {
//	$stores[$key]['url'] = 'home.php?id=' . $value['store_id'];
//	if (empty($value['logo'])) {
//		$stores[$key]['logo'] = getAttachmentUrl('images/default_shop_2.jpg', false);
//	}
//	else {
//		$stores[$key]['logo'] = getAttachmentUrl($value['logo']);
//	}
//}

// 附近的商品
//$product_list = M('Product')->getSellingBydistance('status = 1', '', '', 0, 4);
//getSelling('quantity > 0 AND status = 1 AND is_recommend = 1', '', '', 0, 4);
//D('Product')->where('quantity>0 AND status=1 AND is_recommend = 1')->limit('4')->select();

//	// 首页推荐活动
//	$active_list = D('Activity_recommend')->order("is_rec desc, ucount desc")->limit(4)->select();
//	$activity = M('activity');
//	foreach ($active_list as $k => $value) {
//		$active_list[$k]["url"] = $activity->createUrl($value, $value['model'], '1');
//		$active_list[$k]["image"] = getAttachmentUrl($value['image']);
//	}
//}
//else {
//	if (empty($config['platform_mall_index_page'])) {
//		pigcms_tips('请管理员在管理后台【系统设置】=》【站点配置】=》【平台商城配置】选取微页面', 'none');
//	}
//
//	//首页的微杂志
//	$homePage = D('Wei_page')->where(array('page_id' => $config['platform_mall_index_page']))->find();
//	if (empty($homePage)) {
//		pigcms_tips('您访问的店铺没有首页', 'none');
//	}
//}

//$imUrl = getImUrl($_SESSION['user']['uid'], $store_id);
//$is_have_activity = option('config.is_have_activity');

//分享配置 start
$share_conf = array(
    'title' => empty($now_store) ? $config['site_name'] : $now_store['name'], // 分享标题
    'desc' => str_replace(array("\r", "\n"), array('', ''), option('config.seo_description')), // 分享描述
    'link' => getTwikerUrl($now_store['uid']), // 分享链接
    'imgUrl' => option('config.site_logo'), // 分享图片链接
    'type' => '', // 分享类型,music、video或link，不填默认为link
    'dataUrl' => '', // 如果type是music或video，则要提供数据链接，默认为空
);
import('WechatShare');
$share = new WechatShare();
$shareData = $share->getSgin($share_conf);
//分享配置 end

include display('index');

echo ob_get_clean();