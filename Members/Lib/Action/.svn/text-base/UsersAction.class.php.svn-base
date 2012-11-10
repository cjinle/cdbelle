<?php
/**
 * UsersAction.class.php 
 * 会员管理页面
 * @author Lok Tue Sep 18 21:46:57 CST 2012
 */
class UsersAction extends InitAction {
	/**
	 * 会员列表页（默认）
	 */
	public function index() {
		$User = M('users');
		$list_rows = C('LIST_ROWS');
		!isset($_GET['p']) ? $_GET['p'] = 1 : null;
		$where = array('user_priv'=>C('USERS'));
		if (isset($_GET['user_name'])) { // 模糊搜索
			$where['_string'] = "((user_name LIKE '%{$_GET[user_name]}%') OR (email LIKE '%{$_GET[user_name]}%') OR (real_name LIKE '%{$_GET[user_name]}%'))";
		}
		if (isset($_GET['is_locked'])) { // 锁定状态
			if (0 == $_GET['is_locked']) {
				$where['is_locked'] = 0;
			} elseif (1 == $_GET['is_locked']) {
				$where['is_locked'] = 1;
			}
		}
		if (isset($_GET['parent_id'])) { 
			$where['parent_id'] = intval($_GET['parent_id']);
		}
		if (session('user_priv') == C('AGENTS')) { // 代理商
			$franchise_ids = $User->field('user_id')->where(array('parent_id'=>session('user_id')))->select();
			$franchise_id_arr = array();
			foreach ($franchise_ids as $val) {
				$franchise_id_arr[] = $val['user_id'];
			}
//			var_dump($franchise_id_arr);exit;
			$where['parent_id'] = array('IN', $franchise_id_arr);
		} elseif (session('user_priv') == C('FRANCHISE')) { // 加盟店
			$where['parent_id'] = session('user_id');
		}
		$user_list = $User->where($where)->page($_GET['p'] . ',' . $list_rows)->select();
//		echo $User->getLastSQL();
		foreach ($user_list as $key => $val) {
			$user_list[$key]['reg_time'] = date('Y-m-d H:i:s', $val['reg_time']);
			$user_list[$key]['status'] = $val['is_locked'] == 1 ? '<span style="color:red;">锁定</span>' : '<span style="color:green;">正常</span>';
			$user_list[$key]['franchise'] = $this->get_franchise_name($val['parent_id']);
			$user_address = get_user_address($val['user_id']);
			$user_list[$key]['region'] = $user_address['province'] . '-' . $user_address['city'];
		}
		import("ORG.Util.Page");
		$count = $User->where($where)->count();
		$Page = new Page($count, $list_rows);
		$this->assign('page', $Page->show());
		$this->assign('user_list', $user_list);
		$this->display();
	}
	
	/**
	 * 会员添加
	 */
	public function add() {
		if (session('user_priv') == C('ADMIN')) { // 超级管理员可以选择代理商
			$franchise_list = M('users')->field('user_id,user_name,real_name')->where(array('user_priv'=>C('FRANCHISE')))->select();
			$this->assign('franchise_list', $franchise_list);
		}
		$this->assign('province_list', get_region(1, 1));
		$this->display();
	}
	
	/**
	 * 会员添加处理
	 */
	public function insert() {
		user_priv('users_manage');
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
				$data['user_priv'] = C('USERS');
				$data['salt'] = strval($salt);
				$data['qq'] = addslashes(trim($_POST['qq']));
				$data['mobile_phone'] = addslashes(trim($_POST['mobile_phone']));
				if ((session('user_priv') == C('ADMIN') || (session('user_priv') == C('AGENTS'))) && 
					(isset($_POST['franchise']))) {
					$data['parent_id'] = intval($_POST['franchise']); 
				} else { // 除管理员外，代理商自己建立自己的加盟店账号
					$data['parent_id'] = session('user_id');
				}
				$new_user_id = $User->add($data);
//				echo $User->getLastSQL();exit;
				if ($new_user_id) { // 新代理商生成成功
					$address_data['user_id'] = $new_user_id;
					$address_data['country'] = 1;
					$address_data['province'] = intval($_POST['province']);
					$address_data['city'] = intval($_POST['city']);
					$address_data['district'] = intval($_POST['district']);
					$address_data['address'] = addslashes(trim($_POST['address']));
					$new_address_id = M('user_address')->add($address_data);
					$User->where(array('user_id'=>$new_user_id))->save(array('address_id'=>$new_address_id));
					/* 卡号 */
					if (!empty($_POST['card_no'])) {
						$card_result = check_card_no_is_available($_POST['card_no']);
						if ($card_result == '2') {
							$card_msg = "<span style='color:red;'>（绑定卡号不存在！）</span>";
						} elseif ($card_result == '1') {
							$card_msg = "<span style='color:red;'>（此卡号已经绑定其它会员！）</span>";
						} elseif ($card_result == '0') {
							// 更新会员绑定卡号
							$card_msg = '';
							M('card')->where(array('card_no'=>trim($_POST['card_no'])))->save(array('user_id'=>$new_user_id, 'bind_time'=>time()));
						}
					}
					$this->success('会员账号建立成功！' . $card_msg, C('WEB_ROOT') . '/index.php?m=Users');
				}
			} else {
				$this->error('用户名已存在！');
			}
		} else {
			$this->error('用户不能为空！');
		}
	}
	
	/**
	 * 会员编辑
	 */
	public function edit() {
		$user_id = intval($_GET['user_id']);
		if ($user_id) {
			$user_info = M('users')->where(array('user_id'=>$user_id))->find();
			$user_info['reg_time'] = date('Y-m-d H:i:s', $user_info['reg_time']);
			$user_info['status'] = $user_info['is_locked'] ? '<span style="color:red;">锁定</span>' : '<span style="color:green;">正常</span>';
			$user_info['user_priv'] = $user_info['user_priv'] == C('USERS') ? '普通会员' : '';
			$user_info['card_no'] = get_card_no($user_id);
			if (session('user_priv') == C('ADMIN')) { // 超级管理员可以选择代理商
				$franchise_list = M('users')->field('user_id,user_name,real_name')->where(array('user_priv'=>C('FRANCHISE')))->select();
				$this->assign('franchise_list', $franchise_list);
			} elseif (session('user_priv') == C('AGENTS')) { // 代理商可以选择下级的加盟店
				$franchise_list = M('users')->field('user_id,user_name,real_name')->where(array('user_priv'=>C('FRANCHISE'), 'parent_id'=>session('user_id')))->select();
				$this->assign('franchise_list', $franchise_list);
			}
			$user_address = get_user_address($user_id);
			$this->assign('province_list', get_region(1, 1));	
			$this->assign('city_list', get_region($user_address['province_id'], 2));
			$this->assign('district_list', get_region($user_address['city_id'], 3));
			$this->assign('user_address', $user_address);
		}
		$this->assign('user_info', $user_info);
		$this->display();
	}
	
	/**
	 * 会员更新
	 */
	public function update() {
		user_priv('users_manage');
		$user_id = intval($_POST['user_id']);
		if ($user_id) {
			$UserAdress = M('user_address');
			$User = M('users');
			$tmp_user_info = $User->where(array('user_id'=>$user_id))->field('address_id,salt')->find();
			$salt = $tmp_user_info['salt'];
			$address_id = intval($tmp_user_info['address_id']);
			$address_data['province'] = intval($_POST['province']);
			$address_data['city'] = intval($_POST['city']);
			$address_data['district'] = intval($_POST['district']);
			$address_data['address'] = addslashes(trim($_POST['address']));
			if ($address_id) {
				$UserAdress->where(array('address_id'=>$address_id))->save($address_data);
			} else {
				$address_data['user_id'] = $user_id;
				$data['address_id'] = $UserAdress->add($address_data);
			}
			/* 卡号 */
			if (!empty($_POST['card_no'])) {
				$card_result = check_card_no_is_available($_POST['card_no']);
				if ($card_result == '2') {
					$this->error("绑定卡号不存在！");
				} elseif ($card_result == '1') {
					$this->error("此卡号已经绑定其它会员！");
				} elseif ($card_result == '0') {
					// 更新会员绑定卡号
					M('card')->where(array('card_no'=>trim($_POST['card_no'])))->save(array('user_id'=>$user_id, 'bind_time'=>time()));
				}
			}
			if ((session('user_priv') == C('ADMIN')) && (isset($_POST['franchise']))) {
				$data['parent_id'] = intval($_POST['franchise']); 
			}
			$data['real_name'] = addslashes(trim($_POST['real_name']));
			$data['qq'] = addslashes(trim($_POST['qq']));
			$data['mobile_phone'] = addslashes(trim($_POST['mobile_phone']));
			// 密码
			$new_pwd = $_POST['new_pwd'];
			$confirm_new_pwd = $_POST['confirm_new_pwd'];
			if ($new_pwd !== $confirm_new_pwd) {
				$this->error('新密码与确认新密码不一致！');
			} elseif ((strlen($new_pwd) < 6) && (strlen($new_pwd) != 0)) {
				$this->error('密码至少6位！');
			} elseif (strlen($new_pwd) > 5) {
				$data['password'] = md5($new_pwd . $salt);
			}
			$User->where(array('user_id'=>$user_id))->save($data);
			$this->success('会员信息修改成功！', C('WEB_ROOT') . '/index.php?m=Users');
		}
	}
	
	/**
	 * 锁定或解锁加盟店
	 */
	public function locked() {
		user_priv('users_manage');
		$user_id = intval($_GET['user_id']);
		if ($user_id) {
			$User = M('users');
			$is_locked = $User->where(array('user_id'=>$user_id, 'user_priv'=>C('USERS')))->getField('is_locked');
			$is_locked = $is_locked ? 0 : 1;
			$User->where(array('user_id'=>$user_id))->save(array('is_locked'=>$is_locked));
			$this->success("操作成功");
		}
	}
	
	/**
	 * 会员信息页面
	 */
	public function view() {
		$user_id = intval($_GET['user_id']);
		if ($user_id) {
			$user_info = M('users')->where(array('user_id'=>$user_id))->find();
			$user_info['reg_time'] = date('Y-m-d H:i:s', $user_info['reg_time']);
			$user_info['status'] = $user_info['is_locked'] ? '<span style="color:red;">锁定</span>' : '<span style="color:green;">正常</span>';
			$user_info['user_priv'] = $this->get_priv_name($user_info['user_priv']);
			$user_info['card_no'] = get_card_no($user_id);
			$user_info['parent'] = get_user_name($user_info['parent_id']);
			$user_address = get_user_address($user_id);
			$this->assign('user_address', $user_address);
		}
		$this->assign('user_info', $user_info);
		$this->display();
	}
	
	/**
	 * 更新个人信息页面
	 */
	public function userInfo() {
		$user_id = session('user_id');
		if ($user_id) {
			$user_info = M('users')->where(array('user_id'=>$user_id))->find();
			$user_info['reg_time'] = date('Y-m-d H:i:s', $user_info['reg_time']);
			$user_info['status'] = $user_info['is_locked'] ? '<span style="color:red;">锁定</span>' : '<span style="color:green;">正常</span>';
			$user_info['user_priv'] = session('priv_name');
			if (session('user_priv') == C('USERS')) { // 超级管理员可以选择代理商
				$user_info['card_no'] = get_card_no($user_id);
				$franchise = M('users')->field('user_id,user_name,real_name')->where(array('user_priv'=>C('FRANCHISE'), 'user_id'=>$user_info['parent_id']))->find();
				$this->assign('franchise', $franchise['real_name'] . ' - ' . $franchise['user_name']);
			} 
			if (session('user_priv') == C('FRANCHISE')) {
				$agents = M('users')->field('user_id,user_name,real_name')->where(array('user_priv'=>C('AGENTS'), 'user_id'=>$user_info['parent_id']))->find();
				$this->assign('agents', $agents['real_name'] . ' - ' . $agents['user_name']);
			}
			$user_address = get_user_address($user_id);
			$this->assign('province_list', get_region(1, 1));	
			$this->assign('city_list', get_region($user_address['province_id'], 2));
			$this->assign('district_list', get_region($user_address['city_id'], 3));
			$this->assign('user_address', $user_address);
		}
		$this->assign('user_info', $user_info);
		$this->display();
	}
	
	/**
	 * 处理更新个人信息
	 */
	public function updateUserInfo() {
		$user_id = session('user_id');
		if ($user_id) {
			$UserAdress = M('user_address');
			$User = M('users');
			$tmp_user_info = $User->where(array('user_id'=>$user_id))->field('address_id,salt')->find();
			$salt = $tmp_user_info['salt'];
			$address_id = intval($tmp_user_info['address_id']);
			$address_data['province'] = intval($_POST['province']);
			$address_data['city'] = intval($_POST['city']);
			$address_data['district'] = intval($_POST['district']);
			$address_data['address'] = addslashes(trim($_POST['address']));
			if ($address_id) {
				$UserAdress->where(array('address_id'=>$address_id))->save($address_data);
			} else {
				$address_data['user_id'] = $user_id;
				$data['address_id'] = $UserAdress->add($address_data);
			}
			/* 卡号 */
			if (!empty($_POST['card_no'])) {
				$card_result = check_card_no_is_available($_POST['card_no']);
				if ($card_result == '2') {
					$this->error("绑定卡号不存在！");
				} elseif ($card_result == '1') {
					$this->error("此卡号已经绑定其它会员！");
				} elseif ($card_result == '0') {
					// 更新会员绑定卡号
					M('card')->where(array('card_no'=>trim($_POST['card_no'])))->save(array('user_id'=>$user_id, 'bind_time'=>time()));
				}
			}
			if ((session('user_priv') == C('ADMIN')) && (isset($_POST['franchise']))) {
				$data['parent_id'] = intval($_POST['franchise']); 
			}
			$data['real_name'] = addslashes(trim($_POST['real_name']));
			$data['qq'] = addslashes(trim($_POST['qq']));
			$data['mobile_phone'] = addslashes(trim($_POST['mobile_phone']));
			// 密码
			$new_pwd = $_POST['new_pwd'];
			$confirm_new_pwd = $_POST['confirm_new_pwd'];
			if ($new_pwd !== $confirm_new_pwd) {
				$this->error('新密码与确认新密码不一致！');
			} elseif ((strlen($new_pwd) < 6) && (strlen($new_pwd) != 0)) {
				$this->error('密码至少6位！');
			} elseif (strlen($new_pwd) > 5) {
				$data['password'] = md5($new_pwd . $salt);
			}
			$User->where(array('user_id'=>$user_id))->save($data);
			$this->success('信息修改成功！', C('WEB_ROOT') . '/index.php?m=Users&a=userInfo');
		}
	}
	
	/**
	 * 获取加盟店名字
	 * @param integer $user_id
	 * @return string
	 */
	private function get_franchise_name($user_id) {
		if ($user_id) {
			return M('users')->where(array('user_id'=>$user_id, 'user_priv'=>C('FRANCHISE')))->getField('real_name');
		}
	}
	
	/**
	 * 获取权限名称
	 * @param integer $up_id
	 * @return string
	 */
	private function get_priv_name($up_id) {
		if ($up_id) {
			return M('user_priv')->where(array('up_id'=>$up_id))->getField('priv_name');
		}
	}
	
}