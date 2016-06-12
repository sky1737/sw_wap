<?php

class WeixinAction extends Action
{
	public $config = '';

	public function _initialize()
	{
		$this->config = D('Config')->get_config();
		C('config', $this->config);
		//Log::write('Weixin:config:'.json_encode($this->config));
	}

	public function index()
	{
		$wechat = new Wechat($this->config);
		$data = $wechat->request();

		Log::write('wechat:request:data:' . $data['FromUserName'] . ':' . json_encode($data), 'INFO');
		list($content, $type) = $this->reply($data);

		if ($content) {
			$wechat->response($content, $type);
		}
		else {
			exit();
		}
	}

	private function reply($data)
	{
//		$keyword = (isset($data['Content']) ? $data['Content'] : (isset($data['EventKey']) ? $data['EventKey'] : ''));
//		$mer_id = 0;
		if ($data['MsgType'] == 'event') {
			$id = isset($data['EventKey']) ? $data['EventKey'] : '';
			Log::write('wechat:request:event:' . $data['FromUserName'] . ':' . json_encode($data), 'INFO');
			switch (strtoupper($data['Event'])) {
				case 'SCAN':
					// tpl qrcode
					// {"ToUserName":"gh_fd2baad048ae","FromUserName":"oAiAav6KRhydkDunotbq3KEdkuSo","CreateTime":"1458138845","MsgType":"event","Event":"SCAN","EventKey":"19903","Ticket":"gQFn8DoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xLzlrUU1wN3ZtaDUzX3l0dWNCMnJHAAIEyW7pVgMEgDoJAA=="}
					return $this->scan($id, $data['FromUserName']);

				case 'CLICK':
					$return = $this->special_keyword($id, $data);

					return $return;

				case 'SUBSCRIBE':
					// 获取微信用户信息
					$register = $this->register($data['FromUserName']);
					if ($register['err_code']) {
						return array($register['err_msg'], 'text');
					}

					//$this->route();
					if (isset($data['Ticket'])) {
						$id = substr($data['EventKey'], 8);

						return $this->scan($id, $data['FromUserName']);
					}

					$first = D('First')->field(true)->find();
					if ($first) {
						if ($first['type'] == 0) {
							return array($first['content'], 'text');
						}
						else if ($first['type'] == 1) {
							$return[] =
								array($first['title'], $first['info'], getAttachmentUrl($first['pic']), $first['url']);

							return array($return, 'news');
						}
						else if ($first['type'] == 2) {
							if ($first['fromid'] == 1) {
								return $this->special_keyword('首页', $data);
							}
							else if ($first['fromid'] == 2) {
								return $this->special_keyword('团购', $data);
							}
							else {
								return $this->special_keyword('订餐', $data);
							}
						}
						else if ($first['type'] == 3) {
							$now = time();
							$sql = 'SELECT g.* FROM ' . C('DB_PREFIX') . 'group as g INNER JOIN ' . C('DB_PREFIX') .
								'merchant as m ON m.mer_id=g.mer_id WHERE m.status=1 AND g.begin_time<\'' . $now .
								'\' AND g.end_time>\'' . $now .
								'\' AND g.status=1 ORDER BY g.index_sort DESC LIMIT 0,9';
							$mode = new Model();
							$group_list = $mode->query($sql);
							$group_image_class = new group_image();

							foreach ($group_list as $g) {
								$tmp_pic_arr = explode(';', $g['pic']);
								$image = $group_image_class->get_image_by_path($tmp_pic_arr[0], 's');
								$return[] = array('[团购]' . $g['s_name'], $g['name'], $image,
									$this->config['site_url'] . '/wap.php?g=Wap&c=Group&a=detail&group_id=' .
									$g['group_id']);
							}

							return array($return, 'news');
						}
					}
					else {
						return array('感谢您的关注，我们将竭诚为您服务！', 'text');
					}

				case 'UNSUBSCRIBE':
//					D('User')->where(array('openid' => $data['FromUserName']))->save(array('is_follow' => 0));
//					$this->route();
					return array('BYE-BYE', 'text');

				case 'LOCATION':
					//data:{"ToUserName":"gh_5fecf38b99ed","FromUserName":"otzaAs8LgJfQj3q9uGP7wjbZ3lok","CreateTime":"1450590884","MsgType":"event","Event":"LOCATION","Latitude":"30.586632","Longitude":"114.343948","Precision":"65.000000"}
					// 减小服务器压力
					if (!isset($_SESSION['location_' . $data['FromUserName']])) {
						$location_lng_lat = D('Location');
						if ($long_lat =
							$location_lng_lat->field(true)->where(array('openid' => $data['FromUserName']))->find()
						) {
							$location_lng_lat->where(array('openid' => $data['FromUserName']))
								->save(array('lng' => $data['Longitude'],
									'lat' => $data['Latitude'],
									'pre' => $data['Precision'],
									'dateline' => time()));
						}
						else {
							$location_lng_lat->add(
								array('lng' => $data['Longitude'],
									'lat' => $data['Latitude'],
									'pre' => $data['Precision'],
									'dateline' => time(),
									'openid' => $data['FromUserName']));
						}

						$location_qrcode = D('Location_qrcode');
						if ($location =
							$location_qrcode->where(array('openid' => $data['FromUserName'], 'status' => 1))->find()
						) {
							$location_qrcode->where(
								array('openid' => $data['FromUserName'], 'status' => 1))
								->save(
									array('lng' => $data['Longitude'],
										'lat' => $data['Latitude'],
										'pre' => $data['Precision'],
										'status' => 2));

							return array('已成功获取了您的地理位置。', 'text');
						}
						$_SESSION['location_' . $data['FromUserName']] = $data['Longitude'];
					}
					break;
				default:
			}
		}
		else if ($data['MsgType'] == 'text') {
			$content = $data['Content'];
			$return = $this->special_keyword($content, $data);

			if (strtolower(trim($content)) == 'go') {
				$t_data = $this->route();

				if ($return[0] == '亲，暂时没有找到与“' . $content . '”相关的内容！') {
					header('Content-type: text/xml');
					exit($t_data);
				}
			}

			return $return;
		}
		else if ($data['MsgType'] == 'location') {
			// wechat:request:data:{"ToUserName":"gh_5fecf38b99ed","FromUserName":"otzaAs31WUGlpMsDGcQvuNi3wUDU","CreateTime":"1450759622","MsgType":"location","Location_X":"30.584263","Location_Y":"114.349303","Scale":"15","Label":"\u6b66\u6c49\u5e02\u6d2a\u5c71\u533a\u5f90\u4e1c\u7fa4\u661f\u57ce\u5357\u5185\u5eca(\u5f90\u4e1c\u5927\u8857\u5357)","MsgId":"6230965131250089163"}
//			if(!isset($_SESSION['location_' . $data['FromUserName']])) {
//				$location_lng_lat = D('Location');
//				if($long_lat =
//					$location_lng_lat->field(true)->where(array('openid' => $data['FromUserName']))->find()
//				) {
//					$location_lng_lat->where(array('openid' => $data['FromUserName']))
//						->save(array('lng'      => $data['Location_X'],
//						             'lat'      => $data['Location_Y'],
//						             'pre'      => $data['Scale'],
//						             'dateline' => time()));
//				}
//				else {
//					$location_lng_lat->add(
//						array('lng'      => $data['Location_X'],
//						      'lat'      => $data['Location_Y'],
//						      'pre'      => $data['Scale'],
//						      'dateline' => time(),
//						      'openid'   => $data['FromUserName']));
//				}
//
//				$location_qrcode = D('Location_qrcode');
//				if($location =
//					$location_qrcode->where(array('openid' => $data['FromUserName'], 'status' => 1))->find()
//				) {
//					$location_qrcode->where(
//						array('openid' => $data['FromUserName'], 'status' => 1))
//						->save(
//							array('lng'    => $data['Location_X'],
//							      'lat'    => $data['Location_Y'],
//							      'pre'    => $data['Scale'],
//							      'status' => 2));
//
//					//$_SESSION['lng_' . $data['Location_X']] = $data['Location_X'];
//					// return array('已成功获取了您的地理位置。', 'text');
//				}
//				$_SESSION['location_' . $data['FromUserName']] = $data['Longitude'];
//			}

//			import('@.ORG.longlat');
//			$longlat_class = new longlat();
//			$location2 = $longlat_class->gpsToBaidu($data['Location_X'], $data['Location_Y']);
//			$x = $location2['lat'];
//			$y = $location2['lng'];
//			$meals = D('Merchant_store')->field('*, ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN((' . $x .
//				'*PI()/180-`lat`*PI()/180)/2),2)+COS(' . $x . '*PI()/180)*COS(`lat`*PI()/180)*POW(SIN((' . $y .
//				'*PI()/180-`long`*PI()/180)/2),2)))*1000) AS juli')->where('`have_meal`=1')->order('juli ASC')
//				->limit('0, 10')->select();
//			$store_image_class = new store_image();
//
//			foreach ($meals as $meal) {
//				$images = $store_image_class->get_allImage_by_path($meal['pic_info']);
//				$meal['image'] = $images ? array_shift($images) : '';
//				$len = (1000 <= $meal['juli'] ? number_format($meal['juli'] / 1000, 2) . '千米' : $meal['juli'] . '米');
//				$return[] =
//					array($meal['name'] . '[' . $meal['adress'] . ']约' . $len, $meal['txt_info'], $meal['image'],
//						$this->config['site_url'] . '/wap.php?g=Wap&c=Meal&a=menu&mer_id=' . $meal['mer_id'] .
//						'&store_id=' . $meal['store_id']);
//			}
//
//			$meals = D('Merchant_store')->field('*, ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN((' . $x .
//				'*PI()/180-`lat`*PI()/180)/2),2)+COS(' . $x . '*PI()/180)*COS(`lat`*PI()/180)*POW(SIN((' . $y .
//				'*PI()/180-`long`*PI()/180)/2),2)))*1000) AS juli')->where('`have_group`=1')->order('juli ASC')
//				->limit('0, 10')->select();
//			$store_image_class = new store_image();
//
//			foreach ($meals as $meal) {
//				$images = $store_image_class->get_allImage_by_path($meal['pic_info']);
//				$meal['image'] = $images ? array_shift($images) : '';
//				$len = (1000 <= $meal['juli'] ? number_format($meal['juli'] / 1000, 2) . '千米' : $meal['juli'] . '米');
//				$return[] =
//					array($meal['name'] . '[' . $meal['adress'] . ']约' . $len, $meal['txt_info'], $meal['image'],
//						$this->config['site_url'] . '/wap.php?g=Wap&c=Group&a=shop&store_id=' . $meal['store_id']);
//			}
//
//			if (10 < count($return)) {
//				$return = array_slice($return, 0, 9);
//			}
//
//			return array($return, 'news');
			return array('地理位置信息已获取！', 'text');
		}

		return false;
	}

	/**
	 * $data
	 * {"ToUserName":"gh_5fecf38b99ed","FromUserName":"otzaAs31WUGlpMsDGcQvuNi3wUDU","CreateTime":"1450327283","MsgType":"event","Event":"SCAN","EventKey":"449","Ticket":"gQGb7zoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xLzVralpxWVhseFFlODBfU3F4R1p6AAIE7TxyVgMEgDoJAA=="}
	 * @param $id $data['eventkey']
	 * @param string $openid $data['FromUserName']
	 * @return array
	 */
	private function scan($id, $openid = '')
	{
		//if ((300000000 < $id) && $openid) {
		//	//$id -= 300000000;
		//	//
		//	//if ($order = M('Order')->field('`order_no`,`status`')->where(array('order_id' => $id))->find()) {
		//	//	if ($order['status'] < 2) {
		//	//		return array('<a href="' . $this->config['wap_site_url'] . '/pay.php?id=' .
		//	//			$this->config['orderid_prefix'] . $order['order_no'] . '">查看订单详情</a>', 'text');
		//	//	}
		//	//	else {
		//	//		return array('<a href="' . $this->config['wap_site_url'] . '/order.php?orderno=' .
		//	//			$this->config['orderid_prefix'] . $order['order_no'] . '">查看订单详情</a>', 'text');
		//	//	}
		//	//}
		//	//else {
		//	//	return array('获取不到该订单信息', 'text');
		//	//}
		//}
		//else {
		//	if ((290000000 < $id) && $openid) {
		//		//$store_id -= 290000000;
		//		//D('Service')->data(array('store_id' => $store_id, 'openid' => $openid))->add();
		//		//
		//		//return array('登陆成功', 'text');
		//	}
		//	else {
		//		if ((100000000 < $id) && $openid) {
		//			//if ($user = D('User')->field('uid')->where(array('openid' => $openid))->find()) {
		//			//	D('Login_qrcode')->where(array('id' => $id))->save(array('uid' => $user['uid']));
		//			//
		//			//	return array('登陆成功', 'text');
		//			//}
		//			//else {
		//			//	D('Login_qrcode')->where(array('id' => $id))->save(array('uid' => -1));
		//			//	$return[] = array('点击授权登录', '', $this->config['site_logo'],
		//			//		$this->config['wap_site_url'] . '/weixin_bind.php?qrcode_id=' . $id);
		//			//
		//			//	return array($return, 'news');
		//			//}
		//		}
		//	}
		//}
		Log::write('scan:id:' . $id . ':openid:' . $openid, 'INFO');
		if ($recognition = M('Recognition')->where(array('id' => $id))->find()) {
			Log::write('recognition:' . json_encode($recognition), 'INFO');
			// 判断注册
			$register = $this->register($openid, $recognition['store_id']);
			if ($register['err_code']) {
				return array($register['err_msg'], 'text');
			}

			$return[] = array(
				$this->config['seo_title'],
				$this->config['seo_description'], // 介绍
				$this->config['site_logo'],
				$this->config['wap_site_url']
			);

			switch ($recognition['third_type']) {
//				case 'twiker':
//					$db_user = D('User');
//					$is_twiker = $db_user->field('is_twiker')->where(array('openid' => $openid))->find();
//					if($is_twiker) {
//						return array('您已是推客身份！', 'text');
//					}
//
//					$parent_uid = D('Twiker_qrcode')->field('uid')
//						->where(array('id' => $recognition['third_id']))->find();
//					if($parent_uid) {
//						$db_user->where(array('openid' => $openid))
//							->save(array('parent_uid' => $parent_uid));
//
//						return array('注册成功！', 'text');
//					}
//
//					break;
				case 'auth':
					$auth_db = M('Store_auth');
					if ($auth =
						$auth_db->where(array('store_id' => $recognition['third_id'], 'uid' => $register['user_id']))
							->find()
					) {
						Log::write(json_encode($auth), 'INFO');
						if ($auth['status'] == 1) {
							return array('您的授权登录已经成功了！', 'text');
						}
						else {
							return array('扫码成功，请等待店主审批！', 'text');
						}
					}
					else {
						$auth['uath_id'] = $auth_db->add(array(
							'store_id' => $recognition['third_id'],
							'uid' => $register['user_id'],
							'status' => 0,
							'add_time' => time()));
						if ($auth['auth_id']) {
							return array('扫码成功，请等待店主审批通过！', 'text');
						}
						else {
							return array('授权失败，请重试或联系管理员处理。', 'text');
						}
					}
					break;

				case 'goods':
				case 'product':
					if ($product = M('Product')->where(array('product_id' => $recognition['third_id']))->find()) {
						Log::write('product.info:' . json_encode($product), 'INFO');
						$return[] = array(
							$product['name'],
							$product['intro'] . '点击买！买！买！',
							getAttachmentUrl($product['image']),
							$this->config['wap_site_url'] . '/good.php?id=' . $product['product_id'] . '&twid=' .
							$recognition['store_id']
						);
						Log::write('return.info' . json_encode($return));

						return array($return, 'news');
					}

					return array('对不起，获取产品信息失败！', 'text');

				case 'agent':
				case 'store':
					if ($store = M('Store')->where(array('store_id' => $recognition['third_id']))->find()) {
						$return[] = array(
							$store['name'],
							$store['intro'],
							getAttachmentUrl($store['logo']),
							$this->config['wap_site_url'] . '/?twid=' . $store['store_id']);

						return array($return, 'news');
					}

					return array('对不起，获取商城信息失败！', 'text');

				case 'register':
				case 'login':
					$user = D('User')->where(array('openid' => $openid))->find();
					D('Login_qrcode')->where(array('id' => $recognition['third_id']))
						->save(array('uid' => $user['uid']));

					if ($user['stores']) {
						$store = M('Store')->where(array('uid' => $user['uid'], 'status' => 1))->find();
						if ($store) {
							$return[] = array(
								"亲爱的{$user['nickname']}，您的当前为分销店主" . ($store['agent_id'] ? '和分销代理商' : ''),
								'',
//								"您的当前为分销店主" . ($store['agent_approve'] ? '和分销代理商' : '') .
//								"。您的分销店“{$store['name']}”电脑访问地址：{$store['store_id']}.{$this->config['site_domain']}，微信请点击阅读全文...",
								getAttachmentUrl($store['logo']),
								$this->config['wap_site_url']
							);

							return array($return, 'news');
						}
					}

					$return[] = array(
						"亲爱的{$user['nickname']}，您的当前为普通用户。赶紧注册成为分销店主，赚取佣金吧...",
						'',
						getAttachmentUrl($user['avatar']),
						$this->config['wap_site_url']
					);

					return array($return, 'news');

				case 'location':
					if ($location = D('Location_qrcode')->where(array('id' => $recognition['third_id']))->find()) {
						// 扫码成功
						if ($location['status'] == 0) {
							if ($lng_lat = D('Location')->where(array('openid' => $openid))->find()) {
								D('Location_qrcode')->where(array('id' => $recognition['third_id']))->save(
									array(
										'lng' => $lng_lat['lng'],
										'lat' => $lng_lat['lat'],
										'pre' => $lng_lat['pre'],
										'openid' => $openid,
										'status' => 2
									)
								);

								return array('获取地址成功！', 'text');
							}
							else {
								D('Location_qrcode')->where(array('id' => $recognition['third_id']))
									->save(array('status' => 1,
										'openid' => $openid));

								return array('正在获取您的地理位置，如果十秒内没有响应，请点击左下角的加号将您的“位置”发送给我们！', 'text');
							}
						}
						// 获取地理位置
						if ($location['status'] == 1) {
							if ($lng_lat = D('Location')->where(array('openid' => $openid))->find()) {
								D('Location_qrcode')->where(array('id' => $recognition['third_id']))->save(
									array(
										'lng' => $lng_lat['lng'],
										'lat' => $lng_lat['lat'],
										'pre' => $lng_lat['pre'],
										'status' => 2
									)
								);

								return array('获取地址成功！', 'text');
							}
						}
					}
					break;

				case 'order':
					if ($order = M('Order')->where(array('order_id' => $recognition['third_id']))->find()) {
						$avatar = M('User')->where(array('uid' => $order['uid'], 'status' => 1))->getField('avatar');
						$ss = '';
						$url = '';
						if ($order['status'] < 2) {
							$ss = '订单总金额 ' . $order['total'] . ' 元，点击完成支付';
							$url = $this->config['wap_site_url'] . '/pay.php?id=' . $this->config['orderid_prefix'] . $order['order_no'];
						}
						else {
							$ss = '订单总金额 ' . $order['total'] . ' 元，实际支付 ' . $order['pay_money'] . ' 元，点击查看详情';
							$url = $this->config['wap_site_url'] . '/order.php?orderno=' . $this->config['orderid_prefix'] . $order['order_no'];
						}

						$return[] = array($ss, '', $avatar, $url);

						return array($return, 'news');
					}
					else {
						return array('对不起，获取订单信息失败！', 'text');
					}

//				case 'group':
//					$now_group = D('Group')->field(true)->where(array('group_id' => $recognition['third_id']))->find();
//					$group_image_class = new group_image();
//					$tmp_pic_arr = explode(';', $now_group['pic']);
//					$image = $group_image_class->get_image_by_path($tmp_pic_arr[0], 's');
//					$return[] = array('[团购]' . $now_group['s_name'], $now_group['name'], $image,
//						$this->config['site_url'] . '/wap.php?g=Wap&c=Group&a=detail&group_id=' .
//						$now_group['group_id']);
//					$this->saverelation($openid, $now_group['mer_id'], 0);
//					$return = $this->other_message($return, $now_group['mer_id'], $now_group['group_id']);
//					break;

//				case 'merchant':
//					$now_merchant =
//						D('Merchant')->field(true)->where(array('mer_id' => $recognition['third_id']))->find();
//					$pic = '';
//
//					if($now_merchant['pic_info']) {
//						$images = explode(';', $now_merchant['pic_info']);
//						$merchant_image_class = new merchant_image();
//						$images = explode(';', $images[0]);
//						$pic = $merchant_image_class->get_image_by_path($images[0]);
//					}
//
//					$return[] = array('[商家]' . $now_merchant['name'], $now_merchant['txt_info'], $pic,
//						$this->config['site_url'] . '/wap.php?g=Wap&c=Index&a=index&token=' . $recognition['third_id']);
//					$return = $this->other_message($return, $now_merchant['mer_id']);
//					$this->saverelation($openid, $now_merchant['mer_id'], 1);
//					break;

//				case 'meal':
//					$now_store =
//						D('Merchant_store')->field(true)->where(array('store_id' => $recognition['third_id']))->find();
//
//					if($now_store['have_meal']) {
//						$store_image_class = new store_image();
//						$images = $store_image_class->get_allImage_by_path($now_store['pic_info']);
//						$now_store['image'] = $images ? array_shift($images) : '';
//						$return[] = array('[订餐]' . $now_store['name'], $now_store['txt_info'], $now_store['image'],
//							$this->config['site_url'] . '/wap.php?g=Wap&c=Meal&a=menu&mer_id=' . $now_store['mer_id'] .
//							'&store_id=' . $now_store['store_id']);
//					}
//
//					$this->saverelation($openid, $now_store['mer_id'], 0);
//					$return = $this->other_message($return, $now_store['mer_id'], 0, $now_store['store_id']);
//					break;

//				case 'lottery':
//					$lottery = D('Lottery')->field(true)->where(array('id' => $recognition['third_id']))->find();
//
//					switch ($lottery['type']) {
//						case 1:
//							$return[] = array('[活动]' . $lottery['title'], $lottery['info'],
//								$this->config['site_url'] . $lottery['starpicurl'],
//								$this->config['site_url'] . '/wap.php?c=Lottery&a=index&token=' . $lottery['token'] .
//								'&id=' . $lottery['id']);
//							break;
//
//						case 2:
//							$return[] = array('[活动]' . $lottery['title'], $lottery['info'],
//								$this->config['site_url'] . $lottery['starpicurl'],
//								$this->config['site_url'] . '/wap.php?c=Guajiang&a=index&token=' . $lottery['token'] .
//								'&id=' . $lottery['id']);
//							break;
//
//						case 3:
//							$return[] = array('[活动]' . $lottery['title'], $lottery['info'],
//								$this->config['site_url'] . $lottery['starpicurl'],
//								$this->config['site_url'] . '/wap.php?c=Coupon&a=index&token=' . $lottery['token'] .
//								'&id=' . $lottery['id']);
//							break;
//
//						case 4:
//							$return[] = array('[活动]' . $lottery['title'], $lottery['info'],
//								$this->config['site_url'] . $lottery['starpicurl'],
//								$this->config['site_url'] . '/wap.php?c=LuckyFruit&a=index&token=' . $lottery['token'] .
//								'&id=' . $lottery['id']);
//							break;
//
//						case 5:
//							$return[] = array('[活动]' . $lottery['title'], $lottery['info'],
//								$this->config['site_url'] . $lottery['starpicurl'],
//								$this->config['site_url'] . '/wap.php?c=GoldenEgg&a=index&token=' . $lottery['token'] .
//								'&id=' . $lottery['id']);
//							break;
//					}
//
//					break;
			}
		}

//		if($return) {
//			return array($return, 'news');
//		}

		return array('很抱歉，暂时获取不到该二维码的信息!', 'text');
	}

	private function register($openid, $store_id = 0)
	{
		$database_store = D('Store');
		$parent_uid = 0;
		if ($store_id) {
			$parent_uid = $database_store->where(array('store_id' => $store_id, 'status' => 1))->getField('uid');
		}

		$database_user = D('User');
		$user = $database_user->where(array('openid' => $openid, 'status' => 1))->find();
		if (empty($user)) {
			// 获取用户资料，注册用户
			$appid = $this->config['wechat_appid'];
			$appsecret = $this->config['wechat_appsecret'];
			if (empty($appid) || empty($appsecret)) {
				return array('err_code' => 1, 'err_msg' => '获取用户信息失败，请联系管理员配置【AppId】和【AppSecret】');
			}

			// 微信授权获得 access_token
			import("WechatApi", './source/class');
			$tokenObj = new WechatApi(array('appid' => $appid, 'appsecret' => $appsecret));
			$access_token = $tokenObj->get_access_token();

			import("Http", './source/class');
			$result = Http::curlGet('https://api.weixin.qq.com/cgi-bin/user/info?access_token=' .
				$access_token['access_token'] . '&openid=' . $openid . '&lang=zh_CN');
			Log::write('UserInfo:Befor:' . $result, 'INFO');
			$result = preg_replace("#(\\\ue[0-9a-f]{3})#ie", "addslashes('\\1')", $result);
			Log::write('UserInfo:After:' . $result, 'INFO');
			$json = json_decode($result, true);
			if ($json['openid']) {
				$user = array(
					'nickname' => $json['nickname'],
					'openid' => $openid,
					'reg_time' => $json['subscribe_time'],
					'login_count' => 1,
					'status' => 1,
					'avatar' => $json['headimgurl'],
					'sex' => $json['sex'],
					'country' => $json['country'],
					'province' => $json['province'],
					'city' => $json['city'],
					'check_phone' => 0,
					'reg_time' => $_SERVER['REQUEST_TIME'],
					'last_time' => $_SERVER['REQUEST_TIME'],
					'reg_ip' => ip2long($_SERVER['REMOTE_ADDR']),
					'last_ip' => ip2long($_SERVER['REMOTE_ADDR']),
					'parent_uid' => $parent_uid,
				);
				$user['uid'] = $database_user->add($user);
				if (!$user['uid'])
					return array('err_code' => 1, 'err_msg' => '注册用户失败！');
			}
			else {
				return array('err_code' => 1,
					'err_msg' => '获取用户信息失败：错误代码 ' . $json['errcode'] . '，微信返回错误信息：' . $json['errcode']);
			}
		}

		$agent_id = M('Store')->where(array('uid' => $user['uid'], 'status' => 1))->getField('agent_id');
		if (!$user['parent_uid'] && !$agent_id && $parent_uid && $parent_uid != $user['uid']) {
			// 保存上线
			$database_user->where(array('uid' => $user['uid']))->save(array('parent_uid' => $parent_uid));

			// 推广奖励奖励
			$reward_point = $this->config['promote_reward'] * 1;
			if ($reward_point > 0) {
				$database_user->where(array('uid' => $parent_uid))->setInc('point', $reward_point);
				M('User_income')->add(array(
					'uid' => $parent_uid,
					'order_no' => '',
					'income' => 0.00,
					'point' => $reward_point,
					'type' => 7,
					'add_time' => time(),
					'status' => 1,
					'remarks' => '推荐奖励'));
			}

			$user['parent_uid'] = $parent_uid;
		}

		if ($this->config['auto_create']) {
			$store = $database_store->where(array('uid' => $user['uid'], 'status' => 1))->find();
			if (empty($store)) {
				$data = array('uid' => $user['uid'],
					'name' => $user['nickname'] . '的商城',
					'logo' => $user['avatar'],
					'date_added' => time(),
					'drp_supplier_id' => 0,
					'open_logistics' => 1,
					'offline_payment' => 0,
					'open_friend' => 0,
					'status' => 1
				);
				// 自动创建店铺默认不审核
				//			if($this->config['ischeck_store'] * 1) {
				//				$data['status'] = 2;
				//			}
				$store['store_id'] = $database_store->add($data);
				if ($store['store_id'])
					$database_user->where(array('uid' => $user['uid']))->save(array('stores' => 1));
				else
					return array('err_code' => 1, 'errmsg' => '自动创建店铺失败！');
			}
		}

		return array('err_code' => 0, 'user_id' => $user['uid']);
	}

	private function special_keyword($key, $data = array())
	{
		$return = array();
		if (($key == '附近团购') || ($key == '附近订餐')) {
			$dateline = time() - (3600 * 2);

			if ($long_lat =
				D('User_long_lat')->field(true)->where('`open_id`=\'' . $data['FromUserName'] . '\' AND `dateline`>\'' .
					$dateline . '\'')->find()
			) {
				import('@.ORG.longlat');
				$longlat_class = new longlat();
				$location2 = $longlat_class->gpsToBaidu($long_lat['lat'], $long_lat['long']);
				$x = $location2['lat'];
				$y = $location2['lng'];

				if ($key == '附近订餐') {
					$meals = D('Merchant_store')->field('*, ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN((' . $x .
						'*PI()/180-`lat`*PI()/180)/2),2)+COS(' . $x . '*PI()/180)*COS(`lat`*PI()/180)*POW(SIN((' . $y .
						'*PI()/180-`long`*PI()/180)/2),2)))*1000) AS juli')->where('`have_meal`=1')->order('juli ASC')
						->limit('0, 10')->select();
					$store_image_class = new store_image();

					foreach ($meals as $meal) {
						$images = $store_image_class->get_allImage_by_path($meal['pic_info']);
						$meal['image'] = $images ? array_shift($images) : '';
						$len = (1000 <= $meal['juli']
							? number_format($meal['juli'] / 1000, 1) . '千米' : $meal['juli'] . '米');
						$return[] = array($meal['name'] . '[' . $meal['adress'] . ']约' . $len, $meal['txt_info'],
							$meal['image'],
							$this->config['site_url'] . '/wap.php?g=Wap&c=Meal&a=menu&mer_id=' . $meal['mer_id'] .
							'&store_id=' . $meal['store_id']);
					}
				}
				else {
					$meals = D('Merchant_store')->field('*, ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN((' . $x .
						'*PI()/180-`lat`*PI()/180)/2),2)+COS(' . $x . '*PI()/180)*COS(`lat`*PI()/180)*POW(SIN((' . $y .
						'*PI()/180-`long`*PI()/180)/2),2)))*1000) AS juli')->where('`have_group`=1')->order('juli ASC')
						->limit('0, 10')->select();
					$store_image_class = new store_image();

					foreach ($meals as $meal) {
						$images = $store_image_class->get_allImage_by_path($meal['pic_info']);
						$meal['image'] = $images ? array_shift($images) : '';
						$len = (1000 <= $meal['juli']
							? number_format($meal['juli'] / 1000, 1) . '千米' : $meal['juli'] . '米');
						$return[] = array($meal['name'] . '[' . $meal['adress'] . ']约' . $len, $meal['txt_info'],
							$meal['image'],
							$this->config['site_url'] . '/wap.php?g=Wap&c=Group&a=shop&store_id=' . $meal['store_id']);
					}
				}
			}

			if ($return) {
				return array($return, 'news');
			}
			else {
				return array('请给我发送您的地理位置：对话框右下角点击＋号，然后点击“位置”。', 'text');
			}
		}

		if ($key == '交友') {
			$return[] = array('交友约会', '结交一些朋友吃喝玩乐', $this->config['site_url'] . '/static/images/jiaoyou.jpg',
				$this->config['site_url'] . '/wap.php?c=Invitation&a=datelist');

			return array($return, 'news');
		}

		$platform = D('Platform')->field(true)->where(array('key' => $key))->find();

		if ($platform) {
			$return[] =
				array($platform['title'], $platform['info'], getAttachmentUrl($platform['pic']), $platform['url']);
		}
		else {
			$keys =
				D('Keywords')->field(true)->where(array('keyword' => $key))->order('id DESC')->limit('0,9')->select();
			$lotteryids = $mealids = $groupids = array();

			foreach ($keys as $k) {
				if ($k['third_type'] == 'group') {
					$groupids[] = $k['third_id'];
				}
				else if ($k['third_type'] == 'Merchant_store') {
					$mealids[] = $k['third_id'];
				}
				else if ($k['third_type'] == 'lottery') {
					$lotteryids[] = $k['third_id'];
				}
			}

			if ($groupids) {
				$list = D('Group')->field(true)->where(array(
					'group_id' => array('in', $groupids)
				))->select();
				$group_image_class = new group_image();

				foreach ($list as $li) {
					$image = $group_image_class->get_image_by_path($li['pic'], 's');
					$return[] = array($li['s_name'], $li['name'], $image,
						$this->config['site_url'] . '/wap.php?g=Wap&c=Group&a=detail&group_id=' . $li['group_id']);
				}
			}

			if ($mealids) {
				$list = D('Merchant_store')->field(true)->where(array(
					'store_id' => array('in', $mealids)
				))->select();
				$store_image_class = new store_image();

				foreach ($list as $now_store) {
					$images = $store_image_class->get_allImage_by_path($now_store['pic_info']);
					$now_store['image'] = $images ? array_shift($images) : '';

					if ($now_store['have_meal']) {
						$return[] = array($now_store['name'], $now_store['txt_info'], $now_store['image'],
							$this->config['site_url'] . '/wap.php?g=Wap&c=Meal&a=menu&mer_id=' . $now_store['mer_id'] .
							'&store_id=' . $now_store['store_id']);
					}
					else {
						$return[] = array($now_store['name'], $now_store['txt_info'], $now_store['image'],
							$this->config['site_url'] . '/wap.php?g=Wap&c=Group&a=shop&store_id=' .
							$now_store['store_id']);
					}
				}
			}

			if ($lotteryids) {
				$lotterys = D('Lottery')->field(true)->where(array(
					'id' => array('in', $lotteryids),
					'statdate' => array('lt', time()),
					'enddate' => array('gt', time())
				))->select();

				foreach ($lotterys as $lottery) {
					switch ($lottery['type']) {
						case 1:
							$return[] = array('[活动]' . $lottery['title'], $lottery['info'],
								$this->config['site_url'] . $lottery['starpicurl'],
								$this->config['site_url'] . '/wap.php?c=Lottery&a=index&token=' . $lottery['token'] .
								'&id=' . $lottery['id']);
							break;

						case 2:
							$return[] = array('[活动]' . $lottery['title'], $lottery['info'],
								$this->config['site_url'] . $lottery['starpicurl'],
								$this->config['site_url'] . '/wap.php?c=Guajiang&a=index&token=' . $lottery['token'] .
								'&id=' . $lottery['id']);
							break;

						case 3:
							$return[] = array('[活动]' . $lottery['title'], $lottery['info'],
								$this->config['site_url'] . $lottery['starpicurl'],
								$this->config['site_url'] . '/wap.php?c=Coupon&a=index&token=' . $lottery['token'] .
								'&id=' . $lottery['id']);
							break;

						case 4:
							$return[] = array('[活动]' . $lottery['title'], $lottery['info'],
								$this->config['site_url'] . $lottery['starpicurl'],
								$this->config['site_url'] . '/wap.php?c=LuckyFruit&a=index&token=' . $lottery['token'] .
								'&id=' . $lottery['id']);
							break;

						case 5:
							$return[] = array('[活动]' . $lottery['title'], $lottery['info'],
								$this->config['site_url'] . $lottery['starpicurl'],
								$this->config['site_url'] . '/wap.php?c=GoldenEgg&a=index&token=' . $lottery['token'] .
								'&id=' . $lottery['id']);
							break;
					}
				}
			}
		}

		if ($return) {
			return array($return, 'news');
		}

		return array('亲，暂时没有找到与“' . $key . '”相关的内容！请更换内容。', 'text');
	}

	private function route()
	{
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
		$data = $this->api_notice_increment('http://we-cdn.net', $xml);

		return $data;
	}

	private function api_notice_increment($url, $data)
	{
		$ch = curl_init();
		$headers = array('User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:14.0) Gecko/20100101 Firefox/14.0.1',
			'Accept-Language: en-us,en;q=0.5', 'Referer:http://mp.weixin.qq.com/', 'Content-type: text/xml');
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$error = curl_errno($ch);
		curl_close($ch);

		if ($error) {
			return false;
		}
		else {
			return $tmpInfo;
		}
	}

	private function other_message($return, $token, $group_id = 0, $store_id = 0)
	{
		$nowtime = time();
		$group_list = D('Group')->field(true)->where('`mer_id`=\'' . $token . '\' AND `group_id`<>\'' . $group_id .
			'\' AND `status`=1 AND `begin_time`<' . $nowtime . ' AND `end_time`>' . $nowtime)->select();
		$group_image_class = new group_image();

		foreach ($group_list as $g) {
			$tmp_pic_arr = explode(';', $g['pic']);
			$image = $group_image_class->get_image_by_path($tmp_pic_arr[0], 's');
			$return[] = array('[团购]' . $g['s_name'], $g['name'], $image,
				$this->config['site_url'] . '/wap.php?g=Wap&c=Group&a=detail&group_id=' . $g['group_id']);
		}

		if (10 < count($return)) {
			return array_slice($return, 0, 9);
		}

		if ($card = D('Member_card_set')->field(true)->where(array('token' => $token))->limit('0,1')->find()) {
			$return[] = array('[会员卡]' . $card['cardname'], $card['msg'], $this->config['site_url'] . $card['logo'],
				$this->config['site_url'] . '/wap.php?c=Card&a=index&token=' . $token);
		}

		if (10 < count($return)) {
			return array_slice($return, 0, 9);
		}

		$lotterys = D('Lottery')->field(true)->where(array(
			'token' => $token,
			'statdate' => array('lt', time()),
			'enddate' => array('gt', time())
		))->select();

		foreach ($lotterys as $lottery) {
			switch ($lottery['type']) {
				case 1:
					$return[] = array('[活动]' . $lottery['title'], $lottery['info'],
						$this->config['site_url'] . $lottery['starpicurl'],
						$this->config['site_url'] . '/wap.php?c=Lottery&a=index&token=' . $token . '&id=' .
						$lottery['id']);
					break;

				case 2:
					$return[] = array('[活动]' . $lottery['title'], $lottery['info'],
						$this->config['site_url'] . $lottery['starpicurl'],
						$this->config['site_url'] . '/wap.php?c=Guajiang&a=index&token=' . $token . '&id=' .
						$lottery['id']);
					break;

				case 3:
					$return[] = array('[活动]' . $lottery['title'], $lottery['info'],
						$this->config['site_url'] . $lottery['starpicurl'],
						$this->config['site_url'] . '/wap.php?c=Coupon&a=index&token=' . $token . '&id=' .
						$lottery['id']);
					break;

				case 4:
					$return[] = array('[活动]' . $lottery['title'], $lottery['info'],
						$this->config['site_url'] . $lottery['starpicurl'],
						$this->config['site_url'] . '/wap.php?c=LuckyFruit&a=index&token=' . $token . '&id=' .
						$lottery['id']);
					break;

				case 5:
					$return[] = array('[活动]' . $lottery['title'], $lottery['info'],
						$this->config['site_url'] . $lottery['starpicurl'],
						$this->config['site_url'] . '/wap.php?c=GoldenEgg&a=index&token=' . $token . '&id=' .
						$lottery['id']);
					break;
			}
		}

		if (10 < count($return)) {
			return array_slice($return, 0, 9);
		}

		$stores = D('Merchant_store')->field(true)->where('`mer_id`=\'' . $token .
			'\' AND `status`=1 AND `have_meal`=1 AND `store_id`<>\'' . $store_id . '\'')->select();
		$store_image_class = new store_image();

		foreach ($stores as $store) {
			if ($store['have_meal']) {
				$images = $store_image_class->get_allImage_by_path($store['pic_info']);
				$img = array_shift($images);
				$return[] = array('[订餐]' . $store['name'], $store['txt_info'], $img,
					$this->config['site_url'] . '/wap.php?c=Meal&a=menu&mer_id=' . $store['mer_id'] . '&store_id=' .
					$store['store_id']);
			}
		}

		if (10 < count($return)) {
			return array_slice($return, 0, 9);
		}
		else {
			return $return;
		}
	}

	private function saverelation($openid, $mer_id, $from_merchant)
	{
		$relation = D('Merchant_user_relation')->field('mer_id')->where(array('openid' => $openid, 'mer_id' => $mer_id))
			->find();
		$where = array('img_num' => 1);

		if (empty($relation)) {
			$relation = array('openid' => $openid, 'mer_id' => $mer_id, 'dateline' => time(),
				'from_merchant' => $from_merchant);
			D('Merchant_user_relation')->add($relation);
			$where['follow_num'] = 1;
			$from_merchant && D('Merchant')->update_group_indexsort($mer_id);
		}
		else {
			if (empty($relation['from_merchant']) && $from_merchant) {
				D('Merchant')->update_group_indexsort($mer_id);
				D('Merchant_user_relation')->where(array('openid' => $openid, 'mer_id' => $mer_id))
					->save(array('from_merchant' => $from_merchant));
			}
		}

		D('Merchant_request')->add_request($mer_id, $where);
	}
}
