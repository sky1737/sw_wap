<include file="Public:header"/>
<style type="text/css">
a { color: blue; }
a,
a:hover { text-decoration: none; }
.platform-tag { display: inline-block; vertical-align: middle; padding: 3px 7px 3px 7px; background-color: #f60; color: #fff; font-size: 12px; line-height: 14px; border-radius: 2px; }
</style>
<script type="text/javascript">
    $(function () {
        //是否启用
        $('.status-enable > .cb-enable').click(function () {
            if (!$(this).hasClass('selected')) {
                var help_id = $(this).data('id');
                $.post("<?php echo U('Help/status'); ?>", {'status': 1, 'id': help_id}, function (data) {
                })
            }
        })
        $('.status-disable > .cb-disable').click(function () {
            if (!$(this).hasClass('selected')) {
                var help_id = $(this).data('id');
                if (!$(this).hasClass('selected')) {
                    $.post("<?php echo U('Help/status'); ?>", {'status': 0, 'id': help_id}, function (data) {
                    })
                }
            }
        })
        //是否热门
        $('.status-enable-hot > .cb-enable').click(function () {
            if (!$(this).hasClass('selected')) {
                var help_id = $(this).data('id');
                $.post("<?php echo U('Help/ishot'); ?>", {'is_hot': 1, 'id': help_id}, function (data) {
                })
            }
        })
        $('.status-disable-hot > .cb-disable').click(function () {
            if (!$(this).hasClass('selected')) {
                var help_id = $(this).data('id');
                if (!$(this).hasClass('selected')) {
                    $.post("<?php echo U('Help/ishot'); ?>", {'is_hot': 0, 'id': help_id}, function (data) {
                    })
                }
            }
        })
    })
</script>
<div class="mainbox">
	<div id="nav" class="mainnav_title">
		<ul>
			<li><a href="{pigcms{:U('Help/index')}" class="on">帮助列表</a> | <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Help/add')}','添加分类',720,480,true,false,false,addbtn,'add',true);">添加帮助</a></li>
		</ul>
	</div>
	<table class="search_table" width="100%">
		<tr>
			<td><form action="{pigcms{:U('Help/index')}" method="get">
					<input type="hidden" name="c" value="Help"/>
					<input type="hidden" name="a" value="index"/>
					筛选:
					<input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
					&nbsp;&nbsp;分类：
					<select name="category">
						<option value="0">帮助分类</option>
						<volist name="categories" id="category">
							<option value="{pigcms{$category.cat_id}">
							<?php if ($category['cat_level'] > 1) echo str_repeat('&nbsp;&nbsp;', $category['cat_level']); ?>|-- {pigcms{$category.cat_name} </option>
						</volist>
					</select>
					<input type="submit" value="查询" class="button"/>
				</form></td>
		</tr>
	</table>
	<form name="myform" id="myform" action="" method="post">
		<div class="table-list">
			<style>
                .table-list td {
                    line-height: 22px;
                    padding-top: 5px;
                    padding-bottom: 5px;
                }
            </style>
			<table width="100%" cellspacing="0">
				<thead>
					<tr>
						<th width="30">编号</th>
						<th width="100">分类</th>
						<th>标题</th>
						<th width="30">排序</th>
						<th class="textcenter" width="120">添加时间</th>
						<th width="120">状态</th>
						<th class="textcenter" width="100">操作</th>
					</tr>
				</thead>
				<tbody>
					<if condition="is_array($helps)">
					<volist name="helps" id="help">
						<tr>
							<td>{pigcms{$help.help_id}</td>
							<td>{pigcms{$help.category}</td>
							<td>{pigcms{$help.title}</td>
							<td>{pigcms{$help.sort}</td>
							<td class="textcenter">{pigcms{$help.date_added|date='Y-m-d H:i:s', ###}</td>
							<td><span class="cb-enable status-enable">
								<label class="cb-enable <if condition="$help['status'] eq 1">selected</if>" data-id="<?php echo $help['help_id']; ?>"><span>启用</span><input type="radio" name="status" value="1" <if condition="$help['help_id'] eq 1">checked="checked"</if> /></label>
								</span> <span class="cb-disable status-disable">
								<label class="cb-disable <if condition="$help['status'] eq 0">selected</if>" data-id="<?php echo $help['help_id']; ?>"><span>禁用</span><input type="radio" name="status" value="0" <if condition="$help['help_id'] eq 0">checked="checked" </if> /></label>
								</span></td>
							<td><a href="javascript:void(0);" url="<?php echo U('Help/del', array('id' => $help['help_id'])); ?>" class="delete_row">删除</a> |
							<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Help/edit', array('id' => $help['help_id']))}','修改帮助 - {pigcms{$help.title}',720,480,true,false,false,editbtn,'edit',true);">修改</a></td>
						</tr>
					</volist>
					<tr>
						<td class="textcenter pagebar" colspan="10">{pigcms{$page}</td>
					</tr>
					<else/>
					<tr>
						<td class="textcenter red" colspan="10">列表为空！</td>
					</tr>
						</if>
					
				</tbody>
			</table>
		</div>
	</form>
</div>
<include file="Public:footer"/>