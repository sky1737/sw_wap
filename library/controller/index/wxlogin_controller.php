<?php

/**
 * 微信登录
 */
class wxlogin_controller extends base_controller
{
	public function ajax_weixin_login()
	{
		$database_login_qrcode = D('Login_qrcode');
		$condition_login_qrcode['id'] = $_GET['qrcode_id'];
		$now_qrcode = $database_login_qrcode->field('`uid`')->where($condition_login_qrcode)->find();
		if ($now_qrcode['uid']) {
			$database_login_qrcode->where($condition_login_qrcode)->delete();
			$result = M('User')->autologin('uid', $now_qrcode['uid']);
			if (!$result['error_code']) {
				$_SESSION['user'] = $result['user'];
				$_SESSION['openid'] = $result['user']['openid'];

				// 用户无店，添加到当前店主的下线
				if ($this->store_session && $this->store_session['uid'] != $result['uid'] &&
					!$result['user']['stores'] && !$result['user']['parent_uid']
				) {
					M('User')->saveParent($result['uid'], $this->store_session['uid']);
//					D('User')->where(array('uid' => $result['uid']))
//						->save(array('parent_uid' => $this->store_session['uid']));
				}

				if ($result['user']['openid']) {
					$lng_lat = D('Location')->where(array('openid' => $result['user']['openid']))->find();
					if ($lng_lat) {
						// 写入cookie
						$cookie_arr = array(
							'long' => $lng_lat['lng'],
							'lat' => $lng_lat['lat'],
							'timestamp' => time()
						);
						cookies::put("Web_user", json_encode($cookie_arr), 365);
					}
				}

				$store = D('Store')->where(array('uid' => $_SESSION['user']['uid'], 'status' => 1))->find();
				$_SESSION['store'] = $store;

                $open_self = D('Agent')->where(array('agent_id' => $_SESSION['store']['agent_id']))->find();

                if($open_self['open_self']){
                    exit('supplier');
                }else{
                    exit('true');
                }
			}
			else if ($result['error_code'] == 1001) {
				exit('no_user');
			}
			else if ($result['error_code']) {
				exit('false');
			}
		}
	}
}