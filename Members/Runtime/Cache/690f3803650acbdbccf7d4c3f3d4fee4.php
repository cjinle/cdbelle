<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>管理系统</title><link rel='stylesheet' type='text/css' href='__PUBLIC__/Css/common.css'></head><body class="right-bg"><div class="top-bar">			→ 欢迎<span style="color:red;"><?php echo ($_SESSION["user_name"]); ?></span>, （<span style="color:;"><?php echo ($_SESSION["priv_name"]); ?></span>）进入会员管理系统
			<span class="back-to-index"><img src="__PUBLIC__/Images/home.gif" style="line-height:25px;"/><a href="__ROOT__/index.php?m=Index&a=main">返回主首页</a></span></div><!--// .top-bar--><div class="r-content"><div class="r-content-title">会员编辑</div><div class="r-content-main"><table width="700" border="0" cellpadding="5" cellspacing="5" style="margin:20px 0 20px 50px;"><tr><td align="right">会员ID：</td><td><?php echo ($user_info["user_id"]); ?></td></tr><tr><td align="right">用户名：</td><td><?php echo ($user_info["user_name"]); ?></td></tr><tr><td align="right">用户别名：</td><td><?php echo ($user_info["real_name"]); ?></td></tr><tr><td align="right">邮件：</td><td><?php echo ($user_info["email"]); ?></td></tr><tr><td align="right">QQ：</td><td><?php echo ($user_info["qq"]); ?></td></tr><tr><td align="right">联系电话：</td><td><?php echo ($user_info["mobile_phone"]); ?></td></tr><tr><td align="right">注册时间：</td><td><?php echo ($user_info["reg_time"]); ?></td></tr><tr><td align="right">角色：</td><td><?php echo ($user_info["user_priv"]); ?></td></tr><tr><td align="right">绑定卡号：</td><td><?php if ($user_info['card_no']) : ?><span style="color:red;"><?php echo $user_info['card_no']; ?></span><?php endif; ?></td></tr><?php if ($franchise_list) : ?><tr><td align="right">所属加盟店：</td><td><?php echo ($user_info["parent"]); ?></td></tr><?php endif; ?><tr><td align="right">状态：</td><td><?php echo ($user_info["status"]); ?></td></tr><tr><td align="right">地区：</td><td><?php echo ($user_address['province']); ?> - <?php echo ($user_address['city']); ?> - <?php echo ($user_address['district']); ?></td></tr><tr><td align="right">地址：</td><td><?php echo ($user_address["address"]); ?></td></tr><tr><td align="right"></td><td><input type="button" value="返回" onclick="javascript:history.go(-1);" /></td></tr></table></div><!--// .r-content-main--></div><!--// .r-content--><script src="__PUBLIC__/Js/jquery.js" type="text/javascript"></script><script type="text/javascript">$(document).ready(function(){
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