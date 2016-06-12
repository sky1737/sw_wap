<?php

/**
 * 基础类
 *
 */
class base_controller extends controller
{
	/*
	 * 当前登录用户
	 */
	public $user_session;
	/*
	 * 购物车
	 */
	public $cart_number;
	/*
	 * 当前店铺
	 */
	public $store_session;
	/*
	 * 当前推客
	 */
	public $twiker_session;

	public function __construct()
	{
		parent::__construct();

		// 用户登录
		if(isset($_SESSION['user'])) {
			$this->user_session = $_SESSION['user'];
			if($this->user_session) {
				$this->assign('user_session', $this->user_session);
			}
		}

		if(isset($_SESSION['store'])) {
			$this->store_session = $_SESSION['store'];
		}
		else {
			if($this->user_session['stores']) {
				$this->store_session = D('Store')->where(array('uid' => $this->user_session['uid'], 'status' => 1))
					->find();
				if($this->store_session) {
					$_SESSION['store'] = $this->store_session;
				}
			}
			else {
				if($this->user_session['parent_uid']) {
					$this->store_session =
						D('Store')->where(array('uid' => $this->user_session['parent_uid'], 'status' => 1))
							->find();
					if($this->store_session) {
						$_SESSION['store'] = $this->store_session;
					}
				}
			}
			if(empty($this->store_session)) {
				$_SESSION['store'] = $this->store_session = M('Store')->getOfficial();
			}
		}
		$this->assign('store_session', $this->store_session);

		/**
		 * 用F进行缓存时，后台更新此值，直接进行清空缓存
		 */
		//获取导航 对此值进行文件缓存
		$navList = F('pc_slider_pc_nav');
		if(empty($navList)) {
			$navList = M('Slider')->get_slider_by_key('pc_nav', 7);
			F('pc_slider_pc_nav', $navList);
		}
		$this->assign('navList', $navList); //导航栏目

		//获取热门搜索 对此值进行文件缓存
		$search_hot = F('pc_search_hot');
		if(empty($search_hot)) {
			$search_hot = D('Search_hot')->order("sort asc, id desc")->limit(7)->select();
			F('pc_search_hot', $search_hot);
		}
		$this->assign('search_hot', $search_hot);


		//公用头部右侧广告位 对此值进行文件缓存
		$public_top_ad = F('pc_top_right');
		if(empty($public_top_ad)) {
			$public_top_ad = M('Adver')->get_adver_by_key('pc_top_right', 1);
			F('pc_top_right', $public_top_ad);
		}
		$this->assign('pc_top_right', $public_top_ad[0]);

		// 购物内的数量
		// dump($this->user_session);
		$cart_number = 0;
		if(isset($this->user_session['uid'])) {
			$user_cart = D('User_cart')->where(array('uid' => $this->user_session['uid']))
				->field('sum(pro_num) as number')
				->find();
			$cart_number = $user_cart['number'];
		}

		$this->cart_number = $cart_number;
		$this->assign('cart_number', $cart_number);

		// 产品分类进行缓存
		$categoryList = F('pc_product_category_all');
		if(empty($categoryList)) {
			$categoryList = M('Product_category')->getAllCategory(15, true);
			F('pc_product_category_all', $categoryList);
		}
		$this->assign('categoryList', $categoryList);

		// 友情链接
		$link_list = F('pc_link_list');
		if(empty($link_list)) {
			$link_list = D('Flink')->where(array('status' => 1))->order('sort desc')->limit(10)->select();
			F('pc_link_list', $link_list);
		}
		$this->assign('link_list', $link_list);

//		//cookie 地理坐标
//		$WebUserInfo = show_distance();
//		if (empty($WebUserInfo['long']) || empty($WebUserInfo['lat'])) {
//// Cookies存在跨域问题，修改为SESSION
////			if (empty($_COOKIE['Location_qrcode']) || empty($_COOKIE['Location_qrcode']['location_id']) || empty($_COOKIE['Location_qrcode']['recognition_id']) || $_COOKIE['Location_qrcode']['qrcode']) {
////				$location_return = M('Recognition')->get_location_qrcode();
////				if ($location_return['error_code'] == false) {
////					setcookie('Location_qrcode[location_id]', $location_return['location_qrcode_id'], time() + 60 * 60 * 24);
////					setcookie('Location_qrcode[recognition_id]', $location_return['qrcode_id'], time() + 60 * 60 * 24);
////					setcookie('Location_qrcode[qrcode]', $location_return['qrcode'], time() + 60 * 60 * 24);
////				};
////				//dump($location_return);
////				$this->assign('location_qrcode', $location_return);
////			} else {
////				$this->assign('location_qrcode', $_COOKIE['Location_qrcode']);
////			}
//			$location_qrcode = $_SESSION['location_qrcode'];
//			if(!$location_qrcode){
//				$location_qrcode=M('Recognition')->get_location_qrcode();
//				if($location_qrcode['error_code']) {
//					//pigcms_tips('生成二维码失败！', 'none');
//				} else {
//					$_SESSION['location_qrcode'] = $location_qrcode;
//					$this->assign('location_qrcode',$location_qrcode);
//				}
//			}
//		} else {
//			$xml_array =
//				simplexml_load_file("http://api.map.baidu.com/geocoder?location={$WebUserInfo[lat]},{$WebUserInfo[long]}&output=xml&key=18bcdd84fae25699606ffad27f8da77b"); //将XML中的数据, 读取到数组对象中
//			foreach ($xml_array as $tmp) {
//				$WebUserInfo['address'] = $tmp->formatted_address;
//			}
//			$this->assign('WebUserInfo', $WebUserInfo);
//			$this->user_location = $WebUserInfo;
//		}
	}


	protected function nav_list()
	{
		$categoryList = M('Product_category')->getAllCategory(15);

		return $categoryList;
	}


}