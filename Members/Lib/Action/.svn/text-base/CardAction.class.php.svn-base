<?php
/**
 * CardAction.class.php 
 * 会员卡号管理页面
 * @author Lok Sun Sep 16 23:22:45 CST 2012
 */

class CardAction extends InitAction {
	/**
	 * 会员卡号列表主页
	 */
	public function index() {
		$Card = M('card');
		!isset($_GET['p']) ? $_GET['p'] = 1 : null;
		$list_rows = C('LIST_ROWS');
		$where = array();
		isset($_GET['card_no']) ? $where['card_no'] = array('LIKE', "%{$_GET[card_no]}%") : null;
		if (isset($_GET['is_bind'])) {
			if ($_GET['is_bind'] == 1) {
				$where['user_id'] = array('GT', 0);
			} elseif ($_GET['is_bind'] == 0) {
				$where['user_id'] = 0;
			}
		}
		$card_list = $Card->where($where)->page($_GET['p'] . ',' . $list_rows)->select();
//		echo $Card->getLastSQL();
		foreach ($card_list as $key => $val) {
			$user_info = get_user_info($val['user_id']);
			$card_list[$key]['user_name'] = $user_info['user_name'] ? '<span style="color:green;">' . $user_info['user_name'] . '</span>' 
																	: '<span style="color:red;">未绑定</span>';
			$card_list[$key]['add_time'] = date('Y-m-d H:i:s', $val['add_time']);
			$card_list[$key]['bind_time'] = $val['bind_time'] ? date('Y-m-d H:i:s', $val['bind_time']) : '';
		}
		import("ORG.Util.Page");
		$count = $Card->where($where)->count();
		$Page = new Page($count, $list_rows);
		$this->assign('page', $Page->show());
		$this->assign('card_list', $card_list);
		$this->display();
	}
	
	/**
	 * 会员卡号上传
	 */
	public function add() {
		$this->display();
	}
	
	/**
	 * 处理会员卡号上传
	 */
	public function insert() {
		user_priv('card_manage');
		$card_no = addslashes(trim($_POST['card_no']));
		$upload_type = intval($_POST['upload_type']);
		$now = time();
		$return_url = C('WEB_ROOT') . '/index.php?m=Card&a=add';
		$Card = M('card');
		if ($upload_type == 1) { // 单个卡号插入
			if (!empty($card_no)) {
				// 检测卡号是否已经存在
				$flg = $Card->where("card_no='$card_no'")->count();
				if (!$flg) {
					$Card->add(array('card_no'=>$card_no, 'add_time'=>$now));
					$this->success("插入卡号成功！", $return_url);
				} else {
					$this->error("插入卡号失败，卡号已经存在！");
				}
			} else {
				$this->error("插入卡号失败，卡号不能为空！");
			}
		} elseif ($upload_type == 2) { // 批量卡号插入
			if (!empty($card_no)) {
				$card_no_arr = explode("\n", $card_no);
				if ($card_no_arr) {
					$cnt = 0;
					foreach ($card_no_arr as $val) {
						$val = trim($val);
						$Card->add(array('card_no'=>$val, 'add_time'=>$now)) ? $cnt++ : null;
					}
					$this->success("成功插入{$cnt}个卡号！", $return_url);
				} else {
					$this->error("批量插入0个卡号！");
				}
			} else {
				$this->error("插入卡号失败，卡号不能为空！");
			}
		}
	}
	
	/**
	 *　卡号信息编辑
	 */
	public function edit() {
		$card_no = addslashes(trim($_GET['card_no']));
		$card_info = M('card')->where(array('card_no'=>$card_no))->find();
		!$card_info ? $this->error("此卡号不存在！") : null;
		if ($card_info['user_id']) { // 是否已经绑定会员
			$user_info = get_user_info($card_info['user_id']);
			$card_info['user_name'] = $user_info['user_name'];
			$card_info['bind_status'] = '<span style="color:green;">已绑定</span>';
			$card_info['is_bind'] = 1;
			$card_info['submit_val'] = '解除绑定';
		} else {
			$card_info['user_name'] = '';
			$card_info['bind_status'] = '<span style="color:red;">未绑定</span>';
			$card_info['is_bind'] = 0;
			$card_info['submit_val'] = '绑定';
		}
		$card_info['add_time'] = $card_info['add_time'] ? date('Y-m-d H:i:s', $card_info['add_time']) : '';
		$card_info['bind_time'] = $card_info['bind_time'] ? date('Y-m-d H:i:s', $card_info['bind_time']) : '';
		$this->assign('card_info', $card_info);
		$this->display();
	}
	
	/**
	 * 卡号信息更新（绑定或解除绑定）
	 */
	public function update() {
		user_priv('card_manage');
		$is_bind = intval($_POST['is_bind']);
		$card_no = addslashes(trim($_POST['card_no']));
		$user_name = addslashes(trim($_POST['user_name']));
		$return_url = C('WEB_ROOT') . '/index.php?m=Card';
		$Card = M('card');
		if (0 == $is_bind) { // 绑定
			$user_info = get_user_info(0, $user_name);
			if ($user_info) {
				$flg = $Card->where(array('user_id'=>$user_info['user_id']))->count();
				if (!$flg) {
					$Card->where(array('card_no'=>$card_no))->save(array('bind_time'=>time(), 'user_id'=>$user_info['user_id']));
					$this->success("绑定成功！", $return_url);
				} else {
					$this->error('绑定失败，此会员已经绑定其它卡号！');
				}
			} else {
				$this->error('绑定失败，不存在此会员！');
			}
		} elseif (1 == $is_bind) { // 解决绑定
			$Card->where(array('card_no'=>$card_no))->save(array('bind_time'=>'', 'user_id'=>0));
			$this->success("解除绑定成功！", $return_url);
		}
	}
	
	/**
	 * 删除卡号
	 */
	public function delete() {
		user_priv('card_manage');
		$card_no = addslashes(trim($_GET['card_no']));
		$flg = M('card')->where(array('card_no'=>$card_no))->limit(1)->delete();
		if ($flg) {
			$this->success("删除" . $card_no . "卡号成功！");
		} else {
			$this->error("删除" . $card_no . "卡号失败！");
		}
	}
	
	

	
}