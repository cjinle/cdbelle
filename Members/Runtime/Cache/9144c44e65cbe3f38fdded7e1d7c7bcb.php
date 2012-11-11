<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>会员注册 - 会员管理系统</title><link rel='stylesheet' type='text/css' href='__PUBLIC__/Css/register.css'><script language="javascript" type="text/javascript" src="__PUBLIC__/Js/My97DatePicker/WdatePicker.js"></script></head><body><div style="width:100%;"><div align="center"><form action="__ROOT__/index.php?m=Register&a=doUserRegister" method="POST"><div id="main" style="padding-left:90px;padding-top:50px;height:510px;display:block;"><div id="heading">				会员注册 &nbsp;
				<a href="__ROOT__/index.php?m=Register" title="返上一个页面">返上一个页面</a></div><div style="width:300px;float:left;"><label class="my_label">用户名<span class="mark">*</span></label><div class="line"><input type="text" name="user_name" id="user_name" value="" class="textbox ro" style="color:#904;font-weight:bold;" readonly="readonly" />&nbsp;
				</div><span id="Required_UserName" style="color: red;visibility: visibe;">用户名在选择加盟店时自动生成</span><label class="my_label">会员名称<span class="mark">*</span></label><div class="line"><input type="text" name="real_name" value="" class="textbox"  />&nbsp;
				</div><span id="Required_RealName" style="color: red;visibility: hidden;">会员名称不能为空</span><label class="my_label">电子邮件<span class="mark">*</span></label><div class="line"><input type="text" name="email" value="" class="textbox"  />&nbsp;
				</div><span id="Required_Email" style="color: red;visibility: hidden;">电子邮件不能为空</span><label class="my_label">QQ</label><div class="line"><input type="text" name="qq" value="" class="textbox"  />&nbsp;
				</div><span style="color: red;visibility: hidden;">qq</span><label class="my_label">联系电话<span class="mark">*</span></label><div class="line"><input type="text" name="mobile_phone" value="" class="textbox"  />&nbsp;
				</div><span id="Required_MobilePhone" style="color: red;visibility: hidden;">联系电话不能为空</span><label class="my_label">出生日期</label><div class="line"><input type="text" name="birthday" value="1985-01-01" onClick="WdatePicker()" class="textbox" style="background: white url(__PUBLIC__/Images/datePicker.gif) no-repeat right"  />&nbsp;
				</div><span id="Required_MobilePhone" style="color: green;visibility: visibe;">填写真实生日会有神秘礼物</span></div><!--// sub main--><div style="width:300px;float:left;"><label class="my_label">所属加盟店<span class="mark">*</span></label><div class="line"><select name="parent_id" id="parent_id"><option value="0">请选择</option><?php if(is_array($franchise_list)): foreach($franchise_list as $key=>$item): ?><option value="<?php echo ($item["user_id"]); ?>"><?php echo ($item["user_name"]); ?> - <?php echo ($item["real_name"]); ?></option><?php endforeach; endif; ?></select></div><span id="Required_AgentId" style="color: red;visibility: hidden;">请选择所属加盟店</span><label class="my_label">地区</label><div class="line"><select name="province" id="province"><option value="0">请选择</option><?php if(is_array($province_list)): foreach($province_list as $key=>$item): ?><option value="<?php echo ($item["region_id"]); ?>"><?php echo ($item["region_name"]); ?></option><?php endforeach; endif; ?></select><select name="city" id="city"><option value="0">请选择</option></select><select name="district" id="district"><option value="0">请选择</option></select>&nbsp;
				</div><span id="Required_Province" style="color: red;visibility: hidden;">请选择所属地区</span><label class="my_label">地址</label><div class="line"><input type="text" name="address" value="" class="textbox" style="width:280px;"  />&nbsp;
				</div><span style="visibility: hidden;">空</span><label class="my_label">绑定卡号<span class="mark">*</span></label><div class="line"><input type="text" name="card_no" value="" class="textbox"  />&nbsp;
					<input type="hidden" name="card_no_flg" value="0" /></div><span id="Required_CardNo" style="color: red;visibility: hidden;">卡号不能为空</span><label class="my_label">密码<span class="mark">*</span></label><div class="line"><input type="password" name="new_pwd" value="" class="textbox"  />&nbsp;
				</div><span id="Required_NewPwd" style="color: red;visibility: hidden;">密码不能少于6位</span><label class="my_label">确认密码<span class="mark">*</span></label><div class="line"><input type="password" name="confirm_new_pwd" value="" class="textbox"  />&nbsp;
				</div><span id="Required_ConfirmNewPwd" style="color: red;visibility: hidden;">密码与确认密码不一致</span></div><!--// sub main--><div style="height:1px;display:block;clear:both;"></div><div style="text-align:center;margin-top:20px;"><input type="submit" name="btnLogin" value="注  册" id="btnLogin" class="Button" style="margin-top: 8px">　
				<input type="button" name="" value="返　回" class="Button" onclick="javascript:history.go(-1)" /></div></div><!--// #main--></form></div></div><script src="__PUBLIC__/Js/jquery.js" type="text/javascript"></script><script type="text/javascript">$(document).ready(function(){
	$("form").submit(function(){
		var pwd = $("input[name='new_pwd']").val();
		if ($("input[name='user_name']").val() == "") {
			$("#Required_UserName").html("请选择右边所属加盟店");
			$("#Required_UserName").css("visibility", "visible");
			$("#Required_AgentId").css("visibility", "visible");
		} else {
			$("#Required_UserName").css("visibility", "hidden");
			$("#Required_AgentId").css("visibility", "hidden");
		}
		if ($("input[name='real_name']").val() == "") {
			$("#Required_RealName").css("visibility", "visible");
		} else {
			$("#Required_RealName").css("visibility", "hidden");
		}
		if ($("input[name='email']").val() == "") {
			$("#Required_Email").css("visibility", "visible");
		} else {
			$("#Required_Email").css("visibility", "hidden");
		}
		if ($("input[name='mobile_phone']").val() == "") {
			$("#Required_MobilePhone").css("visibility", "visible");
		} else {
			$("#Required_MobilePhone").css("visibility", "hidden");
		}
		if ($("input[name='card_no']").val() == "") {
			$("#Required_CardNo").css("visibility", "visible");
		} else {
			$.get(
				'__ROOT__/index.php?m=Ajax&a=check_card_no',
				'card_no=' + $("input[name='card_no']").val(),
				function(data){
					if (data == 0) {
						$("#Required_CardNo").css("visibility", "hidden");
						$("input[name='card_no_flg']").val("1")
					}
					else if (data == 1) {
						$("#Required_CardNo").html("卡号已绑定其它会员");
						$("#Required_CardNo").css("visibility", "visible");
					}
					else if (data == 2){	
						$("#Required_CardNo").html("卡号不存在");
						$("#Required_CardNo").css("visibility", "visible");
					}
				}
			);
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
		if (($("input[name='user_name']").val() == "")||($("input[name='real_name']").val() == "")||
			($("input[name='email']").val() == "")||(pwd.length < 6)||($("input[name='new_pwd']").val() != $("input[name='confirm_new_pwd']").val())) {
			if ($("input[name='card_no_flg']").val()=="1") {
				return true;
			} else {
				return false;
			}
		}
		
	});
	$("#parent_id").change(function(){
		$.get(
			'__ROOT__/index.php?m=Ajax&a=create_next_username',
			'user_priv=4&parent_id=' + this.value,
			function(data){
				$("input#user_name").val(data);		
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