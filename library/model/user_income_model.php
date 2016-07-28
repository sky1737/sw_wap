<?php

class user_income_model extends base_model
{
	public $type_data = array();

	function __construct($model)
	{
		parent::__construct($model);

		$this->type_data = array(
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
	}

	public function typeTxt($type)
	{
		if(!$type)
			return $this->type_data;

		return $this->type_data[$type];
	}

	public function typeKey($txt)
	{
		if(!$txt)
			return $this->type_data;

		foreach ($this->type_data as $key => $val) {
			if($val == $txt)
				return $key;
		}

		return $this->type_data;
	}

//	/*
//	 * 增加收支记录
//	 * $order   => array()
//	 * $balance => float
//	 * $point   => int
//	 * $type    => int(1购买返利2分销反佣3代理返佣4物流费用5注册奖励6签到奖励7登录奖励8退货返还9管理员设置-1购物使用)
//	 * $status  => (0,1)
//	 * $remarks => string
//	 */
//	public function insert($uid, $order_no = '', $balance = 0.00, $point = 0, $type = 0, $status = 1, $remarks = '')
//	{
//		$this->db->data(
//			array('uid'      => $uid,
//			      'order_no' => $order_no,
//			      'income'   => $balance,
//			      'point'    => $point,
//			      'type'     => $type,
//			      'add_time' => time(),
//			      'status'   => $status,
//			      'remarks'  => $remarks)
//		)->add();
//	}

	public function buyOff($order)
	{
		$this->db->data(
			array(
				'uid'      => $order['uid'],
				'order_no' => $order['order_no'],
				'income'   => $order['balance'] * -1,
				'point'    => $order['point'] * -1,
				'type'     => -1,
				'add_time' => time(),
				'status'   => 1,
				'remarks'  => '购物余额支付和积分抵现。'
			)
		)->add();

		D('User')->where(array('uid' => $order['uid'], 'status' => 1))->setDec('balance', $order['balance'] * 1.00);
		D('User')->where(array('uid' => $order['uid'], 'status' => 1))->setDec('point', $order['point'] * 1);

		// 更新缓存用户信息
		$_SESSION['user'] = D('User')->where(array('uid' => $order['uid'], 'status' => 1))->find();

		$str = $order['balance'] > 0 ? '余额：' . $order['balance'] : '';
		$str .= $order['point'] > 0 ? ((empty($str) ? '' : '+') . '积分：' . $order['point']) : '';

		import('source.class.Notify');
		if($order['balance'] > 0) {
			Notify::getInstance()->accountChange($_SESSION['user']['openid'],
				option('config.wap_site_url') . '/balance.php?a=index',
				'购物消费使用余额支付',
				date("Y/m/d H:i",$order['add_time']),
				$order['balance'],
				'现金',
				$_SESSION['user']['balance'],
				'使用账户余额支付成功，点击查看详情');
		}
		if($order['point'] > 0) {
			Notify::getInstance()->accountChange($_SESSION['user']['openid'],
				option('config.wap_site_url') . '/balance.php?a=index',
				'购物消费使用积分抵现',
				$order['add_time'],//date('Y-m-d h:i:s', ),
				$order['point'],
				'积分',
				$_SESSION['user']['point'],
				'使用积分抵现支付成功，点击查看详情');
		}
	}

	public function buyReturn($order)
	{
	    //如果不是 已 支付 或者 已返
		if($order['status'] != 2 || $order['profit_status'] != 0)
			return;

        //返 订单商品 成本价 给供应商
        {
            $cost_price   = $order['sub_total'] - $order['profit']; //该订单 的 所有商品成本之和
            $store_info   = D('Store')->where(array('store_id' => $order['agent_id']))->find();
            $supplier_uid = $store_info['uid'];

            $is_ok = D('User')->where(array('uid' => $supplier_uid))->setInc('balance', $cost_price);
            //logs(json_encode(array($cost_price,$supplier_user)), 'SupplierReturn');
            if (false !== $is_ok) {

                $this->db
                    ->data(array(
                        'uid'      => $supplier_uid,
                        'order_no' => $order['order_no'],
                        'income'   => $cost_price,
                        'point'    => 0,
                        'type'     => 10,
                        'add_time' => time(),
                        'status'   => 1,
                        'remarks'  => '供应商成本立返'
                    ))
                    ->add();

                $supplier_user = D('User')->where(array('uid' => $supplier_uid))->find();
                Notify::getInstance()->accountChange($supplier_user['openid'],
                    option('config.wap_site_url') . '/balance.php?a=index',
                    '供应商您好，订单成本现金已到账',
                    date('Y-m-d H:i:s', $order['add_time']),
                    $cost_price,
                    '现金',
                    $supplier_user['balance'] + $cost_price,
                    "返还成本的订单号：{$order['order_no']}");

                //logs(json_encode(array( $supplier_user['balance'] , $cost_price)), 'SupplierInfo');
            }
        }

        //如果无订单利润
        if(!$order['profit'])
			return;

		$rebate = $order['profit'] * 1.00 * option('config.buyer_ratio') / 100.00;
		$point = 0;
		if(option('config.default_point')) {
			$point = (int)($rebate * option('config.point_exchange'));
			$rebate = 0.00;
		}
		if($rebate > 0 || $point > 0) {
			// 用户收益
			$this->db
				->data(array(
					'uid'      => $order['uid'],
					'order_no' => $order['order_no'],
					'income'   => $rebate,
					'point'    => $point,
					'type'     => 1,
					'add_time' => time(),
					'status'   => 1,
					'remarks'  => '购物立返'
				))
				->add();

			// 用户表数值增加
			$db_user = D('User');
			$db_user->where(array('uid' => $order['uid']))->setInc('balance', $rebate);
			$db_user->where(array('uid' => $order['uid']))->setInc('point', $point);
			D('Order')->where(array('order_id' => $order['order_id']))->setInc('profit_status');

			// 更新缓存用户信息
			$_SESSION['user'] = D('User')->where(array('uid' => $order['uid'], 'status' => 1))->find();

			import('source.class.Notify');
			if($rebate > 0) {
				Notify::getInstance()->accountChange($_SESSION['user']['openid'],
					option('config.wap_site_url') . '/balance.php?a=index',
					'购物立返现金到账',
					date('Y/m/d H:i:s', $order['add_time']),
					$rebate,
					'现金',
					$_SESSION['user']['balance'],
					'购物立返现金到账，点击查看详情');
			}
			if($point > 0) {
				Notify::getInstance()->accountChange($_SESSION['user']['openid'],
					option('config.wap_site_url') . '/balance.php?a=index',
					'购物立返积分到帐',
					date('Y/m/d H:i', $order['add_time']),
					$point,
					'积分',
					$_SESSION['user']['point'],
					'购物立返积分到帐，点击查看详情');
			}

			Notify::getInstance()->orderUpdate($_SESSION['user']['openid'],
				option('config.wap_site_url') . '/order.php?orderid=' . $order['order_id'],
				'你好，订单已支付成功',
				$order['order_no'],
				'支付成功，待发货',
				'您的订单已支付成功，已通知供商发货啦！');

			$agent_openid = $db_user->where("`uid` = (select `uid` from `tp_store` where `store_id` =
			{$order['agent_id']} and `status` = 1)")
				->getField('openid');
			if($agent_openid) {
				Notify::getInstance()->notifyAgent($agent_openid, option('config.site_url'),
					'有新订单啦，请尽快登录代理平台发货',
					$order['order_no'],
					'登录代理平台查看',
					$order['total'],
					date('Y/m/d H:i:s',$order['add_time']),
					'平台地址：'.option('config.site_url').'，扫码登录即可。');
			}
		}
	}

//	public function rechargeReturn($order)
//	{
//		if($order['status'] != 2 || $order['profit_status'] != 0)
//			return;
//
//		if(!$order['profit'])
//			return;
//
//		$rebate = $order['profit'] * 1.00 * option('config.buyer_ratio') / 100.00;
//		$point = 0;
//		if(option('config.default_point')) {
//			$point = (int)($rebate * option('config.point_exchange'));
//			$rebate = 0.00;
//		}
//		if($rebate > 0 || $point > 0) {
//			// 用户收益
//			$this->db
//				->data(array('uid'      => $order['uid'],
//				             'order_no' => $order['order_no'],
//				             'income'   => $rebate,
//				             'point'    => $point,
//				             'type'     => 1,
//				             'add_time' => time(),
//				             'status'   => 1,
//				             'remarks'  => '购物立返'))
//				->add();
//
//			// 用户表数值增加
//			$db_user = D('User');
//			$db_user->where(array('uid' => $order['uid']))->setInc('balance', $rebate);
//			$db_user->where(array('uid' => $order['uid']))->setInc('point', $point);
//			D('Order')->where(array('order_id' => $order['order_id']))->setInc('profit_status');
//		}
//	}

	/**
	 * 统计数量
	 * @param $where
	 * @return mixed
	 */
	public function getTotal($where)
	{
		return $this->db->where($where)->count('income_id');
	}

	/**
	 * 获取分布记录
	 * @param $where
	 * @param $order
	 * @param $offset
	 * @param $limit
	 * @return mixed
	 */
	public function getRecords($where, $order, $offset, $limit)
	{
		return $this->db->where($where)->order($order)->limit($offset . ',' . $limit)->select();
	}

	/**
	 * 统计分销数量
	 * @param $where
	 * @return mixed
	 */
	public function getProfits($where)
	{
		return $this->db->where($where)->count('income_id');
	}

	/**
	 * 统计分销利润
	 * @param $where
	 * @return int
	 */
	public function sumProfit($where)
	{
		$where['type'] = array('in', array(2, 3, 4, 5));
		$profit = $this->db->where($where)->sum('profit');

		return !empty($profit) ? $profit : 0;
	}

	/**
	 * 统计分销积分
	 * @param $where
	 * @return int
	 */
	public function sumPoint($where)
	{
		$where['type'] = array('in', array(2, 3, 4, 5));
		$point = $this->db->where($where)->sum('point');

		return !empty($point) ? $point : 0;
	}


	/**
	 * 根据订单号 和 状态 获取返现的积分和余额
	 * @param $where
	 * @return mixed
	 */
	public function getPointAndIncomeByOrderNo($where)
	{
		return $this->db->where($where)->find();
	}


//	public function getIncomeCount($where)
//	{
//		$sql = 'SELECT COUNT(c.id) AS count FROM `' . option('system.DB_PREFIX') . 'user_cash` c,  `' .
//			option('system.DB_PREFIX') . 'user_card` d,  `' . option('system.DB_PREFIX') .
//			'bank` b WHERE c.`card_id` = d.`card_id` AND d.`bank_id` = b.`bank_id`';
//
//		if($where) {
//			foreach ($where as $key => $value) {
//				if($key == '_string') {
//					$sql .= ' AND ' . $value;
//				}
//				else {
//					$sql .= ' AND ' . $key . ' = \'' . $value . '\'';
//				}
//			}
//		}
//
//		$result = $this->db->query($sql);
//
//		return !empty($result[0]['count']) ? $result[0]['count'] : 0;
//	}
//
//	public function getIncomes($where, $offset, $limit)
//	{
//		$sql = 'SELECT c.* , b.`bank_id`, b.`name`, d.`bank_name`, d.`card_no`, d.`card_user` FROM `' .
//			option('system.DB_PREFIX') . 'user_cash` c,  `' . option('system.DB_PREFIX') . 'user_card` d,  `' .
//			option('system.DB_PREFIX') . 'bank` b WHERE c.`card_id` = d.`card_id` AND d.`bank_id` = b.`bank_id`';
//
//		if($where) {
//			foreach ($where as $key => $value) {
//				if($key == '_string') {
//					$sql .= ' AND ' . $value;
//				}
//				else {
//					$sql .= ' AND ' . $key . ' = \'' . $value . '\'';
//				}
//			}
//		}
//
//		$sql .= ' ORDER BY c.id DESC LIMIT ' . $offset . ', ' . $limit;
//		$withdrawals = $this->db->query($sql);
//
//		return $withdrawals;
//	}
}
