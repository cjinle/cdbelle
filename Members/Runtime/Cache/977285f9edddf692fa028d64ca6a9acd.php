<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>管理系统</title><link rel='stylesheet' type='text/css' href='__PUBLIC__/Css/common.css'></head><body class="right-bg"><div class="top-bar">			→ 欢迎<span style="color:red;"><?php echo ($_SESSION["user_name"]); ?></span>, （<span style="color:;"><?php echo ($_SESSION["priv_name"]); ?></span>）进入会员管理系统
			<span class="back-to-index"><img src="__PUBLIC__/Images/home.gif" style="line-height:25px;"/><a href="__ROOT__/index.php?m=Index&a=main">返回主首页</a></span></div><!--// .top-bar--><div class="r-content"><div class="r-content-title">加盟店添加</div><div class="r-content-main"><form action="__ROOT__/index.php?m=Franchise&a=insert" method="POST"><table width="700" border="0" cellpadding="5" cellspacing="5" style="margin:20px 0 20px 50px;"><?php if ($agents_list) : ?><tr><td align="right"><span class="mark">*</span>所属代理商：</td><td><select id="agents" name="agents"><option value="0">未选择代理商</option><?php if(is_array($agents_list)): foreach($agents_list as $key=>$item): ?><option value="<?php echo ($item["user_id"]); ?>" <?php if ($user_info['parent_id'] == $item['user_id']) { echo 'selected';} ?>><?php echo ($item["user_name"]); ?> - <?php echo ($item["real_name"]); ?></option><?php endforeach; endif; ?></select>&nbsp;
						<span id="Required_Agents" style="color:red;visibility:hidden;">请选择属的代理商</span></td></tr><?php else : ?><input type="hidden" id="agents" name="agents" value="<?php echo session('user_id'); ?>" /><?php endif; ?><tr><td align="right"><span class="mark">*</span>用户名：</td><td><input type="text" name="user_name" id="user_name" class="readonly" value="<?php echo $franchise_name; ?>" readonly="readonly" />&nbsp;
						<span id="Required_UserName" style="color:red;visibility:visible">用户名在选择代理商时自动生成</span></td></tr><tr><td align="right"><span class="mark">*</span>加盟店名称：</td><td><input type="text" name="real_name" value="" />&nbsp;
					    <span id="Required_RealName" style="color:red;visibility:hidden;">加盟店名称不能为空</span></td></tr><tr><td align="right">邮件：</td><td><input type="text" name="email" value="" /></td></tr><tr><td align="right">QQ：</td><td><input type="text" name="qq" value="" /></td></tr><tr><td align="right">联系电话：</td><td><input type="text" name="mobile_phone" value="" /></td></tr><tr><td align="right">地区：</td><td><select name="province" id="province"><option value="0">请选择</option><?php if(is_array($province_list)): foreach($province_list as $key=>$item): ?><option value="<?php echo ($item["region_id"]); ?>"><?php echo ($item["region_name"]); ?></option><?php endforeach; endif; ?></select><select name="city" id="city"><option value="0">请选择</option></select><select name="district" id="district"><option value="0">请选择</option></select></td></tr><tr><td align="right">地址：</td><td><input type="text" name="address" value="" /></td></tr><tr><td align="right"><span class="mark">*</span>密码：</td><td><input type="password" name="new_pwd" value="" />&nbsp;
						<span id="Required_NewPwd" style="color: red;visibility: hidden;">密码不能少于6位</span></td></tr><tr><td align="right"><span class="mark">*</span>确认密码：</td><td><input type="password" name="confirm_new_pwd" value="" />&nbsp;
						<span id="Required_ConfirmNewPwd" style="color: red;visibility: hidden;">密码与确认密码不一致</span></td></tr><tr><td align="right"><input type="submit" value="提交" /></td><td><input type="button" value="返回" onclick="javascript:history.go(-1);" /></td></tr></table></form></div><!--// .r-content-main--></div><!--// .r-content--><script src="__PUBLIC__/Js/jquery.js" type="text/javascript"></script><script type="text/javascript">$(document).ready(function(){
	/*$("a#check_user_name").click(function(){
		if ($("input#user_name").val() == "") {
			$("span.alert_msg").css('color', 'red');
			$("span.alert_msg").html("用户名不能为空！");
			$("span.alert_msg").show();
			return false;
		}
		$("img#loading_icon").show();
		$.get(
			'__ROOT__/index.php?m=Ajax&a=check_user_name',
			'user_name=' + $("input#user_name").val(),
			function(data){
				if (data == 0) {
					$("span.alert_msg").css('color', 'green');
					$("span.alert_msg").html("用户名可用！");
					$("span.alert_msg").show();
				}
				else if (data > 0) {
					$("span.alert_msg").css('color', 'red');
					$("span.alert_msg").html("用户名不可用！");
					$("span.alert_msg").show();
				}
				$("img#loading_icon").hide();
			}
		);	
	});*/
	$("form").submit(function(){
		var msg = '';
		var pwd = $("input[name='new_pwd']").val();
		if ($("input[name='user_name']").val() == "") {
			$("#Required_UserName").html("请选择所属的代理商");
			$("#Required_UserName").css("visibility", "visible");
			$("#Required_Agents").css("visibility", "visible");
		} else {
			$("#Required_UserName").css("visibility", "hidden");
			$("#Required_Agents").css("visibility", "hidden");	
		}
		if ($("input[name='real_name']").val() == "") {
			$("#Required_RealName").css("visibility", "visible");
		} else {
			$("#Required_RealName").css("visibility", "hidden");
		}
		if (pwd.length < 6) {
			$("#Required_NewPwd").css("visibility", "visible");
		} else {
			$("#Required_NewPwd").css("visibility", "hidden");
		}
		if ($("input[name='new_pwd']").val() != $("input[name='confirm_new_pwd']").val()) {
			$("#Required_ConfirmNewPwd").css("visibility", "visible");
		} else {
			$("#Required_ConfirmNewPwd").css("visibility", "hidden");
		}
		if (($("input[name='user_name']").val() == "")||($("input[name='real_name']").val() == "")||
			(pwd.length < 6)||($("input[name='new_pwd']").val() != $("input[name='confirm_new_pwd']").val())) {
			return false;
		}
		
	});
	$("#agents").change(function(){
		$.get(
			'__ROOT__/index.php?m=Ajax&a=create_next_username',
			'user_priv=3&parent_id=' + this.value,
			function(data){
				$("input#user_name").val(data);		
			}
		);
	});
	$("#province").change(function(){
		$.get(
			'__ROOT__/index.php?m=Ajax&a=get_region',
			'region_type=2&parent_id=' + this.value,
			function(data){
				$("#city").html(data);	
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