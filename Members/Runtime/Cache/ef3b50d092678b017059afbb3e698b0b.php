<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>管理系统</title><link rel='stylesheet' type='text/css' href='__PUBLIC__/Css/common.css'></head><body class="right-bg"><div class="top-bar">			→ 欢迎<span style="color:red;"><?php echo ($_SESSION["user_name"]); ?></span>, （<span style="color:;"><?php echo ($_SESSION["priv_name"]); ?></span>）进入会员管理系统
			<span class="back-to-index"><img src="__PUBLIC__/Images/home.gif" style="line-height:25px;"/><a href="__ROOT__/index.php?m=Index&a=main">返回主首页</a></span></div><!--// .top-bar--><script type="text/javascript"><!--
	/* window.UEDITOR_HOME_URL = location.pathname.substr(0, location.pathname.lastIndexOf('/')) + '/'; */
	window.UEDITOR_HOME_URL = "__PUBLIC__/Js/ueditor/";
//--></script><script type="text/javascript" src="__PUBLIC__/Js/ueditor/editor_config.js"></script><script type="text/javascript" src="__PUBLIC__/Js/ueditor/editor.min.js"></script><script language="javascript" type="text/javascript" src="__PUBLIC__/Js/My97DatePicker/WdatePicker.js"></script><link rel="stylesheet" href="__PUBLIC__/Js/ueditor/themes/default/ueditor.css" /><div class="r-content"><div class="r-content-title">优惠添加</div><div class="r-content-main"><form action="__ROOT__/index.php?m=Activity&a=insert" method="POST"><table width="900" border="0" cellpadding="5" cellspacing="5" style="margin:20px 0 20px 50px;"><tr><td align="right" width="100">优惠标题：</td><td><input type="text" name="title" value="" style="width:300px;" /></td></tr><tr><td align="right">是否公开：</td><td><input type="radio" name="is_open" value="1" checked="checked">是&nbsp;
						<input type="radio" name="is_open" value="0">否
					</td></tr><tr><td align="right">是否留地址：</td><td><input type="radio" name="act_type" value="1" checked="checked">是&nbsp;
						<input type="radio" name="act_type" value="0">否
					</td></tr><tr><td align="right">起止日期：</td><td><input type="text" name="start_date" value="" onClick="WdatePicker()" class="Wdate" /> ~
						<input type="text" name="end_date" value="" onClick="WdatePicker()" class="Wdate" /></td></tr><tr><td align="right">内容：</td><td><script type="text/plain" id="editor" name="content" style="width:600px"></script><script type="text/javascript">						    var editor = new UE.ui.Editor();
						    editor.render('editor');
						</script></td></tr><tr><td align="right"><input type="submit" value="提交" /></td><td><input type="button" value="返回" onclick="javascript:history.go(-1);" /></td></tr></table></form></div><!--// .r-content-main--></div><!--// .r-content--><script src="__PUBLIC__/Js/jquery.js" type="text/javascript"></script><script type="text/javascript">$(document).ready(function(){
	$("form").submit(function(){
		var msg = '';
		if ($("input[name='title']").val() == "") {
			msg += "标题不能为空\n";
		}
		if ($("input[name='start_date']").val() == "") {
			msg += "没有选择开始日期\n";
		}
		if ($("input[name='end_date']").val() == "") {
			msg += "没有选择结束日期\n";
		}
		if (msg != "") {
			alert(msg);
			return false;
		}
		
	});
});
</script><div class="copyright">&copy; Copyright 2010 Your Company </div></body></html>