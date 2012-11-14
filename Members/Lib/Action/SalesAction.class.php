<?php
/**
 * SalesAction.class.php
 * 销售返点
 * @author Lok 2012/11/12
 */
class SalesAction extends InitAction {
	/**
	 * 首页
	 */
	public function index() {
		
	}
	/**
	 * 返点设置
	 */
	public function config() {
		$config_list = M('config')->select();
		foreach ($config_list as $val) {
			$config[$val['key_name']] = $val['key_value'];
		}
		$this->assign('config', $config);
		$this->display();
	}
	/**
	 * 返点设置处理
	 */
	public function setConfig() {
		$to_agents = floatval($_POST['to_agents']);
		$to_franchise = floatval($_POST['to_franchise']);
		if ($to_agents >= 0 && $to_agents < 1) {} 
		else {
			$this->error("对代理商的返点，请填写正确的小数");
		}
		if ($to_franchise >= 0 && $to_franchise < 1) {} 
		else {
			$this->error("对加盟店的返点，请填写正确的小数");
		}
		if (($to_agents + $to_franchise) > 1) {
			$this->error("代理商加上加盟店的返点已经超过总量1");
		}
		M('config')->where(array('key_name'=>'to_agents'))->save(array('key_value'=>$to_agents));
		M('config')->where(array('key_name'=>'to_franchise'))->save(array('key_value'=>$to_franchise));
		$this->success('设置返点成功！', C('WEB_ROOT') . '/index.php?m=Sales&a=config');
	}
	
	/**
	 * 上传天猫数据
	 */
	public function upload() {
		$this->display();
	}
	
	/**
	 * 处理上传的天猫数据
	 */
	public function doUpload() {
		
	}
}



?>