<?php include display('public:person_header'); ?>
<script>
	$(document).ready(function () {
		$('#profile').ajaxForm({
			beforeSubmit: showRequest,
			success: showResponse,
			dataType: 'json'
		});

	});

	function showRequest() {
		var nickname = $("#nickname").val();

		if (nickname.length == 0) {
			tusi("请填写昵称");
			$("#nickname").focus();
			return false;
		}

		return true;
	}
</script>
<!------------个人资料-------------->

<div style=" background:#da0b00;">
<li><a href="#"><img src="static/images/kaidian.png"></a></li>
</div>

<!------------个人资料-------------------->
<?php include display('public:person_footer'); ?>