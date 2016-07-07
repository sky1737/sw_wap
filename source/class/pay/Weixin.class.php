<?php

class Weixin
{
    protected $order;
    protected $config;
    //protected $user;
    protected $openid;
    protected $type;

    public function __construct($order_info, $pay_config, $openid, $type = 'pay')
    {
        $this->order  = $order_info;
        $this->config = $pay_config;
        $this->openid = $openid;
        $this->type   = $type;
    }

    /**
     * 微信支付
     * @return array
     */
    public function pay()
    {
        if (empty($this->config['pay_weixin_appid']) ||
            empty($this->config['pay_weixin_mchid']) ||
            empty($this->config['pay_weixin_key'])
        ) {
            return array('err_code' => 1, 'err_msg' => '微信支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
        }

        if (empty($this->openid)) {
            return array('err_code' => 1, 'err_msg' => '没有获取到用户的微信资料，无法使用微信支付');
        }

        import('source.class.pay.Weixinnewpay.WxPayPubHelper');
        $jsApi = new JsApi_pub(
            $this->config['pay_weixin_appid'],
            $this->config['pay_weixin_mchid'],
            $this->config['pay_weixin_key']);

        $unifiedOrder = new UnifiedOrder_pub(
            $this->config['pay_weixin_appid'],
            $this->config['pay_weixin_mchid'],
            $this->config['pay_weixin_key']);

        $unifiedOrder->setParameter('openid', $this->openid);
        $unifiedOrder->setParameter('body', $this->order['order_no_txt']);
        $unifiedOrder->setParameter('out_trade_no', $this->order['trade_no']);
        $unifiedOrder->setParameter('total_fee', floatval($this->order['pay_money'] * 100));
        $unifiedOrder->setParameter('notify_url', option('config.wap_site_url') . '/' . $this->type . 'notice.php');
        $unifiedOrder->setParameter('trade_type', 'JSAPI');
        $unifiedOrder->setParameter('attach', 'weixin');
        $time = time();
        $unifiedOrder->setParameter('time_stamp', "$time");
        $prepay_result = $unifiedOrder->getPrepayId();
        logs('prepay_result:' . json_encode($prepay_result), 'INFO');
        if ($prepay_result['return_code'] == 'FAIL') {
            return array(
                'err_code' => 1,
                'err_msg'  => '没有获取微信支付的预支付ID，请重新发起支付！<br/><br/>微信支付错误返回：' . $prepay_result['return_msg'],
            );
        }

        if ($prepay_result['err_code']) {
            return array(
                'err_code' => 1,
                'err_msg'  => '没有获取微信支付的预支付ID，请重新发起支付！<br/><br/>微信支付错误返回：' . $prepay_result['err_code_des'],
            );
        }

        $jsApi->setPrepayId($prepay_result['prepay_id']);

        return array('err_code' => 0, 'pay_data' => $jsApi->getParameters());
    }

    /**
     * 支付、充值 Notice
     * @return array
     */
    public function notice()
    {
        if (empty($this->config['pay_weixin_appid']) || empty($this->config['pay_weixin_mchid']) ||
            empty($this->config['pay_weixin_key'])
        ) {
            return array('err_code' => 1, 'err_msg' => '微信支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
        }

        import('source.class.pay.Weixinnewpay.WxPayPubHelper');
        $notify = new Notify_pub($this->config['pay_weixin_appid'], $this->config['pay_weixin_mchid'],
            $this->config['pay_weixin_key']);
        $xml    = $GLOBALS['HTTP_RAW_POST_DATA'];
        //logs($xml, 'INFO');
        $notify->saveData($xml);
        //logs(''.json_encode($notify->data), 'INFO');
        if ($notify->checkSign() == false) {
            $notify->setReturnParameter('return_code', 'FAIL');
            $notify->setReturnParameter('return_msg', '签名失败');

            return array('err_code' => 1, 'err_msg' => $notify->returnXml());
        } else {
            $notify->setReturnParameter('return_code', 'SUCCESS');
            if (($notify->data['return_code'] == 'SUCCESS') && ($notify->data['result_code'] == 'SUCCESS')) {
                $order_param['trade_no']     = $notify->data['out_trade_no'];
                $order_param['pay_type']     = 'weixin';
                $order_param['third_id']     = $notify->data['transaction_id'];
                $order_param['pay_money']    = $notify->data['total_fee'] / 100;
                $order_param['third_data']   = $notify->data;
                $order_param['echo_content'] = $notify->returnXml();

                return array('err_code' => 0, 'order_param' => $order_param);
            } else {
                return array(
                    'err_code' => 1,
                    'err_msg'  => '支付时发生错误！<br/>错误提示：' . $notify->data['return_code'] . '<br/>错误代码：' .
                        $notify->data['result_code'],
                );
            }
        }
    }

    /**
     * 企业付款接口实现
     * @return array
     */
    public function transfers()
    {
        if (empty($this->config['pay_weixin_appid']) || empty($this->config['pay_weixin_mchid']) ||
            empty($this->config['pay_weixin_key'])
        ) {
            return array('err_code' => 1, 'err_msg' => '微信支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
        }

        if (empty($this->openid)) {
            return array('err_code' => 1, 'err_msg' => '没有获取到用户的微信资料，无法使用微信支付');
        }

        import('source.class.pay.Weixinnewpay.WxPayPubHelper');
        $transferOrder = new Transfer_pub(
            $this->config['pay_weixin_appid'],
            $this->config['pay_weixin_mchid'],
            $this->config['pay_weixin_key']);

        $transferOrder->setParameter('amount', floatval($this->order['amount']) * 100);
        $transferOrder->setParameter('check_name', 'OPTION_CHECK');
        $transferOrder->setParameter('desc', $this->order['truename'] . '提现' . $this->order['amount'] . '元');
        $transferOrder->setParameter('openid', $this->openid);
        $transferOrder->setParameter('partner_trade_no', $this->order['trade_no']);
        $transferOrder->setParameter('re_user_name', $this->order['truename']);

        $result = $transferOrder->getResult();
        logs('Transfer_result:' . $this->order['trade_no'] . '_' . json_encode($result), 'INFO');
        if (empty($result) || $result['return_code'] == "FAIL" || $result['result_code'] == "FAIL") {
            return array(
                'err_code' => 1,
                'err_msg'  => '提现时发生错误！错误提示：' . $result['err_code_des'] . '<br/>错误代码：' .
                    $result['err_code'],
            );
        }

        return array('err_code' => 0, 'err_msg' => $result['result_code']);
    }

    public function redpack()
    {
        if (empty($this->config['pay_weixin_appid']) || empty($this->config['pay_weixin_mchid']) ||
            empty($this->config['pay_weixin_key'])
        ) {
            return array('err_code' => 1, 'err_msg' => '微信支付缺少配置信息！请联系管理员处理或选择其他支付方式。');
        }

        if (empty($this->openid)) {
            return array('err_code' => 1, 'err_msg' => '没有获取到用户的微信资料，无法使用微信支付');
        }

        import('source.class.pay.Weixinnewpay.WxPayPubHelper');
        $transferOrder = new Redpack_pub(
            $this->config['pay_weixin_appid'],
            $this->config['pay_weixin_mchid'],
            $this->config['pay_weixin_key']);

        $transferOrder->setParameter('mch_billno',
            $this->config['pay_weixin_mchid'] .
            date('YmdHis', $_SERVER['REQUEST_TIME']) .
            mt_rand(1000, 9999)); // mch_id+yyyymmdd+10
        $transferOrder->setParameter('send_name', option('config.wechat_name'));
        $transferOrder->setParameter('re_openid', $this->order['openid']);
        $transferOrder->setParameter('total_amount', floatval($this->order['amount']) * 100);
        $transferOrder->setParameter('total_num', 1);
        $transferOrder->setParameter('wishing', '恭喜发财！');
        $transferOrder->setParameter('act_name', '抢红包');
        $transferOrder->setParameter('remark', '推广用户奖励现金红包！');
        $result = $transferOrder->getResult();
        logs('Redpack_result:' . $this->order['trade_no'] . '_' . json_encode($result), 'INFO');
        if (empty($result) || $result['return_code'] == "FAIL" || $result['result_code'] == "FAIL") {
            return array(
                'err_code' => 1,
                'err_msg'  => '发送红包时发生错误！错误提示：' . $result['err_code_des'] . '<br/>错误代码：' .
                    $result['err_code'],
            );
        }

        return array('err_code' => 0, 'err_msg' => $result['mch_billno']);
    }
}
