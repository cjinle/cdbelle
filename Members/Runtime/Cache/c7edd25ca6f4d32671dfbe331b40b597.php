<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>管理系统</title><link rel='stylesheet' type='text/css' href='__PUBLIC__/Css/common.css'></head><body class="right-bg"><div class="top-bar">			→ 欢迎<span style="color:red;"><?php echo ($_SESSION["user_name"]); ?></span>, （<span style="color:;"><?php echo ($_SESSION["priv_name"]); ?></span>）进入会员管理系统
			<span class="back-to-index"><img src="__PUBLIC__/Images/home.gif" style="line-height:25px;"/><a href="__ROOT__/index.php?m=Index&a=main">返回主首页</a></span></div><!--// .top-bar--><script type="text/javascript"><!--
	/* window.UEDITOR_HOME_URL = location.pathname.substr(0, location.pathname.lastIndexOf('/')) + '/'; */
	window.UEDITOR_HOME_URL = "__PUBLIC__/Js/ueditor/";
//--></script><script type="text/javascript" src="__PUBLIC__/Js/ueditor/editor_config.js"></script><script type="text/javascript" src="__PUBLIC__/Js/ueditor/editor.min.js"></script><script language="javascript" type="text/javascript" src="__PUBLIC__/Js/My97DatePicker/WdatePicker.js"></script><link rel="stylesheet" href="__PUBLIC__/Js/ueditor/themes/default/ueditor.css" /><div class="r-content"><div class="r-content-title">领取优惠</div><div class="r-content-main"><form action="__ROOT__/index.php?m=Activity&a=doReceive" method="POST"><table width="900" border="0" cellpadding="5" cellspacing="5" style="margin:20px 0 20px 50px;"><tr><td align="right" width="100">优惠标题：</td><td><?php echo ($activity_info["title"]); ?></td></tr><tr><td align="right">是否公开：</td><td><?php echo ($activity_info["is_open"]); ?></td></tr><tr><td align="right">是否留地址：</td><td><?php echo ($activity_info["act_type"]); ?></td></tr><tr><td align="right">起止日期：</td><td><?php echo ($activity_info["start_date"]); ?> ~
						<?php echo ($activity_info["end_date"]); ?></td></tr><tr><td align="right">内容：</td><td><?php echo ($activity_info["content"]); ?></td></tr><tr><td align="right">地址：</td><td><select name="province" id="province"><option value="0">请选择</option><?php if(is_array($province_list)): foreach($province_list as $key=>$item): ?><option value="<?php echo ($item["region_id"]); ?>"><?php echo ($item["region_name"]); ?></option><?php endforeach; endif; ?></select><select name="city" id="city"><option value="0">请选择</option></select><select name="district" id="district"><option value="0">请选择</option></select><input type="text" style="width:300px;" name="address" value="" /></td></tr><tr><td align="right"><input type="hidden" name="aid" value="<?php echo ($activity_info["aid"]); ?>" /><input type="submit" value="提交" /></td><td><input type="button" value="返回" onclick="javascript:history.go(-1);" /></td></tr></table></form></div><!--// .r-content-main--></div><!--// .r-content--><script src="__PUBLIC__/Js/jquery.js" type="text/javascript"></script><script type="text/javascript">$(document).ready(function(){
	$("form").submit(function(){
		if ($("input[name='address']").val() == "") {
			alert("地址不能为空");
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
});
</script><div class="copyright">&copy; Copyright 2010 Your Company </div></body></html>