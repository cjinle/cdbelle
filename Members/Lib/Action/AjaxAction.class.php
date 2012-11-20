<?php
/**
 * AjaxAction.class.php 
 * Ajax请求页面
 * @author Lok Tue Sep 18 03:07:46 CST 2012
 */

class AjaxAction extends Action {
	/**
	 * 首页（默认页）
	 */
	public function index() {
		
	}
	
	/**
	 * 获取地址
	 */
	public function get_region() {
		$parent_id = intval($_GET['parent_id']);
		$region_type = intval($_GET['region_type']);
		$return_str = '<option value="0">请选择</option>';
		if ($parent_id) {
			$region_list = get_region($parent_id, $region_type);
			foreach ($region_list as $key => $val) {
				$return_str .= '<option value="' . $val['region_id'] . '">' . $val['region_name'] . '</option>';
			}
			die($return_str);
		}
	}
	
	/**
	 * 检测用户名是否可用
	 */
	public function check_user_name() {
		$user_name = addslashes(trim($_GET['user_name']));
		$count = M('users')->where(array('user_name'=>$user_name))->count();
		die($count);
	}

    /**
     * 检测真名是否可用
     */
    public function check_real_name() {
        $real_name = addslashes(trim($_GET['real_name']));
        $count = M('users')->where(array('real_name'=>$real_name))->count();
        die($count);
    }

    /**
	 * 检测卡号是否可用
	 */
	public function check_card_no() {
		die(check_card_no_is_available($_GET['card_no']));
	}
	
	/**
	 * 根据地区生成代理商用户名
	 */
	public function create_agent_username() {
		$region_id = intval($_GET['region_id']);
		$user_priv = intval($_GET['user_priv']);
		die(get_next_agents_username($region_id, $user_priv));
	}
	
	/**
	 * 根据代理商生成(加盟店/会员)用户名
	 */
	public function create_next_username() {
		$parent_id = intval($_GET['parent_id']);
		$user_priv = intval($_GET['user_priv']);
		die(get_next_username($parent_id, $user_priv));
	}
	
}
