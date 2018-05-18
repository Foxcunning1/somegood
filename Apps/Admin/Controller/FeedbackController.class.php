<?php
/*后台广告位控制器
 *
 * */
namespace Admin\Controller;
use Think\Controller;
class FeedbackController extends BaseController {
    //广告位列表
    public function listAction(){
        //判断权限
        if(!parent::checkAdminRoleAction('feedback_manage','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $page = I('get.p',1);
        $status = I('status', '4');
        $keywords = I('keywords');
        if($keywords) {
            $condition['content'] = array('like', '%' . $keywords . '%');
            $pageCondition['keywords'] = $keywords;
        }
        $pageCondition['is_show'] = $status;
        $result = D('Common/Feedback')->getFeedbackPageList($page, C('COMMON_PAGE_NUM'),$condition,$pageCondition);
        $this->assign('keywords',$keywords);
        $this->assign('status',$status);
        $this->assign('list',$result['list']);
        $this->assign('page',$result['show']);
        $this->display();
    }

    /*删除广告*/
    public function delAction(){
        $action = I('action','del');
        switch ($action){
            case 'auth':
                $actionType = 'audit';
                break;
            default:
                $actionType = 'delete';
                break;
        }
        //判断权限
        if(!parent::checkAdminRoleAction('feedback_manage',$actionType)){
            $this->error('亲，您无权进行操作！');
        }
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        $idArr = I('ids');
        $feedbackModel  = D('Common/Feedback');
        //判断id是数组还是一个数值
        $condition['id'] = array('in',$idArr);
        switch ($action){
            case 'auth':
                $result = $feedbackModel->updateStatus($condition);
                break;
            default:
                $result = $feedbackModel->del($condition);
                break;
        }
        if($action=="auth"){
            $actionType = "edit";
            $errTips = "审核失败!";
            $successTips = "审核成功！";
            $msgInfo = '成功审核了' . $result . '条留言信息，留言ID分别为：' . implode(',', $idArr) . '!';
        }else{
            $actionType = "del";
            $errTips = "删除失败!";
            $successTips = "删除成功！";
            $msgInfo = '成功删除了' . $result . '条留言信息，留言ID分别为：' . implode(',', $idArr) . '!';
        }
        if($result!==false){
            add_admin_log($actionType, session('admin.admin_name') . $msgInfo);
            jscript_msg($successTips, $returnUrl);
        }else{
            jscript_msg_tips($errTips);
        }
    }
}