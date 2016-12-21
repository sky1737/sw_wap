<include file="Public:header"/>
<style type="text/css">
.green { color: green; }
.red { color: red; }
</style>
<div class="mainbox">
	<div id="nav" class="mainnav_title">
		<ul>
			<a href="{pigcms{:U('Recharge/index')}" class="on">充值规则列表</a>| <a href="javascript:void(0);"
               onclick="window.top.artiframe('{pigcms{:U('Recharge/edit')}','添加充值规则',520,250,true,false,false,addbtn,'add',true);">添加充值规则</a>
		</ul>
	</div>
	<form name="myform" id="myform" action="" method="post">
		<div class="table-list">
			<table width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>编号</th>
						<th>金额</th>
						<th>积分</th>
						<th>分成金额</th>
						<th>开始时间</th>
						<th>结束时间</th>
						<th>状态</th>
						<th>排序</th>
						<th class="textcenter">操作</th>
					</tr>
				</thead>
				<tbody>
					<if condition="is_array($list)">
						<volist name="list" id="item">
							<tr>
								<td>{pigcms{$item.recharge_id}</td>
								<td>{pigcms{$item.amount}</td>
								<td>{pigcms{$item.point}</td>
								<td>{pigcms{$item.profit}</td>
								<td>{pigcms{$item.start_time|date='Y-m-d H:i:s',###}</td>
								<td>{pigcms{$item.end_time|date='Y-m-d H:i:s',###}</td>
								<td><?php echo ($item['status'] ? '<span class="green">启用' : '<span class="red">禁用').'</span>'; ?></td>
								<td>{pigcms{$item.sort}</td>
								<td class="textcenter">
								<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Recharge/edit',array('id'=>$item['recharge_id']))}','编辑充值规则信息',520,250,true,false,false,editbtn,'add',true);">编辑</a> | <a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$item.recharge_id}" url="{pigcms{:U('Recharge/del')}">删除</a></td>
							</tr>
						</volist>
						<tr>
							<td class="textcenter pagebar" colspan="10">{pigcms{$page}</td>
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