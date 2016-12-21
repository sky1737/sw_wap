<include file="Public:header"/>
<form id="myform" method="post" action="{pigcms{:U('Recharge/edit')}" frame="true" refresh="true">
	<input type="hidden" name="recharge_id" value="{pigcms{$item.recharge_id}" />
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<tr>
			<th width="80">金额</th>
			<td><input type="text" class="input fl" name="amount" size="20" placeholder="请输入金额" value="{pigcms{$item.amount}" validate="required:true,number:true" /></td>
		</tr>
		<tr>
			<th width="80">赠送积分</th>
			<td><input type="text" class="input fl" name="point" size="20" placeholder="请输入积分" value="{pigcms{$item.point}" validate="required:true,number:true" /></td>
		</tr>
		<tr>
			<th width="80">分销金额</th>
			<td><input type="text" class="input fl" name="profit" size="20" placeholder="请输入分销金额" value="{pigcms{$item.profit}" validate="required:true,number:true" /></td>
		</tr>
		<tr>
			<th width="80">开始时间</th>
			<td><input type="text" class="input fl" name="start_time" readonly="readonly" value="{pigcms{$item.start_time|date='Y-m-d 00:00:00',###}" onClick="WdatePicker({dateFmt:'yyyy-MM-dd 00:00:00'})" size="30" placeholder="开始时间" validate="required:true"/></td>
		</tr>
		<tr>
			<th width="80">结束时间</th>
			<td><input type="text" class="input fl" name="end_time" readonly="readonly" value="{pigcms{$item.end_time|date='Y-m-d 23:59:59',###}" onClick="WdatePicker({dateFmt:'yyyy-MM-dd 23:59:59'})" size="30" placeholder="结束时间" validate="required:true"/></td>
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