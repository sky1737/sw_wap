<?php

class recognition_model extends base_model
{

//	public function get_new_qrcode($third_type, $third_id)
//	{
//		$appid = option('config.wechat_appid');
//		$appsecret = option('config.wechat_appsecret');
//
//		if (empty($appid) || empty($appsecret)) {
//			return (array('error_code' => true, 'msg' => '请联系管理员配置【AppId】【 AppSecret】'));
//		}
//
//		$qrcode_return = $this->add_new_qrcode_row($third_type, $third_id);
//
//		if ($qrcode_return['error_code']) {
//			return $qrcode_return;
//		}
//
//		import('ORG.Net.Http');
//		$http = new Http();
//
//		//微信授权获得access_token
//		import('WechatApi');
//
//		$tokenObj = new WechatApi(array('appid' => $appid, 'appsecret' => $appsecret));
//
//		$access_token = $tokenObj->get_access_token();
//
//		$qrcode_url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $access_token['access_token'];
//		$post_data['action_name'] = 'QR_LIMIT_SCENE';
//		$post_data['action_info']['scene']['scene_id'] = $qrcode_return['qrcode_id'];
//
//		$json = $http->curlPost($qrcode_url, json_encode($post_data));
//		if (!$json['errcode']) {
//			$qrcode_save_return =
//				$this->save_qrcode($qrcode_return['qrcode_id'], $json['ticket'], $third_type, $third_id);
//
//			return $qrcode_save_return;
//		}
//		else {
//			return (array('error_code' => true,
//			              'msg'        => '发生错误：错误代码' . $json['errcode'] . ',微信返回错误信息：' . $json['errmsg']));
//		}
//	}

	/*
	 * 生成推广用的临时二维码
	 * @return array('error_code'=>false,'qrcode_id'=>'recognitionId','twiker_qrcode_id'=>'twikerId','qrcode'=>'qrcodeUrl')
	 * @return array('error_code'=>true,'msg'=>'msg content')
	 */
	public function get_twiker_qrcode($uid)
	{
		$database_twiker_qrcode = D('Twiker_qrcode');
		// 清理记录
		$database_twiker_qrcode->where(array('expire_time' => array('<', ($_SERVER['REQUEST_TIME'] - 604800))))
			->delete();

		$qrcode_id = $_SESSION['twiker_qrcode_id'];
		if ($qrcode_id) {
			$twiker = $database_twiker_qrcode->where(array('id' => $qrcode_id))->find();
			if ($twiker) {
				$recognition_id =
					$this->db->field('id')->where(array('third_type' => 'twiker', 'third_id' => $qrcode_id))
						->order('id desc')->find();
				if ($recognition_id) {
					return array(
						'error_code' => false,
						'qrcode_id' => $recognition_id,
						'twiker_qrcode_id' => $qrcode_id,
						'qrcode' => 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' .
							urlencode($twiker['ticket']));
				}
			}
		}

		$data_twiker_qrcode['uid'] = $uid;
		$data_twiker_qrcode['expire_time'] = $_SERVER['REQUEST_TIME'] + 604800;
		$qrcode_id = $database_twiker_qrcode->data($data_twiker_qrcode)->add();
		if (empty($qrcode_id)) {
			return (array('error_code' => true, 'msg' => '获取二维码错误！无法写入数据到数据库。请重试。'));
		}

		// 添加 Recognition 扫码记录
		$recognition = $this->add_new_qrcode_row('twiker', $qrcode_id);
		if ($recognition['error_code']) {
			return array('error_code' => true, 'msg' => '生成Recognition记录失败！');
		}

		//微信授权获得access_token
		import('WechatApi');

		$appid = option('config.wechat_appid');
		$appsecret = option('config.wechat_appsecret');
		if (empty($appid) || empty($appsecret)) {
			return (array('error_code' => true, 'msg' => '请联系管理员配置【AppId】【AppSecret】'));
		}

		$tokenObj = new WechatApi(array('appid' => $appid, 'appsecret' => $appsecret));
		$access_token = $tokenObj->get_access_token();
		if ($access_token['errcode']) {
			return array('error_code' => true, 'msg' => '发生错误：错误代码 ' . $access_token['errcode'] . '，微信返回错误信息：' .
				$access_token['errmsg']);
		}

		$qrcode_url =
			'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $access_token['access_token'];
		$post_data['expire_seconds'] = 604800;
		$post_data['action_name'] = 'QR_SCENE';
		$post_data['action_info']['scene']['scene_id'] = $recognition['qrcode_id'];

		import('Http');
		$json = Http::curlPost($qrcode_url, json_encode($post_data));
		if (!$json['errcode']) {
			$condition_twiker_qrcode['id'] = $qrcode_id;
			$data_twiker_qrcode['ticket'] = $json['ticket'];

			if ($database_twiker_qrcode->where($condition_twiker_qrcode)->data($data_twiker_qrcode)->save()) {
				$return = $this->save_qrcode($recognition['qrcode_id'], $json['ticket'], 'twiker', $qrcode_id);
				$return['twiker_qrcode_id'] = $qrcode_id;

				$_SESSION['twiker_qrcode_id'] = $qrcode_id;

				return $return;
			}
			else {
				$database_twiker_qrcode->where($condition_twiker_qrcode)->delete();

				return (array('error_code' => true, 'msg' => '获取二维码错误！保存二维码失败。请重试。'));
			}
		}
		else {
			$condition_twiker_qrcode['id'] = $qrcode_id;
			$database_twiker_qrcode->where($condition_twiker_qrcode)->delete();

			return (array('error_code' => true,
				'msg' => '发生错误：错误代码 ' . $json['errcode'] . '，微信返回错误信息：' . $json['errmsg']));
		}
	}

	/*
	 * 生成登录用的临时二维码
	 * @return array('error_code'=>false,'qrcode_id'=>'recognitionId','login_qrcode_id'=>'loginId','qrcode'=>'qrcodeUrl')
	 * @return array('error_code'=>true,'msg'=>'msg content')
	 */

	/**
	 * @param $third_type
	 * @param $third_id
	 * @param $store_id
	 * @return array('error_code'=>false, 'qrcode_id'=>(int))
	 * @return array('error_code'=>true, 'msg'=>'')
	 */
	public function add_new_qrcode_row($third_type, $third_id, $store_id = 0)
	{
		$data_new_recognition['third_type'] = $third_type;
		$data_new_recognition['third_id'] = $third_id;
		$data_new_recognition['store_id'] = $store_id;
		$data_new_recognition['status'] = 0;
		//$data_new_recognition['add_time'] = $_SERVER['REQUEST_TIME'];
		//首先查取有没有status = 0的，优先替换
		//$condition_recognition['status'] = 0;

		$recognition = $this->db->field('`id`')->where($data_new_recognition)->find();
		if (!$recognition) {
			$qrcode_id = $this->db->data($data_new_recognition)->add();
			if ($qrcode_id) {
				return (array('error_code' => false, 'qrcode_id' => $qrcode_id));
			}
			else {
				return (array('error_code' => true, 'msg' => '获取失败！请重试。'));
			}
		}
		else {
			return array('error_code' => false, 'qrcode_id' => $recognition['id']);
//			$condition_new_recognition['id'] = $recognition['id'];
//			if ($this->db->where($condition_new_recognition)->data($data_new_recognition)->save()) {
//
//			}
//			else {
//				return (array('error_code' => true, 'msg' => '获取失败！请重试。'));
//			}
		}
	}

	/**
	 * @param $qrcode_id
	 * @param $ticket
	 * @param $third_type
	 * @param $third_id
	 * @return array('error_code'=>false,'qrcode_id'=>'Recognition_id',qrcode='qrcode_url')
	 * @return array('error_code'=>true,'msg'=>'')
	 */
	public function save_qrcode($qrcode_id, $ticket, $third_type, $third_id, $store_id = 0)
	{
		$condition_recognition['id'] = $qrcode_id;

		$data_recognition['status'] = 0;
		$data_recognition['third_type'] = $third_type;
		$data_recognition['third_id'] = $third_id;
		$data_recognition['store_id'] = $store_id;
		$data_recognition['add_time'] = $_SERVER['REQUEST_TIME'];
		$data_recognition['ticket'] = $ticket;
		if ($this->db->where($condition_recognition)->data($data_recognition)->save()) {
//			$save_return = $this->save_app_qrcode($qrcode_id, $third_type, $third_id);
//			if($save_return['error_code']) {
//				return $save_return;
//			}

			return array(
				'error_code' => false,
				'qrcode_id' => $qrcode_id,
				'qrcode' => 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . urlencode($ticket));
		}
		else {
			return (array('error_code' => true, 'msg' => '二维码保存失败！请重试。'));
		}
	}

	public function get_login_qrcode($store_id = 0)
	{
		$database_login_qrcode = D('Login_qrcode');
		// 清理记录
		$database_login_qrcode->where(array('add_time' => array('<', ($_SERVER['REQUEST_TIME'] - 604800))))->delete();

		$qrcode_id = $_SESSION['login_qrcode_id'];
		if ($qrcode_id) {
			$login = $database_login_qrcode->where(array('id' => $qrcode_id))->find();
			if ($login) {
				$recognition_id =
					$this->db->field('id')->where(array('third_type' => 'login', 'third_id' => $qrcode_id,
						'store_id' => $store_id))
						->order('id desc')->find();
				if ($recognition_id) {
					return array(
						'error_code' => false,
						'qrcode_id' => $recognition_id,
						'login_qrcode_id' => $qrcode_id,
						'qrcode' => 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' .
							urlencode($login['ticket']));
				}
			}
		}

		$data_login_qrcode['add_time'] = $_SERVER['REQUEST_TIME'];
		$qrcode_id = $database_login_qrcode->data($data_login_qrcode)->add();
		if (empty($qrcode_id)) {
			return (array('error_code' => true, 'msg' => '获取二维码错误！无法写入数据到数据库。请重试。'));
		}

		// 添加 Recognition 扫码记录
		$recognition = $this->add_new_qrcode_row('login', $qrcode_id, $store_id);
		if ($recognition['error_code']) {
			return array('error_code' => true, 'msg' => '生成Recognition记录失败！');
		}

		//$http = new Http();

		//微信授权获得access_token
		import('WechatApi');

		$appid = option('config.wechat_appid');
		$appsecret = option('config.wechat_appsecret');
		if (empty($appid) || empty($appsecret)) {
			return (array('error_code' => true, 'msg' => '请联系管理员配置【AppId】【AppSecret】'));
		}

		$tokenObj = new WechatApi(array('appid' => $appid, 'appsecret' => $appsecret));
		$access_token = $tokenObj->get_access_token();
		if ($access_token['errcode']) {
			return array('error_code' => true, 'msg' => '发生错误：错误代码 ' . $access_token['errcode'] . '，微信返回错误信息：' .
				$access_token['errmsg']);
		}

		$qrcode_url =
			'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $access_token['access_token'];
		$post_data['expire_seconds'] = 604800;
		$post_data['action_name'] = 'QR_SCENE';
		$post_data['action_info']['scene']['scene_id'] = $recognition['qrcode_id'];

		import('Http');
		$json = Http::curlPost($qrcode_url, json_encode($post_data));
// 统计
		M('Api_count')->visit('qrcode_create');

		if (!$json['errcode']) {
			$condition_login_qrcode['id'] = $qrcode_id;
			$data_login_qrcode['ticket'] = $json['ticket'];

			if ($database_login_qrcode->where($condition_login_qrcode)->data($data_login_qrcode)->save()) {
				$return = $this->save_qrcode($recognition['qrcode_id'], $json['ticket'], 'login', $qrcode_id);
				$return['login_qrcode_id'] = $qrcode_id;
				$_SESSION['login_qrcode_id'] = $qrcode_id;

				return $return;
			}
			else {
				$database_login_qrcode->where($condition_login_qrcode)->delete();

				return (array('error_code' => true, 'msg' => '获取二维码错误！保存二维码失败。请重试。'));
			}
		}
		else {
			$condition_login_qrcode['id'] = $qrcode_id;
			$database_login_qrcode->where($condition_login_qrcode)->delete();

			return (array('error_code' => true,
				'msg' => '发生错误：错误代码 ' . $json['errcode'] . '，微信返回错误信息：' . $json['errmsg']));
		}
	}

	/**
	 * 生成获取位置临时二维码
	 * @return array('error_code'=>false,'qrcode_id'=>'recognitionId','location_qrcode_id'=>'locationId','qrcode'=>'qrcodeUrl')
	 * @return array('error_code'=>true,'msg'=>'msg content')
	 */
	public function get_location_qrcode($store_id = 0)
	{
		$database_location = D('Location_qrcode');
		// 清理记录
		$database_location->where(array('add_time' => array('<', ($_SERVER['REQUEST_TIME'] - 604800))))->delete();

		$qrcode_id = $_SESSION['location_qrcode_id'];
		if ($qrcode_id) {
			$location = $database_location->where(array('id' => $qrcode_id, 'status' => 0))->find();
			if ($location) {
				$recognition_id =
					$this->db->field('id')->where(array('third_type' => 'location',
						'third_id' => $qrcode_id))
						->order('id desc')->find();
				if ($recognition_id) {
					return array(
						'error_code' => false,
						'qrcode_id' => $recognition_id[0],
						'location_qrcode_id' => $qrcode_id,
						'qrcode' => 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' .
							urlencode($location['ticket']));
				}
			}
		}

		$data_location['add_time'] = $_SERVER['REQUEST_TIME'];
		$qrcode_id = $database_location->data($data_location)->add();
		if (empty($qrcode_id)) {
			return array('error_code' => true, 'msg' => '获取二维码错误！无法写入数据到数据库。请重试。');
		}

		// 添加 Recognition 扫码记录
		$recognition = $this->add_new_qrcode_row('location', $qrcode_id, $store_id);
		if ($recognition['error_code']) {
			return array('error_code' => true, 'msg' => '生成Recognition记录失败！');
		}

		$appid = option('config.wechat_appid');
		$appsecret = option('config.wechat_appsecret');
		if (empty($appid) || empty($appsecret)) {
			return (array('error_code' => true, 'msg' => '请联系管理员配置【AppId】【AppSecret】'));
		}

		// 微信授权获得 access_token
		import('WechatApi');
		$tokenObj = new WechatApi(array('appid' => $appid, 'appsecret' => $appsecret));
		$access_token = $tokenObj->get_access_token();
		if ($access_token['errcode']) {
			return array('error_code' => true, 'msg' => '发生错误：错误代码 ' . $access_token['errcode'] . '，微信返回错误信息：' .
				$access_token['errmsg']);
		}

		$qrcode_url =
			'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $access_token['access_token'];
		$post_data['expire_seconds'] = 604800;
		$post_data['action_name'] = 'QR_SCENE';
		$post_data['action_info']['scene']['scene_id'] = $recognition['qrcode_id'];

		import('Http');
		$json = Http::curlPost($qrcode_url, json_encode($post_data));
		// 统计
		M('Api_count')->visit('qrcode_create');

		if (!$json['errcode']) {
			$condition_location_qrcode['id'] = $qrcode_id;
			$data_location_qrcode['ticket'] = $json['ticket'];

			if ($database_location->where($condition_location_qrcode)->data($data_location_qrcode)->save()) {
				$return =
					$this->save_qrcode($recognition['qrcode_id'], $json['ticket'], 'location', $qrcode_id, $store_id);
				$return['location_qrcode_id'] = $qrcode_id;

				$_SESSION['location_qrcode_id'] = $qrcode_id;

				return $return;
			}
			else {
				$database_location->where($condition_location_qrcode)->delete();

				return array('error_code' => true, 'msg' => '获取二维码错误！保存二维码失败。请重试。');
			}
		}
		else {
			$condition_location_qrcode['id'] = $qrcode_id;
			$database_location->where($condition_location_qrcode)->delete();

			return array('error_code' => true,
				'msg' => '发生错误：错误代码 ' . $json['errcode'] . '，微信返回错误信息：' . $json['errmsg']);
		}
	}

	// 产生一条新记录，不包含二维码的ticket

	/**
	 * @param $qrcode_id
	 * @return array('error_code'=>false,'id'=>'','ticket'=>'qrcode_url')
	 */
	public function get_tmp_qrcode($type, $qrcode_id, $store_id = 0)
	{
		$db = D('Recognition');
		$db->where(array('third_type' => $type, 'add_time' => array('<', ($_SERVER['REQUEST_TIME'] - 604800))))
			->delete();

		$recognition =
			$db->where(array('third_type' => $type, 'third_id' => $qrcode_id, 'store_id' => $store_id))->find();
		if ($recognition) {
			return array(
				'error_code' => 0,
				'id' => $qrcode_id,
				'expire' => $recognition['add_time'] + 604800,
				'qrcode' => 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' .
					urlencode($recognition['ticket']));
		}

		$appid = option('config.wechat_appid');
		$appsecret = option('config.wechat_appsecret');
		if (empty($appid) || empty($appsecret)) {
			return array('error_code' => 1, 'msg' => '请联系管理员配置【AppId】【AppSecret】');
		}

		// 添加 Recognition 扫码记录
		$recognition = $this->add_new_qrcode_row($type, $qrcode_id, $store_id);
		if ($recognition['error_code']) {
			return array('error_code' => true, 'msg' => '生成Recognition记录失败！');
		}

		// 微信授权获得access_token
		import('WechatApi');
		$tokenObj = new WechatApi(array('appid' => $appid, 'appsecret' => $appsecret));
		$access_token = $tokenObj->get_access_token();
		$qrcode_url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $access_token['access_token'];
		$post_data['expire_seconds'] = 604800;
		$post_data['action_name'] = 'QR_SCENE';
		$post_data['action_info']['scene']['scene_id'] = $recognition['qrcode_id'];

		import('Http');
		$json = Http::curlPost($qrcode_url, json_encode($post_data));
		if (!$json['errcode']) {
			if ($return = $this->save_qrcode($recognition['qrcode_id'], $json['ticket'], $type, $qrcode_id, $store_id)
			) {
				$return[$type . '_qrcode_id'] = $qrcode_id;
				$return['expire'] = $_SERVER['REQUEST_TIME'] + 604800;

				return $return;
			}
			else {
				return array('error_code' => true, 'msg' => '获取二维码错误！保存二维码失败。请重试。');
			}
		}
		else {
			return array('error_code' => true,
				'msg' => '发生错误：错误代码 ' . $json['errcode'] . '，微信返回错误信息：' . $json['errmsg']);
		}
	}

	/**
	 * 代理商二维码
	 * 修改为和临时二维码生成代码一致
	 * @param $qrcode_id = $store_id
	 * @return array('error_code'=>false,'id'=>'','ticket'=>'qrcode_url')
	 */
	public function get_agent_qrcode($type = 'agent', $qrcode_id = 0, $store_id = 0)
	{
		$db = D('Recognition');
//		$db->where(array('third_type' => $type, 'add_time' => array('<', ($_SERVER['REQUEST_TIME'] - 604800))))
//			->delete();

		$recognition =
			$db->where(array('third_type' => $type, 'third_id' => $qrcode_id, 'store_id' => $store_id))->find();
		if ($recognition) {
			return array(
				'error_code' => 0,
				'id' => $qrcode_id,
				'qrcode' => 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' .
					urlencode($recognition['ticket']));
		}

		$appid = option('config.wechat_appid');
		$appsecret = option('config.wechat_appsecret');
		if (empty($appid) || empty($appsecret)) {
			return array('error_code' => 1, 'msg' => '请联系管理员配置【AppId】【AppSecret】');
		}

		// 查找或添加 recognition 记录
		$recognition = $this->add_new_qrcode_row($type, $qrcode_id, $store_id);
		if ($recognition['error_code']) {
			return array('error_code' => true, 'msg' => '生成Recognition记录失败！');
		}

		// 微信授权获得access_token
		import('WechatApi');
		$tokenObj = new WechatApi(array('appid' => $appid, 'appsecret' => $appsecret));
		$access_token = $tokenObj->get_access_token();
		$qrcode_url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $access_token['access_token'];

		//{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": 123}}}
		$post_data['action_name'] = 'QR_LIMIT_STR_SCENE'; // QR_LIMIT_SCENE
		//$post_data['action_info']['scene']['scene_id'] = $recognition['qrcode_id'];
		$post_data['action_info']['scene']['scene_str'] = $recognition['qrcode_id'];

		import('Http');
		$json = Http::curlPost($qrcode_url, json_encode($post_data));
		if (!$json['errcode']) {
			if ($return = $this->save_qrcode($recognition['qrcode_id'], $json['ticket'], $type, $qrcode_id, $store_id)
			) {
				$return[$type . '_qrcode_id'] = $qrcode_id;

				return $return;
			}
			else {
				return array('error_code' => true, 'msg' => '获取二维码错误！保存二维码失败。请重试。');
			}
		}
		else {
			return array('error_code' => true,
				'msg' => '发生错误：错误代码 ' . $json['errcode'] . '，微信返回错误信息：' . $json['errmsg']);
		}
	}

	/**
	 * @param $qrcode_id
	 * @param $third_type
	 * @param $third_id
	 * @return null/false
	 * @return 未知
	 */
	public function save_app_qrcode($qrcode_id, $third_type, $third_id)
	{
		if ($third_type == 'group') {
			$save_return = D('Group')->save_qrcode($third_id, $qrcode_id);
		}
		else if ($third_type == 'merchant') {
			$save_return = D('Merchant')->save_qrcode($third_id, $qrcode_id);
		}
		else if ($third_type == 'meal') {
			$save_return = D('Merchant_store')->save_qrcode($third_id, $qrcode_id);
		}
		else if ($third_type == 'lottery') {
			$save_return = D('Lottery')->save_qrcode($third_id, $qrcode_id);
		}

		return $save_return;
	}

	/**
	 * @param $qrcode_id
	 * @param $third_type
	 * @param $third_id
	 * @return array('error_code'=>false,'qrcode_id'=>'recognition_id',qrcode=>'qrcode_url')
	 */
	public function get_qrcode($qrcode_id, $third_type, $third_id)
	{
		$condition_recognition['id'] = $qrcode_id;
		$recognition = $this->field('`id`,`ticket`')->where($condition_recognition)->find();
		if (empty($recognition)) {
			$this->del_app_qrcode($qrcode_id, $third_type, $third_id);
		}
		else {
			return (array('error_code' => false, 'qrcode_id' => $recognition['id'],
				'qrcode' => 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' .
					urlencode($recognition['ticket'])));
		}
	}

	/**
	 * @param $qrcode_id
	 * @param $third_type
	 * @param $third_id
	 */
	public function del_app_qrcode($qrcode_id, $third_type, $third_id)
	{
		if ($third_type == 'group') {
			D('Group')->del_qrcode($third_id);
			$msg = '抱歉，没有找到该团购的二维码！页面将会跳转至获取。';
		}
		else if ($third_type == 'merchant') {
			D('Merchant')->del_qrcode($third_id);
			$msg = '抱歉，没有找到商家信息的二维码！页面将会跳转至获取。';
		}
		exit('<html><head><script type="text/javascript">alert("' . $msg .
			'");window.location.href=window.location.href;</script></head></html>');
	}

}