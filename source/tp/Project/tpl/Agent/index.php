<include file="Public:header"/>
<style type="text/css">
.green { color: green; }
.red { color: red; }
</style>
<div class="mainbox">
	<div id="nav" class="mainnav_title">
		<ul>
			<a href="{pigcms{:U('Agent/index')}" class="on">代理级别列表</a>| <a href="javascript:void(0);"
               onclick="window.top.artiframe('{pigcms{:U('Agent/edit')}','添加代理级别',520,250,true,false,false,addbtn,'add',true);">添加代理级别</a>
		</ul>
	</div>
	<form name="myform" id="myform" action="" method="post">
		<div class="table-list">
			<table width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>编号</th>
						<th>名称</th>
						<th>用戶權限</th>
						<!--<th>商品数量</th>-->
						<th>备注</th>
						<th>状态</th>
						<th>排序</th>
						<th class="textcenter">操作</th>
					</tr>
				</thead>
				<tbody>
					<if condition="is_array($list)">
						<volist name="list" id="item">
							<tr>
								<td>{pigcms{$item.agent_id}</td>
								<td>{pigcms{$item.name}</td>
								<td><span class="green"><?php
									echo $item['open_self'] ? '供應商' : '';
									echo $item['is_agent'] ? '代理商' : '';
									echo $item['is_editor'] ? '產品編輯' : ''; ?></span></td>
								<!--<td>{pigcms{$item.max_products}</td>-->
								<td>{pigcms{$item.remarks}</td>
								<td><?php echo ($item['status'] ? '<span class="green">启用' : '<span class="red">禁用').'</span>'; ?></td>
								<td>{pigcms{$item.sort}</td>
								<td class="textcenter">
								<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Agent/edit',array('id'=>$item['agent_id']))}','编辑代理级别信息',520,250,true,false,false,editbtn,'add',true);">编辑</a> | <a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$item.agent_id}" url="{pigcms{:U('Agent/del')}">删除</a></td>
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