<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>管理系统</title><link rel='stylesheet' type='text/css' href='__PUBLIC__/Css/common.css'></head><body class="right-bg"><div class="top-bar">			→ 欢迎<span style="color:red;"><?php echo ($_SESSION["user_name"]); ?></span>, （<span style="color:;"><?php echo ($_SESSION["priv_name"]); ?></span>）进入会员管理系统
			<span class="back-to-index"><img src="__PUBLIC__/Images/home.gif" style="line-height:25px;"/><a href="__ROOT__/index.php?m=Index&a=main">返回主首页</a></span></div><!--// .top-bar--><div class="r-content"><div class="r-content-title">代理商编辑</div><div class="r-content-main"><form action="__ROOT__/index.php?m=Agents&a=update" method="POST"><table width="700" border="0" cellpadding="5" cellspacing="5" style="margin:20px 0 20px 50px;"><tr><td align="right">代理商ID：</td><td><?php echo ($user_info["user_id"]); ?></td></tr><tr><td align="right">用户名：</td><td><?php echo ($user_info["user_name"]); ?></td></tr><tr><td align="right">代理商名：</td><td><input type="text" name="real_name" value="<?php echo ($user_info["real_name"]); ?>" /></td></tr><tr><td align="right">邮件：</td><td><input type="text" name="user_name" value="<?php echo ($user_info["email"]); ?>" /></td></tr><tr><td align="right">QQ：</td><td><input type="text" name="qq" value="<?php echo ($user_info["qq"]); ?>" /></td></tr><tr><td align="right">联系电话：</td><td><input type="text" name="mobile_phone" value="<?php echo ($user_info["mobile_phone"]); ?>" /></td></tr><tr><td align="right">注册时间：</td><td><?php echo ($user_info["reg_time"]); ?></td></tr><tr><td align="right">角色：</td><td><?php echo ($user_info["user_priv"]); ?></td></tr><tr><td align="right">状态：</td><td><?php echo ($user_info["status"]); ?></td></tr><tr><td align="right">地区：</td><td><select name="province" id="province"><option value="0">请选择</option><?php if(is_array($province_list)): foreach($province_list as $key=>$item): ?><option value="<?php echo ($item["region_id"]); ?>" <?php if ($user_address['province_id'] == $item['region_id']) {echo 'selected';} ?>><?php echo ($item["region_name"]); ?></option><?php endforeach; endif; ?></select><select name="city" id="city"><option value="0">请选择</option><?php if(is_array($city_list)): foreach($city_list as $key=>$item): ?><option value="<?php echo ($item["region_id"]); ?>" <?php if ($user_address['city_id'] == $item['region_id']) {echo 'selected';} ?>><?php echo ($item["region_name"]); ?></option><?php endforeach; endif; ?></select><select name="district" id="district"><option value="0">请选择</option><?php if(is_array($district_list)): foreach($district_list as $key=>$item): ?><option value="<?php echo ($item["region_id"]); ?>" <?php if ($user_address['district_id'] == $item['region_id']) {echo 'selected';} ?>><?php echo ($item["region_name"]); ?></option><?php endforeach; endif; ?></select></td></tr><tr><td align="right">地址：</td><td><input type="text" name="address" value="<?php echo ($user_address["address"]); ?>" /></td></tr><tr><td align="right">新密码：</td><td><input type="password" name="new_pwd" value="" /></td></tr><tr><td align="right">确认新密码：</td><td><input type="password" name="confirm_new_pwd" value="" /></td></tr><tr><td align="right"><input type="hidden" name="user_id" value="<?php echo ($user_info["user_id"]); ?>" /><input type="submit" value="提交" /></td><td><input type="button" value="返回" onclick="javascript:history.go(-1);" /></td></tr></table></form></div><!--// .r-content-main--></div><!--// .r-content--><script src="__PUBLIC__/Js/jquery.js" type="text/javascript"></script><script type="text/javascript">$(document).ready(function(){
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