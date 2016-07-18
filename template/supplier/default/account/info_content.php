<div class="ui-nav dianpu">
	<ul>
		<li class="js-app-nav active info"> <a href="#info">个人资料</a> </li>
	</ul>
</div>
<form class="form-horizontal">
	<fieldset>
		<div class="control-group">
			<label class="control-label">手机：</label>
			<div class="controls">
				<input class="js-mobile" type="text" name="phone" data="<?php echo $user_session['phone']; ?>" value="<?php echo $user_session['phone']; ?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">昵称：</label>
			<div class="controls">
				<input class="js-name" type="text" name="nickname" data="<?php echo $user_session['nickname']; ?>" value="<?php echo $user_session['nickname']; ?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">头像：</label>
			<div class="controls">
				<span class="avatar">
					<img class="avatar-img" src="<?php
					if (!empty($user_session['avatar'])) {
						echo getAttachmentUrl($user_session['avatar']);
					}
					else {
						echo TPL_URL.'/images/avatar.png';
					} ?>" style="width: 40px;;" />
				</span> <a href="javascript:;" class="upload-img js-add-picture">修改</a>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">个人签名：</label>
			<div class="controls">
				<textarea name="intro" class="js-intro" cols="30" rows="3" maxlength="100"><?php echo $user_session['intro']; ?></textarea>
			</div>
		</div>
	</fieldset>
	<div class="form-actions">
		<button class="ui-btn ui-btn-primary js-btn-submit" type="button" style="height:30px;line-height:27px;">保存</button>
	</div>
</form>
