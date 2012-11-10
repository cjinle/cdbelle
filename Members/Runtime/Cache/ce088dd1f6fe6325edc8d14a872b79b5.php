<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>管理系统</title><link rel='stylesheet' type='text/css' href='__PUBLIC__/Css/common.css'></head><body class="right-bg"><div class="top-bar">			→ 欢迎<span style="color:red;"><?php echo ($_SESSION["user_name"]); ?></span>, （<span style="color:;"><?php echo ($_SESSION["priv_name"]); ?></span>）进入会员管理系统
			<span class="back-to-index"><img src="__PUBLIC__/Images/home.gif" style="line-height:25px;"/><a href="__ROOT__/index.php?m=Index&a=main">返回主首页</a></span></div><!--// .top-bar--><div class="r-content"><div class="r-content-title">会员卡号上传</div><div class="r-content-main"><form action="__ROOT__/index.php?m=Card&a=insert" method="POST" id="form1"><fieldset><legend>单个卡号上传</legend><div><img src="__PUBLIC__/Images/add.gif" />卡号：<input name="card_no" id="s_card_no" value="" /><input type="submit" value="提交" /><input type="hidden" name="upload_type" value="1" /></div></fieldset></form><form action="__ROOT__/index.php?m=Card&a=insert" method="POST" id="form2"><fieldset><legend>批量卡号上传</legend><div><textarea style="height:226px;width:340px;" id="m_card_no" name="card_no"></textarea><br /><input type="submit" value="提交" /><input type="hidden" name="upload_type" value="2" /></div></fieldset></form></div><!--// .r-content-main--></div><!--// .r-content--><script src="__PUBLIC__/Js/jquery.js" type="text/javascript"></script><script type="text/javascript">$(document).ready(function(){
	$("#form1").submit(function(){
		if ($("#s_card_no").val() == "") {
			alert("卡号不能为空！");
			return false;
		}
	});
	$("#form2").submit(function(){
		if ($("#m_card_no").val() == "") {
			alert("卡号不能为空！");
			return false;
		}
	});
});
</script><div class="copyright">&copy; Copyright 2010 Your Company </div></body></html>