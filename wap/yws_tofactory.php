<?php
/**
 *  引导页
 */
require_once dirname(__FILE__).'/global.php';
$storesid = D();
   //$sql = "select s.store_id as s_sid,p.store_id p_sid, s.name,s.date_added,s.logo,s.approve from tp_product p left join tp_store s on p.store_id=s.store_id where s.approve=1";
    $sql = "select *,count(*) num from (select s.store_id as s_sid,p.store_id p_sid, s.name,s.date_added,s.logo,s.approve from tp_product p left join tp_store s on p.store_id=s.store_id where s.approve=1) as tmp group by p_sid order by num desc";
    $storess = $storesid->query($sql);

    foreach ($storess as $val){
        if(strpos($val['logo'],"yes_")!==false){
            $logo[]=$val;
        }else{
            $s[]=$val;
        }
    }
    $stores = array_merge($logo,$s);
   // dump($logo);
    include display('yws_tofactory');
    echo ob_get_clean();



