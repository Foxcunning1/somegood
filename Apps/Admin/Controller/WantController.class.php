<?php
/**
 * 后台求购信息管理类
 * Created by PhpStorm.
 * User: ZHY
 * Date: 2017/8/3
 * Time: 9:41
 */
namespace Admin\Controller;
use Think\Controller;
class WantController extends AjaxController {
    //求购信息列表
    public function indexAction(){
        if(!parent::checkAdminRoleAction('want_list_manage','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $cateId = I('cate_id');
        $property = I('property','all','strip_tags');
        $keywords = I('keywords');
        $pageNum = I('p',1,'strip_tags');
        if($keywords) {
            $condition['w_title|w_content'] = array('like', '%' . $keywords . '%');
            $pageCondition['keywords'] = $keywords;
        }
        //属性列表
        $property_list = array(
            'all' => '所有属性',
            'lock' => '待审核',
            'unlock' => '已审核',
            'hidden' => '不显示'
        );

        if($cateId) {
            $condition['w_cateid'] = $cateId;
            $pageCondition['w_cateid'] = $cateId;
            $this->assign('cate_id',$cateId);
        }
        if($property!="all"){
            $property1 = 0;
            switch($property){
                case 'lock':
                    $property1 = 0;
                    break;
                case 'unlock':
                    $property1 = 1;
                    break;
                case 'hidden':
                    $property1 = 2;
                    break;
            }
            $condition['status'] = $property1;
            $pageCondition['property'] = $property1;
        }
//        商品类别
        $cate_list=parent::treeAction(D('goodsCategory')->order('sort_num ASC , id DESC')->select(), 0, 1);
        //获取求购信息列表
        $wantModel=D('Common/want');
        $order = 'sort_num ASC , w_id DESC';
        $result = $wantModel->getWantPageList($pageNum,$order,$condition,$pageCondition);
        $this->assign('property', $property);
        $this->assign('cate_list',$cate_list);
        $this->assign('property_list',$property_list);
        $this->assign('list',$result['list']);
        $this->assign('page',$result['show']);
        $this->display("Want/want_list");
    }


    /** 求购信息详情
     *
     */
    public function detailAction(){
        if(!parent::checkAdminRoleAction('want_list_manage','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $wantModel=D('Common/want');
        $detail=$wantModel->where(array('wid'=>I('wid')))->relation(true)->find();
        $this->assign('status_list',array('待审核','正常','不显示'));
        $this->assign('detail',$detail);
        $this->display("Want/want_detail");
    }

    /**批量删除求购信息
     * parm     string        $ids               需求ID字符串
     */
    public function delAction(){
    	//判断权限
        if(!parent::checkAdminRoleAction('want_list_manage','delete')){
            $this->error('亲，您无权进行操作！');
        }
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        $ids             = I('w_id');
        $wantModel  = D('Common/want');
        //判断id是数组还是一个数值
        $count_record = 0;
        if(is_array($ids)){
            $condition["w_id"] = array('in',$ids);
            $del_count = $wantModel->where($condition)->delete();
            if($del_count!==false){
                $count_record += $del_count;
            }else{
                $count_record = false;
            }
        }else{
            $condition["w_id"] = $ids;
            $count_record = $wantModel->where($condition)->delete();
        }
        if($count_record!==false) {
//            add_admin_log('del',session('admin_name').'成功删除了'.$count_record.'条系统导航信息!');
            jscript_msg("删除成功 ",$returnUrl);
        }else{
            jscript_msg("删除失败 ",$returnUrl);
        }
    }
    //求购信息审核
    public function auditAction(){
        //判断权限
        if(!parent::checkAdminRoleAction('want_list_manage','audit')){
            $this->error('亲，您无权进行操作！');
        }
        $status = M('want')->where(array('id' => I('wid')))->save(array('status'=>I('status')));
        if ($status!== false) {
            add_admin_log('edit', session('admin_name') . '成功修改了ID为' . I('wid') . '的求购信息的审核状态!');
            jscript_msg("修改成功 ", U("Want/index"));
        } else {
            jscript_msg_tips("修改失败!");
        }
    }
}