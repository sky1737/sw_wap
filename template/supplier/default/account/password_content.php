<div class="ui-nav dianpu">
    <ul>
        <li class="js-app-nav active info"> <a href="#info">修改密码</a> </li>
    </ul>
</div>
<form class="form-horizontal ui-box" id="address" method="post">
    <div class="control-group">
        <label class="control-label"> <em class="required">*</em>当前密码：</label>
        <div class="controls">
            <input type="password" name="current" id="current" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"> <em class="required">*</em>新密码：</label>
        <div class="controls">
            <input type="password" id="password" name="password" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"> <em class="required">*</em>确认密码：</label>
        <div class="controls">
            <input type="password" name="confirm" id="confirm" />
        </div>
    </div>
    <div class="form-actions">
        <button class="ui-btn ui-btn-primary js-btn-submit" type="button" data-text-loading="保存中...">立即修改</button>
    </div>
</form>
<?php
/*<div>
    <form class="form-horizontal">
        <fieldset class="center-area">
            <div class="control-group">
                <label class="control-label">请输入旧密码：</label>
                <div class="controls">
                    <input type="password" name="old_password">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">请输入新密码：</label>
                <div class="controls">
                    <input type="password" name="new_password">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">重复新密码：</label>
                <div class="controls">
                    <input type="password" name="renew_password">
                </div>
            </div>
            <div class="control-group control-action">
                <div class="controls">
                    <button class="btn btn-large btn-primary js-btn-submit" type="button" data-loading-text="正在提交...">确定修改</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="<?php dourl('store:select'); ?>">取消</a>
                </div>
            </div>
        </fieldset>
    </form>
</div>*/?>