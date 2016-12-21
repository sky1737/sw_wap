<div class="js-app-inner app-inner-wrap hide" style="display: block;">
	<div id="js-store-board" class="ui-store-board">
		<dl class="clearfix">
			<dt class="js-store-board-logo ui-store-board-logo">
				<a href="<?php dourl('info') ?>" style="background-image:url(<?php echo $user_session['avatar']; ?>);">
					<i class="hide" style="display: none;">修改</i> </a></dt>
			<dd class="ui-store-board-desc">
				<h2><?php echo $user_session['nickname']; ?>
					<span style="font-size: 12px;font-weight:400; color: #A0A0A0;">，欢迎您！上次登陆IP：<?php echo long2ip($user['last_ip']); ?>，登陆时间：<?php echo date('Y-m-d H:i:s',
							$user["last_time"]) ?></span></h2>
				<nav><a class="ui-btn" href="<?php dourl('info'); ?>">修改资料</a>
					<a class="ui-btn" href="<?php dourl('order'); ?>">我的订单</a>
					<!--<span>我的订单：</span>
				<a class="ui-btn" href="#">待付款(0)</a>
				<a class="ui-btn" href="#">待发货(0)</a>
				<a class="ui-btn" href="#">待收货(0)</a>
				<a class="ui-btn" href="#">退款(0)</a>-->
					<a class="ui-btn visit-store" href="<?php dourl('store:select') ?>" style="margin-right: 7px;">+我要开店</a>
					<?php
					/* <a class="js-create-template ui-btn ui-btn-success" href="http://www.twiker.com/user.php?c=store&amp;a=wei_page#create">+新建微页面</a> <a class="ui-btn visit-store" href="javascript:void(0);" data-ui-version="3" data-class="bottom center">访问店铺</a><span class="js-notes-cont hide">
						<p>手机扫码访问：</p>
						<p class="team-code"> <img src="./source/qrcode.php?type=home&amp;id=91" alt=""> </p>
						<p> <a class="js-help-notes-btn-copy" href="javascript:void(0);" data-clipboard-text="http://91.jt.252ws.com/wap/">复制页面链接</a> <a href="http://91.jt.252ws.com/wap/" target="_blank">电脑上查看</a> </p>
						</span>*/ ?>
				</nav>
			</dd>
			<div id="js-overview" class="ui-overview" style="float:right;">
				<div class="overview-group">
					<div class="overview-group-inner"><span class="h4"><?php echo $total; ?></span> <span class="h5">全部订单</span></div>
				</div>
				<div class="overview-group">
					<div class="overview-group-inner">
						<span class="h4" style="color: #e86062;"><?php echo $user_session['balance']; ?></span>
						<span class="h5">账户余额</span></div>
				</div>
				<div class="overview-group">
					<div class="overview-group-inner">
						<span class="h4" style="color: #e86062;"><?php echo $user_session['point']; ?></span>
						<span class="h5">我的积分</span></div>
				</div>
			</div>
		</dl>
		<!--<div class="ui-store-board-cert-waiting">已认证</div>-->
	</div>
	<style type="text/css">
		.content .content_list { width: 100%; font-size: 12px; height: auto; }
		.content li { position: relative; }
		.content li .content_list_erweima { width: 100%; height: 100%; position: absolute; top: 0; left: 0; background: rgba(0, 0, 0, 0.7); display: none; filter: alpha(opacity=50); filter: progid:DXImageTransform.Microsoft.gradient(startcolorstr=#7F000000, endcolorstr=#7F000000);
			overflow: hidden; }
		.content li:hover .content_list_erweima { display: block; }
		.content li .content_list_erweima .content_list_erweima_img { margin-left: 30px; margin-top: 10px; }
		.content li .content_list_erweima .content_shop_name { width: 100%; color: #fff; text-align: center; font-size: 18px; }
		.content li .content_list_erweima .content_shop_name:hover { color: rgb(245, 38, 72); }
		.content .content_commodity ul li .content_list_erweima .content_list_erweima_img img { width: 70%; height: auto; padding: 6%; }
		.content .content_commodity ul:after { content: ""; height: 0; clear: both; display: block }
		.content .content_commodity ul.content_list_ul li { float: left; border: 1px solid #e6e6e6; box-shadow: 0 0 5px #fff; border: 1px solid #d9d9d9 \9; padding-bottom: 15px;
			margin: 20px 20px 0 0; display: inline; }
		.content .content_commodity ul.content_list_ul li:hover { box-shadow: 0 0 20px #d9d9d9; border: 1px solid #e6e6e6; opacity: 0.9; }
		.content .content_commodity ul.content_list_ul li .content_list_img { margin-bottom: 15px; position: relative; }
		.content .content_list li { width: 224px; position: relative; }
		.content .content_list li img { width: 224px; height: 224px; }
		.content .content_commodity li .content_list_txt { padding: 0 3%; width: 94%; }
		.content .content_commodity li .content_list_txt:after { content: ""; height: 0; clear: both; display: block }
		.content .content_commodity li .content_list_pice { float: left; color: #c91623; }
		.content .content_commodity li .content_list_pice span { font-size: 20px; }
		.content .content_commodity li .content_list_dec { float: right; color: #191919; }
		.content .content_commodity li .content_list_dec span { color: #6c6c6c; }
		.content .content_commodity li .content_list_day { float: left; color: #666; margin-left: 5px; }
		.content .content_commodity li .content_list_add { /*float:right*/; margin-right: 5px; color: rgb(245, 38, 72); font-size: 14px; }
		.content .content_commodity li .content_list_add:after { content: ""; height: 0; clear: both; display: block; }
		.content .content_commodity li .content_list_add span { display: block; width: 25px; height: 25px; background: url(../images/weidian_icon.png) 604px -121px; float: left; margin-left: 40px; }
		.content .content_commodity li .content_shop_name { float: left; font-size: 14px; color: #191919; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 115px; }
		.content .content_commodity li .content_list_erweima .content_shop_name { font-size: 16px; color: #fff; margin: 0 auto; width: 80%; float: none; }
		.content .content_commodity li .content_list_erweima .content_shop_name:hover { color: rgb(245, 38, 72); }
		.content .content_commodity li .content_list_distance { float: left; margin-left: 5px; font-size: 14px; color: #666; }
		.content .content_commodity .content_shop_jion { color: #6c6c6c; float: right; margin-right: 5px; }
		.content .content_commodity .content_shop_jion span { color: #ffba41; }
		.content .content_commodity li .shop_info { color: #6c6c6c; margin: 10px 12px 0 12px;; padding: 10px 5px 0 5px; border-top: 1px solid #d9d9d9; }
		.content .content_commodity li .shop_info:after { content: ""; height: 0; clear: both; display: block }
		.content .content_commodity li .content_shop_add { float: right; }
	</style>
</div>
