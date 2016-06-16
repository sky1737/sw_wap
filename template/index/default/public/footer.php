<?php include display('public:activity_win'); ?>

<div class="footer1 ">
	<div><?php
	if(empty($pc_footer)) {
			echo '<li>请添加标识为 pc_footer 的广告</li>';
	} else {
		foreach ($pc_footer as $adk => $adv) {
			echo '<a href="' . $adv['url'] . '"><img src="' . $adv['pic'] . '"></a>';
		}
	} ?>
	<?php
//	pc_footer

	?></div>
	<div class="Cfooter">
		<div class="Cfooter-info">
			<dl class="w1000"style="auto;text-align:">
				<dt> <strong><img style="width:180px;" src="<?php echo $config['site_logo'] ?>"></strong> </dt>
				<dd> <strong><?php echo $config['site_name']; ?></strong> 
					<a target="_blank" href="/help/111/119.html">关于云温商</a>
					<a target="_blank" href="/help/111/120.html">合作及洽谈</a>
					<a target="_blank" href="/help/111/121.html">免责声明</a>
				</dd>
				<dd> <strong>购物指南</strong>
					<a target="_blank" href="/help/112/122.html">购物流程</a>
					<a target="_blank" href="/help/111/123.html">交易条款</a>
					<a target="_blank" href="/help/112/124.html">常见问题</a>
				</dd>
				<dd> <strong>配送方式</strong>
					<a target="_blank" href="/help/114/125.html">商品配送</a>
					<a target="_blank" href="/help/114/126.html">验货与签收</a>
					<a target="_blank" href="/help/114/127.html">长时间未收到商品</a>
				</dd>
				<dd> <strong>支付方式</strong>
					<a target="_blank" href="/help/113/131.html">在线支付</a>
					<a target="_blank" href="/help/113/132.html">货到付款</a>
					<a target="_blank" href="/help/113/133.html">优惠券使用</a>
				</dd>
				<dd> <strong>售后服务</strong>
					<a target="_blank" href="/help/115/128.html">售后政策</a>
					<a target="_blank" href="/help/115/129.html">退换货流程</a>
					<a target="_blank" href="/help/115/130.html">退款方式</a>
				</dd>
				<dd class="f-sao1"> <strong>官方微商城</strong> <b><strong><img  style="width:77px;" src="<?php echo option('config.wechat_qrcode'); ?>"/></strong></b> </dd>
			</dl>
		</div>
	</div>
	<div class="footer_txt">
		<?php
		if ($link_list) {
			?>
		<div class="footer_list" style="text-align:center;">
			<ul style="text-align:center;">
				<?php
					foreach ($link_list as $key => $link) {
						echo '<li>' . ($key ? '<span>|</span>' : '') . '<a href="' . $link['url'] .
							'" target="_blank">' . $link['name'] . '</a></li>';
					}
					?>
			</ul>
		</div>
		<?php
		}
		?>
		<div class="Cfooter-cr"> <span><?php echo $config['site_footer'] . ' ' . $config['site_icp']; ?></span>
			<div class="Cfooter-cr-img"> <a id="___szfw_logo___" class="cxwz" rel="nofollow" target="_blank" href="#"></a> <a class="kxwz" rel="nofollow" target="_blank" href="#"></a> <a class="pjzxlm" rel="nofollow" target="_blank" href="#"></a> 
				<!--<a class="itrust" rel="nofollow" target="_blank" href="#">中国互联网信用评价中心</a> <a href="#" target="_blank" rel="nofollow" class="xfwq"></a>--> 
			</div>
		</div>
		
		<!--<div class="footer_txt">
			<?php echo $config['site_footer'] . ' ' . $config['site_icp']; ?>
		</div>--> 
	</div>
</div>

<!--二维码弹出层-->
<style type="text/css">
.right-red-radius {background-color: #fff;border-radius: 10px;}
.mui-mbar-tab-sup-bd {font-size: 12px;}
</style>
<div class="content_right" id="leftsead" style="position: fixed; top: 352px;">
	<ul>
		<?php
		if(!empty($user_session)){
			echo '<li class="content_right_user"><a href="'.url('user:account:index').'"><img src="'.$user_session['avatar'].'" alt="'.$user_session['nickname'].'" /></a></li>'; //
		}
		?>
		<li class="content_right_shpping">
			<div id="cartbottom">
				<div class="right-red-radius" style="margin-top: 0px; color:#B1191A; position: absolute;z-index:2; width: 20px; height: 22px; font-size: 12px;line-height:22px;">
					<div class="mui-mbar-tab-sup-bd">
						<?php if (($cart_number + 0) > 99) {
							echo "99";
						}
						else {
							echo $cart_number + 0;
						} ?>
					</div>
				</div>
			</div>
		</li>
		<li class="content_right_erweima"> <a href="javascript:void(0)">
			<div class="content_right_erweima_img"><img src="<?php echo option('config.wechat_qrcode'); ?>"></div>
			</a> </li>
		<li class="content_right_gotop"><a href="javascript:scroll(0,0)"></a></li>
	</ul>
</div>
<script type="text/javascript">
	function addCart_pf(event) {
		//$("#leftsead").show();
		var offset = $('#cartbottom').offset(),
			flyer = $('<div class="right-red-radius" style="margin-top: 0px; color:#fff; position: absolute;z-index:9999; width: 20px; height: 22px; font-size: 12px;line-height:22px;"><div class="mui-mbar-tab-sup-bd"></div></div>');
		//offset.top = "352";

		flyer.fly({
			start: {left: event.pageX, top: event.clientY - 30},
			end: {left: offset.left, top: offset.top, width: 20, height: 20},
			onEnd: function () {
				var cart_number = parseInt($("#header_cart_number").text());
				if (cart_number > 99) {
					cart_number = 99;
				}
				$(".mui-mbar-tab-sup-bd").html(cart_number);
			}
		});
	}
	$(function () {
		$(".content_right_shpping").css("cursor", "pointer");
		$(".content_right_shpping").click(function () {
			location.href = "<?php echo url('cart:one') ?>";
		});
	})
</script>