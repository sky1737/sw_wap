<include file="Public:header"/>
<form id="myform" method="post" action="{pigcms{:U('Config/menu_edit')}" enctype="multipart/form-data">
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<tr>
			<th width="80">所属分类</th>
			<td><select name="fid">
					<option value="0">顶级分类</option>
					<volist name="parents" id="item">
						<option value="{pigcms{$item.id}"<?php echo $menu['fid'] == $item['id'] ? 'selected': '';?>>{pigcms{$item.name}</option>
					</volist>
				</select></td>
		</tr>
		<!--SELECT `id`, `fid`, `name`, `module`, `action`, `sort`, `status` FROM `tp_system_menu` WHERE 1-->
		<tr>
			<th width="80">名称</th>
			<td><input type="text" class="input fl" name="name" id="name" size="25" value="{pigcms{$menu.name}" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
		</tr>
		<tr>
			<th width="80">模块</th>
			<td><input type="text" class="input fl" name="module" id="module" size="25" value="{pigcms{$menu.module}" placeholder="" validate="maxlength:20" tips=""/></td>
		</tr>
		<tr>
			<th width="80">功能</th>
			<td><input type="text" class="input fl" name="action" id="action" size="25" value="{pigcms{$menu.action}" placeholder="" validate="maxlength:20" tips=""/></td>
		</tr>
		<tr>
			<th width="80">排序</th>
			<td><input type="text" class="input fl" name="sort" value="{pigcms{$menu.sort}" size="10" placeholder="分类排序" validate="maxlength:6,number:true" tips="默认添加id排序！手动排序数值越小，排序越前。"/></td>
		</tr>
		<tr>
			<th width="80">状态</th>
			<td><span class="cb-enable"><label class="cb-enable <?php echo $menu['status'] ? 'selected' : '';?>"><span>启用</span><input type="radio" name="status" value="1" <?php echo $menu['status'] ? 'checked="true"' : '';?> /></label></span>
				<span class="cb-disable"><label class="cb-disable <?php echo $menu['status'] ? '' : 'selected';?>"><span>禁用</span><input type="radio" name="status" value="0" <?php echo $menu['status'] ? '' : 'checked="true"';?> /></label></span></td>
		</tr>
	</table>
	<div class="btn hidden">
		<input type="hidden" name="id" value="{pigcms{$menu.id}"/>
		<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button"/>
		<input type="reset" value="取消" class="button"/>
	</div>
</form>
<include file="Public:footer"/>