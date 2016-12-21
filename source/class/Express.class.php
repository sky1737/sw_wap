<?php

class Express
{
    // 从 快递鸟 里返回信息
    static public function kdn($shipperCode/*快递编码区分大小写*/, $logisticCode)
    {
        $requestData = "{\"OrderCode\":\"\",\"ShipperCode\":\"" . $shipperCode . "\",\"LogisticCode\":\"" . $logisticCode . "\",\"SiteUrl\":\"" . $_SERVER['HTTP_HOST'] . "\",\"App\":\"php52demo\"}";
        $datas       = array(
            'EBusinessID' => '1262051', //快递鸟申请的商户ID
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData),
            'DataType'    => '2',
        );

        $datas['DataSign'] = urlencode(base64_encode(md5($requestData . 'd44e76b9-8734-401b-9dd7-e9b55483edf3'))); //电商加密私钥，快递鸟提供，注意保管，不要泄漏

        //post提交数据
        $temps = array();
        foreach ($datas as $key => $value) $temps[] = sprintf('%s=%s', $key, $value);
        $post_data  = implode('&', $temps);
        $url_info   = parse_url('http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx');
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader .= "Host:" . $url_info['host'] . "\r\n";
        $httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader .= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader .= "Connection:close\r\n\r\n";
        $httpheader .= $post_data;
        $fd = fsockopen($url_info['host'], 80);
        fwrite($fd, $httpheader);
        $json_str = "";
        while (!feof($fd)) if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) break;
        while (!feof($fd)) $json_str .= fread($fd, 128);
        fclose($fd);

        return $json_str;
    }
}