<include file="Public:header"/>
<style type="text/css">
.c-gray { color: #999; }
.table-list tfoot tr { height: 40px; }
.green { color: green; }
a,
a:hover { text-decoration: none; }
</style>
<script type="text/javascript">
    $(function () {
        $('.status-enable > .cb-enable').click(function () {
            if (!$(this).hasClass('selected') && parseFloat($(this).data('status')) == 1) {
                var url = window.location.href;
                var cat_id = $(this).data('id');
                $.post("<?php echo U('Help/category_status'); ?>", {'status': 1, 'cat_id': cat_id}, function (data) {
                    window.location.href = url;
                })
            }
            if (parseFloat($(this).data('status')) == 0) {
                $(this).removeClass('selected');
            }
            return false;
        })
        $('.status-disable > .cb-disable').click(function () {
            if (!$(this).hasClass('selected') && parseFloat($(this).data('status')) == 1) {
                var url = window.location.href;
                var cat_id = $(this).data('id');
                if (!$(this).hasClass('selected')) {
                    $.post("<?php echo U('Help/category_status'); ?>", {
                        'status': 0,
                        'cat_id': cat_id
                    }, function (data) {
                        window.location.href = url;
                    })
                }
            }
            return false;
        })
    })
</script>
<div class="mainbox">
	<div id="nav" class="mainnav_title">
		<ul>
			<li><a href="{pigcms{:U('Help/category')}" class="on">帮助分类</a> | <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Help/category_add')}','添加帮助',480,310,true,false,false,addbtn,'add',true);">添加帮助</a></li>
		</ul>
	</div>
	<table class="search_table" width="100%">
		<tr>
			<td><form action="{pigcms{:U('Help/category')}" method="get">
					<input type="hidden" name="c" value="Help"/>
					<input type="hidden" name="a" value="category"/>
					筛选:
					<select name="cat_id">
						<option value="0">帮助类目</option>
						<volist name="all_categories" id="all_category">
						<option value="{pigcms{$all_category['cat_id']}"<if condition="$Think.get.cat_id eq $all_category['cat_id']"> selected</if>><?php if ($all_category['cat_level'] > 1) {echo str_repeat('&nbsp;&nbsp;', $all_category['cat_level']);} ?> |-- {pigcms{$all_category.cat_name}</option>
						</volist>
					</select>
					<input type="submit" value="查询" class="button"/>
				</form></td>
		</tr>
	</table>
	<div class="table-list">
		<table width="100%" cellspacing="0">
			<thead>
				<tr>
					<th>删除 | 修改</th>
					<th>编号</th>
					<th>名称</th>
					<th>描述</th>
					<th>状态</th>
					<th>排序</th>
					<th class="textcenter" width="100">操作</th>
				</tr>
			</thead>
			<tbody>
					<if condition="is_array($categories)">
				
				<volist name="categories" id="category">
					<tr>
						<td>
							<a url="<?php echo U('Help/category_del', array('id' => $category['cat_id'])); ?>" class="delete_row"><img src="{pigcms{$static_path}images/icon_delete.png" width="18" title="删除" alt="删除"/></a> |
							<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Help/category_edit', array('id' => $category['cat_id']))}','修改分类 - {pigcms{$category.cat_name}',480,<if condition="$category['cat_pic']">390<else/>310</if>,true,false,false,editbtn,'edit',true);"><img src="{pigcms{$static_path}images/icon_edit.png" width="18" title="修改" alt="修改"/></a></td>
						<td>{pigcms{$category.cat_id}</td>
						<td><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Help/category_edit', array('id' => $category['cat_id']))}','修改分类 - {pigcms{$category.cat_name}',480,<if condition="$category['cat_pic']">390<else/>310</if>,true,false,false,editbtn,'edit',true);"><?php if ($category['cat_level'] > 1) { echo str_repeat('|——', $category['cat_level']); } ?><span <?php if ($category['cat_level'] == 1){ ?>style="font-weight: bold;" <?php } ?>>{pigcms{$category.cat_name}</span></a></td>
						<td>{pigcms{$category.cat_desc}</td>
						<td><if condition="$category['cat_status'] eq 1"><span class="green">启用</span><else/><span class="red">禁用</span></if></td>
						<td>{pigcms{$category.cat_sort}</td>
						<td><span class="cb-enable status-enable">
							<label class="cb-enable <if condition="$category['cat_status'] eq 1">selected</if>" data-id="<?php echo $category['cat_id']; ?> " data-status="{pigcms{$category.cat_parent_status}"><span>启用</span><input type="radio" name="status" value="1" <if condition="$category['cat_id'] eq 1">checked="checked" </if> /></label></span>
							<span class="cb-disable status-disable"><label class="cb-disable <if condition="$category['cat_status'] eq 0">selected</if>" data-id="<?php echo $category['cat_id']; ?> " data-status="{pigcms{$category.cat_parent_status}"><span>禁用</span><input type="radio" name="status" value="0" <if condition="$category['cat_id'] eq 0">checked="checked" </if> /></label></span></td>
					</tr>
				</volist>
				</if>
			</tbody>
			<tfoot>
				<if condition="is_array($categories)">
					<tr>
						<td class="textcenter pagebar" colspan="7">{pigcms{$page}</td>
					</tr>
					<else/>
					<tr>
						<td class="textcenter red" colspan="7">列表为空！</td>
					</tr>
				</if>
			</tfoot>
		</table>
	</div>
</div>
<include file="Public:footer"/>