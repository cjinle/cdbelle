<?php
/**
 * UploadModel.class.php
 * 上传模块
 * @author Lok 2012/11/13
 */

class UploadModel extends Model {
    protected $upload_path = "Public/Uploads/";
    public function __construct() {
        import("ORG.Net.UploadFile");
    }

    /**
     * 上传销售列表文件
     * @return array
     */
    public function uploadSalesFile() {
        $upload = new UploadFile();
        $upload->maxSize  = 3145728;
        $upload->allowExts  = array('csv');
        $upload->savePath =  $this->upload_path;
        $upload->saveRule = $this->create_upload_file_name();
        $return_arr = array();
        if(!$upload->upload()) {
            $return_arr = array('flg'=>FALSE, 'msg'=>$upload->getErrorMsg());
        }else{
            $upload_info = $upload->getUploadFileInfo();
            $data['upload_path'] = C('WEB_ROOT') . '/' . $upload_info[0]['savepath'] . $upload_info[0]['savename'];
            $data['upload_time'] = time();
            $data['filename'] = $upload_info[0]['savename'];
            $data['source_filename'] = $upload_info[0]['name'];
            $data['user_name'] = session('user_name');
            M("upload_files")->add($data);
            $return_arr = array('flg'=>TRUE, 'msg'=>'文件上传成功！');
        }
        return $return_arr;
    }

    /**
     * 生成文件名
     * @return string
     */
    private function create_upload_file_name() {
        return 'Sales_' . date('YmdHis') . '_' . rand(1000,9999);
    }
}



?>