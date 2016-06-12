<include file="Public:header" />
<script type="text/javascript">
	function charge() {
		var income = $('#income').val();
		if (isNaN(income)) {
			alert('金额请填写数字！');
			return false;
		}
		var point = $('#point').val();
		if (isNaN(point)) {
			alert('积分请填写数字！');
			return false;
		}
		if (income == 0 && point == 0) {
			alert('充值金额和积分都不能为零！');
			return false;
		}

		$.post('<?php echo U('User/charge');?>', {
				'uid': <?php echo $now_user['uid']; ?>,
				'income': income,
				'point': point
			},
			function (result) {
				if (result.status == '1') {
					location.reload();
					return false;
				}
				alert(result.msg);
			});
	}
</script>
<form id="myform" method="post" action="{pigcms{:U('User/edit')}" frame="true" refresh="true">
	<input type="hidden" name="uid" value="{pigcms{$now_user.uid}" />
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<tr>
			<th width="15%">ID</th>
			<td width="35%">
				{pigcms{$now_user.uid}
			</td>
			<th width="35%">微信唯一标识</th>
			<td width="35%">
				{pigcms{$now_user.openid}
			</td>
		</tr>
		<tr>
			<th width="15%">昵称</th>
			<td width="35%"><input type="text" class="input fl" name="nickname" size="20"
			                       validate="maxlength:50,required:true" value="{pigcms{$now_user.nickname}" /></td>
			<th>头像</th>
			<td><?php echo '<a href="' . getAttachmentUrl($now_user['avatar']) . '" target="_blank"><img src="' .
					getAttachmentUrl($now_user['avatar']) . '" alt="" width="30" />' ?></a></td>
		</tr>
		<tr>
			<th>性别</th>
			<td><?php echo $now_user['sex'] ? '男' : '女'; ?></td>
			<th>地区</th>
			<td>{pigcms{$now_user.country} {pigcms{$now_user.province} {pigcms{$now_user.city}</td>
		</tr>
		<tr>
			<!--<th width="15%">密码</th>
			<td width="35%"><input type="password" class="input fl" name="pwd" size="20" value="" tips="不修改则不填写"/></td>-->
			<th width="15%">手机号</th>
			<td width="35%"><input type="text" class="input fl" name="phone" size="20" validate="mobile:true"
			                       value="{pigcms{$now_user.phone}" /></td>
			<th width="35%">状态</th>
			<td width="35%" class="radio_box"><?php
				echo '<span class="cb-enable"><label class="cb-enable' . ($now_user['status'] ? ' selected' : '') .
					'"><span>正常</span><input type="radio" name="status" value="1"' .
					($now_user['status'] ? ' checked="checked"' : '') .
					' /></label></span> <span class="cb-disable"><label class="cb-disable' .
					($now_user['status'] ? '' : ' selected') .
					'"><span>禁止</span><input type="radio" name="status" value="0"' .
					($now_user['status'] ? '' : ' checked="checked"') . ' /></label></span>';
				?></td>
		</tr>

		<!--tr>
            <th width="15%">手机号验证</th>
            <td width="35%"><if condition="$vo['is_check_phone'] eq 1"><font color="green">已验证</font><else/><font color="red">未验证</font></if></td>
            <th width="15%">关注微信号</th>
            <td width="35%"><if condition="$vo['is_follow'] eq 1"><font color="green">已关注</font><else/><font color="red">未关注</font></if></td>
        </tr-->
		<tr>
			<th>帐户</th>
			<td colspan="3">余额: ￥{pigcms{$now_user['balance']}, 积分： {pigcms{$now_user['point']} （变更：金额
				<input type="text" id="income" value="0.00" size="5" />, 积分
				<input type="text" id="point" value="0" size="5" />
				<input type="button" onclick="charge()" value="充值" />）
			</td>
		</tr>
		<tr>
			<th width="15%">注册时间</th>
			<td width="35%">
				{pigcms{$now_user.reg_time|date='Y-m-d H:i:s',###}
			</td>
			<th width="35%">注册IP</th>
			<td width="35%">
				{pigcms{$now_user.reg_ip|long2ip=###}
			</td>
		</tr>
		<tr>
			<th width="15%">最后访问时间</th>
			<td width="35%">
				{pigcms{$now_user.last_time|date='Y-m-d H:i:s',###}
			</td>
			<th width="35%">最后访问IP</th>
			<td width="35%">
				{pigcms{$now_user.last_ip|long2ip=###}
			</td>
		</tr>
		<tr>
			<th width="15%">个性签名：</th>
			<td width="35%" colspan="3">
				<textarea style="width: 99%" name="intro">{pigcms{$now_user.intro}</textarea></td>
		</tr>
		<tr>
			<th width="15%">上线会员ID：</th>
			<td width="35%" colspan="3">
				<input type="text" class="input fl" name="parent_uid" size="20" validate="number:true"
				       value="{pigcms{$now_user.parent_uid}" /></td>
		</tr>
	</table>
	<div class="btn hidden">
		<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
		<input type="reset" value="取消" class="button" />
	
</form>
<include file="Public:footer" />