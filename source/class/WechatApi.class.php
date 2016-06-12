<?php

class WechatApi
{
	public $appid = '';
	public $appsecret = '';
	public $error = array();

	//构造函数获取access_token
	function __construct($config)
	{
		$this->appid = $config['appid'];
		$this->appsecret = $config['appsecret'];
	}

	public function get_access_token($type = 0)
	{
		$access_obj = D('Access_token')->where('1')->find();
		$now = intval(time());
		if (!$this->checkTokenExpires($access_obj['access_token']) && $access_obj &&
			intval($access_obj['expires_in']) > $now
		) {
			return array('errcode' => 0, 'access_token' => $access_obj['access_token']);
		}

		$json = $this->curlGet('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' .
			trim($this->appid) . '&secret=' . trim($this->appsecret));
//		// 统计
//		M('Api_count')->visit('token');

		$json = json_decode($json);
		if ($json->errmsg) {
			return array('errcode' => $json->errcode, 'errmsg' => $json->errmsg);
		}

		if ($access_obj) {
			D('Access_token')->where(array('id' => $access_obj['id']))
				->data(
					array(
						'access_token' => $json->access_token,
						'expires_in'   => intval($json->expires_in) + $now,
						'time'         => intval($access_obj['time']) + 1,
					)
				)->save();
		}
		else {
			D('Access_token')->data(
				array(
					'access_token' => $json->access_token,
					'expires_in'   => intval($json->expires_in) + $now,
					'time'         => 1
				)
			)->add();
		}

		return array('errcode' => 0, 'access_token' => $json->access_token);
	}

	public function checkTokenExpires($token)
	{
		$url_get = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=' . $token;
//		// 统计
//		M('Api_count')->visit('getcallbackip');
		$res = json_decode($this->curlGet($url_get), true);
		if (!empty($res['errcode']) && $res['errcode'] > 0) {
			return true;
		}
		else {
			return false;
		}
	}

	function curlGet($url)
	{
		$ch = curl_init();
		$header = array("Accept-Charset: utf-8");
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);

		return $temp;
	}
}