<?php

class index_controller extends base_controller
{

    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {
        if (is_mobile()) {
            redirect('/h5');
            //redirect($this->config['wap_site_url']);
        }

        $index_cache = $this->config['web_index_cache'];
        if ($index_cache > 0) {
            $content = S('index');
            if ($content) {
                echo $content;
                exit;
            }
        }

        $adver = M('Adver');
        //幻灯广告位
        $adList = $adver->get_adver_by_key('pc_slide', 6);
        $this->assign('adList', array_values($adList));            //幻灯广告位

        // 热门品牌
        $hot_brands = $adver->get_adver_by_key('pc_hot_brand', 0);
        $this->assign('hot_brands', $hot_brands);

        // 幻灯片右侧广告位
        $adList_right = $adver->get_adver_by_key('pc_slide_right', 1);
        $this->assign('adList_right', array_values($adList_right));    // 幻灯片右侧广告位

        // 幻灯片右侧广告位
        $ad_footer = $adver->get_adver_by_key('pc_footer', 0);
        $this->assign('pc_footer', $ad_footer);
        
        // 首页顶部广告
        $pc_index_top = $adver->get_adver_by_key('pc_index_top', 0);
        $this->assign('pc_index_top', $pc_index_top);

        // 获取您周边店铺数量
        $WebUserInfo = show_distance();
        if (isset($this->user_location) &&
            $WebUserInfo = $this->user_location && $WebUserInfo['long'] && $WebUserInfo['lat']
        ) {
            $nearshop_count = M('Store')->nearshop_count($WebUserInfo['long'], $WebUserInfo['lat']);
        } else {
            $nearshop_count = 0;
        }
        $this->assign('nearshop_count', $nearshop_count);

        $ad_activity_left = M('Adver')->get_adver_by_key('pc_activity_left', 1);
        $this->assign('ad_activity_left', $ad_activity_left);

        // 活动广告
        $ad_activity_list = M('Adver')->get_adver_by_key('pc_activity', 0);
        $this->assign('ad_activity_list', $ad_activity_list);

//		//店铺数 分销商数
//		$common_data = array();
//		$common_data_arr = D('Common_data')->where("`key` in ('store_qty', 'drp_seller_qty')")->select();
//		foreach ($common_data_arr as $data) {
//			$common_data[$data['key']] = $data;
//		}

        //热卖商品 (目前获取id 最优先的)
        //加载产品类别
        $db_product_category = M('Product_category');
        $hot_products['category'] = $db_product_category->getAllCategory('', true); // getAllCategory
        //var_dump($hot_products['category']);
        $this->assign('hot_products', $hot_products);                // 热门商品


//		if(!empty($this->store_session)) {
//			$this->assign('selfGoods', '1');
//		}

//		$where = "p.quantity > 0"; // and 'p.supplier_id'=0
//		//默认
//		$hot_products['product'] =
//			M('Product')->getSellingAndDistance($where, "sales", "desc", 0, 10, 1);

        //周边店铺
        //模拟用户坐标  117.22895,31.866208
        $cookie_location = show_distance();
        if ($cookie_location['status']) {
            $long = $cookie_location['long'];
            $lat = $cookie_location['lat'];

            $nearshops = M('Store_contact')->nearshops($long, $lat, 20);
        } else {
            //任意抽取10个
            $orderby = "collect desc";
            $nearshops = M('Store')->getlist(array('status' => 1), $orderby, 20);
        }
        $this->assign('nearshops', $nearshops);                        // 周边店铺

//		//热门品牌类别
//		$hot_brand['type'] = M('Store_brand_type')->getBrandTypeList();
//		//默认热门品牌
//		$hot_brand['brand'] = M('Store_brand')->getList(array('status' => 1), '', '10');
//		$test = array(0, 3);
//		$i = rand(0, 1);
//		$hot_brand['rand'] = $test[$i];

//		//评论晒单
//		$comment = M('Comment_attachment')->getSimpleList("12");
        //$this->assign('comment', $comment);                            //评论晒单
//		//优质分销商品
//		$excellfx = M('Product')->getExcellentFx(0, 8);
        //$this->assign('excellfx', $excellfx);                        //优质分销商品
//		//分销动态
//		$financiallist = M('Financial_record')->sns('', 4);
        //$this->assign('financiallist', $financiallist);                //分销动态

        // dump($financiallist);
        //$this->assign('common_data', $common_data);                    // 公共数据读取
        //$this->assign('hot_brand', $hot_brand);                        // 热门品牌

        $index_cache = $index_cache * 3600;

//		$is_have_activity = $this->config['is_have_activity'];
//		$this->assign('is_have_activity', $is_have_activity);
//		if ($is_have_activity) {
//			$kanjia = M('Activity')->getActivity('bargain', 8);
//			$miaosha = M('Activity')->getActivity('seckill', 8);
//			$zhongchou = M('Activity')->getActivity('crowdfunding', 8);
//			$duobao = M('Activity')->getActivity('unitary', 8);
//			$jiangjia = M('Activity')->getActivity('cutprice', 8);
//
//			$this->assign('kanjia', $kanjia);
//			$this->assign('miaosha', $miaosha);
//			$this->assign('zhongchou', $zhongchou);
//			$this->assign('duobao', $duobao);
//			$this->assign('jiangjia', $jiangjia);
//
//			$hot = M('Activity')->getHotActivity(5);
//			$this->assign('hot', $hot);
//
//			$rec = M('Activity')->getHotActivity(1, '', 1);
//			$this->assign('rec', $rec);
//		}

        if ($index_cache <= 0) {
            $this->display();
        } else {
            ob_start();
            $this->display();
            $content = ob_get_clean();

            S('index', $content, $index_cache);
            echo $content;
        }
    }

    public function user()
    {
        if ($this->user_session) {
            $data = array();
            $data['nickname'] = $this->user_session['nickname'];
            $data['cart_number'] = $this->cart_number + 0;

            exit(json_encode(array('status' => true, 'data' => $data)));
        }

        exit(json_encode(array('status' => false)));
    }


//获取热卖商品数据
    function ajax_hot_product()
    {
        $catid_string = $_GET['cateIdstr']; //所在栏目顶级id
        if (!$catid_string) echo json_encode(array('status' => false));

        //  二级分类 显示商品
        $catid_arr = explode(",", $catid_string);

        //  分类 显示商品
        $louceng = array();
        if (is_array($catid_arr)) {
            foreach ($catid_arr as $k1 => $v1) {
                // p.quantity > 0 and 'p.supplier_id'=0 and
                // and p.is_recommend = 1
                $where1 = "p.status = 1 and p.is_fx = 1 and (p.category_id ='" . $v1 .
                    "' or p.category_fid ='" . $v1 . "') ";
                $order_by_field1 = "p.is_hot desc, p.product_id DESC";
                $order_by_method1 = " ";

                $pro['product'] =
                    M('Product')->getSellingAndDistance($where1, $order_by_field1, $order_by_method1, 0, 10);

                $louceng[$v1] = $pro;
            }
        }
        echo json_encode($louceng);
        exit;
    }

    function ajax_self_product()
    {
        if (empty($this->store_session))
            echo json_encode(array('status' => false));

        $louceng = array();
        // p.quantity > 0 and 'p.supplier_id'=0 and
        $pros =
            M('Product')->getSellingAndDistance("p.status = 1 and p.is_fx != 1 and p.store_id = {$this->store_session['store_id']}",
                "sales", "desc", 0, 10);

        echo json_encode($pros);
        exit;
    }

//获取热门品牌数据
    function ajax_hot_brand()
    {
        $typeid_string = $_GET['typeIdstr']; //所在栏目顶级id
        if (!$typeid_string) echo json_encode(array('status' => false));
        //  二级分类 显示商品
        $typeid_arr = explode(",", $typeid_string);

        //  分类 显示品牌
        if (is_array($typeid_arr)) {
            foreach ($typeid_arr as $k1 => $v1) {
                $where1 = "status = 1 and  (type_id ='" . $v1 . "') ";

                $pro['brand'] = M('Store_brand')->getbrandList($where1, '', '', 0, 10);
                $test = array(0, 3);
                $i = rand(0, 1);
                $pro['rand'] = $test[$i];

                $louceng[$v1] = $pro;
            }
        }

        echo json_encode($louceng);
        exit;
    }


    // 根据百度获取位置
    function ajax_loaction()
    {
        $WebUserInfo = show_distance();
        if ($WebUserInfo['long']) {
            $xml_array =
                simplexml_load_file("http://api.map.baidu.com/geocoder?location={$WebUserInfo[lat]},{$WebUserInfo[long]}&output=xml&key=18bcdd84fae25699606ffad27f8da77b"); //将XML中的数据,读取到数组对象中

            foreach ($xml_array as $tmp) {
                $WebUserInfo['address'] = $tmp->formatted_address;
            }

            if (!empty($WebUserInfo['address'])) {
                echo json_encode(array('status' => true, 'msgAll' => $WebUserInfo['address'],
                    'msg' => msubstr($WebUserInfo['address'], 0, 8, 'utf-8')));
                exit;
            } else {
                echo json_encode(array('status' => false));
                exit;
            }
        } else {
            echo json_encode(array('status' => false));
            exit;
        }
    }
}