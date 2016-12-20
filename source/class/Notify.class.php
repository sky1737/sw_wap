<?php

/**
 * 提醒
 */
class Notify
{
    private        $_db;
    private static $_instance;

    private function __construct()
    {
        $this->_db = new templateNews(option('config.wechat_appid'), option('config.wechat_secret'));
    }

    /*
     * 覆盖__clone()方法，禁止克隆
     */
    private function __clone()
    {
    }

    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function send($tempKey, $data)
    {
        $this->_db->sendTempMsg($tempKey, $data);
    }

    public function orderUpdate($openid, $url, $first, $orderSn, $orderStatus, $remark)
    {
        // 'first', 'OrderSn', 'OrderStatus', 'remark'
        return $this->_db->sendTempMsg('OPENTM100000', array(
            'openid'      => $openid,
            'url'         => $url,
            'first'       => $first,
            'OrderSn'     => $orderSn,
            'OrderStatus' => $orderStatus,
            'remark'      => $remark,
        ));
    }

    public function orderPaid($openid, $url, $first, $orderMoney, $orderProducts, $remark)
    {
        // 'first', 'orderMoneySum', 'orderProductName', 'remark'
        return $this->_db->sendTempMsg('OPENTM100001', array(
            'openid'           => $openid,
            'url'              => $url,
            'first'            => $first,
            'orderMoneySum'    => $orderMoney,
            'orderProductName' => $orderProducts,
            'remark'           => $remark,
        ));
    }

    public function notifyAgent($openid, $url, $first, $keyword1, $keyword2, $keyword3, $keyword4, $remark)
    {
        return $this->_db->sendTempMsg('OPENTM100002', array(
            'openid'   => $openid,
            'url'      => $url,
            'first'    => $first,
            'keyword1' => $keyword1,
            'keyword2' => $keyword2,
            'keyword3' => $keyword3,
            'keyword4' => $keyword4,
            'remark'   => $remark,
        ));
    }

    public function notifyAgentRefund($openid, $url, $first, $keyword1, $keyword2, $keyword3, $remark)
    {
        return $this->_db->sendTempMsg('OPENTM100003', array(
            'openid'   => $openid,
            'url'      => $url,
            'first'    => $first,
            'keyword1' => $keyword1,
            'keyword2' => $keyword2,
            'keyword3' => $keyword3,
            'remark'   => $remark,
        ));
    }

    public function accountChange($openid, $url, $first, $date, $adCharge, $type, $cashBalance, $remark)
    {
        // 'first', 'date', 'adCharge', 'type', 'cashBalance', 'remark'
        return $this->_db->sendTempMsg('OPENTM200000', array(
            'openid'      => $openid,
            'url'         => $url,
            'first'       => $first,
            'date'        => $date,
            'adCharge'    => $adCharge,
            'type'        => $type,
            'cashBalance' => $cashBalance,
            'remark'      => $remark,
        ));
    }

    public function notifyRecharge($openid, $url, $first, $accountType, $account, $amount, $result, $remark)
    {
        return $this->_db->sendTempMsg('OPENTM200001', array(
            'openid'      => $openid,
            'url'         => $url,
            'first'       => $first,
            'accountType' => $accountType,
            'account'     => $account,
            'amount'      => $amount,
            'result'      => $result,
            'remark'      => $remark,
        ));
    }

    public function cashBack($openid, $url, $first, $order, $money, $remark)
    {
        return $this->_db->sendTempMsg('OPENTM200002', array(
            'openid' => $openid,
            'url'    => $url,
            'first'  => $first,
            'order'  => $order,
            'money'  => $money,
            'remark' => $remark,
        ));
    }

    public function refund($openid, $url, $first, $reason, $refund, $remark)
    {
        return $this->_db->sendTempMsg('OPENTM200099', array(
            'openid' => $openid,
            'url'    => $url,
            'first'  => $first,
            'reason' => $reason,
            'refund' => $refund,
            'remark' => $remark,
        ));
    }
}