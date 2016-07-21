<?php

/**
 * 账号
 * User: pigcms_21
 * Date: 2015/3/3
 * Time: 14:41
 */
class account_controller extends base_controller
{
	public function __construct()
	{
		parent::__construct();
//		$user = M('User')->getUserById($this->user_session['uid']);
//		$user['last_ip'] = long2ip($user['last_ip']);
//		$this->assign('user', $user);
	}

	public function ajax_rand_product()
	{
		$where1 = "p.status = 1 and p.is_fx = 1";
		$order_by_field1 = "ROUND( ( 0.5 - RAND( ) ) *2 *5 ) ";
		$order_by_method1 = "";

		$product = M('Product')->getSellingAndDistance($where1, $order_by_field1, $order_by_method1, 0, 12);

		echo json_encode($product);
		exit;
	}

	public function index()
	{
		$this->display();
	}

    //我的供货商
    public function supplier()
    {
        $this->display();
    }

	public function info()
	{
		if(IS_POST) {
			$data['nickname'] = I('post.name');
			$data['phone'] = I('post.phone');
			if(I('post.avatar')) {
				$data['avatar'] = getAttachmentUrl(I('post.avatar'));
			}

			$data['intro'] = I('post.intro');
			if(empty($data['nickname'])) {
				json_return(1, '请填写昵称');
			}
			if(!preg_match('/^1[34578]{1}\d{9}$/', $data['phone'])) {
				json_return(1, '手机号码不正确！');
			}

			$result = M('User')->save_user(array('uid' => $this->user_session['uid']), $data);

			// 更新session数据
			$_SESSION['user']['nickname'] = $data['nickname'];
			$_SESSION['user']['phone'] = $data['phone'];
			$_SESSION['user']['intro'] = $data['intro'];
			if(!empty($data['avatar'])) {
				$_SESSION['user']['avatar'] = $data['avatar'];
			}
			json_return(0, '保存成功！');
		}
		$this->display();
	}

	public function _info_content()
	{

	}

	public function _index_content()
	{
		$where = array();
		$where['uid'] = $this->user_session['uid'];
		$where['status'] = array('>', 0);

		$this->assign('total',
			M('Order')->getOrderTotal(array('uid' => $this->user_session['uid'], 'status' => array('>', 0))));
	}

	public function check()
	{
		$key = I('post.key');
		$value = I('post.value');
		if(empty($name)) return false;

		$unique = M('User')->getUnique($key, $value);
		exit($unique);
	}

	public function load()
	{
		$action = strtolower(trim($_POST['page']));

        //var_dump($action);exit;

		if(empty($action)) pigcms_tips('非法访问！', 'none');
		switch ($action) {
			case 'income_content':
				$this->_income_content();
				break;
			case 'income_withdraw_content':
				$this->_income_withdraw_content();
				break;
			case 'income_trade_content':
				$this->_income_trade_content();
				break;
			case 'income_details_content':
				$this->_income_details_content();
				break;
			case 'income_setting_content':
				$this->_income_setting_content();
				break;
			case 'income_edit_content':
				$this->_income_edit_content();
				break;
			case 'income_apply_content':
				$this->_income_apply_content();
				break;

			case 'coupon_content':
				$this->_coupon_content();
				break;
			case 'order_content':
				$this->_order_content();
				break;
			case 'detail_content':
				$this->_detail_content();
				break;
			case 'collect_store_content':
				$this->_collect_store_content();
				break;
			case 'collect_goods_content':
				$this->_collect_goods_content();
				break;
			case 'attention_store_content':
				$this->_attention_store_content();

			case 'address_content':
				$this->_address_content();
				break;
			case 'info_content':
				$this->_info_content();
				break;
			case 'index_content':
				$this->_index_content();
				break;
//			case 'personal_content':
//				$this->_personal_content();
//				break;
//			case 'company_content':
//				$this->_company_content();
//				break;
			case 'password_content':
				$this->_password_content();
				break;

            case 'supplier_content':
                $this->_supplier_content();
                break;

            case 'supplier_order_content':

                $status = isset($_POST['status']) ? trim($_POST['status']) : ''; //订单状态
                $shipping_method = isset($_POST['shipping_method']) ? strtolower(trim($_POST['shipping_method'])) : ''; //运送方式 快递发货 上门自提
                $payment_method = isset($_POST['payment_method']) ? strtolower(trim($_POST['payment_method'])) : ''; //支付方式
                $type = isset($_POST['type']) ? $_POST['type'] : '*'; //订单类型 普通订单 代付订单
                $orderbyfield = isset($_POST['orderbyfield']) ? trim($_POST['orderbyfield']) : '';
                $orderbymethod = isset($_POST['orderbymethod']) ? trim($_POST['orderbymethod']) : '';
                $page = isset($_POST['p']) ? intval(trim($_POST['p'])) : 1;
                $order_no = isset($_POST['order_no']) ? trim($_POST['order_no']) : '';
                $trade_no = isset($_POST['trade_no']) ? trim($_POST['trade_no']) : '';
                $user = isset($_POST['user']) ? trim($_POST['user']) : ''; //收货人
                $tel = isset($_POST['tel']) ? trim($_POST['tel']) : ''; //收货人手机
                $time_type = isset($_POST['time_type']) ? trim($_POST['time_type']) : '';
                $start_time = isset($_POST['start_time']) ? trim($_POST['start_time']) : '';
                $stop_time = isset($_POST['stop_time']) ? trim($_POST['stop_time']) : '';
                $weixin_user = isset($_POST['weixin_user']) ? trim($_POST['weixin_user']) : '';

                $data = array(
                    'status' => $status,
                    'orderbyfield' => $orderbyfield,
                    'orderbymethod' => $orderbymethod,
                    'page' => $page,
                    'order_no' => $order_no,
                    'trade_no' => $trade_no,
                    'user' => $user,
                    'tel' => $tel,
                    'time_type' => $time_type,
                    'start_time' => $start_time,
                    'stop_time' => $stop_time,
                    'weixin_user' => $weixin_user,
                    'type' => $type,
                    'payment_method' => $payment_method,
                    'shipping_method' => $shipping_method
                );

                $this->_supplier_order_content($data);
                break;

            case 'supplier_goods_content':
                $this->_supplier_goods_content();
                break;

			default:
				break;
		}
		$this->display($action);
	}

    //我的商品
    public function supplier_goods()
    {
        if(IS_POST) {
            $products = isset($_POST['products']) ? trim($_POST['products']) : '';
            if(!empty($products)) {
                M('Product')
                    ->edit(array('product_id' => array('in', explode(',', $products)),
                                 'store_id'   => $this->store_session['store_id']),
                        array('is_fx' => -1));

                json_return(0, '操作成功');
            }
            else {
                json_return(1001, '操作失败');
            }
        }
        $this->display();
    }

    private function _supplier_goods_content()
    {
        $product = M('Product');
//		$product_group = M('Product_group');
//		$product_to_group = M('Product_to_group');

        $order_by_field = isset($_POST['orderbyfield']) ? $_POST['orderbyfield'] : '';
        $order_by_method = isset($_POST['orderbymethod']) ? $_POST['orderbymethod'] : '';
        $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';

        $where = array();
        $where['store_id'] = $this->store_session['store_id'];
        if($keyword) {
            $where['name'] = array('like', '%' . $keyword . '%');
        }
        $where['buy_way'] = 1; //站内商品
        $where['is_fx'] = array('in', array(-1, 0));
        //$where['supplier_id'] = 0;
        $product_total = $product->getSellingTotal($where);
        import('source.class.user_page');
        $page = new Page($product_total, 15);
        $products = $product->getSelling($where, $order_by_field, $order_by_method, $page->firstRow, $page->listRows);

        $this->assign('page', $page->show());
        $this->assign('products', $products);
    }

    private function _supplier_content()
    {
        $store_id = addslashes($this->store_session['store_id']);

        $product = D('Product');
        $supplier_product_count = $product->where("store_id = $store_id and status=1 and is_fx=1")->count('1');

        $order = D('Order');

        $total_sales_amount = $order->field('sum(`sub_total`-`profit`) as total')->where("store_id = $store_id")->find();

        $this->assign('supplier_product_count', $supplier_product_count);
        $this->assign('total_sales_amount', $total_sales_amount ? $total_sales_amount['total'] : 0);
    }


    public function offshelves(){

        $pid =  $_POST['pid'];
        $product = D('Product')->where("product_id = $pid")->data(array('status'=>0))->save();

        json_return(0, '下架成功！');
    }


    public function supplier_order()
    {
        $this->display();
    }

    private function _supplier_order_content($data)
    {
        $order = M('Order');
        $order_product = M('Order_product');
        $user = M('User');

        $where = array();
        $where['agent_id'] = $this->store_session['store_id'];
        if($data['status'] != '*') {
            $where['status'] = intval($data['status']);
        }
        else { // 所有订单（不包含临时订单）
            $where['status'] = array('>', 0);
        }
        if($data['order_no']) {
            $where['order_no'] = $data['order_no'];
        }
        if(is_numeric($data['type'])) {
            $where['type'] = $data['type'];
        }
        if(!empty($data['user'])) {
            $where['address_user'] = $data['user'];
        }
        if(!empty($data['tel'])) {
            $where['address_tel'] = $data['tel'];
        }
        if(!empty($data['payment_method'])) {
            $where['payment_method'] = $data['payment_method'];
        }
        if(!empty($data['shipping_method'])) {
            $where['shipping_method'] = $data['shipping_method'];
        }
        $field = '';
        if(!empty($data['time_type'])) {
            $field = $data['time_type'];
        }
        if(!empty($data['start_time']) && !empty($data['stop_time']) && !empty($field)) {
            $where['_string'] = "`" . $field . "` >= " . strtotime($data['start_time']) . " AND `" . $field . "` <= " .
                strtotime($data['stop_time']);
        }
        else if(!empty($data['start_time']) && !empty($field)) {
            $where[$field] = array('>=', strtotime($data['start_time']));
        }
        else if(!empty($data['stop_time']) && !empty($field)) {
            $where[$field] = array('<=', strtotime($data['stop_time']));
        }
        //排序
        if(!empty($data['orderbyfield']) && !empty($data['orderbymethod'])) {
            $orderby = "`{$data['orderbyfield']}` " . $data['orderbymethod'];
        }
        else {
            $orderby = '`order_id` DESC';
        }
        $order_total = $order->getOrderTotal($where);
        import('source.class.user_page');
        $page = new Page($order_total, 15);
        $tmp_orders = $order->getOrders($where, $orderby, $page->firstRow, $page->listRows);

        $orders = array();
        foreach ($tmp_orders as $tmp_order) {
            $products = $order_product->getProducts($tmp_order['order_id']);
            $tmp_order['products'] = $products;
            if(empty($tmp_order['uid'])) {
                $tmp_order['is_fans'] = false;
                $tmp_order['buyer'] = '';
            }
            else {
                //$tmp_order['is_fans'] = $user->isWeixinFans($tmp_order['uid']);
                $tmp_order['is_fans'] = true;
                $user_info = $user->checkUser(array('uid' => $tmp_order['uid']));
                $tmp_order['buyer'] = $user_info['nickname'];
            }
            $is_supplier = false;
            if(!empty($tmp_order['suppliers'])) { //订单供货商
                $suppliers = explode(',', $tmp_order['suppliers']);
                if(in_array($this->store_session['store_id'], $suppliers)) {
                    $is_supplier = true;
                }
            }
            $tmp_order['is_supplier'] = $is_supplier;
            $has_my_product = false;
            foreach ($products as &$product) {
                $product['image'] = getAttachmentUrl($product['image']);
                if(empty($product['is_fx'])) {
                    $has_my_product = true;
                }
            }

            $tmp_order['products'] = $products;
            $tmp_order['has_my_product'] = $has_my_product;
            if(!empty($tmp_order['user_order_id'])) {
                $order_info =
                    D('Order')->field('store_id')->where(array('order_id' => $tmp_order['user_order_id']))->find();
                $seller = D('Store')->field('name')->where(array('store_id' => $order_info['store_id']))->find();
                $tmp_order['seller'] = $seller['name'];
            }
            $orders[] = $tmp_order;
        }

        //订单状态
        $order_status = $order->status();
        $this->assign('order_status', $order_status);

        //支付方式
        $payment_method = $order->getPaymentMethod();
        $this->assign('payment_method', $payment_method);

        $this->assign('status', $data['status']);
        $this->assign('orders', $orders);
        $this->assign('page', $page->show());
    }


//	public function income()
//	{
//		$this->display();
//	}
//
//	function _income_content()
//	{
//		$income_db = M('User_income');
//		$order_db = D('Order');
//
//		$where = array();
//		$where['uid'] = $this->user_session['uid'];
//		$where['status'] = 1;
//
//		$total = $income_db->getTotal($where);
//		import('source.class.user_page');
//		$page = new Page($total, 15);
//		$list = $income_db->getRecords($where, '`income_id` DESC', $page->firstRow, $page->listRows);
//
//		$incomes = array();
//		foreach ($list as $tmp_order) {
//			if($tmp_order['order_id']) {
//				$order = $order_db->where(array('order_id' => $tmp_order['order_id']))->find();
//				$tmp_order['order'] = $order;
//			}
//			$incomes[] = $tmp_order;
//		}
//
//		//订单状态
//		$types = $income_db->typeTxt(0);
//		$this->assign('types', $types);
//
//		$this->assign('incomes', $incomes);
//		$this->assign('pages', $page->show());
//	}

	/**
	 * 收入提现
	 */
	public function income()
	{
		$this->display();
	}

	private function _income_content()
	{
		$store = M('Store');
		//$financial_record = M('Financial_record');
		$income_db = M('User_income');

		//七天收入
		$start_day = date("Y-m-d", strtotime('-7 day'));
		$end_day = date('Y-m-d', strtotime('-1 day'));
		$start_time = strtotime($start_day . ' 00:00:00');
		$end_time = strtotime($end_day . ' 23:59:59');
		$where = array();
		//$where['store_id'] = $this->store_session['store_id'];
		$where['uid'] = $this->store_session['uid'];
		//$where['type'] = array('in', array(1, 5));
		$where['_string'] = " add_time >= '" . $start_time . "' AND add_time <= '" . $end_time . "'";
		$day_7_income = $income_db->sumProfit($where);
		$day_7_point = $income_db->sumPoint($where);

		$user_card = D('User_card')->where(array('uid' => $this->user_session['uid']))->find();
		if($user_card) {
			$user_card['bank'] = M('Bank')->getBank($user_card['bank_id']);
		}
		$this->assign('card', $user_card);
//		if(!empty($store['bank_id']) && !empty($store['bank_card'])) {
//			$this->assign('bind_bank_card', true);
//		}
//		else {
//			$this->assign('bind_bank_card', false);
//		}

		$this->assign('day_7_income', number_format($day_7_income, 2, '.', ''));
		$this->assign('day_7_point', $day_7_point);
	}

	// 提现记录
	private function _income_withdraw_content()
	{
		$cash_db = M('User_cash');

		$where = array();
		$where['c.uid'] = $this->user_session['uid'];
		if(!empty($_POST['status'])) {
			$where['sw.status'] = $_POST['status'];
		}
		if(!empty($_POST['start_time']) && !empty($_POST['stop_time'])) {
			$where['_string'] = "c.add_time >= '" . strtotime(trim($_POST['start_time'])) . "' AND c.add_time <= '" .
				strtotime(trim($_POST['stop_time'])) . "'";
		}
		else if(!empty($_POST['start_time'])) {
			$where['_string'] = "c.add_time >= '" . strtotime(trim($_POST['start_time'])) . "'";
		}
		else if(!empty($_POST['stop_time'])) {
			$where['_string'] = "c.add_time <= '" . strtotime(trim($_POST['stop_time'])) . "'";
		}
		$withdrawal_count = $cash_db->getCount($where);
		import('source.class.user_page');
		$page = new Page($withdrawal_count, 20);
		$withdrawals = $cash_db->getRecords($where, $page->firstRow, $page->listRows);

		$status = $cash_db->getStatus();

		$this->assign('withdrawals', $withdrawals);
		$this->assign('page', $page->show());
		$this->assign('status', $status);
	}

//	//交易记录
//	private function _income_trade_content()
//	{
//		$status = array(
//			1 => '进行中',
//			2 => '退款',
//			3 => '成功',
//			4 => '失败'
//		);
//
//		$status_text = array(
//			1 => '进行中',
//			2 => '退款',
//			3 => '交易完成',
//			4 => '交易失败'
//		);
//		$order = M('Order');
//		//$financial_record = M('Financial_record');
//
//		$where = array();
//		//$where['store_id'] = $this->store_session['store_id'];
//		$where['uid'] = $this->user_session['uid'];
//		if(!empty($_POST['order_no'])) {
//			$where['order_no'] = trim($_POST['order_no']);
//		}
//		if(!empty($_POST['status'])) {
//			$where['status'] = trim($_POST['status']);
//		}
//		else if(!empty($_POST['param'])) {
//			$where['status'] = trim($_POST['param']);
//		}
//		if(!empty($_POST['start_time']) && !empty($_POST['stop_time'])) {
//			$where['_string'] = "add_time >= '" . strtotime(trim($_POST['start_time'])) . "' AND add_time <= '" .
//				strtotime(trim($_POST['stop_time'])) . "'";
//		}
//		else if(!empty($_POST['start_time'])) {
//			$where['add_time'] = array('<=', strtotime(trim($_POST['start_time'])));
//		}
//		else if(!empty($_POST['stop_time'])) {
//			$where['add_time'] = array('>=', strtotime(trim($_POST['stop_time'])));
//		}
//		$record_total = $order->getTotal($where);
//		import('source.class.user_page');
//		$page = new Page($record_total, 15);
//		$records = $order->getRecords($where, 'order_id desc', $page->firstRow, $page->listRows);
//
//		$this->assign('records', $records);
//		$this->assign('page', $page->show());
//		$this->assign('status', $status);
//		$this->assign('status_text', $status_text);
//	}

	// 添加银行卡信息
	public function settingwithdrawal()
	{
		if(IS_POST) {
			$card_id = isset($_POST['card_id']) ? intval(trim($_POST['card_id'])) : 0;
			$bank_id = isset($_POST['bank_id']) ? intval(trim($_POST['bank_id'])) : 0;
			$bank_name = isset($_POST['bank_name']) ? trim($_POST['bank_name']) : '';
			$card_no = isset($_POST['card_no']) ? trim($_POST['card_no']) : '';
			$card_user = isset($_POST['card_user']) ? trim($_POST['card_user']) : '';
			if(!$bank_id) {
				json_return(1, '请选择发卡银行！');
			}
			if(empty($bank_name)) {
				json_return(1, '请填写开户行！');
			}
			if(!preg_match("/^\d{12,20}$/", $card_no)) {
				json_return(1, '请填写银行卡号！');
			}
			if(!$card_user) {
				json_encode('请填写持卡人！');
			}

			$db = M('User_card');
			if(!$card_id) {
				$card = $db->getCard($this->user_session['uid']);
				if(empty($card)) {
					$db->add(array('uid'       => $this->user_session['uid'],
					               'bank_id'   => $bank_id,
					               'bank_name' => $bank_name,
					               'card_no'   => $card_no,
					               'card_user' => $card_user,
					               'add_time'  => time()));
					json_return(0, '添加银行卡成功！');
				}
				$card_id = $card['card_id'];
			}

			$db->save(
				array('uid'     => $this->user_session['uid'],
				      'card_id' => $card_id),
				array('bank_id'   => $bank_id,
				      'bank_name' => $bank_name,
				      'card_no'   => $card_no,
				      'card_user' => card_user)
			);
			json_return(0, '保存成功！');
		}
	}

	// 收支明细
	private function _income_details_content()
	{
		$income_db = M('User_income');
		$order_db = D('Order');

		$where = array();
		$where['uid'] = $this->user_session['uid'];
		$where['status'] = 1;

		$total = $income_db->getTotal($where);
		import('source.class.user_page');
		$page = new Page($total, 15);
		$list = $income_db->getRecords($where, '`income_id` DESC', $page->firstRow, $page->listRows);

		$incomes = array();
		foreach ($list as $tmp_order) {
			if($tmp_order['order_no']) {
				$order = $order_db->where(array('order_no' => $tmp_order['order_no']))->find();
				$tmp_order['order'] = $order;
			}
			$incomes[] = $tmp_order;
		}

		//订单状态
		$types = $income_db->typeTxt(0);
		$this->assign('types', $types);

		$this->assign('incomes', $incomes);
		$this->assign('pages', $page->show());
//		$order = M('Order');
//		$financial_record = M('Financial_record');
//
//		$where = array();
//		$where['store_id'] = $this->store_session['store_id'];
//		if(!empty($_POST['order_no'])) {
//			$where['order_no'] = trim($_POST['order_no']);
//		}
//		if(!empty($_POST['type'])) {
//			$where['type'] = trim($_POST['type']);
//		}
//		if(!empty($_POST['start_time']) && !empty($_POST['stop_time'])) {
//			$where['_string'] = "add_time >= '" . strtotime(trim($_POST['start_time'])) . "' AND add_time <= '" .
//				strtotime(trim($_POST['stop_time'])) . "'";
//		}
//		else if(!empty($_POST['start_time'])) {
//			$where['add_time'] = array('<=', strtotime(trim($_POST['start_time'])));
//		}
//		else if(!empty($_POST['stop_time'])) {
//			$where['add_time'] = array('>=', strtotime(trim($_POST['stop_time'])));
//		}
//		$record_total = $financial_record->getRecordCount($where);
//		import('source.class.user_page');
//		$page = new Page($record_total, 20);
//		$records = $financial_record->getRecords($where, $page->firstRow, $page->listRows);
//
//		//订单类型
//		$record_types = $financial_record->getRecordTypes();
//		//支付方式
//		$payment_methods = $order->getPaymentMethod();
//
//		$this->assign('records', $records);
//		$this->assign('page', $page->show());
//		$this->assign('record_types', $record_types);
//		$this->assign('payment_methods', $payment_methods);
	}

	//设置提现账号 界面
	private function _income_setting_content()
	{
		$bank = M('Bank');
		$banks = $bank->getEnableBanks();

		$user_card = D('User_card')->where(array('uid' => $this->user_session['uid']))->find();
//		if($user_card) {
//			$user_card['bank'] = $bank->getBank($user_card['bank_id']);
//		}
		$this->assign('card', $user_card);
		$this->assign('store', $this->store_session);
		$this->assign('banks', $banks);
	}

//	// 修改提现账号
//	private function _income_edit_content()
//	{
//		$bank = M('Bank');
//		$store = M('Store');
//
//		$banks = $bank->getEnableBanks();
//		$store = $store->getStore($this->store_session['store_id']);
//
//		$this->assign('param', !empty($_POST['param']) ? $_POST['param'] : '');
//		$this->assign('store', $this->store_session);
//		$this->assign('banks', $banks);
//		$this->assign('withdrawal_type', $store['withdrawal_type']);
//		$this->assign('bank_id', $store['bank_id']);
//		$this->assign('opening_bank', $store['opening_bank']);
//		$this->assign('bank_card', $store['bank_card']);
//		$this->assign('bank_card_user', $store['bank_card_user']);
//	}

	// 申请提现 界面
	private function _income_apply_content()
	{
		$bank = M('Bank');
		//$store = M('Store');

		//$store = $store->getStore($this->store_session['store_id']);
		$user_card = D('User_card')->where(array('uid' => $this->user_session['uid']))->find();
		if($user_card) {
			$user_card['bank'] = $bank->getBank($user_card['bank_id']);
		}
		$this->assign('card', $user_card);

		$balance = D('User')->where(array('uid' => $this->user_session['uid'], 'status' => 1))->getField('balance');
		$this->assign('balance', number_format($balance, 2, '.', ''));
	}

	//添加提现申请
	public function applywithdrawal()
	{
		if(IS_POST) {
			$data = array();
			$data['trade_no'] = date('YmdHis', $_SERVER['REQUEST_TIME']) . mt_rand(100000, 999999);
			$data['uid'] = $this->user_session['uid'];
			$data['card_id'] = isset($_POST['card_id']) ? intval(trim($_POST['card_id'])) : 0;
			if(!$data['card_id']) {
				json_return(1000, '请绑定银行卡信息！');
			}
			$data['type'] = 0;
			$data['amount'] = isset($_POST['amount']) ? floatval(trim($_POST['amount'])) : 0;
			if($data['amount'] < option('config.withdrawal_min') * 1.00) {
				json_return(1000, '最低提现金额为 ' . round(option('config.withdrawal_min') * 1.00, 2) . ' 元！');
			}
			$data['status'] = 1;
			$data['add_time'] = time();

			$wap_user = D('User')->where(array('uid' => $this->user_session['uid'], 'status' => 1))->find();
			$user_cash = M('User_cash');
			if($wap_user['balance'] >= $data['amount']) {
				if($user_cash->add($data)) {
					M('User')->applywithdrawal($data['uid'], $data['amount']);
					//$store->drpProfitCash($data['store_id'], $data['amount']);
					json_return(0, $data['amount']);
				}
				else {
					json_return(1001, '写入日志失败，提现不成功！');
				}
			}
			else {
				json_return(1002, '余额不足，提现失败！');
			}
//			$store = M('Store');
//			$store_withdrawal = M('Store_withdrawal');
//
//			$data = array();
//			$data['trade_no'] = date('YmdHis', $_SERVER['REQUEST_TIME']) . mt_rand(100000, 999999);
//			$data['uid'] = $this->user_session['uid'];
//			$data['store_id'] = $this->store_session['store_id'];
//			$data['bank_id'] = isset($_POST['bank_id']) ? intval(trim($_POST['bank_id'])) : 0;
//			$data['opening_bank'] = isset($_POST['opening_bank']) ? trim($_POST['opening_bank']) : '';
//			$data['bank_card'] = isset($_POST['bank_card']) ? trim($_POST['bank_card']) : '';
//			$data['bank_card_user'] = isset($_POST['bank_card_user']) ? trim($_POST['bank_card_user']) : '';
//			$data['withdrawal_type'] = isset($_POST['withdrawal_type']) ? intval(trim($_POST['withdrawal_type'])) : 0;
//			$data['amount'] = isset($_POST['amount']) ? floatval(trim($_POST['amount'])) : 0;
//			$data['status'] = 1;
//			$data['add_time'] = time();
//
//			if($store_withdrawal->add($data)) {
//				$store->applywithdrawal($data['store_id'], $data['amount']);
//				$store_info = $store->getStore($data['store_id']);
//				if(!empty($store_info['drp_profit']) &&
//					$store_info['drp_profit'] > $store_info['drp_profit_withdrawal']
//				) {
//					if(($store_info['drp_profit_withdrawal'] + $data['amount']) <= $store_info['drp_profit']) {
//						D('Store')->where(array('store_id' => $data['store_id']))
//							->setInc('drp_profit_withdrawal', $data['amount']);
//					}
//					else {
//						D('Store')->where(array('store_id' => $data['store_id']))
//							->data(array('drp_profit_withdrawal' => $store_info['drp_profit']))->save();
//					}
//				}
//				json_return(0, '提现申请成功');
//			}
//			else {
//				json_return(1001, '提现申请失败');
//			}
		}
	}

//	//删除提现账号
//	public function delwithdrawal()
//	{
//		$store = M('Store');
//
//		if($store->delwithdrawal($this->store_session['store_id'])) {
//			json_return(0, '删除成功');
//		}
//		else {
//			json_return(1001, '删除失败');
//		}
//	}

	public function coupon()
	{
		$this->display();
	}

	function _coupon_content()
	{

	}

	public function order()
	{
		$this->display();
	}

	function _order_content()
	{
		$order = M('Order');
		$order_product = M('Order_product');
		$user = M('User');

		$where = array();
		$where['uid'] = $this->user_session['uid'];
		if($_POST['status'] != '*') {
			$where['status'] = intval($_POST['status']);
		}
		else { //所有订单（不包含临时订单）
			$where['status'] = array('>', 0);
		}

		$order_total = $order->getOrderTotal($where);
		import('source.class.user_page');
		$page = new Page($order_total, 15);
		$tmp_orders = $order->getOrders($where, '`order_id` DESC', $page->firstRow, $page->listRows);

		$orders = array();
		foreach ($tmp_orders as $tmp_order) {
			$products = $order_product->getProducts($tmp_order['order_id']);
			$tmp_order['products'] = $products;
//			if(empty($tmp_order['uid'])) {
//				$tmp_order['is_fans'] = false;
//				$tmp_order['buyer'] = '';
//			}
//			else {
//				//$tmp_order['is_fans'] = $user->isWeixinFans($tmp_order['uid']);
//				$tmp_order['is_fans'] = true;
//				$user_info = $user->checkUser(array('uid' => $tmp_order['uid']));
//				$tmp_order['buyer'] = $user_info['nickname'];
//			}
//			$is_supplier = false;
//			if(!empty($tmp_order['suppliers'])) { //订单供货商
//				$suppliers = explode(',', $tmp_order['suppliers']);
//				if(in_array($this->store_session['store_id'], $suppliers)) {
//					$is_supplier = true;
//				}
//			}
//			$tmp_order['is_supplier'] = $is_supplier;
//			$has_my_product = false;
//			foreach ($products as &$product) {
//				$product['image'] = getAttachmentUrl($product['image']);
//				if(empty($product['is_fx'])) {
//					$has_my_product = true;
//				}
//			}

			$tmp_order['products'] = $products;
//			$tmp_order['has_my_product'] = $has_my_product;
//			if(!empty($tmp_order['user_order_id'])) {
//				$order_info =
//					D('Order')->field('store_id')->where(array('order_id' => $tmp_order['user_order_id']))->find();
//				$seller = D('Store')->field('name')->where(array('store_id' => $order_info['store_id']))->find();
//				$tmp_order['seller'] = $seller['name'];
//			}
			$orders[] = $tmp_order;
		}

		//订单状态
		$order_status = $order->status();
		$this->assign('order_status', $order_status);

		//支付方式
		$payment_method = $order->getPaymentMethod();
		$this->assign('payment_method', $payment_method);

		$this->assign('status', $_POST['status']);
		$this->assign('orders', $orders);
		$this->assign('page', $page->show());
	}

	function order_cancel()
	{
		$order_id = $_GET['order_id'];
		if(empty($order_id)) {
			echo json_encode(array('status' => false, 'msg' => '参数错误'));
			exit;
		}

		// 实例化order_model
		$order_model = M('Order');
		$order = $order_model->find($order_id);

		// 权限判断是否可以取消订单
		if($order['uid'] != $this->user_session['uid']) {
			echo json_encode(array('status' => false, 'msg' => '您无权操作'));
			exit;
		}

		if($order['status'] > 1) {
			echo json_encode(array('status' => false, 'msg' => '此订单不能取消'));
			exit;
		}

		// 更改订单状态
		$order_model->cancelOrder($order);

		echo json_encode(array('status' => true, 'msg' => '订单取消完成', 'data' => array('nexturl' => 'refresh')));
		exit;
	}

	function order_confirm()
	{
		$order_id = $_GET['order_id'];
		if(empty($order_id)) {
			echo json_encode(array('status' => false, 'msg' => '参数错误！'));
			exit;
		}

		// 实例化order_model
		$order_model = M('Order');
		$order = $order_model->find($order_id);

		// 权限判断是否可以确认订单
		if($order['uid'] != $this->user_session['uid']) {
			echo json_encode(array('status' => false, 'msg' => '您无权操作！'));
			exit;
		}

		if($order['status'] < 2) {
			echo json_encode(array('status' => false, 'msg' => '此订单不能确认收货！'));
			exit;
		}

		// 确认收货，完成订单
		$order_model->confirmOrder($order);

		echo json_encode(array('status' => true, 'msg' => '订单已完成！', 'data' => array('nexturl' => 'refresh')));
		exit;
	}

	function order_refund()
	{
		$order_id = $_GET['order_id'];
		if(empty($order_id)) {
			echo json_encode(array('status' => false, 'msg' => '参数错误！'));
			exit;
		}


		echo json_encode(array('status' => true, 'msg' => '确认退款中！', 'data' => array('nexturl' => 'refresh')));
		exit;
	}


//	//订单详细
//	public function detail() {
//		$this->display();
//	}
//
//	//订单详细页面
//	private function _detail_content() {
//		$order = M('Order');
//		$order_product = M('Order_product');
//		$user = M('User');
//		$package = M('Order_package');
//
//		$order_id = isset($_POST['order_id']) ? intval(trim($_POST['order_id'])) : 0;
//		$order = $order->getOrder($this->store_session['store_id'], $order_id);
//		$products = $order_product->getProducts($order_id);
//		if (empty($order['uid'])) {
//			$order['is_fans'] = false;
//			$is_fans = false;
//			$order['buyer'] = '';
//		} else {
//			//$tmp_order['is_fans'] = $user->isWeixinFans($tmp_order['uid']);
//			$order['is_fans'] = true;
//			$is_fans = true;
//			$user_info = $user->checkUser(array('uid' => $order['uid']));
//			$order['buyer'] = $user_info['nickname'];
//		}
//
////		$is_supplier = false;
////		if (!empty($order['suppliers'])) { //订单供货商
////			$suppliers = explode(',', $order['suppliers']);
////			if (in_array($this->store_session['store_id'], $suppliers)) {
////				$is_supplier = true;
////			}
////		}
////		$order['is_supplier'] = $is_supplier;
//
//		$comment_count = 0;
//		$product_count = 0;
//		$tmp_products = array();
//		foreach ($products as $product) {
//			if (!empty($product['comment'])) {
//				$comment_count++;
//			}
//			$product_count++;
//
//			$tmp_products[] = $product['original_product_id'];
//		}
//
//		$status = M('Order')->status();
//		$payment_method = M('Order')->getPaymentMethod();
//
//		if (empty($order['address'])) {
//			$status[0] = '未填收货地址';
//		} else {
//			$status[1] = '已填收货地址';
//		}
//		if (!empty($order['user_order_id'])) {
//			$user_order_id = $order['user_order_id'];
//		} else {
//			$user_order_id = $order['order_id'];
//		}
//		$where = array();
//		$where['user_order_id'] = $user_order_id;
//		$tmp_packages = $package->getPackages($where);
//		$packages = array();
//		foreach ($tmp_packages as $package) {
//			$package_products = explode(',', $package['products']);
//			if (array_intersect($package_products, $tmp_products)) {
//				$packages[] = $package;
//			}
//		}
//
//		// 查看满减送
//		$order_ward_list = M('Order_reward')->getByOrderId($order['order_id']);
//		// 使用优惠券
//		$order_coupon = M('Order_coupon')->getByOrderId($order['order_id']);
//		$this->assign('is_fans', $is_fans);
//		$this->assign('order', $order);
//		$this->assign('products', $products);
//		$this->assign('rows', $comment_count + $product_count);
//		$this->assign('comment_count', $comment_count);
//		$this->assign('status', $status);
//		$this->assign('payment_method', $payment_method);
//		$this->assign('packages', $packages);
//		$this->assign('order_ward_list', $order_ward_list);
//		$this->assign('order_coupon', $order_coupon);
//	}
//
//	public function detail_json() {
//		$order = M('Order');
//		$order_product = M('Order_product');
//
//		$order_id = isset($_POST['order_id']) ? intval(trim($_POST['order_id'])) : 0;
//		$order = $order->getOrder($this->store_session['store_id'], $order_id);
//		$order['address'] = !empty($order['address']) ? unserialize($order['address']) : '';
//		$tmp_products = $order_product->getProducts($order_id);
//		$products = array();
//		foreach ($tmp_products as $product) {
//			$products[] = array(
//				'product_id' => $product['product_id'],
//				'name'	   => $product['name'],
//				'price'	  => $product['pro_price'],
//				'quantity'   => $product['pro_num'],
//				'skus'	   => !empty($product['sku_data']) ? unserialize($product['sku_data']) : '',
//				'url'		=> $this->config['wap_site_url'].'/good.php?id='.$product['product_id'],
//			);
//		}
//		$order['products'] = $products;
//
//		// 查看满减送
//		$order_ward_list = M('Order_reward')->getByOrderId($order['order_id']);
//		// 使用优惠券
//		$order_coupon = M('Order_coupon')->getByOrderId($order['order_id']);
//		$money = 0;
//		foreach ($order_ward_list as $order_ward) {
//			$money += $order_ward['content']['cash'];
//		}
//
//		if (!empty($order_coupon)) {
//			$money += $order_coupon['money'];
//		}
//
//		$order['reward_money'] = round($money, 2);
//
//		echo json_encode($order);
//		exit;
//	}

	public function collect_store()
	{
		$this->display();
	}

	function _collect_store_content()
	{
		// 基本参数设定
		$page = max(1, $_GET['page']);
		$limit = 20;

		$count = D('')->table(array('User_collect' => 'uc', 'Store' => 's'))
			->where("`uc`.`type` = '2' AND `uc`.`user_id` = '" . $this->user_session['uid'] .
				"' AND `uc`.`dataid` = `s`.`store_id`")->count("`uc`.`collect_id`");

		$store_list = array();
		$pages = '';
		if($count > 0) {
			$total_pages = ceil($count / $limit);
			$page = min($page, $total_pages);
			$offset = ($page - 1) * $limit;


			$store_list = D('')->table(array('User_collect' => 'uc', 'Store' => 's'))
				->where("`uc`.`type` = '2' AND `uc`.`user_id` = '" . $this->user_session['uid'] .
					"' AND `uc`.`dataid` = `s`.`store_id`")
				->order("`uc`.`collect_id` DESC")->limit($offset . ',' . $limit)
				->select();
			foreach ($store_list as &$store) {
				if(empty($store['logo'])) {
					$store['logo'] = getAttachmentUrl('images/default_shop_2.jpg', false);
				}
				else {
					$store['logo'] = getAttachmentUrl($store['logo']);
				}
			}
			// 分页
			import('source.class.user_page');

			$user_page = new Page($count, $limit, $page);
			$pages = $user_page->show();
		}

		$this->assign('store_list', $store_list);
		$this->assign('pages', $pages);
	}

	public function collect_goods()
	{
		$this->display();
	}

	function _collect_goods_content()
	{
		// 基本参数设定
		$page = max(1, $_GET['page']);
		$limit = 20;

		//$count = D('User_collect')->where(array('type' => 1, 'user_id' => $this->user_session['uid']))->count('collect_id');
		$count = D('')->table(array('User_collect' => 'uc', 'Product' => 'p'))
			->where("`uc`.`type` = '1' AND `uc`.`user_id` = '" . $this->user_session['uid'] .
				"' AND `uc`.`dataid` = `p`.`product_id`")->count("`uc`.`collect_id`");

		$product_list = array();
		$pages = '';
		if($count > 0) {
			$total_pages = ceil($count / $limit);
			$page = min($page, $total_pages);
			$offset = ($page - 1) * $limit;


			$product_list = D('')->table(array('User_collect' => 'uc', 'Product' => 'p'))
				->where("`uc`.`type` = '1' AND `uc`.`user_id` = '" . $this->user_session['uid'] .
					"' AND `uc`.`dataid` = `p`.`product_id`")->order("`uc`.`collect_id` DESC")->limit($offset . ',' .
					$limit)->select();

			foreach ($product_list as &$product) {
				$product['image'] = getAttachmentUrl($product['image']);
			}
			// 分页
			import('source.class.user_page');

			$user_page = new Page($count, $limit, $page);
			$pages = $user_page->show();
		}


		$this->assign('products', $product_list);
		$this->assign('pages', $pages);
	}

	public function attention_store()
	{
		$this->display();
	}

	function _attention_store_content()
	{
		// 基本参数设定
		$page = max(1, $_GET['page']);
		$limit = 10;

		$count = D('')->table(array('User_attention' => 'ua', 'Store' => 's'))
			->where("`ua`.`data_type` = '2' AND `ua`.`user_id` = '" . $this->user_session['uid'] .
				"' AND `ua`.`data_id` = `s`.`store_id`")->count("`ua`.`id`");

		$store_list = array();
		$pages = '';


		if($count > 0) {
			$total_pages = ceil($count / $limit);
			$page = min($page, $total_pages);
			$offset = ($page - 1) * $limit;

			$product_model = M('Product');
			$store_list = D('')->table(array('User_attention' => 'ua', 'Store' => 's'))
				->where("`ua`.`data_type` = '2' AND `ua`.`user_id` = '" . $this->user_session['uid'] .
					"' AND `ua`.`data_id` = `s`.`store_id`")->order("`ua`.`id` DESC")->limit($offset . ',' . $limit)
				->select();


			foreach ($store_list as &$store) {
				if(empty($store['logo'])) {
					$store['logo'] = getAttachmentUrl('images/default_shop_2.jpg', false);
				}
				else {
					$store['logo'] = getAttachmentUrl($store['logo']);
				}
				//每个店铺获取 10个热销商品 10个新品
				// 店铺热销个产品
				$store['hot_list'] =
					$product_model->getSelling(array('store_id' => $store['store_id'], 'status' => 1), 'sales', 'desc',
						0, 9);
				$store['hot_list_count'] = count($store['hot_list']);

				/*新品*/
				$store['news_list'] =
					$product_model->getSelling(array('store_id' => $store['store_id'], 'status' => 1), '', '', 0, 9);
				$store['news_list_count'] = count($store['news_list']);

				// 评论满意，一般，不满意数量，以及满意百分比
				$where = array();
				$where['type'] = 'STORE';
				$where['relation_id'] = $store['store_id'];
				$comment_type_count = M('Comment')->getCountList($where);
				$satisfaction_pre = '100%';
				if($comment_type_count['total'] > 0) {
					$satisfaction_pre = round($comment_type_count['t3'] / $comment_type_count['total'] * 100) . '%';
				}
				$store['satisfaction_pre'] = $satisfaction_pre;

				$store['imUrl'] = getImUrl($_SESSION['user']['uid'], $store['store_id']);
			}


			// 分页
			import('source.class.user_page');

			$user_page = new Page($count, $limit, $page);
			$pages = $user_page->show();
		}


		$this->assign('store_list', $store_list);
		$this->assign('pages', $pages);
	}

	public function address()
	{
		$db = M('User_address');

		// 添加新收货地址
		if(IS_POST) {
			$name = $_POST['name'];
			$tel = $_POST['tel'];
			$province = $_POST['province'];
			$city = $_POST['city'];
			$area = $_POST['area'];
			$address = $_POST['address'];
			$zipcode = $_POST['zipcode'];
			$default = $_POST['default'] + 0;

			if(empty($name)) {
				json_response(false, '收货人没有填写！');
			}

			if(empty($tel) || !preg_match("/1[34578]{1}\d{9}$/", $tel)) {
				json_response(false, '手机号码格式不正确！');
			}

			if(empty($province)) {
				json_response(false, '省份没有选择！');
			}

			if(empty($city)) {
				json_response(false, '城市没有选择！');
			}

			if(empty($area)) {
				json_response(false, '地区没有选择！');
			}

			if(empty($address)) {
				json_response(false, '详细地址没有填写！');
			}

			if(strlen($zipcode) != 6 && !is_numeric($zipcode)) {
				json_response(false, '邮编填写错误！');
			}

			// 更新数据库操作，当有address_id时做更新操作，没有时做添加操作
			$data = array();
			$data['uid'] = $this->user_session['uid'];
			$data['name'] = $name;
			$data['tel'] = $tel;
			$data['province'] = $province;
			$data['city'] = $city;
			$data['area'] = $area;
			$data['address'] = $address;
			$data['zipcode'] = $zipcode;
			$data['default'] = $default;

			$msg = '添加成功';

			$address_id = $_POST['address_id'];
			if(!empty($address_id)) {
				$msg = '修改成功';
				// 更改记录条件
				$condition = array();
				$condition['uid'] = $this->user_session['uid'];
				$condition['address_id'] = $address_id;

				$db->save_address($condition, $data);
			}
			else {
				$data['add_time'] = time();
				$address_id = $db->add($data);
			}


			// 设置默认收货地址
			if($default == 1) {
				$db->canelDefaultAaddress($this->user_session['uid'], $address_id);
			}

			json_response(true, $msg, 'refresh');
		}

		$id = $_GET['id'];
		if(is_numeric($id) && $id > 0) {
			$item = $db->getAdressById(null, $this->user_session['uid'], $id);
			$this->assign('item', $item);
		}

		$this->display();
	}

	function _address_content()
	{
		$db = M('User_Address');
		$list = $db->getMyAddress($this->user_session['uid']);
		$this->assign('list', $list);
	}

	/**
	 * 设置默认收货地址
	 */
	function address_default()
	{
		$id = $_GET['id'];
		if(!is_numeric($id))
			json_response(false, '参数错误！');

		M('User_address')->canelDefaultAaddress($this->user_session['uid'], $id);
		json_response(true, '设置完成', 'refresh');
	}

	/**
	 * 删除收货地址
	 */
	function address_delete()
	{
		$id = $_GET['id'];
		if(!is_numeric($id))
			json_response(false, '参数错误！');

		M('User_address')->deleteAddress(array('address_id' => $id, 'uid' => $this->user_session['uid']));

		json_response(true, '删除完成', 'refresh');
	}

//	//个人资料
//	public function personal()
//	{
//		if(IS_POST) {
//			$data = array();
//			$data['nickname'] = isset($_POST['nickname']) ? trim($_POST['nickname']) : '';
//			$data['avatar'] = isset($_POST['avatar']) ? trim($_POST['avatar']) : '';
//			$data['intro'] = isset($_POST['intro']) ? trim($_POST['intro']) : '';
//
//			M('User')->save_user(array('uid' => $this->user_session['uid']), $data);
//			$_SESSION['user']['nickname'] = $data['nickname'];
//			json_return(0, '设置成功');
//		}
//		if(empty($this->store_session)) {
//			$condition_store['uid'] = $this->user_session['uid'];
//			$store = D('Store')->where($condition_store)->order('`store_id` DESC')->find();
//			if($store) {
//				if(empty($store['logo'])) $store['logo'] = 'default_shop_2.jpg';
//				$_SESSION['store'] = $store;
//			}
//			else {
//				pigcms_tips('您需要先创建一个店铺', url('store:select'));
//			}
//		}
//		$this->display();
//	}

//	//个人资料详细
//	private function _personal_content()
//	{
//		$user = M('User')->getUserById($this->user_session['uid']);
//		$this->assign('user', $user);
//	}

//	//公司信息
//	public function company()
//	{
//		if(IS_POST) {
//			$company = M('Company');
//			$data = array();
//			$data['name'] = isset($_POST['name']) ? trim($_POST['name']) : '';
//			$data['province'] = isset($_POST['province']) ? trim($_POST['province']) : '';
//			$data['city'] = isset($_POST['city']) ? trim($_POST['city']) : '';
//			$data['area'] = isset($_POST['area']) ? trim($_POST['area']) : '';
//			$data['address'] = isset($_POST['address']) ? trim($_POST['address']) : '';
//
//			$where = array();
//			$where['uid'] = $this->user_session['uid'];
//			$company_info = $company->get($where['uid']);
//			if(!empty($company_info)) {
//				$result = $company->edit($where, $data);
//			}
//			else {
//				$data['uid'] = $this->user_session['uid'];
//				$result = $company->create($data);
//				if(!empty($result['err_code'])) {
//					$result = true;
//				}
//				else {
//					$result = false;
//				}
//			}
//			if($result) {
//				json_return(0, '公司修改成功');
//			}
//			else {
//				json_return(1001, '公司修改失败');
//			}
//		}
//
//		$user = M('User');
//		$avatar = $user->getAvatarById($this->user_session['uid']);
//
//		$this->assign('avatar', $avatar);
//		$this->display();
//	}
//
//	// 公司信息设置
//	private function _company_content()
//	{
//		$company = M('Company');
//
//		$company = $company->getCompanyByUid($this->user_session['uid']);
//
//		$this->assign('company', $company);
//	}

	//密码
	public function password()
	{
		if(IS_POST) {
			$user = M('User');
			$current = I('post.current');
			$password = I('post.password');
			$confirm = I('post.confirm');
			if($current == "") {
				json_return(1, '当前密码不能为空！');
			}

			if($password == '') {
				json_return(1, '新密码不能为空！');
			}

			if($password != $confirm) {
				json_return(1, "两次密码输入不一致！");
			}

			if($password == $password) {
				json_return(1, "新密码不能和当前密码一致！");
			}

			$user_info = $user->getUserById($this->user_session['uid']);
			if($user_info['password'] != md5($current)) {
				json_return(1, "当前密码输入错误！");
			}

			else {
				if($user->setField(array('uid' => $this->user_session['uid']),
					array('password' => md5($password)))
				) {
					unset($_SESSION['user']);
					session_destroy();
					json_return(0, '修改成功，请重新登录！');
				}
				else {
					json_return(1, '修改失败');
				}
			}
		}
		$this->display();
	}

	//密码修改
	private function _password_content()
	{

	}

}