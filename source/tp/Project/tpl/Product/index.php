<include file="Public:header" />
<style type="text/css">
	/*a {		color: blue;	}
	a, a:hover {		text-decoration: none;	}*/
	.tag {
		display: inline-block;
		vertical-align: middle;
		padding: 3px 5px;
		margin: 0 3px 0 0;
		background-color: #f60; /**/
		color: #fff;
		font-size: 12px;
		line-height: 14px;
		border-radius: 2px;
		display: inline-block;
	}
	.red { color: #f60; }
	.attr {
		display: inline-block;
		vertical-align: middle;
		color: #f60;
		font-size: 12px;
		line-height: 14px;
		display: inline-block;
	}
	.attr b {
		font-weight: 400;
		color: #3A6EA5;
	}
</style>
<div class="mainbox">
	<div id="nav" class="mainnav_title">
		<ul>
			<a href="{pigcms{:U('Product/index')}" class="on">商品列表</a>
		</ul>
	</div>
	<table class="search_table" width="100%">
		<tr>
			<td>
				<form action="{pigcms{:U('Product/index')}" method="get">
					<input type="hidden" name="c" value="Product" />
					<input type="hidden" name="a" value="index" />
					筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}" />
					<select name="type">
						<option value="product_id"
						<if condition="$_GET['type'] eq 'product_id'">selected="selected"</if>
						>商品编号</option>
						<option value="name"
						<if condition="$_GET['type'] eq 'name'">selected="selected"</if>
						>商品名称</option>
						<option value="store"
						<if condition="$_GET['type'] eq 'store'">selected="selected"</if>
						>店铺名称</option>
						<option value="create_uid"
						<if condition="$_GET['type'] eq 'create_uid'">selected="selected"</if>
						>创建者id</option>
						<option value="create_u_name"
						<if condition="$_GET['type'] eq 'create_u_name'">selected="selected"</if>
						>创建者昵称</option>
					</select>
					&nbsp;&nbsp;分类：
					<select name="category">
						<option value="0">商品分类</option>
						<volist name="categories" id="category">
							<option
							<if condition="$_GET['category'] eq $category['cat_id']">selected="selected"</if>
								value="{pigcms{$category.cat_id}"><?php if($category['cat_level'] > 1) {
									echo str_repeat('&nbsp;&nbsp;', $category['cat_level']);
								} ?> |-- {pigcms{$category.cat_name}
							</option>
						</volist>
					</select>
					&nbsp;&nbsp;是否分销：
					<select name="isfx">
						<option value="0"
						<if condition="$_GET['isfx'] eq '0'">selected="selected"</if>
						>全部商品</option>
                        <option value="-1"
						<if condition="$_GET['isfx'] eq '-1'">selected="selected"</if>
						>分销待审</option>
						<option value="1"
						<if condition="$_GET['isfx'] eq '1'">selected="selected"</if>
						>分销商品</option>
						<option value="2"
						<if condition="$_GET['isfx'] eq '2'">selected="selected"</if>
						>非分销商品</option>
					</select>
                    
                    &nbsp;&nbsp;是否审核：
					<select name="ischk">
						<option>全部</option>
						<option value="1"
						<if condition="$_GET['ischk'] eq '1'">selected="selected"</if>
						>审核</option>
						<option value="2"
						<if condition="$_GET['ischk'] eq '2'">selected="selected"</if>
						>非审核</option>
					</select>
                    
                    &nbsp;&nbsp;是否热门：
					<select name="ishot">
						<option>全部</option>
						<option value="1"
						<if condition="$_GET['ishot'] eq '1'">selected="selected"</if>
						>热门</option>
						<option value="2"
						<if condition="$_GET['ishot'] eq '2'">selected="selected"</if>
						>非热门</option>
					</select>
                    
                    &nbsp;&nbsp;是否推荐：
					<select name="isred">
						<option>全部</option>
						<option value="1"
						<if condition="$_GET['isred'] eq '1'">selected="selected"</if>
						>推荐</option>
						<option value="2"
						<if condition="$_GET['isred'] eq '2'">selected="selected"</if>
						>非推荐</option>
					</select>

					<!-- &nbsp;&nbsp;分组：
							<select name="group">
								<option value="0">商品分组</option>
								<volist name="groups" id="group">
								<option value="{pigcms{$group['group_id']}" <if condition="$Think.get.group eq $group['group_id']">selected</if>>{pigcms{$group['group_name']}</option>
								</volist>
							</select>
							&nbsp;&nbsp;会员折扣：
							<select name="allow_discount">
								<option value="*">选择</option>
								<option value="1" <if condition="$Think.get.allow_discount eq 1">selected</if>>有</option>
								<option value="0" <?php if(isset($_GET['allow_discount']) &&
						is_numeric($_GET['allow_discount']) && $_GET['allow_discount'] == 0
					) { ?>selected<?php } ?>>无</option>
							</select>
							&nbsp;&nbsp;发票：
							<select name="invoice">
								<option value="*">选择</option>
								<option value="1" <if condition="$Think.get.invoice eq 1">selected</if>>有</option>
								<option value="0" <?php if(isset($_GET['invoice']) && is_numeric($_GET['invoice']) &&
						$_GET['invoice'] == 0
					) { ?>selected<?php } ?>>无</option>
							</select>-->
					<input type="submit" value="查询" class="button" />
				</form>
			</td>
		</tr>
	</table>
	<form name="myform" id="myform" action="" method="post">
		<div class="table-list">
			<style>
				.table-list td { line-height: 22px; padding-top: 5px; padding-bottom: 5px; }
			</style>
            <script type="text/javascript">
            function chk(o){
				var b = $(o).prop('checked');
				$('input[name=id]').each(function(index, element) {
					$(this).prop('checked',b);
				});
			}
            </script>
			<table width="100%" cellspacing="0">
				<thead>
				<tr>
                	<th><input type="checkbox" id="all" onclick="chk(this)" /></th>
					<th>编号</th>
					<th class="textcenter">图片</th>
					<th>产品信息</th>
					<!--<th>分类</th>
					<th>店铺</th>-->
					<th>价格(元)</th>
					<th>成本(元)</th>
					<th>数量(件)</th>
					<th>销量(件)</th>
					<!--th>买家限购</th-->
					<!--th class="textcenter">是/否参与折扣</th-->
					<!--th class="textcenter">是/否有发票</th-->
					<th class="textcenter">添加时间</th>
					<th class="textcenter">状态</th>
				</tr>
				</thead>
				<tbody>
				<if condition="is_array($products)">
					<volist name="products" id="product">
						<tr>
                        	<td><input type="checkbox" id="id_{pigcms{$product.product_id}" name="id" value="{pigcms{$product.product_id}" /></td>
							<td>{pigcms{$product.product_id}</td>
							<td class="textcenter"><img src="{pigcms{$product.image}" width="60" /></td>
							<td>
								<a href="/goods/{pigcms{$product.product_id}.html" target="_blank">{pigcms{$product.name}</a>
								<?php
								echo $product['is_fx'] == 1 ? '<span class="tag">分销</span>' : ''; ?>
								<br />
								<span class="attr">分类：<b>{pigcms{$product.category}</b></span>
								<br />
								<span class="attr">商家：<b>{pigcms{$product.store}</b></span>
								<br />
								<span class="attr">创建者 UID：<b>{pigcms{$product.createUid}</b></span>
								<span class="attr">昵称：<b>{pigcms{$product.createUName}</b></span>
							</td>
							<!--<td>{pigcms{$product.category}</td>
							<td>{pigcms{$product.store}</td>-->
							<td>{pigcms{$product.price}</td>
							<td>{pigcms{$product.cost_price}</td>
							<td>{pigcms{$product.quantity}</td>
							<td>{pigcms{$product.sales}</td>
							<!--td>{pigcms{$product.buyer_quota}</td-->
							<!--td class="textcenter"><if condition="$product['allow_discount'] eq 1">是<else/>否</if></td-->
							<!--td class="textcenter"><if condition="$product['invoic'] eq 1">有<else/>无</if></td-->
							<td class="textcenter">{pigcms{$product.date_added|date='Y-m-d H:i:s', ###}</td>
							<td>
								<?php
								echo '状态：<a class="red" href="javascript:;" data-type="status" data-id="' .
									$product['product_id'] . '">' . ($product['status'] ? '是' : '否') . '</a>';
								echo '<br/>热门：<a class="red" href="javascript:;" data-type="is_hot" data-id="' .
									$product['product_id'] . '">' . ($product['is_hot'] ? '是' : '否') . '</a>';
								//if($product['is_recommend'] != 0) {
									echo '<br/>推荐：<a class="red" href="javascript:;" data-type="is_recommend" data-id="' .
										$product['product_id'] . '">' . ($product['is_recommend'] == 1 ? '是' : '否') . '</a>';
								//}

								if($product['is_fx'] != 0) {
									echo '<br/>分销：<a class="red" href="javascript:;" data-type="is_fx" data-id="' .
										$product['product_id'] . '">' . ($product['is_fx'] == 1 ? '是' : '否') . '</a>';
								}
                                echo '<br/>爆款：<a class="red" href="javascript:;" data-type="is_boom" data-id="' .
                                    $product['product_id'] . '">' . ($product['is_boom'] ? '是' : '否') . '</a>';
								/*<span class="cb-enable status-enable"><label class="cb-enable<if condition=" $product['status'] eq 1"> selected</if>" data-id="<?php echo $product['product_id']; ?>"><span>启用</span><input type="radio" name="status" value="1" <if condition="$product['product_id'] eq 1">checked="checked"</if> /></label></span><span class="cb-disable status-disable"><label class="cb-disable<if condition="$product['status'] eq 0"> selected</if> " data-id="<?php echo $product['product_id']; ?>"><span>禁用</span><input type="radio" name="status" value="0" <if condition="$product['product_id'] eq 0">checked="checked"</if>/></label></span>*/
								?>
							</td>
						</tr>
					</volist>
					<tr>
						<td class="textcenter pagebar" colspan="10">{pigcms{$page}</td>
					</tr>
                    <tr>
                    	<td colspan="10">
                        <script type="text/javascript">
                        function set(type, val) {
							if($('input[name=id]:checked').length==0) {
								alert('没有选择任何记录！');
								return false;
							}
							var ids = '';
							$('input[name=id]:checked').each(function(index, element) {
                                ids += (ids==''?'':',') + $(this).val();
                            });
							$.post("<?php echo U('Product/sets'); ?>", {'ids':ids, 'type': type, 'val': val}, function (data) {
								alert(data.info);
								if (data.status == 1) {
									location.reload()
								}
							});
						}
						function del(){
							if($('input[name=id]:checked').length==0) {
								alert('没有选择任何记录！');
								return false;
							}
							if(!confirm('确认删除选择记录？')) return false;
							
							var ids = '';
							$('input[name=id]:checked').each(function(index, element) {
                                ids += (ids==''?'':',') + $(this).val();
                            });
							$.post("<?php echo U('Product/dels'); ?>", {'ids':ids}, function (data) {
								alert(data.info);
								if (data.status == 1) {
									location.reload()
								}
							});
						}
                        </script>
                        <?php if(session('system.account')=='ywswatch'):?>
                        <?php else:?>
                            全选：<input type="checkbox" id="all1" onclick="chk(this)" />&nbsp; <input type="button" id="dochk" value="设置审核" onclick="set('status',1)" /> <input type="button" id="undochk" value="取消审核" onclick="set('status',0)" />&nbsp; / &nbsp;<input type="button" id="dored" value="设置推荐" onclick="set('is_recommend',1)" /> <input type="button" id="undored" value="取消推荐" onclick="set('is_recommend',0)" />&nbsp; / &nbsp;<input type="button" id="dohot" value="设置热门" onclick="set('is_hot',1)" /> <input type="button" id="undohot" value="取消热门" onclick="set('is_hot',0)" />&nbsp; / &nbsp;<input type="button" id="dofx" value="设置分销" onclick="set('is_fx',1)" /> <input type="button" id="undofx" value="取消分销" onclick="set('is_fx',0)" />&nbsp; / &nbsp;<input type="button" value="批量删除" onclick="del()" /></td>

                        <?php endif;?>
                    </tr>
					<else />
					<tr>
						<td class="textcenter red" colspan="10">列表为空！</td>
					</tr>
				</if>
				</tbody>
			</table>
		</div>
	</form>
</div>
<script type="text/javascript">
	$(function () {
		$('.red').click(function () {
			var obj = $(this);
			var id = obj.data('id');
			var type = obj.data('type');
			if (!id || !type) {
				alert('请刷新后重试！');
				return false;
			}
			$.post("<?php echo U('Product/set'); ?>", {'key': type, 'val': id}, function (data) {
					if (data.status == '1') {
						if (data.info == '1') {
							obj.text('是');
						}
						else {
							obj.text('否');
						}
						return false;
					}
					alert(data.msg);
				}
			);
			return false;
		});
	})
</script>
<include file="Public:footer" />