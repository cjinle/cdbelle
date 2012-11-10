<?php
class IndexAction extends InitAction{
    public function index() {
        $this->assign('hello','Hello,ThinkPHP');
        $this->display();
    }
    
    /**
     * 左侧导航
     */
    public function menu() {
    	$this->display();
    }
    
    /**
     * 主体内容区
     */
    public function main() {
    	$bulletin = M('bulletins')->where(array('is_open'=>1))->order('bid DESC')->find();
		$bulletin['add_time'] = date('Y-m-d H:i:s', $bulletin['add_time']);
		$this->assign('bulletin', $bulletin);
    	$this->display();
    }
    
    /**
     * 会员登录
     */
    public function logout() {
//    	echo '<img src="' . C('WEB_ROOT') . '/Public/Images/loading2.gif" />退出中...';
    	session(null);
    	header("Location: " . C('WEB_ROOT'));
    	exit;
    }
}
