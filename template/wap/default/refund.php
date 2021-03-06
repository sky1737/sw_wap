<?php if (!defined('TWIKER_PATH')) exit('deny access!'); ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta name="keywords" content="<?php echo $config['seo_keywords']; ?>"/>
    <meta name="description" content="<?php echo $config['seo_description']; ?>"/>
    <link rel="icon" href="<?php echo $config['site_url']; ?>/favicon.ico"/>
    <title>退款申请</title>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="default"/>
    <meta name="applicable-device" content="mobile"/>
    <link rel="stylesheet" href="<?php echo TPL_URL; ?>index_style/css/address.css"/>
    <link rel="stylesheet" href="<?php echo $config['site_url']; ?>/template/wap/default/css/gonggong.css"/>
    <script src="<?php echo $config['oss_url']; ?>/static/js/fastclick.js"></script>
    <script src="<?php echo $config['oss_url']; ?>/static/js/jquery.min.js"></script>
    <script src="<?php echo $config['oss_url']; ?>/static/js/area/area.min.js"></script>
    <script src="<?php echo TPL_URL; ?>index_style/js/base.js"></script>
    <style>.address_list ul:before {
            display: none;
        }</style>
    <script>
        $(function () {
            $('#is_take').change(function () {
                if($(this).val()==1)
                {
                    $('#div-express').show()
                } else
                {
                    $('#div-express').hide()
                }
            });

            $('#express_code').change(function () {
                var value = $(this).find("option:selected").text();
                $("input[name='express_company']").val(value);
            });


            $("#button").click(function(){
                if($('#refund_reason').val() == '' ){
                    alert('请填写退款原因');
                } else if($('#express_no').val() == ''  &&  $('#is_take').val()==1){
                    alert('请填写物流单号');
                } else {
                    $.post('./refund_package.php',$("form").serialize(),function (data) {
                        if(typeof(data)=="string") data = eval('('+data+')');
                        if(data.err_code == 0){
                            alert(data.err_msg);
                            window.location.href ='./my_order.php';
                        } else {
                            alert(data.err_msg);
                        }
                    });
                }
                return false;
            });
        });
    </script>
</head>
<body>
<div class="wx_wrap">
    <div class="address_new">
        <form>
            <p>
                <label>
                    <span class="tit">是否收货：</span>
                    <select id="is_take" name="is_take">
                        <option value ="0">未收货</option>
                        <option value ="1">已收货</option>
                    </select>
                </label>
            </p>
            <p>
                <label for="refund_reason">
                    <span class="tit">退款原因：</span>
                    <input id="refund_reason" name="refund_reason" type="text" placeholder="退款原因">
                </label>
            </p>
            <div id ="div-express" style="display: none">
                <p>
                    <label for="express_code">
                        <span class="tit">物流信息：</span>
                        <span>
                            <select id="express_code" name="express_code">
                                <option value ="">请选择快递公司</option>
                                <?php foreach ($express  as $key => $val):?>
                                    <option value ="<?php echo $val['code']?>"><?php echo $val['name'];?></option>
                                <?php endforeach;?>
                            </select>
                        </span>
                    </label>
                </p>
                <p>
                    <label for="express_no">
                        <span class="tit">物流单号：</span>
                        <input id="express_no" name="express_no" value="" type="text" placeholder="物流单号">
                    </label>
                </p>
            </div>
            <p class="action">
                <input type="hidden" name="express_company" value="">
                <input type="hidden" name="order_id" value="<?php echo $nowOrder['order_id']?>">
                <input type="hidden" name="products" value="<?php echo $product_ids ?>">
                <button class="submit" id="button">确认提交</button>
            </p>
        </form>
    </div>
</div>
</body>
</html>