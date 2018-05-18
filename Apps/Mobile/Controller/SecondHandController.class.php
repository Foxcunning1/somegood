<?php

namespace Mobile\Controller;
use Think\Controller;

/*二手商品管理类
* date 2017-10-25
*/
class SecondHandController extends MemberController{

    //卖家管理中心
    public function indexAction(){
        //获得卖家部分信息
        $sellerModel=D('Common/Users');
        $SellerDetail=$sellerModel->getUsersInfo();
        //统计待发货数量
        $orderModel = M('Order');
        $userId = session('mobile.id');
        $map['is_used'] = 1;//二手商品
        $map['status'] = 1;//待发货
        $map['seller_id'] = $userId;//当前卖家的ID
        $waitSendGoodsNum = $orderModel->where($map)->count();
        //在售
        $goodsModel = M('ShGoods');
        $on_sale_num = $goodsModel->where(array('user_id'=>$userId,'status'=>1))->count();
        $this->assign('on_sale_num',$on_sale_num);
        $this->assign('wait_send_num',$waitSendGoodsNum);
        $this->assign('info',$SellerDetail);
        $this->display();
    }

    //卖家订单列表
    public function orderAction(){
        C('TOKEN_ON',false);//屏蔽表单验证
        $orderModel = D('Common/Order');
        $pageNum = I('p',1);
        $keywords = I('keywords','');
        $pageType=I('pageType',1);//列表类型,1待发货,2待完成,3已完成,0代付款
        $userId = session('mobile.id');
        //查询条件
        $condition['o.seller_id'] = $userId;
        if ($pageType == 3) {
            $condition['_string'] = "o.status=3 OR o.status=6" ;
        }else {
            $condition['o.status'] = $pageType;
        }
        //查询出卖家对应状态的订单
        $result=$orderModel->getSellerOrderPageList($pageNum,'id DESC',$pageType,$condition);
        //var_dump($result);
        if(IS_AJAX){
            foreach ($result['list'] as &$v){
                $v['goods_thumb'] = explode(',',$v['goods_thumb']);
                $v['goods_title'] = explode(',',$v['goods_title']);
            }
            $this->assign("isAjax",1);
            $this->assign('pageType',$pageType);
            $this->assign('list',$result['list']);
            $this->assign('pageCount',$result['pageCount']);
            $this->display();
        }else{
            $this->assign("isAjax",0);
            $this->assign('pageType',$pageType);
            $this->assign('pageCount',$result['pageCount']);
            $this->display();
        }
    }

    //改价
    public function editPriceAction(){
        if (!IS_AJAX){
            $this->tips('提交方式错误',1,500,$_SERVER['HTTP_REFERER']);
        }else {
            $order_sn = I('order_sn');
            $edit_price = (float)I('edit_price');
            $orderModel = D('Common/Order');
            $info = $orderModel->selectOrderDetail($order_sn);

            $goods_info = $info['list1'][0];
            $order_info = $info['list2'];
            if ($order_info['status']!=0 || $order_info['delivery_way'] !=0 ||$order_info['is_son'] !=2||$goods_info['store_id']!=session('mobile.store_id')){
                $this->ajaxInfoReturn('','订单状态不正确,请重试',0);
            }elseif ($edit_price<$goods_info['goods_price']){
                $this->ajaxInfoReturn('','不得低于商品底价',0);
            }else{
                //修改价格
                $data['money']=$edit_price;
                $re=M('market_order')->where(array('order_sn'=>$order_sn))->save($data);
                if ($re!==false){
                    $this->ajaxInfoReturn('','改价成功',1);
                }else{
                    $this->ajaxInfoReturn('','改价失败',0);
                }
            }
        }
    }

    //发货
    public function deliverGoodsAction(){
        if (IS_AJAX){
            $data=I('data');
            //验证订单当前状态
            $orderModel=D('Common/Order');
            $re=$orderModel->deliverGoods($data);
            if ($re){
                //获取收货人信息
                $condition['o.order_sn'] = $data['order_sn'];
                $result=$orderModel->selectOrderInfoById($condition);
                $info=$result['0'];
                send_sms_message('shipping',$info['mobile'],'',array($info['order_sn'],$info['name'],$info['logistics_sn']));
                $this->ajaxInfoReturn('','发货成功',1);
            }else{
                $this->ajaxInfoReturn('','信息有误或订单状态错误,请重试',0);
            }
        }else{
            //获取物流/快递列表
            $logistics_list=M('logistics')->order('sort_num ASC')->select();
            $this->assign('logistics_list',$logistics_list);
            $this->assign('returnUrl',$_SERVER['HTTP_REFERER']);
            $this->display();
        }
    }
    /*发布和修改商品*/
    public function publishAction(){
        $action = I('action','add', 'strip_tags');//操作类型
        $uid=session('mobile.id');
        $id=I('id');
        $goodsModel=D('Common/ShGoods');
        if (IS_POST){
            $dataArr = I('data');
            $photoArr = I('photo');
            if(count($photoArr)<3){
                $this->ajaxInfoReturn('','最少上传三张宝贝图片更容易成交哦',0);
            }elseif(!$dataArr['goods_title']){
                $this->ajaxInfoReturn('','请输入标题',0);
            }elseif(!$dataArr['goods_price']){
                $this->ajaxInfoReturn('','请输入商品价格！',0);
            }elseif(!$dataArr['category_id']){
                $this->ajaxInfoReturn('','请选择宝贝分类',0);
            }elseif(!$dataArr['goods_condition']){
                $this->ajaxInfoReturn('','请选择宝贝新旧程度',0);
            }elseif (explode(',',$dataArr['area_list'])[2]==0){
                $this->ajaxInfoReturn('','请选择区域',0);
            }else{
                if($action == 'edit') {
                    $dataArr['id']=$id;
                    $dataArr['update_time']=time();
                    $result = $goodsModel->where(array('id' => $id))->save($dataArr);
                    if($result !== false) {
                        $this->ajaxInfoReturn('','修改成功',1);
                    }
                    else $this->ajaxInfoReturn('','修改失败',0);
                }else{
                    $dataArr['status'] = 1;
                    $dataArr['user_id']=$uid;
                    $dataArr['add_time']=$dataArr['update_time']=time();//发布时间/最后更新时间
                    $dataArr['area_id']=explode(',',$dataArr['area_list'])[2];//区域ID
                    $dataArr['goods_sn']=$id.get_goods_sn().time();
                    $dataArr['goods_thumb']=I('hidFocusPhoto');//商品封面缩略图
                    $dataArr['original_img']=serialize(I('photo'))."||".serialize(I('rphoto'));//商品图组||备注组
                    $goodsId = $goodsModel->add($dataArr);
                    if($goodsId > 0) {
                        //增加通知
                        insert_notice('admin','waitDelivery','新增推广商品ID:'.$goodsId.'待投放',0);
                        $this->ajaxInfoReturn('','发布成功',1);
                    }
                    else $this->ajaxInfoReturn('','发布失败',0);
                }
            }
        }else {
            $returnUrl=$_SERVER['HTTP_REFERER'];//获取上一操作URL
            if($action == 'edit'){
                $info = $goodsModel->getGoodsInfo($id);
                $info['region']=str_replace(',',' ',mb_substr($info['region'],stripos($info['region'],',')+1));
                $photoArr=explode("||",$info['original_img']);
                $this->assign('plist',unserialize($photoArr[0]));
                $this->assign('rlist',unserialize($photoArr[1]));
                $this->assign('id',$id);
            }else{
                //初始化区域
                $info['goods_num'] = 1;
                $city_id=cookie('city_id');
                $province_id=M('region')->where(array('id'=>$city_id))->find()['pid'];
                $info['area_list']="$province_id,$city_id";
            }
            //商品分类
            $goodsCategoryList = F('Mobile/Category/category_list_0');
            if (!$goodsCategoryList) {
                $cacheCategoryList = require_once ("./Cache/category/category.cache.php");
                $categoryArr = array();
                foreach($cacheCategoryList as $key=>$val){
                    $tempCategoryList['id'] = $val['id'];
                    $tempCategoryList['title'] = $val['title'];
                    $tempCategoryList['img_url'] = $val['img_url'];
                    $tempCategoryList['parent_id'] = $val['parent_id'];
                    $categoryArr[] = $tempCategoryList;
                }
                $goodsCategoryList = $categoryArr;
                F('Mobile/Category/category_list_0',$goodsCategoryList);
            }
            //获取当前类别的信息
            $bigCatId = 0;
            if($info['category_id']>0){
                $catInfo = D("Common/GoodsCategory")->getCategoryInfo($info['category_id']);
                $tempArr = explode(",",$catInfo['class_list']);
                if(count($tempArr)>1){
                    $bigCatId = $tempArr[1];
                }
            }
            $this->assign('bigCatId', $bigCatId);
            $this->assign('info', $info);
            $this->assign('categoryList',$goodsCategoryList);
            $this->assign('returnUrl',$returnUrl);
            $this->assign('action',$action);
            $this->assign('goods_condition',C('GOODS_CONDITION'));
            $this->display();
        }
    }
    //我的发布
    public function goodsManageAction(){
        $user_id=session('mobile.id');
        $pageNum=I('p',1);
        $pageType=I('pageType');//页面状态sale上架,success卖出
        switch ($pageType){
            case "sale":
                break;
            case "success":
                $successListCount = M('order_goods')->alias('og')->field('g.*')
                    ->join("LEFT JOIN __ORDER__ AS o ON og.order_sn = o.order_sn")
                    ->join('LEFT JOIN __SH_GOODS__ AS g ON og.goods_id = g.id')
                    ->where(array('user_id'=>session('mobile.id'),'o.status'=>3,'is_used_goods'=>1))->count();
                $successList = M('order_goods')->alias('og')->field('g.*')
                    ->join("LEFT JOIN __ORDER__ AS o ON og.order_sn = o.order_sn")
                    ->join('LEFT JOIN __SH_GOODS__ AS g ON og.goods_id = g.id')
                    ->where(array('user_id'=>session('mobile.id'),'o.status'=>3,'is_used_goods'=>1))
                    ->limit(($pageNum-1)*C('GOODS_PAGE_SIZE'),C('GOODS_PAGE_SIZE'))->select();
                $this->assign('time',time());
                break;
        }
        $condition['g.user_id']=$user_id;
        $goodsModel = D('Common/ShGoods');
        $result=$goodsModel->getGoodsPageList($pageNum,C('GOODS_PAGE_SIZE'),'',$condition,$condition);
        if (IS_AJAX){
            if ($pageType=="success"){
                $result['list']=$successList;
                $result['pageCount']=ceil($successListCount/C('GOODS_PAGE_SIZE'));
            }
            $this->ajaxInfoReturn($result,'',1);
        }else{
            $this->assign('list',$result['list']);
            $this->assign('pageCount',$result['pageCount']);
            $this->assign('pageType',$pageType);
            $this->display();
        }
    }
    //删除发布的商品
    public function delGoodsAction(){
        if (!IS_AJAX){
            $this->ajaxInfoReturn('提交方式错误','',0);
        }else{
            $user_id = session('mobile.id');
            $goods_id = I('id');
            $goodsModel = M('sh_goods');
            $re = $goodsModel->where(array('id'=>$goods_id,'user_id'=>$user_id))->delete();
            if ($re){
                $this->ajaxInfoReturn('删除成功','',1);
            }else{
                $this->ajaxInfoReturn('删除失败','',0);
            }
        }
    }

    //卖家结算界面
    public function settlementAction(){
        $sellerModel=D('Common/UsersSeller');
        $userId=session('mobile.id');   //卖家用户id
        $pageNum=I('p','1');  //当前页数
        $action=I('action')?I('action'):'ing';  //正在结算的条件
        $map['seller_id']=$userId;
        $map['is_used'] = 1;   //二手商品
        if ($action == 'ing') {
            //正在结算
            $map['_string'] = 'o.status=3 and (o.is_son != 0)';
        }else {
            //已结算
            $map['_string'] = 'o.status=6 and (o.is_son != 0)';
        }
        $list=$sellerModel->sellerSettlement($pageNum,$map);
        $pageCount=$list['pageCount'];
        //var_dump($pageCount);exit;
        if (IS_AJAX) {
            $this->assign("is_ajax",1);
            $this->assign('list',$list['list']);
        }else{
            $this->assign('pageCount',$pageCount);
            $this->assign('action',$action);
            $this->assign('list',$list);
        }
        $this->display();
    }
}
?>
