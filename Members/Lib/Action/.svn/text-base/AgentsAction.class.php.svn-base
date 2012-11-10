<?php
/**
 * AgentsAction.class.php 
 * 代理商页面
 * @author Lok Mon Sep 17 20:55:40 CST 2012
 */
class AgentsAction extends InitAction {
	/**
	 * 代理商列表
	 */
	public function index() {
		$User = M('users');
		$list_rows = C('LIST_ROWS');
		!isset($_GET['p']) ? $_GET['p'] = 1 : null;
		$where = array('user_priv'=>C('AGENTS'));
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
		$user_list = $User->where($where)->page($_GET['p'] . ',' . $list_rows)->select();
//		echo $User->getLastSQL();
		foreach ($user_list as $key => $val) {
			$user_list[$key]['reg_time'] = date('Y-m-d H:i:s', $val['reg_time']);
			$user_list[$key]['franchise'] = $User->where(array('parent_id'=>$val['user_id'], 'user_priv'=>C('FRANCHISE')))->count();
			$user_list[$key]['status'] = $val['is_locked'] == 1 ? '<span style="color:red;">锁定</span>' : '<span style="color:green;">正常</span>';
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
	 * 代理商添加
	 */
	public function add() {
		$this->assign('province_list', get_region(1, 1));	
//		$this->assign('city_list', get_region(2, 2));
//		$this->assign('district_list', get_region(52, 3));
		$this->display();
	}
	
	/**
	 * 代理商添加处理
	 */
	public function insert() {
		user_priv('agents_manage');
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
				$data['user_priv'] = C('AGENTS');
				$data['salt'] = strval($salt);
				$data['parent_id'] = 1;
				$data['qq'] = addslashes(trim($_POST['qq']));
				$data['mobile_phone'] = addslashes(trim($_POST['mobile_phone']));
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
					$this->success('代理商账号建立成功！');
				}
			} else {
				$this->error('用户名已存在！');
			}
		} else {
			$this->error('用户不能为空！');
		}
	}
	
	/**
	 * 代理商编辑
	 */
	public function edit() {
		$user_id = intval($_GET['user_id']);
		if ($user_id) {
			$user_info = M('users')->where(array('user_id'=>$user_id))->find();
			$user_info['reg_time'] = date('Y-m-d H:i:s', $user_info['reg_time']);
			$user_info['status'] = $user_info['is_locked'] ? '<span style="color:red;">锁定</span>' : '<span style="color:green;">正常</span>';
			$user_info['user_priv'] = $user_info['user_priv'] == C('AGENTS') ? '代理商' : '';
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
	 * 代理商更新
	 */
	public function update() {
		user_priv('agents_manage');
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
			$this->success('代理商信息修改成功！', C('WEB_ROOT') . '/index.php?m=Agents');
		}
	}
	
	/**
	 * 锁定或解锁代理商
	 */
	public function locked() {
		user_priv('agents_manage');
		$user_id = intval($_GET['user_id']);
		if ($user_id) {
			$User = M('users');
			$is_locked = $User->where(array('user_id'=>$user_id, 'user_priv'=>C('AGENTS')))->getField('is_locked');
			$is_locked = $is_locked ? 0 : 1;
			$User->where(array('user_id'=>$user_id))->save(array('is_locked'=>$is_locked));
			$this->success("操作成功");
		}
		
	}
}