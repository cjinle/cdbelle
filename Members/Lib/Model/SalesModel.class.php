<?php
/**
 * 销售模块
 * @author Lok 2012-11-18
 */

class SalesModel extends Model {
    protected $UploadFiles;
    protected $TaobaoSales;

    public function __construct() {
        $this->UploadFiles = M('upload_files');
        $this->TaobaoSales = M('taobao_sales');
    }

    /**
     * 获取上传文件信息
     * @param integer $upload_id
     * @return array
     */
    public function getFileInfo($upload_id = 0) {
        if ($upload_id) {
            return $this->UploadFiles->where(array('upload_id'=>$upload_id))->find();
        }
    }

    /**
     * 删除上传文件
     * @param integer $upload_id
     * @return boolean
     */
    public function delUploadFile($upload_id) {
        $file_info = $this->getFileInfo($upload_id);
        if (0 == $file_info['status']) {
            M("upload_files")->where(array('upload_id'=>$upload_id))->delete();
            $file_path = str_replace("/Members/", "", $file_info['upload_path']);
            @unlink($file_path);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 文件数据入库
     * @param integer $upload_id
     * @return boolean
     */
    public function insertDb($upload_id) {
        $flg = FALSE;
        $file_info = $this->getFileInfo($upload_id);
        $file_path = str_replace("/Members/", "", $file_info['upload_path']);
        $file_data = $this->readFile($file_path);
        if ($file_data) {
//            var_dump($file_data);exit;
            foreach ($file_data as $key => $val) {
                $data['upload_id'] = $upload_id;
                $data['order_sn'] = $val[0];  // 订单编号
                $data['taobao_name'] = $this->gbk2utf8($val[1]);  // 买家会员名
                $data['alipay'] = $val[2];   // 买家支付宝账号
                $data['goods_fee'] = $val[3];  // 买家应付货款
                $data['shipping_fee'] = $val[4];   // 买家应付邮费
                $data['pay_point'] = $val[5];   // 买家支付积分
                $data['total_fee'] = $val[6];   // 总金额
                $data['rebate_point'] = $val[7];  // 返点积分
                $data['paid_fee'] = $val[8];   // 买家实际支付金额
                $data['paid_point'] = $val[9];   // 买家实际支付积分
                $data['order_status'] = $this->gbk2utf8($val[10]);   // 订单状态
                $data['buyer_msg'] = $this->gbk2utf8($val[11]);   // 买家留言
                $data['buyer_name'] = $this->gbk2utf8($val[12]);   // 收货人姓名
                $data['address'] = $this->gbk2utf8($val[13]);   // 收货地址
                $data['shipping_type'] = $this->gbk2utf8($val[14]);   // 运送方式
                $data['phone'] = $val[15];   // 联系电话
                $data['cellphone'] = $val[16];   // 联系手机
                $data['create_time'] = $val[17];   // 订单创建时间
                $data['paid_time'] = $val[18];   // 订单付款时间
                $data['goods_name'] = $this->gbk2utf8($val[19]);   // 宝贝标题
                $data['close_reason'] = $this->gbk2utf8($val[27]);   // 订单关闭原因
                $ts_id = $this->TaobaoSales->add($data);
                $this->doRebate($ts_id);
//                echo $this->TaobaoSales->getLastSql();
//                exit;
            }
            $this->UploadFiles->where(array('upload_id'=>$upload_id))->save(array('status'=>1));
            $flg = TRUE;
        }
        return $flg;
    }

    /**
     * 文件编码转成utf-8
     * @param string $str
     * @return string
     */
    private function gbk2utf8($str) {
        return @iconv('gbk', 'utf-8', $str);
    }

    /**
     * 读取CSV文件
     * @param string $file_path
     * @return array
     */
    private function readFile($file_path) {
        if (file_exists($file_path)) {
            $return_arr = array();
            $row = 1;
            $handle = fopen($file_path,"r");
            while ($data = fgetcsv($handle, 1000, ",")) {
                if ($row > 1) {
                    $return_arr[] = $data;
                }
                $row++;
            }
            fclose($handle);
            return $return_arr;
        } else {
            throw_exception("文件不存在");
        }
    }

    /**
     * 处理返点
     * @param integer $ts_id
     * @return void
     */
    private function doRebate($ts_id = 0) {
        if ($ts_id) {
            $ts_info = $this->TaobaoSales->where(array('ts_id'=>$ts_id))->find();
//            var_dump($ts_info);exit;
            if (0 == $ts_info['rebate_status']) {
                $now = time();
                $rebate_rate = $this->getRebateRate();
//                var_dump($rebate_rate);exit;
                $relation = $this->getRelation($ts_info['taobao_name']);
//                var_dump($)
                if ($ts_info['paid_fee'] && $relation['user_id'] && $relation['franchise_id'] && $relation['agents_id']) {
//                    echo 123;exit;;
                    $Users = M('users');
                    // 加盟店返点
                    $franchise_rebate = ($ts_info['paid_fee'] - $ts_info['shipping_fee']) * $rebate_rate['to_franchise'];
                    $Users->where(array('user_id'=>$relation['franchise_id']))->setInc('rebate', $franchise_rebate);
                    $f_rebate_log['rebate'] = $franchise_rebate;
                    $f_rebate_log['rebate_rate'] = $rebate_rate['to_franchise'];
                    $f_rebate_log['user_id'] = $relation['user_id'];
                    $f_rebate_log['rebate_id'] = $relation['franchise_id'];
                    $f_rebate_log['rebate_user_priv'] = C('FRANCHISE');
                    $f_rebate_log['upload_id'] = $ts_info['upload_id'];
                    $f_rebate_log['order_sn'] = $ts_info['order_sn'];
                    $f_rebate_log['paid_fee'] = $ts_info['paid_fee'];
                    $f_rebate_log['log_type'] = 1;
                    $f_rebate_log['rebate_time'] = $now;
                    $f_rebate_log['remark'] = "加盟店增加{$franchise_rebate}返点";
                    $this->rebateLog($f_rebate_log);

                    // 代理商返点
                    $agents_rebate = ($ts_info['paid_fee'] - $ts_info['shipping_fee']) * $rebate_rate['to_franchise'];
                    $Users->where(array('user_id'=>$relation['agents_id']))->setInc('rebate', $agents_rebate);
                    $a_rebate_log['rebate'] = $agents_rebate;
                    $a_rebate_log['rebate_rate'] = $rebate_rate['to_agents'];
                    $a_rebate_log['user_id'] = $relation['user_id'];
                    $a_rebate_log['rebate_id'] = $relation['agents_id'];
                    $a_rebate_log['rebate_user_priv'] = C('AGENTS');
                    $a_rebate_log['upload_id'] = $ts_info['upload_id'];
                    $a_rebate_log['order_sn'] = $ts_info['order_sn'];
                    $a_rebate_log['paid_fee'] = $ts_info['paid_fee'];
                    $a_rebate_log['log_type'] = 1;
                    $a_rebate_log['rebate_time'] = $now;
                    $a_rebate_log['remark'] = "代理商增加{$agents_rebate}返点";
                    $this->rebateLog($a_rebate_log);

                    $this->TaobaoSales->where(array('ts_id'=>$ts_id))->save(array('rebate_status'=>1));
                }
            }
        }
    }

    /**
     * 返点清零
     * @return void
     */
    public function rebateClear() {
        $Users = M('uesrs');
        $now = time();
        $where = " rebate > 0 AND user_priv IN (" . C('AGENTS') . ", " . C('FRANCHISE') . ") ";
        $rebate_user = $Users->where($where)->filed('user_id,parent_id,user_priv,rebate')->select();
        foreach ($rebate_user as $key => $val) {
            $rebate = $val['rebate'] * (-1);
            $rebate_log['rebate'] = $rebate;
            $rebate_log['rebate_rate'] = 0;
            $rebate_log['user_id'] = $val['parent_id'];
            $rebate_log['rebate_id'] = $val['user_id'];
            $rebate_log['rebate_user_priv'] = $val['user_priv'];
            $rebate_log['upload_id'] = 0;
            $rebate_log['order_sn'] = 0;
            $rebate_log['paid_fee'] = 0;
            $rebate_log['log_type'] = 1;
            $rebate_log['rebate_time'] = $now;
            $rebate_log['remark'] = "年底清空{$rebate}返点";
            $this->rebateLog($rebate_log);
            $Users->where(array('uesr_id'=>$val['user_id']))->save(array('rebate'=>0));
        }
    }

    /**
     * 返点日志
     * @param array $log
     * @return void
     */
    private function rebateLog($log = array()) {
        if ($log) {
//            var_dump($log);exit;
            M('rebate_log')->add($log);
        }
    }

    /**
     * 获取返点比率
     * @return array
     */
    private function getRebateRate() {
        $Config = M('config');
        $to_agents = $Config->where(array('key_name'=>'to_agents'))->getField('key_value');
        $to_franchise = $Config->where(array('key_name'=>'to_franchise'))->getField('key_value');
        return array('to_agents'=>$to_agents, 'to_franchise'=>$to_franchise);
    }

    /**
     * 通过淘宝名找关系
     * @param string $taobao_name
     * @return array
     */
    public function getRelation($taobao_name = '') {
        if (!empty($taobao_name)) {
            $Users = M('users');
            $relation = array('user_id', 'franchise_id', 'agents_id');
            $user_info = $Users->where(array('taobao_name'=>$taobao_name, 'user_priv'=>C('USERS')))
                               ->field('user_id,parent_id')->find();
            if ($user_info) {
                $relation['user_id'] = $user_info['user_id'];
                $relation['franchise_id'] = $user_info['parent_id'];
                $relation['agents_id'] = $Users->where(array('user_id'=>$user_info['parent_id']))->getField('parent_id');
            }
            return $relation;
        }
    }

}