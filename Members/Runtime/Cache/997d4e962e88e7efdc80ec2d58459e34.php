<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><title>会员登录</title><meta http-equiv=content-type content="text/html; charset=utf-8"><link rel='stylesheet' type='text/css' href='__PUBLIC__/Css/login2.css'></head><body><body><div style="width:100%;"><div align="center"><form action="__ROOT__/index.php?m=Login&a=doLogin" method="POST"><div id="main"><div id="heading">				登录会员系统 &nbsp;
				<a href="__ROOT__/index.php?m=Register" title="立即注册">立即注册</a></div><label class="my_label">用户名</label><div class="line"><input type="text" name="user_name" value="" class="textbox" />&nbsp;
				<a href="#" tabindex="-1">找回用户名</a></div><span id="Required_UserName" style="color: red; visibility: hidden; ">用户名不能为空</span><label class="my_label">密码</label><div class="line"><input type="password" name="password" value="" class="textbox" />&nbsp;
				<a href="#" tabindex="-2">找回密码</a></div><span id="Required＿Password" style="color:red; visibility:hidden; ">密码不能为空</span><div><span class="chk"><input id="chkRemember" type="checkbox" name="chkRemember"><label for="chkRemember">保存密码</label></span><br /><input type="submit" name="btnLogin" value="登  录" id="btnLogin" class="Button" style="margin-top: 8px">　
				<a id="lnkReturnUrl" href="#">返 回</a></div><div style="line-height:1.8em;margin-top:10px;">            » <a href="__ROOT__/index.php?m=Register" title="注册成为用户"><b>立即注册</b></a><br>            » <a href="#">联系我们</a><br>			» <a href="../">网站首页</a></div></div><!--// #main--></form></div></div><script src="__PUBLIC__/Js/jquery.js" type="text/javascript"></script><script type="text/javascript">$(document).ready(function(){
	$("form").submit(function(){
		var user_name = $("input[name='user_name']").val();
		var password = $("input[name='password']").val();
		if (user_name == '')
		{
			$("#Required_UserName").css("visibility", "visible");
		} else {
			$("#Required_UserName").css("visibility", "hidden");
		}
		if (password == '') 
		{
			$("#Required＿Password").css("visibility", "visible");
		} else {
			$("#Required＿Password").css("visibility", "hidden");
		}
		if (user_name == '' || password == '') 
		{
			return false;
		}
		
	});	
});
</script></body></html>