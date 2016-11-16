<?php

/*
 */

class user_model extends base_model
{

    public $RedPackRatio;

    function __construct($model)
    {
        parent::__construct($model);

        $this->RedPackRatio = array(
            'redpack_1' => 24.99,
            'redpack_2' => 12.99,
            'redpack_3' => 33.99,
            'agent_1' => 5.99,
            'agent_2' => 5.99,
            'agent_3' => 2.99
        );
    }

    public function getUnique($key, $value)
    {
        $count = $this->db->where(array($key => $value, 'status' => 1))->count('store_id');
        if ($count) {
            return false;
        } else {
            return true;
        }
    }

    public function add_user($user)
    {
        if (!isset($user['phone'])) return array('err_code' => 1006, 'err_msg' => '必须携带手机号！');
        if (!isset($user['nickname'])) return array('err_code' => 1007, 'err_msg' => '必须携带用户名！');
        if (!isset($user['password'])) return array('err_code' => 1008, 'err_msg' => '必须携带密码！');
        if (!isset($user['check_phone'])) $user['check_phone'] = 0;
        if (!isset($user['openid'])) $user['openid'];

        $data_user['phone'] = $user['phone'];
        $data_user['nickname'] = $user['nickname'];
        $data_user['password'] = $user['password'];
        $data_user['check_phone'] = $user['check_phone'];
        $data_user['reg_time'] = $data_user['last_time'] = $_SERVER['REQUEST_TIME'];
        $data_user['reg_ip'] = $data_user['last_ip'] = ip2long($_SERVER['REMOTE_ADDR']);
        $data_user['avatar'] = $user['avatar'];
        $data_user['login_count'] = $user['login_count'];
        $data_user['openid'] = $user['openid'];
        if ($uid = $this->db->data($data_user)->add()) {
            $data_user['uid'] = $uid;

            return array('err_code' => 0, 'err_msg' => $data_user);
        } else {
            return array('err_code' => 1009, 'err_msg' => '用户添加失败！请重试。');
        }
    }

    public function get_user($field, $value)
    {
        if (!in_array($field, array('uid', 'phone', 'nickname', 'openid')))
            return array('err_code' => -1, 'err_msg' => 'field参数错误！');

        if (empty($value))
            return array('err_code' => -1, 'err_msg' => 'value参数错误！');

        return array('err_code' => 0, 'user' => $this->db->where(array($field => $value))->find());
    }

    public function save_user($condition, $data)
    {
        return array('err_code' => 0, 'err_msg' => $this->db->where($condition)->data($data)->save());
    }

    public function autologin($field, $value)
    {
        $condition_user[$field] = $value;
        $now_user = $this->db->where($condition_user)->find();
        if ($now_user) {
            if (empty($now_user['status'])) {
                return array('err_code' => -1, 'err_msg' => '该帐号被禁止登录!');
            }
            $condition_save_user['uid'] = $now_user['uid'];
            $data_save_user['last_time'] = $_SERVER['REQUEST_TIME'];
            $data_save_user['last_ip'] = get_client_ip(1);
            $this->db->where($condition_save_user)->data($data_save_user)->save();

            return array('err_code' => 0, 'err_msg' => 'OK', 'user' => $now_user);
        } else {
            return array('err_code' => 1001, 'err_msg' => '没有此用户！');
        }
    }

    public function getUserById($uid)
    {
        return $this->db->where(array('uid' => $uid))->find();
    }

    public function getUser($where)
    {
        return $this->db->where($where)->find();
    }

    public function getAvatarById($uid)
    {
        $user = $this->db->field('avatar')->where(array('uid' => $uid))->find();

        return !empty($user['avatar']) ? $user['avatar'] : '';
    }

    public function setField($where, $data)
    {
        return $this->db->where($where)->data($data)->save();
    }

    public function isWeixinFans($uid)
    {
        $user = $this->db->field('is_weixin')->where(array('uid' => $uid))->find();

        return !empty($user['is_weixin']) ? true : false;
    }

    public function setStoreInc($uid)
    {
        return $this->db->where(array('uid' => $uid))->setInc('stores');
    }

    public function setStoreDec($uid)
    {
        if ($result = $this->db->where(array('uid' => $uid))->setDec('stores')) {
            $user = $this->db->field('stores')->where(array('uid' => $uid))->find();
            if (empty($user['stores'])) {
                $result = $this->db->where(array('uid' => $uid))->data(array('is_seller' => 0))->save();
            }
        }

        return $result;
    }

    public function checkUser($where)
    {
        return $this->db->where($where)->find();
    }

    public function addUser($data)
    {
        return $this->db->data($data)->add();
    }

    public function getUserByToken($token)
    {
        $user = $this->db->where(array('token' => $token, 'is_seller' => 1))->find();

        return !empty($user['uid']) ? $user['uid'] : '';
    }

    public function getPaymentUrlByToken($token, $uid = '')
    {
        $where = array();
        $where['token'] = $token;
        if (!empty($uid)) {
            $where['uid'] = $uid;
        }
        $where['is_seller'] = 1;
        $user = $this->db->where($where)->find();

        return !empty($user['payment_url']) ? $user['payment_url'] : '';
    }

    public function getNotifyUrlByToken($token, $session_id = '', $third_id = '')
    {
        $where = array();
        $where['token'] = $token;
        if (!empty($session_id)) {
            $where['session_id'] = $session_id;
        }
        if (!empty($third_id)) {
            $where['third_id'] = $third_id;
        }
        $where['is_seller'] = 1;
        $user = $this->db->where($where)->find();

        return !empty($user['notify_url']) ? $user['notify_url'] : '';
    }

    public function setSeller($uid, $status)
    {
        return $this->db->where(array('uid' => $uid))->data(array('is_seller' => $status))->save();
    }

    public function getFansCount($where)
    {
        $sql = "SELECT COUNT(uid) AS count FROM " . option('system.DB_PREFIX') . 'user u';
        $_string = '';
        if (array_key_exists('_string', $where)) {
            $_string = ' AND ' . $where['_string'];
            unset($where['_string']);
        }
        $condition = array();
        foreach ($where as $key => $value) {
            $condition[] = $key . " = '" . $value . "'";
        }
        $where = ' WHERE ' . implode(' AND ', $condition) . $_string;
        $sql .= $where;
        $fans = $this->db->query($sql);
        if (!empty($fans)) {
            return !empty($fans[0]['count']) ? $fans[0]['count'] : 0;
        } else {
            return 0;
        }
    }

    public function getFans($where, $offset, $limit, $order = '')
    {
        //$sql = "SELECT u.uid,u.nickname,u.phone,(SELECT COUNT(order_id) FROM " . option('system.DB_PREFIX') . "order o WHERE u.uid = o.uid) AS order_count, (SELECT SUM(total) FROM " . option('system.DB_PREFIX') . "order o WHERE u.uid = o.uid) AS order_total FROM " . option('system.DB_PREFIX') . "user u";
        $sql = "SELECT u.uid,u.nickname,u.phone,(SELECT COUNT(fx_order_id) FROM " . option('system.DB_PREFIX') .
            "fx_order fo1 WHERE fo1.uid = u.uid AND fo1.store_id = '" . $_SESSION['wap_drp_store']['store_id'] .
            "') AS order_count, (SELECT SUM(total) FROM " . option('system.DB_PREFIX') .
            "fx_order fo2  WHERE fo2.uid = u.uid AND fo2.store_id = '" . $_SESSION['wap_drp_store']['store_id'] .
            "') AS order_total FROM " . option('system.DB_PREFIX') . "user u";
        $_string = '';
        if (array_key_exists('_string', $where)) {
            $_string = ' AND ' . $where['_string'];
            unset($where['_string']);
        }
        $condition = array();
        foreach ($where as $key => $value) {
            $condition[] = $key . " = '" . $value . "'";
        }
        $where = ' WHERE ' . implode(' AND ', $condition) . $_string;
        $sql .= $where;
        if (empty($order)) {
            $order = 'u.uid DESC';
        }
        $order = ' ORDER BY ' . $order;
        $sql .= $order;
        $sql .= ' LIMIT ' . $offset . ',' . $limit;
        $fans = $this->db->query($sql);

        return $fans;
    }

    public function getList($where)
    {
        $user_list = $this->db->where($where)->select();

        $return_user_list = array();
        foreach ($user_list as $tmp) {
            if ($tmp['avatar']) {
                $tmp['avatar'] = getAttachmentUrl($tmp['avatar']);
            } else {
                $tmp['avatar'] = getAttachmentUrl('images/touxiang.png', false);
            }

            $return_user_list[$tmp['uid']] = $tmp;
        }

        return $return_user_list;
    }

    public function outgo($uid, $balance, $point)
    {
        $this->db->where(array('uid' => $uid))->setDec('balance', $balance);
        $this->db->where(array('uid' => $uid))->setDec('point', $point);
    }

    public function promoter($parent_uid, $order_no, $profit, $level = 1)
    {
        if (!$parent_uid || !$profit)
            return;

        $ratio = option('config.promoter_ratio_' . $level);
        if (!$ratio)
            return;

        $parent = D('')->table("User as u")
            ->join("Store as s ON u.uid = s.uid", "LEFT")
            ->join("Agent as a ON a.agent_id = s.agent_id", "LEFT")
            ->field('`u`.`uid` as uid, `u`.`parent_uid` as parent_uid')
            ->where("`u`.`uid`='$parent_uid' AND `u`.`stores`='1' AND `u`.`status`='1' AND `a`.`open_self`=0 AND `a`.`is_editor`=0")
            ->find();
        /*
                $parent = $this->db->where(array('uid' => $parent_uid, 'status' => 1, 'stores' => array('>', 0)))
                    ->field('uid,parent_uid')->find();
        */
        if (!$parent)
            return;

        $ratio = $ratio * 1;
        $exchange = option('config.point_exchange') * 1;
        $is_point = option('config.default_point') + 0;
        $money = round($profit * $ratio / 100, 2);
        $point = 0;
        if ($is_point) {
            $point = $money * $exchange;
            $money = 0;
        }
        if ($money > 0 || $point > 0) {
            $this->income($parent['uid'], $money, $point);
            D('User_income')->data(array('uid' => $parent_uid,
                'order_no' => $order_no,
                'income' => $money,
                'point' => $point,
                'type' => 1 + $level,
                'add_time' => time(),
                'status' => 1,
                'remarks' => '分销返佣结算'))
                ->add();
        }

        if (!$parent['parent_uid'])
            return;

        $level++;
        $this->promoter($parent['parent_uid'], $order_no, $profit, $level);
    }

    public function income($uid, $balance, $point)
    {
        $this->db->where(array('uid' => $uid))->setInc('balance', $balance);
        $this->db->where(array('uid' => $uid))->setInc('point', $point);
    }

    public function agent($parent_uid, $order_no, $profit, $level)
    {
        if (!$parent_uid || !$profit)
            return;

        $ratio = option('config.agent_ratio_' . $level);
        if (!$ratio)
            return;

        $parent = D('')->query('SELECT u.`uid`, u.`parent_uid`, s.`store_id`, s.`agent_id`, a.`is_agent` from `tp_user` u INNER JOIN `tp_store` s on u.`uid`= s.`uid` INNER JOIN `tp_agent` a on s.`agent_id`= a.`agent_id` where a.`open_self`=0 AND a.`is_editor`=0 AND u.`status`= 1 and s.`status`= 1 and u.`stores`= 1 u.`uid` = ' . $parent_uid);
        if (empty($parent))
            return;

        $parent = $parent[0];
        if ($parent['is_agent']) {
            $ratio = $ratio * 1;
            $exchange = option('config.point_exchange') * 1;
            $is_point = option('config.default_point') + 0;
            $money = round($profit * $ratio / 100, 2);
            $point = 0;
            if ($is_point) {
                $point = $money * $exchange;
                $money = 0;
            }

            if (!$parent['parent_uid']) {
                if ($level == 1) {
                    $money = round($profit * C('config.agent_ratio_1') / 100.00, 2) +
                        round($profit * C('config.agent_ratio_2') / 100.00, 2) +
                        round($profit * C('config.agent_ratio_3') / 100.00, 2);
                } else if ($level == 2) {
                    $money = round($profit * C('config.agent_ratio_2') / 100.00, 2) +
                        round($profit * C('config.agent_ratio_3') / 100.00, 2);
                } else {
                    $money = round($profit * C('config.agent_ratio_3') / 100.00, 2);
                }
            } else {
                if ($level > 2) {
                    $money = 0;
                    $point = 0;
                }
            }

            if ($money > 0 || $point > 0) {
                $this->income($parent['uid'], $money, $point);

                D('User_income')->data(array('uid' => $parent_uid,
                    'order_no' => $order_no,
                    'income' => $money,
                    'point' => $point,
                    'type' => 3 + $level,
                    'add_time' => time(),
                    'status' => 1,
                    'remarks' => '代理管理費用'))
                    ->add();
            }
            $level++;
        }

        if (!$parent['parent_uid'])
            return;

        $this->agent($parent['parent_uid'], $order_no, $profit, $level);
    }

    public function postage($store_id, $order_no, $profit)
    {
        if (!$store_id || !$profit)
            return;

        $ratio = option('config.logistics_ratio');
        if (!$ratio)
            return;

        $agent_uid = D('Store')
            ->where("store_id = {$store_id} and status = 1 and agent_id in (select agent_id from tp_agent where status = 1 and is_agent = 1)")
            //->where(array('store_id' => $store_id, 'status' => 1, 'agent_approve' => array('>', 1)))
            ->getField('uid');
        if (!$agent_uid)
            return;

        $ratio = $ratio * 1;
        $exchange = option('config.point_exchange') * 1;
        $is_point = option('config.default_point') + 0;
        $money = round($profit * $ratio / 100, 2);
        $point = 0;
        if ($is_point) {
            $point = $money * $exchange;
            $money = 0;
        }

        if ($money > 0 || $point > 0) {
            $this->income($agent_uid, $money, $point);

            D('User_income')->data(array('uid' => $agent_uid,
                'order_no' => $order_no,
                'income' => $money,
                'point' => $point,
                'type' => 6,
                'add_time' => time(),
                'status' => 1,
                'remarks' => '物流费用结算'))
                ->add();

//			import('source.class.Notify');
//			$openid = D('User')->where(array('uid' => $agent_uid, 'status' => 1))->getField('openid');
//			if($openid) {
//				if($money > 0) {
//					Notify::getInstance()->accountChange($openid,
//						option('config.wap_site_url') . '/balance.php?a=index',
//						'物流费用结算',
//						date('Y-m-d h:i:s', time()),
//						$money,
//						'现金',
//						$_SESSION['user']['balance'],
//						'订单完成后的物流费用结算，点击查看详情');
//				}
//				if($point > 0) {
//					Notify::getInstance()->accountChange($_SESSION['user']['openid'],
//						option('config.wap_site_url') . '/balance.php?a=index',
//						'物流费用结算',
//						date('Y-m-d h:i:s', time()),
//						$point,
//						'积分',
//						$_SESSION['user']['point'],
//						'订单完成后的物流费用结算，点击查看详情');
//				}
//			}
        }
    }

    public function costReturn($store_id, $order_no, $cost)
    {
        if (!$store_id || !$cost)
            return;

        $agent_uid = D('Store')
            //->where(array('store_id' => $store_id, 'status' => 1, 'agent_approve' => 1))
            ->where("store_id = {$store_id} and status = 1 and agent_id in (select agent_id from tp_agent where status = 1 and is_agent = 1 and open_self =0 AND is_editor=0)")
            ->getField('uid');
        if (!$agent_uid)
            return;

        if ($cost > 0) {
            $this->income($agent_uid, $cost, 0);

            D('User_income')->data(
                array('uid' => $agent_uid,
                    'order_no' => $order_no,
                    'income' => $cost,
                    'point' => 0,
                    'type' => 11,
                    'add_time' => time(),
                    'status' => 1,
                    'remarks' => '成本结算'))
                ->add();

//			import('source.class.Notify');
//			$openid = D('User')->where(array('uid' => $agent_uid, 'status' => 1))->getField('openid');
//			if($openid) {
//				Notify::getInstance()->accountChange($openid,
//					option('config.wap_site_url') . '/balance.php?a=index',
//					'代理商成本结算',
//					date('Y-m-d h:i:s', time()),
//					$cost,
//					'现金',
//					$_SESSION['user']['balance'],
//					'订单完成后的成本结算，点击查看详情');
//			}
        }
    }

//	//保存提现配置
//	public function settingWithdrawal($where, $data)
//	{
//		return $this->db->where($where)->data($data)->save();
//	}

    public function getIncome($store_id)
    {
        $store = $this->db->where(array('store_id' => $store_id))->find();

        return $store['income'];
    }

    public function getBalance($store_id)
    {
        $store = $this->db->field('balance')->where(array('store_id' => $store_id))->find();

        return !empty($store['balance']) ? $store['balance'] : 0;
    }

//	//利润提现
//	public function drpProfitWithdrawal($store_id, $amount)
//	{
//		return $this->db->where(array('store_id' => $store_id))->setInc('drp_profit_withdrawal', $amount);
//	}

//	//删除提现账号
//	public function delwithdrawal($store_id)
//	{
//		return $this->db->where(array('store_id' => $store_id))->data(array('withdrawal_type' => 0, 'bank_id' => 0,
//		                                                                    'bank_card'       => '',
//		                                                                    'bank_card_user'  => '',
//		                                                                    'last_edit_time'  => time()))->save();
//	}

    /**
     * 店铺已提现金额
     * @param $store_id
     * @return int
     */
    public function getWithdrawalAmount($store_id)
    {
        $store = $this->db->field('withdrawal_amount')->where(array('store_id' => $store_id))->find();

        return !empty($store['withdrawal_amount']) ? $store['withdrawal_amount'] : 0;
    }

    /**
     * 提现申请
     * @param $uid
     * @param $amount
     */
    public function applywithdrawal($uid, $amount)
    {
        $where = array('uid' => $uid, 'status' => 1);
        $this->db->where($where)->setInc('withdrawal', $amount);
        $this->db->where($where)->setDec('balance', $amount);

        D('User_income')
            ->data(array(
                'uid' => $uid,
                'order_no' => '',
                'income' => $amount * -1,
                'point' => 0,
                'type' => -3,
                'add_time' => time(),
                'status' => 1,
                'remarks' => '提现'))
            ->add();

        $_SESSION['user'] = $this->db->where($where)->find();
    }

    public function applyExchange($uid, $point, $balance)
    {
        $where = array('uid' => $uid, 'status' => 1);
        $this->db->where($where)->setDec('point', $point);
        $this->db->where($where)->setInc('exchanged', $point);
        $this->db->where($where)->setInc('balance', $balance);

        D('User_income')
            ->data(array(
                'uid' => $uid,
                'order_no' => '',
                'income' => $balance,
                'point' => $point * -1,
                'type' => 12,
                'add_time' => time(),
                'status' => 1,
                'remarks' => '积分兑换余额'))
            ->add();

        $_SESSION['user'] = $this->db->where($where)->find();
    }

    /**
     * 保存上线
     * @param $uid
     * @param $pid
     */
    public function saveParent($uid, $pid)
    {
        //option('config.promoter_ratio_' . $level)
        $this->db->where(array('uid' => $uid))
            ->data(array('parent_uid' => $pid))
            ->save();

        $this->savePromote($pid);
    }

    /**
     * 推广奖励
     * @param $pid
     */
    public function savePromote($pid)
    {
        $point = option('config.promote_reward');
        if (!$point) {
            return;
        }

        D('User_income')
            ->data(array(
                'uid' => $pid,
                'order_no' => '',
                'income' => 0.00,
                'point' => $point,
                'type' => 7,
                'add_time' => time(),
                'status' => 1,
                'remarks' => '推荐奖励'))
            ->add();
    }

    public function recharge($uid, $amount, $point, $order_no = '')
    {
        if (!$uid || !$amount)
            return;

        $this->income($uid, $amount, $point);
        D('User_income')->data(
            array('uid' => $uid,
                'order_no' => $order_no,
                'income' => $amount,
                'point' => $point,
                'type' => 99,
                'add_time' => time(),
                'status' => 1,
                'remarks' => '账户充值'))
            ->add();
    }

    /**
     * 充值推客返佣计算
     * @param $uid
     * @param $order_no
     * @param $profit
     * @param int $level
     */
    public function rechargePromoter($uid, $order_no, $profit, $level = 0)
    {
        $ratio = 0;
        /*if ($level == 0) {
            $ratio = option('config.promoter_ratio_1') * 1;
        } else {
            $ratio = option('config.promoter_ratio_' . $level) * 1;
        }*/
        $ratio = option('config.promoter_ratio_' . ($level + 1)) * 1;

        if (!$uid || !$profit || !$ratio)
            return;

        $parent = $this->db->where(array('uid' => $uid, 'status' => 1, 'stores' => array('>', 0)))
            ->getField('parent_uid');
        if (!$parent)
            return;

        $money = round($profit * $ratio / 100, 2);
        $point = 0;

        if ($money > 0 || $point > 0) {
            $this->income($parent, $money, $point);
            D('User_income')->data(
                array('uid' => $parent,
                    'order_no' => $order_no,
                    'income' => $money,
                    'point' => $point,
                    'type' => 1 + $level,
                    'add_time' => time(),
                    'status' => 1,
                    'remarks' => '充值分销返佣结算'))
                ->add();

            // 充值订单状态
            D('Recharge_order')->where(array('order_no' => $order_no))
                ->data(array('profit_status' => $level + 1))
                ->save();

            import('source.class.Notify');
            $openid = D('User')->where(array('uid' => $parent, 'status' => 1))->getField('openid');
            if ($openid) {
                if ($money > 0) {
                    Notify::getInstance()->cashBack($openid,
                        option('config.wap_site_url') . '/balance.php?a=index',
                        '分店充值佣金结算',
                        $order_no,
                        $money,
                        '分店充值佣金结算，点击查看详情');
                }
                if ($point > 0) {
                    Notify::getInstance()->cashBack($openid,
                        option('config.wap_site_url') . '/balance.php?a=index',
                        '分店充值积分结算',
                        $order_no,
                        $point,
                        '分店充值积分结算，点击查看详情');
                }
            }
        }

        $level++;
        $this->rechargePromoter($parent, $order_no, $profit, $level);
    }

//	public function payfor($uid, $amount, $point, $order_no = '')
//	{
//		if (!$uid || !$amount)
//			return;
//
//		$this->income($uid, $amount, $point);
//		D('User_income')->data(array('uid' => $uid,
//			'order_no' => $order_no,
//			'income' => $amount,
//			'point' => $point,
//			'type' => 99,
//			'add_time' => time(),
//			'status' => 1,
//			'remarks' => '账户充值'))
//			->add();
//	}

    /*
     * 开店红包
     */
    public function payforRedpack($uid, $order_no, $total, $level = 0)
    {
        $level++;
        $money = $total * $this->RedPackRatio['redpack_' . $level] / 100.00;
        if (!$uid || !$total || !$money)
            return;

        $parent = $this->db
            ->where("`status`=1 AND `stores`>0 AND `uid`=(SELECT `parent_uid` FROM `tp_user` where `uid`={$uid})")
            ->field('uid, openid')
            ->find();
        if (empty($parent))
            return;

        D('Payfor_redpack')->data(array('order_no' => $order_no, 'uid' => $parent['uid'], 'openid' => $parent['openid'], 'add_time' => time(), 'status' => 0, 'amount' => $money))->add();

        $this->payforRedpack($parent['uid'], $order_no, $total, $level);
    }

    public function payforCommission($uid, $order_no, $total, $level = 0)
    {
        $level++;
        $money = $total * $this->RedPackRatio['redpack_' . $level] / 100.00;
        if (!$uid || !$total || !$money)
            return;

        $parent = $this->db->where("`status` = 1 AND `stores` > 0 AND `uid` = (SELECT `parent_uid` FROM `tp_user` where `uid` = {$uid})")->field('uid, openid')->find();
        if (empty($parent))
            return;

        $this->income($parent['uid'], $money, 0);
        D('User_income')->data(array('uid' => $parent['uid'], 'order_no' => $order_no, 'income' => $money, 'point' => 0, 'type' => 7, 'add_time' => time(), 'status' => 1, 'remarks' => '推荐用户开店返佣'))->add();

        // 充值订单分佣状态
        D('payfor_order')->where(array('order_no' => $order_no))->data(array('profit_status' => $level))->save();

        import('source.class.Notify');
        if ($money > 0) {
            Notify::getInstance()->cashBack($parent['openid'],
                option('config.wap_site_url') . '/balance.php?a=index',
                //'分店充值佣金结算',
                '推荐用户开店',
                $order_no,
                $money,
                '推荐用户开店，点击查看详情');
        }

        $this->payforCommission($parent['uid'], $order_no, $total, $level);
    }

    public function payforAgent($uid, $order_no, $total, $level = 1)
    {
        $money = $total * $this->RedPackRatio['agent_' . $level] / 100.00;
        if (!$uid || !$total || !$money)
            return;

        $parent =
            D('')->query('SELECT u.uid, u.parent_uid, u.openid, s.store_id, s.agent_id FROM `tp_user` u INNER JOIN `tp_store` s ON u.uid = s.uid
AND u.status =1 AND s.status =1 AND stores > 0 inner join `tp_agent` a on s.`agent_id` = a.`agent_id` and a.`is_agent` = 1 WHERE u.`uid` = (SELECT parent_uid FROM `tp_user` WHERE `uid` =' .
                $uid . ')');
        if (empty($parent))
            return;

        $parent = $parent[0];
        if ($parent['agent_id']) {
            if ($parent['parent_uid'] == 0) {
                if ($level == 1) {
                    $money = $total * $this->RedPackRatio['agent_1'] / 100.00 +
                        $total * $this->RedPackRatio['agent_2'] / 100.00 +
                        $total * $this->RedPackRatio['agent_3'] / 100.00;
                } else if ($level == 2) {
                    $money = $total * $this->RedPackRatio['agent_2'] / 100.00 +
                        $total * $this->RedPackRatio['agent_3'] / 100.00;
                } else {
                    $money = $total * $this->RedPackRatio['agent_3'] / 100.00;
                }
            } else {
                if ($level == 3) {
                    $money = 0.00;
                }
            }

            if ($money > 0) {
                $this->income($parent['uid'], $money, 0);
                D('User_income')->data(array(
                    'uid' => $parent['uid'],
                    'order_no' => $order_no,
                    'income' => $money,
                    'point' => 0,
                    'type' => 7,
                    'add_time' => time(),
                    'status' => 1,
                    'remarks' => '推荐用户开店返佣'))
                    ->add();

                // 充值订单状态
                D('payfor_order')->where(array('order_no' => $order_no))
                    ->data(array('agent_status' => $level))
                    ->save();

                import('source.class.Notify');
                if ($money > 0) {
                    Notify::getInstance()->cashBack($parent['openid'],
                        option('config.wap_site_url') . '/balance.php?a=index',
                        //'分店充值佣金结算',
                        '推荐用户开店',
                        $order_no,
                        $money,
                        '推荐用户开店，点击查看详情');
                }

                $level++;
            }
        }

        $this->payforAgent($parent['uid'], $order_no, $total, $level);
    }

    public function investCommission($uid, $order_no, $total, $level = 0)
    {
        $level++;
        $money = $total * $this->RedPackRatio['redpack_' . $level] / 100.00;
        if (!$uid || !$total || !$money)
            return;

        $parent = $this->db->where("`status` = 1 AND `stores` > 0 AND `uid` = (SELECT `parent_uid` FROM `tp_user` where `uid` = {$uid})")->field('uid, openid')->find();
        if (empty($parent))
            return;

        $this->income($parent['uid'], $money, 0);
        D('User_income')->data(array('uid' => $parent['uid'], 'order_no' => $order_no, 'income' => $money, 'point' => 0, 'type' => 7, 'add_time' => time(), 'status' => 1, 'remarks' => '推荐用户投资奖励'))->add();

        // 充值订单分佣状态
        D('app_z_order')->where(array('order_no' => $order_no))->data(array('profit_status' => $level))->save();

        import('source.class.Notify');
        if ($money > 0) {
            Notify::getInstance()->cashBack($parent['openid'],
                option('config.wap_site_url') . '/balance.php?a=index',
                //'分店充值佣金结算',
                '推荐用户投资奖励',
                $order_no,
                $money,
                '推荐用户投资奖励，点击查看详情');
        }

        $this->investCommission($parent['uid'], $order_no, $total, $level);
    }

    public function investAgent($uid, $order_no, $total, $level = 1)
    {
        $money = $total * $this->RedPackRatio['agent_' . $level] / 100.00;
        if (!$uid || !$total || !$money)
            return;

        $parent =
            D('')->query('SELECT u.uid, u.parent_uid, u.openid, s.store_id, s.agent_id FROM `tp_user` u INNER JOIN `tp_store` s ON u.uid = s.uid
AND u.status =1 AND s.status =1 AND stores > 0 inner join `tp_agent` a on s.`agent_id` = a.`agent_id` and a.`is_agent` = 1 WHERE u.`uid` = (SELECT parent_uid FROM `tp_user` WHERE `uid` =' .
                $uid . ')');
        if (empty($parent))
            return;

        $parent = $parent[0];
        if ($parent['agent_id']) {
            if ($parent['parent_uid'] == 0) {
                if ($level == 1) {
                    $money = $total * $this->RedPackRatio['agent_1'] / 100.00 +
                        $total * $this->RedPackRatio['agent_2'] / 100.00 +
                        $total * $this->RedPackRatio['agent_3'] / 100.00;
                } else if ($level == 2) {
                    $money = $total * $this->RedPackRatio['agent_2'] / 100.00 +
                        $total * $this->RedPackRatio['agent_3'] / 100.00;
                } else {
                    $money = $total * $this->RedPackRatio['agent_3'] / 100.00;
                }
            } else {
                if ($level == 3) {
                    $money = 0.00;
                }
            }

            if ($money > 0) {
                $this->income($parent['uid'], $money, 0);
                D('User_income')->data(array(
                    'uid' => $parent['uid'],
                    'order_no' => $order_no,
                    'income' => $money,
                    'point' => 0,
                    'type' => 7,
                    'add_time' => time(),
                    'status' => 1,
                    'remarks' => '推荐用户投资奖励'))
                    ->add();

                // 充值订单状态
                D('app_z_order')->where(array('order_no' => $order_no))
                    ->data(array('agent_status' => $level))
                    ->save();

                import('source.class.Notify');
                if ($money > 0) {
                    Notify::getInstance()->cashBack($parent['openid'],
                        option('config.wap_site_url') . '/balance.php?a=index',
                        //'分店充值佣金结算',
                        '推荐用户投资奖励',
                        $order_no,
                        $money,
                        '推荐用户投资奖励，点击查看详情');
                }

                $level++;
            }
        }

        $this->investAgent($parent['uid'], $order_no, $total, $level);
    }
}
