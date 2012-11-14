<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>管理系统</title><link rel='stylesheet' type='text/css' href='__PUBLIC__/Css/common.css'></head><body class="right-bg"><div class="top-bar">			→ 欢迎<span style="color:red;"><?php echo ($_SESSION["user_name"]); ?></span>, （<span style="color:;"><?php echo ($_SESSION["priv_name"]); ?></span>）进入会员管理系统
			<span class="back-to-index"><img src="__PUBLIC__/Images/home.gif" style="line-height:25px;"/><a href="__ROOT__/index.php?m=Index&a=main">返回主首页</a></span></div><!--// .top-bar--><div class="r-content"><div class="r-content-title">返点设置</div><div class="r-content-main"><form action="__ROOT__/index.php?m=Sales&a=setConfig" method="POST"><table width="730" border="0" cellpadding="5" cellspacing="5" style="margin:20px 0 20px 50px;"><tr><td colspan="2" align="center" style="color:green;">提示：下面填写的数字范围是：0 &lt;= 返点 < 1, 如果要给代理商返点<span class="mark">15%</span>, 则应该填写：<span class="mark">0.15</span>, 如果没有返点，则设置为<span class="mark">0</span></td></tr><tr><td align="right">对代理商的返点：</td><td><input type="text" name="to_agents" value="<?php echo ($config["to_agents"]); ?>" />&nbsp;
							<span id="to_agents_alert" style="color:red;visibility:hidden;">请填写正确的小数</span></td></tr><tr><td align="right">对加盟店的返点：</td><td><input type="text" name="to_franchise" value="<?php echo ($config["to_franchise"]); ?>" />&nbsp;
							<span id="to_franchise_alert" style="color:red;visibility:hidden;">请填写正确的小数</span></td></tr><tr><td align="right"><input type="submit" value="提交" /></td><td><input type="button" value="返回" onclick="javscript:history.go(-1)" /></td></tr></table></form></div><!--// .r-content-main--></div><!--// .r-content--><script src="__PUBLIC__/Js/jquery.js" type="text/javascript"></script><script type="text/javascript">$(document).ready(function(){
//	$('form').submit(function(){
//		var to_agents = $("input[name='to_agents']").val();
//		var to_franchise = $("input[name='to_franchise']").val();
//		if ((to_agents<1)&&(to_agents>=0)) {
//			$("#to_agents_alert").css("visibility", "hidden");	
//		} else {
//			$("#to_agents_alert").css("visibility", "visible");
//			return false;
//		}
//		if ((to_franchise<1)&&(to_franchise>=0)) {
//			$("#to_franchise_alert").css("visibility", "hidden");
//		} else {
//			$("#to_franchise_alert").css("visibility", "visible");
//			return false;	
//		}	
//		if (parseFloat(to_agents)+parseFloat(to_franchise)>1) {
//			alert("加起来的返点已经超过1");
//			return false;
//		}
//	});
});
</script><div class="copyright">&copy; Copyright 2010 Your Company </div></body></html>