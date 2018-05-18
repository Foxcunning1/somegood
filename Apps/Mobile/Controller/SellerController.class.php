<?php

namespace Mobile\Controller;
use Think\Controller;

/*移动端卖家管理类
* date 2017-10-10
*/
class SellerController extends Controller{
    //验证是否是卖家
    public function __construct()
    {
        parent::__construct();
        $is_seller=M('users')->where(array('uid'=>session('mobile.id')))->getField('is_seller');
        if( $is_seller == 0 ){
                $this->redirect("Mobile/Users/apply");
        }
    }


    //卖家管理中心
    public function indexAction(){

        //获得卖家部分信息
        $sellerModel=D('Common/Users');
        $SellerDetail=$sellerModel->getSellerInfo();
        $goodsModel=M('Goods');
        $id=session('mobile.id');  //用户id

        $info=M('users')->field("avatar,user_name")->where("uid=$id")->find(); //获得用户头像和用户名信息

        //待发货数量
        $wait_send_num=M('order')->where(array('seller_id'=>$id,'status'=>1,'is_son'=>array('neq',0),'is_used'=>0))->count();
        //在售
        $on_sale_num=$goodsModel->where(array('user_id'=>$id,'status'=>array('gt',0)))->count();
        //待投放
        $wait_delivery_num=$goodsModel->where(array('user_id'=>$id,'status'=>0))->count();
        $this->assign('info',$info);
        $this->assign('wait_send_num',$wait_send_num);
        $this->assign('on_sale_num',$on_sale_num);
        $this->assign('wait_delivery_num',$wait_delivery_num);
        $this->display();
    }



    /**
     * 发布商品
     * */

    public function publishAction(){
        $uid=session('mobile.id');
        $id=I('id');
        $goodsModel=D('Common/Goods');
        if (IS_POST){
            $dataArr = I('data');
            $photoArr = I('photo');
            if(count($photoArr)<3){
                $this->ajaxInfoReturn('','最少上传三张宝贝图片更容易成交哦',0);
            }elseif(!$dataArr['goods_title']){
                $this->ajaxInfoReturn('','请输入标题',0);
            }elseif(!$dataArr['price']){
                $this->ajaxInfoReturn('','请输入商品价格！',0);
            }elseif(!$dataArr['category_id']){
                $this->ajaxInfoReturn('','请选择宝贝分类',0);
            }else{
                $dataArr['user_id']=$uid;
                $dataArr['add_time']=$dataArr['update_time']=time();//发布时间/最后更新时间
                $dataArr['goods_sn']=$id.get_goods_sn().time();
                $dataArr['goods_thumb']=I('hidFocusPhoto');//商品封面缩略图
                $dataArr['original_img']=serialize(I('photo'))."||".serialize(I('rphoto'));//商品图组||备注组
                $goodsId = $goodsModel->add($dataArr);
                if($goodsId > 0) {
                    insert_notice('admin','waitDelivery');
                    $this->ajaxInfoReturn('','发布成功',1);
                }
                else $this->ajaxInfoReturn('','发布失败',0);
            }
        }else {
            $returnUrl=$_SERVER['HTTP_REFERER'];//获取上一操作URL
            //初始化区域
            $info['goods_num'] = 1;
            $city_id=cookie('city_id');
            $province_id=M('region')->where(array('id'=>$city_id))->find()['pid'];
            $info['area_list']="$province_id,$city_id";
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
            $order_info = $orderModel->where("order_sn=$order_sn")->find();
            if ($order_info['status']!=0 || $order_info['delivery_way'] !=1 ||$order_info['is_son'] !=2||$order_info['seller_id']!=session('mobile.id')){
                $this->ajaxInfoReturn('','订单状态不正确,请重试',0);
            }else{
                //修改价格
                $data['original_price']=$edit_price;
                $data['money']=$edit_price;
                $re=M('order')->where(array('order_sn'=>$order_sn))->save($data);
                if ($re!==false){
                    $this->ajaxInfoReturn('','改价成功',1);
                }else{
                    $this->ajaxInfoReturn('','改价失败',0);
                }
            }
        }
    }

    //卖家订单列表
    public function sellerOrderAction(){
        $orderModel=D('Common/Order');
        $pageNum=I('p',1);
        $keywords = I('keywords','');
        $pageType=I('pageType',1);//列表类型,1待发货,2待完成,3已完成,0代付款
        $userId= session('mobile.id');
        C('TOKEN_ON',false);  //避免加载__HASH__隐藏input
        //查询条件
        $condition['o.seller_id'] = $userId;
        if ($pageType == 3) {
            $condition['_string'] = "o.status=3 OR o.status=6" ;
        }else {
            $condition['o.status'] = $pageType;
        }
        //查询出卖家对应状态的订单
        $result=$orderModel->getSellerOrderPageList($pageNum,'id DESC',$pageType,$condition);
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
            $order_sn=I('order_sn');
            $lname=M('order_logistics')->alias('ol')->join('__LOGISTICS__ l ON l.id= ol.type')->where("order_sn=$order_sn")->getField('l.name');  //用户选择的快递
            $this->assign('lname',$lname);
            $this->assign('returnUrl',$_SERVER['HTTP_REFERER']);
            $this->display();
        }
    }

    /**
     * 卖家商品列表
     */
    public function sellerGoodsListAction(){
        $goods_model=D('Common/Goods');
        $pageNum=I('p',1);
        $condition['user_id']=session('mobile.id');
        $type=I('pageType','all');
        switch ($type){
            case 'wait':
                $condition['status']=0;
                $pageType='wait';
                $pageTitle="待投放";
                break;
            case 'ing':
                $condition['status']=1;
                $pageType='ing';
                $pageTitle="投放中";
                break;
            case 'sale':
                $condition['status']=array('gt',0);
                $pageType='sale';
                $pageTitle="在售";
                break;
            default:
                break;
        }
        $result=$goods_model->getSellerGoodsPageList($pageNum,C('GOODS_PAGE_SIZE'),'sales_volume DESC',$condition);
        foreach ($result['list'] as &$v){
            $photo_alt_array=explode('||',$v['original_img']);
            $v['plist']=unserialize($photo_alt_array[0]);
            $v['rlist']=unserialize($photo_alt_array[1]);
            unset($v['original_img']);
        }
        if(IS_AJAX){
            $this->assign("isAjax",1);
            $this->assign('pageType',$pageType);
            $this->assign('list',$result['list']);
            $this->assign('pageCount',$result['pageCount']);
            $this->assign('time',time());
            $this->display();
        }else{
            $this->assign("isAjax",0);
            $this->assign('pageType',$pageType);
            $this->assign('pageCount',$result['pageCount']);
            $this->assign('pageTitle',$pageTitle);
            $this->display();
        }

    }


    /**
     * 商品投放店铺列表
     * */
    public function deliveryStoreListAction(){
        $id=I('id');
        $pageNum=I('p',1);
        $pageCondition['id']=$id;
        //获取该商品投放店铺列表及店铺库存等信息
        $sellerDeliveryModel=D('Common/SellerDelivery');
        $condition['sg_id']=$id;
        $result=$sellerDeliveryModel->getGoodsPageList($pageNum,C('SELLER_DELIVERY_PAGE_SIZE'),'sales_volume DESC',$condition,$pageCondition);
        if (IS_AJAX){
            $this->assign('isAjax',1);
            $this->assign('pageCount',$result['pageCount']);
            $this->assign('list',$result['list']);
            $this->assign('page',$result['show']);
            $this->display();
        }else{
            $goods_model=D('Common/Goods');
            //获取商品信息
            $info=$goods_model->getGoodsInfo($id);
            $this->assign('isAjax',0);
            $this->assign('info',$info);
            $this->assign('pageCount',$result['pageCount']);
            $this->assign('list',$result['list']);
            $this->assign('page',$result['show']);
            $this->display();
        }
    }

    //卖家结算界面
    public function settlementAction(){
        $sellerModel=D('Common/UsersSeller');
        $userId=session('mobile.id');   //卖家用户id
        $pageNum=I('p','1');  //当前页数
        $action=I('action')?I('action'):'ing';  //正在结算的条件
        $map['seller_id']=$userId;
        $map['is_used'] = '0';   //一手商品
        if ($action == 'ing') {
                //正在结算
                $map['_string'] = 'o.status=3 and (o.is_son != 0)';
        }else {
                //已结算
                $map['_string'] = 'o.status=6 and (o.is_son != 0)';
        }
        $list=$sellerModel->sellerSettlement($pageNum,$map);
        $pageCount=$list['pageCount'];
        //用户头像
        $avatar=M('users')->where("uid=$userId")->getField('avatar');
        $this->assign('avatar',$avatar);
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

    //我卖出的
    public function mySoldAction(){
        $user_id=session('mobile.id');  //卖家id
        $pageNum=I('p',1);
        $pageType=I('pageType','success');//页面状态,audit审核,sale上架,success卖出,over过期
        switch ($pageType){
            case "audit":
                $condition['g.status']=array('in',array('0','2'));
                break;
            case "wait":
                $condition['g.status']=1;
                $condition['over_time']=-1;
                $condition['goods_num']=1;
                break;
            case "sale":
                $condition['is_on_sale']=1;
                $condition['over_time']=array('gt',time());
                break;
            case "success":
                //$successListCount=M('order_goods')->alias('og')->field('g.*')->join("LEFT JOIN __ORDER__ AS o ON og.order_sn = o.order_sn")->join('LEFT JOIN __GOODS__ AS g ON og.goods_id = g.id')->where(array('user_id'=>session('mobile.id'),'o.status'=>3))->count();
                //获取商家卖出的所有商品
                $orderModel=D('Common/Order');
                $successList=$orderModel->getSellerSoldGoods();
                $this->assign('time',time());
                break;
            case "over":
                $condition['over_time'] = array('between',array(0,time()));
                $condition['g.status']=1;
                $condition['goods_num']=1;
                break;
        }

        $this->display();


    }
    /************************************************广告推广**************************************************************/
    //广告推广列表
    public function advListAction(){
        $pageNum = I('p',1,'strip_tags');
        $pageType=I('pageType');
        switch ($pageType){
            case 'online':
                $pageCondition['pageType']='online';
                break;
            case 'store':
                $pageCondition['pageType']='store';
                break;
        }
        $advModel = D('Common/Adv');
        $condition['seller_id']=session('mobile.id');
        $result=$advModel->getStoreAdvPageList($pageNum,$condition,$pageCondition,$pageType);
        if(IS_AJAX){
            $this->assign('is_ajax',1);
            $this->assign('list',$result['list']);
        }else{
            $this->assign('pageCount',$result['totalPage']);
        }
        $this->assign('pageType',$pageType);
        $this->display();
    }
}
?>
