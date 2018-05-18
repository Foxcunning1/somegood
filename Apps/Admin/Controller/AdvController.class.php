<?php
/*后台广告位控制器
 *
 * */
namespace Admin\Controller;
use Think\Controller;
class AdvController extends BaseController {
    //广告位列表
    public function advPositionListAction(){
        //判断权限
        if(!parent::checkAdminRoleAction('adv_position_manage','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $page = I('get.p',1);
        $status = I('status', '4');
        $keywords = I('keywords');
        if($keywords) {
            $condition['title|content'] = array('like', '%' . $keywords . '%');
            $pageCondition['keywords'] = $keywords;
        }
        $pageCondition['status'] = $status;
        //广告位的时效判断
        if($status == 1){
            $condition['end_time'] = array('LT',time());
        }elseif($status == 2){
            $condition['end_time'] = array('GT',time());
            $condition['begin_time'] = array('LT',time());
        }elseif($status == 3){
            $condition['begin_time'] = array('GT',time());
        }
        $result = D('Adv')->getAdvPosPageList($page, C('COMMON_PAGE_NUM'),$condition,$pageCondition);
        $this->assign('keywords',$keywords);
        $this->assign('status',$status);
        $this->assign('list',$result['list']);
        $this->assign('page',$result['show']);
        $this->display();
    }

    //添加广告位
    public function advPositionAddAction(){
        $action = I('action','add');//操作类型
        $id = I("id", 0);//角色ID
        if(!parent::checkAdminRoleAction('adv_position_manage',$action)){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        if(IS_POST){
            $jumpUrl = I('post.returnUrl');
            $dataArr = I('data');
            $dataArr['begin_time'] = strtotime($dataArr['begin_time']);
            $dataArr['end_time'] = strtotime($dataArr['end_time']);
            if($action == 'edit') {
                $status = M('adv_position')->where(array('id' => $id))->save($dataArr);
                if ($status !== false) {
                    add_admin_log('edit', session('admin.admin_name') . '成功更新了ID为' . $id . '广告位!');
                    jscript_msg("修改成功 ", $jumpUrl);
                } else {
                    jscript_msg_tips("修改失败！");
                }
            }else{
                $advPositionId = M('adv_position')->add($dataArr);
                if ($advPositionId > 0 ) {
                    add_admin_log('add', session('admin.admin_name') . '成功增加了ID为' . $advPositionId . '广告位!');
                    jscript_msg("添加成功 ", $jumpUrl);
                } else {
                    jscript_msg_tips("添加失败！");
                }
            }
        }else {
            //添加操作初始化时间空间
            $advModel = D('Adv');
            $animateTypeList = $advModel->getAdvAnimateTypeList();
            if ($action == 'edit') {
                $info = M('adv_position')->find($id);
            }else{
                $info['begin_time'] = time();
                $info['end_time'] = strtotime('30 days');
                $info['sort_num'] = 1;
            }
            $this->assign('info', $info);
            $this->assign('animateTypeList', $animateTypeList);
            //具体操作类型
            $this->assign('action',$action);
            $this->assign('returnUrl',$returnUrl);
            $this->display();
        }
    }
    /*删除广告位*/
    public function advPositionDelAction(){
        $action = 'delete';
        //判断权限
        if(!parent::checkAdminRoleAction('adv_position_manage',$action)){
            $this->error('亲，您无权进行操作！');
        }
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        $idArr = I('ids');
        $advPositionModel  = M('adv_position');
        //判断id是数组还是一个数值
        $condition['id'] = array('in',$idArr);
        $result = $advPositionModel->where($condition)->delete();
        if($result!==false) {
            add_admin_log('del',session('admin.admin_name').'成功删除了'.$result.'个广告位!');
            jscript_msg("删除成功！",$returnUrl);
        }else{
            jscript_msg_tips("删除失败！");
        }
    }

    //广告列表
    public function listAction(){
        //获取广告位对应的列表数据
        if(!parent::checkAdminRoleAction('adv_manage','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $page = I('get.p',1);
        $posid = I('get.posid');//广告位ID
        $keywords = I('get.keywords');
        $user_id=I('user_id');//用户ID
        if($user_id){
            $advIdArray=M('SellerAdvLog')->where(array('type'=>0,'seller_id'=>$user_id))->getField('adv_id',true);
            if($advIdArray){
                $condition['a.id']=array('in',$advIdArray);
            }else{
                $condition['a.id']=0;
            }
            $pageCondition['user_id']=$user_id;
            $this->assign('user_id',$user_id);
        }
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        if($keywords) {
            $condition['a.title|a.content'] = array('like', '%' . $keywords . '%');
            $pageCondition['keywords'] = $keywords;
        }
        if($posid){
            $condition['posid'] = $posid;
            $pageCondition['posid'] = $posid;
            $this->assign('posid',$posid);
        }
        $result = D('Common/Adv')->getAdvPageList($page,$condition,$pageCondition);
        $this->assign('list',$result['list']);
        $this->assign('page',$result['show']);
        $this->display();
    }

    //添加广告
    public function addAction(){
        $action = I('action','add');//操作类型
        $posid = I('posid',0);//广告位ID
        $id = I("id", 0);//广告ID
        $user_id=I('user_id');//商家ID
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        if(!parent::checkAdminRoleAction('adv_manage',$action)){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        if(IS_POST){
            $dataArr = I("data");
            $dataArr['begin_time'] = strtotime($dataArr['begin_time']);
            $dataArr['end_time'] = strtotime($dataArr['end_time']);
            $jumpUrl = I('returnUrl');
            if($action == 'edit') {
                $status = M('adv')->where(array('id' => $id))->save($dataArr);
                if ($status !== false) {
                    add_admin_log('edit', session('admin.admin_name') . '成功更新了ID为' . $id . '广告!');
                    jscript_msg("修改成功 ", $jumpUrl);
                }else{
                    jscript_msg_tips("修改失败！");
                }
            }else{
                $advId = M('adv')->add($dataArr);
                if ($advId > 0 ) {
                    if ($user_id){
                        $data['title']=$dataArr['title'];
                        $data['img_url']=$dataArr['img_url'];
                        $data['seller_id']=$user_id;
                        $data['begin_time']=$dataArr['begin_time'];
                        $data['end_time']=$dataArr['end_time'];
                        $data['adv_id']=$advId;
                        $data['adv_position']=$dataArr['posid'];
                        $data['is_delivery']=1;
                        M('SellerAdvLog')->add($data);
                    }
                    add_admin_log('add', session('admin.admin_name') . '成功增加了ID为' . $advId . '广告!');
                    jscript_msg("添加成功 ", $jumpUrl);
                } else {
                    jscript_msg_tips("添加失败！");
                }
            }
        }else {
            if ($action == 'edit') {
                $info = M('adv')->find($id);
            }else {
                $info['begin_time'] = time();
                $info['end_time'] = strtotime('30 days');
                $info['sort_num']  = 1;
            }
            $this->assign('info', $info);
            $advPosList = D('Adv')->getTopAdvPosPageList();
            $this->assign('advPosList',$advPosList);
            $this->assign('user_id',$user_id);
            //具体操作类型
            $this->assign('action',$action);
            $this->assign('posid',$posid);
            $this->assign('returnUrl',$returnUrl);
            $this->display();
        }
    }
    /*删除广告*/
    public function delAction(){
        $action = 'delete';
        //判断权限
        if(!parent::checkAdminRoleAction('adv_manage',$action)){
            $this->error('亲，您无权进行操作！');
        }
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        $idArr = I('ids');
        $advModel  = M('adv');
        //判断id是数组还是一个数值
        $condition['id'] = array('in',$idArr);
        $result = $advModel->where($condition)->delete();
        if($result!==false) {
            add_admin_log('del',session('admin.admin_name').'成功删除了'.$result.'个广告!');
            jscript_msg("删除成功！",$returnUrl);
        }else{
            jscript_msg_tips("删除失败！");
        }
    }



/*****************************************************线下广告******************************************************************************/


    /**添加商家线下推广广告*/
    public function addSellerAdvAction(){
        if(!parent::checkAdminRoleAction('adv_manage','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $user_id=I('user_id');
        //获取店铺类型列表
        $store_type_list=M('StoreType')->select();
        //获取省级
        $regionModel = D('Common/Region');
        $regionList = $regionModel->getAreaList(0);
        //获取广告位类型
        $advPositionList=M('StoreAdvPosition')->select();
        $info['begin_time'] = time();
        $info['end_time'] = strtotime('30 days');

        $this->assign('type_list',$store_type_list);
        $this->assign('info',$info);
        $this->assign('advPositionList',$advPositionList);
        $this->assign('list',$regionList);
        $this->assign('returnUrl',$_SERVER['HTTP_REFERER']);
        $this->assign('user_id',$user_id);
        $this->display();
    }

    /**获得指定区域的信息
     */
    public function getRegionListAction(){
        $pid = I('id',0);
        $regionModel = D('Common/Region');
        $list = $regionModel->getAreaList($pid);
        $this->ajaxInfoReturn($list,"获取成功！",1);
    }

    /**AJAX获取店铺列表*/
    public function getStoreAction(){
        if (!IS_AJAX){
            $this->ajaxInfoReturn('提交方式错误','',0);
        }else{
            $storeType=I('storeType');
            $region=I('region');
            $posId=I('posId');
            if ($storeType>0){
                $condition['store_type']=$storeType;
            }
            if ($region>0){
                $condition['area_list']=array('like','%,'.$region.',%');
            }
            $store_list = M('store')->alias('s')->field('sid,store_name,code,area_id')->join("LEFT JOIN __REGION__ AS r ON s.area_id = r.id")->where($condition)->select();
            //筛选店铺
            if($store_list!==false){
                //筛选有符合广告位的店铺
                foreach ($store_list as $k=>$v){
                    $advList=M('StoreAdv')->field('id')->where(array('pos_id'=>$posId,'store_id'=>$v['sid'],'is_lock'=>0))->order('store_id asc')->select();
                    foreach ($advList as $sk=>$sv){
                        $store_list[$k]['adv'][$sk]['id']=$sv['id'];
                    }
                    if (!$store_list[$k]['adv'])unset($store_list[$k]);
                }
                $this->ajaxInfoReturn($store_list,'',1);
            }else{
                $this->ajaxInfoReturn('获取失败','',0);
            }
        }
    }

    /**投放处理*/
    public function addDeliveryAction(){
        if(!parent::checkAdminRoleAction('adv_manage','edit')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $returnUrl=I('returnUrl');
        $data=I('data');
        //新增投放店铺
        $storeStr=I('store_list');
        if ($storeStr==null){
            jscript_msg_tips("请选择店铺!");
        }else{
            $data['type']=1;
            $data['begin_time'] = strtotime($data['begin_time']);
            $data['end_time'] = strtotime($data['end_time']);
            $storeArray=explode(',',$storeStr);
            $num='';
            foreach ($storeArray as $v){
                $deliveryInfo=explode('|',$v);
                $data['store_id']=$deliveryInfo[0];
                $data['adv_id']=$deliveryInfo[1];
                $data['adv_position']=$deliveryInfo[2];
                M()->startTrans();
                //投放店铺表增加记录
                $re1=M('SellerAdvLog')->add($data);
                //锁定广告位
                $re2=M('StoreAdv')->where(array('id'=>$data['adv_id']))->setField('is_lock',1);
                if($re1&&$re2){
                    M()->commit();
                    $num++;
                }else{
                    M()->rollback();
                }
            }
            if($num>0){
                add_admin_log('add',$_SESSION['admin']['admin_name'].'成功新增了'.$num.'条广告投放计划!');
                jscript_msg("成功生成".$num."条广告投放计划", $returnUrl);
            }else{
                jscript_msg_tips("失败!");
            }
        }
    }

    //广告列表
    public function advListAction(){
        //获取广告位对应的列表数据
        if(!parent::checkAdminRoleAction('adv_manage','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $property = I('property','all','strip_tags');
        $keywords = I('keywords');
        $pageNum = I('p',1,'strip_tags');
        $user_id=I('user_id');
        $condition['seller_id']=$user_id;
        if($keywords) {
            $condition['sal.title'] = array('like', '%' . $keywords . '%');
            $pageCondition['keywords'] = $keywords;
        }
        //属性列表
        $property_list = array(
            'all' => '所有属性',
            'wait' => '待投放',
            'success' => '投放完成'
        );
        if($property!="all"){
            $property1 = 0;
            switch($property){
                case 'wait':
                    $property1 = 0;
                    break;
                case 'success':
                    $property1 = 1;
                    break;
            }
            $condition['is_delivery'] = $property1;
            $pageCondition['property'] = $property;
        }
        $advModel = D('Common/Adv');
        $result=$advModel->getStoreAdvPageList($pageNum,$condition,$pageCondition,'store');
        $this->assign('list',$result['list']);
        $this->assign('page',$result['show']);
        $this->assign('property',$property);
        $this->assign('property_list',$property_list);
        $this->display();
    }

    /**完成投放*/
    public function doDeliveryAction(){
        if(!parent::checkAdminRoleAction('adv_manage','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $id=I('id');
        $deliveryModel=M('SellerAdvLog');
        $info=$deliveryModel->field('begin_time,end_time')->where(array('id'=>$id))->find();
        $time=time()-$info['begin_time'];//时间差
        $data['begin_time']=time();
        $data['end_time']=$info['end_time']+$time;
        $data['is_delivery']=1;
        $re=$deliveryModel->where(array('id'=>$id))->save($data);
        if ($re!==false){
            jscript_msg("投放成功",$_SERVER['HTTP_REFERER']);
        }else{
            jscript_msg_tips("投放失败!");
        }
    }
}