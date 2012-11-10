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
	public function doRegister() {
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
				$data['parent_id'] = intval($_POST['franchise']); 
//				var_dump($data);exit;
				$new_user_id = $User->add($data);
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
		$this->display();
	}
	
	/**
	 * 加盟店注册
	 */
	public function franchiseRegister() {
		$this->display();
	}
	
	/**
	 * 忘记密码
	 */
	public function forgot_pwd() {
		
	}
}