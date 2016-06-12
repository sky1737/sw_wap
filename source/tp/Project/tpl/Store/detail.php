<include file="Public:header" />
<style type="text/css">
	.frame_form th { border-left: 1px solid #e5e3e3 !important; font-weight: bold; color: #ccc; }
	.frame_form td { vertical-align: middle; }
	.center { text-align: center !important; }
	.right-border { border-right: 1px solid #e5e3e3 !important; }
</style>
<script type="text/javascript">
	$(function () {
//		$('.status-enable > .cb-enable').click(function () {
//			if (!$(this).hasClass('selected')) {
//				var store_id = $(this).data('id');
//				$.post("<?php echo U('Store/status'); ?>", {'status': 1, 'store_id': store_id}, function (data) {
//				})
//			}
//		})
//		$('.status-disable > .cb-disable').click(function () {
//			var store_id = $(this).data('id');
//			if (!$(this).hasClass('selected')) {
//				$.post("<?php echo U('Store/status'); ?>", {'type': 'status', 'status': 0, 'store_id': store_id}, function (data) {
//				})
//			}
//		})
		$(".js-store_status").change(function () {
			var store_id = $(this).closest("td").data("id");
			var status = $(this).val();
			$.post("<?php echo U('Store/status'); ?>", {
				'type': 'status',
				'status': status,
				'store_id': store_id
			}, function (data) {
			})
		});
		
		$(".js-agent_id").change(function () {
			var store_id = $(this).closest("td").data("id");
			var status = $(this).val();
			$.post("<?php echo U('Store/status'); ?>", {
				'type': 'agent_id',
				'status': status,
				'store_id': store_id
			}, function (data) {
			})
		});
		
		$('.approve-enable > .cb-enable').click(function () {
			if (!$(this).hasClass('selected')) {
				var store_id = $(this).data('id');
				var status = $(this).val();
				$.post("<?php echo U('Store/status'); ?>", {
					'type': 'approve',
					'status': 1,
					'store_id': store_id
				}, function (data) {
				})
			}
		});
		
		$('.approve-disable > .cb-disable').click(function () {
			var store_id = $(this).data('id');
			if (!$(this).hasClass('selected')) {
				$.post("<?php echo U('Store/status'); ?>", {
					'type': 'approve',
					'status': 0,
					'store_id': store_id
				}, function (data) {
				})
			}
		});

		$('.buyer_selffetch-enable > .cb-enable').click(function () {
			if (!$(this).hasClass('selected')) {
				var store_id = $(this).data('id');
				$.post("<?php echo U('Store/status'); ?>", {
					'type': 'buyer_selffetch',
					'status': 1,
					'store_id': store_id
				}, function (data) {
				})
			}
		});
		
		$('.buyer_selffetch-disable > .cb-disable').click(function () {
			var store_id = $(this).data('id');
			if (!$(this).hasClass('selected')) {
				$.post("<?php echo U('Store/status'); ?>", {
					'type': 'buyer_selffetch',
					'status': 0,
					'store_id': store_id
				}, function (data) {
				})
			}
		});

		$('.open_logistics-enable > .cb-enable').click(function () {
			if (!$(this).hasClass('selected')) {
				var store_id = $(this).data('id');
				$.post("<?php echo U('Store/status'); ?>", {
					'type': 'status',
					'type': 'open_logistics',
					'status': 1,
					'store_id': store_id
				}, function (data) {
				})
			}
		});
		$('.open_logistics-disable > .cb-disable').click(function () {
			var store_id = $(this).data('id');
			if (!$(this).hasClass('selected')) {
				$.post("<?php echo U('Store/status'); ?>", {
					'type': 'open_logistics',
					'status': 0,
					'store_id': store_id
				}, function (data) {
				})
			}
		});

		$('.open_friend-enable > .cb-enable').click(function () {
			if (!$(this).hasClass('selected')) {
				var store_id = $(this).data('id');
				$.post("<?php echo U('Store/status'); ?>", {
					'type': 'status',
					'type': 'open_friend',
					'status': 1,
					'store_id': store_id
				}, function (data) {
				})
			}
		});
		$('.open_friend-disable > .cb-disable').click(function () {
			var store_id = $(this).data('id');
			if (!$(this).hasClass('selected')) {
				$.post("<?php echo U('Store/status'); ?>", {
					'type': 'open_friend',
					'status': 0,
					'store_id': store_id
				}, function (data) {
				})
			}
		});

		$('.drp_approve-enable > .cb-enable').click(function () {
			if (!$(this).hasClass('selected')) {
				var store_id = $(this).data('id');
				$.post("<?php echo U('Store/status'); ?>", {
					'type': 'status',
					'type': 'drp_approve',
					'status': 1,
					'store_id': store_id
				}, function (data) {
				})
			}
		});
		$('.drp_approve-disable > .cb-disable').click(function () {
			var store_id = $(this).data('id');
			if (!$(this).hasClass('selected')) {
				$.post("<?php echo U('Store/status'); ?>", {
					'type': 'drp_approve',
					'status': 0,
					'store_id': store_id
				}, function (data) {
				})
			}
		});
	})
</script>
<input type="hidden" name="id" value="{pigcms{$store.store_id}" />
<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
	<tr>
		<th width="60" class="center">店铺LOGO</th>
		<td>
			<div class="show"><img src="{pigcms{$store.logo}" width="60" height="60" /></div>
		</td>
		<th width="80" class="center">店铺名称</th>
		<td colspan="3" class="right-border">{pigcms{$store.name}</td>
	</tr>
	<tr>
		<th width="80" class="center">商户账号</th>
		<td>{pigcms{$store.username}</td>
		<th class="center">商品名称</th>
		<td colspan="3" class="right-border">{pigcms{$store.nickname}</td>
	</tr>
	<tr>
		<th class="center">主营类目</th>
		<td>{pigcms{$store.category}</td>
		<th class="center">创建时间</th>
		<td colspan="3" class="right-border">{pigcms{$store.date_added|date='Y-m-d H:i:s', ###}</td>
	</tr>
	<tr>
		<th class="center">联系电话</th>
		<td>{pigcms{$store.tel}</td>
		<th class="center">QQ号码</th>
		<td colspan="3" class="right-border">{pigcms{$store.qq}</td>
	</tr>
	<tr>
		<th class="center">店铺收入</th>
		<td align="right">￥{pigcms{$store.income}</td>
		<th class="center">可提现金额</th>
		<td align="right">￥{pigcms{$store.balance}</td>
		<th class="center">待结算金额</th>
		<td align="right" class="right-border">￥{pigcms{$store.unbalance}</td>
	</tr>
	<tr>
		<th width="80" class="center">店铺状态</th>
		<td data-id="<?php echo $store['store_id']; ?>"><select class="js-store_status">
				<?php
				$sel = 'selected="selected"';
				echo '<option value="1"' . ($store['status'] == 1 ? $sel : '') . '>正常</option>' .
					'<option value="2"' . ($store['status'] == 2 ? $sel : '') . '>待审核</option>' .
					'<option value="3"' . ($store['status'] == 3 ? $sel : '') . '>审核未通过</option>' .
					'<option value="4"' . ($store['status'] == 4 ? $sel : '') . '>店铺关闭</option>';
				if($store['drp_supplier_id']) {
					echo '<option value="5"' . ($store['status'] == 5 ? $sel : '') . '>供货商关闭</option>';
				}
				?>
			</select></td>
		<th width="80" class="center">认证状态</th>
		<td class="right-border" colspan="3">
			<?php
			echo '<span class="cb-enable approve-enable"><label class="cb-enable' .
				($store['approve'] ? ' selected' : '') . '" data-id="' . $store['store_id'] .
				'"><span>认证</span><input type="radio" name="approve" value="1"' .
				($store['approve'] ? ' checked="checked"' : '') . ' /></label></span>'
				. '<span class="cb-disable approve-disable"><label class="cb-disable' .
				($store['approve'] ? '' : ' selected') . '" data-id="' . $store['store_id'] .
				'"><span>取消</span><input type="radio" name="approve" value="0"' .
				($store['approve'] ? '' : ' checked="checked"') . ' /></label></span>'; ?></td>
	</tr>
	<tr>
		<th width="80" class="center">分销状态</th>
		<td class="right-border">
			<?php
			echo '<span class="cb-enable drp_approve-enable"><label class="cb-enable' .
				($store['drp_approve'] ? ' selected' : '') . '" data-id="' . $store['store_id'] .
				'"><span>认证</span><input type="radio" name="drp_approve" value="1"' .
				($store['drp_approve'] ? ' checked="checked"' : '') . ' /></label></span>'
				. '<span class="cb-disable drp_approve-disable"><label class="cb-disable' .
				($store['drp_approve'] ? '' : ' selected') . '" data-id="' . $store['store_id'] .
				'"><span>取消</span><input type="radio" name="drp_approve" value="0"' .
				($store['drp_approve'] ? '' : ' checked="checked"') . ' /></label></span>'; ?></td>
		<th width="80" class="center">代理状态</th>
		<td class="right-border" colspan="3" data-id="<?php echo $store['store_id']; ?>"><select class="js-agent_id">
				<?php
				$sel = 'selected="selected"';
				if(!empty($store['agents'])) {
					echo '<option value="0"></option>';
					foreach($store['agents'] as $v) {
						echo '<option value="'.$v['agent_id'].'"' . ($store['agent_id'] == $v['agent_id'] ? $sel : '') . '>'.$v['name'].'</option>';
					}
				}
				?>
			</select>
			<?php
			/*echo '<span class="cb-enable agent_approve-enable"><label class="cb-enable' .
				($store['agent_approve'] ? ' selected' : '') . '" data-id="' . $store['store_id'] .
				'"><span>认证</span><input type="radio" name="agent_approve" value="1"' .
				($store['agent_approve'] ? ' checked="checked"' : '') . ' /></label></span>'
				. '<span class="cb-disable agent_approve-disable"><label class="cb-disable' .
				($store['agent_approve'] ? '' : ' selected') . '" data-id="' . $store['store_id'] .
				'"><span>取消</span><input type="radio" name="agent_approve" value="0"' .
				($store['agent_approve'] ? '' : ' checked="checked"') . ' /></label></span>';*/ ?></td>
	</tr>
	<tr>
		<th width="80" class="center">物流状态</th>
		<td class="right-border" colspan="5">
			<?php
			echo '<span class="cb-enable open_logistics-enable"><label class="cb-enable' .
				($store['open_logistics'] ? ' selected' : '') . '" data-id="' . $store['store_id'] .
				'"><span>开启</span><input type="radio" name="open_logistics" value="1"' .
				($store['open_logistics'] ? ' checked="checked"' : '') . ' /></label></span>'
				. '<span class="cb-disable open_logistics-disable"><label class="cb-disable' .
				($store['open_logistics'] ? '' : ' selected') . '" data-id="' . $store['store_id'] .
				'"><span>禁用</span><input type="radio" name="open_logistics" value="0"' .
				($store['open_logistics'] ? '' : ' checked="checked"') . ' /></label></span>'; ?></td>
		<!--<th class="center">送朋友</th>
		<td align="center" colspan="3" class="right-border">
			<?php
		echo '<span class="cb-enable open_friend-enable"><label class="cb-enable' .
			($store['open_friend'] ? ' selected' : '') . '" data-id="' . $store['store_id'] .
			'"><span>开启</span><input type="radio" name="open_friend" value="1"' .
			($store['open_friend'] ? ' checked="checked"' : '') . ' /></label></span>'
			. '<span class="cb-disable open_friend-disable"><label class="cb-disable' .
			($store['open_friend'] ? '' : ' selected') . '" data-id="' . $store['store_id'] .
			'"><span>禁用</span><input type="radio" name="open_friend" value="0"' .
			($store['open_friend'] ? '' : ' checked="checked"') . ' /></label></span>'; ?></td>-->
	</tr>

	<!--<tr>
		<th class="center">上门自提</th>
		<td align="center" colspan="5">
			<?php
	echo '<span class="cb-enable buyer_selffetch-enable"><label class="cb-enable' .
		($store['buyer_selffetch'] ? ' selected' : '') . '" data-id="' . $store['store_id'] .
		'"><span>开启</span><input type="radio" name="buyer_selffetch" value="1"' .
		($store['buyer_selffetch'] ? ' checked="checked"' : '') . ' /></label></span>'
		. '<span class="cb-disable buyer_selffetch-disable"><label class="cb-disable' .
		($store['buyer_selffetch'] ? '' : ' selected') . '" data-id="' . $store['store_id'] .
		'"><span>禁用</span><input type="radio" name="buyer_selffetch" value="0"' .
		($store['buyer_selffetch'] ? '' : ' checked="checked"') . ' /></label></span>'; ?></td>
	</tr>-->
	<tr>
		<th class="center">店铺描述</th>
		<td colspan="5" class="right-border">{pigcms{$store.intro}</td>
	</tr>
	<!--<tr>
		<th class="center" colspan="6">提现账号</th>
	</tr>
	<tr>
		<th class="center">提现方式</th>
		<td>
			<if condition="$store['widthdrawal_type'] eq 1">对公银行账户
				<else />
				对私银行账户
			</if>
		</td>
		<th class="center">开户银行</th>
		<td colspan="3" class="right-border">{pigcms{$store.bank}</td>
	</tr>
	<tr>
		<th class="center">银行卡号</th>
		<td>{pigcms{$store.bank_card}</td>
		<th class="center">开卡人姓名</th>
		<td colspan="3" class="right-border">{pigcms{$store.bank_card_user}</td>
	</tr>-->
</table>
<div class="btn hidden">
	<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
	<input type="reset" value="取消" class="button" />
</div>
<include file="Public:footer" />