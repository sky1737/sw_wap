/**
 * Created by pigcms_21 on 2015/2/5.
 */
var t = '';
$(function(){
    load_page('.app__content', load_url, {page:'supplier_goods_content'}, '');

    //分页
    $('.pagenavi > a').live('click', function(e){
        var p = $(this).attr('data-page-num');
        $('.app__content').load(load_url, {page: 'supplier_goods_content', 'p': p}, '');
    });
    
        $('.js-apply-to-fx').live('click', function(){
        var products = $(this).data('id');
        $.post(supplier_goods_url, {'products': products}, function(data){
            if (!data.err_code) {
                $('.notifications').html('<div class="alert in fade alert-success">' + data.err_msg + '</div>');
                load_page('.app__content', load_url, {page:'supplier_market_content', 'category_id': category_id, 'category_fid': category_fid, 'keyword': keyword}, '', function(){
                    if (text != '' && text != undefined && index != undefined) {
                        $('.chosen-single > span').text(text);
                        $('.active-result').not(index).removeClass('result-selected highlighted')
                        $('.active-result').eq(index).addClass('result-selected highlighted');
                    }
                    if (keyword != '' && keyword != undefined) {
                        $('.js-list-search > .js-keyword').val(keyword);
                    }
                });
            } else {
                $('.notifications').html('<div class="alert in fade alert-error">' + data.err_msg + '</div>');
            }
            t = setTimeout('msg_hide()', 3000);
        })
    })

    $('.js-batch-apply-to-fx').live('click', function(){
        if ($('.js-check-toggle:checked').length == 0) {
            $('.notifications').html('<div class="alert in fade alert-error"><a href="javascript:;" class="close pull-right">×</a>请选择商品。</div>');
            $('body').append('<div class="notify-backdrop fade in"></div>');
            return false;
        }
        var products = [];
        $('.js-check-toggle:checked').each(function(i){
            products[i] = $(this).val();
        })
        $.post(supplier_goods_url, {'products': products.toString()}, function(data){
            if (!data.err_code) {
                $('.notifications').html('<div class="alert in fade alert-success">' + data.err_msg + '</div>');
                load_page('.app__content', load_url, {page:'supplier_market_content', 'category_id': category_id, 'category_fid': category_fid, 'keyword': keyword}, '', function(){
                    if (text != '' && text != undefined && index != undefined) {
                        $('.chosen-single > span').text(text);
                        $('.active-result').not(index).removeClass('result-selected highlighted')
                        $('.active-result').eq(index).addClass('result-selected highlighted');
                    }
                    if (keyword != '' && keyword != undefined) {
                        $('.js-list-search > .js-keyword').val(keyword);
                    }
                });
            } else {
                $('.notifications').html('<div class="alert in fade alert-error">' + data.err_msg + '</div>');
            }
            t = setTimeout('msg_hide()', 3000);
        })
    })
})
