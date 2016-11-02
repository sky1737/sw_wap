<include file="Public:header" />
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
				var id = $(this).data('id');
				$.post("<?php echo U('Config/menu_status'); ?>", {'status': 1, 'id': id}, function (data) {
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
				var id = $(this).data('id');
				if (!$(this).hasClass('selected')) {
					$.post("<?php echo U('Config/menu_status'); ?>", {
						'status': 0,
						'id': id
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
			<a href="{pigcms{:U('Config/menu')}" class="on">菜单管理</a>| <a href="javascript:void(0);"
			   onclick="window.top.artiframe('{pigcms{:U('Config/menu_edit')}','添加菜单',480,310,true,false,false,addbtn,'add',true);">添加菜单</a>
		</ul>
	</div>
	<?php /*<table class="search_table" width="100%">
		<tr>
			<td><form action="{pigcms{:U('Config/menu')}" method="get">
					<input type="hidden" name="c" value="Product" />
					<input type="hidden" name="a" value="menu" />
					筛选:
					<select name="id">
						<option value="0">商品类目</option>
						<volist name="all_list" id="all_menu"> <option<?php echo $Think.get.id == $all_menu['id'] ? ' selected="selected"' :''; ?> value="{pigcms{$all_menu['id']}">
							<?php echo $all_menu['level'] > 1 ? str_repeat('&nbsp;&nbsp;', $all_menu['level']) : ''; ?>|-- {pigcms{$all_menu.name}</option>
						</volist>
					</select>
					<input type="submit" value="查询" class="button" />
				</form></td>
		</tr>
	</table>*/?>
	<div class="table-list">
		<table width="100%" cellspacing="0">
			<thead>
				<tr>
					<th>编号</th>
					<th>名称</th>
					<th>描述</th>
					<th>状态</th>
					<th>排序</th>
					<th>状态</th>
					<th>删除 | 修改</th>
				</tr>
			</thead>
			<tbody>
				<if condition="is_array($list)">
					<volist name="list" id="menu">
						<tr>
							<td>{pigcms{$menu.id}</td>
							<td><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Config/menu_edit', array('id' => $menu['id']))}','修改分类 - {pigcms{$menu.name}',480,310,true,false,false,editbtn,'edit',true);">{pigcms{$menu.prefix} <span <?php if($menu['fid'] == 0){ echo 'style="font-weight:bold;"'; } ?>>{pigcms{$menu.name}</span></a></td>
							<td>{pigcms{$menu.desc}</td>
							<td><?php echo $menu['status']?'<span class="green">启用</span>':'<span class="red">禁用</span>';?></td>
							<td>{pigcms{$menu.sort}</td>
							<td><span class="cb-enable status-enable">
								<label class="cb-enable <?php echo $menu['status'] ? 'selected' : ''; ?>" data-id="<?php echo $menu['id']; ?>" data-status="{pigcms{$menu.status}"><span>启用</span>
									<input type="radio" name="status" value="1"<?php echo $menu['status'] ? ' checked="checked"' : ''; ?> />
								</label>
								</span> <span class="cb-disable status-disable">
								<label class="cb-disable <?php echo $menu['status'] ? '' : 'selected'; ?>" data-id="<?php echo $menu['id']; ?>" data-status="{pigcms{$menu.status}"><span>禁用</span>
									<input type="radio" name="status" value="0"<?php echo $menu['status'] ? '' : ' checked="checked"'; ?> />
								</label>
								</span></td>
							<td><a url="<?php echo U('Config/menu_del', array('id' => $menu['id'])); ?>" class="delete_row"> <img src="{pigcms{$static_path}images/icon_delete.png" width="18" title="删除" alt="删除" /></a> | <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Config/menu_edit', array('id' => $menu['id']))}','修改分类 - {pigcms{$menu.name}',480,310,true,false,false,editbtn,'edit',true);"> <img src="{pigcms{$static_path}images/icon_edit.png" width="18" title="修改" alt="修改" /></a></td>
						</tr>
					</volist>
				</if>
			</tbody>
			<tfoot>
				<if condition="is_array($list)">
					<tr>
						<td class="textcenter pagebar" colspan="7">{pigcms{$page}</td>
					</tr>
					<else />
					<tr>
						<td class="textcenter red" colspan="7">列表为空！</td>
					</tr>
				</if>
			</tfoot>
		</table>
	</div>
</div>
<include file="Public:footer" />