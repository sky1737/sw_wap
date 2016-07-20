/**
 * Created by pigcms_21 on 2015/2/5.
 */
var t = '';
var category_id = 0;
var category_fid = 0;
var index = 0;
var text = '';
var keyword = '';
var address_text = '';
var address_index = 0;
var product_id = '';
var address_id = 0;
$(function(){
    load_page('.app__content', load_url, {page:'market_content'}, '', function(){
        /*$.post(service_url, {'type': 'check'}, function(data){
            if (data.err_code > 0) {
                var html = '<div class="modal-backdrop fade in"></div><div class="modal modal-contact hide fade in" data-reactid=".0" aria-hidden="false" style="margin-top: -1000px; display: block;"><div class="modal-header"><h6 class="modal-title">设置分销客服联系方式</h6></div><div class="modal-body"><div class="ui-message-warning">为了保障供销两端的有效沟通和维权，您必须设置客服联系方式。</div><form class="form-horizontal"><div class="control-group"><label class="control-label">客服电话：</label><div class="controls"><input class="input-large" type="text" name="mobile" /></div></div><div class="control-group"><label class="control-label">客服 QQ：</label><div class="controls"><input class="input-large" type="text" name="qq" /></div></div><div class="control-group"><label class="control-label">客服微信：</label><div class="controls"><input class="input-large" type="text" name="weixin" /></div></div><div class="form-actions"><input type="button" value="提交" class="btn btn-primary" /><input type="button" value="取消" class="btn btn-cancel" /></div></form></div></div>';
                $('body').append(html);
                $('.modal').animate({'margin-top': ($(window).scrollTop() + $(window).height() * 0.05) + 'px'}, "slow");
            }
        })*/
    });

    $('.btn-primary').live('click', function(){
        var tel = $("input[name='mobile']").val();
        var qq = $("input[name='qq']").val();
        var weixin = $("input[name='weixin']").val();
        $.post(service_url, {'type': 'add', 'tel': tel, 'qq': qq, 'weixin': weixin}, function(data){
            if (data.err_code == 0) {
                $('.notifications').html('<div class="alert in fade alert-success">' + data.err_msg + '</div>');
                $('.modal').animate({'margin-top': '-' + ($(window).scrollTop() + $(window).height()) + 'px'}, "slow",function(){
                    $('.modal-backdrop,.modal').remove();
                });
            } else {
                $('.notifications').html('<div class="alert in fade alert-error">' + data.err_msg + '</div>');
            }
            t = setTimeout('msg_hide()', 3000);
        })
    })

    $('.btn-cancel').live('click', function(){
        $('.modal').animate({'margin-top': '-' + ($(window).scrollTop() + $(window).height()) + 'px'}, "slow",function(){
            $('.modal-backdrop,.modal').remove();
        });
    })


    //分页
    $('.pagenavi > a').live('click', function(e){
        var p = $(this).attr('data-page-num');
        var category = $('.result-selected').data('option-array-index');
        category = category.split('|');
        category_fid = category[0];
        category_id = category[1];
        if (category_fid == 0) {
            category_fid = category_id;
            category_id = 0;
        } else {
            category_fid = 0;
        }
        index = $('.result-selected').index('.active-result');
        text = $('.result-selected').text();
        keyword = $.trim($('.js-list-search > .js-keyword').val());
        $('.app__content').load(load_url, {page: 'market_content', 'p': p, 'category_id': category_id, 'category_fid': category_fid, 'keyword': keyword}, function(){
            $('.chosen-single > span').text(text);
            $('.active-result').not(index).removeClass('result-selected highlighted')
            $('.active-result').eq(index).addClass('result-selected highlighted');
            $('.js-list-search > .js-keyword').val(keyword);
        });
    })

    //选择分类触发
    $('.active-result').live('click', function(){
        var category = $(this).data('option-array-index');
        category = category.split('|');
        category_fid = category[0];
        category_id = category[1];
        if (category_fid == 0) {
            category_fid = category_id;
            category_id = 0;
        } else {
            category_fid = 0;
        }
        index = $('.result-selected').index('.active-result');
        text = $('.result-selected').text();
        load_page('.app__content', load_url, {page: 'market_content', 'category_id': category_id, 'category_fid': category_fid}, '', function(){
            $('.chosen-single > span').text(text);
            $('.active-result').not(index).removeClass('result-selected highlighted')
            $('.active-result').eq(index).addClass('result-selected highlighted');
        });
    })

    $('.market-search-btn').live('click', function(){
        var category = $('.result-selected').data('option-array-index');
        category = category.split('|');
        category_fid = category[0];
        category_id = category[1];
        if (category_fid == 0) {
            category_fid = category_id;
            category_id = 0;
        } else {
            category_fid = 0;
        }
        index = $('.result-selected').index('.active-result');
        text = $('.result-selected').text();
        keyword = $.trim($('.js-list-search > .js-keyword').val());
        load_page('.app__content', load_url, {page: 'market_content', 'category_id': category_id, 'category_fid': category_fid, 'keyword': keyword}, '', function(){
            $('.chosen-single > span').text(text);
            $('.active-result').not(index).removeClass('result-selected highlighted')
            $('.active-result').eq(index).addClass('result-selected highlighted');
            $('.js-list-search > .js-keyword').val(keyword);
            $('.js-list-search > .js-keyword').focus();
        });
    })

    /*$('.js-add-to-shop,.js-batch-add-to-shop').live('click', function(){
        var product_ids = [];
        if ($(this).hasClass('js-add-to-shop')) {
            product_id = $(this).data('id');
        }
        if (product_id != '' && product_id != undefined) {
            product_ids[0] = product_id;
        } else {
            $('.js-check-toggle:checked').each(function(i){
                product_ids[i] = $(this).val();
            })
        }
        if (product_ids.length <= 0) {
            $('.notifications').html('<div class="alert in fade alert-error"><a href="javascript:;" class="close pull-right">×</a>请选择商品。</div>');
            $('body').append('<div class="notify-backdrop fade in"></div>');
            return false;
        }
        var obj = this;
        if ($(obj).hasClass('ui-btn')) {
            $('.js-check-toggle:checked').each(function(i){
                $(this).closest('tr').find('td:last-child').children('.js-opts').html('添加中...');
            })
            var top = (parseFloat($(obj).offset().top) - 23);
            var left  = (parseFloat($(obj).offset().left) - 432);
        } else {
            var top = (parseFloat($(obj).offset().top) - 23);
            var left  = (parseFloat($(obj).offset().left) - 405);
            obj = $(obj).parent('.js-opts');
            $(obj).html('添加中...');
        }
        $.post(market_url, {'type': 'fx', 'product_ids': product_ids}, function(data){
            if (data.err_code == 0) {
                $('.popover-logistics').remove();
                var html ='<div class="popover-logistics popover left" style="display: block; top: ' + top + 'px; left: ' + left + 'px;">';
                html += '   <div class="arrow"></div>';
                html += '   <div class="popover-inner">';
                html += '       <div class="popover-content">';
                html += '           <div class="logistics-content js-logistics-region">';
                html += '               <div class="logistics-success"><div class="success-container">';
                html += '                   <i class="ui-icon-success success-circle"></i>';
                html += '                   <div class="success-content">';
                html += '                       <h3 class="success-title">添加成功!</h3>';
                html += '                       <p class="success-tips">';
                html += '                           您现在可以进入<a href="' + soldout_url + '" target="_blank" class="new-window">微商城</a>管理您的商品。';
                html += '                       </p>';
                html += '                   </div>';
                html += '               </div>';
                html += '           </div>';
                html += '       </div>';
                html += '   </div>';
                html += '</div>';
                html += '</div>';
                $('body').append(html);
                if ($(obj).hasClass('ui-btn')) {
                    $('.js-check-toggle:checked').each(function(i){
                        $(this).closest('tr').find('td:last-child').children('.js-opts').html('已添加');
                    })
                } else {
                    $(obj).html('已添加');
                }
            }
        })
    })*/

    /*$('.js-add-to-shop,.js-batch-add-to-shop').live('click', function(){
        var obj = this;
        if ($(this).data('id') != '' && $(this).data('id') != undefined) {
            $('.js-add-active').removeClass('js-add-active');
            $(this).addClass('js-add-active');
            product_id = $(this).data('id');
            var position = 'left';
            var top = (parseFloat($(obj).offset().top) - 23);
            var left = (parseFloat($(obj).offset().left) - 432);
        } else {
            if ($('.js-check-toggle:checked').length == 0) {
                $('.notifications').html('<div class="alert in fade alert-error"><a href="javascript:;" class="close pull-right">×</a>请选择商品。</div>');
                $('body').append('<div class="notify-backdrop fade in"></div>');
                return false;
            }
            var position = 'top';
            var top = (parseFloat($(obj).offset().top) - 244);
            var left = (parseFloat($(obj).offset().left) - 170);
        }
        $.post(delivery_address_url, '', function(data) {
            var address = $.parseJSON(data);
            var html = '<div class="popover-logistics popover ' + position + '" style="display: block; top: ' + top + 'px; left: ' + left + 'px;"><div class="arrow"></div>';
            html += '   <div class="popover-inner">';
            html += '       <div class="popover-content">';
            html += '           <div class="logistics-content js-logistics-region"><div>';
            html += '           <div class="logistics-header">编辑物流方式</div>';
            html += '           <form class="form-horizontal select-address">';
            html += '           <div class="control-group">';
            html += '               <label class="control-label control-label-logis">物流设置：</label>';
            html += '               <div class="controls">';
            html += '                   <div class="control-action">';
            html += '                       <label class="radio">';
            html += '                           <input type="radio" name="type" value="0" checked="">直接发给买家（使用买家收货地址）';
            html += '                       </label>';
            html += '                       <label class="radio">';
            html += '                           <input type="radio" name="type" value="1">先发货给我，我再发给买家（使用指定地址）';
            html += '                       </label>';
            html += '                   </div>';
            html += '               </div>';
            html += '               <div class="controls">';
            if (address == '' || address == undefined) {
                var style = 'width: 230px;display:none';
            } else {
                var style = 'width: 230px;display:block';
            }
            html += '                   <div class="select2-container js-select margin-16" id="s2id_autogen1" style="' + style + '">';
            html += '                       <a href="javascript:void(0)" onclick="return false;" class="select2-choice" tabindex="-1">';
            if (address != '') {
                address_id = address[0]['address_id'];
                var tmp_address_detail = address[0]['province'] + ' ' + address[0]['city'] + ' ' + address[0]['area'] + ' ' + address[0]['address'];
            } else {
                var tmp_address_detail = '您还没有收货地址';
                var tmp_address_id = 0;
            }
            html += '                           <span class="select2-chosen">' + tmp_address_detail + '</span>';
            html += '                           <abbr class="select2-search-choice-close"></abbr>';
            html += '                           <span class="select2-arrow"><b></b></span>';
            html += '                       </a>';
            html += '                   </div>';
            html += '               </div>';
            html += '           </div>';
            html += '           <div class="control-group">';
            html += '               <div class="controls">';
            html += '                   <a href="javascript:;" class="js-add-new margin-16">添加收货地址</a>';
            html += '               </div>';
            html += '           </div>';
            html += '           <div class="control-group">';
            html += '               <div class="controls">';
            html += '                   <a href="javascript:;" class="btn btn-primary js-confirm">确定</a>';
            html += '                   <a href="javascript:;" class="btn js-cancel">取消</a>';
            html += '               </div>';
            html += '           </div>';
            html += '           </form>';
            html += '           <form class="form-horizontal add-address" style="display: none">';
            html += '               <div class="control-group">';
            html += '                   <label class="control-label">收货人</label>';
            html += '                   <div class="controls">';
            html += '                       <input type="text" name="name" placeholder="请填写收货人姓名" />';
            html += '                   </div>';
            html += '               </div>';
            html += '               <div class="control-group">';
            html += '                   <label class="control-label">手机号码</label>';
            html += '                   <div class="controls">';
            html += '                       <input type="text" name="tel" placeholder="请填写收货人手机号码" maxlength="11" />';
            html += '                   </div>';
            html += '               </div>';
            html += '               <div class="control-group">';
            html += '                   <label class="control-label">地区信息</label>';
            html += '                   <div class="controls">';
            html += '                       <select name="province" class="js-province-select input-small" id="s1">';
            html += '                           <option value="">选择省份</option>';
            html += '                       </select>';
            html += '                       <select name="city" class="js-city-select input-small" id="s2">';
            html += '                           <option value="">选择城市</option>';
            html += '                       </select>';
            html += '                       <select name="area" class="js-area-select input-small" id="s3">';
            html += '                           <option value="">选择地区</option>';
            html += '                       </select>';
            html += '                   </div>';
            html += '               </div>';
            html += '               <div class="control-group">';
            html += '                   <label class="control-label">详细地址</label>';
            html += '                   <div class="controls">';
            html += '                       <input type="text" name="address" placeholder="请填写街道门牌信息" />';
            html += '                   </div>';
            html += '               </div>';
            html += '               <div class="control-group">';
            html += '                   <label class="control-label">邮政编码</label>';
            html += '                   <div class="controls">';
            html += '                       <input type="text" name="zipcode" placeholder="请填写邮政编码" />';
            html += '                   </div>';
            html += '               </div>';
            html += '               <div class="control-group">';
            html += '                   <div class="controls">';
            html += '                       <a href="javascript:;" class="btn btn-primary js-add">确定</a>';
            html += '                       <a href="javascript:;" class="btn js-back">取消</a>';
            html += '                   </div>';
            html += '               </div>';
            html += '           </form>';
            html += '       </div>';
            html += '   </div>';
            html += ' </div>';
            html += ' </div>';
            html += '</div>';
            $('body').append(html);
            getProvinces('s1', '');
            $('#s1').change(function(){
                $('#s2').html('<option>选择城市</option>');
                if($(this).val() != ''){
                    getCitys('s2','s1','');
                }
                $('#s3').html('<option>选择地区</option>');
            });
            $('#s2').change(function () {
                getAreas('s3', 's2', '');
            });
            html = '<div class="select2-drop select2-display-none select2-with-searchbox select2-drop-active" id="select2-drop" style="top: ' + (parseFloat($('.select2-container').offset().top) + parseFloat($('.select2-container').height())) + 'px; left: ' + $('.select2-container').offset().left + 'px; width: 230px;">';
            html += '   <div class="select2-search">';
            html += '       <input type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input" />';
            html += '   </div>';
            html += '   <ul class="select2-results">';
            if (address != '' && address != undefined) {
                for (i in address) {
                    html += '       <li class="select2-results-dept-0 select2-result select2-result-selectable" data-id="' + address[i]['address_id'] + '">';
                    html += '           <div class="select2-result-label"><span class="select2-match"></span>' + address[i]['province'] + ' ' + address[i]['city'] + ' ' + address[i]['area'] + ' ' + address[i]['address'] + '</div>';
                    html += '       </li>';
                }
            }
            html += '   </ul>';
            html += '</div>';
            $('body').append(html);
        })
    })*/

    $('.select2-container').live('click', function(){
        if ($(this).hasClass('select2-dropdown-open')) {
            $(this).removeClass('select2-container-active select2-dropdown-open');
            $('.select2-input').val('');
            $('.select2-drop').hide();
            $('.select2-results > .select2-no-results').remove();
            $('.select2-results > .select2-result').show();
        } else {
            $(this).addClass('select2-container-active select2-dropdown-open');
            $('.select2-results > .select2-result').removeClass('select2-highlighted');
            $('.select2-results > .select2-result').eq(address_index).addClass('select2-highlighted');
            $('.select2-drop').css({'top': (parseFloat($('.select2-container').offset().top) + parseFloat($('.select2-container').height())), 'left': (parseFloat($('.select2-container').offset().left))});
            $('.select2-drop').show();
            $('.select2-input').focus();
        }
    })

    $('.select2-input').live('keyup', function(){
        if (event.keyCode == 38 && $('.select2-container').hasClass('select2-dropdown-open')) { //向上
            if ($('.select2-highlighted').prev('.select2-result').length > 0) {
                var index = $('.select2-highlighted').index('.select2-result');
                $('.select2-result').eq(index).removeClass('select2-highlighted');
                $('.select2-result').eq(index).prev('.select2-result').addClass('select2-highlighted');
            }
            var scrollTop = $('.select2-results').scrollTop();
            var top = $('.select2-highlighted').position().top;
            if (top == -25) {
                $('.select2-results').scrollTop(scrollTop - 25);
            }
            return true;
        }
        if (event.keyCode == 40 && $('.select2-container').hasClass('select2-dropdown-open')) { //向下
            if ($('.select2-highlighted').next('.select2-result').length > 0) {
                var index = $('.select2-highlighted').index('.select2-result');
                $('.select2-result').eq(index).removeClass('select2-highlighted');
                $('.select2-result').eq(index).next('.select2-result').addClass('select2-highlighted');
            }
            var scrollTop = $('.select2-highlighted').position().top + $('.select2-results').scrollTop();
            if (scrollTop > 175) {
                $('.select2-results').scrollTop((scrollTop - 175));
            }
            return true;
        }
        if (event.keyCode == 13 && $('.select2-container').hasClass('select2-dropdown-open') && $('.select2-highlighted').length > 0) {
            address_text = $('.select2-highlighted').text();
            address_index = $('.select2-highlighted').index('.select2-results > .select2-result');
            address_id = $('.select2-highlighted').data('id');
            $('.select2-chosen').text(address_text);
            $('.select2-container').removeClass('select2-container-active select2-dropdown-open');
            $('.select2-drop').hide();
        }
        $('.select2-results > .select2-no-results').remove();
        $('.select2-results > .select2-result').hide();
        var value = $.trim($(this).val());
        var flag = false;
        $('.select2-results > .select2-result').each(function(i){
            if ($(this).text().indexOf(value) >= 0) {
                if (!flag) {
                    $(this).siblings('.select2-result').removeClass('select2-highlighted');
                    $(this).addClass('select2-highlighted');
                }
                $(this).show();
                flag = true;
            }
        })
        if (!flag) {
            $('.select2-results').append('<li class="select2-no-results">没有找到匹配项</li>');
        }
    })

    $('.select2-results > .select2-result').live('hover', function(){
        if (event.type == 'mouseover') {
            $(this).siblings('.select2-result').removeClass('select2-highlighted');
            $(this).addClass('select2-highlighted');
        } else {
            $(this).removeClass('select2-highlighted');
        }
    })

    $('.select2-results > .select2-result').live('click', function(){
        address_text = $(this).text();
        address_index = $(this).index('.select2-results > .select2-result');
        address_id = $(this).data('id');
        $(this).siblings('.select2-result').removeClass('select2-highlighted');
        $(this).addClass('select2-highlighted');
        $('.select2-chosen').text(address_text);
        $('.select2-container').removeClass('select2-container-active select2-dropdown-open');
        $('.select2-drop').hide();
    })

    $('.js-cancel').live('click', function(){
        $('.popover-logistics').remove();
    })

    $('.select2-input').live('blur', function(){
        $(this).focus();
    })

    $(window).keydown(function(event){
        if (event.keyCode == 27) {
            $('.select2-container').removeClass('select2-container-active select2-dropdown-open');
            $('.select2-drop').hide();
        }
    })

    $('.js-add-new').live('click', function(){
        $('.select-address').hide();
        $('.add-address').show();
    })

    $('.js-back').live('click', function(){
        $('.add-address').hide();
        if ($.trim($('.select2-results').html()) == '') {
            $("input[name='type']:radio").eq(0).attr('checked', true);
        }
        $('.select-address').show();
    })

    $('.js-add').live('click', function(){
        var name = $.trim($("input[name='name']").val());
        var tel = $.trim($("input[name='tel']").val());
        var province_id = $.trim($('.js-province-select').val());
        var city_id = $.trim($('.js-city-select').val());
        var area_id = $.trim($('.js-area-select').val());
        var address_detail = $.trim($("input[name='address']").val());
        var zipcode = $.trim($("input[name='zipcode']").val());
        var flag = true;

        $("input[name='name']").closest('.control-group').removeClass('error');
        $("input[name='name']").next('.error-message').remove();
        if (name == '') {
            $("input[name='name']").closest('.control-group').addClass('error');
            $("input[name='name']").after('<p class="help-block error-message">收货人不能为空</p>');
            flag = false;
        }
        $("input[name='tel']").closest('.control-group').removeClass('error');
        $("input[name='tel']").next('.error-message').remove();
        if (tel == '' || !/^[0-9]{11}$/.test(tel)) {
            $("input[name='tel']").closest('.control-group').addClass('error');
            $("input[name='tel']").after('<p class="help-block error-message">手机号码不正确</p>');
            flag = false;
        }
        $('.js-area-select').closest('.control-group').removeClass('error');
        $('.js-area-select').next('.error-message').remove();
        if ($('.js-area-select').length == 1 && area_id == '') {
            $('.js-area-select').closest('.control-group').addClass('error');
            $('.js-area-select').after('<p class="help-block error-message">地区没有选择</p>');
            flag = false;
        }
        $("input[name='address']").closest('.control-group').removeClass('error');
        $("input[name='address']").next('.error-message').remove();
        if ($("input[name='address']").length == 1 && address_detail == ''){
            $("input[name='address']").closest('.control-group').addClass('error');
            $("input[name='address']").after('<p class="help-block error-message">地址没有填写</p>');
            flag = false;
        }
        $("input[name='zipcode']").closest('.control-group').removeClass('error');
        $("input[name='zipcode']").next('.error-message').remove();
        if (zipcode == '' || !/^[0-9]{6}$/.test(zipcode)){
            $("input[name='zipcode']").closest('.control-group').addClass('error');
            $("input[name='zipcode']").after('<p class="help-block error-message">邮政编码不正确</p>');
            flag = false;
        }

        if (flag) {
            $.post(delivery_address_url, {'type': 'add', 'name': name, 'tel': tel, 'province': province_id, 'city': city_id, 'area': area_id, 'address': address_detail, 'zipcode': zipcode}, function(data) {
                if (!data.err_code) {
                    address_id = data.err_msg.address_id;
                    $('.notifications').html('<div class="alert in fade alert-success">收货地址添加成功</div>');
                    $('.select2-results > .select2-result').removeClass('select2-highlighted');
                    $('.select2-drop > .select2-results').append('<li class="select2-results-dept-0 select2-result select2-result-selectable select2-highlighted" data-id="' + data.err_msg.address_id + '"><div class="select2-result-label"><span class="select2-match"></span>' + data.err_msg.province + ' ' + data.err_msg.city + ' ' + data.err_msg.area + ' ' + data.err_msg.address +'</div></li>');
                    $('.select2-choice > .select2-chosen').text(data.err_msg.province + ' ' + data.err_msg.city + ' ' + data.err_msg.area + ' ' + data.err_msg.address);
                    $('.select2-container').show();
                    address_index = $('.select2-results > .select2-result').length - 1;
                    $('.add-address').hide();
                    $('.select-address').show();
                } else {
                    $('.notifications').html('<div class="alert in fade alert-error">' + data.err_msg + '</div>');
                }
                t = setTimeout('msg_hide()', 3000);
            })
        }
    })

    $("input[name='type']:radio").live('click', function(){
        if ($(this).is(':checked') && $.trim($('.select2-drop > .select2-results').html()) == '') {
            $('.select-address').hide();
            $('.add-address').show();
        }
    })

    $('.js-confirm').live('click', function(){
        var product_ids = [];
        if (product_id != '' && product_id != undefined) {
            product_ids[0] = product_id;
        } else {
            $('.js-check-toggle:checked').each(function(i){
                product_ids[i] = $(this).val();
            })
        }
        var delivery_type = $("input[name='type']:checked").val();
        if (delivery_type == 0) {
            var delivery_address_id = 0;
        } else {
            var delivery_address_id = address_id;
        }
        $.post(market_url, {'type': 'fx', 'product_ids': product_ids, 'address_id': delivery_address_id, 'delivery_type': delivery_type}, function(data){
            if (data.err_code == 0) {
                $('.popover-logistics').remove();
                var html ='<div class="popover-logistics popover left" style="display: block; top: ' + (parseFloat($('.js-add-active').offset().top) - 23) + 'px; left: ' + (parseFloat($('.js-add-active').offset().left) - 432) + 'px;">';
                html += '   <div class="arrow"></div>';
                html += '   <div class="popover-inner">';
                html += '       <div class="popover-content">';
                html += '           <div class="logistics-content js-logistics-region">';
                html += '               <div class="logistics-success"><div class="success-container">';
                html += '                   <i class="ui-icon-success success-circle"></i>';
                html += '                   <div class="success-content">';
                html += '                       <h3 class="success-title">添加成功!</h3>';
                html += '                       <p class="success-tips">';
                html += '                           您现在可以进入<a href="' + soldout_url + '" target="_blank" class="new-window">微商城</a>管理您的商品。';
                html += '                       </p>';
                html += '                   </div>';
                html += '               </div>';
                html += '           </div>';
                html += '       </div>';
                html += '   </div>';
                html += '</div>';
                html += '</div>';
                $('body').append(html);
                $('.js-add-active').parent('.js-opts').html('已添加');
            }
        })
    })

    $(".js-keyword").live('keyup', function(e){
        if (event.keyCode == 13) { //回车
            $('.market-search-btn').trigger('click');
        }
    })

    $('body').click(function(e){
        var _con = $('.popover-logistics');   // 设置目标区域
        var _con2 = $('.select2-result');
        var _con3 = $('.select2-search');
        if((!_con.is(e.target) && _con.has(e.target).length === 0) && (!_con2.is(e.target) && _con2.has(e.target).length === 0) && (!_con3.is(e.target) && _con3.has(e.target).length === 0)){ // Mark 1
            $('.popover-logistics').remove();
            $('.select2-drop').hide();
        }
    })
})

//function msg_hide() {
//    $('.notifications').html('');
//    clearTimeout(t);
//}
