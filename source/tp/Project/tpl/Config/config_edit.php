<include file="Public:header"/>
<form id="myform" method="post" action="{pigcms{:U('Config/config_edit')}" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{pigcms{$cfg.id}"/>
    <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<tr>
			<th>配置分组</th>
			<td><select name="group_id">
			<volist name="groups" id="g">
			<option value="{pigcms{$g['id']}"<?php echo $g['id'] == $cfg['group_id'] ? ' selected' : ''?>>{pigcms{$g['name']}</option>
			</volist>
			</select></td>
		</tr>
        <tr>
            <th width="80">配置ID</th>
            <td><input type="text" class="input fl" name="name" value="{pigcms{$cfg.name}" size="20" placeholder="请输入名称" validate="maxlength:20,required:true" /></td>
        </tr>
        <tr>
            <th width="80">配置名称</th>
            <td><input type="text" class="input fl" name="info" value="{pigcms{$cfg.info}" style="width:200px;" placeholder="" validate="maxlength:200,required:true"/></td>
        </tr>
        <tr>
            <th width="80">输入类型</th>
            <td><input type="text" class="input fl" name="type" value="{pigcms{$cfg.type}" style="width:200px;" placeholder="" validate="maxlength:200,required:true"/></td>
        </tr>
        <tr>
            <th width="80">配置值</th>
            <td><input type="text" class="input fl" name="value" value="{pigcms{$cfg.value}" style="width:200px;" placeholder="" validate="maxlength:200,required:true"/></td>
        </tr>
        <tr>
            <th width="80">配置介绍</th>
            <td><input type="text" class="input fl" name="desc" value="{pigcms{$cfg.desc}" style="width:200px;" placeholder="" validate="maxlength:200"/></td>
        </tr>
		<!--SELECT `id`, `name`, `type`, `value`, `info`, `desc`, `tab_id`, `tab_name`, `group_id`, `sort`, `status` FROM `tp_config` WHERE 1-->
        <tr>
            <th width="80">标签ID</th>
            <td><input type="text" class="input fl" name="tab_id" value="{pigcms{$cfg.tab_id}" style="width:200px;" placeholder="" validate="maxlength:200"/></td>
        </tr>
        <tr>
            <th width="80">标签名称</th>
            <td><input type="text" class="input fl" name="tab_name" value="{pigcms{$cfg.tab_name}" style="width:200px;" placeholder="" validate="maxlength:200"/></td>
        </tr>
		<tr>
			<th width="80">排序</th>
			<td><input type="text" class="input fl" name="sort" value="{pigcms{$cfg.sort}" size="10" placeholder="分类排序" validate="maxlength:6,number:true" tips="默认添加id排序！手动排序数值越小，排序越前。"/></td>
		</tr>
        <tr>
			<th width="80">状态</th>
			<td><span class="cb-enable"><label class="cb-enable <?php echo $cfg['status'] ? 'selected' : '';?>"><span>启用</span><input type="radio" name="status" value="1" <?php echo $cfg['status'] ? 'checked="true"' : '';?> /></label></span>
				<span class="cb-disable"><label class="cb-disable <?php echo $cfg['status'] ? '' : 'selected';?>"><span>禁用</span><input type="radio" name="status" value="0" <?php echo $cfg['status'] ? '' : 'checked="true"';?> /></label></span></td>
		</tr>
    </table>
    <div class="btn hidden">
        <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button"/>
        <input type="reset" value="取消" class="button"/>
    </div>
</form>
<include file="Public:footer"/>