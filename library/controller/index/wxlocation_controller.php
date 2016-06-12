<?php

/**
 * 微信位置
 */
class wxlocation_controller extends base_controller
{

	public function ajax_location_qrcode()
	{
		$location_qrcode = M('Recognition')->get_location_qrcode();
		echo json_encode($location_qrcode);
	}

	public function check_status()
	{
		$database_location = D('Location_qrcode');
		$qid = $_GET['qrcode_id'];
		$now_qrcode = $database_location->where(array('id' => $qid))->find();
		if ($now_qrcode) {
			if ($now_qrcode['status'] == 1 && $now_qrcode['openid'] != '') {
				$lng_lat = M('Location')->where(array('openid' => $now_qrcode['openid']))->find();
				if (!$lng_lat) {
					$database_location->where(array('id' => $qid))
						->save(array('lat'    => $lng_lat['lat'],
						             'lng'    => $lng_lat['lng'],
						             'pre'    => $lng_lat['pre'],
						             'status' => 2));
				}
				echo json_encode(array('errcode' => 0, 'errmsg' => 'scan_ok'));
			}
			else if ($now_qrcode['status'] == 2 && $now_qrcode['openid'] != '') {
				$cookie_arr = array(
					'long'      => $now_qrcode['lng'],
					'lat'       => $now_qrcode['lat'],
					'timestamp' => time()
				);
				//$_SESSION['user'] = $result['user'];
				cookies::put("Web_user", json_encode($cookie_arr), 365);

//				//$expire_time = time() - 60 * 60 * 24 * 7;
//				if ($database_location->where("id = $qid OR add_time < " . (time() - 60 * 60 * 24))->delete()) {
//					setcookie('Location_qrcode[id]', '', time() - 60 * 60 * 24);
//					setcookie('Location_qrcode[ticket]', '', time() - 60 * 60 * 24);
//					setcookie('Location_qrcode[status]', '', time() - 60 * 60 * 24);
//				}

				// 用户不存在时注册
				//$this->user_check($now_qrcode['openid']);

				// 登陆
				if (!$_SESSION['user']) {
					//session_write_close();
					$result = M('User')->autologin('openid', $now_qrcode['openid']);
					//session_start();
					$_SESSION['user'] = $result['user'];

					// 用户无店，添加到当前店主的下线
					if ($this->store_session && $this->store_session['uid'] != $result['uid'] &&
						!$result['user']['stores'] && !$result['user']['parent_uid']
					) {
						// save 上线, 赠送积分
						M('User')->saveParent($result['uid'], $this->store_session['uid']);
//						D('User')->where(array('uid' => $result['uid']))
//							->save(array('parent_uid' => $this->store_session['uid']));
					}
				}

				$return = array(
					'errcode' => 0,
					'errmsg'  => 'location_ok',
					'lng'     => $now_qrcode['lng'],
					'lat'     => $now_qrcode['lat'],
					'pre'     => $now_qrcode['pre']
				);

//				$store = M('Store')->getStoresByUid($_SESSION['user']['uid'], 1);
//				if($store['store_list']) {
//					$return['store_id'] = $store['store_list'][0]['store_id'];
//				}

				//登录成功
				echo json_encode($return);
			}
			else {
				echo json_encode(array('errcode' => 0, 'errmsg' => '1'));
			}
		}
		else {
//			setcookie('Location_qrcode[id]', '', time() - 60 * 60 * 24);
//			setcookie('Location_qrcode[ticket]', '', time() - 60 * 60 * 24);
//			setcookie('Location_qrcode[status]', '', time() - 60 * 60 * 24);
			echo json_encode(array('errcode' => 1, 'errmsg' => 'false'));
		}
	}

//	// 关注时获取粉丝信息增加
//	private function user_check($openid)
//	{
////		$access_token_array = M('Access_token')->get_access_token();
////		$access_token = $access_token_array['access_token'];
//		// 微信授权获得 access_token
//		import('WechatApi');
//		$tokenObj = new WechatApi(array('appid' => option('config.wechat_appid'), 'appsecret' => option('config.wechat_appsecret')));
//		$access_token = $tokenObj->get_access_token();
//		if ($access_token['errcode']) {
//			return array('error_code' => true,
//			             'msg'        => '发生错误：错误代码 ' . $access_token['errcode'] . '，微信返回错误信息：' .
//				             $access_token['errmsg']);
//		}
//
//		$url = 'https://api.weixin.qq.com/cgi-bin/user/info?openid=' . $openid . '&access_token=' . $access_token;
//		$content = $this->curlGet($url);
//		$classData = json_decode($content);
//
//		if ($classData->subscribe && $classData->subscribe == 1) {
//			$user = D('User')->where(array('openid' => $classData->openid))->find();
//			$data = array();
//			$data['nickname'] = str_replace(array("'", "\\"), array(''), $classData->nickname);
//
//
//			$data['last_time'] = time();
//			$data['last_ip'] = get_client_ip(1);
//			$data['avatar'] = $classData->headimgurl;
//			$data['is_weixin'] = 1;
//			if (empty($user)) {
//				$data['reg_time'] = time();
//				$data['openid'] = $classData->openid;
//				$data['reg_ip'] = get_client_ip(1);
//				D('User')->add($data);
//
//			}
//			else {
//				D('User')->where(array('openid' => $openid))->data($data)->save();
//			}
//		}
//	}
//
//	function curlGet($url)
//	{
//		$ch = curl_init();
//		$header[] = "Accept-Charset: utf-8";
//		curl_setopt($ch, CURLOPT_URL, $url);
//		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
//		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
//		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
//		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
//		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//		$temp = curl_exec($ch);
//
//		return $temp;
//	}
}