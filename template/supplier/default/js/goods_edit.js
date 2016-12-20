var cat_id = '', cat_name = '', group_ids = '', t = null;
//已添加商品分组
var edit_group_ids = [];
is_get_group = false;
//商品分组数组
var goodsCategory = {};
//商品规格数组
var sku_name_obj = {};
//运费模板列表
var trade_tpl_obj = {};
var doMouseDownTimmer = 0;
// 是否更改产品规格
var is_edit_sku = false;
$(function () {
	//refresh_goodsCategory();
	load_page('.app__content', load_url, {page: 'edit_content'}, '', function () {
		cat_id = $('.widget-goods-klass > .current').attr('data-id');
		/*cat_name = $('.widget-goods-klass > .current').attr('data-name');
		 $('#base-info-region .js-goods-class').html(cat_name);*/
		cat_name = $('#base-info-region .js-goods-class').html();
		$('#base-info-region input[name="class_ids"]').val(cat_id);

		$('#start_sold_time').datetimepicker({
			dateFormat: "yy-mm-dd",
			timeFormat: "HH:mm:ss",
			minDate: new Date(+new Date + 6e4),
			showSecond: true,
			onSelect: function () {
				$('#start_sold_time').siblings("input").trigger("click");
			}
		});
		var product_ids = $(".js-btn-edit").attr('data-product-id');
		/*var cat_ids = '';
		 $.each($('.chosen-container-multi .search-choice'), function (i, item) {
		 cat_ids += $(item).attr('data-id') + ',';
		 });
		 group_ids = cat_ids;*/


		//筛选商品栏目属性值
		$('.checkproperty').live('click', function (e) {
			if ($("#is_fenxiao").val() == 0) {
				if ($(this).hasClass("red")) {
					$(this).removeClass("red");
				}
				else {
					$(this).addClass("red");
				}
			}
			else {
				//button_box($(this), e, 'left', 'confirm', '确认删除？', function(){
				button_box($(this), e, 'left', 'tips', '只有该商品的<b style="color:red">供货商</b>可以修改！', function () {

					close_button_box();
				})
			}
		})

		//读取商品栏目属性
		$.get(get_system_product_property_list, {catid: cat_id, pid: product_ids}, function (result) {
			var htmls = '';
			if (result.err_code == 998) {
				// layer_tips(1,'该商品栏目缺少筛选属性，请联系系统管理员添加该属性后再操作');
			}
			var obj = result.err_msg;
			for (var i in obj) {
				if (typeof(obj[i].cat_name) != 'undefined') {
					htmls += '<span>' + obj[i].cat_name + '</span>';
				}

				if (obj[i].error_code != '998') {
					htmls += '<div class="control-group "><label class="control-label">' + obj[i].name + '：</label><div class="controls">';
					for (var j in obj[i].property_value) {
						if (obj[i].property_value[j].selected == 'selected') {
							htmls += '<label class="radio inlines"> <input type="button" class="checkproperty red" data-pid="' + obj[i].property_value[j].pid + '" data-vid="' + obj[i].property_value[j].vid + '"  value="' + obj[i].property_value[j].value + '"> </label>  ';
						}
						else {
							htmls += '<label class="radio inlines"> <input type="button" class="checkproperty" data-pid="' + obj[i].property_value[j].pid + '" data-vid="' + obj[i].property_value[j].vid + '"  value="' + obj[i].property_value[j].value + '"> </label>  ';
						}
					}
					htmls += "</div></div>";
				}


			}
			$(".sxsx .group-inner2").html(htmls);
		})

		//读取商品规格
		$.post(get_product_property_url, function (result) {
			if (result.err_code == 0) {
				sku_name_obj = result.err_msg;
				var html = '<div id="select2-drop-mask" class="select2-drop-mask" style="display: none;"></div><div class="select2-drop select2-display-none select2-with-searchbox select2-drop-active" id="select2-drop"><div class="select2-search"><input type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input" maxlength="4"/></div><ul class="select2-results"></ul></div>';
				$('body').append(html);
			}
			else {
				layer_tips(1, result.err_msg);
				if (result.err_code == 999) {
					$('.js-goods-sku').remove();
				}
			}
		});
		//读取运费模板列表
		get_trade_delivery(false, function () {
			var html = '<div id="select3-drop-mask" class="select2-drop-mask" style="display:none;"></div><div class="select2-drop select2-display-none select2-with-searchbox select2-drop-active" id="select3-drop"><div class="select2-search"><input type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input" maxlength="4"/></div><ul class="select2-results"></ul></div>';
			$('body').append(html);
		});

		customField.init();
		customField.setHtml($('#edit_custom').attr('custom-field'));

		var defaultDesc = '<div class="goods-details-block" style="background:#fff;"><h4>商品详情区</h4><p>点击进行编辑</p></div>';
		var defaultFieldObj = $('.js-config-region .app-field');
		defaultFieldObj.find('.control-group').data('info', defaultFieldObj.find('.control-group .custom-richtext').html());
		if ($.trim(defaultFieldObj.find('.control-group .custom-richtext').html()).length == 0) {
			defaultFieldObj.find('.control-group').html(defaultDesc);
		}
		defaultFieldObj.data({'intro': ''}).click(function () {
			$('.app-entry .app-field').removeClass('editing');
			$(this).addClass('editing');
			$('.js-sidebar-region').html(defaultHtmlObj());

			var top = "244";
			if ($(this).closest('.app-field').offset().top - $('.app-preview').offset().top > 244) {
				top = $(this).closest('.app-field').offset().top - $('.app-preview').offset().top;
			}

			$('.app-sidebar').css('margin-top', top);
			$('.js-goods-sidebar-sub-title').show();

			//分类名
			$('.js-sidebar-region input[name="title"]').val(defaultFieldObj.data('cat_name')).live('blur', function () {
				var val = $(this).val();
				if (val.length == 0 || val.length > 50) {
					layer_tips(1, '分类名不能少于一个字或者多余50个字');
				}
				var customTitleObj = $('.js-config-region .custom-title .title');
				if (val.length == 0) {
					defaultFieldObj.data('cat_name', '');
					customTitleObj.html('微页面分类名');
					$('.js-config-region h1 span').empty();
				}
				else {
					defaultFieldObj.data('cat_name', val);
					customTitleObj.html(val);
					$('.js-config-region h1 span').html(val);
				}
			});
			//第一优先级
			$('.js-sidebar-region select[name="first_sort"]').change(function () {
				defaultFieldObj.data('first_sort', $(this).val());
			}).find('option[value="' + defaultFieldObj.data('first_sort') + '"]').prop('selected', true);
			//第二优先级
			$('.js-sidebar-region select[name="second_sort"]').change(function () {
				defaultFieldObj.data('second_sort', $(this).val());
			}).find('option[value="' + defaultFieldObj.data('second_sort') + '"]').prop('selected', true);

			var domHtml = defaultFieldObj.find('.control-group');

			var ueditor = new window.UE.ui.Editor({
				toolbars: [["bold", "italic", "underline", "strikethrough", "forecolor", "backcolor", "justifyleft", "justifycenter", "justifyright", "|", "insertunorderedlist", "insertorderedlist", "blockquote"], ["emotion", "uploadimage", "insertvideo", "link", "removeformat", "|", "rowspacingtop", "rowspacingbottom", "lineheight", "paragraph", "fontsize"], ["inserttable", "deletetable", "insertparagraphbeforetable", "insertrow", "deleterow", "insertcol", "deletecol", "mergecells", "mergeright", "mergedown", "splittocells", "splittorows", "splittocols"]],
				autoClearinitialContent: false,
				autoFloatEnabled: true,
				wordCount: true,
				elementPathEnabled: false,
				maximumWords: 10000,
				initialFrameWidth: 458,
				initialFrameHeight: 108,
				focus: false
			});
			ueditor.addListener("blur", function () {
				var ue_con = ueditor.getContent();
				if (ue_con != '') {
					domHtml.data('info', ue_con).html('<div class="custom-richtext">' + ue_con + '</div>');
				}
				else {
					domHtml.data('info', '').html(defaultDesc);
				}
			});
			ueditor.addListener("contentChange", function () {
				var ue_con = ueditor.getContent();
				if (ue_con != '') {
					domHtml.data('info', ue_con).html('<div class="custom-richtext">' + ue_con + '</div>');
				}
				else {
					domHtml.data('info', '').html(defaultDesc);
				}
			});
			ueditor.render($('.js-editor')[0]);
			ueditor.ready(function () {
				if (domHtml.data('info')) {
					ueditor.setContent(domHtml.data('info'));
				}
			});
		});
		defaultFieldObj.trigger('click');

		$('#edit_custom').remove();

		$('.js-fields-region').click(function () {
			$('.js-goods-sidebar-sub-title').hide();
		});
	});

	var defaultHtmlObj = function () {
		return '<div><form class="form-horizontal"><div class="control-group"><script class="js-editor" type="text/plain"></script></div></form></div>';
	};

	/*
	 //刷新商品分组
	 function refresh_goodsCategory() {
	 $.post(get_goodsCategory_url, function (result) {
	 if (result.err_code == 0) {
	 goodsCategory = result.err_msg;
	 if (edit_group_ids.length > 0) {
	 var goods_li = '';
	 $.each(goodsCategory, function (i, item) {
	 if ($.inArray(item.group_id, edit_group_ids) > -1) {
	 goods_li += '<li class="search-choice" data-id="' + item.group_id + '"><span>' + item.group_name + '</span><a class="search-choice-close"></a></li>';
	 }
	 });
	 $('.chosen-container-multi .chosen-choices').prepend(goods_li);
	 $('.chosen-container-multi .chosen-choices .search-field input').width(25).val('');
	 edit_group_ids = [];
	 }
	 }
	 });
	 }*/

	/*$('.js-refresh-tag').live('click', function () {
	 refresh_goodsCategory();
	 });*/
	/*//选择分类
	 $('.chosen-container-multi').live('click', function (event) {
	 event.stopPropagation();
	 $(this).addClass('chosen-with-drop chosen-container-active').find('.search-field .default').val('');
	 var chosen_results = '';
	 var choice_has_arr = [];
	 $.each($(this).find('.search-choice'), function (i, item) {
	 choice_has_arr.push($(item).attr('data-id'));
	 });
	 $.each(goodsCategory, function (i, item) {
	 if ($.inArray(item.group_id, choice_has_arr) == -1) {
	 chosen_results += '<li class="active-result" data-id="' + item.group_id + '">' + item.group_name + '</li>';
	 }
	 });
	 $(this).find('.chosen-results').html(chosen_results).find('li:first').addClass('highlighted');

	 $(this).find('.chosen-results .active-result').click(function () {
	 var choicesDom = $(this).closest('.chosen-container').find('.chosen-choices');
	 var choiceHtml = '<li class="search-choice" data-id="' + ($(this).attr('data-id')) + '"><span>' + ($(this).html()) + '</span><a class="search-choice-close"></a></li>';
	 if (choicesDom.find('.search-choice').size() > 0) {
	 choicesDom.find('.search-choice:last').after(choiceHtml);
	 } else {
	 choicesDom.prepend(choiceHtml);
	 }
	 var cat_ids = '';
	 $.each($(this).closest('.chosen-container').find('.search-choice'), function (i, item) {
	 cat_ids += $(item).attr('data-id') + ',';
	 });
	 group_ids = cat_ids;
	 });
	 $(this).find('.search-choice-close').click(function (ee) {
	 ee.stopPropagation();
	 $(this).closest('li').remove();
	 var cat_ids = '';
	 $.each($('.search-choice'), function (i, item) {
	 cat_ids += $(item).attr('data-id') + ',';
	 });
	 group_ids = cat_ids;
	 });

	 $('body').bind('click', function () {
	 $('.chosen-container-multi').removeClass('chosen-with-drop chosen-container-active');
	 });
	 });*/


	$('.widget-goods-klass-item').live('click', function () {
		if (!$(this).hasClass('has-children')) {
			$.each($('.widget-goods-klass-item.has-children'), function (i, item) {
				$(item).find('span').html($(item).attr('data-name'));
				$(item).find(':radio').prop('checked', false);
			});
			$(this).addClass('current').siblings().removeClass('current');

			cat_id = $(this).attr('data-id');
			cat_name = $(this).attr('data-name');
		}
	});
	$('.widget-goods-klass-children li').live('click', function () {
		$(this).closest('div').addClass('current').siblings().removeClass('current');
		$(this).closest('div').find('span').html($.trim($(this).text()));
		cat_id = $(this).attr('data-id');
		cat_name = $(this).closest('div').attr('data-name') + ' - ' + $.trim($(this).text());
	});

	$('li.js-switch-step').live('click', function () {
		show_next($(this).attr('data-next-step'));
		return false;
	});
	$('.fm-goods-info').live('submit', function () {
		show_next($('.fm-goods-info button.js-switch-step:visible').attr('data-next-step'));
		return false;
	});

	/* 编辑基本信息 */
	//购买方式
	//$(':radio[name="shop_method"]').live('click',function(){
	//	if($(this).val() == 0){
	//		$('.js-outbuy-tip,.js-buy-url-group').removeClass('hide');
	//		$('#sku-info-region,#other-info-region').hide();
	//	}else{
	//		$('.js-outbuy-tip,.js-buy-url-group').addClass('hide');
	//		$('#sku-info-region,#other-info-region').show();
	//	}
	//});

	/*$('.chosen-container-multi').live('click', function () {
	 $(this).addClass('chosen-with-drop chosen-container-active');
	 $(this).find('.search-field input').width(25).val('');
	 });*/

	//添加规格
	$('.js-add-sku-atom').live('click', function (event) {
		var add_sku_atom_dom = $(this);
		button_box($(this), event, 'bottom', 'multi_txt', '添加规格值（按Enter键完成）', function () {
			var atom_list_dom = add_sku_atom_dom.siblings('div.js-sku-atom-list');
			var atom_has_arr = [];
			$.each($(atom_list_dom.find('.sku-atom span')), function (i, item) {

				atom_has_arr.push($(item).html());
			});
			var html = '';
			$.each($('.popover-chosen .select2-search-choice'), function (i, item) {
				var choice_html = $.trim($(item).find('div').html());
				if (atom_has_arr.length > 0) {
					if ($.inArray(choice_html, atom_has_arr) == -1) {
						html += '<div class="sku-atom" style="width:50px;" id="r_' + $(item).attr("check-data-vid") + '" rid="' + $(item).attr("check-data-vid") + '" atom-id="' + $(item).attr('data-vid') + '"><span>' + choice_html + '</span><div class="close-modal small js-remove-sku-atom">×</div><div class="property_value_img" style="cursor:pointer;width:48px; height:48px; line-height:48px; border:1px solid gray;" data-vid="' + $(item).attr('data-vid') + '">添加图片</div></div>';
					}
					else {
						layer_tips(1, '已经添加了相同的规格值');
					}
				}
				else {
					html += '<div class="sku-atom" style="width:50px;" id="r_' + $(item).attr("check-data-vid") + '" rid="' + $(item).attr("check-data-vid") + '" atom-id="' + $(item).attr('data-vid') + '"><span>' + choice_html + '</span><div class="close-modal small js-remove-sku-atom">×</div><div class="property_value_img" style="cursor:pointer;width:48px; height:48px; line-height:48px; border:1px solid gray;" data-vid="' + $(item).attr('data-vid') + '">添加图片</div></div>';
				}
			});
			atom_list_dom.append(html);
			close_button_box();
			if (html != '') {
				create_sku();
			}
		});
	});

	$("input[name='price']").live('blur', function () {
		if ($(this).val().length > 0) {
			if (!/^\d+(\.\d+)?$/.test($(this).val())) {
				layer_tips(1, '只能输入合法的价格');
				return false;
			}
			var float_val = parseFloat($(this).val());
			//var wholesale_product_id = $("input[name='wholesale_product_id']").val();
			//var min_price = parseFloat($("input[name='sale_min_price']").val());
			//var max_price = parseFloat($("input[name='sale_max_price']").val());
			if (float_val > 9999999.99) {
				layer_tips(1, '价格最大为 9999999.99');
				$(this).val('9999999.99');
			}
			if (float_val < 0.01) {
				layer_tips(1, '价格最小为 0.01');
				return false;
			}
			//else if (wholesale_product_id != 0) {
			//    if (float_val > max_price || float_val < min_price) {
			//        $(this).next('div').remove();
			//        $(this).parent('td').addClass('manual-valid-error');
			//        $(this).after('<div style="color:red;">价格区间为 ' + min_price.toFixed(2) + ' - ' + max_price.toFixed(2) + '</div>');
			//    } else {
			//        $(this).next('div').remove();
			//    }
			//}
		}
	});
	$(".js-price").live('blur', function () {
		if ($(this).val().length > 0) {
			var float_val = parseFloat($(this).val());
			//var min_price = parseFloat($(this).data('min-price'));
			//var max_price = parseFloat($(this).data('max-price'));
			$(this).parent('td').removeClass('manual-valid-error');
			$(this).next('.error-message').remove();
			//if (float_val > max_price) {
			//	$(this).val(max_price.toFixed(2));
			//	$(this).parent('td').addClass('manual-valid-error');
			//	$(this).after('<div class="error-message">价格最大为 ' + max_price.toFixed(2) + '</div>');
			//	return false;
			//} else
			if (!/^\d+(\.\d+)?$/.test($(this).val())) {
				layer_tips(1, '只能输入合法的价格');
				return false;
			}
			//else if (float_val < min_price) {
			//	$(this).val(min_price.toFixed(2));
			//	$(this).parent('td').addClass('manual-valid-error');
			//	$(this).after('<div class="error-message">价格最小为 ' + min_price.toFixed(2) + '</div>');
			//	return false;
			//}
			else {
				$(this).val(float_val.toFixed(2));
			}
		}
	});
	$('.js-stock-num').live('blur', function () {
		if ($(this).val().length > 0) {
			if (!/^\d+$/.test($(this).val())) {
				layer_tips(1, '只输入合法的数量');
				return false;
			}
			var total_stock = 0;
			$.each($('.js-stock-num'), function (i, item) {
				if ($(item).val() != '') {
					total_stock += parseInt($(item).val());
				}
			});
			$('input[name="total_stock"]').val(total_stock);
		}
	});
	$("input[name='total_stock']").live('blur', function () {
		if ($(this).val().length > 0) {
			if (!/^\d+$/.test($(this).val())) {
				layer_tips(1, '只输入合法的数量');
				return false;
			}
			if ($('.js-stock-num').length > 0) {
				var total_stock = 0;
				$.each($('.js-stock-num'), function (i, item) {
					if ($(item).val() != '') {
						total_stock += parseInt($(item).val());
					}
				});
				$('input[name="total_stock"]').val(total_stock);
			}
		}
	});

	//删除规格样式
	$('.js-remove-sku-atom').live('click', function () {
		$(this).closest('.sku-atom').remove();
		create_sku();
	});

	// 属性上传图片
	$(".property_value_img").live("click", function () {
		var property_value_img_obj = $(this);
		var vid = $(this).data("vid");
		var rid = $(this).closest("div").parent().attr("rid");
		if (vid == rid) {
			var atom_id = $(this).closest("div").parent().attr("atom-id");
			if (atom_id == vid) {
				layer_tips(1, '网络忙，请稍等');
				return;
			}
			else {
				$(this).attr("data-vid", atom_id);
				vid = atom_id;
			}
		}

		upload_pic_box(1, true, function (pic_list) {
			if (pic_list.length > 0) {
				for (var i in pic_list) {
					$.get(property_value_img, {"vid": vid, "image": pic_list[i]}, function (data) {
						try {
							if (data.err_code == '0') {
								property_value_img_obj.html("<img src='" + pic_list[i] + "' width='48' height='48' />");
							}
							else {
								alert(data.err_msg);
							}
						} catch (e) {
						}
					});
				}
			}
		}, 1);
	})

	//批量设置
	var js_batch_type = '';
	$('.js-batch-price').live('click', function () {
		js_batch_type = 'price';
		$('.js-batch-form').show();
		$('.js-batch-type').hide();
		$('.js-batch-txt').attr('placeholder', '请输入价格');
		$('.js-batch-txt').focus();
	});
	$('.js-batch-market').live('click', function () {
		js_batch_type = 'market';
		$('.js-batch-form').show();
		$('.js-batch-type').hide();
		$('.js-batch-txt').attr('placeholder', '请输入市场价格');
		$('.js-batch-txt').focus();
	});
	$('.js-batch-cost').live('click', function () {
		js_batch_type = 'cost';
		$('.js-batch-form').show();
		$('.js-batch-type').hide();
		$('.js-batch-txt').attr('placeholder', '请输入成本价格');
		$('.js-batch-txt').focus();
	});
	$('.js-batch-stock').live('click', function () {
		js_batch_type = 'stock';
		$('.js-batch-form').show();
		$('.js-batch-type').hide();
		$('.js-batch-txt').attr('placeholder', '请输入库存');
		$('.js-batch-txt').focus();
	});
	$('.js-batch-weight').live('click', function () {
		js_batch_type = 'weight';
		$('.js-batch-form').show();
		$('.js-batch-type').hide();
		$('.js-batch-txt').attr('placeholder', '请输入重量');
		$('.js-batch-txt').focus();
	});
	$('.js-batch-save').live('click', function () {
		var batch_txt = $('.js-batch-txt');
		switch (js_batch_type) {
			case 'cost':
			case 'price':
			case 'market':
				var float_val = parseFloat(batch_txt.val());
				if (float_val > 9999999.99) {
					layer_tips(1, '价格最大为 9999999.99');
					batch_txt.focus();
					return false;
				}
				else if (!/^\d+(\.\d+)?$/.test(batch_txt.val())) {
					layer_tips(1, '只输入合法的价格');
					batch_txt.focus();
					return false;
				}
				else {
					batch_txt.val(float_val.toFixed(2));
				}
				$('.js-goods-stock .js-' + js_batch_type).val(batch_txt.val());
				batch_txt.val('');

				//商品价格
				$("input[name='" + js_batch_type + "']").val(float_val.toFixed(2));
				$("input[name='" + js_batch_type + "']").attr('readonly', true);

				break;
			case 'stock':
				if (!/^\d+$/.test(batch_txt.val())) {
					layer_tips(1, '只输入合法的数量');
					batch_txt.focus();
					return false;
				}
				$('.js-goods-stock .js-stock-num').val(batch_txt.val());

				$('input[name="total_stock"]').val(parseInt(batch_txt.val()) * $('.js-stock-num').size());
				batch_txt.val('');
				break;
			default:
				if (!/^\d+$/.test(batch_txt.val())) {
					layer_tips(1, '只输入合法的数量');
					batch_txt.focus();
					return false;
				}
				$('.js-goods-stock .js-sku_weight').val(batch_txt.val());

				$('input[name="weight"]').val(batch_txt.val());
				batch_txt.val('');
				break;
		}
		$('.js-batch-form').hide();
		$('.js-batch-type').show();
	});

	$('.js-batch-cancel').live('click', function () {
		$('.js-batch-form').hide();
		$('.js-batch-type').show();
	});

	$('.js-add-picture').live('click', function () {
		upload_pic_box(1, true, function (pic_list) {
			if (pic_list.length > 0) {
				for (var i in pic_list) {
					var list_size = $('.js-picture-list .sort').size();
					if (list_size > 7) {
						layer_tips(1, '商品图片最多支持 8 张');
						return false;
					}
					else if (list_size > 0) {
						$('.js-picture-list .sort:last').after('<li class="sort"><a href="' + pic_list[i] + '" target="_blank"><img src="' + pic_list[i] + '"></a><a class="js-delete-picture close-modal small hide">×</a></li>');
					}
					else {
						$('.js-picture-list').prepend('<li class="sort"><a href="' + pic_list[i] + '" target="_blank"><img src="' + pic_list[i] + '"></a><a class="js-delete-picture close-modal small hide">×</a></li>');
					}
				}
			}
		}, 8);
	});

	$('.js-add-message').live('click', function () {
		$('.js-message-container').append('<div class="message-item"><input type="text" name="field_name" value="留言" class="input-mini message-input field-name" maxlength="5"><select class="input-small message-input" name="field-type" class="field-type"><option value="text" selected="">文本格式</option><option value="number">数字格式</option><option value="email">邮件格式</option><option value="date">日期</option><option value="time">时间</option><option value="id_no">身份证号码</option></select><label class="checkbox inline message-input"><input type="checkbox" name="multi_rows" class="multi-rows" value="1" />多行</label><label class="checkbox inline message-input"><input type="checkbox" name="required" class="required" value="1" checked="">必填</label><a href="javascript:;" class="js-remove-message remove-message">删除</a></div>');
	});

	$('.js-remove-message').live('click', function () {
		$(this).closest('.message-item').remove();
	});

	$('.js-message-container select.message-input').live('change', function () {
		if ($(this).val() != 'text') {
			$(this).closest('.message-item').find('input[name="multiple"]').prop({'checked': false, 'disabled': true});
		}
		else {
			$(this).closest('.message-item').find('input[name="multiple"]').prop({'disabled': false});
		}
	});

	$('.js-refresh-delivery').live('click', function () {
		get_trade_delivery(true);
	});
	$('.js-delivery-template').live('click', function () {
		$(this).addClass('select2-dropdown-open select2-container-active');
		var html = '';
		$.each(trade_tpl_obj, function (i, item) {
			html += '<li class="select2-results-dept-0 select2-result select2-result-selectable select2-result-li-' + item.tpl_id + '" data-id="' + item.tpl_id + '"><div class="select2-result-label">' + item.tpl_name + '</div></li>';
		});
		$('#select3-drop .select2-results').html(html);
		$('#select3-drop').css({
			top: ($(this).offset().top + $(this).height() - 2) + 'px',
			left: $(this).offset().left + 'px',
			width: $(this).width() + 'px',
			display: 'block'
		});
		$('#select3-drop-mask').show();
		if ($(this).attr('data-id')) {
			var select_li_dom = $('#select3-drop .select2-result-li-' + $(this).attr('data-id'));
			select_li_dom.addClass('select3-highlighted').siblings().removeClass('select2-highlighted');
			$('#select3-drop .select2-results').scrollTop(25 * (select_li_dom.index() - 3));
		}
		else {
			$('#select3-drop .select2-results .select2-result').eq(0).addClass('select2-highlighted').siblings().removeClass('select2-highlighted');
		}
		$('#select3-drop .select2-input').focus();
	});
	$('#select3-drop .select2-input').live('keyup', function (event) {
		var find_str = $.trim($(this).val());
		var html = '';
		if (find_str.length != 0) {
			$.each(trade_tpl_obj, function (i, item) {
				if (item.tpl_name.indexOf(find_str) != -1) {
					html += '<li class="select2-results-dept-0 select2-result select2-result-selectable select2-result-li-' + item.tpl_id + '" data-id="' + item.tpl_id + '"><div class="select2-result-label">' + (item.tpl_name.replace(find_str, '<span class="select2-match">' + find_str + '</span>')) + '</div></li>';
				}
			});
		}
		else {
			$.each(trade_tpl_obj, function (i, item) {
				html += '<li class="select2-results-dept-0 select2-result select2-result-selectable select2-result-li-' + item.tpl_id + '" data-id="' + item.tpl_id + '"><div class="select2-result-label">' + item.tpl_name + '</div></li>';
			});
		}
		$('#select3-drop .select2-results').html(html);
		$('#select3-drop .select2-results .select2-result').eq(0).addClass('select2-highlighted').siblings().removeClass('select2-highlighted');
	});

	$('#select3-drop-mask').live('click', function () {
		$('.js-delivery-template').removeClass('select2-dropdown-open select2-container-active');
		$('#select3-drop-mask,#select3-drop').hide();
		$('#select3-drop .select2-input').val('');
		$('#select3-drop .select2-results').empty();
	});
	$('#select3-drop .select2-result').live('mouseover click', function (event) {
		if (event.type == 'mouseover') {
			$(this).addClass('select2-highlighted').siblings().removeClass('select2-highlighted');
		}
		else {
			var data_id = $(this).attr('data-id');

			$('.js-delivery-template .select2-chosen').attr('data-id', data_id).html($.trim($(this).text()));
			$('#select3-drop-mask,#select3-drop').hide();
			$('#select3-drop .select2-input').val('');
			$('#select3-drop .select2-results').empty();
			// now_sku_name_dom.closest('.sku-sub-group').find('.js-sku-atom-container').html('<div><div class="js-sku-atom-list sku-atom-list"></div><a href="javascript:;" class="js-add-sku-atom add-sku">+添加</a></div>');
			$('.js-delivery-template .select2-choice').removeClass('select2-default');
			$('#js-use-delivery').prop('checked', true);
		}
	});
	$('#js-unified-postage,.js-postage').live('click', function () {
		$('.js-delivery-template .select2-chosen').removeAttr('data-id').html('请选择运费模版...');
		$('#js-unified-postage').prop('checked', true);
		$('.js-delivery-template .select2-choice').addClass('select2-default');
	});

	$('.field-name').blur(function () {
		if ($(this).val() != '') {
			layer_tips(1, '留言名称不能为空');
			return false;
		}
	})

	$('.js-btn-edit').live('click', function () {
		var is_sku = true;

		// 检测商品属性
		$(".js-sku-name").each(function () {
			if ($(this).find(".select2-chosen").text().trim().length == 0) {
				$('.js-step').hide();
				$('#step-2').show();
				$('.js-step-1').removeClass('active');
				$('.js-step-2').addClass('active');
				$('.js-step-3').removeClass('active');

				$(this).click();
				is_sku = false;
				layer_tips(1, '请选择属性');
				return false;
			}

			if ($(this).closest(".sku-sub-group").find(".sku-atom").size() == 0) {
				$('.js-step').hide();
				$('#step-2').show();
				$('.js-step-1').removeClass('active');
				$('.js-step-2').addClass('active');
				$('.js-step-3').removeClass('active');

				layer_tips(1, '请添加“' + $(this).find(".select2-chosen").text() + '”的规格');

				$("html,body").animate({scrollTop: $("#js-group_sku").offset().top - 150}, 200);
				is_sku = false;
				return false;
			}
		});

		if (!is_sku) {
			return false;
		}

		//商品名称验证
		if ($("input[name='title']").val() == '') {
			layer_tips(1, '商品名长度不能少于一个字或者多于100个字');
			$("input[name='title']").focus();
			return false;
		}
		//商品价格验证
		if ($("input[name='price']").val() == 0 || $("input[name='price']").val() == '') {
			layer_tips(1, '商品价格不能为空');
			$('.price').val('');
			$('.price').focus();
			return false;
		}
		if (isNaN($("input[name='price']").val()) || $("input[name='price']").val() < 0) {
			layer_tips(1, '商品价格只能填写大于0的数字');
			$('.price').val('');
			$('.price').focus();
			return false;
		}

		//商品市场价验证
		if ($("input[name='market']").val() == 0 || $("input[name='market']").val() == '') {
			layer_tips(1, '商品市场价不能为空');
			$('.market').val('');
			$('.market').focus();
			return false;
		}
		if (isNaN($("input[name='market']").val()) || $("input[name='market']").val() < 0) {
			layer_tips(1, '商品市场价只能填写大于0的数字');
			$('.market').val('');
			$('.market').focus();
			return false;
		}


		//商品成本价验证
		if ($("input[name='cost']").val() == '' || isNaN($("input[name='cost']").val()) || $("input[name='cost']").val() < 0) {
			$("input[name='cost']").val('0');
//            layer_tips(1, '商品成本价不能为空');
//            $('.cost').val('');
//            $('.cost').focus();
//            return false;
		}
//        if (isNaN($("input[name='cost']").val()) || $("input[name='cost']").val() < 0) {
//            layer_tips(1, '商品成本价只能填写大于0的数字');
//            $('.cost').val('');
//            $('.cost').focus();
//            return false;
//        }
		/*if ($("input[name='origin']").val() != '' && (isNaN($("input[name='origin']").val()) || $("input[name='origin']").val() < 0)) {
		 layer_tips(1,'商品原价只能填写大于0的数字');
		 $("input[name='origin']").val('');
		 $("input[name='origin']").focus();
		 return false;
		 }*/
		//商品图片验证
		if ($('.app-image-list > .sort').length == 0) {
			layer_tips(1, '商品图至少有一张');
			$('.js-add-picture').css('border', '1px dotted red');
			return false;
		}
		////购买地址
		//if ($("input[name='shop_method']:checked").val() == 0) {
		//	if ($("input[name='buy_url']").val() == '') {
		//		layer_tips(1, '外部购买地址不能为空');
		//		return false;
		//	} else {
		//		var pattern = /http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/;
		//		var objExp = new RegExp(pattern);
		//		if (!objExp.test($.trim($("input[name='buy_url']").val()))) {
		//			layer_tips(1, '外部购买地址格式不正确');
		//			return false;
		//		}
		//	}
		//}
		//邮费验证
		if ($("input[name='postage']").length > 0 && $("input[name='postage']").val() != '' && (isNaN($("input[name='postage']").val()) || $("input[name='postage']").val() < 0)) {
			layer_tips(1, '邮费只能填写大于0的数字');
			$('.postage').val('');
			$('.postage').focus();
			return false;
		}
		//每人限购
		if ($("input[name='quota']").val() != '' && $("input[name='quota']").val() != 0) {
			if (isNaN($("input[name='quota']").val()) || $("input[name='quota']").val() < 0) {
				layer_tips(1, '每人限购只能填写大于0的数字');
				$("input[name='quota']").val('');
				$("input[name='quota']").focus();
				return false;
			}
		}
		//自定义字段（留言）
		var fields = [];
		var flag = true;
		if ($('.js-message-container > .message-item').length > 0) {
			$('.js-message-container > .message-item').each(function (i) {
				var field_name = $(this).children('.field-name').val();
				if (field_name == '') {
					layer_tips(1, '留言名称不能为空');
					flag = false;
				}
				var field_type = $(this).children("select[name='field-type']").val();
				var field_required = $(this).find('.required:checked').val();
				var field_multi_rows = $(this).find('.multi-rows:checked').val();
				fields[i] = {
					'name': field_name,
					'type': field_type,
					'multi_rows': field_multi_rows,
					'required': field_required
				};
			})
		}
		if (!flag) {
			return false;
		}
		//商品详细验证
		if ($("input[name='info']").val() == '') {
			layer_tips(1, '商品描述不能为空');
			return false;
		}
		//上架/下架
		var sale_status = $(this).attr('data');
		if (sale_status == 'soldout') {
			var status = 0;
		}
		else if (sale_status == 'putaway' || sale_status == 'preview') {
			var status = 1;
		}
		var category_id = $("input[name='class_ids']").val();
		category_id = category_id.split('-');
		if (category_id[1] != undefined) {
			category_id = category_id[1];
		}
		else {
			category_id = category_id[0];
		}
		var buy_way = "1"; //$("input[name='shop_method']:checked").val();
		var buy_url = ""; //$("input[name='buy_url']").val();

		//商品库存信息
		var skus = [];
		//商品属性名称
		var props_name = [];
		$('.js-sku-name').each(function (i) {
			var prop_name_id = $(this).attr('data-id');
			props_name[i] = prop_name_id;
		})

		if (!is_edit_sku) {
			if ($('.table-sku-stock > tbody > .sku').length > 0) {
				$('.table-sku-stock > tbody > .sku').each(function (i) {
					var sku_id = $(this).attr('sku-id');
					var price = $(this).find('.js-price').val();
					var market = $(this).find('.js-market').val();
					var cost = $(this).find('.js-cost').val();
					var quantity = $(this).find('.js-stock-num').val();
					var weight = $(this).find('.js-sku_weight').val();
					var code = $(this).find('.js-code').val();
					var properties = $(this).attr('properties');
					skus[i] = {
						'sku_id': sku_id,
						'price': price,
						'market': market,
						'cost': cost,
						'quantity': quantity,
						'weight': weight,
						'code': code,
						'properties': properties
					};
				});
			}
		}
		else {
			$('.table-sku-stock > tbody > .sku').each(function (i) {
				var sku_ids = $(this).attr('sku-ids');
				sku_ids = sku_ids.split('-');
				var props_str = ''; //商品属性id字符串
				for (var j in props_name) {
					if ($("#r_" + sku_ids[j]).size() > 0) {
						if ($("#r_" + sku_ids[j]).attr("atom-id") == sku_ids[j]) {
							layer_tips(1, '网络忙，请稍后');
							is_sku = false;
							return false;
						}
						else {
							sku_ids[j] = $("#r_" + sku_ids[j]).attr("atom-id");
						}
					}

					props_str += props_name[j] + ':' + sku_ids[j] + ';'; //pid1:vid1;pid2:vid2;...;
				}

				var sku_id = $(this).attr('sku-id');
				var sku_price = $(this).find('.js-price').val();
				var sku_market = $(this).find('.js-market').val();
				var sku_cost = $(this).find('.js-cost').val();
				var sku_quantity = $(this).find('.js-stock-num').val();
				var sku_weight = $(this).find('.js-sku_weight').val();
				var sku_code = $(this).find('.js-code').val();
				skus[i] = {
					"sku_id": sku_id,
					"properties": props_str.substring(0, props_str.length - 1),
					"price": sku_price,
					"market": sku_market,
					"cost": sku_cost,
					"quantity": sku_quantity,
					"weight": sku_weight,
					"code": sku_code
				};
			});
		}

		if (!is_sku) {
			return false;
		}

		var quantity = $("input[name='total_stock']").val();
		if ($("input[name='hide_stock']:checked").length > 0) {
			var show_stock = 0;
		}
		else {
			var show_stock = 1;
		}
		var code = $("input[name='goods_no']").val();
		var name = $("input[name='title']").val();
		var is_fx = $("input[name='is_fx']").val();
		var is_wholesale = $("input[name='is_wholesale']").val();
		var price = $("input[name='price']").val();
		var market_price = $("input[name='market']").val();
		var cost_price = $("input[name='cost']").val();
		var images = [];
		$('.app-image-list > .sort > a > img').each(function (i) {
			images[i] = $(this).attr('src');
		})
		var postage_type = $("input[name='delivery']:checked").val();
		var postage = $("input[name='postage']").val();
		if ($('.delivery-template').find('.select2-chosen').data('id') != undefined) {
			var postage_tpl_id = $('.delivery-template').find('.select2-chosen').data('id');
		}
		else {
			var postage_tpl_id = $("input[name='delivery_template_id']").val();
		}
		var buyer_quota = parseInt($("input[name='quota']").val());
		if ($("input[name='sold_time']:checked").val() == 1) {
			var sold_time = $("input[name='start_sold_time']").val();
		}
		else {
			var sold_time = 0;
		}
		var discount = $("input[name='join_level_discount']:checked").val();
		var is_recommend = $("input[name='is_recommend']:checked").val();
		var is_fx = $("input[name='is_fx']:checked").val();
		var invoice = $("input[name='invoice']:checked").val();
		var warranty = $("input[name='warranty']:checked").val();
		var intro = $("textarea[name='intro']").val();
		var info = $('.js-config-region .app-field .control-group').data('info');
		if (sale_status == 'preview') {
			var preview = 1;
		}
		else {
			var preview = 0;
		}
		var product_id = $(this).attr('data-product-id');

		//筛选中 ：选中的 栏目商品属性值id
		var sys_fields = [];
		if ($(".sxsx .red").length > 0) {
			$(".sxsx .red").each(function (i) {
				var sys_property_value_id = $(this).attr("data-vid");
				var sys_property_id = $(this).attr("data-pid");

				sys_fields[i] = {'sys_property_id': sys_property_id, 'sys_property_value_id': sys_property_value_id};

			});
		}
		else {
			if ($(".sxsx").length) {
				if ($("#is_fenxiao").val() == '0') {
					// layer_tips(1,'该商品栏目缺少筛选属性，请联系系统管理员添加该属性后再操作！');
					// return false;
				}
			}
		}

		var weight = $("input[name='weight']").val();
		var supplierUid = $("select[name='supplier_id']").val();
		var supplierStoreId = $("#supplier_id").find("option:selected").data("storeid");

		var weight = $("input[name='weight']").val();
		$.post(save_url, {
			'supplier_uid':supplierUid,
			'supplier_store_id':supplierStoreId,
			'product_id': product_id,
			'category_id': category_id,
			'is_wholesale': is_wholesale,
			'buy_way': buy_way,
			'buy_url': buy_url,
			'quantity': quantity,
			'show_stock': show_stock,
			'code': code,
			'name': name,
			'price': price,
			'market_price': market_price,
			'cost_price': cost_price,
			'images': images,
			'postage_type': postage_type,
			'postage': postage,
			'postage_tpl_id': postage_tpl_id,
			'skus': skus,
			'buyer_quota': buyer_quota,
			'sold_time': sold_time,
			'discount': discount,
			'invoice': invoice,
			'warranty': warranty,
			'status': status,
			'intro': intro,
			'info': info,
			'preview': preview,
			'sys_fields': sys_fields,
			'fields': fields,
			//'group_ids': group_ids,
			'referer': referer,
			'weight': weight,
			'is_recommend': is_recommend,
			'is_fx': is_fx,
			'is_edit_sku': is_edit_sku,
			'custom': customField.checkEvent()
		}, function (data) {
			if (data.err_code == 0) {
				$('.notifications').html('<div class="alert in fade alert-success">商品保存成功</div>');
				t = setTimeout('msg_hide(true, "' + data.err_msg + '")', 1000);
			}
			else {
				$('.notifications').html('<div class="alert in fade alert-error">' + data.err_msg + '</div>');
				t = setTimeout('msg_hide(false, "")', 3000);
			}
		});
	});
	//删除图片
	$('.js-delete-picture').live('click', function () {
		$(this).closest('li').remove();
	});

	//留言类型
	$("select[name='field-type']").live('change', function () {
		if ($(this).val() == 'text') {
			$(this).next('.checkbox').find('.multi-rows').attr('disabled', false);
		}
		else {
			$(this).next('.checkbox').find('.multi-rows').attr('disabled', true);
		}
	})
	//库存价格
	$("input[name='sku_price']").live('blur', function () {
		var price = [];
		$("input[name='sku_price']").each(function (i) {
			if (parseFloat($(this).val()) > 0) {
				price[i] = parseFloat($(this).val());
			}
		});
		if (price.length == 0) {
			price[0] = '0.00';
		}
		$("input[name='price']").val(Math.min.apply(null, price).toFixed(2));
	});


	//图片拖动
	$('.js-picture-list > .sort').live('click', function () {
		clearTimeout(doMouseDownTimmer);
	}).live('mousedown', function (ee) {
		var dom = $(this);
		var moveCssDom = $('<style>*{cursor:move!important;}.ui-sortable-placeholder{background: none!important;border: none!important;}</style>');
		var oldLeft = dom.offset().left;
		var oldTop = dom.offset().top;
		if (ee.button > 0) { //非鼠标左击
			return false;
		}
		doMouseDownTimmer = setTimeout(function () {
			$('body').bind('mousemove mouseup', function (e) {
				if (e.type == 'mousemove') {
					if (dom.data('noFirst') == 1) {
						dom.css({'left': e.pageX, 'top': e.pageY});
						if (e.pageX > oldLeft) {
							if ($('.ui-sortable-placeholder').next().hasClass('sort') && $('.ui-sortable-placeholder').next().offset().left < e.pageX && e.pageX - $('.sort:last').next().offset().left < 25 && Math.abs($('.ui-sortable-placeholder').next().offset().top - e.pageY) < 50) {
								$('.ui-sortable-placeholder').insertAfter($('.ui-sortable-placeholder').next());
							}
							else {

							}
						}
						else if ($('.ui-sortable-placeholder').index() > 0 && $('.ui-sortable-placeholder').prev().offset().left > e.pageX - 25 && Math.abs($('.ui-sortable-placeholder').prev().offset().top - e.pageY) < 25) {
							if ($('.ui-sortable-placeholder').prev().hasClass('sort')) {
								$('.ui-sortable-placeholder').insertBefore($('.ui-sortable-placeholder').prev());
							}
						}
					}
					else {
						$('body').bind("selectstart", function () {
							return false;
						}).css({
							'cursor': 'move',
							'-moz-user-select': 'none',
							'-khtml-user-select': 'none',
							'user-select': 'none'
						}).append(moveCssDom);
						dom.css({
							position: 'absolute',
							width: (dom.width()) + 'px',
							height: (dom.height()) + 'px',
							'z-index': '1000',
							'left': e.pageX + 'px',
							'top': e.pageY + 'px'
						}).data('noFirst', '1').after('<li class="sort ui-sortable-placeholder"></li>');
					}
				}
				else {
					$('body').css({
						'cursor': 'auto',
						'-moz-user-select': '',
						'-khtml-user-select': '',
						'user-select': ''
					}).unbind('mousemove mouseup selectstart');
					dom.data({'mousedown': false, 'noFirst': '0'}).attr('style', 'position:relative');
					$('.ui-sortable-placeholder').replaceWith(dom);
					moveCssDom.remove();
				}
			})
		}, 200);
		return false;
	})
});

function get_trade_delivery(show_err, obj) {
	$.post(get_trade_delivery_url, function (result) {
		if (result.err_code == 0) {
			trade_tpl_obj = result.err_msg;
		}
		else if (show_err) {
			layer_tips(1, result.err_msg);
		}
		if (obj) obj();
	});
}

function show_next(next_id) {
	//var wholesale_product_id = $("input[name='wholesale_product_id']").val();
	//var min_price = parseFloat($("input[name='sale_min_price']").val());
	//var max_price = parseFloat($("input[name='sale_max_price']").val());

	if ($('.manual-valid-error').length > 0) {
		$('.manual-valid-error:eq(0) > .js-price').focus();
		return false;
	}
	$('.widget-goods-klass-children').find("input[type='radio']:checked").closest('li').trigger('click');
	var now_step = $('.js-switch-step.active').attr('data-next-step');

	switch (now_step) {
		case '1':
			if (cat_id == '' || cat_name == '') {
				layer_tips(1, '必须选择一个商品类目');
			}
			else {
				if (next_id == 2) {
					$('.js-step').hide();
					$('#step-2').show();
					$('.fm-goods-info button.js-switch-step').attr('data-next-step', 3);
				}
				else if (next_id == 3) {
					if ($(".sxsx .red").length == '' || $(".sxsx .red").length == '0') {
						$('.js-step').hide();
						$('#step-2').show();
						$('.js-step-1').removeClass('active');
						$('.js-step-2').addClass('active');
						$('.fm-goods-info button.js-switch-step').attr('data-next-step', 3);
						// layer_tips(1,'您未选择商品的栏目属性！');
						// return false;
					}

					var is_sku = true;

					// 检测商品属性
					$(".js-sku-name").each(function () {
						if ($(this).find(".select2-chosen").text().trim().length == 0) {
							$('.js-step').hide();
							$('#step-2').show();
							$('.js-step-1').removeClass('active');
							$('.js-step-2').addClass('active');
							$('.js-step-3').removeClass('active');

							$(this).click();
							is_sku = false;
							layer_tips(1, '请选择属性');
							return false;
						}

						if ($(this).closest(".sku-sub-group").find(".sku-atom").size() == 0) {
							$('.js-step').hide();
							$('#step-2').show();
							$('.js-step-1').removeClass('active');
							$('.js-step-2').addClass('active');
							$('.js-step-3').removeClass('active');

							layer_tips(1, '请添加“' + $(this).find(".select2-chosen").text() + '”的规格');

							$("html,body").animate({scrollTop: $("#js-group_sku").offset().top - 150}, 200);
							is_sku = false;
							return false;
						}
					});

					if (!is_sku) {
						return false;
					}

					if ($("input[name='title']").val() == '') {
						$('.js-step').hide();
						$('#step-2').show();
						$('.js-step-1').removeClass('active');
						$('.js-step-2').addClass('active');
						$('.fm-goods-info button.js-switch-step').attr('data-next-step', 3);
						layer_tips(1, '商品名长度不能少于一个字或者多于100个字');
						$("input[name='title']").focus();
						return false;
					}
					if ($("input[name='price']").val() == 0 || $("input[name='price']").val() == '') {
						$('.js-step').hide();
						$('#step-2').show();
						$('.js-step-1').removeClass('active');
						$('.js-step-2').addClass('active');
						$('.fm-goods-info button.js-switch-step').attr('data-next-step', 3);
						layer_tips(1, '商品价格不能为空');
						$('.price').val('');
						$('.price').focus();
						return false;
					}
					if (isNaN($("input[name='price']").val()) || $("input[name='price']").val() < 0) {
						$('.js-step').hide();
						$('#step-2').show();
						$('.js-step-1').removeClass('active');
						$('.js-step-2').addClass('active');
						$('.fm-goods-info button.js-switch-step').attr('data-next-step', 3);
						layer_tips(1, '商品价格只能填写大于0的数字');
						$('.price').val('');
						$('.price').focus();
						return false;
					}
					if ($("input[name='market']").val() == 0 || $("input[name='market']").val() == '') {
						$('.js-step').hide();
						$('#step-2').show();
						$('.js-step-1').removeClass('active');
						$('.js-step-2').addClass('active');
						$('.fm-goods-info button.js-switch-step').attr('data-next-step', 3);
						layer_tips(1, '商品市场价不能为空');
						$('.market').val('');
						$('.market').focus();
						return false;
					}
					if (isNaN($("input[name='market']").val()) || $("input[name='market']").val() < 0) {
						$('.js-step').hide();
						$('#step-2').show();
						$('.js-step-1').removeClass('active');
						$('.js-step-2').addClass('active');
						$('.fm-goods-info button.js-switch-step').attr('data-next-step', 3);
						layer_tips(1, '商品市场价只能填写大于0的数字');
						$('.market').val('');
						$('.market').focus();
						return false;
					}
					if ($("input[name='cost']").val() == '' || isNaN($("input[name='cost']").val()) || $("input[name='cost']").val() < 0) {
						$("input[name='cost']").val('0');
//                        $('.js-step').hide();
//                        $('#step-2').show();
//                        $('.js-step-1').removeClass('active');
//                        $('.js-step-2').addClass('active');
//                        $('.fm-goods-info button.js-switch-step').attr('data-next-step', 3);
//                        layer_tips(1, '商品成本价不能为空');
//                        $('.cost').val('');
//                        $('.cost').focus();
//                        return false;
					}
//                    if (isNaN($("input[name='cost']").val()) || $("input[name='cost']").val() < 0) {
//                        $('.js-step').hide();
//                        $('#step-2').show();
//                        $('.js-step-1').removeClass('active');
//                        $('.js-step-2').addClass('active');
//                        $('.fm-goods-info button.js-switch-step').attr('data-next-step', 3);
//                        layer_tips(1, '商品成本价只能填写大于0的数字');
//                        $('.cost').val('');
//                        $('.cost').focus();
//                        return false;
//                    }
					/*if ($("input[name='origin']").val() != '' && (isNaN($("input[name='origin']").val()) || $("input[name='origin']").val() < 0)) {
					 $('.js-step').hide();$('#step-2').show();
					 $('.js-step-1').removeClass('active');
					 $('.js-step-2').addClass('active');
					 $('.fm-goods-info button.js-switch-step').attr('data-next-step',3);
					 layer_tips(1,'商品原价只能填写大于0的数字');
					 $("input[name='origin']").val('');
					 $("input[name='origin']").focus();
					 return false;
					 }*/
					if ($('.app-image-list > .sort').length == 0) {
						$('.js-step').hide();
						$('#step-2').show();
						$('.js-step-1').removeClass('active');
						$('.js-step-2').addClass('active');
						$('.fm-goods-info button.js-switch-step').attr('data-next-step', 3);
						layer_tips(1, '商品图至少有一张');
						$('.js-add-picture').css('border', '1px dotted red');
						return false;
					}
					////购买地址
					//if ($("input[name='shop_method']:checked").val() == 0) {
					//	if ($("input[name='buy_url']").val() == '') {
					//		layer_tips(1, '外部购买地址不能为空');
					//		return false;
					//	} else {
					//		var pattern = /http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/;
					//		var objExp = new RegExp(pattern);
					//		if (!objExp.test($.trim($("input[name='buy_url']").val()))) {
					//			layer_tips(1, '外部购买地址格式不正确');
					//			return false;
					//		}
					//	}
					//}
					if ($("input[name='postage']").length > 0 && $("input[name='postage']").val() != '' && (isNaN($("input[name='postage']").val()) || $("input[name='postage']").val() < 0)) {
						$('.js-step').hide();
						$('#step-2').show();
						$('.js-step-1').removeClass('active');
						$('.js-step-2').addClass('active');
						$('.fm-goods-info button.js-switch-step').attr('data-next-step', 3);
						layer_tips(1, '邮费只能填写大于0的数字');
						$('.postage').val('');
						$('.postage').focus();
						return false;
					}
					//每人限购
					if ($("input[name='quota']").val() != '' && $("input[name='quota']").val() != 0) {
						if (isNaN($("input[name='quota']").val()) || $("input[name='quota']").val() < 0) {
							layer_tips(1, '每人限购只能填写大于0的数字');
							$("input[name='quota']").val('');
							$("input[name='quota']").focus();
							return false;
						}
					}
					//自定义字段（留言）
					var fields = [];
					var flag = true;
					if ($('.js-message-container > .message-item').length > 0) {
						$('.js-message-container > .message-item').each(function (i) {
							var field_name = $(this).children('.field-name').val();
							if (field_name == '') {
								layer_tips(1, '留言名称不能为空');
								$(this).children('.field-name').focus();
								flag = false;
							}
							var field_type = $(this).children("select[name='field-type']").val();
							var field_required = $(this).children('.required').val();
							var field_multi_rows = $(this).children('.multi-rows').val();
							fields[i] = {
								'name': field_name,
								'type': field_type,
								'multi_rows': field_multi_rows,
								'required': field_required
							};
						})
					}
					if (!flag) {
						return false;
					}
					$('.js-step').hide();
					$('#step-3').show();
					$('.fm-goods-info button.js-switch-step').attr('data-next-step', 3);
				}

				$('.js-step-' + now_step).removeClass('active');
				$('.js-step-' + next_id).addClass('active');

				$('#base-info-region .js-goods-class').html(cat_name);
				$('#base-info-region input[name="class_ids"]').val(cat_id);
			}
			break;
		case '2':
			if ($(".sxsx:visible").length) {
				if ($("#is_fenxiao").val() == 0) {
					if ($(".sxsx .red").length == '0' || $(".sxsx .red").length == '') {
						// layer_tips(1,'未选择筛选的栏目属性！');
						//  return false;
					}
				}
			}

			if (next_id == 1) {
				$('.js-step').hide();
				$('#step-1').show();
				$('.js-step-2').removeClass('active');
				$('.js-step-1').addClass('active');
				$('.fm-goods-info button.js-switch-step').attr('data-next-step', 2);
			}
			else if (next_id == 2) {
				$('.js-step').hide();
				$('#step-2').show();
				$('.js-step-1').removeClass('active');
				$('.js-step-' + next_id).addClass('active');
				$('.fm-goods-info button.js-switch-step').attr('data-next-step', 3);
			}
			else if (next_id == 3) {
				var is_sku = true;
				// 检测商品属性
				$(".js-sku-name").each(function () {
					if ($(this).find(".select2-chosen").text().trim().length == 0) {
						$(this).click();
						is_sku = false;
						layer_tips(1, '请选择属性');
						return false;
					}

					if ($(this).closest(".sku-sub-group").find(".sku-atom").size() == 0) {
						layer_tips(1, '请添加“' + $(this).find(".select2-chosen").text() + '”的规格');

						$("html,body").animate({scrollTop: $("#js-group_sku").offset().top - 150}, 200);
						is_sku = false;
						return false;
					}
				});

				if (!is_sku) {
					return false;
				}

				$('.table-sku-stock > tbody > tr').each(function (i) {
					var sku_price = $(this).find('.js-price').val(); //库存价格
					var sku_market = $(this).find('.js-market').val(); //库存价格
					var sku_cost = $(this).find('.js-cost').val(); //库存价格
					var sku_quantity = $(this).find('.js-stock-num').val(); //库存数量
					var sku_weight = $(this).find('.js-sku_weight').val(); // 库存重量

					if (sku_price.length > 0) {
						var float_val = parseFloat(sku_price);
						if (float_val > 9999999.99) {
							layer_tips(1, '价格最大为 9999999.99');
							$(this).find(".js-price").val('9999999.99');
						}
						else if (!/^\d+(\.\d+)?$/.test(sku_price)) {
							layer_tips(1, '只输入合法的价格');
							$(this).find(".js-price").focus();
							is_sku = false;
							return false;
						}
						else if (float_val < 0.01) {
							layer_tips(1, '价格最小为 0.01');
							$(this).find(".js-price").focus();
							is_sku = false;
							return false;
						}
						else {
							$(this).val(float_val.toFixed(2));
						}
					}
					else {
						layer_tips(1, '请输入价格');
						$(this).find(".js-price").focus();
						is_sku = false;
						return false;
					}

					if (sku_market.length > 0) {
						var float_val = parseFloat(sku_market);
						if (float_val > 9999999.99) {
							layer_tips(1, '市场价最大为 9999999.99');
							$(this).find(".js-market").val('9999999.99');
						}
						else if (!/^\d+(\.\d+)?$/.test(sku_market)) {
							layer_tips(1, '只输入合法的市场价');
							$(this).find(".js-market").focus();
							is_sku = false;
							return false;
						}
						else if (float_val < 0.01) {
							layer_tips(1, '市场价最小为 0.01');
							$(this).find(".js-market").focus();
							is_sku = false;
							return false;
						}
						else {
							$(this).val(float_val.toFixed(2));
						}
					}
					else {
						layer_tips(1, '请输入市场价');
						$(this).find(".js-market").focus();
						is_sku = false;
						return false;
					}

					if (sku_cost.length > 0) {
						if (isNaN(sku_cost)) {
							var float_val = 0;
						}
						else {
							var float_val = parseFloat(sku_cost);
						}
//                        if (float_val > 9999999.99) {
//                            layer_tips(1, '成本价最大为 9999999.99');
//                            $(this).find(".js-cost").val('9999999.99');
//                        } else if (!/^\d+(\.\d+)?$/.test(sku_cost)) {
//                            layer_tips(1, '只输入合法的成本价');
//                            $(this).find(".js-cost").focus();
//                            is_sku = false;
//                            return false;
//                        } else if (float_val < 0.01) {
//                            layer_tips(1, '成本价最小为 0.01');
//                            $(this).find(".js-cost").focus();
//                            is_sku = false;
//                            return false;
//                        } else {
//                            $(this).val(float_val.toFixed(2));
//                        }
//                    } else {
//                        layer_tips(1, '请输入成本价');
//                        $(this).find(".js-cost").focus();
//                        is_sku = false;
//                        return false;
					}

					if (sku_quantity.length > 0) {
						if (!/^\d+$/.test(sku_quantity)) {
							layer_tips(1, '请输入合法的数量');
							$(this).find(".js-stock-num").focus();
							is_sku = false;
							return false;
						}
					}
					else {
						layer_tips(1, '请输入库存数量');
						$(this).find(".js-stock-num").focus();
						is_sku = false;
						return false;
					}

					if (sku_weight.length > 0) {
						if (!/^\d+$/.test(sku_weight)) {
							layer_tips(1, '请输入合法的重量');
							$(this).find(".js-sku_weight").focus();
							is_sku = false;
							return false;
						}
					}
					else {
						layer_tips(1, '请输入库存重量');
						$(this).find(".js-sku_weight").focus();
						is_sku = false;
						return false;
					}

				});

				if (!is_sku) {
					return false;
				}

				//表单验证
				if ($("input[name='title']").val() == '') {
					layer_tips(1, '商品名长度不能少于一个字或者多于100个字');
					$("input[name='title']").focus();
					return false;
				}
				if ($("input[name='price']").val() == 0 || $("input[name='price']").val() == '') {
					layer_tips(1, '商品价格不能为空');
					$('.price').val('');
					$('.price').focus();
					return false;
				}
				//if (wholesale_product_id != 0) {
				//    if (parseFloat($("input[name='price']").val()) > max_price || parseFloat($("input[name='price']").val()) < min_price) {
				//        layer_tips(1, '商品价格只能填写区间内的价格');
				//        $('.price').val('');
				//        $('.price').focus();
				//        return false;
				//    }
				//}
				if (isNaN($("input[name='price']").val()) || $("input[name='price']").val() < 0) {
					layer_tips(1, '商品价格只能填写大于0的数字');
					$('.price').val('');
					$('.price').focus();
					return false;
				}

				if ($("input[name='market']").val() == 0 || $("input[name='market']").val() == '') {
					layer_tips(1, '商品市场价不能为空');
					$('.market').val('');
					$('.market').focus();
					return false;
				}
				//if (wholesale_product_id != 0) {
				//    if (parseFloat($("input[name='market']").val()) > max_market || parseFloat($("input[name='market']").val()) < min_market) {
				//        layer_tips(1, '商品市场价只能填写区间内的市场价');
				//        $('.market').val('');
				//        $('.market').focus();
				//        return false;
				//    }
				//}
				if (isNaN($("input[name='market']").val()) || $("input[name='market']").val() < 0) {
					layer_tips(1, '商品市场价只能填写大于0的数字');
					$('.market').val('');
					$('.market').focus();
					return false;
				}

				if ($("input[name='cost']").val() == 0 || $("input[name='cost']").val() == '' || isNaN($("input[name='cost']").val())) {
					$('.cost').val('0');
//                    layer_tips(1, '商品成本价不能为空');
//                    $('.cost').val('');
//                    $('.cost').focus();
//                    return false;
				}
				//if (wholesale_product_id != 0) {
				//    if (parseFloat($("input[name='cost']").val()) > max_cost || parseFloat($("input[name='cost']").val()) < min_cost) {
				//        layer_tips(1, '商品成本价只能填写区间内的成本价');
				//        $('.cost').val('');
				//        $('.cost').focus();
				//        return false;
				//    }
				//}
//                if (isNaN($("input[name='cost']").val()) || $("input[name='cost']").val() < 0) {
//                    layer_tips(1, '商品成本价只能填写大于0的数字');
//                    $('.cost').val('');
//                    $('.cost').focus();
//                    return false;
//                }

				/*if ($("input[name='origin']").val() != '' && (isNaN($("input[name='origin']").val()) || $("input[name='origin']").val() < 0)) {
				 layer_tips(1,'商品原价只能填写大于0的数字');
				 $("input[name='origin']").val('');
				 $("input[name='origin']").focus();
				 return false;
				 }*/
				if ($('.app-image-list > .sort').length == 0) {
					layer_tips(1, '商品图至少有一张');
					$('.js-add-picture').css('border', '1px dotted red');
					return false;
				}
				////购买地址
				//if ($("input[name='shop_method']:checked").val() == 0) {
				//	if ($("input[name='buy_url']").val() == '') {
				//		layer_tips(1, '外部购买地址不能为空');
				//		return false;
				//	} else {
				//		var pattern = /http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/;
				//		var objExp = new RegExp(pattern);
				//		if (!objExp.test($.trim($("input[name='buy_url']").val()))) {
				//			layer_tips(1, '外部购买地址格式不正确');
				//			return false;
				//		}
				//	}
				//}
				//邮费
				if ($("input[name='postage']").length > 0 && $("input[name='postage']").val() != '' && (isNaN($("input[name='postage']").val()) || $("input[name='postage']").val() < 0)) {
					layer_tips(1, '邮费只能填写大于0的数字');
					$('.postage').val('');
					$('.postage').focus();
					return false;
				}
				//每人限购
				if ($("input[name='quota']").val() != '' && $("input[name='quota']").val() != 0) {
					if (isNaN($("input[name='quota']").val()) || $("input[name='quota']").val() < 0) {
						layer_tips(1, '每人限购只能填写大于0的数字');
						$("input[name='quota']").val('');
						$("input[name='quota']").focus();
						return false;
					}
				}
				//自定义字段（留言）
				var fields = [];
				var flag = true;
				if ($('.js-message-container > .message-item').length > 0) {
					$('.js-message-container > .message-item').each(function (i) {
						var field_name = $(this).children('.field-name').val();
						if (field_name == '') {
							layer_tips(1, '留言名称不能为空');
							$(this).children('.field-name').focus();
							flag = false;
						}
						var field_type = $(this).children("select[name='field-type']").val();
						var field_required = $(this).children('.required').val();
						var field_multi_rows = $(this).children('.multi-rows').val();
						fields[i] = {
							'name': field_name,
							'type': field_type,
							'multi_rows': field_multi_rows,
							'required': field_required
						};
					})
				}
				if (!flag) {
					return false;
				}
				$('.js-step').hide();
				$('#step-3').show();
				$('.js-step-2').removeClass('active');
				$('.js-step-' + next_id).addClass('active');
				$('.fm-goods-info button.js-switch-step').attr('data-next-step', 2);
			}
			break;
		case '3':
			if (next_id == 1) {
				$('.js-step').hide();
				$('#step-1').show();
				$('.js-step-3').removeClass('active');
				$('.js-step-1').addClass('active');
				$('.fm-goods-info button.js-switch-step').attr('data-next-step', 2);
			}
			else if (next_id == 2) {
				$('.js-step').hide();
				$('#step-2').show();
				$('.js-step-3').removeClass('active');
				$('.js-step-' + next_id).addClass('active');
				$('.fm-goods-info button.js-switch-step').attr('data-next-step', 3);
			}
			break;
	}

	if (next_id == "3") {
		$('.js-config-region .app-field').click();
	}
}

function msg_hide(redirect, url) {
	if (redirect) {
		window.location.href = url;
	}
	$('.notifications').html('');
	if (t) clearTimeout(t);
}
$('.js-add-sku-group').live('click', function () {
	if ($('.js-sku-list-container .sku-sub-group').size() == 3) {
		$('.js-sku-group-opts').hide();
	}
	$('.js-sku-list-container').append('<div class="sku-sub-group"><h3 class="sku-group-title"><div class="select2-container js-sku-name" style="width:180px;"><a href="javascript:void(0)" onclick="return false;" class="select2-choice" tabindex="-1"><span class="select2-chosen">&nbsp;</span><abbr class="select2-search-choice-close"></abbr><span class="select2-arrow"><b></b></span></a></div><a class="js-remove-sku-group remove-sku-group">×</a></h3><div class="js-sku-atom-container sku-group-cont"></div></div>');
	$('div.js-sku-name:last').click();
});

$('.js-remove-sku-group').live('click', function () {
	$(this).closest('.sku-sub-group').remove();
	$('.js-sku-group-opts').show();
	create_sku();
});
var now_sku_name_dom = null;
$('.js-sku-name').live('click', function () {
	now_sku_name_dom = $(this);
	$(this).addClass('select2-dropdown-open select2-container-active');
	var html = '';
	$.each(sku_name_obj, function (i, item) {
		html += '<li class="select2-results-dept-0 select2-result select2-result-selectable select2-result-li-' + item.pid + '" data-id="' + item.pid + '"><div class="select2-result-label">' + item.name + '</div></li>';
	});
	$('#select2-drop .select2-results').html(html);
	$('#select2-drop').css({
		top: ($(this).offset().top + $(this).height() - 2) + 'px',
		left: $(this).offset().left + 'px',
		width: $(this).width() + 'px',
		display: 'block'
	});
	$('#select2-drop-mask').show();
	if ($(this).attr('data-id')) {
		var select_li_dom = $('#select2-drop .select2-result-li-' + $(this).attr('data-id'));
		select_li_dom.addClass('select2-highlighted').siblings().removeClass('select2-highlighted');
		$('#select2-drop .select2-results').scrollTop(25 * (select_li_dom.index() - 3));
	}
	else {
		$('#select2-drop .select2-results .select2-result').eq(0).addClass('select2-highlighted').siblings().removeClass('select2-highlighted');
	}
	$('#select2-drop .select2-input').focus();
});
//点击属性 获取值弹窗
$('#select2-drop .select2-input').live('keyup', function (event) {
	var find_str = $.trim($(this).val());
	var html = '';
	if (find_str.length != 0) {
		$.each(sku_name_obj, function (i, item) {
			if (item.name.indexOf(find_str) != -1) {
				html += '<li class="select2-results-dept-0 select2-result select2-result-selectable select2-result-li-' + item.pid + '" data-id="' + item.pid + '"><div class="select2-result-label">' + (item.name.replace(find_str, '<span class="select2-match">' + find_str + '</span>')) + '</div></li>';
			}
		});
	}
	else {
		$.each(sku_name_obj, function (i, item) {
			html += '<li class="select2-results-dept-0 select2-result select2-result-selectable select2-result-li-' + item.pid + '" data-id="' + item.pid + '"><div class="select2-result-label">' + item.name + '</div></li>';
		});
	}
	$('#select2-drop .select2-results').html(html);
	$('#select2-drop .select2-results .select2-result').eq(0).addClass('select2-highlighted').siblings().removeClass('select2-highlighted');
});

$('#select2-drop-mask').live('click', function () {
	now_sku_name_dom.removeClass('select2-dropdown-open select2-container-active');
	$('#select2-drop-mask,#select2-drop').hide();
	$('#select2-drop .select2-input').val('');
	$('#select2-drop .select2-results').empty();
});

//点击属性名 获取 属性值 并弹窗
$('#select2-drop .select2-result').live('mouseover click', function (event) {
	if (event.type == 'mouseover') {
		$(this).addClass('select2-highlighted').siblings().removeClass('select2-highlighted');
	}
	else {
		var data_id = $(this).attr('data-id');
		var check_result = true;
		$.each($('.js-sku-list-container .js-sku-name'), function (i, item) {
			if ($(item).attr('data-id') == data_id) {
				check_result = false;
				return false;
			}
		});
		if (check_result) {
			now_sku_name_dom.attr('data-id', data_id).removeClass('select2-dropdown-open select2-container-active').find('.select2-chosen').html($.trim($(this).text()));
			$('#select2-drop-mask,#select2-drop').hide();
			$('#select2-drop .select2-input').val('');
			$('#select2-drop .select2-results').empty();
			now_sku_name_dom.closest('.sku-sub-group').find('.js-sku-atom-container').html('<div><div data-cid="' + data_id + '" class="js-sku-atom-list sku-atom-list"></div><a href="javascript:;" class="js-add-sku-atom add-sku">+添加</a></div>');
			create_sku();
			//点击属性 ajax 获取属性值
			$.post(get_propertyvaluebyid_url, {propertypid: data_id}, function (result) {
					count = result.counts;
					results = result.fields;
					var guige_arr = [];
					var sstr = [];
					if (count) {
						select_property_value(results, data_id, 0);
						arrs2 = results;
						$(".popover-chosen").show();
					}
				},
				'json'
			)
		}
		else {
			layer_tips(1, '规则名不能相同');
			$('#select2-drop .select2-input').focus();
		}
	}
});

//显示所有属性的值 及后续操作
function select_property_value(arrs, cats_id, sku_atom_arr) {
	var add_sku_atom_dom = $('.js-add-sku-atom');
	var return_content = [];
	return_content['contents'] = '选择规格值';
	return_content['arr'] = arrs;
	if (sku_atom_arr)   return_content['has_atom_id'] = sku_atom_arr;
	var atom_index = $(".js-sku-name").index($(".js-sku-name[data-id='" + cats_id + "']"))
	button_box($('.js-add-sku-atom').eq(atom_index), event, 'bottom', 'multi_txt2', return_content, function () {
		var atom_list_dom = $(".js-sku-atom-list[data-cid='" + cats_id + "']");

		var atom_has_arr = [];
		$.each($(atom_list_dom.find('.sku-atom span')), function (i, item) {
			atom_has_arr.push($(item).html());
		});
		var html = '';

		$.each($('.popover-chosen .select2-search-choice'), function (i, item) {

			if ($(this).attr("idd") == 'choice') {   //只有选中的才显示

				var choice_html = $.trim($(item).find('div').html());
				if (atom_has_arr.length > 0) {
					if ($.inArray(choice_html, atom_has_arr) == -1) {
						html += '<div class="sku-atom" atom-id="' + $(item).attr('data-vid') + '"><span>' + choice_html + '</span><div class="close-modal small js-remove-sku-atom">×</div></div>';
					}
					else {
						layer_tips(1, '已经添加了相同的规格值');
					}
				}
				else {
					html += '<div class="sku-atom" atom-id="' + $(item).attr('data-vid') + '"><span>' + choice_html + '</span><div class="close-modal small js-remove-sku-atom">×</div></div>';
				}
			}
		});
		atom_list_dom.append(html);
		close_button_box();
		if (html != '') create_sku();
	})

}

/*商品库存*/
function create_sku() {
	is_edit_sku = true;
	var sku_dom_count = 0;
	var sku_arr = [];
	$.each($('.js-sku-atom-container'), function (i, item) {
		if ($(item).find('.sku-atom').size() > 0) {
			sku_arr[i] = $(item);
			sku_dom_count++;
		}
	});

	if (sku_dom_count == 0) {
		$('.js-goods-stock').hide().find('table.table-sku-stock').empty();
		$('input[name="total_stock"]').prop('readonly', false);
		$('input[name="price"]').prop('readonly', false);
		$('input[name="weight"]').prop('readonly', false);
		return false;
	}

	var thead = '<th class="th-price text-right">价格（元）</th>' +
		'<th class="th-market text-right">市场（元）</th>' +
		'<th class="th-cost text-right">成本（元）</th>' +
		'<th class="th-stock text-right">库存</th>' +
		'<th class="th-stock text-right">重量(克)</th>' +
		'<th class="th-code">商品编码</th>' +
		'<th class="text-right">销量</th>' +
		'</tr>' +
		'</thead>' +
		'<tbody>';

	var html = '<thead><tr>';
	switch (sku_dom_count) {
		case 1:
			var current_i = 0;
			$.each($('.js-sku-atom-container'), function (i, item) {
				if ($(item).find('.sku-atom').size() > 0) {
					current_i = i;
				}
			});

			var js_pid = $(".js-sku-name").eq(current_i).data("id");

			html += '<th class="text-center">' + (sku_arr[current_i].siblings('h3').find('span.select2-chosen').html()) + '</th>';

			html += thead;

			$.each(sku_arr[current_i].find('.sku-atom'), function (i, item) {
				var js_sku_price = '';
				var js_sku_market = '';
				var js_sku_cost = '';
				var js_sku_num = '';
				var js_sku_weight = '0';
				var js_sku_code = '';
				var js_sku_sales = '0';
				var js_sku_id = '';
				var js_sku_properties = '';

				// 处理是否有原始值
				var js_vid = $(this).attr("atom-id");
				try {
					var sku_data_obj = $.parseJSON(sku_data);

					var tmp_key = js_pid + "_" + js_vid;
					if (typeof sku_data_obj[tmp_key] != "undefined") {
						js_sku_price = sku_data_obj[tmp_key].price;
						js_sku_market = sku_data_obj[tmp_key].market;
						js_sku_cost = sku_data_obj[tmp_key].cost;
						js_sku_num = sku_data_obj[tmp_key].quantity;
						js_sku_weight = sku_data_obj[tmp_key].weight;
						js_sku_code = sku_data_obj[tmp_key].code;
						js_sku_sales = parseInt(sku_data_obj[tmp_key].sales) + 0;
						js_sku_id = sku_data_obj[tmp_key].sku_id;
						js_sku_properties = sku_data_obj[tmp_key].properties;
					}
				} catch (e) {
				}

				html += '<tr class="sku" sku-ids="' + ($(item).attr('atom-id')) + '" sku-id="' + js_sku_id + '" properties="' + js_sku_properties + '">';
				html += '<td rowspan="1" class="text-center">' + ($(item).find('span').html()) + '</td>';

				html += '<td><input type="text" name="sku_price" class="js-price input-mini" value="' + js_sku_price + '" maxlength="10" /></td>';
				html += '<td><input type="text" name="sku_market" class="js-market input-mini" value="' + js_sku_market + '" maxlength="10" /></td>';
				html += '<td><input type="text" name="sku_cost" class="js-cost input-mini" value="' + js_sku_cost + '" maxlength="10" /></td>';
				html += '<td><input type="text" name="stock_num" class="js-stock-num input-mini" value="' + js_sku_num + '" maxlength="9" /></td>';
				html += '<td><input type="text" name="sku_weight" class="js-sku_weight input-mini" value="' + js_sku_weight + '" maxlength="9" /></td>';
				html += '<td><input type="text" name="code" class="js-code input-mini" value="' + js_sku_code + '"></td>';
				html += '<td class="text-right">' + js_sku_sales + '</td>';
				html += '</tr>';
			});
			break;
		case 2:
			var current_i1 = 0;
			var current_i2 = 1;
			var current_i1_set = false;

			$.each($('.js-sku-atom-container'), function (i, item) {
				if ($(item).find('.sku-atom').size() > 0 && current_i1_set == false) {
					current_i1 = i;
					current_i1_set = true;
				}
				else if ($(item).find('.sku-atom').size() > 0) {
					current_i2 = i;
				}
			});

			var js_pid1 = $(".js-sku-name").eq(current_i1).data("id");
			var js_pid2 = $(".js-sku-name").eq(current_i2).data("id");

			html += '<th class="text-center">' + (sku_arr[current_i1].siblings('h3').find('span.select2-chosen').html()) + '</th>' +
				'<th class="text-center">' + (sku_arr[current_i2].siblings('h3').find('span.select2-chosen').html()) + '</th>';

			html += thead;

			$.each(sku_arr[current_i1].find('.sku-atom'), function (i, item) {
				var js_vid1 = $(this).attr("atom-id");
				$.each(sku_arr[current_i2].find('.sku-atom'), function (j, jtem) {
					var js_vid2 = $(this).attr("atom-id");

					var js_sku_price = '';
					var js_sku_market = '';
					var js_sku_cost = '';
					var js_sku_num = '';
					var js_sku_weight = '0';
					var js_sku_code = '';
					var js_sku_sales = '0';
					var js_sku_id = '';
					var js_sku_properties = '';

					// 处理是否有原始值
					try {
						var sku_data_obj = $.parseJSON(sku_data);

						var tmp_key = js_pid1 + "_" + js_vid1 + "_" + js_pid2 + "_" + js_vid2;
						if (typeof sku_data_obj[tmp_key] != "undefined") {
							js_sku_price = sku_data_obj[tmp_key].price;
							js_sku_market = sku_data_obj[tmp_key].market;
							js_sku_cost = sku_data_obj[tmp_key].cost;
							js_sku_num = sku_data_obj[tmp_key].quantity;
							js_sku_weight = sku_data_obj[tmp_key].weight;
							js_sku_code = sku_data_obj[tmp_key].code;
							js_sku_sales = parseInt(sku_data_obj[tmp_key].sales) + 0;
							js_sku_id = sku_data_obj[tmp_key].sku_id;
							js_sku_properties = sku_data_obj[tmp_key].properties;
						}

					} catch (e) {

					}

					html += '<tr class="sku" sku-ids="' + js_vid1 + '-' + js_vid2 + '" sku-id="' + js_sku_id + '" properties="' + js_sku_properties + '">';
					if (j == 0)
						html += '<td rowspan="' + (sku_arr[current_i2].find('.sku-atom').size()) + '" class="text-center">' + ($(item).find('span').html()) + '</td>';

					html += '<td class="text-center">' + ($(jtem).find('span').html()) + '</td>';

					html += '<td><input type="text" name="sku_price" class="js-price input-mini" value="' + js_sku_price + '" maxlength="10" /></td>';
					html += '<td><input type="text" name="sku_market" class="js-market input-mini" value="' + js_sku_market + '" maxlength="10" /></td>';
					html += '<td><input type="text" name="sku_cost" class="js-cost input-mini" value="' + js_sku_cost + '" maxlength="10" /></td>';
					html += '<td><input type="text" name="stock_num" class="js-stock-num input-mini" value="' + js_sku_num + '" maxlength="9" /></td>';
					html += '<td><input type="text" name="sku_weight" class="js-sku_weight input-mini" value="' + js_sku_weight + '" maxlength="9" /></td>';
					html += '<td><input type="text" name="code" class="js-code input-small" value="' + js_sku_code + '"></td>';
					html += '<td class="text-right">' + js_sku_sales + '</td>';
					html += '</tr>';
				});
			});

			break;
		case 3:
			html += '<th class="text-center">' + (sku_arr[0].siblings('h3').find('span.select2-chosen').html()) + '</th>' +
				'<th class="text-center">' + (sku_arr[1].siblings('h3').find('span.select2-chosen').html()) + '</th>' +
				'<th class="text-center">' + (sku_arr[2].siblings('h3').find('span.select2-chosen').html()) + '</th>';

			html += thead;

			var js_pid1 = $(".js-sku-name").eq(0).data("id");
			var js_pid2 = $(".js-sku-name").eq(1).data("id");
			var js_pid3 = $(".js-sku-name").eq(2).data("id");

			$.each(sku_arr[0].find('.sku-atom'), function (i, item) {
				var js_vid1 = $(this).attr("atom-id");
				$.each(sku_arr[1].find('.sku-atom'), function (j, jtem) {
					var js_vid2 = $(this).attr("atom-id");
					$.each(sku_arr[2].find('.sku-atom'), function (k, ktem) {
						var js_vid3 = $(this).attr("atom-id");

						var js_sku_price = '';
						var js_sku_market = '';
						var js_sku_cost = '';
						var js_sku_num = '';
						var js_sku_weight = '0';
						var js_sku_code = '';
						var js_sku_sales = '0';
						var js_sku_id = '';
						var js_sku_properties = '';

						// 处理是否有原始值
						try {
							var sku_data_obj = $.parseJSON(sku_data);

							var tmp_key = js_pid1 + "_" + js_vid1 + "_" + js_pid2 + "_" + js_vid2 + "_" + js_pid3 + "_" + js_vid3;
							if (typeof sku_data_obj[tmp_key] != "undefined") {
								js_sku_price = sku_data_obj[tmp_key].price;
								js_sku_market = sku_data_obj[tmp_key].market;
								js_sku_cost = sku_data_obj[tmp_key].cost;
								js_sku_num = sku_data_obj[tmp_key].quantity;
								js_sku_weight = sku_data_obj[tmp_key].weight;
								js_sku_code = sku_data_obj[tmp_key].code;
								js_sku_sales = parseInt(sku_data_obj[tmp_key].sales) + 0;
								js_sku_id = sku_data_obj[tmp_key].sku_id;
								js_sku_properties = sku_data_obj[tmp_key].properties;
							}
						} catch (e) {

						}

						//html += '<tr sku-ids="' + ($(item).attr('atom-id')) + '-' + ($(jtem).attr('atom-id')) + '-' + ($(ktem).attr('atom-id')) + '">';
						html += '<tr class="sku" sku-ids="' + ($(item).attr('atom-id')) + '-' + ($(jtem).attr('atom-id')) + '-' + ($(ktem).attr('atom-id')) + '" sku-id="' + js_sku_id + '" properties="' + js_sku_properties + '">';
						if (j == 0 && k == 0)
							html += '<td rowspan="' + (sku_arr[1].find('.sku-atom').size() * sku_arr[2].find('.sku-atom').size()) + '" class="text-center">' + ($(item).find('span').html()) + '</td>';

						if (k == 0)
							html += '<td rowspan="' + (sku_arr[2].find('.sku-atom').size()) + '" class="text-center">' + ($(jtem).find('span').html()) + '</td>';

						html += '<td class="text-center">' + ($(ktem).find('span').html()) + '</td>';

						html += '<td><input type="text" name="sku_price" class="js-price input-mini" value="' + js_sku_price + '" maxlength="10" /></td>';
						html += '<td><input type="text" name="sku_market" class="js-market input-mini" value="' + js_sku_market + '" maxlength="10" /></td>';
						html += '<td><input type="text" name="sku_cost" class="js-cost input-mini" value="' + js_sku_cost + '" maxlength="10" /></td>';
						html += '<td><input type="text" name="stock_num" class="js-stock-num input-mini" value="' + js_sku_num + '" maxlength="9" /></td>';
						html += '<td><input type="text" name="sku_weight" class="js-sku_weight input-mini" value="' + js_sku_weight + '" maxlength="9" /></td>';
						html += '<td><input type="text" name="code" class="js-code input-small" value="' + js_sku_code + '"></td>';
						html += '<td class="text-right">' + js_sku_sales + '</td>';
						html += '</tr>';
					});
				});
			});
			break;
	}

	html += '</tbody>' +
		'<tfoot>' +
		'<tr>' +
		'<td colspan="5">' +
		'<div class="batch-opts">' +
		'批量设置：' +
		'<span class="js-batch-type">' +
		'<a class="js-batch-price" href="javascript:;">价格</a>&nbsp;' +
		'<a class="js-batch-market" href="javascript:;">市价</a>&nbsp;' +
		'<a class="js-batch-cost" href="javascript:;">成本</a>&nbsp;' +
		'<a class="js-batch-stock" href="javascript:;">库存</a>&nbsp;' +
		'<a class="js-batch-weight" href="javascript:;">重量</a>' +
		'</span>' +
		'<span class="js-batch-form" style="display:none;">' +
		'<input type="text" class="js-batch-txt input-mini" placeholder=""/>&nbsp;' +
		'<a class="js-batch-save" href="javascript:;">保存</a>&nbsp;' +
		'<a class="js-batch-cancel" href="javascript:;">取消</a>' +
		'<p class="help-desc"></p>' +
		'</span>' +
		'</div>' +
		'</td>' +
		'</tr>' +
		'</tfoot>';

	$('.js-goods-stock').show().find('table.table-sku-stock').html(html);
	$('input[name="total_stock"]').prop('readonly', true);
	$('input[name="price"]').prop('readonly', true);
	$('input[name="weight"]').prop('readonly', true);
}