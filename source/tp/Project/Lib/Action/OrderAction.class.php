<?php

/**
 * 订单
 * User: pigcms_21
 * Date: 2015/3/18
 * Time: 10:32
 */
class OrderAction extends BaseAction
{

	public function _initialize()
	{
		parent::_initialize();

		$this->check = array('1' => '未对账', '2' => '已对账');
	}


	//所有订单（不含临时订单）
	public function index()
	{
		$this->_orders();
	}

//	//到店自提订单（不含临时订单）
//	public function selffetch()
//	{
//		$this->_orders(array('StoreOrder.shipping_method' => 'selffetch'));
//	}
//
//	//货到付款订单（不含临时订单）
//	public function codpay()
//	{
//		$this->_orders(array('StoreOrder.payment_method' => 'codpay'));
//	}
//
//	//代付的订单（不含临时订单）
//	public function payagent()
//	{
//		$this->_orders(array('StoreOrder.type' => 1));
//	}

	private function _orders($where = array())
	{
		$order = D('OrderView');
		//搜索
		$condition = array();
		if (!empty($_GET['type']) && !empty($_GET['keyword'])) {

            $keyWord = $_GET['keyword'];

            switch ($_GET['type']){

                case 'order_no':
                    $condition['StoreOrder.order_no'] = $keyWord;
                    break;
                case 'name':
                    $condition['Store.name'] = array('like', "%$keyWord%");
                    break;

                case 'linkman':
                    $condition['Store.linkman'] = array('like', "%$keyWord%");
                    break;

                case 'trade_no':
                    $condition['StoreOrder.trade_no'] = $keyWord;
                    break;

                case 'third_id':
                    $condition['StoreOrder.third_id'] = $keyWord;
                    break;
            }

		}
		if (!empty($_GET['status'])) {
			$condition['StoreOrder.status'] = $_GET['status'];
		}
		if (!empty($_GET['is_check'])) {
			$condition['StoreOrder.is_check'] = $_GET['is_check'];
		}

		//自定义查询条件
		if (!empty($where)) {
			foreach ($where as $key => $value) {
				$condition[$key] = $value;
			}
		}
		if ($this->_get('start_time', 'trim') && $this->_get('end_time', 'trim')) {
			$condition['_string'] = "StoreOrder.add_time >= '" . strtotime($this->_get('start_time', 'trim')) .
				"' AND StoreOrder.add_time <= '" . strtotime($this->_get('end_time')) . "'";
		}
		else if ($this->_get('start_time', 'trim')) {
			$condition['StoreOrder.add_time'] = array('egt', strtotime($this->_get('start_time', 'trim')));
		}
		else if ($this->_get('end_time', 'trim')) {
			$condition['StoreOrder.add_time'] = array('elt', strtotime($this->_get('end_time', 'trim')));
		}

		//不含临时订单
		$order_count = $order->where($condition)->count();
		import('@.ORG.system_page');
		$page = new Page($order_count, 10);
		$orders =
			$order->where($condition)->order("StoreOrder.order_id DESC")
				->limit($page->firstRow . ',' . $page->listRows)
				->select();

		//订单状态
		$status = $order->status();
		//unset($status[0]);
		$this->assign('page', $page->show());
		$this->assign('orders', $orders);
		$this->assign('status', $status);

		$this->assign('check', $this->check);
		$this->display();
	}

	public function recharge()
	{
		$order = D('RechargeView');
		//搜索
		$condition = array();
		if (!empty($_GET['type']) && !empty($_GET['keyword'])) {

		    $keyWord = $_GET['keyword'];

		    switch ($_GET['type']){

                case 'order_no':
                    $condition['o.order_no'] = $keyWord;
                    break;
                case 'name':
                    $condition['u.nickname'] = array('like', "%$keyWord%");
                    break;

                case 'trade_no':
                    $condition['o.trade_no'] = $keyWord;
                    break;

                case 'third_id':
                    $condition['o.third_id'] = $keyWord;
                    break;
            }
		}

		if (!empty($_GET['status'])) {
			$condition['o.status'] = $_GET['status'];
		}
		if (!empty($_GET['is_check'])) {
			$condition['o.is_check'] = $_GET['is_check'];
		}

		//自定义查询条件
		if (!empty($where)) {
			foreach ($where as $key => $value) {
				$condition[$key] = $value;
			}
		}
		if ($this->_get('start_time', 'trim') && $this->_get('end_time', 'trim')) {
			$condition['_string'] = "o.add_time >= '" . strtotime($this->_get('start_time', 'trim')) .
				"' AND o.add_time <= '" . strtotime($this->_get('end_time')) . "'";
		}
		else if ($this->_get('start_time', 'trim')) {
			$condition['o.add_time'] = array('egt', strtotime($this->_get('start_time', 'trim')));
		}
		else if ($this->_get('end_time', 'trim')) {
			$condition['o.add_time'] = array('elt', strtotime($this->_get('end_time', 'trim')));
		}

		//不含临时订单
		$order_count = $order->where($condition)->count();
		import('@.ORG.system_page');
		$page = new Page($order_count, 10);
		$orders =
			$order->where($condition)->order("o.order_id DESC")
				->limit($page->firstRow . ',' . $page->listRows)
				->select();

		//订单状态
		$status = $order->status();
		//unset($status[0]);
		$this->assign('page', $page->show());
		$this->assign('orders', $orders);
		$this->assign('status', $status);

		$this->assign('check', $this->check);
		$this->display();
	}

	public function redpack()
	{
		$order = D('RedpackView');
		//搜索
		$condition = array();
		if (!empty($_GET['type']) && !empty($_GET['keyword'])) {
			if ($_GET['type'] == 'order_no') {
				$condition['o.order_no'] = $_GET['keyword'];
			}
			else if ($_GET['type'] == 'name') {
				$condition['u.nickname'] = array('like', '%' . $_GET['keyword'] . '%');
			}
		}
		if (!empty($_GET['status'])) {
			$condition['o.status'] = $_GET['status'];
		}
		if (!empty($_GET['is_check'])) {
			$condition['o.is_check'] = $_GET['is_check'];
		}

		//自定义查询条件
		if (!empty($where)) {
			foreach ($where as $key => $value) {
				$condition[$key] = $value;
			}
		}
		if ($this->_get('start_time', 'trim') && $this->_get('end_time', 'trim')) {
			$condition['_string'] = "o.add_time >= '" . strtotime($this->_get('start_time', 'trim')) .
				"' AND o.add_time <= '" . strtotime($this->_get('end_time')) . "'";
		}
		else if ($this->_get('start_time', 'trim')) {
			$condition['o.add_time'] = array('egt', strtotime($this->_get('start_time', 'trim')));
		}
		else if ($this->_get('end_time', 'trim')) {
			$condition['o.add_time'] = array('elt', strtotime($this->_get('end_time', 'trim')));
		}

		//不含临时订单
		$order_count = $order->where($condition)->count();
		import('@.ORG.system_page');
		$page = new Page($order_count, 10);
		$orders =
			$order->where($condition)->order("o.id DESC")
				->limit($page->firstRow . ',' . $page->listRows)
				->select();

		//订单状态
		$status = $order->status();
		//unset($status[0]);
		$this->assign('page', $page->show());
		$this->assign('orders', $orders);
		$this->assign('status', $status);

		$this->assign('check', $this->check);
		$this->display();
	}

	public function payfor()
	{
		$order = D('PayforView');
		//搜索
		$condition = array();
		if (!empty($_GET['type']) && !empty($_GET['keyword'])) {
			if ($_GET['type'] == 'order_no') {
				$condition['o.order_no'] = $_GET['keyword'];
			}
			else if ($_GET['type'] == 'name') {
				$condition['u.nickname'] = array('like', '%' . $_GET['keyword'] . '%');
			}
		}
		if (!empty($_GET['status'])) {
			$condition['o.status'] = $_GET['status'];
		}
		if (!empty($_GET['is_check'])) {
			$condition['o.is_check'] = $_GET['is_check'];
		}

		//自定义查询条件
		if (!empty($where)) {
			foreach ($where as $key => $value) {
				$condition[$key] = $value;
			}
		}
		if ($this->_get('start_time', 'trim') && $this->_get('end_time', 'trim')) {
			$condition['_string'] = "o.add_time >= '" . strtotime($this->_get('start_time', 'trim')) .
				"' AND o.add_time <= '" . strtotime($this->_get('end_time')) . "'";
		}
		else if ($this->_get('start_time', 'trim')) {
			$condition['o.add_time'] = array('egt', strtotime($this->_get('start_time', 'trim')));
		}
		else if ($this->_get('end_time', 'trim')) {
			$condition['o.add_time'] = array('elt', strtotime($this->_get('end_time', 'trim')));
		}

		//不含临时订单
		$order_count = $order->where($condition)->count();
		import('@.ORG.system_page');
		$page = new Page($order_count, 10);
		$orders =
			$order->where($condition)->order("o.order_id DESC")
				->limit($page->firstRow . ',' . $page->listRows)
				->select();

		//订单状态
		$status = $order->status();
		//unset($status[0]);
		$this->assign('page', $page->show());
		$this->assign('orders', $orders);
		$this->assign('status', $status);

		$this->assign('check', $this->check);
		$this->display();
	}

    public function payforlottery()
    {
        $lottery = D('LotteryView');
        //搜索
        $condition = array();
        if (!empty($_GET['type']) && !empty($_GET['keyword'])) {
            if ($_GET['type'] == 'order_no') {
                $condition['o.order_no'] = $_GET['keyword'];
            }
            else if ($_GET['type'] == 'name') {
                $condition['u.nickname'] = array('like', '%' . $_GET['keyword'] . '%');
            }
        }
        if (!empty($_GET['status'])) {
            $condition['o.status'] = $_GET['status'];
        }
        if (!empty($_GET['is_check'])) {
            $condition['o.is_check'] = $_GET['is_check'];
        }

        //自定义查询条件
        if (!empty($where)) {
            foreach ($where as $key => $value) {
                $condition[$key] = $value;
            }
        }
        if ($this->_get('start_time', 'trim') && $this->_get('end_time', 'trim')) {
            $condition['_string'] = "l.time >= '" . strtotime($this->_get('start_time', 'trim')) .
                "' AND l.time <= '" . strtotime($this->_get('end_time')) . "'";
        }
        else if ($this->_get('start_time', 'trim')) {
            $condition['l.time'] = array('egt', strtotime($this->_get('start_time', 'trim')));
        }
        else if ($this->_get('end_time', 'trim')) {
            $condition['l.time'] = array('elt', strtotime($this->_get('end_time', 'trim')));
        }

        //不含临时订单
        $order_count = $lottery->where($condition)->count();

        import('@.ORG.system_page');
        $page = new Page($order_count, 10);
        $orders =
            $lottery->where($condition)->order("o.order_id DESC")
                    ->limit($page->firstRow . ',' . $page->listRows)
                    ->select();
        //print_r($lottery->_sql());exit;
        $this->assign('page', $page->show());
        $this->assign('orders', $orders);

        $this->display();
    }

	public function detail()
	{
		$order = D('OrderView');
		$order_product = D('OrderProductView');
		$package = D('OrderPackage');
		$user = D('User');

		//订单状态
		$status = $order->status();
		//支付方式
		$payment_method = $order->getPaymentMethod();

		$order_id = $this->_get('id');
		$order = $order->where(array('StoreOrder.order_id' => $order_id))->find();

		$products = $order_product->getProducts($order_id);
		$comment_count = 0;
		$product_count = 0;
		foreach ($products as &$product) {
			if (!empty($product['comment'])) {
				$comment_count++;
			}
			$product_count++;

			$product['image'] = getAttachmentUrl($product['image']);
		}

//		if(empty($order['uid'])) {
//			$is_fans = false;
//		}
//		else {
//			$is_fans = $user->isWeixinFans($order['uid']);
//		}

		if (empty($order['address'])) {
			$status[0] = '未填收货地址';
		}
		else {
			$status[1] = '已填收货地址';
		}
		if (!empty($order['user_order_id'])) {
			$user_order_id = $order['user_order_id'];
		}
		else {
			$user_order_id = $order['order_id'];
		}
		//订单包裹
		$where = array();
		$where['user_order_id'] = $user_order_id;
        $packages = $package->getPackages($where);
        /*
		$packages = array();
		foreach ($tmp_packages as $package) {
			$package_products = explode(',', $package['products']);
            var_dump($tmp_packages,$package_products);exit;
			if (array_intersect($package_products, $tmp_packages)) {
				$packages[] = $package;
			}
		}
        var_dump($packages);exit;
        */

        /**
         * @var $express mysql
         */
        $express = M('Express');
        //快递公司
        $express = $express->field('code,name')->select();

		//$this->assign('is_fans', $is_fans);
		$this->assign('order', $order);
		$this->assign('products', $products);
		$this->assign('rows', $comment_count + $product_count);
		$this->assign('comment_count', $comment_count);
		$this->assign('status', $status);
		$this->assign('payment_method', $payment_method);
		$this->assign('packages', $packages);
		$this->assign('express', $express);
		$this->display();
	}


    public function changeexpressno()
    {
        $package = M('OrderPackage');
        if (IS_POST) {
            $package_id = $this->_post('package_id', 'trim,intval');
            $no = $this->_post('no', 'trim');
            $package->where(array('package_id' => $package_id))->save(array('express_no' => $no));
            //print_r($package->getLastSql());
        }
    }

    public function changeexpresscom()
    {
        $package = M('OrderPackage');
        if (IS_POST) {
            $package_id = $this->_post('package_id', 'trim,intval');
            $name = $this->_post('name', 'trim');
            $code = $this->_post('code', 'trim');
            $package->where(array('package_id' => $package_id))->save(array('express_company' => $name,'express_code' => $code));
            //print_r($package->getLastSql());
        }
    }

	public function check()
	{
		$order = D('OrderView');
		//搜索
		$condition = array();
		if (!empty($_GET['type']) && !empty($_GET['keyword'])) {
			if ($_GET['type'] == 'order_no') {
				$condition['StoreOrder.order_no'] = $_GET['keyword'];
			}
			else if ($_GET['type'] == 'name') {
				$condition['Store.name'] = array('like', '%' . $_GET['keyword'] . '%');
			}
			else if ($_GET['type'] == 'linkman') {
				$condition['Store.linkman'] = array('like', '%' . $_GET['keyword'] . '%');
			}
		}
		if (!empty($_GET['status'])) {
			$condition['StoreOrder.status'] = $_GET['status'];
		}
		else {
			$condition['StoreOrder.status'] = array('gt', 0);
		}
		if (!empty($_GET['is_check'])) {
			$condition['StoreOrder.is_check'] = $_GET['is_check'];
		}
		else {
			$condition['StoreOrder.is_check'] = array('gt', 0);
		}

		//自定义查询条件
		if (!empty($where)) {
			foreach ($where as $key => $value) {
				$condition[$key] = $value;
			}
		}
		if ($this->_get('start_time', 'trim') && $this->_get('end_time', 'trim')) {
			$condition['_string'] = "StoreOrder.add_time >= '" . strtotime($this->_get('start_time', 'trim')) .
				"' AND StoreOrder.add_time <= '" . strtotime($this->_get('end_time')) . "'";
		}
		else if ($this->_get('start_time', 'trim')) {
			$condition['StoreOrder.add_time'] = array('egt', strtotime($this->_get('start_time', 'trim')));
		}
		else if ($this->_get('end_time', 'trim')) {
			$condition['StoreOrder.add_time'] = array('elt', strtotime($this->_get('end_time', 'trim')));
		}
		//不含临时订单
		$order_count = $order->where($condition)->count();
		import('@.ORG.system_page');
		$page = new Page($order_count, 10);
		$orders =
			$order->where($condition)->order("StoreOrder.order_id DESC")->limit($page->firstRow . ',' . $page->listRows)
				->select();

		//订单状态
		$status = $order->status();
		unset($status[0]);
		$this->assign('page', $page->show());
		$this->assign('orders', $orders);
		$this->assign('status', $status);

		$this->assign('check', $this->check);
		$this->display();
	}

	//对账日志
	public function checklog()
	{
		$order_check_log = D('OrderCheckLog');
		$condition = array();

		if (!empty($_GET['type']) && !empty($_GET['keyword'])) {
			if ($_GET['type'] == 'realname') {
				$condition['a.realname'] = array('like', '%' . $_GET['keyword'] . '%');
			}
			else if ($_GET['type'] == 'account') {
				$condition['a.account'] = array('like', '%' . $_GET['keyword'] . '%');
			}
		}


		$order_check_count = $order_check_log->alias("logs")->join(C('DB_PREFIX') . "admin a ON a.id = logs.admin_uid")
			->where($condition)->count('logs.id');
		import('@.ORG.system_page');
		$page = new Page($order_check_count, 20);
		//$OrderCheckList = $order_check_log->where($condition)->limit($page->firstRow, $page->listRows)->order("id desc,timestamp desc")->select();
		$OrderCheckList = $order_check_log->alias("logs")->join(C('DB_PREFIX') . "admin a ON a.id = logs.admin_uid")
			->field("logs.*,a.account,a.realname,a.last_ip")->where($condition)->limit($page->firstRow, $page->listRows)
			->order("logs.id desc,logs.timestamp desc")->select();
		//echo $order_check_log->getLastSql();


		$this->assign('page', $page->show());
		$this->assign('array', $OrderCheckList);
		$this->display();

	}

	//详细对账抽成比例
	public function alert_check()
	{
		$order = D('OrderView');
		$order_product = D('OrderProductView');
		$package = D('OrderPackage');
		$user = D('User');

		//订单状态
		$status = $order->status();
		//支付方式
		$payment_method = $order->getPaymentMethod();

		$order_id = $this->_get('id');
		$order = $order->where(array('StoreOrder.order_id' => $order_id))->find();

		$products = $order_product->getProducts($order_id);
		$comment_count = 0;
		$product_count = 0;
		foreach ($products as &$product) {
			if (!empty($product['comment'])) {
				$comment_count++;
			}
			$product_count++;

			$product['image'] = getAttachmentUrl($product['image']);
		}

		if (empty($order['uid'])) {
			$is_fans = false;
		}
		else {
			$is_fans = $user->isWeixinFans($order['uid']);
		}

		if (empty($order['address'])) {
			$status[0] = '未填收货地址';
		}
		else {
			$status[1] = '已填收货地址';
		}
		if (!empty($order['user_order_id'])) {
			$user_order_id = $order['user_order_id'];
		}
		else {
			$user_order_id = $order['order_id'];
		}
		//订单包裹
		$where = array();
		$where['user_order_id'] = $user_order_id;
		$tmp_packages = $package->getPackages($where);
		$packages = array();
		foreach ($tmp_packages as $package) {
			$package_products = explode(',', $package['products']);
			if (array_intersect($package_products, $tmp_products)) {
				$packages[] = $package;
			}
		}
		$this->assign('is_fans', $is_fans);
		$this->assign('order', $order);
		$this->assign('products', $products);
		$this->assign('rows', $comment_count + $product_count);
		$this->assign('comment_count', $comment_count);
		$this->assign('status', $status);
		$this->assign('payment_method', $payment_method);
		$this->assign('packages', $packages);
		$this->display();

	}

	//更改：出账状态
	public function check_status()
	{
		$order_id = $this->_post('order_id');
		$order_no = $this->_post('order_no');
		$is_check = $this->_post('is_check');
		$store_id = $this->_post('store_id');
		$order = D('Order');

		if (empty($order_id) || empty($order_no) || empty($is_check)) {
			exit(json_encode(array('error' => 1, 'message' => '缺少必要参数')));
		}

		$where = array(
			'order_id' => $order_id,
			'order_no' => $order_no,
		);
		$order->where($where)->save(array('is_check' => $is_check));

		$log_where = $where;
		$log_where['store_id'] = $store_id;


		$this->set_check_log($log_where);
		exit(json_encode(array('error' => 0, 'message' => '已出账')));


	}

	/*description:记录出账日志
	 *
	 * @arr : 必须包含： order_id,order_no
	 */
	public function set_check_log($arr)
	{
		$check_log = D('OrderCheckLog');

		$thisUser = $this->system_session;

		if (empty($arr['order_id']) || empty($arr['order_no']) || empty($thisUser['id'])) {

			return false;
		}

		$description = "";

		$data = array(
			'timestamp' => time(),
			'admin_uid' => $thisUser['id'],
			'order_id' => $arr['order_id'],
			'order_no' => $arr['order_no'],
			'ip' => ip2long($_SERVER['REMOTE_ADDR']),
			'description' => $description
		);

		if ($check_log->add($data)) {
			return true;
		}
		else {
			return false;
		}

	}

	//电子币收支明细
	public function income()
	{
		$type = array(
			-3 => '提现',
			-2 => '活动使用',
			-1 => '购物使用',
			1  => '购物立返',
			2  => '一级分销返佣',
			3  => '二级分销返佣',
			4  => '一级代理利润',
			5  => '二级代理利润',
			6  => '物流费用',
			7  => '推荐奖励',
			8  => '活动奖励',
			9  => '管理员充值',
			10 => '退货返还',
			11 => '成本结算',
			12 => '积分兑换',
			99 => '充值'
		);
		$act = $_GET['act'];
		$condition = " A.status=1 ";
		$csvTitle = "全部";
		$keyWord = $_GET['keyword'];
		if (!empty($_GET['type']) && !empty($_GET['keyword'])) {
			switch ($_GET['type']){
				case 'order_no':
					$csvTitle = '订单号';
					$condition .= " AND  A.order_no=".$keyWord;
					break;
				case 'name':
					$csvTitle = '用户名';
					$condition .= " AND  C.nickname like'%".$keyWord."%'";
					break;
				case 'trade_no':
					$csvTitle = '交易号';
					$condition .= " AND  B.trade_no=".$keyWord;
					break;
				case 'third_id':
					$csvTitle = '微信支付单号';
					$condition .= " AND  B.third_id=".$keyWord;
					break;
			}
		}
		if ($this->_get('start_time', 'trim') && $this->_get('end_time', 'trim')) {
			$condition .= " AND add_time >= '" . strtotime($this->_get('start_time', 'trim')) .
				"' AND add_time <= '" . strtotime($this->_get('end_time')) . "'";
		}
		else if ($this->_get('start_time', 'trim')) {
			$condition .= " AND  add_time >='". strtotime($this->_get('start_time', 'trim'))."'";
		}
		else if ($this->_get('end_time', 'trim')) {
			$condition .= " AND  add_time <='". strtotime($this->_get('end_time', 'trim'))."'";
		}

		$db_prefix = C('DB_PREFIX');

		$sql = $db_prefix."user_income as A";
		$sql .=" LEFT JOIN ".$db_prefix."order  as B on A.order_no = B.order_no ";
		$sql .=" LEFT JOIN ".$db_prefix."user  as C on A.uid = C.uid " ;
		$sql .= " WHERE " .$condition;

		$count_sql = "select count(*) as count from  ".$sql;
		$count =  M()->query($count_sql);
		$count_user = $count[0]['count'];

		$list_sql = " SELECT A.uid,A.order_no,trade_no,B.third_id,A.income,A.point,A.type,A.add_time,remarks,c.nickname FROM " .$sql;
		$list = M()->query($list_sql);

		if($act =='export'){
			$expText = "筛选条件\n";
			$expText .= $csvTitle.":".$keyWord.",下单时间：".$this->_get('start_time', 'trim').",".$this->_get('end_time', 'trim')."\n\n";
			$expText .= "UID,用户名,时间,订单号,交易号,微信支付单号,类型,金额(元),积分(分),状态,备注\n";
			foreach ($list as $l) {
				$expTextArray = array();
				$expTextArray[] = $l['uid'];
				$expTextArray[] = $l['nickname'];
				$expTextArray[] = date('Y-m-d H:i:s',$l['add_time']);
				$expTextArray[] = $l['order_no'];
				$expTextArray[] = $l['trade_no'];
				$expTextArray[] = $l['third_id'];
				$expTextArray[] = $type[$l['type'] ];
				$expTextArray[] = ($l['income'] > 0  ? '+' : '-') . number_format(abs($l['income']), 2, '.', '');
				$expTextArray[] = ($l['point'] > 0 ? '+' : '-') .abs($l['point']);
				$expTextArray[] = '成功';
				$expTextArray[] = $l['remarks'];
				foreach ($expTextArray as $t) {
					$expText .= '"' . $t . '",';
				}
				$expText .= "\n";
			}
			$expText = mb_convert_encoding($expText, 'CP936', 'UTF8');
			return $this->exportCsv('电子币收支明细' . date('Y-m-d H:I') . '.csv', $expText); //导出
		}

		import('@.ORG.system_page');

		$p = new Page($count_user, 20);

		$this->assign('list', $list);
		$pager = $p->show();
		$this->assign('pager', $pager);
		$this->assign('types',$type );

		$this->display();
	}

	//红包开店收支明细
	public function payfor_log()
	{
		$db_prefix = C('DB_PREFIX');
		$act = $_GET['act'];

		$condition = " 1=1 ";
		$csvTitle = "全部";
		if (!empty($_GET['type']) && !empty($_GET['keyword'])) {

			$keyWord = $_GET['keyword'];

			switch ($_GET['type']){
				case 'order_no':
					$csvTitle = '订单号';
					$condition .= " AND  order_no=".$keyWord;
					break;
				case 'name':
					$csvTitle = '用户名';
					$condition .= " AND  nickname like'%".$keyWord."%'";
					break;
				case 'trade_no':
					$csvTitle = '交易号';
					$condition .= " AND  trade_no=".$keyWord;
					break;
				case 'third_id':
					$csvTitle = '微信支付单号';
					$condition .= " AND  third_id=".$keyWord;
					break;
			}
		}

		if ($this->_get('start_time', 'trim') && $this->_get('end_time', 'trim')) {
			$condition .= " AND add_time >= '" . strtotime($this->_get('start_time', 'trim')) .
				"' AND add_time <= '" . strtotime($this->_get('end_time')) . "'";
		}
		else if ($this->_get('start_time', 'trim')) {
			$condition .= " AND  add_time >='". strtotime($this->_get('start_time', 'trim'))."'";
		}
		else if ($this->_get('end_time', 'trim')) {
			$condition .= " AND  add_time <='". strtotime($this->_get('end_time', 'trim'))."'";
		}

		$sql = " SELECT order_no,trade_no,third_id,uid,pay_type,pay_money,add_time,1 as type,remarks FROM " .$db_prefix."payfor_order WHERE status=2 ";

		$sql.= " union all ";

		$sql.= " SELECT order_no, trade_no, trade_no as third_id,uid,'weixin' as pay_type,amount as pay_money,add_time,0 as type,'' as remarks FROM " .$db_prefix."payfor_redpack WHERE status=1";


		$count_sql = "select count(*) as count from ( ".$sql." ) as A  left join  ".$db_prefix."user as B on A.uid=B.uid  where ".$condition;

		$count =  M()->query($count_sql);

		$count = $count[0]['count'];

		import('@.ORG.system_page');

		$p = new Page($count, 20);

		if($act == 'export' ){
			$p->listRows = '1000';
		}
		$list_sql = "select A.*,B.nickname from ( ".$sql." ) as A left join  ".$db_prefix."user as B on A.uid=B.uid WHERE  ". $condition ."  limit ".$p->listRows ." offset $p->firstRow";

		$list =M()->query($list_sql);


		if($act =='export'){
			$expText = "筛选条件\n";
			$expText .= $csvTitle.":".$keyWord.",下单时间：".$this->_get('start_time', 'trim').",".$this->_get('end_time', 'trim')."\n\n";
			$expText .= "UID,用户名,时间,订单号,交易号,微信支付单号,类型,金额(元),积分(分),状态,备注\n";
			foreach ($list as $l) {
				$expTextArray = array();
				$expTextArray[] = $l['uid'];
				$expTextArray[] = $l['nickname'];
				$expTextArray[] = date('Y-m-d H:i:s',$l['add_time']);
				$expTextArray[] = $l['order_no'];
				$expTextArray[] = $l['trade_no'];
				$expTextArray[] = $l['third_id'];
				$expTextArray[] = $l['type'] ? '开店' : '红包';
				$expTextArray[] = ($l['type']  ? '+' : '-') . number_format(abs($l['pay_money']), 2, '.', '');
				$expTextArray[] = ($l['type']  ? '+' : '-') .'0';
				$expTextArray[] = '已支付';
				$expTextArray[] = $l['remarks'];

				foreach ($expTextArray as $t) {
					$expText .= '"' . $t . '",';
				}
				$expText .= "\n";
			}

			$expText = mb_convert_encoding($expText, 'CP936', 'UTF8');

			return $this->exportCsv('红包开店收支明细' . date('Y-m-d H:I') . '.csv', $expText); //导出

		}

		$pager = $p->show();
		$this->assign('pager', $pager);

		$this->assign('list', $list);

		$this->display();

	}

	//购物收支明细
	public function buy_log()
	{
		$type = array(
			2  => '未发货',
			3  => '已发货',
			4  => '已完成',
			5  => '已取消',
		);
		$db_prefix = C('DB_PREFIX');
		$act = $_GET['act'];
		$keyWord = $_GET['keyword'];
		$condition = ' A.status in(2,3,4) or (A.status = 5 and refund_time > 0) ';
		$csvTitle = '';
		if (!empty($_GET['type']) && !empty($_GET['keyword'])) {
			switch ($_GET['type']){
				case 'order_no':
					$csvTitle = '订单号';
					$condition .= " AND  order_no=".$keyWord;
					break;
				case 'name':
					$csvTitle = '用户名';
					$condition .= " AND  B.nickname like'%".$keyWord."%'";
					break;
				case 'trade_no':
					$csvTitle = '交易号';
					$condition .= " AND  trade_no=".$keyWord;
					break;
				case 'third_id':
					$csvTitle = '微信支付单号';
					$condition .= " AND  A.third_id=".$keyWord;
					break;
			}
		}

		if ($this->_get('start_time', 'trim') && $this->_get('end_time', 'trim')) {
			$condition .= " AND add_time >= '" . strtotime($this->_get('start_time', 'trim')) .
				"' AND add_time <= '" . strtotime($this->_get('end_time')) . "'";
		}
		else if ($this->_get('start_time', 'trim')) {
			$condition .= " AND  add_time >='". strtotime($this->_get('start_time', 'trim'))."'";
		}
		else if ($this->_get('end_time', 'trim')) {
			$condition .= " AND  add_time <='". strtotime($this->_get('end_time', 'trim'))."'";
		}


		$sql = $db_prefix."order as A";
		$sql .=" LEFT JOIN ".$db_prefix."user  as B on A.uid = B.uid " ;
		$sql .= " WHERE " .$condition;

		$count_sql = "select count(*) as count from  ".$sql;
		$count =  M()->query($count_sql);
		$count_user = $count[0]['count'];

		$list_sql = " SELECT A.uid,order_no,trade_no,A.third_id,A.balance,A.point,A.status,add_time,nickname FROM " .$sql;
		$list = M()->query($list_sql);
		
		if($act =='export'){
			$expText = "筛选条件\n";
			$expText .= $csvTitle.":".$keyWord.",下单时间：".$this->_get('start_time', 'trim').",".$this->_get('end_time', 'trim')."\n\n";
			$expText .= "UID,用户名,时间,订单号,交易号,微信支付单号,类型,金额(元),积分(分),状态\n";
			foreach ($list as $l) {
				$expTextArray = array();
				$expTextArray[] = $l['uid'];
				$expTextArray[] = $l['nickname'];
				$expTextArray[] = date('Y-m-d H:i:s',$l['add_time']);
				$expTextArray[] = $l['order_no'];
				$expTextArray[] = $l['trade_no'];
				$expTextArray[] = $l['third_id'];
				$expTextArray[] = '购物';
				$expTextArray[] = ('-') . number_format(abs($l['balance']), 2, '.', '');
				$expTextArray[] = ( '-') .abs($l['point']);
				$expTextArray[] = $type[$l['status']];
				foreach ($expTextArray as $t) {
					$expText .= '"' . $t . '",';
				}
				$expText .= "\n";
			}
			$expText = mb_convert_encoding($expText, 'CP936', 'UTF8');
			return $this->exportCsv('购物收支明细' . date('Y-m-d H:I') . '.csv', $expText); //导出
		}
		import('@.ORG.system_page');
		$p = new Page($count_user, 20);
		$pager = $p->show();
		$this->assign('pager', $pager);
		$this->assign('list', $list);
		$this->assign('types',$type );
		$this->display();

	}


	/**
	 * 导出 excel
	 *
	 * @param $filename
	 * @param $data
	 *
	 * @return bool
	 */
	private  function exportCsv($filename, $data)
	{

		header("Content-type:text/csv");
		header("Content-Disposition:attachment; filename=" . $filename);
		header('Cache-Control:must-revalidate, post-check=0, pre-check=0');
		header('Expires:0');
		header('Pragma:public');

		echo $data;
		exit;
	}

} 