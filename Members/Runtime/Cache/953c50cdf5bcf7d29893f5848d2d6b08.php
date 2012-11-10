<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>管理系统</title><link rel='stylesheet' type='text/css' href='__PUBLIC__/Css/common.css'><script language="javascript" type="text/javascript" src="__PUBLIC__/Js/My97DatePicker/WdatePicker.js"></script></head><body class="right-bg"><div class="r-content" style="width:800px;margin:50px auto 0;"><div class="r-content-title">代理商注册</div><div class="r-content-main"><div style="color:blue;margin:5px;"><a href="__ROOT__/index.php?m=Register&a=index">&lt;&lt;返回上一页面</a></div><form action="__ROOT__/index.php?m=Register&a=doAgentRegister" method="POST"><table width="700" border="0" cellpadding="5" cellspacing="5" style="margin:50px;"><tr><td align="right" width="280"><span class="mark">*</span>登录名：</td><td><input type="text" name="user_name" id="user_name" value="" readonly="readonly" class="readonly" /><span class="tips">当选择所属地区时，登录名由系统自动生成。</span></td></tr><tr><td align="right"><span class="mark">*</span>姓名：</td><td><input type="text" name="real_name" value="" /></td></tr><tr><td align="right"><span class="mark">*</span>邮件：</td><td><input type="text" name="email" value="" /></td></tr><tr><td align="right">QQ：</td><td><input type="text" name="qq" value="" /></td></tr><tr><td align="right">联系电话：</td><td><input type="text" name="mobile_phone" value="" /></td></tr><tr><td align="right"><span class="mark">*</span>地区：</td><td><select name="province" id="province"><option value="0">请选择</option><?php if(is_array($province_list)): foreach($province_list as $key=>$item): ?><option value="<?php echo ($item["region_id"]); ?>"><?php echo ($item["region_name"]); ?></option><?php endforeach; endif; ?></select><select name="city" id="city"><option value="0">请选择</option></select><select name="district" id="district"><option value="0">请选择</option></select></td></tr><tr><td align="right">地址：</td><td><input type="text" name="address" value="" /></td></tr><tr><td align="right"><span class="mark">*</span>密码：</td><td><input type="password" name="new_pwd" value="" /></td></tr><tr><td align="right"><span class="mark">*</span>确认密码：</td><td><input type="password" name="confirm_new_pwd" value="" /></td></tr><tr><td align="right"><input type="submit" value="注册" /></td><td><input type="button" value="返回" onclick="javascript:history.go(-1)" /></td></tr></table></form></div><!--// .r-content-main--></div><!--// .r-content--><script src="__PUBLIC__/Js/jquery.js" type="text/javascript"></script><script type="text/javascript">$(document).ready(function(){
	$("form").submit(function(){
		var msg = '';
		var pwd = $("input[name='new_pwd']").val();
		if ($("input[name='real_name']").val() == "") {
			msg += "名字不能为空\n";
		}
		if ($("input[name='email']").val() == "") {
			msg += "邮件不能为空\n";
		}
		if ($("input[name='user_name']").val() == "") {
			msg += "必须选择所属地区\n";
		}
		if (pwd.length < 6) {
			msg += "密码不能少于6位\n";
		}
		if ($("input[name='new_pwd']").val() != $("input[name='confirm_new_pwd']").val()) {
			msg += "密码与确认密码不一致\n";
		}
		if (msg != "") {
			alert(msg);
			return false;
		}
		
	});
	$("#province").change(function(){
		$.get(
			'__ROOT__/index.php?m=Ajax&a=get_region',
			'region_type=2&parent_id=' + this.value,
			function(data){
				$("#city").html(data);	
			}
		);
		$.get(
			'__ROOT__/index.php?m=Ajax&a=create_agent_username',
			'user_priv=2&region_id=' + this.value,
			function(data){
				$("input#user_name").val(data);		
			}
		);
	});
	$("#city").change(function(){
		$.get(
			'__ROOT__/index.php?m=Ajax&a=get_region',
			'region_type=3&parent_id=' + this.value,
			function(data){
				$("#district").html(data);	
			}
		);
	});
});
</script><div class="copyright">&copy; Copyright 2010 Your Company </div></body></html>