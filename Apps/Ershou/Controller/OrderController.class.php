<?php

namespace Ershou\Controller;
use phpDocumentor\Reflection\Types\This;
use Think\Controller;
/**
 *  网站前台订单管理类
 */
class OrderController extends MemberController{

    //提交订单
    public function submitOrderAction(){
        $orderArr['user_id'] = $userId = session('mobile.id');
        $orderArr['goods_id'] = $goodsId = cookie('sh_goods_id')?cookie('sh_goods_id'):0;
        $orderArr['address_id'] = $addressId = cookie('sh_address_id')?cookie('sh_address_id'):0;
        //检测订单确认信息
        if(!$userId) {
            $this->tips("亲，您的登录异常!",1,2000,U("Ershou/Login/login"));
            die;
        }
        if(!$goodsId) {
            $this->tips("亲，您购买的商品异常!",1,2000,U("Ershou/Index/index"));
            die;
        }
        if(!$addressId) {
            $this->tips("亲，您的收货地址异常!",1,2000,U("Ershou/Goods/detail",array("id"=>$goodsId)));
            die;
        }
        /*检查商品库存*/
        $goodsNum = 1;//购买商品的数量
        $goodsModel = D("Common/ShGoods");
        $goodsInfo = $goodsModel->getGoodsPartInfo($goodsId);
        if($goodsInfo['goods_num'] < $goodsNum) {
            $this->tips("亲，你下手晚了!",1,2000,U("Ershou/Index/index"));
            die;
        }
        /*检查收货人地址*/
        $usersAddressModel = D("Common/UsersAddress");
        $condition['user_id'] = $userId;
        $condition['address_id'] = $addressId;
        $userAddressInfo = $usersAddressModel->getUserAddressInfo($condition);
        if(!$userAddressInfo){
            $this->tips("亲，你的收获地址信息有异常！",U("Ershou/Order/receipt"));
            die;
        }
        /*生成订单*/
        //事务：表必须是innodb
        $orderModel = D('Common/Order');
        $orderModel->startTrans();//开启事务
        //生成订单(分别生成主单和根据store_id拆开的分单)
        $orderSn = StrOrderOne();
        $data['order_sn'] = $orderSn;
        $orderMoney = number_format($goodsInfo['goods_price']+$goodsInfo['logistics_price'],2);
        //订单信息
        $data = array(
            'order_sn'        => $orderSn,//订单号
            'money'           => $orderMoney,//订单总金额
            'address_id'      => $addressId,//收获地址ID
            'delivery_way'    => 1,//收获方式,1为快递
            'status'          => '0',//订单状态
            'add_time'        => time(),//提交时间
            'pay_type'        => '1',//支付方式，1为微信
            'seller_id'       => $goodsInfo['user_id'],//卖家ID
            'original_price'  => $orderMoney ,//原价
            'uid'             => $userId,//买家ID
            'coupon_id'       => 0,//优惠卷
            'share_bill'      => 0,//分享币使用
            'is_son'          => 2,//未拆单订单
            'is_used'         => 1,//二手商品订单
            'parent_order_sn' => $orderSn//父类订单
        );
        //订单商品
        $orderGoodsArr = array(array(
            'order_sn'       => $orderSn,//订单编号
            'goods_id'       => $goodsInfo['id'],//商品ID
            'goods_title'    => $goodsInfo['goods_title'],//商品标题
            'goods_thumb'    => $goodsInfo['goods_thumb'],//商品缩略图
            'goods_price'    => $goodsInfo['goods_price'],//商品价格
            'store_id'       => 0,//店铺ID
            'is_used_goods'  => 1,//是否为二手商品
            'counts'         => 1,//商品购买的数量
        ));
        //物流信息
        $logisticsArr = array(array(
            'order_sn'       => $orderSn,//订单编号
            'id'             => 0,//物流公司ID
            'price'          => $goodsInfo['logistics_price']//快递费用
        ));
        $rsAffairs = $orderModel->createUserOneOrder($orderGoodsArr,$orderSn,$data,$logisticsArr,1);
        //减去商品库存
        $rsAffairs1 = $goodsModel->updateGoodsNum($goodsInfo['id'],1);
        //清空cookie
        cookie('sh_goods_id',null);
        cookie('sh_address_id',null);
        if ($rsAffairs && ($rsAffairs1!==false)){
            $orderModel->commit();
        }else {
            $orderModel->rollback();
            $this->tips("你的订单提交失败!",1,2000,U("Mobile/Users/index"));
            die;
        }
        /* 到收银台付款 */
        $jumpUrl = U('Mobile/Wxpay/index')."?order_sn=".$orderSn;
        redirect($jumpUrl);
    }

    /**订单结算*/
    public function confirmOrderAction(){
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上一个回调页面的url
        $userId = session('mobile.id');
        if(!$userId){
            $this->tips("亲，请先登录!",1,2000,U('Ershou/Login/login'));
            die;
        }
        //二手商品走购物车
        $goodsId = I('id');
        if (!$goodsId) {
            $goodsId = cookie("sh_goods_id");
            if(!$goodsId){
                $this->tips("亲，你还没有选择宝贝!",1,2000,$returnUrl);die;
            }
        }else{
            cookie("sh_goods_id",$goodsId);//把当前商品id写入cookie
        }
        //获取商品对应的信息
        $goodsModel = D("Common/ShGoods");
        $goodsInfo = $goodsModel->getGoodsPartInfo($goodsId);
        if($goodsInfo['goods_num']==0){
            $this->tips("亲，你下手晚了!",1,2000,$returnUrl);
            die;
        }
        if($goodsInfo['status']!=1){
            $this->tips("亲，该宝贝已下架!",1,2000,$returnUrl);
            die;
        }
        $addressId = cookie('sh_address_id');
        //获取配送方式
        $deliveryWay = 1;
        if (!$addressId) {
            $condition['is_default']=1;
        }else {
            $condition['address_id'] = $addressId;
        }
        $orderModel = D("Common/Order");
        //用户所有收货地址
        $addressCounts = M('users_address')->where("user_id=$userId")->count();
        //获得用户收货地址
        $userDefaultAddress= $orderModel->getUserOneAddress($condition,$userId);
        //var_dump($userDefaultAddress);
        if ($userDefaultAddress) {
          cookie('sh_address_id',$userDefaultAddress['address_id']);
        }
        //用户应付款金额
        $goodsMoney = $goodsInfo['goods_price'];//商品价格
        $logisticsPrice = $goodsInfo['logistics_price'];//商品物流费用
        $applyMoney = $goodsMoney + $logisticsPrice;
        $applyMoney = number_format($applyMoney,2);
        $this->assign('applyMoney',$applyMoney);
        $this->assign('userDefaultAddress',$userDefaultAddress);
        $this->assign('addressCounts',$addressCounts);
        $this->assign('returnUrl',$returnUrl);
        $this->assign('goodsInfo',$goodsInfo);
        $this->display();
    }

    //用户收货地址管理
    public function receiptAction(){
        $userId = session('mobile.id');
        $result = D('Common/Order')->getUserAddress($userId);
        if (IS_POST) {//添加、修改收获地址
            $usersAddressModel = D('Common/UsersAddress');
            $dataArr['consignee'] = I('post.person');
            $dataArr['mobile'] = I('post.mobile');
            $areaListData = I('post.data');//城市选择
            $dataArr['address'] = I('post.address');

            //验证用户填写数据
            if($dataArr['consignee']==""){
                $this->ajaxInfoReturn('',"亲，请填写收货人名字！",0);
            }
            if($dataArr['mobile']==""){
                $this->ajaxInfoReturn('',"亲，请填写手机号！",0);
            }
            if(!regexp_str('mobile',$dataArr['mobile'])){
                $this->ajaxInfoReturn('',"亲，手机格式有误！",0);
            }
            if(!$areaListData){
                $this->ajaxInfoReturn('',"亲，请选择城市！",0);
            }
            $regionArr = explode(',',$areaListData['area_list']);
            if(count($regionArr)<3){
                $this->ajaxInfoReturn('',"亲，城市选择异常！",0);
            }
            if($dataArr['address']==""){
                $this->ajaxInfoReturn('',"亲，请填写收获地址！",0);
            }
            $dataArr['province'] = $regionArr[0];//省份ID
            $dataArr['city'] = $regionArr[1];//城市ID
            $dataArr['district'] = $regionArr[2];//县、区ID

            $dataArr['user_id'] = $userId;//当前用户ID
            if (I('post.action') == 'edit') {
                $map['user_id'] = $userId;
                $map['address_id'] = I('post.address_id');
                $result = $usersAddressModel->updateAddress($map,$dataArr);
                if($result!==false){
                    $this->ajaxInfoReturn('',"更新成功",1);
                }else{
                    $this->ajaxInfoReturn('',"更新失败",0);
                }
            }else {
                $result = $usersAddressModel->addAddress($dataArr);
                if($result){
                    $this->ajaxInfoReturn('',"添加成功",1);
                }else{
                    $this->ajaxInfoReturn('',"添加失败",0);
                }
            }
        }else{
            $returnUrl = urlencode($_SERVER['HTTP_REFERER']);//获取上个操作页面
            $this->assign('returnUrl',$returnUrl);
            $this->assign('list',$result);
            $this->display();
        }
    }

    /**选择收货地址*/
    public function selectAddressAction(){
        $id = I("get.id",0);
        cookie("sh_address_id",$id);
        $this->redirect('Ershou/Order/confirmOrder');
    }

    /**添加收货地址
     */
    public function addAddressAction(){
        $this->display();
    }
    /**修改收货地址
     */
    public function editAddressAction(){
        $addressId = I('address_id');
        $userId = session('mobile.id');

        $condition = array(
            'user_id' => $userId,
            'address_id'=>$addressId
        );
        $result = D('Common/Order')->getUserOneAddress($condition,$userId);
        $this->assign('address_id',$addressId);
        $this->assign('list',$result);
        $this->display();
    }

    /**
     * 更新默认收货地址
     */
    public function setDefaultAddressAction(){
        $user_id=session('mobile.id');
        $address_id=I('address_id');
        $usersAddressModel = D("Common/UsersAddress");//实例化用户收获地址模型
        $usersAddressModel->startTrans();//开启事务
        //重置默认，已为默认的修改为0
        $map1['user_id']=$user_id;
        $map1['is_default']='1';
        $data1['is_default']='0';
        $res1 = $usersAddressModel->updateAddress($map1,$data1);
        //设置当前的地址为默认地址
        $map['user_id'] = $user_id;
        $map['address_id'] = $address_id;
        $data['is_default']='1';
        $res = $usersAddressModel->updateAddress($map,$data);
        if(($res1!==false)&&($res!==false)){
            $usersAddressModel->commit();//事务提交
            $this->ajaxInfoReturn("","设置成功！",1);
        }else{
            $usersAddressModel->rollback();//事务回滚
            $this->ajaxInfoReturn("","设置失败！",0);
        }
    }

    /**删除收货地址*/
    public function delAddressAction(){
        $address_id = I('address_id');
        $condition['user_id'] = session('mobile.id');
        $usersAddressModel = D("Common/UsersAddress");
        $rs= $usersAddressModel->delAddress($address_id,$condition);
        if ($rs!==false) {
            $this->ajaxInfoReturn("",'删除成功!',1);
        }else {
            $this->ajaxInfoReturn("",'删除失败!',0);
        }
    }

    /**结算页面,正常返回上一操作页面清空cookie*/
    public function returnPreAction(){
        $goodsId = cookie("sh_goods_id");
        cookie('sh_goods_id',null);//清空当前购买商品id
        cookie('sh_address_id',null);//清空当前收货地址
        $this->redirect('Ershou/Goods/detail/id/'.$goodsId);
    }
}
 ?>
