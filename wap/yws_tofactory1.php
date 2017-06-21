<?php
/**
 *  引导页
 */
require_once dirname(__FILE__).'/global.php';
$storesid = D();
$name = $_POST['name'];
//dump(I('POST'));
    //dump($name);
if($name=='' || !$name){
    $sql = "select *,count(*) num from (select s.store_id as s_sid,p.store_id p_sid, s.name,s.date_added,s.logo,s.approve from tp_product p left join tp_store s on p.store_id=s.store_id where s.approve=1 ) as tmp group by p_sid order by num desc ";
    //dump($sql);
    $storess = $storesid->query($sql);
    //dump($stores);
    foreach ($storess as $val){
        if(strpos($val['logo'],"yes_")!==false){
            $logo[]=$val;
        }else{
            $s[]=$val;
        }
    }
    $stores = array_merge($logo,$s);
    include display('yws_tofactorysearch');
    echo ob_get_clean();
}else{
    $sql = "select *,count(*) num from (select s.store_id as s_sid,p.store_id p_sid, s.name,s.date_added,s.logo,s.approve from tp_product p left join tp_store s on p.store_id=s.store_id where s.approve=1 ) as tmp group by p_sid order by num desc ";
    //dump($sql);
    $storesss = $storesid->query($sql);
    //dump($storess);
    foreach ($storesss as $val){
        //dump(strpos($val['name'],$name));
        if(strpos($val['name'],$name)!==false){
            $storess[]=$val;
        }
    }
    if($storess){
        foreach ($storess as $val){
            if(strpos($val['logo'],"yes_")!==false){
                $logo[]=$val;
            }else{
                $s[]=$val;
            }
        }
        if($logo && $s){
            $stores = array_merge($logo,$s);
        }else{
            $stores = empty($logo)?$s:$logo;
        }

    }
    //dump($stores);
    include display('yws_tofactorysearch');
    echo ob_get_clean();

}
