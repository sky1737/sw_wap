<include file="Public:header"/>
<!-- kindeditor -->
<link rel="stylesheet" href="{pigcms{$static_public}/kindeditor/themes/default/default.css" />
<link rel="stylesheet" href="{pigcms{$static_public}/kindeditor/plugins/code/prettify.css" />

<script charset="utf-8" src="{pigcms{$static_public}/kindeditor/kindeditor.js"></script>
<script charset="utf-8" src="{pigcms{$static_public}/kindeditor/lang/zh_CN.js"></script>
<script charset="utf-8" src="{pigcms{$static_public}/kindeditor/plugins/code/prettify.js"></script>
<script>
var editor;
KindEditor.ready(function(K) {
	 editor = K.create('textarea[name="content"]', {
		// cssPath : '../plugins/code/prettify.css',
		// uploadJson : '../php/upload_json.php',
		// fileManagerJson : '../php/file_manager_json.php',
		allowFileManager : true,
		afterCreate : function() {
			var self = this;
			K.ctrl(document, 13, function() {
				self.sync();
				K('form[name=example]')[0].submit();
			});
			K.ctrl(self.edit.doc, 13, function() {
				self.sync();
				K('form[name=example]')[0].submit();
			});
		}
	});
	prettyPrint();
});
</script>
<form id="myform" method="post" action="{pigcms{:U('Help/add')}" onSubmit="editor.sync()">
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<tr>
			<th width="80">所属分类</th>
			<td><select name="cat_id" validate="required:true">
					<option value="0">帮助分类</option>
					<volist name="categories" id="category">
						<option value="{pigcms{$category.cat_id}">
						<?php if ($category['cat_level'] > 1) echo str_repeat('&nbsp;&nbsp;', $category['cat_level']); ?>
						|-- {pigcms{$category.cat_name} </option>
					</volist>
				</select></td>
		</tr>
		<tr>
			<th>文章标题：</th>
			<td><input type="text" class="input-text" name="title" style="width:80%;" validate="required:true"/></td>
		</tr>
		<tr>
			<th>文章内容</th>
			<td><textarea name="content" style="height:300px; width:96%" validate="required:true"></textarea></td>
		</tr>
		<tr>
			<th>SEO标题：</th>
			<td><input type="text" name="seo_title" class="input-text" style="width:80%;" /></td>
		</tr>
		<tr>
			<th>SEO关键词：</th>
			<td><input type="text" name="seo_key" class="input-text" style="width:80%;" /></td>
		</tr>
		<tr>
			<th>SEO介绍：</th>
			<td><textarea type="text" name="seo_des" class="input-text" style="height:60px;width:80%;"></textarea></td>
		</tr>
		<tr>
			<th width="80">分类排序</th>
			<td><input type="text" class="input fl" name="sort" value="0" size="10" placeholder="分类排序"
                   validate="maxlength:6,number:true" tips="默认添加id排序！手动排序数值越小，排序越前。"/></td>
		</tr>
		<tr>
			<th width="80">分类状态</th>
			<td><span class="cb-enable">
				<label class="cb-enable selected"><span>启用</span>
					<input type="radio" name="status" value="1" checked="checked"/>
				</label>
				</span>
				<span class="cb-disable">
				<label class="cb-disable"><span>禁用</span>
					<input type="radio" name="status" value="0"/>
				</label>
				</span></td>
		</tr>
	</table>
	<div class="btn hidden">
		<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button"/>
		<input type="reset" value="取消" class="button"/>
	</div>
</form>
<include file="Public:footer"/>