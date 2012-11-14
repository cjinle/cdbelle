<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'><html><head><title>页面提示</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style>html, body{margin:0; padding:0; border:0 none;font:14px Tahoma,Verdana;line-height:150%;background:#D6DFF7;}
a{text-decoration:none; color:#174B73; border-bottom:1px dashed gray}
a:hover{color:#F60; border-bottom:1px dashed gray}
div.message{margin:10% auto 0px auto;clear:both;padding:5px;border:1px solid #6595D6;background:white; text-align:center; width:45%}
span.wait{color:blue;font-weight:bold;}
span.error{color:red;font-weight:bold}
span.success{color:blue;font-weight:bold}
div.msg{margin:20px 0px}
</style></head><body><div class="message"><div class="msg"><?php if(isset($message)): ?><span class="success"><?php echo ($msgTitle); echo ($message); ?></span><?php else: ?><span class="error"><?php echo ($msgTitle); echo ($error); ?></span><?php endif; ?></div><div class="tip"><?php if(isset($closeWin)): ?>页面将在 <span class="wait"><?php echo ($waitSecond); ?></span> 秒后自动关闭，如果不想等待请点击 <a href="<?php echo ($jumpUrl); ?>">这里</a> 关闭
	<?php else: ?>		页面将在 <span class="wait" id="totalSecond"><?php echo ($waitSecond); ?></span> 秒后自动跳转，如果不想等待请点击 <a href="<?php echo ($jumpUrl); ?>">这里</a> 跳转<?php endif; ?></div></div><script language="javascript" type="text/javascript"><!--
    var second = document.getElementById('totalSecond').textContent;
    if (navigator.appName.indexOf("Explorer") > -1)  //判断是IE浏览器还是Firefox浏览器，采用相应措施取得秒数
    {
        second = document.getElementById('totalSecond').innerText;
    } else
    {
        second = document.getElementById('totalSecond').textContent;
    }
    setInterval("redirect()", 900);  //每1秒钟调用redirect()方法一次
    function redirect()
    {
        if (second < 0)
        {
            location.href = '<?php echo ($jumpUrl); ?>';
        } else
        {
            if (navigator.appName.indexOf("Explorer") > -1)
            {
                document.getElementById('totalSecond').innerText = second--;
            } else
            {
                document.getElementById('totalSecond').textContent = second--;
            }
        }
    }
--></script></body></html>