<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><title>用户登录</title><link href="__PUBLIC__/Css/Login/login.css" type=text/css rel=stylesheet><meta http-equiv=content-type content="text/html; charset=utf-8"></head><body id=userlogin_body><div></div><div id=user_login><form action="__ROOT__/index.php?m=Login&a=doLogin" method="POST"><dl><dd id=user_top><ul><li class=user_top_l></li><li class=user_top_c></li><li class=user_top_r></li></ul><dd id=user_main><ul><li class=user_main_l></li><li class=user_main_c><div class=user_main_box><ul><li class=user_main_text>用户名： </li><li class=user_main_input><input class=txtusernamecssclass id=txtusername maxlength=20 name="user_name" /></li></ul><ul><li class=user_main_text>密 码： </li><li class=user_main_input><input class=txtpasswordcssclass id=txtpassword type=password name="password"></li></ul><ul><li class=user_main_text>cookie： </li><li class=user_main_input><select id=dropexpiration name="expiration"><option value="0" selected>不保存</option><option value="1">保存一天</option><option value="2">保存一月</option><option value="3">保存一年</option></select></li></ul><ul><li style="margin-top:5px;"><a href="__ROOT__/index.php?m=Register" title="注册会员" class="register_link">注册会员</a><a href="__ROOT__/index.php?m=Register&a=forgot_pwd" title="忘记密码？" class="forget_pwd_link">忘记密码？</a></li></ul></div></li><li class=user_main_r><input class=ibtnentercssclass id=ibtnenter style="border-top-width: 0px; border-left-width: 0px; border-bottom-width: 0px; border-right-width: 0px" type=image src="__PUBLIC__/Images/Login/user_botton.gif" name=ibtnenter></li></ul><dd id=user_bottom><ul><li class=user_bottom_l></li><li class=user_bottom_c></li><li class=user_bottom_r></li></ul></dd></dl></div></form><div></div><script src="__PUBLIC__/Js/jquery.js" type="text/javascript"></script><script type="text/javascript">$(document).ready(function(){
	$('#ibtnenter').click(function(){
		var user_name = $('#txtusername').val();
		var password = $('#txtpassword').val();
		var err_msg = '';
		if (user_name == '')
		{
			err_msg += "用户名不能为空\n";	
		}
		if (password == '') 
		{
			err_msg += "密码不能为空";
		}
		if (err_msg != '') 
		{
			alert(err_msg);
			return false;
		}
		
	});	
});
</script></body></html>