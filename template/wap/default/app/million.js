function chk(point) {
	if(!/^\d+$/.test(point)) {
		alert('请输入投资积分数量！');
		return false;
	}
	point = parseInt(point);
	if(point < 1000) {
		alert('投资积分数量最少需要 1000 积分！');
		return false;
	}
	if(point % 100 > 0) {
		alert('投资积分数量请输入 1000 的倍数！');
		return false;
	}
	return true;
}
$(function(){
	$('#join').click(function(){
		var point = $('#point').val();
		if(!chk(point)) {
			return false;
		}
		if(!confirm('确认投资 '+point+' 积分参与百万大奖？')){
			return false;
		}
		$.post('./app_million.php?a=join',{point:point},function(data){
			if(data.status=='success') {
				alert('投资成功！赶紧分享给小伙伴们吧！');
				location.href = './app_million.php';
			} else {
				alert(data.msg);
				return false;
			}
		},'json');
	});
});