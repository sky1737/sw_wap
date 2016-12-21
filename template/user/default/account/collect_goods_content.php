<style type="text/css">
    .red { color: red; }
    .goods-image img{width: 60px; height: 60px;}
    .goods-list .goods-meta .goods-price {display: inline-block;vertical-align: middle;font-size: 12px;line-height: 14px;color: #FF9A03;}
    .goods-list .goods-image-td {padding-right: 10px;width: 70px;}
    .pull-left {float: left;}
    .pull-left .ui-btn {display: inline-block;border-radius: 2px;height: 26px;line-height: 26px;padding: 0 12px;cursor: pointer;color: #666!important;border: 1px solid #ddd;text-align: center;font-size: 12px;}
</style>
<nav class="ui-nav">
	<ul>
		<li class="js-list-index active"><a href="#list">商品收藏</a></li>
	</ul>
</nav>
<div class="widget-list-filter">
	<div class="market-filter-container">
		<div class="js-list-search">
			<input type="text" placeholder="搜素已收藏商品" value="">
			<input type="button" class="market-search-btn ui-btn ui-btn-primary" value="搜索" style="vertical-align:top; border: 1px solid #ddd; margin-left:10px;">
		</div>
	</div>
</div>
<div class="goods-list">
	<div class="ui-box">
		<table class="ui-table ui-table-list" style="padding: 0px;">
			<thead class="js-list-header-region tableFloatingHeaderOriginal" style="position: static; top: 0px; margin-top: 0px; left: 601.5px; z-index: 1; width: 850px;">
				<tr>
					<th class="checkbox cell-35" colspan="3" style="min-width: 332px; max-width: 332px;"> <label class="checkbox inline">
							<input type="checkbox" class="js-check-all">
							商品</label>
						<a href="javascript:;" class="orderby" data-orderby="price">商品详情</a> </th>
					<th class="cell-8 text-right" style="min-width: 68px; max-width: 68px;"> <a href="javascript:;" class="orderby" data-orderby="quantity">访问量</a> </th>
					<th class="cell-8 text-right" style="min-width: 68px; max-width: 68px;"> <a href="javascript:;" class="orderby" data-orderby="quantity">收藏量</a> </th>
					<th class="cell-8 text-right" style="min-width: 68px; max-width: 68px;"> <a href="javascript:;" class="orderby" data-orderby="sales">已销售</a> </th>
					<th class="cell-15 text-right" style="min-width: 127px; max-width: 127px;">操作</th>
				</tr>
			</thead>
			<tbody class="js-list-body-region">
			<?php
			foreach ($products as $product) { ?>
				<tr>
					<td class="checkbox"><input type="checkbox" class="js-check-toggle" value="<?php echo $product['product_id']; ?>"></td>
					<td class="goods-image-td"><div class="goods-image js-goods-image "><img src="<?php echo $product['image'] ?>"> </div></td>
					<td class="goods-meta">
						<p class="goods-title"> <a href="<?php echo url_rewrite('goods:index',
								array('id' => $product['product_id'])) ?>" target="_blank" class="new-window" title="<?php echo $product['name']; ?>"><?php echo $product['name']; ?></a> </p>
						<p> <span class="goods-price" goods-price="<?php echo $product['price']; ?>">￥<?php echo $product['price']; ?></span> </p></td>
					<td class="text-right"><?php echo $product['pv'] ?></td>
					<td class="text-right"><?php echo $product['collect'] ?></td>
					<td class="text-right"><?php echo $product['sales'] ?>件</td>
					<td class="text-right " style="margin: 0 auto;"><p class="anniu"> <a class="anjian" target="_blank" href="<?php echo url_rewrite('goods:index',
								array('id' => $product['product_id'])) ?>">立即购买</a></p>
						<p class="anniu"><a href="javascript:void(0);" class="js-delete" data-id="<?php echo $product['product_id']; ?>">取消收藏</a> </p></td>
				</tr>
				<?php
				/*<tr id="tr_1159804448">
					<td><a target="_blank" href=""> <img width="100" height="100" src="<?php echo $product['image'] ?>" alt="<?php echo $product['name']; ?>"> </a></td>
					<td class="tb01"><div class="p-name"><a href="<?php echo url_rewrite('goods:index',
								array('id' => $product['product_id'])) ?>" target="_blank"></a> </div>

						<!-- <div class="p-evel">
					       <span id="star1159804448" class="star sa5">五星级</span>
					       <a href="javascript:void(0);" target="_blank" clstag="click|count|follow|productpj"><span class="p-s-n" id="pj1159804448">39</span>评价</a>
					      </div>  -->

						<div class="ftag">
							<div id="t1159804448" piden="" clstag="homepage|keycount|guanzhu|bjbiaoqian"> 成交<?php echo $product['sales'] <
								10 ? '10笔以内' : $product['sale'] . '笔' ?> </div>
							<div class="prompt-01" pid="1159804448" style="display:none"></div>
						</div>
						<div class="date"> 收藏时间：<?php echo date('Y-m-d', $product['add_time']); ?> </div>
						<div class="btns"> <a id="a_simi_1159804448" class="btn btn-12 psame" style="display:none" href="javascript:void(0);" clstag="click|count|follow|btnsimi"> <s></s><b></b>找相似</a> <a id="a_match_1159804448" class="btn btn-12 pcoll" style="display:none" href="javascript:void(0);" clstag="click|count|follow|btnmatch"> <s></s><b></b>找搭配</a>
							<!-- <a id="a_match_1159804448" class="btn btn-12 pcoll" style="display:none" onclick="find_match(1159804448);" href="javascript:void(0);"><s></s><b></b>找搭配</a>  -->
						</div></td>
					<td class="tb02"><div class="p-price" id="price_1159804448"> ￥<?php echo $product['price'] ?> </div></td>
					<td><div class="p-state">
							<div id="f_1159804448" class="ac"> <?php echo $product['quantity'] ?> </div>
						</div>
						<input type="hidden" id="state_1159804448" value="0">
						<input type="hidden" id="isLoc_1159804448" value=""></td>
					<td><ul class="operating">
							<li><a id="buyCart_1159804448" class="btn-add" href="">我要购买</a></li>
							<li> <a onclick="cancelCollect(<?php echo $product['product_id']; ?>, 1)" href="javascript:void(0);">取消关注</a> </li>
						</ul></td>
				</tr>*/
			}
			?>

			</tbody>
		</table>
	</div>
	<!-- ui-box -->
	<div class="js-list-footer-region ui-box">
		<div>
			<div class="pull-left"> <a href="javascript:;" class="ui-btn js-batch-delete">删除</a> </div>
			<div class="js-page-list ui-box pagenavi"><?php echo $pages ?></div>
		</div>
	</div>
</div>