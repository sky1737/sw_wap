<include file="Public:header"/>
<style type="text/css">
.date-quick-pick { display: inline-block; color: #07d; cursor: pointer; padding: 2px 4px; border: 1px solid transparent; margin-left: 12px; border-radius: 4px; line-height: normal; }
.date-quick-pick.current { background: #fff; border-color: #07d !important; }
.date-quick-pick:hover { border-color: #ccc; text-decoration: none }
</style>
<div class="mainbox">
	<div id="nav" class="mainnav_title">
		<ul>
			<a href="{pigcms{:U('Order/redpack')}" class="on">订单列表</a>
		</ul>
	</div>
	<table class="search_table" width="100%">
		<tr>
			<td><form action="{pigcms{:U('Order/redpack')}" method="get">
					<input type="hidden" name="c" value="Order"/>
					<input type="hidden" name="a" value="redpack"/>
					筛选:
					<input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
					<select name="type">
						<option value="order_no"<?php echo $_GET['type'] == 'order_no' ? ' selected="selected"' : ''; ?>>订单号</option>
						<option value="merchant"<?php echo $_GET['type'] == 'merchant' ? ' selected="selected"' : ''; ?>>商家名称</option>
						<option value="name"<?php echo $_GET['type'] == 'name' ? ' selected="selected"' : ''; ?>>店铺名称</option>
					</select>
					&nbsp;&nbsp;状态：
					<select name="status">
						<option value="">订单状态</option>
						<volist name="status" id="value">
						<option value="{pigcms{$key}"<?php echo $_GET['status'] == $key ? ' selected="selected"' : ''; ?>>{pigcms{$value}</option>
						</volist>
					</select>
					&nbsp;&nbsp;下单时间：
					<input type="text" name="start_time" id="js-start-time" class="input-text Wdate" style="width: 150px" value="{pigcms{$Think.get.start_time}"/>
					-
					<input type="text" name="end_time" id="js-end-time" style="width: 150px" class="input-text Wdate" value="{pigcms{$Think.get.end_time}"/>
					<span class="date-quick-pick" data-days="7">最近7天</span> <span class="date-quick-pick" data-days="30">最近30天</span>
					<input type="submit" value="查询" class="button"/>
				</form></td>
		</tr>
	</table>
	<form name="myform" id="myform" action="" method="post">
		<div class="table-list">
			<table width="100%" cellspacing="0">
				<thead>
					<tr>
						<th width="150">订单号 / 交易号</th>
						<th>图像</th>
						<th>昵称</th>
						<th>时间</th>
						<th>金额</th>
						<th>状态</th>
					</tr>
				</thead>
				<tbody>
					<if condition="is_array($orders)">
						<volist name="orders" id="order">
							<tr>
								<td>{pigcms{$order.order_no}<br/>{pigcms{$order.trade_no}</td>
								<td><img src="{pigcms{$order.avatar}" width="50" /></td>
								<td>{pigcms{$order.nickname}</td>
								<td>{pigcms{$order.add_time|date="Y-m-d H:i:s",###}</td>
								<td>￥{pigcms{$order.amount}</td>
								<td>{pigcms{$status[$order['status']]}</td>
							</tr>
						</volist>
						<tr>
							<td class="textcenter pagebar" colspan="11">{pigcms{$page}</td>
						</tr>
						<else/>
						<tr>
							<td class="textcenter red" colspan="10">列表为空！</td>
						</tr>
					</if>
				</tbody>
			</table>
		</div>
	</form>
</div>
<include file="Public:footer"/>