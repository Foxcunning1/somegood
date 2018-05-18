<?php
//求购模型
namespace Admin\Model;
use Think\Page;

class AttachmentModel extends Model{

    /*添加附件
     *return  int       返回添加数据自增id
     * */
    public function  add($data){
        $attachmentModel = M('attachment');
        if($data){
            return $attachmentModel->data($data)->add();
        }else{
            return 0;
        }
    }

    /*修改附件信息
     * @param  $aid                int              附件自增ID
     * @param  $data               array            附件数据
     * @return bool
     * */
    public function edit($aid,$data){
        $attachmentModel = M('attachment');
        if($data&&$aid){
            return $attachmentModel->data($data)->add();
        }else{
            return false;
        }
    }

    /*删除附件信息
     * @param  $modeltype         int              附件对应的控制器类型
     * @param  $id                int              附件对应的信息id
     * @param  $where             array            查询条件
     * @return bool
     * */
    public function del($id, $modeltype = 'article', $where){
        $attachmentModel = M('attachment');
        if($id>0){
            $where['id'] = $id;
            $where['modelid'] = C('MODEL_TYPE.'.$modeltype);
            $attachmentModel->where($where)->delete();
            return true;
        }else{
            return false;
        }
    }
}