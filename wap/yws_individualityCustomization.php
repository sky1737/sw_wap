<?php
/**
 *  引导页
 */
require_once dirname(__FILE__).'/global.php';
/*//$page =     I('get.page');
$page = $_GET['page'];
define('TWIKER_PATH', dirname(__FILE__) . '/../');
define('GROUP_NAME', 'wap');
define('IS_SUB_DIR', true);
//    require_once dirname(__FILE__) . '/global.php';
require_once TWIKER_PATH . 'source/init.php';*/

$cats = D('Product_category')->field('cat_name,cat_id')->where(array('cat_fid'=>0,'cat_parent_status'=>1))->select();

$SQL = "select cat_fid , group_concat(cat_id) as str from tp_product_category where cat_fid > 0 group by cat_fid ";
$catidstr = D()->query($SQL);

foreach ($catidstr as $val){
    foreach ($cats as $cat){
        if($val['cat_fid']==$cat['cat_id']){
            $menu[$cat['cat_name']]=$val;
        }
    }
}
//dump($menu);
//$str = '1母婴用品,2服装配饰,3鞋子箱包,4美食特产,5美妆护理,6数码产品,7汽车配件,8家电生活,9家纺家饰,10厨具收纳';

$menuearr[0] =array('194',"1母婴用品");
$menuearr[1] =array('173','179',"2服装配饰");
$menuearr[2] =array('197',"3鞋子箱包");
$menuearr[3] =array('464',"4美食特产");
$menuearr[4] =array('186',"5美妆护理");
$menuearr[5] =array('175',"6数码产品");
$menuearr[6] =array('189',"7汽车配件");
$menuearr[7] =array('195',"8家电生活");
$menuearr[8] =array('196',"9家纺家饰");
$menuearr[9] =array('176',"10厨具收纳");

/*$menuearr[173][179] ="2服装配饰";
$menuearr[197][197] ="3鞋子箱包";
$menuearr[464][464] ="4美食特产";
$menuearr[186][186] ="5美妆护理";
$menuearr[175][175] ="6数码产品";
$menuearr[189][189] ="7汽车配件";
$menuearr[195][195] ="8家电生活";
$menuearr[196][196] ="9家纺家饰";
$menuearr[176][176] ="10厨具收纳";*/

foreach ($menuearr as $k=>$v){
    $cat_ids[$k]='';
    foreach ($menu as $s){
        if(in_array($s['cat_fid'],$v)){
            $cat_ids[$k] .= $s['str'].',';
        }
    }
}
//dump($cat_ids);
include display('yws_individualityCustomization');

echo ob_get_clean();
