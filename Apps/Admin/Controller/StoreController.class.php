<?php
/**
 * 后台店铺信息管理类
 * Created by PhpStorm.
 * User: ZHY
 * Date: 2017/8/9
 * Time: 9:41
 */
namespace Admin\Controller;
use Think\Controller;
class StoreController extends BaseController {
    /**
     * 店铺信息列表
     * TODO:地区列表筛选目前仅深圳
     **/
    public function indexAction(){
        if(!parent::checkAdminRoleAction('store_list_manage','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        //通知设为已读
        if (I('notice')==1){
            set_readed('admin','auditStore');
        }
        $areaId = I('area_id');
        $property = I('property','all','strip_tags');
        $keywords = I('keywords');
        $pageNum = I('p',1,'strip_tags');
        if($keywords) {
            $condition['store_name|alias_name'] = array('like', '%' . $keywords . '%');
            $pageCondition['keywords'] = $keywords;
        }
        //属性列表
        $property_list = array(
            'all' => '所有属性',
            'unlock' => '正常',
            'lock' => '待审核',
            'error'=>'审核失败',
            'disable'=>'禁用'
        );
        if($areaId) {
            $condition['area_id'] = $areaId;
            $pageCondition['area_id'] = $areaId;
            $this->assign('area_id',$areaId);
        }
        if($property!="all"){
            $property1 = 0;
            switch($property){
                case 'unlock':
                    $property1 = 1;
                    break;
                case 'lock':
                    $property1 = 2;
                    break;
                case 'error':
                    $property1 = 3;
                    break;
                case 'disable':
                    $property1 = 4;
                    break;

            }
            $condition['status'] = $property1;
            $pageCondition['property'] = $property;
        }
        //     地区列表
        $area_list=M('region')->where(array('pid'=>1988))->order('id DESC')->select();
        //获取店铺列表
        $storeModel=D('Common/store');
        $order = 'sid DESC';
        $result = $storeModel->getStorePageListAction($pageNum,$order,$condition,$pageCondition);
        $this->assign('property', $property);
        $this->assign('area_list',$area_list);
        $this->assign('property_list',$property_list);
        $this->assign('list',$result['list']);
        $this->assign('page',$result['show']);
        $this->display("list");
    }
    /**
     * 店铺信息审核/新增
     */
    public function addAction(){
        $storeModule = D('Common/Store');
        $id = I('id', 0, 'strip_tags');//属性ID
        $action=I('action');
        //判断权限
        if(!parent::checkAdminRoleAction('store_manage',$action)){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        if(IS_POST){
            $returnUrl=I('returnUrl');
            $dataArr = I('data');
            $delIds=I('delIds');
            $add_box=I('box_size_list');
            $delPosIds=I('delPosIds');
            $adv_pos=I('adv_pos_list');
           if($action=='edit'){

               //获取当前申请店铺的会员信息
               $uid = I("user_id");
               $userInfo = D('Common/Users')->getUsersInfo($uid);
               //审核失败记录
               if($dataArr['status']==3){
                   if(!I('error_reason')){
                       $this->error("请填写审核失败原因");
                   }
                   $errorArr['sid']=$id;//店铺ID
                   $errorArr['user_id'] = $uid;//用户ID
                   $errorArr['error_reason']=I('error_reason');//失败原因
                   $errorArr['audit_time']=time();//审核时间
                   M('storeErrorReason')->add($errorArr);//写入记录表
                   if($userInfo){//用户信息存在则把审核失败消息以短信形式发送给用户
                       send_sms_message('failed',$userInfo['mobile'],$userInfo['mobile']);
                   }
               }elseif($dataArr['status']==1){
                   //审核成功则必须拾取坐标
                   if(!I('coordinate')){
                       $this->error("请选择店铺坐标");
                   }else{
                       $coordinate=explode(',',I('coordinate'));//拾取的坐标
                       $dataArr['lng']=$coordinate[0];
                       $dataArr['lat']=$coordinate[1];
                   }
               }
               $dataArr['qq'] = ($dataArr['qq']!=""||$dataArr['qq']!=0)?$dataArr['qq']:NULL;
               if (I('old_status')==1&&$dataArr['status']!=4){
                   //正常状态的店铺只能禁用
                   unset($dataArr['status']);
               }
               $status = $storeModule->where(array('sid' => $id))->data($dataArr)->save();
               //货柜格子处理
               if ($delIds || $add_box){
                   $success_num=$storeModule->updataStoreBox($id,$delIds,$add_box);
                   if ($success_num==0){
                       jscript_msg_tips("修改失败!");
                   }
               }
               //店铺广告位处理
               if ($delPosIds || $adv_pos){
                   $success_num=$storeModule->updataStoreAdvPos($id,$delPosIds,$adv_pos);
                   if ($success_num==0){
                       jscript_msg_tips("修改失败!");
                   }
               }
               if ($status!== false) {
                   if($dataArr['status']==1){
                       M('Users')->where(array('uid'=>I('user_id')))->setField('is_store',1);//更新用户表
                       send_sms_message('application',I('mobile'),I('owner'));//店铺审核通过，
                   }
                   add_admin_log('edit', session('admin_name') . '成功更新了ID为' . $id . '的店铺!');
                   jscript_msg("修改成功 ", $returnUrl);
               } else {
                   jscript_msg_tips("修改失败!");
               }
           }else{
               //坐标拾取
               if(!I('coordinate')){
                   $this->error("请选择店铺坐标");
               }else{
                   $coordinate=explode(',',I('coordinate'));//拾取的坐标
                   $dataArr['lng']=$coordinate[0];
                   $dataArr['lat']=$coordinate[1];
               }
               $dataArr['user_id']=I('user_id');
               $dataArr['reg_time']=time();
               //地区
               $dataArr['area_list']=I('province_id').','.I('city').','.$dataArr['area_id'];
               $dataArr['vacancy']=count(I('box_size_list'));
               $id=$storeModule->add($dataArr);
               //货柜格子处理
               if ($delIds || $add_box){
                   $storeModule->updataStoreBox($id,$delIds,$add_box);
               }
               //店铺广告位处理
               if ($delPosIds || $adv_pos){
                   $storeModule->updataStoreAdvPos($id,$delPosIds,$adv_pos);
               }
               if($id){
                   M('Users')->where(array('uid'=>I('user_id')))->setField('is_store',1);//更新用户表
                   jscript_msg("添加成功 ", $returnUrl);
               }
           }


        }else {
            $returnUrl=$_SERVER['HTTP_REFERER'];//获取上一操作URL
            //地区列表
            $regionModel = D('Common/Region');
            if(F('Admin/City/province_list')){
                $provinceList = F('Admin/City/province_list');
            }else{
                $provinceList = $regionModel->getAreaList();
            }
            if ($action=='edit'){
                $info = $storeModule->find($id);
                $info['user_mobile']=$storeModule->relationGet('users')['mobile'];
                //获取店铺格子信息
                $box['list']=$storeModule->getStoreBox($id);
                $box['count']=count($box['list']);
                $this->assign('box',$box);
                //获取店铺广告位信息
                $pos['list']=$storeModule->getStorePos($id);
                $pos['count']=count($pos['list']);
                $this->assign('pos',$pos);
            }else{
                //获取店主信息
                $info=M('Users')->where(array('uid'=>I('uid')))->find();
                $info['user_id']=$info['uid'];
                $info['owner']=$info['real_name'];
            }
            //获取地区信息
            if($info['area_id']){
                $areaInfo = $regionModel->getRegionInfo($info['area_id']);
                if($areaInfo['pid']>0){
                    $cityInfo = $regionModel->getRegionInfo($areaInfo['pid']);
                    $townList = $regionModel->getAreaList($areaInfo['pid']);
                    $cityList = $regionModel->getAreaList($cityInfo['pid']);
                }
            }
            $this->assign('provinceId',$cityInfo['pid']);
            $this->assign('cityId',$areaInfo['pid']);
            $this->assign('cityList',$cityList);
            $this->assign('townList',$townList);
            //店铺类型
            if(!F('storeTypeList')){
                $store_type_list=M('StoreType')->select();
                F('storeTypeList',$store_type_list);
            }
            //格子规格
            if(!F('boxSize')){
                $boxSize=M('StoreBoxSize')->select();
                F('boxSize',$boxSize);
            }
            //广告位规格
            if(!F('StorePos')){
                $storePos=M('StoreAdvPosition')->select();
                F('StorePos',$storePos);
            }
            $this->assign('info', $info);
            $this->assign('action', $action);
            $this->assign('provinceList',$provinceList);
            $this->assign('boxSize',F('boxSize'));
            $this->assign('storePos',F('StorePos'));
            $this->assign('storeTypeList',F('storeTypeList'));
            $this->assign('returnUrl',$returnUrl);
            $this->assign('action',$action);
            $this->assign('status_list',array(1=>"正常",2=>"待审核",3=>"审核失败",4=>"禁用"));
            $this->display();
        }
    }

    /*************************************************格子规格****************************************************************/
    /**店铺格子规格*/
    public function sizeAction(){
        //判断权限
        if(!parent::checkAdminRoleAction('store_box_size','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $storeSizeModel=D('Common/Store');
        $pageNum=I('p',1);
        $result=$storeSizeModel->getBoxSizeList($pageNum);
        $this->assign('list',$result['list']);
        $this->assign('page',$result['show']);
        $this->display();
    }
    /**添加格子规格*/
    public function addSizeAction(){
        //判断权限
        if(!parent::checkAdminRoleAction('store_box_size','add')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $data=I('data');
        $condition['l']=$data['l'];
        $condition['w']=$data['w'];
        $condition['h']=$data['h'];
        //查看是否已有相同规格
        $storeBoxSizeModel=M('StoreBoxSize');
        $count=$storeBoxSizeModel->where($condition)->count();
        if ($count==0){
            $re=$storeBoxSizeModel->add($data);
            if ($re){
                $boxSize=$storeBoxSizeModel->select();
                F('BoxSize',$boxSize);
                jscript_msg("添加成功 ",$_SERVER['HTTP_REFERER']);
            }else{
                jscript_msg_tips("添加失败!");
            }
        }else{
            jscript_msg_tips("已有相同规格!");
        }
    }
    /**删除未放置过的格子规格*/
    public function delBoxSizeAction(){
        //判断权限
        if(!parent::checkAdminRoleAction('store_box_size','delete')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $id=I('id');
        //判断是该规格的格子否有被放置
        $count=M('StoreBox')->where(array('size_id'=>$id))->count();
        if ($count==0){
            $re=M('StoreBoxSize')->where(array('id'=>$id))->delete();
            if ($re!==false){
                jscript_msg("删除成功 ",$_SERVER['HTTP_REFERER']);
            }else{
                jscript_msg_tips("删除失败!");
            }
        }else{
            jscript_msg_tips("已有该规格的格子，不可删除!");
        }
    }


    /*************************************************店铺广告位规格******************************************************************/

    /**店铺广告位规格*/
    public function positionAction(){
        //判断权限
        if(!parent::checkAdminRoleAction('store_adv','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $storePosModel=D('Common/Adv');
        $pageNum=I('p',1);
        $result=$storePosModel->getStorePosPageList($pageNum);
        $this->assign('list',$result['list']);
        $this->assign('page',$result['show']);
        $this->display();
    }
    /**添加广告位规格*/
    public function addPosAction(){
        //判断权限
        if(!parent::checkAdminRoleAction('store_adv','add')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $data=I('data');
        $condition['title']=$data['title'];
        $condition['width']=$data['width'];
        $condition['height']=$data['height'];
        //查看是否已有相同规格
        $storeAdvPositionModel=M('StoreAdvPosition');
        $count=$storeAdvPositionModel->where($condition)->count();
        if ($count==0){
            $re=$storeAdvPositionModel->add($data);
            if ($re){
                $storePos=$storeAdvPositionModel->select();
                F('StorePos',$storePos);
                jscript_msg("添加成功 ",$_SERVER['HTTP_REFERER']);
            }else{
                jscript_msg_tips("添加失败!");
            }
        }else{
            jscript_msg_tips("已有相同规格!");
        }
    }
    /**删除未使用过的广告位规格*/
    public function delAdvPosAction(){
        //判断权限
        if(!parent::checkAdminRoleAction('store_adv','delete')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $id=I('id');
        //判断是该规格的格子否有被放置
        $count=M('StoreAdv')->where(array('pos_id'=>$id))->count();
        if ($count==0){
            $re=M('StoreAdvPosition')->where(array('id'=>$id))->delete();
            if ($re!==false){
                jscript_msg("删除成功 ",$_SERVER['HTTP_REFERER']);
            }else{
                jscript_msg_tips("删除失败!");
            }
        }else{
            jscript_msg_tips("已有该规格的格子，不可删除!");
        }
    }
}
