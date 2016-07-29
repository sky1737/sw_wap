<?php

class templateNews
{
    public $thisWxUser;
    public $appid;
    public $appsecret;

    public function __construct($appid = null, $appsecret = null)
    {
        $this->appid     = $appid;
        $this->appsecret = $appsecret;
    }

    public function test()
    {
        $this->sendTempMsg('OPENTM201752540', array(
            'openid'           => 'ouk9dwe7RqSVEuo6wMp5o08ayC_4',
            'url'              => 'http://www.whwzd.com/h5/',
            'first'            => '我们已收到您的货款，开始为您打包商品，请耐心等待!',
            'orderMoneySum'    => '100.00',
            'orderProductName' => '测试的商品名称',
            'remark'           => '测试的备注！',
        ));
    }

    /*
     * $tempKey 本地模板KEY
     * $dataArr Array('openid','url', vars...)
     */
    public function sendTempMsg($tempKey, $dataArr)
    {
        $dbinfo = D('Tempmsg')->where(array('tempkey' => $tempKey))->find();
        if ($dbinfo['status']) {
            $access_token_array = M('Access_token')->get_access_token();
            if ($access_token_array['errcode']) {
                return array('rt' => true, 'errorno' => $access_token_array['errcode'], 'errmsg' => $access_token_array['errmsg']);
            }

            $access_token = $access_token_array['access_token'];
            $requestUrl   = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token;
            $data         = $this->getData($tempKey, $dataArr, $dbinfo['textcolor']);
            $sendData     = '{"touser":"' . $dataArr['openid'] .
                '","template_id":"' . $dbinfo['tempid'] .
                '","url":"' . $dataArr['url'] .
                '","topcolor":"' . $dbinfo['topcolor'] .
                '","data":' . $data .
                '}';
            logs($sendData, 'TemplateMsg');

            return $this->postCurl($requestUrl, $sendData);
        }
    }

    public function getData($key, $dataArr, $color)
    {
        $tempsArr = $this->templates();
        $data     = $tempsArr[ $key ]['vars'];
        $data     = array_flip($data);
        $jsonData = '';

        foreach ($dataArr as $k => $v) {
            if (in_array($k, array_flip($data))) {
                $jsonData .= '"' . $k . '":{"value":"' . $v . '","color":"' . $color . '"},';
            }
        }

        $jsonData = rtrim($jsonData, ',');

        return '{' . $jsonData . '}';
    }

    public function templates()
    {
        return array(
            'OPENTM100000' => array(
                'name'    => '订单状态更新',
                'vars'    => array('first', 'OrderSn', 'OrderStatus', 'remark'),
                'content' => '' .
                    "\r\n" .
                    '{{first.DATA}}' .
                    "\r\n" .
                    '订单编号: {{OrderSn.DATA}}' .
                    "\r\n" .
                    '订单状态: {{OrderStatus.DATA}}' .
                    "\r\n" .
                    '{{remark.DATA}}',
            ),
            'OPENTM100001' => array(
                'name'    => '订单支付成功通知',
                'vars'    => array('first', 'orderMoneySum', 'orderProductName', 'remark'),
                'content' => '' .
                    "\r\n" .
                    '{{first.DATA}}' .
                    "\r\n" .
                    '支付金额：{{orderMoneySum.DATA}}' .
                    "\r\n" .
                    '商品信息：{{orderProductName.DATA}}' .
                    "\r\n" .
                    '{{remark.DATA}}',
            ),
            'OPENTM100002' => array(
                'name'    => '提醒供应商发货通知',
                'vars'    => array('first', 'keyword1', 'keyword2', 'keyword3', 'keyword4', 'remark'),
                'content' => '' .
                    "\r\n" .
                    '{{first.DATA}}' .
                    "\r\n" .
                    '订单号：{{keyword1.DATA}}' .
                    "\r\n" .
                    '商品名称：{{keyword2.DATA}}' .
                    "\r\n" .
                    '订单金额：{{keyword3.DATA}}' .
                    "\r\n" .
                    '下单时间：{{keyword4.DATA}}' .
                    "\r\n" .
                    '{{remark.DATA}}',
            ),
            'OPENTM100003' => array(
                'name'    => '买家申请退货',
                'vars'    => array('first', 'keyword1', 'keyword2', 'keyword3', 'remark'),
                'content' => '' .
                    "\r\n" .
                    '{{first.DATA}}' .
                    "\r\n" .
                    '订单号：{{keyword1.DATA}}' .
                    "\r\n" .
                    '商品名称：{{keyword2.DATA}}' .
                    "\r\n" .
                    '订单金额：{{keyword3.DATA}}' .
                    "\r\n" .
                    '{{remark.DATA}}',
            ),
            // 2000 开头账户提示
            'OPENTM200000' => array(
                'name'    => '帐户资金变动提醒',
                'vars'    => array('first', 'date', 'adCharge', 'type', 'cashBalance', 'remark'),
                'content' => '' .
                    "\r\n" .
                    '{{first.DATA}}' .
                    "\r\n" .
                    '' .
                    "\r\n" .
                    '变动时间：{{date.DATA}}' .
                    "\r\n" .
                    '变动金额：{{adCharge.DATA}}' .
                    "\r\n" .
                    '{{type.DATA}}帐户余额：{{cashBalance.DATA}}' .
                    "\r\n" .
                    '{{remark.DATA}}',
            ),
            'OPENTM200001' => array(
                'name'    => '充值提示',
                'vars'    => array('first', 'accountType', 'account', 'amount', 'result', 'remark'),
                'content' => '' .
                    "\r\n" .
                    '{{first.DATA}}' .
                    "\r\n" .
                    '{{accountType.DATA}}：{{account.DATA}}' .
                    "\r\n" .
                    '充值金额：{{amount.DATA}}' .
                    "\r\n" .
                    '充值状态：{{result.DATA}}' .
                    "\r\n" .
                    '{{remark.DATA}}',
            ),
            'OPENTM200002' => array(
                'name'    => '返现到账通知',
                'vars'    => array('first', 'order', 'money', 'remark'),
                'content' => '' .
                    "\r\n" .
                    '{{first.DATA}}' .
                    "\r\n" .
                    '订单：{{order.DATA}}' .
                    "\r\n" .
                    '返现金额：{{money.DATA}}' .
                    "\r\n" .
                    '{{remark.DATA}}',
            ),
            'OPENTM200099' => array(
                'name'    => '退款通知',
                'vars'    => array('first', 'reason', 'refund', 'remark'),
                'content' => '' .
                    "\r\n" .
                    '{{first.DATA}}' .
                    "\r\n" .
                    '' .
                    "\r\n" .
                    '退款原因：{{reason.DATA}}' .
                    "\r\n" .
                    '退款金额：{{refund.DATA}}' .
                    "\r\n" .
                    '{{remark.DATA}}',
            ),
        );
    }

    public function postCurl($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Charset: utf-8\r\n'));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        $errorno = curl_errno($ch);

        if ($errorno) {
            return array('rt' => false, 'errorno' => $errorno);
        } else {
            $js = json_decode($tmpInfo, 1);

            if ($js['errcode'] == '0') {
                return array('rt' => true, 'errorno' => 0);
            } else {
                return array('rt' => false, 'errorno' => $js['errcode'], 'errmsg' => $js['errmsg']);
            }
        }
    }

    public function curlGet($url)
    {
        $ch     = curl_init();
        $header = 'Accept-Charset: utf-8';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $temp = curl_exec($ch);

        return $temp;
    }
}
