<div class="goods-list">
	<div class="js-list-filter-region clearfix ui-box" style="position: relative;">
		<div>
			<h3 class="list-title js-goods-list-title">我的商品</h3>
		</div>
	</div>
	<div class="ui-box">
		<table class="ui-table ui-table-list" style="padding: 0px;">
			<thead class="js-list-header-region tableFloatingHeaderOriginal"
style="position: static; top: 0px; margin-top: 0px; left: 601.5px; z-index: 1; width: 850px;">
				<?php if (!empty($products)) { ?>
				<tr>
					<th class="checkbox cell-35" colspan="3">
						<label class="checkbox inline">商品</label>
					</th>
					<th class="cell-10 text-right" style="min-width: 85px; max-width: 85px;">人气</th>
					<th class="cell-8 text-right" style="min-width: 68px; max-width: 68px;"> 价格 </th>
					<th class="cell-8 text-right" style="min-width: 68px; max-width: 68px;"> 成本 </th>
					<th class="cell-8 text-right" style="min-width: 68px; max-width: 68px;"> 库存 </th>
					<th class="cell-8 text-right" style="min-width: 68px; max-width: 68px;"> 销量 </th>
				</tr>
				<?php } ?>
			</thead>
			<tbody class="js-list-body-region">
				<?php foreach ($products as $product) { ?>
				<tr>
					<td class="checkbox"></td>
					<td class="goods-image-td"><div class="goods-image js-goods-image "> <img src="<?php echo $product['image']; ?>"/> </div></td>
					<td class="goods-meta"><p class="goods-title"> <a href="<?php echo $config['wap_site_url']; ?>/good.php?id=<?php echo $product['product_id']; ?>" target="_blank" class="new-window" title="<?php echo $product['name']; ?>"> <?php echo $product['name']; ?> </a> </p>
						<p><span class="goods-price" goods-price="<?php echo $product['price']; ?>">￥<?php echo $product['price']; ?></span> </p>
					</td>
					<td class="text-right"><?php echo $product['pv']; ?></td>
					<td class="text-right"><?php echo  $product['market_price']; ?></td>
					<td class="text-right"><?php echo $product['price']; ?></td>
					<td class="text-right"><?php echo $product['quantity']; ?></td>
					<td class="text-right"><?php echo $product['sales']; ?></td>

				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php if (empty($products)) { ?>
		<div class="js-list-empty-region">
			<div>
				<div class="no-result">还没有相关数据。</div>
			</div>
		</div>
		<?php } ?>
	</div>
	<div class="js-list-footer-region ui-box">
		<?php if (!empty($products)) { ?>
		<div>
			<div class="pull-left"> <a href="javascript:;" class="ui-btn js-batch-apply-to-fx">批量申请分销</a> </div>
			<div class="js-page-list ui-box pagenavi"><?php echo $page; ?></div>
		</div>
		<?php } ?>
	</div>
</div>
