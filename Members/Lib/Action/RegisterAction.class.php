<?php
/**
 * RegisterAction.class.php 
 * 注册页面
 * @author Lok Wed Sep 19 00:13:01 CST 2012
 */
class RegisterAction extends Action {
	/**
	 * 注册页面（默认）
	 */
	public function index() {
		$this->display();
	}
	
	public function userRegister() {
		$franchise_list = M('users')->field('user_id,user_name,real_name')->where(array('user_priv'=>C('FRANCHISE')))->select();
		$this->assign('franchise_list', $franchise_list);
		$this->assign('province_list', get_region(1, 1));
		$this->display();
	}
	
	/**
	 * 处理会员注册页面
	 */
	public function doUserRegister() {
		$User = M('users');
		$salt = rand(1000, 9999);
		$user_name = addslashes(trim($_POST['user_name']));
		if (!empty($user_name)) {
			$count = $User->where(array('user_name'=>$user_name))->count();
			if (!$count) {
				$data['user_name'] = $user_name;
				$data['real_name'] = addslashes(trim($_POST['real_name']));
				$data['email'] = is_email($_POST['email']) ? addslashes(trim($_POST['email'])) : '';
				/* 卡号 */
				$card_result = check_card_no_is_available($_POST['card_no']);
				if ($card_result == '2') {
					$this->error("绑定卡号不存在！");
				} elseif ($card_result == '1') {
					$this->error("此卡号已经绑定其它会员！");
				}
				/* 密码 */
				$new_pwd = $_POST['new_pwd'];
				$confirm_new_pwd = $_POST['confirm_new_pwd'];
				if ($new_pwd !== $confirm_new_pwd) {
					$this->error('新密码与确认新密码不一致！');
				} elseif ((strlen($new_pwd) < 6)) {
					$this->error('密码至少6位！');
				} elseif (strlen($new_pwd) > 5) {
					$data['password'] = md5($new_pwd . $salt);
				}
				$data['reg_time'] = time();
				$data['last_ip'] = real_ip();
				$data['user_priv'] = C('USERS');
				$data['salt'] = strval($salt);
				$data['qq'] = addslashes(trim($_POST['qq']));
				$data['birthday'] = trim($_POST['birthday']);
				$data['birthday_month'] = date('m', strtotime($data['birthday']));
				$data['mobile_phone'] = addslashes(trim($_POST['mobile_phone']));
				$data['parent_id'] = intval($_POST['parent_id']); 
//				var_dump($data);exit;
				$new_user_id = $User->add($data);
				$parent_name = $User->where(array('user_id'=>$data['parent_id']))->getField('user_name');
				update_last_username($data['parent_id'], $parent_name, get_agent_province($new_user_id), C('USERS'));
//				echo $User->getLastSQL();exit;
				if ($new_user_id) { // 会员生成成功
					$address_data['user_id'] = $new_user_id;
					$address_data['country'] = 1;
					$address_data['province'] = intval($_POST['province']);
					$address_data['city'] = intval($_POST['city']);
					$address_data['district'] = intval($_POST['district']);
					$address_data['address'] = addslashes(trim($_POST['address']));
					$new_address_id = M('user_address')->add($address_data);
					$User->where(array('user_id'=>$new_user_id))->save(array('address_id'=>$new_address_id));
					$card_no = addslashes(trim($_POST['card_no']));
					M('card')->where(array('card_no'=>$card_no))->save(array('user_id'=>$new_user_id, 'bind_time'=>time()));
					session(NULL); // 清空SESSION
					$this->success('会员账号建立成功！', C('WEB_ROOT') . '/index.php?m=Login');
				}
			} else {
				$this->error('用户名已存在！');
			}
		} else {
			$this->error('用户不能为空！');
		}
	}
	
	/**
	 * 代理商注册
	 */
	public function agentRegister() {
		$this->assign('province_list', get_region(1, 1));
		$this->display('agentRegister2');
	}
	
	/**
	 * 处理代理商注册
	 */
	public function doAgentRegister() {
		$User = M('users');
		$salt = rand(1000, 9999);
		$user_name = addslashes(trim($_POST['user_name']));
		if (!empty($user_name)) {
			$count = $User->where(array('user_name'=>$user_name))->count();
			if (!$count) {
				$data['user_name'] = $user_name;
                if (check_real_name_is_available($_POST['real_name'])) {
                    $data['real_name'] = addslashes(trim($_POST['real_name']));
                } else {
                    $this->error("用户名已存在");
                }
                $data['company'] = addslashes($_POST['company']);
				$data['email'] = is_email($_POST['email']) ? addslashes(trim($_POST['email'])) : '';
				/* 密码 */
				$new_pwd = $_POST['new_pwd'];
				$confirm_new_pwd = $_POST['confirm_new_pwd'];
				if ($new_pwd !== $confirm_new_pwd) {
					$this->error('新密码与确认新密码不一致！');
				} elseif ((strlen($new_pwd) < 6)) {
					$this->error('密码至少6位！');
				} elseif (strlen($new_pwd) > 5) {
					$data['password'] = md5($new_pwd . $salt);
				}
				$data['reg_time'] = time();
				$data['last_ip'] = real_ip();
				$data['user_priv'] = C('AGENTS');
				$data['salt'] = strval($salt);
				$data['qq'] = addslashes(trim($_POST['qq']));
				$data['birthday'] = trim($_POST['birthday']);
				$data['birthday_month'] = date('m', strtotime($data['birthday']));
				$data['mobile_phone'] = addslashes(trim($_POST['mobile_phone']));
				$data['parent_id'] = 0; 
				$new_user_id = $User->add($data);
				update_last_username($new_user_id, $data['user_name'], intval($_POST['province']), C('AGENTS'));
//				echo $User->getLastSQL();exit;
				if ($new_user_id) { // 会员生成成功
					$address_data['user_id'] = $new_user_id;
					$address_data['country'] = 1;
					$address_data['province'] = intval($_POST['province']);
					$address_data['city'] = intval($_POST['city']);
					$address_data['district'] = intval($_POST['district']);
					$address_data['address'] = addslashes(trim($_POST['address']));
					$new_address_id = M('user_address')->add($address_data);
					$User->where(array('user_id'=>$new_user_id))->save(array('address_id'=>$new_address_id));
					$card_no = addslashes(trim($_POST['card_no']));
					M('card')->where(array('card_no'=>$card_no))->save(array('user_id'=>$new_user_id, 'bind_time'=>time()));
					session(NULL); // 清空SESSION
					$this->success('代理商账号建立成功！', C('WEB_ROOT') . '/index.php?m=Login');
				}
			} else {
				$this->error('用户名已存在！');
			}
		} else {
			$this->error('登录名异常，请返回注册页面重要填写信息！');
		}
	}
	
	/**
	 * franchise register page
	 */
	public function franchiseRegister() {
		$this->assign('agent_list', $aa = $this->get_a_f_list(C('AGENTS'))); // get agent list
		$this->assign('province_list', get_region(1, 1));
		$this->display();
	}
	
	public function doFranchiseRegister() {
		$User = M('users');
		$salt = rand(1000, 9999);
		$user_name = addslashes(trim($_POST['user_name']));
		if (!empty($user_name)) {
			$count = $User->where(array('user_name'=>$user_name))->count();
			if (!$count) {
				$data['user_name'] = $user_name;
				$data['real_name'] = addslashes(trim($_POST['real_name']));
				$data['email'] = is_email($_POST['email']) ? addslashes(trim($_POST['email'])) : '';
				/* 密码 */
				$new_pwd = $_POST['new_pwd'];
				$confirm_new_pwd = $_POST['confirm_new_pwd'];
				if ($new_pwd !== $confirm_new_pwd) {
					$this->error('新密码与确认新密码不一致！');
				} elseif ((strlen($new_pwd) < 6)) {
					$this->error('密码至少6位！');
				} elseif (strlen($new_pwd) > 5) {
					$data['password'] = md5($new_pwd . $salt);
				}
				$data['reg_time'] = time();
				$data['last_ip'] = real_ip();
				$data['user_priv'] = C('FRANCHISE');
				$data['salt'] = strval($salt);
				$data['qq'] = addslashes(trim($_POST['qq']));
				$data['birthday'] = trim($_POST['birthday']);
				$data['birthday_month'] = date('m', strtotime($data['birthday']));
				$data['mobile_phone'] = addslashes(trim($_POST['mobile_phone']));
				$data['parent_id'] = intval($_POST['parent_id']); 
				$new_user_id = $User->add($data);
//				$parent_address_id = $User->where(array('user_id'=>$data['parent_id']))->getField('address_id');
//				$parent_province = M('user_address')->where(array('address_id'=>$parent_address_id))->getField('province');
				$parent_name = $User->where(array('user_id'=>$data['parent_id']))->getField('user_name');
				update_last_username($data['parent_id'], $parent_name, get_agent_province($new_user_id), C('FRANCHISE'));
//				echo $User->getLastSQL();exit;
				if ($new_user_id) { // 会员生成成功
					$address_data['user_id'] = $new_user_id;
					$address_data['country'] = 1;
					$address_data['province'] = intval($_POST['province']);
					$address_data['city'] = intval($_POST['city']);
					$address_data['district'] = intval($_POST['district']);
					$address_data['address'] = addslashes(trim($_POST['address']));
					$new_address_id = M('user_address')->add($address_data);
					$User->where(array('user_id'=>$new_user_id))->save(array('address_id'=>$new_address_id));
					$card_no = addslashes(trim($_POST['card_no']));
					M('card')->where(array('card_no'=>$card_no))->save(array('user_id'=>$new_user_id, 'bind_time'=>time()));
					session(NULL); // 清空SESSION
					$this->success('加盟店账号建立成功！', C('WEB_ROOT') . '/index.php?m=Login');
				}
			} else {
				$this->error('用户名已存在！');
			}
		} else {
			$this->error('登录名异常，请返回注册页面重要填写信息！');
		}
	}
	
	/**
	 * 忘记密码
	 */
	public function forgot_pwd() {
		echo '<meta charset="utf-8">';
		echo '页面建议中...';
	}
	
	/**
	 * get agent or franchise list
	 * @param integer $user_priv
	 * @return array
	 */
	private function get_a_f_list($user_priv) {
		$User = M('users');
		$where['user_priv'] = $user_priv;
		$user_list = $User->field('user_id,user_name,real_name')->where($where)->order("user_name ASC")->select();
		return $user_list;
	}
}