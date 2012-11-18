<?php
/**
 * SalesAction.class.php
 * 销售返点
 * @author Lok 2012/11/12
 */
class SalesAction extends InitAction {

	/**
	 * 首页
	 */
	public function index() {
		
	}
	/**
	 * 返点设置
	 */
	public function config() {
		$config_list = M('config')->select();
		foreach ($config_list as $val) {
			$config[$val['key_name']] = $val['key_value'];
		}
		$this->assign('config', $config);
		$this->display();
	}
	/**
	 * 返点设置处理
	 */
	public function setConfig() {
		$to_agents = floatval($_POST['to_agents']);
		$to_franchise = floatval($_POST['to_franchise']);
		if ($to_agents >= 0 && $to_agents < 1) {} 
		else {
			$this->error("对代理商的返点，请填写正确的小数");
		}
		if ($to_franchise >= 0 && $to_franchise < 1) {} 
		else {
			$this->error("对加盟店的返点，请填写正确的小数");
		}
		if (($to_agents + $to_franchise) > 1) {
			$this->error("代理商加上加盟店的返点已经超过总量1");
		}
		M('config')->where(array('key_name'=>'to_agents'))->save(array('key_value'=>$to_agents));
		M('config')->where(array('key_name'=>'to_franchise'))->save(array('key_value'=>$to_franchise));
		$this->success('设置返点成功！', C('WEB_ROOT') . '/index.php?m=Sales&a=config');
	}
	
	/**
	 * 上传天猫数据
	 */
	public function upload() {
        $upload_file = M('upload_files')->order("upload_id DESC")->select();
        foreach ($upload_file as $key => $val) {
            $upload_file[$key]['upload_time'] = date("Y-m-d H:i:s", $val['upload_time']);
            if (0 == $val['status']) {
                $upload_file[$key]['status_msg'] = "<span style='color:orange'>未入库</span>";
            } elseif (1 == $val['status']) {
                $upload_file[$key]['status_msg'] = "<span style='color:green'>已入库</span>";
            } elseif (2 == $val['status']) {
                $upload_file[$key]['status_msg'] = "<span style='color:red'>已取消</span>";
            }
        }
        $this->assign('upload_file', $upload_file);
		$this->display();
	}
	
	/**
	 * 处理上传的天猫数据
	 */
	public function doUpload() {
        $UploadModel = D("Upload");
        $result = $UploadModel->uploadSalesFile();
        if (!$result['flg']) { // 上传失败
            $this->error($result['msg']);
        } else {
            $this->success($result['msg']);
        }
		exit;
	}

    /**
     * 删除上传文件
     */
    public function delUploadFile() {
        $upload_id = intval($_GET['id']);
        user_priv('system_manage'); // 权限检测
        if ($upload_id) {
            $SalesModel = D('Sales');
            if ($SalesModel->delUploadFile($upload_id)) {
                $this->success("删除文件成功");
            } else {
                $this->error("删除文件失败");
            }
        }
    }

    /**
     * 文件数据入库
     */
    public function insert() {
        $upload_id = intval($_GET['id']);
        user_priv('system_manage'); // 权限检测
        if ($upload_id) {
            $SalesModel = D('Sales');
            if ($SalesModel->insertDb($upload_id)) {
                $url = C('WEB_ROOT') . '/index.php?m=Sales&a=history';
                $this->success("文件入库成功", $url);
            } else {
                $this->error("文件状态不对，入库失败");
            }
        }
    }

    /**
	 * 历史天猫数据
	 */
	public function history() {
        $TaobaoSales = M('taobao_sales');
        $list_rows = C('LIST_ROWS');
        !isset($_GET['p']) ? $_GET['p'] = 1 : null;
        $where = array();
        if (isset($_GET['upload_id'])) {
            $where['upload_id'] = intval($_GET['upload_id']);
        }
        if (isset($_GET['rebate_status']) && $_GET['rebate_status'] != 99) {
            $where['rebate_status'] = intval($_GET['rebate_status']);
        }
        if (!empty($_GET['order_sn'])) {
            $order_sn = trim($_GET['order_sn']);
            $where['_string'] = " (order_sn like '%{$order_sn}%') ";
        }

        $sales_list = $TaobaoSales->where($where)->page($_GET['p'] . ',' . $list_rows)->order("upload_id DESC")->select();
        foreach ($sales_list as $key => $val) {
            $sales_list[$key]['rebate_status_msg'] = $val['rebate_status'] ? '<span style="color:red;">已返点</span>' :
                                                               '<span style="color:green;">未返点</span>';
            $sales_list[$key]['user_name'] = get_username_by_taobaoname($val['taobao_name']);
        }

        import("ORG.Util.Page");
        $count = $TaobaoSales->where($where)->count();
        $Page = new Page($count, $list_rows);
        $this->assign('page', $Page->show());
        $this->assign('sales_list', $sales_list);
		$this->display();
	}


}



?>