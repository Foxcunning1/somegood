<?php

namespace Admin\Controller;
use Think\Controller;


/*平台卖家管理类
* date 2017-10-10
*/
class SellerController extends AjaxController{

    protected $dataModel;

    public function indexAction(){
        $this->display();
    }


    //用户申请成为卖家审核页面
    public function checkAction(){
        if(!parent::checkAdminRoleAction('seller_manage','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
            $is_seller=I('is_seller','3');
            $keywords=I('keywords','','strip_tags');
            $pageNum=I('p','1');
            //通知设为已读
            if (I('notice')==1){
                set_readed('admin','auditSeller');
            }
            if ($is_seller != 3) {
                $condition['u.is_seller'] = $is_seller;
                $pageCondition['u.is_seller'] = $is_seller;
            }
            if ($keywords) {
                $condition['us.mobile|us.company_name|us.name'] = array('like',$keywords.'%');
                $pageCondition['keywords']=$keywords;
            }
            $status = array(
                '3' => '全部',
                '0' => '未审核',
                '1' => '已通过',
                '2' => '已拒绝',

            );
            $sellerModel=D('Common/UsersSeller');
            $res=$sellerModel->checkSellerApply($condition,$pageNum,$pageCondition);
            $this->assign('is_seller',$is_seller);
            $this->assign('list',$res['list']);
            $this->assign('status',$status);
            $this->assign('page',$res['page']);
            $this->display();
    }


    public function passAction(){
        if (IS_POST) {
            //更新通过状态
            $ids=I('ids');
            foreach ($ids as $key => $v) {
                M('users')->where("uid=$v")->setField('is_seller','1');
                add_admin_log('edit',  session('admin_name').'成功更新了ID为'.$v.'的卖家申请信息!');
            }
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

        /**添加店铺信息
        *@param          $user_id                 用户id
        *@param          $pass                      is_seller的状态
        *@param          $data                      数据
        */
        public function addStoreInfo($pass,$user_id){
            $storeModel=M('store');
            if ($pass == 1 && $storeModel->where("user_id=$user_id")->count() ==0) {
                $info=M("users_seller")->where("user_id=$user_id")->find();
                $data['store_name']=$info['company_name'];
                $data['email']=$info['email'];
                $data['owner']=$info['name'];
                $data['mobile']=$info['mobile'];
                $storeModel->data($data)->add();
            }
        }

    //申请卖家详情页
    public function detailAction(){
        if(!parent::checkAdminRoleAction('seller_manage','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $user_id=I('id');
        if (IS_AJAX) {
            //通过审核
            $pass=I('pass');
            $rs=M('users')->where("uid=$user_id")->setField('is_seller',$pass);
            if ($rs) {
                add_admin_log('edit',  session('admin_name').'成功更新了ID为'.$user_id.'的卖家申请信息!');
                //$this->addStoreInfo($pass,$user_id);      //添加店铺信息
                $this->ajaxInfoReturn('','操作成功!',1);
            }else {
                $this->ajaxInfoReturn('','操作失败!',0);
            }
        }else {
            $res=M('users_seller')->alias('us')->field("us.*,u.id_cardnum,u.id_cardphoto,u.is_seller,group_concat(gc.title) as category_name")
                ->join("LEFT JOIN __USERS__ AS u on u.uid = us.user_id")
                ->join("LEFT JOIN __USERS_SELLER_CATEGORY__ AS usc on usc.user_id = us.user_id")
                ->join("LEFT JOIN __GOODS_CATEGORY__ AS gc on gc.id = usc.cat_id")
                ->where("us.user_id=$user_id")->select();
            //获取卖家申请区域
            $returnUrl=$_SERVER['HTTP_REFERER'];
            $area_list=$res['0']['area_list'];
            $condition['id']=array('in',$area_list);
            if ($area_list) {
                $region=M('region')->field('name')->where($condition)->order('id DESC')->select();
                foreach ($region as $key => $v) {
                    $region_one=$v['name'].' '.$region_one;
                }
            }
            //反序列化图片
            $photo=unserialize($res['id_cardphoto']);
            $this->assign('photo',$photo);
            $this->assign('returnUrl',$returnUrl);
            $this->assign('region',$region_one);
            $this->assign('info',$res['0']);
            $this->display();
        }
    }
    /************************************************企业类型*********************************************************************/
    //获取企业类型列表
    public function industrysTypeAction(){
        if(!parent::checkAdminRoleAction('industrys_type','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $keywords = I('keywords');
        if($keywords) {
            $condition['title'] = array('like', '%' . $keywords . '%');
        }
        $list = D('Admin/Goods')->getIndustrysTypeTreeListAction($condition);
        $this->assign('list',$list);
        $this->display();
    }

    //新增/修改类型
    public function industrysTypeAddAction(){
        $action = I('action','add', 'strip_tags');//操作类型
        //判断权限
        if(!parent::checkAdminRoleAction('industrys_type',$action)){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $industrysTypeModel = M('industrysType');
        $id = I('id', 0, 'strip_tags');//属性ID
        if(IS_POST){
            $returnUrl=I('returnUrl');
            $dataArr = I('data');
            $class_list              = get_deep_table_class_list('industrys_type',$dataArr['parent_id']);
            $dataArr['class_list']   = $class_list;//导航层级关系
            //获得父类的层级数量
            $class_layer             = get_deep_table_class_layer('industrys_type',$dataArr['parent_id']);
            $dataArr['class_layer']  = $class_layer + 1;//导航层级数量
            if($action == 'edit') {
                $new_class=$industrysTypeModel->field('class_list')->where(array('id'=>$dataArr['parent_id']))->find()['class_list'];
                if (in_array($id,explode(',',$new_class))){
                    jscript_msg("父类别不可为当前类别的子类别",$_SERVER['HTTP_REFERER']);
                }else {
                    $status = $industrysTypeModel->where(array('id' => $id))->save($dataArr);
                    if ($status !== false) {
                        if ($dataArr['class_list'] == "") {
                            $class_list = "," . $id . ",";
                        } else {
                            $class_list = $class_list . $id . ",";
                        }
                        $industrysTypeModel->where("id = $id")->setField('class_list', $class_list);
                        add_admin_log('edit', session('admin_name') . '成功更新了ID为' . $id . '的商品类别!');
                        jscript_msg("修改成功 ", $returnUrl);
                    } else {
                        jscript_msg_tips("修改失败!");
                    }
                }
            }else{
                $industrysTypeId = $industrysTypeModel->add($dataArr);
                if ($industrysTypeId > 0 ) {
                    if($dataArr['class_list']==""){
                        $class_list = ",".$industrysTypeId.",";
                    }else{
                        $class_list = $class_list.$industrysTypeId.",";
                    }
                    $industrysTypeModel->where("id=$industrysTypeId")->setField('class_list',$class_list);
                    add_admin_log('add', session('admin_name') . '成功增加了ID为' . $industrysTypeId . '的企业类型!');
                    jscript_msg("添加成功 ", $returnUrl);
                } else {
                    jscript_msg_tips("添加失败!");
                }
            }
        }else {
            $returnUrl=$_SERVER['HTTP_REFERER'];//获取上一操作URL
            //添加操作初始化时间空间
            if ($action == 'edit') {
                $info = $industrysTypeModel->find($id);
            }else{
                $info['parent_id'] = $id;
            }
            $this->assign('info', $info);
            $industyrs_type_list = D('Admin/Goods')->getIndustrysTypeTreeListAction();
            $this->assign('industyrs_type_list',$industyrs_type_list);
            //具体操作类型
            $this->assign('action',$action);
            $this->assign('returnUrl',$returnUrl);
            $this->display();
        }
    }

    //删除类型
    public function industrysTypeDelAction(){
        $ids = I('ids');
        if(!parent::checkAdminRoleAction('industrys_type','delete')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        $industrysTypeModel = M('industrysType');
        //判断id是数组还是一个数值
        $count_record = 0;
        if(is_array($ids)){
            for($i = 0; $i < count($ids); $i++){
                $condition["class_list"] = array("like", "%,".$ids[$i].",%");
                $del_count = $industrysTypeModel->where($condition)->delete();
                if($del_count!==false){
                    $count_record += $del_count;
                }else{
                    $count_record = false;
                }
            }
        }else{
            $condition["class_list"] = array("like", "%,".$ids.",%");
            $count_record = $industrysTypeModel->where($condition)->delete();
        }
        if($count_record!==false) {
            add_admin_log('del',session('admin_name').'成功删除了'.$count_record.'条企业类型!');
            jscript_msg("删除成功 ",$returnUrl);
        }else{
            jscript_msg_tips("删除失败！");
        }
    }

}
