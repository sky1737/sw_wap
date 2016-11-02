<include file="Public:header"/>
<?php
//print_r($group);
?>
<div class="mainbox">
	<div id="nav" class="mainnav_title">
		<ul>
			<a href="{pigcms{:U('Config/group')}">配置分组列表</a> | <a href="{pigcms{:U('Config/config',array('group'=>$group['id']))}" class="on">《{pigcms{$group.name}》配置列表</a> - <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Config/config_edit',array('group'=>$group['id']))}','添加配置',480,250,true,false,false,addbtn,'add',true);">添加配置</a>
		</ul>
	</div>
	<form name="myform" id="myform" action="" method="post">
		<div class="table-list">
			<table width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>编号</th>
						<th>名称</th>
						<th>类型</th>
						<th>值</th>
						<th>标签ID</th>
						<th>标签名</th>
						<th>排序</th>
						<th>状态</th>
						<th class="textcenter">操作</th>
					</tr>
				</thead>
				<tbody>
					<if condition="is_array($list)">
						<!--SELECT `id`, `name`, `type`, `value`, `info`, `desc`, `tab_id`, `tab_name`, `sort`, `status` FROM `tp_config` WHERE 1-->
						<volist name="list" id="vo">
							<tr>
								<td>{pigcms{$vo.id}</td>
								<td title="{pigcms{$vo.desc}">{pigcms{$vo.info}({pigcms{$vo.name})</td>
								<td>{pigcms{$vo.type}</td>
								<td>{pigcms{$vo.value|mb_substr=###,0,100,'utf-8'}</td>
								<td>{pigcms{$vo.tab_id}</td>
								<td>{pigcms{$vo.tab_name}</td>
								<td>{pigcms{$vo.sort}</td>
								<td><?php echo $vo['status'] ? '启用' : '禁用'; ?></td>
								<td><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Config/config_edit',array('id'=>$vo['id']))}','编辑配置信息',480,330,true,false,false,editbtn,'add',true);">编辑</a> | <a href="javascript:void(0);" class="delete_row" url="{pigcms{:U('Config/config_del',array('id'=>$vo['id']))}">删除</a></td>
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