<?php
namespace Seller\Controller;
use Think\Controller;

class IndexController extends BaseController{

    //获取卖家信息
    public function indexAction(){
        $sellerModel=D('Common/Users');
        $SellerDetail=$sellerModel->getSellerInfo();
        $this->assign('info',$SellerDetail);
        $this->display();
    }

    //卖家资料设置
    public function settingAction(){
        if (IS_POST){
            if (IS_AJAX){
                if (I('type')=='pw'){
                    $oldPw=I('oldPassword');
                    $newPw=I('newPassword');
                    if ($oldPw=='' || $newPw ==''){
                        $this->ajaxInfoReturn('','密码不能为空',0);
                        exit;
                    }
                    $userModel=M('Users');
                    $user=$userModel->where(array('uid'=>session('seller.id')))->find();
                    //验证密码
                    if (md5(md5($oldPw).$user['salt'])==$user['password']){
                        $save['password']=md5(md5($newPw).$user['salt']);
                        $re=$userModel->where(array('uid'=>session('seller.id')))->setField($save);
                        if ($re!==false){
                            $this->ajaxInfoReturn('','密码修改成功',1);
                        }else{
                            $this->ajaxInfoReturn('','密码修改失败',0);
                        }
                    }else{
                        $this->ajaxInfoReturn('','密码错误',0);
                    }
                }elseif(I('type')=='store'){
                    $save['store_name']=I('store_name');
                    $save['mobile']=I('mobile');
                    if ($save['store_name']==''){
                        $this->ajaxInfoReturn('','卖家名不能为空',0);
                        exit;
                    }
                    if (!preg_match("/^1[34578]\d{9}$/", $save['mobile'])){
                        $this->ajaxInfoReturn('','手机号格式不正确',0);
                        exit;
                    }
                    $re=M('store')->where(array('sid'=>session('seller.store_id')))->save($save);
                    if ($re!==false){
                        $this->ajaxInfoReturn('','修改成功',1);
                    }else{
                        $this->ajaxInfoReturn('','修改失败',0);
                    }
                }
            }else{
                $this->tips('提交方式错误',0,3,$_SERVER['HTTP_REFERER']);
            }
        }else{
            //获取卖家基本信息
            $info=M('store')->field('store_name,mobile,logo')->where(array('sid'=>session('seller.store_id')))->find();
            $this->assign('info',$info);
            $this->display();
        }
    }
    //申请成为卖家
    public function applyAction(){
        $id = session('seller.id');
        $returnUrl = $_SERVER['HTTP_REFERER'];
        $is_seller = M('users_seller')->where("user_id=$id")->find();
        if ($is_seller) {
            $_GET['action'] = 'edit';
            $action = 'edit';
        } else {
            $_GET['action'] = 'add';
            $action = 'add';
        }
        if(IS_POST) {
            $data = I('data');
            //验证post数据是否合法
            //var_dump($data);
            /**PC端申请,数据统一*/
            if (count($data['spread_way']) == 2) {
                $data['spread_way'] = 2;
            }else {
                $data['spread_way'] = $data['spread_way'][0];
            }
            if ($data['address'] == '') {
                $this->ajaxInfoReturn('','亲，请填写地址!',0);
            }elseif ($data['category'] =="") {
                $this->ajaxInfoReturn('','亲，请填写品牌类别!',0);
            }elseif ($data['mobile'] =="") {
                $this->ajaxInfoReturn('','亲，请输入联系方式',0);
            }elseif ($data['name'] == '') {
                $this->ajaxInfoReturn('','亲，请填写联系人姓名!',0);
            }elseif ($data['company_name'] == '') {
                $this->ajaxInfoReturn('','亲，请填写公司名!',0);
            }elseif ($data['spread_way'] == '') {
                $this->ajaxInfoReturn('','亲，请填写品牌推广方式!',0);
            }elseif (!regexp_str('email',$data['email'])) {
                $this->ajaxInfoReturn('','邮箱格式有误!',0);
            }
            //post提交申请,判断添加更新
            if ($action == 'add') {
                //添加用户申请信息
                $data['user_id'] = $id;
                $res = M('users_seller')->add($data);
                if ($res) {
                    //增加通知
                    insert_notice('admin','auditSeller','新增卖家申请ID:'.$res.'待审核',0);
                    $this->ajaxInfoReturn('', "申请成功", 1);
                } else {
                    $this->ajaxInfoReturn('', "申请失败", 0);
                }
            } else {
                //更新用户申请信息
                $data['user_id'] = $id;
                $res = M('users_seller')->where("user_id=$id")->save($data);
                if ($res !== false) {
                    $this->ajaxInfoReturn('', "更新成功", 1);
                } else {
                    $this->ajaxInfoReturn('', "更新失败", 0);
                }
            }
            $this->assign('returnUrl', $returnUrl);
            redirect($returnUrl);
        }else{
            $info = M('users_seller')->where("user_id=$id")->find();
            $is_seller = M('users')->where("uid=$id")->getField('is_seller');
            $this->assign('is_seller', $is_seller);
            $this->assign('info', $info);
            $this->display();
        }
    }



    /******************************************************卖家通知********************************************************************/
    //获取卖家通知
    public function getNoticeAction(){
        if (IS_AJAX){
            $noticeModel=M('notice');
            //本次查询时间
            $condition['time']=array('lt',time());
            $condition['to']='seller';
            $condition['s_id']=session('seller.id');
            $condition['is_readed']=0;
            //待发货订单数量
            $condition['type']='waitSend';
            $result['wait_num']=$noticeModel->where($condition)->count();
            $this->ajaxInfoReturn($result,'',1);
        }else{
            $this->tips('提交方式错误',0,3,$_SERVER['HTTP_REFERER']);
        }
    }
    //卖家通知列表
    public function noticeAction(){
        $condition['to']='seller';
        $condition['s_id']=session('seller.store_id');
        $pageNum=I('p',1);
        $noticeModel=D('Common/Notice');
        $result=$noticeModel->getNoticePageList($pageNum,$condition);
        $this->assign('list',$result['list']);
        $this->assign('pageCount',$result['pageCount']);
        $this->assign('pageShow',$result['show']);
        $this->display();
    }
    //处理通知
    public function readAction(){
        $id=I('id');
        if ($id=='all'){
            $condition['to']='seller';
            $condition['s_id']=session('seller.id');
            $condition['is_readed']=0;
            M('notice')->where($condition)->setField('is_readed',1);
            header("Location:".U('Seller/Index/notice')."");
        }else{
            $re=M('notice')->where(array('id'=>$id))->setField('is_readed',1);
            if ($re!==false){
                header("Location:".U('Seller/Order/index')."?pageType=1");
            }
        }
    }
}