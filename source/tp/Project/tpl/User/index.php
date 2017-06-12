<include file="Public:header"/>
<div class="mainbox">
	<div id="nav" class="mainnav_title">
		<ul>
			<a href="{pigcms{:U('User/index')}" class="on">用户列表</a>
		</ul>
	</div>
	<table class="search_table" width="100%">
		<tr>
			<td>
				<form action="{pigcms{:U('User/index')}" method="get">
					<input type="hidden" name="c" value="User"/>
					<input type="hidden" name="a" value="index"/>
					筛选:
					<input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
					<select name="searchtype">
						<option value="uid"

						<if condition="$_GET['searchtype'] eq 'uid'">selected="selected"</if>
						>用户ID
						</option>
						<option value="nickname"

						<if condition="$_GET['searchtype'] eq 'nickname'">selected="selected"</if>
						>昵称
						</option>
						<option value="phone"

						<if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>
						>手机号
						</option>
					</select>
					<input type="submit" value="查询" class="button"/>
				</form>
			</td>
		</tr>
	</table>
	<form name="myform" id="myform" action="" method="post">
		<div class="table-list">
			<table width="100%" cellspacing="0">
				<colgroup>
					<col/>
					<col/>
					<col/>
					<col/>
					<col/>
					<col/>
					<col/>
					<col/>
					<col width="180" align="center"/>
				</colgroup>
				<thead>
				<tr>
					<th>ID</th>
					<th>昵称</th>
					<th>手机号</th>
					<th>上线</th>
					<th>店铺数量</th>
					<th>余额</th>
					<th>钱包</th>
					<th>积分</th>
					<th>最后登录时间</th>
					<th>最后登录IP</th>
					<th class="textcenter">状态</th>
                    <?php if(session('system.account')=='ywswatch'):?>
                    <?php else:?>
                        <th class="textcenter">操作</th>
                    <?php endif;?>

				</tr>
				</thead>
				<tbody>
				<if condition="is_array($user_list)">
					<volist name="user_list" id="vo">
						<tr>
							<td>{pigcms{$vo.uid}<?php echo $vo['import_uid'] ? '.' : ''; ?></td>
							<td>{pigcms{$vo.nickname}</td>
							<td>{pigcms{$vo.phone}</td>
							<td>{pigcms{$vo.parent_uid}</td>
							<td>{pigcms{$vo.stores}</td>
							<td>{pigcms{$vo.balance} 元</td>
							<td>{pigcms{$vo.consumer} 元</td>
							<td>{pigcms{$vo.point} 分</td>
							<td>{pigcms{$vo.last_time|date='Y-m-d H:i:s',###}</td>
							<td>{pigcms{$vo.last_ip_txt}</td>
							<td class="textcenter">
								<if condition="$vo['status'] eq 1"><font color="green">正常</font>
									<else/>
									<font color="red">禁止</font></if>
							</td>
                            <?php if(session('system.account')=='ywswatch'):?>
                            <?php else:?>
                                <td class="textcenter">
                                    <a href="javascript:void(0);"
                                       onclick="window.top.artiframe('{pigcms{:U('User/edit',array('uid'=>$vo['uid']))}','编辑用户信息',800,420,true,false,false,editbtn,'edit',true);">编辑</a>
                                    |
                                    <a href="javascript:void(0);" onclick="if(confirm('确认删除？')){location.href='{pigcms{:U('User/dele',array('uid'=>$vo['uid']))}';}">删除</a>
                                    |
                                    <?php if($vo['stores']){ ?>
                                        <a href="javascript:void(0);" style="color: #3865B8" onclick="window.top.artiframe('{pigcms{:U('Store/detail',array('uid'=>$vo['uid'],'frame_show'=>true))}','店铺详细 - {pigcms{$store.name}',650,500,true,false,false,false,'detail',true);">店铺详细</a>
                                    <?php }else{ ?>
                                        <a href="javascript:void(0);" onclick="if(confirm('确认创建店铺吗？')){location.href='{pigcms{:U('Store/create',array('uid'=>$vo['uid']))}';}">创建店铺</a>
                                        <?php
                                        /*
                                            <a href="javascript:;" style="color: #3865B8" onclick="window.top.artiframe('{pigcms{:U('User/stores', array('uid' => $vo['uid'], 'frame_show' => true))}','商家 {pigcms{$vo.nickname} 的店铺',750,700,true,false,false,false,'detail',true)">查看店铺</a>
                                        */
                                    } ?>

                                    | <a href="javascript:void(0);"
                                         onclick="window.top.artiframe('{pigcms{:U('User/details',array('uid'=>$vo['uid']))}','收支明细',800,420,true,false,false,editbtn,'edit',true);">收支明细</a>
                                    | <a href="{pigcms{:U('tab_store',array('uid'=>$vo['uid']))}" target="_blank">进入店铺</a>
                                </td>
                            <?php endif;?>

						</tr>
					</volist>
					<tr>
						<td class="textcenter pagebar" colspan="12">{pigcms{$pagebar}</td>
					</tr>
					<else/>
					<tr>
						<td class="textcenter red" colspan="12">列表为空！</td>
					</tr>
				</if>
				</tbody>
			</table>
		</div>
	</form>
</div>
<include file="Public:footer"/>