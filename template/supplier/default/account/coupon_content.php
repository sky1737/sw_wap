<style>
.ui-nav-table {position: relative; border-bottom: 1px solid #ccc;margin-bottom: 15px;}
.ui-nav-table ul {zoom: 1;margin-bottom: -1px;}
.pull-left {float: left;}
.ui-nav-table li {float: left;margin-left: -1px;}
.ui-nav-table li.active a {border-color: #ccc #ccc #fff;background: #fff;color: #f60;}
.ui-nav-table li a {  display: inline-block;  padding: 0 12px;  line-height: 40px;  color: #333;  border: 1px solid #ccc;  background: #f8f8f8;  min-width: 80px;  text-align: center;  -webkit-box-sizing: border-box;  -moz-box-sizing: border-box;  box-sizing: border-box;  }
</style>
<nav class="ui-nav-table clearfix">
	<ul class="pull-left js-list-filter-region">
		<li id="js-list-nav-all" class="active"> <a href="#all">所有券</a> </li>
		<li id="js-list-nav-future"> <a href="#future">未开始</a> </li>
		<li id="js-list-nav-on"> <a href="#on">进行中</a> </li>
		<li id="js-list-nav-end"> <a href="#end">已结束</a> </li>
	</ul>
</nav>
<div class="widget-list">
	<div class="js-list-filter-region clearfix ui-box" style="position:relative;">
		<div> <a href="#create" class="ui-btn ui-btn-primary">领取优惠券</a>
			<div class="js-list-search ui-search-box">
				<input class="txt js-coupon-keyword" type="text" placeholder="搜索" value="">
			</div>
		</div>
	</div>
</div>
<div class="ui-box">
	<table class="ui-table ui-table-list" style="padding:0px;">
		<thead class="js-list-header-region tableFloatingHeaderOriginal">
			<tr>
				<th class="cell-15">券名称</th>
				<th class="cell-25">有效时间</th>
				<th class="cell-25">领取限制</th>
				<th class="cell-25">券类型</th>
				<th class="cell-15">活动状态</th>
				<!-- <th class="cell-15">领取人/次</th>
<th class="cell-15">已使用</th> -->
				<th class="cell-25 text-right">操作</th>
			</tr>
		</thead>
		<tbody class="js-list-body-region">
			<tr class="js-present-detail" service_id="24">
				<td>满50可用</td>
				<td><p class="text-left">2015-12-22 00:00:00 至</p>
					<p class="text-left">2016-01-22 00:00:00</p></td>
				<td> 一人1张
					<p class="gray">库存：1000</p></td>
				<td>优惠券</td>
				<td>正在进行</td>
				<!-- <td>0/0</td>
<td>0份</td> -->
				<td class="text-right js-operate" data-coupon_id="24"><a href="javascript:" class="js-link">立即领取</a>- <a href="javascript:void(0);" class="js-delete">删除</a></td>
			</tr>
			<tr class="js-present-detail" service_id="25">
				<td>满100可用</td>
				<td><p class="text-left">2015-12-22 00:00:00 至</p>
					<p class="text-left">2016-01-22 00:00:00</p></td>
				<td>一人1张
					<p class="gray">库存：1000</p></td>
				<td>优惠券</td>
				<td>正在进行</td>
				<!-- <td>0/0</td>
<td>0份</td> -->
				<td class="text-right js-operate" data-coupon_id="25"><a href="javascript:" class="js-link">立即领取</a>-<a href="javascript:void(0);" class="js-delete">删除</a></td>
			</tr>
			<tr class="js-present-detail" service_id="26">
				<td>满200可用</td>
				<td><p class="text-left">2015-12-22 00:00:00 至</p>
					<p class="text-left">2016-01-22 00:00:00</p></td>
				<td>一人1张
					<p class="gray">库存：998</p></td>
				<td>优惠券</td>
				<td>正在进行</td>
				<!-- <td><a href="#fetchlist/26" class="info_detail_person">2</a>
	/2
</td>
<td>0份</td> -->
				<td class="text-right js-operate" data-coupon_id="26"><a href="javascript:" class="js-link">立即领取</a>-<a href="javascript:void(0);" class="js-delete">删除</a></td>
			</tr>
		</tbody>
	</table>
	<div class="js-list-footer-region ui-box">
		<div>
			<div class="pagenavi js-page-list"><span class="total">共 3 条，每页 20 条</span> </div>
		</div>
	</div>
</div>
<div class="js-list-footer-region ui-box"></div>