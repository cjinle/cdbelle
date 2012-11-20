<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>管理系统</title><link rel='stylesheet' type='text/css' href='__PUBLIC__/Css/register.css'></head><body><div style="width:100%;"><div align="center"><form action="__ROOT__/index.php?m=Register&a=doAgentRegister" method="POST"><div id="main" style="padding-left:90px;padding-top:50px;height:480px;display:block;"><div id="heading">				代理商注册 &nbsp;
				<a href="__ROOT__/index.php?m=Register" title="返上一个页面">返上一个页面</a></div><div style="width:300px;float:left;"><label class="my_label">用户编号<span class="mark">*</span></label><div class="line"><input type="text" name="user_name" id="user_name" value="" class="textbox ro" style="color:#904;font-weight:bold;" readonly="readonly" />&nbsp;
			</div><span id="Required_UserName" style="color: red;visibility: visibe;">用户编号在选择地区时自动生成</span><label class="my_label">用户名<span class="mark">*</span></label><div class="line"><input type="text" id="real_name" name="real_name" value="" class="textbox"  />&nbsp;
				<input type="hidden" name="real_name_flg" value="0" /></div><span id="Required_RealName" style="color: red;visibility: hidden;">用户名不能为空</span><label class="my_label">公司名称<span class="mark">*</span></label><div class="line"><input type="text" name="company" value="" class="textbox"  />&nbsp;
			</div><span id="Required_Company" style="color: red;visibility: hidden;">公司名称不能为空</span><label class="my_label">电子邮件<span class="mark">*</span></label><div class="line"><input type="text" name="email" value="" class="textbox"  />&nbsp;
			</div><span id="Required_Email" style="color: red;visibility: hidden;">电子邮件不能为空</span><label class="my_label">联系电话<span class="mark">*</span></label><div class="line"><input type="text" name="mobile_phone" value="" class="textbox"  />&nbsp;
			</div><span id="Required_MobilePhone" style="color: red;visibility: hidden;">联系电话不能为空</span></div><!--// sub main--><div style="width:300px;float:left;"><label class="my_label">地区<span class="mark">*</span></label><div class="line"><select name="province" id="province"><option value="0">请选择</option><?php if(is_array($province_list)): foreach($province_list as $key=>$item): ?><option value="<?php echo ($item["region_id"]); ?>"><?php echo ($item["region_name"]); ?></option><?php endforeach; endif; ?></select><select name="city" id="city"><option value="0">请选择</option></select><select name="district" id="district"><option value="0">请选择</option></select>&nbsp;
			</div><span id="Required_Province" style="color: red;visibility: hidden;">请选择所属地区</span><label class="my_label">地址</label><div class="line"><input type="text" name="address" value="" class="textbox" style="width:280px;"  />&nbsp;
			</div><span style="visibility: hidden;">空</span><label class="my_label">QQ</label><div class="line"><input type="text" name="qq" value="" class="textbox"  />&nbsp;
			</div><span style="color: red;visibility: hidden;">qq</span><label class="my_label">密码<span class="mark">*</span></label><div class="line"><input type="password" name="new_pwd" value="" class="textbox"  />&nbsp;
			</div><span id="Required_NewPwd" style="color: red;visibility: hidden;">密码不能少于6位</span><label class="my_label">确认密码<span class="mark">*</span></label><div class="line"><input type="password" name="confirm_new_pwd" value="" class="textbox"  />&nbsp;
			</div><span id="Required_ConfirmNewPwd" style="color: red;visibility: hidden;">密码与确认密码不一致</span></div><!--// sub main--><div style="height:1px;display:block;clear:both;"></div><div style="text-align:center;margin-top:20px;"><input type="submit" name="btnLogin" value="注  册" id="btnLogin" class="Button" style="margin-top: 8px">　
				<input type="button" name="" value="返　回" class="Button" onclick="javascript:history.go(-1)" /></div></div><!--// #main--></form></div></div><script src="__PUBLIC__/Js/jquery.js" type="text/javascript"></script><script type="text/javascript">$(document).ready(function(){
	$("form").submit(function(){
		var msg = '';
		var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
		var email = $("input[name='email']").val();
		var pwd = $("input[name='new_pwd']").val();
		if ($("input[name='user_name']").val() == "") {
			$("#Required_UserName").html("请选择右边所属地区");
			$("#Required_UserName").css("visibility", "visible");
			$("#Required_Province").css("visibility", "visible");
		} else {
			$("#Required_UserName").css("visibility", "hidden");
			$("#Required_Province").css("visibility", "hidden");
		}
		if ($("input[name='real_name']").val() == "") {
			$("#Required_RealName").html("用户名不能为空");
			$("#Required_RealName").css("visibility", "visible");
		} else if ($("input[name='real_name_flg']").val() == "0") {
			$("#Required_RealName").html("用户名已存在");
			$("#Required_RealName").css("visibility", "visible");
		} else {
			$("#Required_RealName").css("visibility", "hidden");
		}
		if ($("input[name='company']").val() == "") {
			$("#Required_Company").css("visibility", "visible");
		} else {
			$("#Required_Company").css("visibility", "hidden");
		}
		if (email == "") {
			$("#Required_Email").css("visibility", "visible");
		} else if (!myreg.test(email)) {
			$("#Required_Email").html("请填写有效的邮箱地址");
			$("#Required_Email").css("visibility", "visible");
		} else {
			$("#Required_Email").css("visibility", "hidden");
		}
		if ($("input[name='mobile_phone']").val() == "") {
			$("#Required_MobilePhone").css("visibility", "visible");
		} else {
			$("#Required_MobilePhone").css("visibility", "hidden");
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
		if (($("input[name='user_name']").val() == "")||($("input[name='real_name']").val() == "")||($("input[name='real_name_flg']").val() == "0")||
			(email == "")||(!myreg.test(email))||(pwd.length < 6)||($("input[name='new_pwd']").val() != $("input[name='confirm_new_pwd']").val())) {
			
			return false;
		}
		
	});
	$("#real_name").change(function(){
		$.get(
			'__ROOT__/index.php?m=Ajax&a=check_real_name',
			'real_name=' + this.value,
			function(data){
				data = parseInt(data);
				if (!data) {
					$("input[name='real_name_flg']").val(1);
				} else {
					$("input[name='real_name_flg']").val(0);
					$("#Required_RealName").css("visibility", "hidden");
				}
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
		$.get(
			'__ROOT__/index.php?m=Ajax&a=create_agent_username',
			'user_priv=2&region_id=' + this.value,
			function(data){
				$("input#user_name").val(data);		
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
</script></body></html>