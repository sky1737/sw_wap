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
			<td><form id="form" action="{pigcms{:U('Order/payfor_log')}" method="get">
					<input type="hidden" name="c" value="Order"/>
					<input type="hidden" name="a" value="payfor_log"/>
					<input type="hidden" name="act" value=""/>
					筛选:
					<input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
					<select name="type">
						<option value="order_no"<?php echo $_GET['type'] == 'order_no' ? ' selected="selected"' : ''; ?>>订单号</option>
						<option value="trade_no"<?php echo $_GET['type'] == 'trade_no' ? ' selected="selected"' : ''; ?>>交易号</option>
						<option value="third_id"<?php echo $_GET['type'] == 'third_id' ? ' selected="selected"' : ''; ?>>付款流水号</option>
						<option value="name"<?php echo $_GET['type'] == 'name' ? ' selected="selected"' : ''; ?>>用户名</option>
					</select>
					&nbsp;&nbsp;下单时间：
					<input type="text" name="start_time" id="js-start-time" class="input-text Wdate" style="width: 150px" value="{pigcms{$Think.get.start_time}"/>
					-
					<input type="text" name="end_time" id="js-end-time" style="width: 150px" class="input-text Wdate" value="{pigcms{$Think.get.end_time}"/>
					<span class="date-quick-pick" data-days="7">最近7天</span> <span class="date-quick-pick" data-days="30">最近30天</span>
					<a class="csv">导出EXCEL</a>
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
						<th>UID</th>
						<th>用户名</th>
						<th>时间</th>
						<th>订单号</th>
						<th>交易号</th>
						<th>微信支付单号</th>
						<th>类型</th>
						<th>金额(元)</th>
						<th>积分(分)</th>
						<th>状态</th>
						<th>备注</th>
					</tr>
				</thead>
				<tbody>
				<if condition="$list neq ''">
					<volist name="list" id="record">
							<tr>
								<td>{pigcms{$record['uid']}</td>
								<td>{pigcms{$record['nickname']}</td>
								<td>{pigcms{$record['add_time']|date='Y-m-d H:i:s', ###}</td>
								<td>{pigcms{$record['order_no']}</td>
								<td>{pigcms{$record['trade_no']}</td>
								<td>{pigcms{$record['third_id']}</td>
								<td><?php echo $record['type'] ? '开店' : '红包'?></td>
								<td class="text-right ui-money ui-money-income"><?php echo ($record['type']  ? '+' : '-') . number_format(abs($record['pay_money']), 2, '.', ''); ?></td>
								<td class="text-right ui-money ui-money-outlay"><?php echo ($record['type']  ? '+' : '-') .'0'; ?></td>
								<td class="text-right">已支付</td>
								<td>{pigcms{$record['remarks']}</td>
							</tr>
						</volist>
						<tr>
							<td class="textcenter pagebar" colspan="11">{pigcms{$pager}</td>
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

<script type="text/javascript">
	$(function () {
		//切换包裹选项卡
		$('.csv').click(function () {
			$("input[name=act]").val('export');
			$( "#form" ).submit();
		})
	})
</script>
<include file="Public:footer"/>