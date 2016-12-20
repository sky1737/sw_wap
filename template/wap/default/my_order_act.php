<!DOCTYPE html>
<html class="no-js" lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta name="keywords" content="云温商分销微店"/>
    <meta name="description" content="云温商分销微店"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="MobileOptimized" content="320"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta http-equiv="cleartype" content="on"/>
    <link rel="icon" href="http://www.yun-ws.com/favicon.ico"/>
    <title>中秋活动订单</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <link rel="stylesheet" href="http://www.yun-ws.com/template/wap/default/css/base.css"/>
    <link rel="stylesheet" href="http://www.yun-ws.com/template/wap/default/css/order_list.css"/>
    <link rel="stylesheet" href="http://www.yun-ws.com/template/wap/default/css/gonggong.css"/>
    <script src="http://www.yun-ws.com/static/js/jquery.min.js"></script>
    <script src="http://www.yun-ws.com/template/wap/default/js/base.js"></script>
    <script src="http://www.yun-ws.com/template/wap/default/js/order.js"></script>
    <script src="http://www.yun-ws.com/template/wap/default/js/order_paid.js"></script>
    <script>
        $(function () {
            $("#pages a").click(function () {
                var page = $(this).attr("data-page-num");
                location.href = "my_order.php?action=all&page=" + page;
            });
        });
    </script>
    <style type="text/css">
        .mid-autumn-img{  width:100%;}
        .mid-autumn-img img{ display:block; width:100%;}
        .mid-autumn-footer {  width:100%; position:relative; padding:20px 10px 10px; margin:10px 0 30px; background-color: #fff; box-sizing:border-box; font-size:13px; color:#666;}
        .barControl {  margin: 0 auto; }
        .barContro_space {  margin: 10px 0;  background: grey;  border-radius: 5px;  }
        .windu {  border-radius: 2px;  display: block;  width: 0%;  box-shadow: 0px 0px 10px 1px #AC85B4, 0 0 1px #AC85B4, 0 0 1px #AC85B4, 0 0 1px #AC85B4, 0 0 1px #AC85B4, 0 0 1px #AC85B4, 0 0 1px #AC85B4;  background-color: #fff;  }
        .progress {width:100%; padding: 10px 60px 10px 0px; box-sizing:border-box;}
        .mid-autumn-present{color: #ff5e5d; font-size: 22px; font-weight: bold; position:absolute; top:48px; right:10px}
        .mid-autumn-cost > span:first-child{color: #333}
        .mid-autumn-cost > span:nth-child(2){color: #eee;padding:0 8px;}
        .mid-autumn-cost > span:last-child{color: #ff0000; font-size: 18px;}
        .block-order .block-list {height:0;}
        .block.block-list+.block.block-list { margin-top: 0; }
    </style>
</head>
<body>
<div class="container">
    <div>
        <div class="mid-autumn-img">
            <?php
            if(empty($banner[0])) {
                echo '请添加标签为 wap_lottery_top 的广告。';
            } else {
                $value = $banner[0];
                echo '<a href="'.$value['url'].'"><img src="'.$value['pic'].'" alt="'.$value['name'].'" /></a>';
            } ?>
        </div>
        <div id="order-list-container">
            <div class="b-list">
                <?php if(count($order_list)):?>
                    <?php $i=1; $sub_total = 0 ;foreach ($order_list as $key => $value):?>
                        <li class="block block-order animated" id="li_<?php echo $i;?>">
                            <div class="header"> <span class="font-size-12">订单号：<?php echo $value['order_no_txt']; ?></span><span class="font-size-12 pull-right"> 总价：<span class="c-orange">￥<?php echo $value['total']; ?></span></span>
                            </div>
                            <hr class="margin-0 left-10"/>
                            <?php $j=0; foreach ($value['product_list'] as $product):?>
                                <div class="block block-list block-border-top-none block-border-bottom-none">
                                    <div class="block-item name-card name-card-3col clearfix"> <a href="<?php echo $product['url']; ?>" class="thumb"> <img src="<?php echo $product['image']; ?>"/> </a>
                                        <div class="detail">
                                            <a href="<?php echo $product['url']; ?>">
                                                <h3 style="margin-bottom:6px;"><?php echo $product['name']; ?></h3>
                                            </a>
                                            <?php if(count($product['sku_data_arr'])):?>
                                                <?php foreach ($product['sku_data_arr'] as $val):?>
                                                    <p class="c-gray ellipsis"><?php echo $val['name']?> ：<?php echo $val['value']?></p>
                                                <?php endforeach;?>
                                            <?php endif;?>
                                        </div>
                                        <div class="right-col">
                                            <div class="price"> ¥&nbsp;<span><?php echo $product['pro_price']; ?></span></div>
                                            <div class="num">×<span class="num-txt"><?php echo $product['pro_num']; ?></span>
                                                <div style="font-weight: bold;">
                                                    <?php if(4 == $value['status']):?>
                                                        <a  class="btn btn-in-order-list btn-orange" style="padding: 2px 12px;" href="./comment_add.php?id=<?php echo $value['product_list'][$j++]['product_id'] ?>&type=PRODUCT">评价</a>
                                                    <?php endif;?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php $j++; endforeach;?>
                        </li>
                        <?php $i++; $sub_total += $value['total']; endforeach;?>
                    <?php $rate = ($sub_total/2999)*100?>
                    <div class="mid-autumn-footer">
                        <div class="mid-autumn-cost">已消费<span>￥<?php echo  $sub_total?></span><span> | </span>还差<span>￥<?php echo $sub_total < 299 ? 2999-$sub_total : 0?></span></div>
                        <div class="progress" id="windu"></div>
                        <div class="mid-autumn-present">2999</div>
                    </div>
                <?php else:?>
                    <div class="empty-list list-finished" style="padding-top:60px;display:none;">
                        <div>
                            <h4>居然还没有订单</h4>
                            <p class="font-size-12">好东西，手慢无</p>
                        </div>
                        <div><a href="./index.php" class="tag tag-big tag-orange" style="padding:8px 30px;">去逛逛</a></div>
                    </div>
                <?php endif;?>
            </div>
        </div>
        <div class="mid-autumn-img">
            <?php
            if(empty($footer[0])) {
                echo '请添加标签为 wap_lottery_footer 的广告。';
            } else {
                $value = $footer[0];
                echo '<a href="'.$value['url'].'"><img src="'.$value['pic'].'" alt="'.$value['name'].'" /></a>';
            } ?>
        </div>
    </div>
</div>
<script>
    $(function(){
        $.fn.extend({
            ProgressBarWars: function (opciones) {
                var ProgressBarWars = this;
                var theidProgressBarWars = $(ProgressBarWars).attr("id");
                var styleUnique = Date.now();
                var StringStyle = "";

                defaults = {
                    porcentaje: "100",
                    tiempo: 1000,
                    color: "",
                    estilo: "yoda",
                    tamanio: "30%",
                    alto: "6px"
                }

                var opciones = $.extend({}, defaults, opciones);
                if (opciones.color != '') {
                    StringStyle = "<style>.color" + styleUnique + "{ border-radius: 2px;display: block; width: 0%; box-shadow:0px 0px 10px 1px " + opciones.color + ", 0 0 1px " + opciones.color + ", 0 0 1px " + opciones.color + ", 0 0 1px " + opciones.color + ", 0 0 1px " + opciones.color + ", 0 0 1px " + opciones.color + ", 0 0 1px " + opciones.color + ";background-color: #fff;}</style>";
                    opciones.estilo = "color" + styleUnique;
                }
                $(ProgressBarWars).before(StringStyle);
                $(ProgressBarWars).append('<span class="barControl" style="width:' + opciones.tamanio + ';"><div class="barContro_space"><span class="' + opciones.estilo + '" style="height: ' + opciones.alto + ';"  id="bar' + theidProgressBarWars + '"></span></div></span>');
                $("#bar" + theidProgressBarWars).animate({width: opciones.porcentaje + "%"}, opciones.tiempo);
                this.mover = function (ntamanio) {
                    $("#bar" + $(this).attr("id")).animate({width: ntamanio + "%"}, opciones.tiempo);
                };
                return this;
            }
        });
        var wd=$("#windu").ProgressBarWars({porcentaje:<?php echo $rate;?>,estilo:"windu"});

        $('.block-order .header').click(function () {
            var $this = $(this);
            $liId = $this.parent().attr('id');
            $list = $('#' + $liId + ' .block-list');
            if ($list.hasClass('extension')) {
                $list.removeClass('extension').animate({height:'0'},300);
            }else{
                $list.addClass('extension').animate({height:'80px'},300);
            }
        });
    })
</script>

</body>
</html>
