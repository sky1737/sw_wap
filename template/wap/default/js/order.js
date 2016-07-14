$(function () {
    if ($('li.block-order').size() == 0) {
        $('.empty-list').show();
    }

    $('.js-cancel-order').click(function () {
        var nowDom = $(this);
        $.post('./order.php?del_id=' + $(this).data('id'), function (result) {
            motify.log(result.err_msg);
            if (result.err_code == 0) {
                nowDom.closest('li').remove();
                if ($('li.block-order').size() == 0) {
                    $('.bottom').hide();
                    $('.empty-list').show();
                }
            }
        });
    });


    $('.js-refund-it').click(function () {
        var orderNo = $(this).data('id');
        var popBg = $('<div id="qpPh1bGqgC" style="height:100%;position:fixed;top:0px;left:0px;right:0px;background-color:rgba(0,0,0,0.7);z-index:1000;transition:none 0.2s ease 0s;opacity:1;"></div>');
        var skuHtml = '<div id="n65dA7sX3X" class="sku-layout sku-box-shadow" style="overflow:hidden;bottom:0px;left:0px;right:0px;visibility:visible;position:absolute;z-index:1100;opacity:1;"><div class="adv-opts layout-content"><div class="confirm-action content-foot">';
        skuHtml += '<a href="javascript:;" class="js-refund-money confirm btn btn-block btn-orange-dark">退款</a><div class="goods-models block block-list block-border-top-none"></div><a href="javascript:;" class="js-refund-good cart btn btn-block btn-orange-dark half-button">退货</a></div></div></div>';

        var popCon = $(skuHtml);

        var nowScroll = $(window).scrollTop();
        $('html').css({'position': 'relative', 'overflow': 'hidden', 'height': $(window).height() + 'px'});
        $('body').css({
            'overflow': 'hidden',
            'height': $(window).height() + 'px',
            'padding': '0px'
        }).append(popBg).append(popCon);

        setTimeout(function () {
            popCon.height(popCon.height() > $(window).height() - 50 ? $(window).height() - 50 + 'px' : popCon.height()).find('.layout-content').height(popCon.height() - 61 + 'px');
            setTimeout(function () {
                popCon.css({'transform': 'translate3d(0px,0px,0px)', '-webkit-transform': 'translate3d(0px,0px,0px)'});
            }, 100);
        }, 100);

        popBg.click(function () {
            popCon.css({'transform': 'translate3d(0px,100%,0px)', '-webkit-transform': 'translate3d(0px,100%,0px)'});

            $('html').css({'overflow': 'visible', 'height': 'auto', 'position': 'static'});
            $('body').css({'overflow': 'visible', 'height': 'auto', 'padding-bottom': '45px'});
            $(window).scrollTop(nowScroll);
            popBg.remove();
            popCon.remove();
        });

        //退款
        $('.js-refund-money').click(function () {
            $.post('./saveorder.php?action=refund&',{orderNo:orderNo},function (data){
                if(data.err_code == 0){
                    console.log(data);
                } else {
                    alert(data.err_msg);
                }
            });
            console.log('refund-m');
        });
        //退货
        $('.js-refund-good').click(function () {
            window.location.href ='./refund.php?orderNo='+orderNo;
            console.log('refund-g');
        });

    });
    
});

