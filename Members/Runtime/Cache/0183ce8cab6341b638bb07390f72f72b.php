<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>管理系统</title><link rel='stylesheet' type='text/css' href='__PUBLIC__/Css/common.css'></head><body class="right-bg"><div class="top-bar">			→ 欢迎<span style="color:red;"><?php echo ($_SESSION["user_name"]); ?></span>, （<span style="color:;"><?php echo ($_SESSION["priv_name"]); ?></span>）进入会员管理系统
			<span class="back-to-index"><img src="__PUBLIC__/Images/home.gif" style="line-height:25px;"/><a href="__ROOT__/index.php?m=Index&a=main">返回主首页</a></span></div><!--// .top-bar--><div class="r-content"><div class="r-content-title">会员列表</div><div class="r-content-main"><div class="r-search"><form>			状态：<select name="is_locked"><option value="99">不限</option><option value="1" <?php if($_GET["is_locked"] == '1'): ?>selected<?php endif; ?>>锁定</option><option value="0" <?php if($_GET["is_locked"] == '0'): ?>selected<?php endif; ?>>正常</option></select>			生日月份：<select name="birthday_month"><option value="">不限</option><?php $__FOR_START__=1;$__FOR_END__=13;for($i=$__FOR_START__;$i < $__FOR_END__;$i+=1){ ?><option value="<?php echo ($i); ?>" <?php if($_GET["birthday_month"] == $i): ?>selected<?php endif; ?>><?php echo ($i); ?>月</option><?php } ?></select>			&nbsp;<img src="__PUBLIC__/Images/search.gif" />用户名/用户邮件：<input type="text" name="user_name" value="<?php echo ($_GET["user_name"]); ?>" /><input type="submit" value="搜索" />　（可进行模糊搜索）	
			<input type="hidden" name="m" value="Users" /><input type="hidden" name="a" value="index" /></form></div><table width="100%" class="tableborder" border="0" cellpadding="3" cellspacing="3"><thead><tr><th width="7%">用户ID</th><th>用户名</th><th>用户姓名</th><th>天猫帐号</th><th>加盟店名称</th><?php if (user_priv('users_contact', 0)) : ?><th>联系电话</th><?php endif; ?><th>地区</th><th>状态</th><?php if (user_priv('users_edit', 0)) : ?><th>操作</th><?php endif; ?></tr></thead><tbody><?php if(is_array($user_list)): foreach($user_list as $key=>$item): ?><tr class="alt-row <?php if ($key%2==1) {echo 't2tr';} ?>"><td align="center"><?php echo ($item["user_id"]); ?></td><td align="center"><?php echo ($item["user_name"]); ?></td><td align="center"><?php echo ($item["real_name"]); ?></td><td align="center"><?php echo ($item["taobao_name"]); ?></td><td align="center"><?php echo ($item["franchise"]); ?></td><?php if (user_priv('users_contact', 0)) : ?><td align="center"><?php echo ($item["mobile_phone"]); ?></td><?php endif; ?><td align="center"><?php echo ($item["region"]); ?></td><td align="center"><?php echo ($item["status"]); ?></td><?php if (user_priv('users_edit', 0)) : ?><td align="center"><!-- Icons --><img src="__PUBLIC__/Images/edit.gif" /><a href="__ROOT__/index.php?m=Users&a=edit&user_id=<?php echo ($item["user_id"]); ?>" title="编辑">编辑</a>&nbsp;
                  <img src="__PUBLIC__/Images/locked.gif" /><a href="__ROOT__/index.php?m=Users&a=locked&user_id=<?php echo ($item["user_id"]); ?>" class="lock" title="锁定">锁定</a></td><?php endif; ?></tr><?php endforeach; endif; ?></tbody><tfoot><tr><td colspan="6"><div class="pagination"><?php echo ($page); ?></div><!-- End .pagination --><div class="clear"></div></td></tr></tfoot></table></div><!--// .r-content-main--></div><!--// .r-content--><script src="__PUBLIC__/Js/jquery.js" type="text/javascript"></script><script type="text/javascript">$(document).ready(function(){
	$("a.lock").click(function(){
		if (!confirm("确定要锁定或解锁此会员？")) {
			return false;
		}	
	});
});
</script><div class="copyright">&copy; Copyright 2010 Your Company </div></body></html>