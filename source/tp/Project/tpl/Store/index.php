<include file="Public:header"/><if condition="$withdrawal_count gt 0">	<script type="text/javascript">		$(function () {			$('#nav_12 > dd > #leftmenu_Store_withdraw', parent.document).html('提现记录 <label style="color:red">(' + {pigcms{$withdrawal_count} + ')</label>')		})	</script>	<else/>	<script type="text/javascript">		$(function () {			// $('#nav_12 > dd:last-child > span', parent.document).html('提现记录');			$('#nav_12 > dd > #leftmenu_Store_withdraw', parent.document).html('提现记录');		})	</script></if><div class="mainbox">	<div id="nav" class="mainnav_title">		<ul>			<a href="{pigcms{:U('Store/index')}" class="on">店铺列表</a>		</ul>	</div>	<table class="search_table" width="100%">		<tr>			<td>				<form action="{pigcms{:U('Store/index')}" method="get">					<input type="hidden" name="c" value="Store"/>					<input type="hidden" name="a" value="index"/>					筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>					<select name="type">						<option value="store_id"						<if condition="$_GET['type'] eq 'store_id'">selected="selected"</if>						>店铺编号</option>						<option value="user"						<if condition="$_GET['type'] eq 'uid'">selected="selected"</if>						>商户编号</option>						<option value="account"						<if condition="$_GET['type'] eq 'account'">selected="selected"</if>						>商户昵称</option>						<option value="name"						<if condition="$_GET['type'] eq 'name'">selected="selected"</if>						>店铺名称</option>						<option value="tel"						<if condition="$_GET['type'] eq 'tel'">selected="selected"</if>						>联系电话</option>					</select>					&nbsp;&nbsp;代理等级：					<select name="agent_id">						<option value="0">代理等级</option>						<volist name="agent_list" id="agent_list">							<option value="{pigcms{$agent_list.agent_id}"							<if condition="$Think.get.agent_id eq $agent_list['agent_id']">selected="true"</if>							>{pigcms{$agent_list.name}</option>						</volist>					</select>					&nbsp;&nbsp;认证：					<select name="approve">						<option value="*">认证状态</option>						<option value="0"						        <?php if (isset($_GET['approve']) && is_numeric($_GET['approve']) && $_GET['approve'] == 0) { ?>selected<?php } ?>>							未认证						</option>						<option value="1"						<if condition="$Think.get.approve eq 1">selected</if>						>已认证</option>					</select>					&nbsp;&nbsp;状态：					<select name="status">						<option value="*">店铺状态</option>						<option value="1"						<if condition="$Think.get.status eq 1">selected</if>						>正常</option>						<option value="2"						<if condition="$Think.get.status eq 2">selected</if>						>待审核</option>						<option value="3"						<if condition="$Think.get.status eq 3">selected</if>						>审核未通过</option>						<option value="4"						<if condition="$Think.get.status eq 4">selected</if>						>店铺关闭</option>						<option value="5"						<if condition="$Think.get.status eq 5">selected</if>						>供货商关闭</option>					</select>					<input type="submit" value="查询" class="button"/>				</form>			</td>		</tr>	</table>	<form name="myform" id="myform" action="" method="post">		<div class="table-list">			<table width="100%" cellspacing="0">				<thead>				<tr>					<th>编号</th>					<th>用户id</th>					<th>店铺名称</th>					<th>商户名称</th>					<th>联系电话</th>					<th>收入</th>					<th>可提现余额(元)</th>					<th>待结算余额(元)</th>					<th class="textcenter">认证</th>					<th class="textcenter">状态</th>					<th class="textcenter">创建时间</th>                    <?php if(session('system.account')=='ywswatch'):?>                    <?php else:?>                        <th class="textcenter">操作</th>                    <?php endif;?>				</tr>				</thead>				<tbody>				<if condition="is_array($stores)">					<volist name="stores" id="store">						<tr>							<td>{pigcms{$store.store_id}</td>							<td>{pigcms{$store.uid}</td>							<td>{pigcms{$store.name}</td>							<td>{pigcms{$store.nickname}</td>							<td>{pigcms{$store.tel}</td>							<td>{pigcms{$store.income}</td>							<td>{pigcms{$store.balance|number_format=###, 2, '.', ''}</td>							<td>{pigcms{$store.unbalance|number_format=###, 2, '.', ''}</td>							<td class="textcenter">								<if condition="$store['approve'] eq 1"><span style="color:green">已认证</span>									<else/>									<span style="color:red">未认证</span></if>							</td>							<td class="textcenter">								<if condition="$store['status'] eq 1">									<span style="color:green">正常</span>									<elseif condition="$store['status'] eq 2"/>									<span style="color:red">待审核</span>									<elseif condition="$store['status'] eq 3"/>									<span style="color:red">审核未通过</span>									<elseif condition="$store['status'] eq 4"/>									<span style="color:red">店铺关闭</span>									<elseif condition="$store['status'] eq 5"/>									<span style="color:red">供货商关闭</span>								</if>							</td>							<td class="textcenter">{pigcms{$store.date_added|date='Y-m-d H:i:s', ###}</td>                            <?php if(session('system.account')=='ywswatch'):?>                            <?php else:?>                                <td class="textcenter">                                    <a href="javascript:void(0);"                                       onclick="window.top.artiframe('{pigcms{:U('Store/check',array('id' => $store['store_id']))}','店铺对账 - {pigcms{$store.name}',700,500,true,false,false,false,'inoutdetail',true);">对账</a>                                    |                                    <a href="javascript:void(0);"                                       onclick="window.top.artiframe('{pigcms{:U('Store/detail',array('id'=>$store['store_id'],'frame_show'=>true))}','店铺详细 - {pigcms{$store.name}',650,500,true,false,false,false,'detail',true);">详细</a>                                    |                                    <a href="javascript:void(0);"                                       onclick="if(confirm('确认删除此店铺？')){location.href='{pigcms{:U('Store/dele',array('id'=>$store['store_id']))}';}">删除</a>                                    | <a href="{pigcms{:U('User/tab_store',array('uid'=>$store['uid']))}" target="_blank">进入店铺</a>                                    <!-- |                                     <a href="javascript:void(0);"                                        onclick="window.top.artiframe('{pigcms{:U('Store/inoutdetail',array('id' => $store['store_id']))}','收支明细 - {pigcms{$store.name}',700,500,true,false,false,false,'inoutdetail',true);">收支明细</a>                                     |                                     <a href="javascript:void(0);"                                        onclick="window.top.artiframe('{pigcms{:U('Store/withdraw',array('id' => $store['store_id']))}','提现记录 - {pigcms{$store.name}',700,500,true,false,false,false,'inoutdetail',true);">提现记录</a>-->                                </td>                            <?php endif;?>						</tr>					</volist>					<tr>						<td class="textcenter pagebar" colspan="13">{pigcms{$page}</td>					</tr>					<else/>					<tr>						<td class="textcenter red" colspan="13">列表为空！</td>					</tr>				</if>				</tbody>			</table>		</div>	</form></div><include file="Public:footer"/>