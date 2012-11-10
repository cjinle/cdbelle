<?php
/**
 * OperatorAction.class.php 
 * 操作员管理页面
 * @author Lok Sat Sep 22 22:26:05 CST 2012
 */
class OperatorAction extends InitAction {
	/**
	 * 列表默认页
	 */
	public function index() {
		user_priv('operator_manage'); 
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
		$where['user_priv'] = array('IN', array(C('ADMIN'), C('OPERATOR')));
		$user_list = $User->where($where)->page($_GET['p'] . ',' . $list_rows)->select();
//		echo $User->getLastSQL();
		foreach ($user_list as $key => $val) {
			$user_list[$key]['reg_time'] = date('Y-m-d H:i:s', $val['reg_time']);
			$user_list[$key]['status'] = $val['is_locked'] == 1 ? '<span style="color:red;">锁定</span>' : '<span style="color:green;">正常</span>';
			$user_list[$key]['real_name'] = $this->get_priv_name($val['user_priv']);
		}
		import("ORG.Util.Page");
		$count = $User->where($where)->count();
		$Page = new Page($count, $list_rows);
		$this->assign('page', $Page->show());
		$this->assign('user_list', $user_list);
		$this->display();
	}
	
	/**
	 * 操作员添加
	 */
	public function add() {
		$priv_list = M('user_priv')->where(array('up_id'=>array('IN', array(C('ADMIN'), C('OPERATOR')))))->select();
		$this->assign('priv_list', $priv_list);
		$this->display();
	}
	
	/**
	 * 操作员添加处理
	 */
	public function insert() {
		user_priv('operator_manage');
		$User = M('users');
		$salt = rand(1000, 9999);
		$user_name = addslashes(trim($_POST['user_name']));
		if (!empty($user_name)) {
			$count = $User->where(array('user_name'=>$user_name))->count();
			if (!$count) {
				$data['user_name'] = $user_name;
				$data['user_priv'] = intval($_POST['user_priv']);
				$data['real_name'] = $this->get_priv_name($_POST['user_priv']);
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
				$data['salt'] = strval($salt);
				$data['parent_id'] = 1;
				$new_user_id = $User->add($data);
				$this->success('账号建立成功！', C('WEB_ROOT') . '/index.php?m=Operator');
			} else {
				$this->error('用户名已存在！');
			}
		} else {
			$this->error('用户不能为空！');
		}
	}
	
	/**
	 * 操作员编辑
	 */
	public function edit() {
		user_priv('operator_manage');
		$user_id = intval($_GET['user_id']);
		if ($user_id) {
			$user_info = M('users')->where(array('user_id'=>$user_id))->find();
			$user_info['status'] = $user_info['is_locked'] ? '<span style="color:red;">锁定</span>' : '<span style="color:green;">正常</span>';
			$priv_list = M('user_priv')->where(array('up_id'=>array('IN', array(C('ADMIN'), C('OPERATOR')))))->select();
		}
		$this->assign('priv_list', $priv_list);
		$this->assign('user_info', $user_info);
		$this->display();
	}
	
	/**
	 * 操作员编辑更新
	 */
	public function update() {
		user_priv('operator_manage');
		$user_id = intval($_POST['user_id']);
		if ($user_id) {
			$User = M('users');
			$salt = $User->where(array('user_id'=>$user_id))->getField('salt');
			$data['user_priv'] = in_array($_POST['user_priv'], array(C('ADMIN'), C('OPERATOR'))) ? $_POST['user_priv'] : C('OPERATOR');
			$data['real_name'] = $this->get_priv_name($data['user_priv']);
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
			$this->success('会员信息修改成功！', C('WEB_ROOT') . '/index.php?m=Operator');
		}
	}
	
	/**
	 * 操作员锁定或解锁
	 */
	public function locked() {
		user_priv('operator_manage');
		$user_id = intval($_GET['user_id']);
		if ($user_id) {
			$User = M('users');
			$where['user_id'] = $user_id;
			$where['user_priv'] = array('IN', array(C('ADMIN'), C('OPERATOR')));
			$is_locked = $User->where($where)->getField('is_locked');
			$is_locked = $is_locked ? 0 : 1;
			$User->where($where)->save(array('is_locked'=>$is_locked));
			$this->success("操作成功");
		}
	}
	
	/**
	 * 获取角色名称
	 * @param integer $up_id
	 * @return string
	 */
	private function get_priv_name($up_id) {
		if ($up_id) {
			return M('user_priv')->where(array('up_id'=>$up_id))->getField('priv_name');
		}
	}
}