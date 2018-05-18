<?php
/*Home Ajax统一提交类*/
namespace Home\Controller;
use Think\Controller;
class AjaxController extends Controller {
    //添加留言
    public function addFeedbackAction(){
        $feedbackModel = D("Common/Feedback");
        if(!$feedbackModel->autoCheckToken($_POST)){
            $this->ajaxInfoReturn("","亲，请不要重复提交表单!",0);
        }else{
            $dataArr = I("data");
            if(!$dataArr['name']){
                $this->ajaxInfoReturn("亲，请填写您的姓名!");
                die;
            }
            if(!$dataArr['email']){
                $this->ajaxInfoReturn("亲，请填写您的Email!");
                die;
            }
            if(!$dataArr['mobile']){
                $this->ajaxInfoReturn("亲，请填写您的手机号码!");
                die;
            }
            if(!$dataArr['content']){
                $this->ajaxInfoReturn("亲，请填写留言内容!");
                die;
            }
            $dataArr['add_time'] = time();
            $fid = $feedbackModel->add($dataArr);
            if($fid > 0){
                $this->ajaxInfoReturn("","提交成功!",1);
            }else{
                $this->ajaxInfoReturn("","提交失败!",0);
            }
        }

    }
}
