<?php
/*计划任务*/
namespace Admin\Controller;
use Think\Controller;

class CronController extends BaseController{
    /**
     * 计划任务列表
     */
    public function indexAction(){
        if(!parent::checkAdminRoleAction('cron_plan','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $list=M('plan')->select();
        $this->assign('list',$list);
        $this->display();
    }

    /**
     * 计划任务启动/停止
     */
    public function startAction(){
        //判断权限
        if(!parent::checkAdminRoleAction('article_list_manage','edit')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $id=I('id');
        $save['is_start']=I('is_start');
        $planModel=M('plan');
        $re=$planModel->where(array('id'=>$id))->setField($save);
        if ($re!==false){
            //更新F
            $crons=$planModel->where(array('is_start'=>1))->select();
            F('CRON_CONFIG',$crons);
            $this->ajaxInfoReturn($id,'',1);
        }else{
            $this->ajaxInfoReturn('','操作失败',0);
        }
    }

    /**
     * 计划任务修改
     */
    public function editAction(){
        //判断权限
        if(!parent::checkAdminRoleAction('article_list_manage','edit')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $planModel=M('plan');
        $id=I('id');
        if(IS_POST){
            $data=I('data');
            $data['start_time']=strtotime($data['start_time']);
            $re=$planModel->where(array('id'=>$id))->setField($data);
            if ($re!==false){
                //更新F
                $crons=$planModel->where(array('is_start'=>1))->select();
                F('CRON_CONFIG',$crons);
                jscript_msg('修改成功',I('returnUrl'));
            }else{
                jscript_msg_tips('修改失败');
            }
        }else {
            //获取计划任务信息
            $info=$planModel->where(array('id'=>$id))->find();
            $this->assign('info',$info);
            $this->assign('returnUrl',$_SERVER['HTTP_REFERER']);
            $this->display();
        }
    }
}
