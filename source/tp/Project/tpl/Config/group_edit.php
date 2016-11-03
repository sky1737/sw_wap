<include file="Public:header"/>
<form id="myform" method="post" action="{pigcms{:U('Config/group_edit')}" frame="true" refresh="true">
    <input type="hidden" name="id" value="{pigcms{$group.id}"/>
    <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
        <tr>
            <th width="80">分类名称</th>
            <td><input type="text" class="input fl" name="name" value="{pigcms{$group.name}" size="10"
                       placeholder="请输入名称" validate="maxlength:20,required:true"/></td>
        </tr>
		<!--SELECT `id`, `name`, `sort`, `status` FROM `tp_config_group` WHERE 1-->
        <tr>
			<th width="80">排序</th>
			<td><input type="text" class="input fl" name="sort" value="{pigcms{$group.sort}" size="10" placeholder="分类排序" validate="maxlength:6,number:true" tips="默认添加id排序！手动排序数值越小，排序越前。"/></td>
		</tr>
		<tr>
			<th width="80">状态</th>
			<td><span class="cb-enable"><label class="cb-enable <?php echo $group['status'] ? 'selected' : '';?>"><span>启用</span><input type="radio" name="status" value="1" <?php echo $group['status'] ? 'checked="true"' : '';?> /></label></span>
				<span class="cb-disable"><label class="cb-disable <?php echo $group['status'] ? '' : 'selected';?>"><span>禁用</span><input type="radio" name="status" value="0" <?php echo $group['status'] ? '' : 'checked="true"';?> /></label></span></td>
		</tr>
    </table>
    <div class="btn hidden">
        <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button"/>
        <input type="reset" value="取消" class="button"/>
    </div>
</form>
<include file="Public:footer"/>