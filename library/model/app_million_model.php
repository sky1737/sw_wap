<?php

class app_million_model extends base_model
{
	public function getIssue($uid)
	{
		$count = $this->db->where(array('uid' => $uid))->getField('issue');
		if($count)
			return $count + 1;

		return 1;
	}

	public function getPoint($uid)
	{
		return $this->db->where(array('uid' => $uid))->sum('point');
	}

	public function getIncome($uid)
	{
		return $this->db->where(array('uid' => $uid))->sum('income');
	}

	public function investOff($uid, $point)
	{
		D('User_income')->data(
			array('uid'      => $uid,
			      'order_no' => '',
			      'income'   => 0,
			      'point'    => $point * -1,
			      'type'     => -2,
			      'add_time' => time(),
			      'status'   => 1,
			      'remarks'  => '积分参与百万大奖。')
		)->add();

		//D('User')->where(array('uid' => $uid, 'status' => 1))->setDec('balance', $order['balance'] * 1.00);
		D('User')->where(array('uid' => $uid, 'status' => 1))->setDec('point', $point * 1);
		// 更新缓存用户信息
		$_SESSION['user'] = D('User')->where(array('uid' => $uid, 'status' => 1))->find();
	}

	public function investReturn($pid, $issue, $point, $layer)
	{
		if($layer > 2)
			return;

		$uid = D('User')->where(array('uid' => $pid, 'status' => 1))->getField('parent_uid');
		if(!$uid)
			return;

		$parent = $this->db->where(array('uid' => $uid, 'issue' => $issue))->find();
		if(!empty($parent)) {
			$income_db = D('App_million_income');
			$parent_income = $income_db->where(array('uid' => $uid, 'issue' => $issue));
			if(empty($parent_income)) {
//				$income = 0;
//				if($parent['point'] <= $point) {
//					$income = $parent['point'] * 30 / 100;
//				}
//				else {
//					$income = $point / 2;
//				}
				$income = round($parent['point'] * 30 / 100);

				// 增加收益记录
				$income_db->data(array('uid'        => $uid,
				                       'issue'      => $issue,
				                       'point'      => $point,
				                       'income'     => $income,
				                       'parent_uid' => $pid,
				                       'time'       => time()))
					->add();
				// 本期收益增加
				$this->db->where(array('uid' => $uid, 'issue' => $issue))->setInc('income', $income);
			}
		}

		$this->investReturn($uid, $issue, $point, $layer + 1);
//		if($order['status'] != 2 || $order['profit_status'] != 0)
//			return;
//
//		if(!$order['profit'])
//			return;

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
//				             'order_id' => $order['order_id'],
//				             'income'   => $rebate,
//				             'point'    => $point,
//				             'type'     => 1,
//				             'add_time' => time(),
//				             'status'   => 1))
//				->add();
//
//			// 用户表数值增加
//			$db_user = D('User');
//			$db_user->where(array('uid' => $order['uid']))->setInc('balance', $rebate);
//			$db_user->where(array('uid' => $order['uid']))->setInc('point', $point);
//			D('Order')->where(array('order_id' => $order['order_id']))->setInc('profit_status');
//		}
	}
}