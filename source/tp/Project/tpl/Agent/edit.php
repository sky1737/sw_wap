<include file="Public:header"/>
<form id="myform" method="post" action="{pigcms{:U('Agent/edit')}" frame="true" refresh="true">
	<input type="hidden" name="agent_id" value="{pigcms{$item.agent_id}" />
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<tr>
			<th width="80">名称</th>
			<td><input type="text" class="input fl" name="name" size="20" placeholder="请输入代理名称" value="{pigcms{$item.name}" validate="required:true" /></td>
		</tr>


		<tr>
			<th width="80">销售价格</th>
			<td><input type="text" class="input fl" name="price" size="20" placeholder="请输入代理名称" value="{pigcms{$item.price}" validate="required:true" /></td>
		</tr>
		<tr>
			<th width="80">分佣金额</th>
			<td><input type="text" class="input fl" name="commission" size="20" placeholder="请输入代理名称" value="{pigcms{$item.commission}" validate="required:true" /></td>
		</tr>
		<tr>
			<th width="80">赠送消费</th>
			<td><input type="text" class="input fl" name="consumer" size="20" placeholder="请输入代理名称" value="{pigcms{$item.consumer}" validate="required:true" /></td>
		</tr>

		<tr>
			<th width="80">供应商</th>
			<td class="radio_box"><span class="cb-enable">
				<label class="cb-enable<?php echo $item['open_self'] ? ' selected' : ''; ?>"><span>启用</span>
					<input type="radio" name="open_self" value="1"<?php echo $item['open_self'] ? ' checked="checked"' : ''; ?>/>
				</label>
				</span> <span class="cb-disable">
				<label class="cb-disable<?php echo $item['open_self'] ? '' : ' selected'; ?>"><span>关闭</span>
					<input type="radio" name="open_self" value="0"<?php echo $item['open_self'] ? '' : ' checked="checked"'; ?>/>
				</label>
				</span></td>
		</tr>
		<tr>
			<th width="80">代理</th>
			<td class="radio_box"><span class="cb-enable">
				<label class="cb-enable<?php echo $item['is_agent'] ? ' selected' : ''; ?>"><span>启用</span>
					<input type="radio" name="is_agent" value="1"<?php echo $item['is_agent'] ? ' checked="checked"' : ''; ?>/>
				</label>
				</span> <span class="cb-disable">
				<label class="cb-disable<?php echo $item['is_agent'] ? '' : ' selected'; ?>"><span>关闭</span>
					<input type="radio" name="is_agent" value="0"<?php echo $item['is_agent'] ? '' : ' checked="checked"'; ?>/>
				</label>
				</span></td>
		</tr>
		<tr>
			<th width="80">產品編輯</th>
			<td class="radio_box"><span class="cb-enable">
				<label class="cb-enable<?php echo $item['is_editor'] ? ' selected' : ''; ?>"><span>启用</span>
					<input type="radio" name="is_editor" value="1"<?php echo $item['is_editor'] ? ' checked="checked"' : ''; ?>/>
				</label>
				</span> <span class="cb-disable">
				<label class="cb-disable<?php echo $item['is_editor'] ? '' : ' selected'; ?>"><span>关闭</span>
					<input type="radio" name="is_editor" value="0"<?php echo $item['is_editor'] ? '' : ' checked="checked"'; ?>/>
				</label>
				</span></td>
		</tr>
		<!--<tr>
			<th width="80">商品数量</th>
			<td><input type="text" class="input fl" name="max_products" size="10" placeholder="请输入商品数量" value="{pigcms{$item.max_products}" validate="required:true,number:true" /></td>
		</tr>-->
		<tr>
			<th width="80">备注说明</th>
			<td><input type="text" class="input fl" name="remarks" size="30" placeholder="请输入备注说明" value="{pigcms{$item.remarks}" validate="required:true" /></td>
		</tr>
		<tr>
			<th width="80">排序</th>
			<td><input type="text" class="input fl" name="sort" size="10" placeholder="请输入排序" value="{pigcms{$item.sort}" validate="required:true,number:true,maxlength:6" /></td>
		</tr>
		<tr>
			<th width="80">状态</th>
			<td class="radio_box"><span class="cb-enable">
				<label class="cb-enable<?php echo $item['status'] ? ' selected' : ''; ?>"><span>启用</span>
					<input type="radio" name="status" value="1"<?php echo $item['status'] ? ' checked="checked"' : ''; ?>/>
				</label>
				</span> <span class="cb-disable">
				<label class="cb-disable<?php echo $item['status'] ? '' : ' selected'; ?>"><span>关闭</span>
					<input type="radio" name="status" value="0"<?php echo $item['status'] ? '' : ' checked="checked"'; ?>/>
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