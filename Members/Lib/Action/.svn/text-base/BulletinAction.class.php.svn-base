<?php
/**
 * BulletinAction.class.php 
 * 公告管理页面
 * @author Lok Wed Sep 19 21:46:46 CST 2012
 */
class BulletinAction extends InitAction {
	/**
	 * 公告列表（默认）
	 */
	public function index() {
		$Bulletin = M('bulletins');
		!isset($_GET['p']) ? $_GET['p'] = 1 : null;
		$list_rows = C('LIST_ROWS');
		$where = array();
		isset($_GET['title']) ? $where['title'] = array('LIKE', "%{$_GET[title]}%") : null;
		if (isset($_GET['is_open'])) {
			if ($_GET['is_open'] == 1) {
				$where['is_open'] = 1;
			} elseif ($_GET['is_open'] == 0) {
				$where['is_open'] = 0;
			}
		}
		if (!user_priv('bulletin_manage', 0)) {
			$where['is_open'] = 1;
		}
		$bulletin_list = $Bulletin->where($where)->page($_GET['p'] . ',' . $list_rows)->order('bid DESC')->select();
//		echo $Card->getLastSQL();
		foreach ($bulletin_list as $key => $val) {
			$bulletin_list[$key]['add_time'] = date('Y-m-d H:i:s', $val['add_time']);
			$bulletin_list[$key]['status'] = $val['is_open'] ? '<span style="color:green;">公开</span>' : '<span style="color:red;">关闭</span>';
		}
		import("ORG.Util.Page");
		$count = $Bulletin->where($where)->count();
		$Page = new Page($count, $list_rows);
		$this->assign('page', $Page->show());
		$this->assign('bulletin_list', $bulletin_list);
		$this->display();
	}
	
	/**
	 * 公告添加
	 */
	public function add() {
		$this->display();
	}
	
	/**
	 * 公告添加处理
	 */
	public function insert() {
		$data['title'] = trim($_POST['title']);
		$data['add_time'] = time();
		$data['is_open'] = intval($_POST['is_open']) ? 1 : 0;
		$data['content'] = trim($_POST['content']);
		if (empty($data['title'])) {
			$this->error("公告标题不能为空！");
		} elseif (empty($data['content'])) {
			$this->error("公告内容不能为空！");
		} else {
			$data['content'] = stripslashes($data['content']); // 反过滤
			M('bulletins')->add($data);
			$this->success('公告添加成功！', C('WEB_ROOT') . '/index.php?m=Bulletin');
		}
	}
	
	/**
	 * 公告编辑
	 */
	public function edit() {
		user_priv('bulletin_manage');
		$bid = intval($_GET['bid']);
		$bulletin = M('bulletins')->where(array('bid'=>$bid))->find();
		$this->assign('bulletin', $bulletin);
		$this->display();
	}
	
	/**
	 * 公告更新
	 */
	public function update() {
		$bid = intval($_POST['bid']);
		if ($bid) {
			$data['title'] = trim($_POST['title']);
			$data['is_open'] = intval($_POST['is_open']) ? 1 : 0;
			$data['content'] = trim($_POST['content']);
			if (empty($data['title'])) {
				$this->error("公告标题不能为空！");
			} elseif (empty($data['content'])) {
				$this->error("公告内容不能为空！");
			} else {
				$data['content'] = stripslashes($data['content']); // 反过滤
				M('bulletins')->where(array('bid'=>$bid))->save($data);
				$this->success('公告更新成功！', C('WEB_ROOT') . '/index.php?m=Bulletin');
			}
		}
	}
	
	/**
	 * 公告删除
	 */
	public function delete() {
		user_priv('bulletin_manage');
		$bid = intval($_GET['bid']);
		M('bulletins')->where(array('bid'=>$bid))->limit(1)->delete();
		$this->success("公告删除成功！", C('WEB_ROOT') . '/index.php?m=Bulletin');
	}
	
	/**
	 * 公告查看
	 */
	public function view() {
		$bid = intval($_GET['bid']);
		if ($bid) {
			$bulletin = M('bulletins')->where(array('bid'=>$bid))->find();
			$bulletin['add_time'] = date('Y-m-d H:i:s', $bulletin['add_time']);
			if ($bulletin['is_open'] == 0) {
				$this->error('公告已经关闭！');
			}
			$this->assign('bulletin', $bulletin);
		}
		$this->display();
	}
	
	/**
	 * 关闭或公开公告
	 */
	public function locked() {
		user_priv('bulletin_manage');
		$bid = intval($_GET['bid']);
		$Bulletin = M('bulletins');
		$is_open = $Bulletin->where(array('bid'=>$bid))->getField('is_open');
		$is_open = $is_open ? 0 : 1;
		$Bulletin->where(array('bid'=>$bid))->save(array('is_open'=>$is_open));
		$this->success("公开或关闭公告成功！", C('WEB_ROOT') . '/index.php?m=Bulletin');
	}
	
	
	
}