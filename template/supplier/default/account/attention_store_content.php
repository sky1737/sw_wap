<style type="text/css">
    .red {color: red;}
    .goods-image img{width: 100px; height: 100px;}
    .goods-list .goods-meta .goods-price {display: inline-block;vertical-align: middle;font-size: 12px;line-height: 14px;color: #FF9A03;}
    .goods-list .goods-image-td {padding-right: 10px;width: 70px;}
    .pull-left {float: left;}
    .pull-left .ui-btn {display: inline-block;border-radius: 2px;height: 26px;line-height: 26px;padding: 0 12px;cursor: pointer;color: #666!important;border: 1px solid #ddd;text-align: center;font-size: 12px;}
</style>
<nav class="ui-nav">
	<ul>
		<li class="js-list-index active"><a href="#list">店铺收藏</a></li>
	</ul>
</nav>
<div class="widget-list-filter">
	<div class="market-filter-container">
		<div class="js-list-search">
			<input type="text" placeholder="搜素已收藏店铺" value="">
			<input type="button" class="market-search-btn ui-btn ui-btn-primary" value="搜索" style="vertical-align:top; border: 1px solid #ddd; margin-left:10px;">
		</div>
	</div>
</div>
<div class="goods-list">
	<div class="ui-box">
		<table class="ui-table ui-table-list" style="padding: 0px;">
			<thead class="js-list-header-region tableFloatingHeaderOriginal">
			<tr>
				<th class="checkbox cell-35" colspan="2">
					<label class="checkbox inline">
						<input type="checkbox" class="js-check-all">
						店铺</label>
				</th>
				<th>
					<a href="javascript:;" class="orderby" data-orderby="price">店铺详情</a></th>
				<th class="text-right">操作</th>
			</tr>
			</thead>
			<?php
			if(!empty($store_list)) {
				echo '<tbody class="js-list-body-region">';
				foreach ($store_list as $store) { ?>
					<tr>
						<td class="checkbox">
							<input type="checkbox" class="js-check-toggle" value="<?php echo $store['store_id'] ?>">
						</td>
						<td class="goods-image-td">
							<div class="goods-image js-goods-image ">
								<img src="<?php echo $store['logo'] ?>" />
							</div>
						</td>
						<td class="goods-meta">
							<p class="goods-title">
								<a href="<?php echo url_rewrite('store:index',
									array('id' => $store['store_id'])) ?>" target="_blank" class="new-window" title="<?php echo htmlspecialchars($store['name']) ?>"><?php echo htmlspecialchars($store['name']) ?></a>
							</p>
							<p>&nbsp;</p>
							<p><span>联系卖家：<?php echo $store['service_tel'] ?></span></p></td>
						<td class="goods-meta text-right" style="padding-top:20px;"><p class="anniu">
								<a href="<?php echo url_rewrite('store:index',
									array('id' => $store['store_id'])) ?>" target="_blank">进入店铺</a></p>
							<p class="anniu">
								<a href="javascript:void(0);" class="js-delete" data-id="<?php echo $store['store_id'] ?>">取消关注</a>
							</p></td>
					</tr>
					<?php
					/*<li>
						<div class="youhuiquan_shop_info clearfix">
							<div class="youhuiquan_shop_info_img"></div>
							<div class="youhuiquan_shop_info_c">
								<div class="youhuiquan_shop_info_c_txt"></div>
								<div class="youhuiquan_shop_info_c_txt">联系卖家:<span><?php echo $store['service_tel'] ?></span>
								</div>
								<div class="youhuiquan_shop_info_c_txt">店查看优惠券<span><?php echo $store['collect'] ?></span>
								</div>
							</div>
							<div class="youhuiquan_shop_info_r">
								<button class="go_shop" onclick="location.href=''" style="cursor:pointer">进入店铺
								</button>
								<button data-id="<?php echo $store['store_id'] ?>" class="dt-shop-collect" style="cursor:pointer">取消</button>
							</div>
						</div>
					</li>*/
				}
				echo '</tbody>';
			}
			?>
		</table>
	</div>
	<!-- ui-box -->
	<div class="js-list-footer-region ui-box">
		<div>
			<div class="pull-left"> <a href="javascript:;" class="ui-btn js-batch-delete">删除</a> </div>
			<div class="js-page-list ui-box pagenavi">
				<?php echo $pages; ?>
			</div>
		</div>
	</div>
</div>