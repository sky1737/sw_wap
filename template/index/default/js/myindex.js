// JavaScript Document
//滚动图片插件 
var component_slider_timer1 = null;
function component_slider_play1() {
	component_slider_timer1 = window.setInterval(function () {
		var slider_index = $('.component-index-slider1 .mt-slider-trigger-container li.mt-slider-current-trigger').index();
		if (slider_index == $('.component-index-slider1 .mt-slider-trigger-container li').size() - 1) {
			slider_index = 0;
		} else {
			slider_index++;
		}
		$('.component-index-slider1 .content li').eq(slider_index).css({
			'opacity': '0',
			'display': 'block'
		}).animate({
			opacity: 1
		}, 600).siblings().hide();
		$('.component-index-slider1 .mt-slider-trigger-container li').eq(slider_index).addClass('mt-slider-current-trigger').siblings().removeClass('mt-slider-current-trigger');
	}, 3400);
}
component_slider_play1();
$('.component-index-slider1').hover(function () {
	window.clearInterval(component_slider_timer1);
	$('.component-index-slider1 .mt-slider-previous,.component-index-slider1 .mt-slider-next').css({
		'opacity': '0.4'
	}).show();
}, function () {
	window.clearInterval(component_slider_timer1);
	component_slider_play1();
	$('.component-index-slider1 .mt-slider-previous,.component-index-slider1 .mt-slider-next').css({
		'opacity': '0'
	}).hide();
});
$('.component-index-slider1 .mt-slider-previous,.component-index-slider1 .mt-slider-next').hover(function () {
	$(this).css({
		'opacity': '0.7'
	});
});
$('.component-index-slider1 .mt-slider-trigger-container li').click(function () {
	if ($(this).hasClass('mt-slider-current-trigger')) {
		return false;
	}
	var slider_index = $(this).index();
	$('.component-index-slider1 .content li').eq(slider_index).show().siblings().hide();
	$(this).addClass('mt-slider-current-trigger').siblings().removeClass('mt-slider-current-trigger');
});
$('.component-index-slider1 .mt-slider-previous').click(function () {
	var slider_index = $('.component-index-slider1 .mt-slider-trigger-container li.mt-slider-current-trigger').index() - 1;
	if (slider_index < 0) {
		slider_index = $('.component-index-slider1 .mt-slider-trigger-container li').size() - 1;
	}
	$('.component-index-slider1 .content li').eq(slider_index).show().siblings().hide();
	$('.component-index-slider1 .mt-slider-trigger-container li').eq(slider_index).addClass('mt-slider-current-trigger').siblings().removeClass('mt-slider-current-trigger');
});
$('.component-index-slider1 .mt-slider-next').click(function () {
	var slider_index = $('.component-index-slider1 .mt-slider-trigger-container li.mt-slider-current-trigger').index() + 1;
	if (slider_index == $('.component-index-slider1 .mt-slider-trigger-container li').size()) {
		slider_index = 0;
	}
	$('.component-index-slider1 .content li').eq(slider_index).show().siblings().hide();
	$('.component-index-slider1 .mt-slider-trigger-container li').eq(slider_index).addClass('mt-slider-current-trigger').siblings().removeClass('mt-slider-current-trigger');
});


var component_slider_timer2 = null;
function component_slider_play2() {
	component_slider_timer2 = window.setInterval(function () {
		var slider_index = $('.component-index-slider2 .mt-slider-trigger-container li.mt-slider-current-trigger').index();
		if (slider_index == $('.component-index-slider2 .mt-slider-trigger-container li').size() - 1) {
			slider_index = 0;
		} else {
			slider_index++;
		}
		$('.component-index-slider2 .content li').eq(slider_index).css({
			'opacity': '0',
			'display': 'block'
		}).animate({
			opacity: 1
		}, 600).siblings().hide();
		$('.component-index-slider2 .mt-slider-trigger-container li').eq(slider_index).addClass('mt-slider-current-trigger').siblings().removeClass('mt-slider-current-trigger');
	}, 3400);
}
component_slider_play2();
$('.component-index-slider2').hover(function () {
	window.clearInterval(component_slider_timer2);
	$('.component-index-slider2 .mt-slider-previous,.component-index-slider2 .mt-slider-next').css({
		'opacity': '0.4'
	}).show();
}, function () {
	window.clearInterval(component_slider_timer2);
	component_slider_play2();
	$('.component-index-slider2 .mt-slider-previous,.component-index-slider2 .mt-slider-next').css({
		'opacity': '0'
	}).hide();
});
$('.component-index-slider2 .mt-slider-previous,.component-index-slider2 .mt-slider-next').hover(function () {
	$(this).css({
		'opacity': '0.7'
	});
});
$('.component-index-slider2 .mt-slider-trigger-container li').click(function () {
	if ($(this).hasClass('mt-slider-current-trigger')) {
		return false;
	}
	var slider_index = $(this).index();
	$('.component-index-slider2 .content li').eq(slider_index).show().siblings().hide();
	$(this).addClass('mt-slider-current-trigger').siblings().removeClass('mt-slider-current-trigger');
});
$('.component-index-slider2 .mt-slider-previous').click(function () {
	var slider_index = $('.component-index-slider2 .mt-slider-trigger-container li.mt-slider-current-trigger').index() - 1;
	if (slider_index < 0) {
		slider_index = $('.component-index-slider2 .mt-slider-trigger-container li').size() - 1;
	}
	$('.component-index-slider2 .content li').eq(slider_index).show().siblings().hide();
	$('.component-index-slider2 .mt-slider-trigger-container li').eq(slider_index).addClass('mt-slider-current-trigger').siblings().removeClass('mt-slider-current-trigger');
});
$('.component-index-slider2 .mt-slider-next').click(function () {
	var slider_index = $('.component-index-slider2 .mt-slider-trigger-container li.mt-slider-current-trigger').index() + 1;
	if (slider_index == $('.component-index-slider2 .mt-slider-trigger-container li').size()) {
		slider_index = 0;
	}
	$('.component-index-slider2 .content li').eq(slider_index).show().siblings().hide();
	$('.component-index-slider2 .mt-slider-trigger-container li').eq(slider_index).addClass('mt-slider-current-trigger').siblings().removeClass('mt-slider-current-trigger');
});


//热门商品加载
function load_product(arrays) {
	var cateid_str = arrays.join(",");
	$.get('/index.php?c=index&a=ajax_hot_product',
		{cateIdstr: cateid_str},
		function (obj) {
			for (var i in obj) {
				//每个子类
				var htmls = "";
				var ddsj = "";
				for (var ii in obj[i].product) {
					var weidian_code = '/source/qrcode.php?type=good&id=' + obj[i].product[ii].product_id;

					if (obj[i].product[ii].long) {
						var obj_location = getUserDistance();

						if (obj_location) {
							var long = obj_location.long;
							var lat = obj_location.lat;
							var juli = (getFlatternDistance(lat, long, obj[i].product[ii].lat, obj[i].product[ii].long) / 1000).toFixed(2) + 'km';
							var ddsj = expressTime2(obj[i].product[ii].lat, obj[i].product[ii].long);
						} else {
							juli = "0km";
							ddsj = "请设置您的位置";
						}
					} else {
						juli = "";
						ddsj = "请设置您的位置";
					}
					htmls += '<li> <a target="_blank" title="微信扫码直接购买：' + obj[i].product[ii].name + '" href="' + obj[i].product[ii].link + '"> <div class="content_list_img"><img width="224" height="159" onload="AutoResizeImage(224,159,this)"  src="' + obj[i].product[ii].image + '"> <div class="content_list_erweima"> <div class="content_list_erweima_img"><img src="' + weidian_code + '"></div> <div class="content_shop_name">微信扫码直接购买</div></div> </div><div class="content_list_txt"> <div class="content_list_pice">￥<span>' + obj[i].product[ii].price + '</span></div><div class="content_list_dec"><span>售' + obj[i].product[ii].sales + '/</span>分销' + obj[i].product[ii].drp_seller_qty + '</div> </div><div class="content_list_txt"> <div class="content_list_day">' + ddsj + ' </div><div class="content_list_add"><span></span>' + juli + '</div></div> </a> </li>';
				}
				$(".hot_ul_product_" + i).html(htmls);
			}
		},
		'json'
	)
}

function load_self_product() {
	$.get('/index.php?c=index&a=ajax_self_product', {}, 
		function (objs) {
			var htmls = "";
			var ddsj = "";
			for (var ii in objs) {
				var weidian_code = '/source/qrcode.php?type=good&id=' + objs[ii].product_id;

				if (objs[ii].long) {
					var obj_location = getUserDistance();

					if (obj_location) {
						var long = obj_location.long;
						var lat = obj_location.lat;
						var juli = (getFlatternDistance(lat, long, objs[ii].lat, objs[ii].long) / 1000).toFixed(2) + 'km';
						var ddsj = expressTime2(objs[ii].lat, objs[ii].long);
					} else {
						juli = "0km";
						ddsj = "请设置您的位置";
					}
				} else {
					juli = "";
					ddsj = "请设置您的位置";
				}
				htmls += '<li> <a target="_blank" href="' + objs[ii].link + '"> <div class="content_list_img"><img width="224" height="159" onload="AutoResizeImage(224,159,this)"  src="' + objs[ii].image + '"> <div class="content_list_erweima"> <div class="content_list_erweima_img"><img src="' + weidian_code + '"></div> <div class="content_shop_name">' + objs[ii].name + '</div></div> </div><div class="content_list_txt"> <div class="content_list_pice">￥<span>' + objs[ii].price + '</span></div><div class="content_list_dec"><span>售' + objs[ii].sales + '/</span>分销' + objs[ii].drp_seller_qty + '</div> </div><div class="content_list_txt"> <div class="content_list_day">' + ddsj + ' </div><div class="content_list_add"><span></span>' + juli + '</div></div> </a> </li>';
			}
			$(".hot_ul_product_self").html(htmls);
		},
		'json'
	)
}

////热门品牌加载
//function load_brand(arrays2) {
//	var typeid_str = arrays2.join(",");
//	$.get(
//		'index.php?c=index&a=ajax_hot_brand',
//		{typeIdstr: typeid_str},
//		function (obj) {
//			for (var i in obj) {
//				//每个子类
//				var htmls1 = "";
//				var htmls2 = "";
//				var htmls3 = "";
//				var randdetail = "";
//				//var rand = obj[i].rand;
//				for (var ii in obj[i].brand) {
//					if (ii == 0) {
//						randdetail = "class='content_nameplate_left_big'";
//						var height_unique = 314;
//					} else {
//						randdetail = "";
//						var height_unique = 147;
//					}
//					/*
//					 if(ii<8) {
//					 htmls1 +=' <li '+randdetail+' ><div><a	href="'+obj[i].brand[ii].link+'"><img onloads="AutoResizeImage(302,'+height_unique+',this)"   src="'+obj[i].brand[ii].pic+'"  ></a></div> </li>';
//					 } else {
//					 htmls2 += '<li '+randdetail+'><div><a	 href="'+obj[i].brand[ii].link+'"><img  src="'+obj[i].brand[ii].pic+'" onloads="AutoResizeImage(231,231,this)" ></a></div> </li>';
//					 }
//					 */
//					if (ii < 2) {
//						htmls1 += '<li ' + randdetail + '><a href="' + obj[i].brand[ii].link + '"><img src="' + obj[i].brand[ii].pic + '" ></a> </li>';
//
//					} else if (ii > 1 && ii < 8) {
//						htmls2 += '<li ><a href="' + obj[i].brand[ii].link + '"><img src="' + obj[i].brand[ii].pic + '" ></a> </li>';
//					} else if (ii > 7) {
//						htmls3 += '<li ><div><a	 href="' + obj[i].brand[ii].link + '"><img  src="' + obj[i].brand[ii].pic + '" onloads="AutoResizeImage(231,231,this)" ></a></div> </li>';
//					}
//				}
//				$(".hot_ul_brand_" + i).eq(0).html(htmls1);
//				$(".hot_ul_brand_" + i).eq(1).html(htmls2);
//
//				$(".hot_ul_brand_" + i).eq(2).html(htmls3);
//			}
//		},
//		'json'
//	)
//}
//
$(function () {
	//热门商品
	var arr = [];

	$(".hot_category_content").each(function () {
		var data_id = $(this).data("id");
		arr.push(data_id);
		//var category_li = $(".hot_category_sales_category li").index($(this));
		//$(this).addClass("content_curn").siblings().removeClass("content_curn");
		$(this).find(".hot_ul_product").hide();
		$(this).find(".hot_ul_product_"+data_id).show();
		$(this).find(".hot_ul_product_self").show();
		if($(this).find(".tabs").data("tips_have") == '1') {return true;}
		/*
		//不加载二级分娄数据
		var  hot_prodcuttype_list = $(this).find(".hot_li_category").each(function(){
			arr.push($(this).attr("data_li_id"));
		})*/
		$(this).find(".tabs").data("tips_have",'1');
		load_product(arr);
		load_self_product();
//		load_product(new Array(data_id));
	});
	
	/*
	//不加载二级分娄数据
	$('.hot_li_category').click(function(){
		var data_id = $(this).attr('data_li_id');
		$(this).addClass("content_curn").siblings().removeClass("content_curn");
		$(this).parent().parent().parent().parent().find('.hot_ul_product').hide();
		$(this).parent().parent().parent().parent().find('.hot_ul_product_'+data_id).show();
	});*/

//	//热门品牌
//	var arr2 = [];
//	$(".hot_category_brand li").click(function () {
//		var data_li_id2 = $(this).attr("data_li_id2");
//		var brand_type_li = $(".hot_category_brand li").index($(this));
//		$(this).addClass("content_curn").siblings().removeClass("content_curn");
//		$(".hot_ul_brand").hide();
//
//		$(".hot_ul_brand_" + data_li_id2).show();
//		if (brand_type_li == 0) {
//			$(".hot_ul_brand").hide();
//			$(".hot_ul_brand_0").show();
//		}
//
//		if ($(".hot_category_brand .tabs").data("tips_have") == '1') {
//			return true;
//		}
//		var hot_brandtype_list = $(".hot_category_brand .hot_li_brand").each(function () {
//			arr2.push($(this).attr("data_li_id2"));
//		})
//		$(".hot_category_brand .tabs").data("tips_have", '1');
//		load_brand(arr2);
//	});

})


//首页周边活动切换效果
$(function () {
	$('.content_activity').on('click', '.content_commodity_title_content li', function () {
		var idx = $(this).index();
		$(this).parents('.content_commodity_title').siblings('.content_list_activity').eq(idx).addClass('cur').siblings().removeClass('cur');
	});
});


///评价切换
$(function () {
	//return;
	$(".zzsc .tab a").mouseover(function () {
		$(this).addClass('on').siblings().removeClass('on');
		var index = $(this).index();
		number = index;
		$('.zzsc .content_tab li').hide();
		$('.zzsc .content_tab li:eq(' + index + ')').show();
	});

	var auto = 1;  //等于1则自动切换，其他任意数字则不自动切换
	if (auto == 1) {
		var number = 0;
		var maxNumber = $('.zzsc .tab a').length;

		function autotab() {
			number++;
			number == maxNumber ? number = 0 : number;
			$('.zzsc .tab a:eq(' + number + ')').addClass('on').siblings().removeClass('on');
			$('.zzsc .content_tab ul li:eq(' + number + ')').show().siblings().hide();
		}

		var tabChange = setInterval(autotab, 3000000);
		//鼠标悬停暂停切换
		$('.zzsc').mouseover(function () {
			clearInterval(tabChange);
		});
		$('.zzsc').mouseout(function () {
			tabChange = setInterval(autotab, 3000000);
		});
	}
});
