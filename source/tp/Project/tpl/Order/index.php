<include file="Public:header"/>
<style type="text/css">
.date-quick-pick { display: inline-block; color: #07d; cursor: pointer; padding: 2px 4px; border: 1px solid transparent; margin-left: 12px; border-radius: 4px; line-height: normal; }
.date-quick-pick.current { background: #fff; border-color: #07d !important; }
.date-quick-pick:hover { border-color: #ccc; text-decoration: none }
</style>
<div class="mainbox">
	<div id="nav" class="mainnav_title">
		<ul>
			<a href="{pigcms{:U('Order/index')}" class="on">订单列表</a>
		</ul>
	</div>
	<table class="search_table" width="100%">
		<tr>
			<td><form action="{pigcms{:U('Order/index')}" method="get">
					<input type="hidden" name="c" value="Order"/>
					<input type="hidden" name="a" value="index"/>
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
					&nbsp;&nbsp;对账情况：
					<select name="is_check">
						<option value="">全部</option>
						<volist name="check" id="value">
						<option value="{pigcms{$key}"<?php echo $_GET['is_check'] == $key ? ' selected="selected"' : ''; ?>>{pigcms{$value}</option>
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
				<colgroup>
				<col/>
				<col/>
				<col/>
				<col/>
				<col/>
				<col width="180" align="center"/>
				</colgroup>
				<thead>
					<tr>
						<th width="150">订单号</th>
						<th>商家名称</th>
						<th>店铺名称</th>
						<th>收货人</th>
						<th>电话</th>
						<th>对账情况</th>
						<th>下单时间</th>
						<th>总价</th>
						<th>状态</th>
						<th>备注</th>
						<th class="textcenter">操作</th>
					</tr>
				</thead>
				<tbody>
					<if condition="is_array($orders)">
						<volist name="orders" id="order">
							<tr>
								<td>{pigcms{$order.order_no}</td>
								<td>{pigcms{$order.linkman}</td>
								<td>{pigcms{$order.store}</td>
								<td>{pigcms{$order.address_user}</td>
								<td>{pigcms{$order.address_tel}</td>
								<td><?php echo $order['is_check'] == 2 ? '已对账' : '未对账';?></td>
								<td>{pigcms{$order.add_time|date="Y-m-d H:i:s",###}</td>
								<td>￥{pigcms{$order.total}</td>
								<td>{pigcms{$status[$order['status']]}</td>
								<td>{pigcms{$order.bak}</td>
								<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Order/detail',array('id' => $order['order_id'], 'frame_show' => true))}','订单详情 #{pigcms{$order.order_no}',800,600,true,false,false,false,'detail',true);">操作</a></td>
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