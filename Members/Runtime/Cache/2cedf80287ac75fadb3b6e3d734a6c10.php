<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>管理系统</title><link rel='stylesheet' type='text/css' href='__PUBLIC__/Css/common.css'></head><body class="right-bg"><div class="top-bar">			→ 欢迎<span style="color:red;"><?php echo ($_SESSION["user_name"]); ?></span>, （<span style="color:;"><?php echo ($_SESSION["priv_name"]); ?></span>）进入会员管理系统
			<span class="back-to-index"><img src="__PUBLIC__/Images/home.gif" style="line-height:25px;"/><a href="__ROOT__/index.php?m=Index&a=main">返回主首页</a></span></div><!--// .top-bar--><div class="r-content"><div class="r-content-title">会员添加</div><div class="r-content-main"><form action="__ROOT__/index.php?m=Users&a=insert" method="POST"><table width="700" border="0" cellpadding="5" cellspacing="5" style="margin:20px 0 20px 50px;"><tr><td align="right"><span class="mark">*</span>用户名：</td><td><input type="text" name="user_name" id="user_name" value="" /><a href="javascript:void(0)" id="check_user_name">检测用户名</a><img src="__PUBLIC__/Images/loading2.gif" id="loading_icon" style="display:none;" /><span class="alert_msg" style="display:none;margin:0 5px;color:red;"></span></td></tr><tr><td align="right"><span class="mark">*</span>用户别名：</td><td><input type="text" name="real_name" value="" /></td></tr><tr><td align="right"><span class="mark">*</span>邮件：</td><td><input type="text" name="email" value="" /></td></tr><tr><td align="right">QQ：</td><td><input type="text" name="qq" value="" /></td></tr><tr><td align="right">联系电话：</td><td><input type="text" name="mobile_phone" value="" /></td></tr><tr><td align="right">绑定卡号：</td><td><input type="text" name="card_no" value="" /><a href="javascript:void(0)" id="check_card_no">验证卡号</a><img src="__PUBLIC__/Images/loading2.gif" id="loading_icon2" style="display:none;" /><span class="alert_msg2" style="display:none;margin:0 5px;color:red;"></span></td></tr><?php if ($franchise_list) : ?><tr><td align="right">所属加盟店：</td><td><select name="franchise"><option value="0">未选择加盟店</option><?php if(is_array($franchise_list)): foreach($franchise_list as $key=>$item): ?><option value="<?php echo ($item["user_id"]); ?>" <?php if ($user_info['parent_id'] == $item['user_id']) { echo 'selected';} ?>><?php echo ($item["real_name"]); ?> - <?php echo ($item["user_name"]); ?></option><?php endforeach; endif; ?></select></td></tr><?php endif; ?><tr><td align="right">地区：</td><td><select name="province" id="province"><option value="0">请选择</option><?php if(is_array($province_list)): foreach($province_list as $key=>$item): ?><option value="<?php echo ($item["region_id"]); ?>"><?php echo ($item["region_name"]); ?></option><?php endforeach; endif; ?></select><select name="city" id="city"><option value="0">请选择</option></select><select name="district" id="district"><option value="0">请选择</option></select></td></tr><tr><td align="right">地址：</td><td><input type="text" name="address" value="" /></td></tr><tr><td align="right"><span class="mark">*</span>密码：</td><td><input type="password" name="new_pwd" value="" /></td></tr><tr><td align="right"><span class="mark">*</span>确认密码：</td><td><input type="password" name="confirm_new_pwd" value="" /></td></tr><tr><td align="right"><input type="submit" value="提交" /></td><td><input type="button" value="返回" onclick="javascript:history.go(-1);" /></td></tr></table></form></div><!--// .r-content-main--></div><!--// .r-content--><script src="__PUBLIC__/Js/jquery.js" type="text/javascript"></script><script type="text/javascript">$(document).ready(function(){
	$("a#check_user_name").click(function(){
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
	});
	$("form").submit(function(){
		var msg = '';
		var pwd = $("input[name='new_pwd']").val();
		if ($("input[name='user_name']").val() == "") {
			msg += "用户不能为空\n";
		}
		if ($("input[name='real_name']").val() == "") {
			msg += "用户别名不能为空\n";
		}
		if ($("input[name='email']").val() == "") {
			msg += "邮件不能为空\n";
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
	$("a#check_card_no").click(function(){
		if ($("input[name='card_no']").val() == "") {
			$("span.alert_msg2").css('color', 'red');
			$("span.alert_msg2").html("卡号不能为空！");
			$("span.alert_msg2").show();
			return false;
		}
		$("img#loading_icon2").show();
		$.get(
			'__ROOT__/index.php?m=Ajax&a=check_card_no',
			'card_no=' + $("input[name='card_no']").val(),
			function(data){
				if (data == 0) {
					$("span.alert_msg2").css('color', 'green');
					$("span.alert_msg2").html("卡号可用！");
					$("span.alert_msg2").show();
				}
				else if (data == 1) {
					$("span.alert_msg2").css('color', 'red');
					$("span.alert_msg2").html("卡号已绑定其它会员！");
					$("span.alert_msg2").show();
				}
				else if (data == 2){
					$("span.alert_msg2").css('color', 'red');
					$("span.alert_msg2").html("卡号不存在！");
					$("span.alert_msg2").show();	
				}
				$("img#loading_icon2").hide();
			}
		);	
	});
});
</script><div class="copyright">&copy; Copyright 2010 Your Company </div></body></html>