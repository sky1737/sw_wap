<nav class="ui-nav">
	<ul>
		<li class="js-list-index active"><a href="#list">收货地址</a></li>
	</ul>
</nav>
<div class="widget-list">
	<div class="ui-box">
		<table class="ui-table ui-table-list physical_list">
			<thead class="js-list-header-region tableFloatingHeaderOriginal">
			<tr class="widget-list-header">
				<th style="width: 45px;">默认</th>
				<th class="cell-12">收货地址</th>
				<th class="cell-20">收件人</th>
				<th class="cell-12">联系电话</th>
				<th class="cell-12">操作</th>
			</tr>
			</thead>
			<tbody class="js-list-body-region">
			<?php
			if(!empty($list)) {
				foreach ($list as $addr) {
					echo '<tr class="widget-list-item">' .
						'<td><input type="radio" id="default" name="default" value="' . $addr['address_id'] . '" ' .
						($addr['default'] ? ' checked="checked"' : '') . ' /></td>' .
						'<td>' . $addr['province_txt'] . ' ' . $addr['city_txt'] . ' ' . $addr['area_txt'] . ' ' .
						$addr['address'] . '(' . $addr['zipcode'] . ')</td>' .
						'<td>' . $addr['name'] . '</td>' .
						'<td>' . $addr['tel'] . '</td>' .
						'<td class="dianpu">' .
						'<a href="javascript:;" class="js-edit" data-json=\'' . json_encode($addr) . '\'>编辑</a>' .
						// url('account:address',array('id'=>$addr['address_id']))
						' - ' .
						'<a href="javascript:;" class="js-delete" data-id="' . $addr['address_id'] . '">删除</a></td>' .
						'</tr>';
				}
			}
			?>
			</tbody>
		</table>
	</div>
	<div class="js-list-footer-region ui-box"></div>
</div>
<nav class="ui-nav">
	<ul>
		<li class="js-list-index active"><a href="#add">新增收货地址</a></li>
	</ul>
</nav>
<div id="J_AddressEditContainer" style="display: block;">
	<form class="form-horizontal clearfix" id="form1" method="post" name="form1">
		<input type="hidden" id="address_id" name="address_id" value="0" />
		<div class="control-group clearfix">
			<label class="control-label"> <em class="required">*</em> 收件人： </label>
			<div class="controls">
				<input id="name" type="text" name="name" placeholder="请填写完整的真实姓名" />
			</div>
		</div>
		<div class="control-group clearfix">
			<label class="control-label"><em class="required">*</em>联系电话：</label>
			<div class="controls">
				<input id="tel" type="text" name="tel" placeholder="填写准确的手机号码，便于及时联系" maxlength="11" />
			</div>
		</div>
		<div class="control-group clearfix">
			<label class="control-label"> <em class="required">*</em> 所属地区： </label>
			<div class="controls ui-regions js-regions-wrap">
				<span>
				<select name="province" id="province">
				</select>
				</span> <span>
				<select name="city" id="city">
					<option value="">选择城市</option>
				</select>
				</span> <span>
				<select name="area" id="area">
					<option value="">选择地区</option>
				</select>
				</span></div>
		</div>
		<div class="control-group clearfix">
			<label class="control-label"> <em class="required">*</em> 联系地址： </label>
			<div class="controls">
				<input id="address" type="text" class="span6  js-address-input" name="address" placeholder="请填写详细地址，以便买家联系；（勿重复填写省市区信息）" maxlength="80" />
			</div>
		</div>
		<div class="control-group clearfix">
			<label class="control-label"> <em class="required">*</em> 邮编： </label>
			<div class="controls">
				<input id="zipcode" type="text" class="span6  js-address-input" name="zipcode" placeholder="邮政编码，不清楚请填写“000000”" maxlength="80" />
			</div>
		</div>
		<div class="control-group clearfix">
			<label class="control-label"><em class="required">*</em>默认地址：</label>
			<div class="controls">
				<input id="default" type="checkbox" name="default" value="1" />
			</div>
		</div>
		<div class="form-actions clearfix">
			<button class="ui-btn ui-btn-primary js-btn-submit" type="submit" data-text-loading="保存中...">新增收货地址</button>
		</div>
	</form>
</div>
<script src="<?php echo $config['oss_url']; ?>/static/js/area/area.min.js" type="text/javascript"></script>
<script type="text/javascript">

	function showRequest() {
		var name = $("#name").val();
		var tel = $("#tel").val();
		var province = $("#province").val();
		var city = $("#city").val();
		var area = $("#area").val();
		var address = $("#address").val();
		//var def	= $('#default').attr("checked") ? 1 : 0;
		if (name.length == 0) {
			tusi("请填写收货人姓名");
			$("#name").focus();
			return false;
		}

		if (tel.length == 0) {
			tusi('请填写手机号码');
			$("#tel").focus();
			return false;
		}

		if (!checkMobile(tel)) {
			tusi("手机号码格式不正确");
			$("#tel").focus();
			return false;
		}

		if (province.length <= 0) {
			tusi("请选择省份");
			$("#province").focus();
			return false;
		}

		if (city.length <= 0) {
			tusi("请选择城市");
			$("#city").focus();
			return false;
		}

		if (area.length <= 0) {
			tusi("请选择地区");
			$("#area").focus();
			return false;
		}

		if (address.length < 10) {
			tusi('街道地址不能少于10个字');
			("#address").focus();
			return false;
		}

		if (address.length > 120) {
			tusi('街道地址不能多于120个字');
			("#address").focus();
			return false;
		}

		return true;
	}

	function changeArea(prov, city, area) {
		getProvinces('province', prov, '省份');
		getCitys('city', 'province', city, '城市');
		getAreas('area', 'city', area, '区县');

		$('#province').change(function () {
			if ($(this).val() != '') {
				getCitys('city', 'province', '', '城市');
			}
			else {
				$('#city').html('<option value="">城市</option>');
			}
			$('#area').html('<option value="">区县</option>');
		});
		$('#city').change(function () {
			if ($(this).val() != '') {
				getAreas('area', 'city', '', '区县');
			}
			else {
				$('#area').html('<option value="">区县</option>');
			}
		});
	}

	$(document).ready(function () {
		$('#form1').ajaxForm({
			beforeSubmit: showRequest,
			success: showResponse,
			dataType: 'json'
		});

		changeArea('', '', '');

		$('.js-delete').click(function () {
			var id = $(this).data('id');
			if (!confirm('确认删除？'))
				return false;

			var url = '<?php echo url('account:address_delete') ?>&id=' + id;
			$.getJSON(url, function (data) {
				showResponse(data);
			});
		});

		$('input#default').click(function () {
			var id = $(this).val();
			var url = '<?php echo url('account:address_default') ?>&id=' + id;
			$.getJSON(url, function (data) {
				showResponse(data);
			});
		})

		$(".js-edit").click(function () {
			$('a[href=#add]').text('编辑收货地址');
			$('.js-btn-submit').text('编辑收货地址');

			var obj = $(this).data('json');
			if (!obj.address_id) {
				return false;
			}
			$("#address_id").val(obj.address_id);
			$("#name").val(obj.name);
			$('#tel').val(obj.tel);
			$('#address').val(obj.address);
			$('#zipcode').val(obj.zipcode);
			$('#default').attr('checked', obj.default == 1);
			changeArea(obj.province, obj.city, obj.area);
		});
	});

</script>