<include file="Public:header"/>
<form id="myform" method="post" action="{pigcms{:U('Adver/adver_amend')}" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{pigcms{$now_adver.id}"/>
    <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
        <tr>
            <th width="80">广告名称</th>
            <td><input type="text" class="input fl" name="name" value="{pigcms{$now_adver.name}" size="20"
                       placeholder="请输入名称" validate="maxlength:20,required:true"/></td>
        </tr>
        <tr>
            <th width="80">广告现图</th>
            <td><img src="{pigcms{$now_adver.pic}" style="width:260px;height:80px;" class="view_msg"/></td>
        </tr>
        <tr>
            <th width="80">广告图片</th>
            <td><input type="file" class="input fl" name="pic" style="width:200px;" placeholder="请上传图片"
                       tips="不修改请不上传！上传新图片，老图片会被自动删除！"/></td>
        </tr>
		<tr>
            <th width="80">二维码现图</th>
            <td><img src="{pigcms{$now_adver.qrcode}" style="width:260px;height:80px;" class="view_msg"/></td>
        </tr>
		<tr>
            <th width="80">二维码图片</th>
            <td><input type="file" class="input fl" name="qrcode" style="width:200px;" placeholder="请上传图片"
                       tips="不修改请不上传！上传新图片，老图片会被自动删除！"/></td>
        </tr>
        <tr>
            <th width="80">背景颜色</th>
            <td><input type="text" class="input fl" name="bg_color" value="{pigcms{$now_adver.bg_color}"
                       id="choose_color" style="width:120px;" placeholder="可不填写" readonly
                       tips="请点击右侧按钮选择颜色，用途为如果图片尺寸小于屏幕时，会被背景颜色扩充，主要为首页使用。"/>&nbsp;&nbsp;<a href="javascript:void(0);"
                                                                                           id="choose_color_box"
                                                                                           style="line-height:28px;">点击选择颜色</a>
            </td>
        </tr>
        <tr>
            <th width="80">链接地址</th>
            <td><input type="text" class="input fl" name="url" value="{pigcms{$now_adver.url}" style="width:200px;"
                       placeholder="请填写链接地址" validate="maxlength:200,required:true,url:true"/></td>
        </tr>
        <tr>
            <th width="80">广告状态</th>
            <td>
                <span class="cb-enable"><label class="cb-enable <if condition="$now_adver['status'] eq 1">selected</if>
                    "><span>启用</span><input type="radio" name="status" value="1" checked="checked" <if
                        condition="$now_adver['status'] eq 1">checked="checked"
                    </if>/></label></span>
                <span class="cb-disable"><label
                        class="cb-disable <if condition="$now_adver['status'] eq 0">selected</if>"><span>关闭</span><input
                        type="radio" name="status" value="0" <if condition="$now_adver['status'] eq 0">
                        checked="checked"
                    </if>/></label></span>
            </td>
        </tr>
    </table>
    <div class="btn hidden">
        <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button"/>
        <input type="reset" value="取消" class="button"/>
    </div>
</form>
<include file="Public:footer"/>