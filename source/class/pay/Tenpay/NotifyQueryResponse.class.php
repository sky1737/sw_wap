<?php

require_once 'common/CommonResponse.class.php';

class NotifyQueryResponse extends CommonResponse
{
    public function NotifyQueryResponse($paraMap, $secretKey)
    {
        $this->CommonResponse($paraMap, $secretKey);
    }
}

?>
