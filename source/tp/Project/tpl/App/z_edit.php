<include file="Public:header"/>
<script charset="utf-8" src="{pigcms{$static_public}/kindeditor/kindeditor.js"></script>
<script charset="utf-8" src="{pigcms{$static_public}/kindeditor/lang/zh_CN.js"></script>
<script charset="utf-8" src="{pigcms{$static_public}/kindeditor/plugins/code/prettify.js"></script>
<script>
    var editor;
    KindEditor.ready(function (K) {
        editor = K.create('textarea[name="details"]', {
            // cssPath : '../plugins/code/prettify.css',
            // uploadJson : '../php/upload_json.php',
            // fileManagerJson : '../php/file_manager_json.php',
            allowFileManager: true,
            afterCreate: function () {
                var self = this;
                K.ctrl(document, 13, function () {
                    self.sync();
                    K('form[name=myform]')[0].submit();
                });
                K.ctrl(self.edit.doc, 13, function () {
                    self.sync();
                    K('form[name=myform]')[0].submit();
                });
            }
        });
        prettyPrint();
    });
</script>
<form id="myform" method="post" action="{pigcms{:U('App/z_edit')}" frame="true" refresh="true"
      enctype="multipart/form-data">
    <input type="hidden" name="zid" value="{pigcms{$item.zid}"/>
    <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
        <tr>
            <th width="80">名称</th>
            <td colspan="5"><input type="text" class="input fl" name="title" size="20" placeholder="请输入代理名称"
                                   value="{pigcms{$item.title}" validate="required:true"/></td>
        </tr>
        <tr>
            <th width="80">封面</th>
            <td colspan="5"><input type="file" class="input fl" name="pic" style="width:200px;" placeholder="请上传图片"
                                   tips="不修改请不上传！上传新图片，老图片会被自动删除！"/></td>
        </tr>
        <?php if ($item['image']) { ?>
            <tr>
                <th width="80"><input type="hidden" name="image" value="{pigcms{$item.image}"/></th>
                <td colspan="5"><img src="/upload/{pigcms{$item.image}" style="height:80px;" class="view_msg"/></td>
            </tr>
        <?php } ?>
        <tr>
            <th width="80">目标金额(元)</th>
            <td><input type="text" class="input fl" name="target" size="20" placeholder="请输入目标金额"
                       value="{pigcms{$item.target}" validate="required:true"/></td>
            <th width="80">已筹金额(元)
            </td>
            <td><input type="text" class="input fl" name="achieved" size="20" placeholder="请输入已筹金额"
                       value="{pigcms{$item.achieved}" validate="required:true"/></td>
            <th width="80">封闭时长(天)
            </td>
            <td><input type="text" class="input fl" name="days" size="20" placeholder="请输入封闭时长"
                       value="{pigcms{$item.days}" validate="required:true"/></td>
        </tr>
        <tr>
            <th width="80">收益利率(%)</th>
            <td><input type="text" class="input fl" name="profit_rate" size="20" placeholder="请输入收益率"
                       value="{pigcms{$item.profit_rate}" validate="required:true"/></td>
            <th>赠送比率(%)</th>
            <td><input type="text" class="input fl" name="gift_rate" size="20" placeholder="请输入赠送率"
                       value="{pigcms{$item.gift_rate}" validate="required:true"/></td>
            <th>分佣比率(%)</th>
            <td><input type="text" class="input fl" name="commission_rate" size="20" placeholder="请输入分佣率"
                       value="{pigcms{$item.commission_rate}" validate="required:true"/></td>
        </tr>
        <!--SELECT  `id`,  `image`,  `title`,  `target`,  `achieved`,  `profit_rate`,  `gift_rate`,  `commission_rate`,  `days`,  `status`,  LEFT(`details`, 256),  `add_time` FROM `yunws`.`tp_app_z` LIMIT 1000;-->
        <tr>
            <th width="80">详情介绍</th>
            <td colspan="5"><textarea name="details" style="height:300px; width:96%" validate="required:true">{pigcms{$item.details}</textarea>
            </td>
        </tr>
        <tr>
            <th>支持选项</th>
            <td colspan="5">
                <table width="96%">
                    <tr>
                        <th>代理级别</th>
                        <th>支持数</th>
                        <th>最小额度</th>
                        <th>最大额度</th>
                        <th>增加基数</th>
                        <th>回报说明</th>
                    </tr>
                    <!--SELECT  `id`,  `zid`,  `agent_id`,  `minimum`,  `amount`,  `maximum`,  `buys`,  LEFT(`details`, 256) FROM `yunws`.`tp_app_z_item` LIMIT 1000;-->
                    <?php
                    foreach ($agents as $a) {
                        $c = array('item_id' => 0, 'buys' => 0, 'agent_id' => $a['agent_id'], 'minimum' => 0, 'maximum' => 0, 'amount' => 0, 'note' => '');
                        foreach ($item['items'] as $b) {
                            if ($a['agent_id'] == $b['agent_id']) {
                                $c = $b;
                                break;
                            }
                        } ?>
                        <input type="hidden" name="item_id[]" value="{pigcms{$c.item_id}"/>
                        <input type="hidden" name="agent_id[]" value="{pigcms{$a.agent_id}"/>
                        <tr>
                            <td>{pigcms{$a.name}</td>
                            <td>{pigcms{$c.buys}</td>
                            <td><input type="text" class="input fl" size="10" name="minimum[]" value="{pigcms{$c.minimum}"/></td>
                            <td><input type="text" class="input fl" size="10" name="maximum[]" value="{pigcms{$c.maximum}"/></td>
                            <td><input type="text" class="input fl" size="10" name="amount[]" value="{pigcms{$c.amount}"/></td>
                            <td><textarea class="input fl" name="note[]">{pigcms{$c.note}</textarea></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </td>
        </tr>
        <tr>
            <th width="80">排序</th>
            <td colspan="5"><input type="text" class="input fl" name="sort" size="10" placeholder="请输入排序"
                                   value="{pigcms{$item.sort}" validate="required:true,number:true,maxlength:6"/></td>
        </tr>
        <tr>
            <th width="80">状态</th>
            <td colspan="5" class="radio_box"><span class="cb-enable">
				<label class="cb-enable<?php echo $item['status'] ? ' selected' : ''; ?>"><span>启用</span>
                    <input type="radio" name="status"
                           value="1"<?php echo $item['status'] ? ' checked="checked"' : ''; ?>/>
                </label>
				</span> <span class="cb-disable">
				<label class="cb-disable<?php echo $item['status'] ? '' : ' selected'; ?>"><span>关闭</span>
                    <input type="radio" name="status"
                           value="0"<?php echo $item['status'] ? '' : ' checked="checked"'; ?>/>
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