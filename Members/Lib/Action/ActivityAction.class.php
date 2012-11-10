<?php
/**
 * ActivityAction.class.php 
 * 优惠活动页面
 * @author Lok Fri Sep 21 16:48:04 CST 2012
 */
class ActivityAction extends InitAction {
	/**
	 * 优惠活动列表（默认）
	 */
	public function index() {
		$Activity = M('activity');
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
		if (!user_priv('activity_manage', 0)) {
			$where['is_open'] = 1;
		}
		$activity_list = $Activity->where($where)->page($_GET['p'] . ',' . $list_rows)->order('aid DESC')->select();
		foreach ($activity_list as $key => $val) {
			$activity_list[$key]['add_time'] = date('Y-m-d H:i:s', $val['add_time']);
			$activity_list[$key]['start_date'] = date('Y-m-d', $val['start_date']);
			$activity_list[$key]['end_date'] = date('Y-m-d', $val['end_date']);
			$activity_list[$key]['status'] = $val['is_open'] ? '<span style="color:green;">公开</span>' : '<span style="color:red;">关闭</span>';
			$activity_list[$key]['address'] = $this->get_address_count($val['aid']);
		}
		import("ORG.Util.Page");
		$count = $Activity->where($where)->count();
		$Page = new Page($count, $list_rows);
		$this->assign('page', $Page->show());
		$this->assign('activity_list', $activity_list);
		$this->display();
	}
	
	/**
	 * 优惠活动列表（会员）
	 */
	public function actList() {
		$Activity = M('activity');
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
		if (!user_priv('activity_manage', 0)) {
			$where['is_open'] = 1;
		}
		$activity_list = $Activity->where($where)->page($_GET['p'] . ',' . $list_rows)->order('aid DESC')->select();
		foreach ($activity_list as $key => $val) {
			$activity_list[$key]['add_time'] = date('Y-m-d H:i:s', $val['add_time']);
			$activity_list[$key]['start_date'] = date('Y-m-d', $val['start_date']);
			$activity_list[$key]['end_date'] = date('Y-m-d', $val['end_date']);
			$tmp_status = $this->get_activity_status($val['aid']);
			$activity_list[$key]['status'] = $tmp_status['tips'];
			$tmp_receive_status = $this->check_receive_status($val['aid'], session('user_id'));
			$activity_list[$key]['receive_status'] = $tmp_receive_status && $tmp_status['status'] ? true : false;
			$activity_list[$key]['receive'] = $tmp_receive_status ? '<span style="color:red">未领取</span>' : '<span style="color:green">已领取</span>';
		}
		import("ORG.Util.Page");
		$count = $Activity->where($where)->count();
		$Page = new Page($count, $list_rows);
		$this->assign('page', $Page->show());
		$this->assign('activity_list', $activity_list);
		$this->display();
	}
	
	/**
	 * 领取优惠
	 */
	public function receive() {
		$aid = intval($_GET['aid']);
		$activity_info = M('activity')->where(array('aid'=>$aid))->find();
		$activity_info['start_date'] = date('Y-m-d', $activity_info['start_date']);
		$activity_info['end_date'] = date('Y-m-d', $activity_info['end_date']);
		$tmp_status = $this->get_activity_status($aid);
		if (!$tmp_status['status']) {
			$this->error($tmp_status['tips']);
		}
		$act_type = M('activity')->where(array('aid'=>$aid))->getField('act_type');
		if (0 == $act_type) {
			$this->error('此优惠活动不需要留地址');
		} elseif (1 == $act_type) {
			$cnt = M('activity_address')->where(array('activity_id'=>$aid, 'user_id'=>session('user_id')))->count();
			if ($cnt) {
				$this->success('已经领取');
				exit;
			}
		}
		$activity_info['is_open'] = $activity_info['is_open'] ? '是' : '否';
		$activity_info['act_type'] = $activity_info['act_type'] ? '是' : '否';
		$this->assign('activity_info', $activity_info);
		$this->assign('province_list', get_region(1, 1));
		$this->display();
	}
	
	/**
	 * 处理领取优惠
	 */
	public function doReceive() {
		$data['activity_id'] = intval($_POST['aid']);
		$data['user_id'] = session('user_id');
		$data['province'] = intval($_POST['province']);
		$data['city'] = intval($_POST['city']);
		$data['district'] = intval($_POST['district']);
		$data['address'] = trim($_POST['address']);
		M('activity_address')->add($data);
		$this->success('预留地址成功！', C('WEB_ROOT') . '/index.php?m=Activity&a=actList');
	}
	
	/**
	 * 优惠添加
	 */
	public function add() {
		$this->display();
	}
	
	/**
	 * 优惠添加处理
	 */
	public function insert() {
		$data['title'] = trim($_POST['title']);
		$data['add_time'] = time();
		$data['is_open'] = intval($_POST['is_open']) ? 1 : 0;
		$data['act_type'] = intval($_POST['act_type']) ? 1 : 0;
		$data['content'] = trim($_POST['content']);
		$data['start_date'] = strtotime($_POST['start_date']);
		$data['end_date'] = strtotime($_POST['end_date']);
		if ($data['end_date'] < $data['start_date']) {
			$this->error('结束日期不能小于开始日期！');
		}
		if (empty($data['title'])) {
			$this->error("优惠标题不能为空！");
		} elseif (empty($data['content'])) {
			$this->error("优惠内容不能为空！");
		} else {
			$data['content'] = stripslashes($data['content']); // 反过滤
			M('activity')->add($data);
			$this->success('优惠添加成功！', C('WEB_ROOT') . '/index.php?m=Activity');
		}
	}
	
	/**
	 * 优惠编辑
	 */
	public function edit() {
		$aid = intval($_GET['aid']);
		$activity_info = M('activity')->where(array('aid'=>$aid))->find();
		$activity_info['start_date'] = date('Y-m-d', $activity_info['start_date']);
		$activity_info['end_date'] = date('Y-m-d', $activity_info['end_date']);
		$this->assign('activity_info', $activity_info);
		$this->display();
	}
	
	/**
	 * 优惠更新
	 */
	public function update() {
		$aid = intval($_POST['aid']);
		if ($aid) {
			$data['title'] = trim($_POST['title']);
			$data['is_open'] = intval($_POST['is_open']) ? 1 : 0;
			$data['act_type'] = intval($_POST['act_type']) ? 1 : 0;
			$data['content'] = trim($_POST['content']);
			$data['start_date'] = strtotime($_POST['start_date']);
			$data['end_date'] = strtotime($_POST['end_date']);
			if ($data['end_date'] < $data['start_date']) {
				$this->error('结束日期不能小于开始日期！');
			}
			if (empty($data['title'])) {
				$this->error("优惠标题不能为空！");
			} elseif (empty($data['content'])) {
				$this->error("优惠内容不能为空！");
			} else {
				$data['content'] = stripslashes($data['content']); // 反过滤
				M('activity')->where(array('aid'=>$aid))->save($data);
				$this->success('优惠修改成功！', C('WEB_ROOT') . '/index.php?m=Activity');
			}
		}
	}
	
	/**
	 * 优惠活动查看
	 */
	public function view() {
		$aid = intval($_GET['aid']);
		$activity_info = M('activity')->where(array('aid'=>$aid))->find();
		$activity_info['start_date'] = date('Y-m-d', $activity_info['start_date']);
		$activity_info['end_date'] = date('Y-m-d', $activity_info['end_date']);
		$activity_info['is_open'] = $activity_info['is_open'] ? '是' : '否';
		$activity_info['act_type'] = $activity_info['act_type'] ? '是' : '否';
		$this->assign('activity_info', $activity_info);
		$this->display();
	}
	
	/**
	 * 优惠锁定或解锁
	 */
	public function locked() {
		user_priv('activity_manage');
		$aid = intval($_GET['aid']);
		$Activity = M('activity');
		$is_open = $Activity->where(array('aid'=>$aid))->getField('is_open');
		$is_open = $is_open ? 0 : 1;
		$Activity->where(array('aid'=>$aid))->save(array('is_open'=>$is_open));
		$this->success("公开或关闭优惠成功！", C('WEB_ROOT') . '/index.php?m=Activity');
	}
	
	/**
	 * 优惠删除
	 */
	public function delete() {
		user_priv('activity_manage');
		$aid = intval($_GET['aid']);
		M('activity')->where(array('aid'=>$aid))->delete();
		M('activity_address')->where(array('activity_id'=>$aid))->delete();
		$this->success("优惠删除成功！", C('WEB_ROOT') . '/index.php?m=Activity');
	}
	
	/**
	 * 优惠对应的地址
	 */
	public function address() {
		$aid = intval($_GET['aid']);
		!isset($_GET['p']) ? $_GET['p'] = 1 : null;
		$list_rows = C('LIST_ROWS');
		$title = M('activity')->where(array('aid'=>$aid))->getField('title');
		$ActivityAddress = M('activity_address');
		$where['activity_id'] = $aid;
		$address_list = $ActivityAddress->where($where)->page($_GET['p'] . ',' . $list_rows)->order('address_id DESC')->select();
		foreach ($address_list as $key => $val) {
			$address_list[$key]['province'] = get_region_name($val['province']);
			$address_list[$key]['city'] = get_region_name($val['city']);
			$address_list[$key]['district'] = get_region_name($val['district']);
			$address_list[$key]['user_name'] = get_user_name($val['user_id']);
		}
		import("ORG.Util.Page");
		$count = $ActivityAddress->where($where)->count();
		$Page = new Page($count, $list_rows);
		$this->assign('page', $Page->show());
		$this->assign('title', $title);
		$this->assign('address_list', $address_list);
		$this->display();
	}
	
	/**
	 * 获取活动的地址数
	 * @param integer $activity_id
	 * @return integer
	 */
	private function get_address_count($activity_id) {
		if ($activity_id) {
			return M('activity_address')->where(array('activity_id'=>$activity_id))->count();
		}
	}
	
	/**
	 * 检测是否已经领取奖品
	 * @param integer $aid
	 * @param integer $user_id
	 * @return boolean
	 */
	private function check_receive_status($aid, $user_id) {
		if ($aid) {
			$act_type = M('activity')->where(array('aid'=>$aid))->getField('act_type');
			if (0 == $act_type) {
				return false;
			} elseif (1 == $act_type) {
				$cnt = M('activity_address')->where(array('activity_id'=>$aid, 'user_id'=>$user_id))->count();
				return $cnt ? false : true;
			}
		}
	}
	
	/**
	 * 检测活动优惠状态
	 * @param integer $aid
	 * @return array
	 */
	private function get_activity_status($aid) {
		$ret = array();
		$now = time();
		$activity_info = M('activity')->field('is_open,start_date,end_date')->where(array('aid'=>$aid))->find();
		if (0 == $activity_info['is_open']) {
			$ret = array(
				'status' => false,
				'tips' => '<span style="color:red">锁定</span>'
			);
		} elseif (1 == $activity_info['is_open']) {
			if ($now < $activity_info['start_date']) {
				$ret = array(
					'status' => false,
					'tips' => '<span style="color:red">未开始</span>'
				);
			} elseif (($now >= $activity_info['start_date']) && ($now <= ($activity_info['end_date'] + 86400))) {
				$ret = array(
					'status' => true,
					'tips' => '<span style="color:green">正常</span>'
				);
			} elseif ($now > ($activity_info['end_date'] + 86400)) {
				$ret = array(
					'status' => false,
					'tips' => '<span style="color:red">过期</span>'
				);
			}
		}
		return $ret;
	}

	
}