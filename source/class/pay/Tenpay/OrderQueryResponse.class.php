<?php

require_once 'common/CommonResponse.class.php';

class OrderQueryResponse extends CommonResponse
{
    public function OrderQueryResponse($paraMap, $secretKey)
    {
        $this->CommonResponse($paraMap, $secretKey);
    }
}

?>
