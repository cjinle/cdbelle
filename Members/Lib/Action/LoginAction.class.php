<?php
/**
 * LoginAction.class.php 
 * 会员登录页面
 * @author Lok Sat Sep 15 23:27:44 CST 2012
 */
class LoginAction extends Action {
	/**
	 * 会员登录页面
	 */
	public function index() {
		$user_id = session('user_id');
		// 假如已经登录，则免去登录
		if ($user_id && session('?user_name')) { 
			header('Location: ' . C('WEB_ROOT'));
			exit;
		}
		$this->display('index2');
	}
	
	/**
	 * 处理登录数据
	 */
	public function doLogin() {
		$user_name = addslashes(trim($_POST['user_name']));
		$password = addslashes(trim($_POST['password']));
		$expiration = intval($_POST['expiration']);
		$User = M('users');
		$user_info = $User->field()->where(array('real_name'=>$user_name))->find();
		if ($user_info) {
			$user_priv = M('user_priv')->where(array('up_id'=>$user_info['user_priv']))->find();
			if (md5($password . $user_info['salt']) === $user_info['password']) {
				if ($user_info['is_locked']) { // 是否为锁定账号 
					$this->error('此账号已被锁定，请联系管理员！');
				}
				session('user_id', $user_info['user_id']);
				session('user_name', $user_info['user_name']);
				session('real_name', $user_info['real_name']);
				session('email', $user_info['email']);
				session('password', $user_info['password']);
				session('user_priv', $user_info['user_priv']);
				session('action_list', $user_priv['action_list']);
				session('priv_name', $user_priv['priv_name']);
				header('Location: ' . C('WEB_ROOT'));
				exit;
			} else {
				session(null);
				$this->error('用户名或密码错误！');
				exit;
			}
		} else {
			session(null);
			$this->error('用户名或密码错误！');
			exit;
		}
	}

}