<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>管理系统</title><link rel='stylesheet' type='text/css' href='__PUBLIC__/Css/common.css'></head><body class="right-bg"><div class="top-bar">			→ 欢迎<span style="color:red;"><?php echo ($_SESSION["user_name"]); ?></span>, （<span style="color:;"><?php echo ($_SESSION["priv_name"]); ?></span>）进入会员管理系统
			<span class="back-to-index"><img src="__PUBLIC__/Images/home.gif" style="line-height:25px;"/><a href="__ROOT__/index.php?m=Index&a=main">返回主首页</a></span></div><!--// .top-bar--><div class="r-content"><div class="r-content-title">上传天猫数据</div><div class="r-content-main"><form action="__ROOT__/index.php?m=Sales&a=doUpload" method="POST" enctype="multipart/form-data"><fieldset><legend>上传天猫销售报表</legend><table width="500" border="0" cellpadding="5" cellspacing="5" style="margin:20px 0 20px 50px;"><tr><td colspan="2" align="center" style="color:green;">提示：请上传正确的天猫数据，文件格式为：<span class="mark">csv</span>，文件的第一行为标题，请不要<span class="mark">删除</span></td></tr><tr><td align="right">请上传文件：</td><td><input type="file" name="csv_file" />&nbsp;
								<span id="csv_file_alert" style="color:red;visibility:hidden;">文件不能为空</span></td></tr><tr><td align="right"></td><td><input type="submit" value="上传" /></td></tr></table></fieldset></form><h4 style="padding-left:10px;">历史上传文件</h4><hr /><!-- 文件上传列表 --><table width="100%" class="tableborder" border="0" cellpadding="3" cellspacing="3"><tr><th width="7%">上传ID</th><th width="20%">文件名</th><th width="30%">原文件名</th><th>上传时间</th><th>上传用户</th><th>状态</th><th>操作</th><tr><?php foreach ($upload_file as $key => $val) : ?><tr class="alt-row <?php if ($key%2==1) {echo 't2tr';} ?>"><td align="center"><?php echo $val['upload_id']; ?></td><td><a href="<?php echo $val['upload_path']; ?>"><?php echo $val['filename']; ?></a></td><td><?php echo $val['source_filename']; ?></td><td align="center"><?php echo $val['upload_time']; ?></td><td align="center"><?php echo $val['user_name']; ?></td><td align="center"><?php echo $val['status_msg']; ?></td><td align="center"><?php if (0 == $val['status']) : ?><a href="__ROOT__/index.php?m=Sales&a=insert&id=<?php echo $val['upload_id']; ?>" onclick="javascript:if(!confirm('确定将此文件入库？')){return false;}">入库</a> | 
						<a href="__ROOT__/index.php?m=Sales&a=delUploadFile&id=<?php echo $val['upload_id']; ?>" onclick="javascript:if(!confirm('确定要删除此文件？')){return false;}">删除</a><?php elseif (1 == $val['status']) : ?><a href="#">取消</a><?php endif; ?></td></tr><?php endforeach; ?></table></div><!--// .r-content-main--></div><!--// .r-content--><script src="__PUBLIC__/Js/jquery.js" type="text/javascript"></script><script type="text/javascript">$(document).ready(function(){
	$('form').submit(function(){
		var n = $("input[name='csv_file']").val();
		if (n == "") {
			$("#csv_file_alert").css('visibility', 'visible');
			return false;
		} else {
			$("#csv_file_alert").css('visibility', 'hidden');	
		}
	});
});


</script><div class="copyright">&copy; Copyright 2010 Your Company </div></body></html>