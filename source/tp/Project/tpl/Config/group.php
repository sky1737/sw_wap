<include file="Public:header"/>
<div class="mainbox">
	<div id="nav" class="mainnav_title">
		<ul>
			<a href="{pigcms{:U('Config/group')}" class="on">配置分组列表</a>| <a href="javascript:void(0);"
               onclick="window.top.artiframe('{pigcms{:U('Config/group_edit')}','添加配置分组',400,180,true,false,false,addbtn,'add',true);">添加配置分组</a>
		</ul>
	</div>
	<form name="myform" id="myform" action="" method="post">
		<div class="table-list">
			<table width="100%" cellspacing="0">
				<colgroup>
				<col>
				<col>
				<col>
				<col>
				<col width="180" align="center">
				</colgroup>
				<thead>
					<tr>
						<th>编号</th>
						<th>名称</th>
						<th>标识</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<if condition="is_array($list)"> 
						<!--SELECT `id`, `name`, `sort`, `status` FROM `tp_config_group` WHERE 1-->
						<volist name="list" id="vo">
							<tr>
								<td>{pigcms{$vo.id}</td>
								<td><a href="{pigcms{:U('Config/config',array('group'=>$vo['id']))}">{pigcms{$vo.name}</a></td>
								<td>{pigcms{$vo.sort}</td>
								<td><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Config/group_edit',array('id'=>$vo['id']))}','编辑配置分组',400,180,true,false,false,editbtn,'add',true);">编辑</a> | <a href="javascript:void(0);" class="delete_row" url="{pigcms{:U('Config/group_del',array('id'=>$vo['id']))}">删除</a></td>
							</tr>
						</volist>
						<else/>
						<tr>
							<td class="textcenter red" colspan="8">列表为空！</td>
						</tr>
					</if>
				</tbody>
			</table>
		</div>
	</form>
</div>
<include file="Public:footer"/>