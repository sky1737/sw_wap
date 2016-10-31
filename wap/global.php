<?php

/* 手机端公共文件 */
define('TWIKER_PATH', dirname(__FILE__) . '/../');
define('GROUP_NAME', 'wap');
define('IS_SUB_DIR', true);

require_once TWIKER_PATH . 'source/init.php';

// 用户登录
$exclude = array('paynotice.php', 'rechargenotice.php', 'mergepaynotice.php', 'payfornotice.php');
$currentUrl = $_SERVER['REQUEST_URI'];
$isLoged = true;
foreach ($exclude as $v) {
    if (stripos($currentUrl, $v) === false)
        continue;

    $isLoged = false;
    break;
}

if ($isLoged && empty($_SESSION['user'])) {
    $appid = $config['wechat_appid'];
    $appsecret = $config['wechat_appsecret'];

    $openid = $_SESSION['openid'];
    $code = I('get.code');
    if (!$openid || !$_SESSION['oauthed']) {
        if (!$openid && !$code) {
            $custom_url = $config['wap_site_url'] . '/?refer=' . base64_encode($_SERVER['REQUEST_URI']);
            logs($custom_url, 'INFO');
            $oauthUrl =
                'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' .
                $appid . '&redirect_uri=' . urlencode($custom_url) .
                '&response_type=code&scope=snsapi_userinfo&state=oauth#wechat_redirect';

            echo '<h1>正在自动为您登录，请稍候...</h1>' .
                '<script type="text/javascript">location.href="' . $oauthUrl . '";</script>';
            exit();
        }

        $state = I('get.state');
        if ($code && $state == 'oauth') {
            import('source.class.Http');
            $json = Http::curlGet('https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid .
                '&secret=' . $appsecret . '&code=' . $code . '&grant_type=authorization_code');
            $result = json_decode($json, 1);
            if ($result['openid'] && $result['access_token']) {
                $json = Http::curlGet('https://api.weixin.qq.com/sns/userinfo?access_token=' . $result['access_token'] .
                    '&openid=' . $result['openid'] . '&lang=zh_CN');
                logs($json, 'INFO');
                $json = preg_replace("#(\\\ue[0-9a-f]{3})#ie", "addslashes('\\1')", $json);
                logs($json, 'INFO');
                $info = json_decode($json, 1);
                if ($info['openid']) {
                    // 检查 OPENID
                    $db_user = D('User');
                    if ($user = $db_user->where(array('openid' => $info['openid']))->find()) {
                        $user['nickname'] = $info['nickname'];
                        $user['avatar'] = $info['headimgurl'];
                        $user['sex'] = $info['sex'];
                        $user['country'] = $info['country'];
                        $user['province'] = $info['province'];
                        $user['city'] = $info['city'];
                        $db_user->where(array('openid' => $info['openid']))->data($user)->save();
                        // logs('update:' . $info['openid'], 'INFO');
                    } else {
                        // 注册
                        $user = array(
                            'nickname' => $info['nickname'],
                            'openid' => $info['openid'],
                            'avatar' => $info['headimgurl'],
                            'sex' => $info['sex'],
                            'country' => $info['country'],
                            'province' => $info['province'],
                            'city' => $info['city'],
                            'check_phone' => 0,
                            'reg_time' => $_SERVER['REQUEST_TIME'],
                            'last_time' => $_SERVER['REQUEST_TIME'],
                            'reg_ip' => ip2long($_SERVER['REMOTE_ADDR']),
                            'last_ip' => ip2long($_SERVER['REMOTE_ADDR'])
                        );
                        $user['uid'] = $db_user->data($user)->add();
                        //logs('insert:' . $user['uid'], 'INFO');

                        $login = M('User')->autologin('uid', $user['uid']);
                        if ($login['err_code'] === 0)
                            $user = $login['user']; //pigcms_tips('注册用户失败！', 'none');
                        else
                            pigcms_tips($login['err_msg'], 'none');
                    }

                    // 推荐奖励
                    $agent_id = D('Store')->where(array('uid' => $user['uid'], 'status' => 1))->getField('agent_id');
                    if (!$user['parent_uid'] && !$agent_id && $twid && $twid != $user['uid']) {
                        // 保存上线
                        $db_user->where(array('uid' => $user['uid']))->data(array('parent_uid' => $twid))->save();

                        // 推广奖励奖励
                        $reward_point = $config['promote_reward'] * 1;
                        if ($reward_point > 0) {
                            $db_user->where(array('uid' => $user['uid']))->setInc('point', $reward_point);
                            // 专属积分
                            $db_user->where(array('uid' => $user['uid']))->setInc('excl_point', $reward_point);
                            D('User_income')->data(array(
                                'uid' => $twid,
                                'order_no' => '',
                                'income' => 0.00,
                                'point' => $reward_point,
                                'type' => 7,
                                'add_time' => time(),
                                'status' => 1,
                                'remarks' => '推荐奖励'))->add();
                        }

                        $user['parent_uid'] = $twid;
                    }

                    // 注册店铺
                    if ($config['auto_create']) {
                        $db_store = D('Store');
                        $store = $db_store->where(array('uid' => $user['uid'], 'status' => 1))->find();
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
                            $store['store_id'] = $db_store->data($data)->add();
                            if ($store['store_id'])
                                $db_user->where(array('uid' => $user['uid']))->data(array('stores' => 1))->save();
                            else
                                pigcms_tips('自动创建店铺失败！', 'none');
                        }

                        $_SESSION['store'] = $store;
                    }
                    $_SESSION['user'] = $user;

                    $_SESSION['openid'] = $info['openid'];
                    $_SESSION['oauthed'] = $info['nickname'];

                    $refer = I('get.refer');
                    if (!empty($refer)) {
                        header('location:' . base64_decode($refer));
                        exit;
                    }

                    //完善登陆手机号
                    if (
                        (empty($user['phone']) || empty($user['password'])) &&
                        false === stripos($currentUrl, 'edit_login_phone')
                    ) redirect('/wap/edit_login_phone.php');
                } else {
                    pigcms_tips('授权不对！<br/>' . $result['errcode'], 'none');
                }
            } else {
                pigcms_tips('授权不对！<br/>' . $result['errcode'], 'none');
            }
        }
    } else {
        $login = M('User')->autologin('openid', $openid);
        if (!$login['err_code'])
            $_SESSION['user'] = $login['user'];
        else
            pigcms_tips($login['err_msg'], 'none');
    }
}

if (!is_array($_SESSION['store'])) $_SESSION['store'] = null;

if (empty($_SESSION['store'])) {
    /**
     * @var $db_store store_model
     */
    $db_store = M('Store');
    $store = $db_store->getStoreByUid($_SESSION['user']['uid']);
    if (empty($store)) {
        $store = $db_store->getStoreByUid($_SESSION['user']['parent_uid']);
    }
    if (empty($store)) $store = $db_store->getOfficial();
    $_SESSION['store'] = $store;
} else {
    if ($_SESSION['user']['stores'] && $_SESSION['store']['uid'] != $_SESSION['user']['uid']) {
        if ($store = D('Store')->where(array('uid' => $_SESSION['user']['uid'], 'status' => 1))->find()) {
            $_SESSION['store'] = $store;
        };
    }
}

/*用户信息*/
///////////////粉丝同步///////////////////
//if (!empty($_GET['id'])) {
//	if (stripos($_SERVER['REQUEST_URI'], 'good.php')) {
//		$tmp_product =
//			D('Product')->field('store_id')->where(array('product_id' => intval(trim($_GET['id']))))->find();
//		$tmp_store_id = $tmp_product['store_id'];
//	}
//	else if (stripos($_SERVER['REQUEST_URI'], 'goodcat.php')) {
//		$tmp_group =
//			D('Product_group')->field('store_id')->where(array('group_id' => intval(trim($_GET['id']))))->find();
//		$tmp_store_id = $tmp_group['store_id'];
//	}
//	else if (stripos($_SERVER['REQUEST_URI'], 'page.php')) {
//		$tmp_page = D('Wei_page')->field('store_id')->where(array('page_id' => intval(trim($_GET['id']))))->find();
//		$tmp_store_id = $tmp_page['store_id'];
//	}
//	else if (stripos($_SERVER['REQUEST_URI'], 'drp_product_share')) {
//		$tmp_product =
//			D('Product')->field('store_id')->where(array('product_id' => intval(trim($_GET['id']))))->find();
//		$tmp_store_id = $tmp_product['store_id'];
//	}
//	else if (stripos($_SERVER['REQUEST_URI'], 'pay')) {
//		$tmp_order = M('Order')->find($_GET['id']);
//		$tmp_store_id = $tmp_order['store_id'];
//	}
//	else {
//		$tmp_store_id = intval(trim($_GET['id']));
//	}
//}
//else if (!empty($_GET['store_id'])) {
//	$tmp_store_id = intval(trim($_GET['store_id']));
//}
//
//if (!empty($tmp_store_id) && !empty($_GET['sessid']) && !empty($_GET['token'])) { // 对接粉丝登录
//	$user = M('User');
//	$tmp_sessid = trim($_GET['sessid']);
//	$tmp_token = trim($_GET['token']);
//	$tmp_openid = trim($_GET['wecha_id']);
//	$user = $user->checkUser(array('session_id' => $tmp_sessid, 'token' => $tmp_token, 'third_id' => $tmp_openid));
//	if (!empty($user)) {
//		$_SESSION['user'] = $user;
//		$_SESSION['user']['store_id'] = $tmp_store_id;
//		$_SESSION['sync_user'] = true;
//		import('source.class.String');
//
//		if (empty($_SESSION['sessid'])) {
//			$session_id = String::keyGen();
//			$_SESSION['sessid'] = $session_id;
//		}
//		D('User')->where(array('uid' => $user['uid']))->data(array('session_id' => $_SESSION['sessid']))->save();
//	}
//}
//$php_self = php_self();
////////////////////////////////////

$wap_user = $_SESSION['user'];
$now_store = $_SESSION['store'];


//是否是 供应商
if (!isset($_SESSION['store']['is_supplier'])) {
    $isSupplier = D('Agent')->where(array('agent_id' => $_SESSION['store']['agent_id']))->find();
    $_SESSION['store']['is_supplier'] = isset($isSupplier['open_self']) && 1 == $isSupplier['open_self'];
    //redirect('/wap/supplier_ucenter.php');
}
$isSupplier = $_SESSION['store']['is_supplier'];


//// 检测分销商是否存在
//if (!empty($_SESSION['wap_drp_store']) && $_SESSION['wap_drp_store']['store_id'] != $tmp_store_id) {
//	$store_exists =
//		D('Store')->where(array('store_id' => $_SESSION['wap_drp_store']['store_id'], 'status' => 1))->find();
//	if (empty($store_exists)) { //店铺不存在或已删除
//		unset($_SESSION['wap_drp_store']); //删除保存在session中分销商
//	}
//}

/*是否移动端*/
$is_mobile = is_mobile();
/*是否微信端*/
$is_weixin = is_weixin();
////热门关键词
//$hot_keyword = D('Search_hot')->where('1')->order('sort DESC')->limit(8)->select();

//合并SESSION和UID的购物车、订单、收货地址等信息
function mergeSessionUserInfo($sessionid, $uid)
{
    //购物车
    D('User_cart')->where(array('uid' => 0, 'session_id' => $sessionid))
        ->data(array('uid' => $uid, 'session_id' => ''))
        ->save();

    //订单
    $edit_rows = D('Order')->where(array('uid' => 0, 'session_id' => $sessionid))
        ->data(array('uid' => $uid, 'session_id' => ''))
        ->save();
    if ($edit_rows && $_COOKIE['wap_store_id']) {
        //分销订单
        D('Fx_order')->where(array('uid' => 0, 'session_id' => $sessionid))
            ->data(array('uid' => $uid, 'session_id' => ''))
            ->save();
        M('Store_user_data')->updateData($_COOKIE['wap_store_id'], $uid);
    }

    //收货地址
    D('User_address')->where(array('uid' => 0, 'session_id' => $sessionid))
        ->data(array('uid' => $uid, 'session_id' => ''))
        ->save();
}

//访问统计
function Analytics($store_id, $module, $title, $id)
{
    $analytics = M('Store_analytics');
    $ip = bindec(decbin(ip2long($_SERVER['REMOTE_ADDR'])));
    $time = time();
    $analytics->add(array(
        'store_id' => $store_id,
        'module' => $module,
        'title' => $title,
        'page_id' => $id,
        'visited_time' => $time,
        'visited_ip' => $ip));
    if (strtolower($module) == 'goods') {
        $product = M('Product');
        $product->analytics(array('product_id' => $id, 'store_id' => $store_id));
    }
}

// 获取当前文件名称 （公用菜单选中效果）
function php_self()
{
    return substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/') + 1);
}
