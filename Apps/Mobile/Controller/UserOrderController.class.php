<?php

namespace Mobile\Controller;
use Think\Controller;
/**
*  网站前台订单管理类
*/
class UserOrderController extends MemberController{


    //提交订单
    public function submitOrderAction(){
        $user_id=session('mobile.id');
        $coupon_id=session('cart_coupon_id')?session('cart_coupon_id'):0;
        $address_id=cookie('cart_address_id')?cookie('cart_address_id'):0;
        $delivery_way=!empty(cookie('cart_delivery_way'))?cookie('cart_delivery_way'):0;
        $share_bill=session('cart_share_bill')?session('cart_share_bill'):0;
        $cartIds=cookie('cartIds');
        //检测购物车是否有商品
        $OrderModel=D('Common/Order');
        $cart_info = $OrderModel->getUserOrder($cartIds);
        if ($cart_info['num'] == 0)
        {
            /* 购物车是空的 */
            $this->tips("购物车是空的!",1,2000,U("Mobile/Users/index"));exit;
        }
        /*  检查是否添加收货人地址  */
        if (empty($address_id)) {
            $condition['is_default']='1';
        }else {
            $condition['address_id']=$address_id;
        }
        $userOneAddress = $OrderModel->getUserOneAddress($condition,$user_id);
        if ( $delivery_way == 1 && $address_id== 0 ) {
            $address_id = $userOneAddress['address_id'];
        }
        if ( !$userOneAddress && $delivery_way!=0) {
            $this->tips("用户没有填写收货地址!",1,2000,U("Mobile/UserOrder/add"));exit;
        }
        /* 优惠券数据处理 */
        if (isset($coupon_id) && $coupon_id!=0) {
            $coupon = $OrderModel->getUserOneCoupon($coupon_id);
            if (empty($coupon))
            {
                $this->tips("你没有这个优惠劵!",1,2000,U("Mobile/Users/index"));exit;
            }
            $time = time();
            if ($coupon['use_start_date'] <= $time && $coupon['use_end_date'] >= $time)
            { }else {
                $this->tips("你的优惠劵时间不对啊!",1,2000,U("Mobile/Users/index"));exit;
            }
            if ($coupon['min_amount'] > $cart_info['money'])
            {
                $this->tips("你的订单金额不足!",1,2000,U("Mobile/Users/index"));exit;
            }
            unset($time);
            $cart_info['discount'] = $coupon['type_money'];
        }

        /*分享币数据处理*/
        $userShallBill=$OrderModel->getUserShallBill($user_id);
        if ( $share_bill > $userShallBill || $share_bill > $cart_info['money']) {
            $this->tips("你的购物币有点小问题!",1,2000,U("Mobile/Users/index"));exit;
        }
        //验证订单价格是否正确
        $allMoney=$OrderModel->countPrice($cartIds);
        if ( floor($cart_info['money']) != floor($allMoney) ) {
            $this->tips("你的订单价格不对啊!",1,2000,U("Mobile/Users/index"));exit;
        }
        //检测订单的快递是否全部填写
        if ( $delivery_way == 1) {
            $cart_way_info=unserialize(session('cart_way_info'));
            if (!$cart_way_info) {
                $this->tips("你的快递没有选择!",1,2000,U("Mobile/UserOrder/deliveryWay"));exit;
            }
        }
        //获得快递选择的信息
        foreach ($cart_way_info as $key => $v) {
            $selectTransportPrice=$selectTransportPrice+$v['price'];  //运费总价
        }
        /*  检查商品库存 */
        $groupByOrderArr = $OrderModel->groupByOrder($cartIds);  //根据seller_id分组的订单数组
            if ($delivery_way == 0) {
                foreach ($cart_info['info'] as $key => $v) {
                if ($v['stock'] < $v['counts'] ) {
                    $this->tips("商品库存不足!",1,2000,U("Mobile/Users/index"));exit;
                    }
                }
            }else {
                foreach ($groupByOrderArr as $key => $vd) {
                    if ($v['online_stock'] < $v['scounts']) {
                        $this->tips("商品库存不足!",1,2000,U("Mobile/Users/index"));exit;
                    }
                }
            }
        /*生成订单*/
        //事务：表必须是innodb
        $OrderModel->startTrans();//开启事务
        //生成订单(分别生成主单和根据store_id拆开的分单)
        $oneOrder_sn=StrOrderOne();
        $data['order_sn']=$oneOrder_sn;
        $orderMoney=$cart_info['money']-$share_bill-$coupon['type_money'];
        $data = array(
            'order_sn'      =>$oneOrder_sn,
            'money'         => $orderMoney+$selectTransportPrice,
            'address_id'    => $address_id,
            'delivery_way'  => $delivery_way,
            'status'        => '0',
            'add_time'      => time(),
            'pay_type'      => '1',
            'seller_id'      => '0',
            'original_price'=> $allMoney+$selectTransportPrice,
            'uid'           =>   $user_id,
            'coupon_id'     =>$coupon_id,
            'share_bill'    =>$share_bill
        );
        //判断是否需要拆单
        if (count($groupByOrderArr) == 1) {
            $data['is_son'] = 2;
            $data['seller_id'] = $groupByOrderArr['0']['seller_id'];   //订单卖家id
            $rsAffairs5=$OrderModel->createUserOneOrder($cart_info['info'],$oneOrder_sn,$data,$cart_way_info,$delivery_way);
        }else {
            $rsAffairs5=$OrderModel->createUserOrder($oneOrder_sn,$data,$groupByOrderArr,$address_id,$delivery_way,$user_id,$cart_way_info);
        }
        if (empty($coupon_id)) {
            $rsAffairs=true;
        }else {
            $rsAffairs=$OrderModel->updateCouponUseTime($coupon_id); //更新优惠劵使用时间
        }
        if (floor($share_bill) == floor(0)) {
            $rsAffairs2=true;
        }else {
            //冻结分享币
            $virtualCashLog=D('Common/VirtualCashLog');
            $data1['user_money'] = "-$share_bill";
            $data1['user_id'] = $user_id;
            $rsAffairs2=$virtualCashLog->add($data1);
        }
        /* 减去商品库存 */
        $rsAffairs3=$OrderModel->reduceGoodsNum($cart_info['info'],$delivery_way);
        /* 下单完成后清理商品，如清空购物车，或将团购拍卖的状态转为已下单之类的 */
        $rsAffairs4=$OrderModel->clearCart($cartIds);
        //清空cookie
        cookie('cartIds',null);
        session('cart_share_bill',null);
        cookie('cart_delivery_way',null);
        session('cart_coupon_id',null);
        cookie('cart_address_id',null);
        session('cart_way_info',null);
        //var_dump($rsAffairs);var_dump($rsAffairs2);var_dump($rsAffairs3);var_dump($rsAffairs4);var_dump($rsAffairs5);exit;
        if ( $rsAffairs && $rsAffairs2 && $rsAffairs3 && $rsAffairs4 && $rsAffairs5 ) {
            $OrderModel->commit();
        }else {
            $OrderModel->rollback();
            $this->tips("你的订单提交失败!",1,2000,U("Mobile/Users/index"));exit;
        }
        /* 到收银台付款 */
            $jumpUrl = U('Mobile/Wxpay/index')."?order_sn=".$oneOrder_sn;
            redirect($jumpUrl);
    }


    //店铺选择配送方式
    public function deliveryWayAction(){
        $cartIds=cookie('cartIds');
        $cartModel=D('Common/Cart');
        //获取用户收货地址的县区
        $address_id=cookie('cart_address_id');
        if (!$address_id) {
            $this->redirect('Mobile/UserOrder/receipt');
        }
        $result=M('users_address')->field('province,city,district')->where("address_id=$address_id")->find();
        $province_id=$result['province'];
        $city_id=$result['city'];
        $region_id=$result['district'];
        //获取快递信息
        $cart_way_info=unserialize(session('cart_way_info'));
        //获取订单商品信息  获取店铺物流
        $res=$cartModel->getGoodsInfoById($cartIds,$cart_way_info);
        //获取订单信息计算价格
        $sellerLogisticsMoel=D('Common/SellerLogistics');
        //计算店铺可用物流及费用
        $store_id_arr1=$sellerLogisticsMoel->getSellerLogistics($cartIds,$province_id,$city_id,$region_id);
        //var_dump($store_id_arr1);
        $store_id_arr=json_encode($store_id_arr1);
        //var_dump($store_id_arr);exit;
        if ($cart_way_info == false) {
            $cart_way_info['0'] = array(
                'store_id' => '1',
                'od' =>'0'
            );
        }
        $this->assign('delivery_way',0);
        $this->assign('store_id_arr',$store_id_arr);

        $this->assign('orderInfo',$res);
        $this->assign('info',$cart_way_info);
        $this->display();
    }

    //更新配送方式
    public function updataDeliveryWayAction(){
        $delivery_way=I('delivery');
        //计算运费
        $cartIds=cookie('cartIds');
        $cartModel=D('Common/Cart');
        //获取用户收货地址的县区
        $address_id=cookie('cart_address_id');
        $result=M('users_address')->field('province,city,district')->where("address_id=$address_id")->find();
        $province_id=$result['province'];
        $city_id=$result['city'];
        $region_id=$result['district'];
        //计算店铺可用物流及费用
        $sellerLogisticsMoel=D('Common/sellerLogistics');
        $storeIdArr=$sellerLogisticsMoel->getSellerLogistics($cartIds,$province_id,$city_id,$region_id);
        //var_dump($delivery_way);var_dump($storeIdArr);exit;
        //验证提交的表单数据是否合法
        foreach ($delivery_way as $key => $v) {
            //验证运费是否正确合法
            foreach ($storeIdArr[$v['seller_id']] as $key => $vs) {
                if ($vs['id'] == $v['id']) {
                    //计算运费是否相等
                    if ($v['price'] != $vs['price']) {
                        $this->ajaxInfoReturn("","您的运费价格不对啊!",0);
                    }
                }
            }
            if ($v['seller_id'] =="") {
                $this->ajaxInfoReturn("","您的商品卖家不存在!",0);
            }elseif ($v['delivery_way'] =="") {
                $this->ajaxInfoReturn("","您的商品没有选择配送方式!",0);
            }elseif ($v['value'] =="") {
                $this->ajaxInfoReturn("","您的值不对啊!",0);
            }
        }
        $delivery_way=serialize($delivery_way);
        $a=session('cart_way_info',$delivery_way,1800);
        $this->redirect("Mobile/UserOrder/order");
    }

    //ajax获取input的值
    public function getDeliverValueAction(){
        $cart_way_info=unserialize(session('cart_way_info'));
        if (IS_AJAX) {
            $store_id=I('store_id');
            $index=I('index');
            switch ($index) {
                case '0':
                $key='id';
                break;
                case '1':
                $key='price';
                break;
                default:
                $key='value';
                break;
            }
            $data=$cart_way_info[$store_id][$key];
            $this->ajaxInfoReturn($data,'获取成功',1);
        }
    }

    //更新配送方式deliway cookie
    public function updateWayCookieAction(){
        $delivery_way=I('delivery_way');
        $rs=cookie('cart_delivery_way',$delivery_way);
        if ($rs) {
            $this->ajaxInfoReturn("",'更新成功!',1);
        }else {
            $this->ajaxInfoReturn("",'更新失败!',1);
        }
    }

    //订单结算(一手商品)
    public function orderAction(){
        $user_id = session('mobile.id');
        //一手商品走购物车
        if (!$cartIds) {
            $cartIds=cookie('cartIds');
        }
        //检测购物车是否有商品
        if (!$cartIds) {
            $this->tips("你的购物车是空的!",1,2000,U("Mobile/Users/index"));exit;
        }
        cookie('cartIds',$cartIds,3600);
        $coupon_id  = session('cart_coupon_id');
        $address_id = cookie('cart_address_id');
        $delivery_way=!empty(cookie('cart_delivery_way'))?cookie('cart_delivery_way'):0; //获取配送方式
        $daliver_arr=unserialize(session('cart_way_info'))?unserialize(session('cart_way_info')):0; //获取订单的配送信息
        $this->assign('cart_way_info',$daliver_arr);
        //var_dump($daliver_arr);exit;
        foreach ($daliver_arr as $key => $v) {
            $yunfei=$v['price']+$yunfei;
        }
        $share_bill=session('cart_share_bill');
        if (empty($address_id)) {
            $condition['is_default']=1;
        }else {
            $condition['address_id']=$address_id;
        }
        $returnUrl=$_SERVER['HTTP_REFERER'];
        //统计用户所有收货地址
        $addressCounts=M('users_address')->where("user_id=$user_id")->count();
        //订单列表
        $OrderModel=D('Common/Order');
        $orderinfo=$OrderModel->getUserOrder($cartIds);
        $checkStock = true;
        //检测是否所有的购物车商品都在店铺有货
        foreach ($orderinfo['info'] as $key => $v) {
            $checkStock = $checkStock&&($v['online_stock'] && $v['stock']);
        }
        //var_dump($checkStock);exit;
        $this->assign('checkStock',(int)$checkStock);
        //根据优惠劵选取id获取优惠劵信息
        if ($coupon_id) {
            $couponInfo=$OrderModel->getCouponInfo($coupon_id);
        }
        //获得订单可用优惠劵总数
        $CouponArr=$OrderModel->getCouponArr($cartIds);
        $goods_array=$CouponArr['info'];
        $couponNum1=D('Common/Coupon')->orderCouponAction($orderinfo['money'],$goods_array);
        //获得用户收货地址
        $userDefaultAddress=$OrderModel->getUserOneAddress($condition,$user_id);
        //var_dump($userDefaultAddress);
        if ($userDefaultAddress) {
            cookie('cart_address_id',$userDefaultAddress['address_id']);
        }
        //用户分享币
        $userShallBill=$OrderModel->getUserShallBill($user_id);
        //用户应付款金额
        $applyMoney=$orderinfo['money']-$couponInfo['type_money']-$share_bill;
        $applyMoney=number_format($applyMoney,2);
        $this->assign('yunfei',number_format($yunfei,2));
        $this->assign('couponNum',$couponNum1['couponNum']);
        $this->assign('applyMoney',$applyMoney);
        $this->assign('share_bill',$share_bill);
        $this->assign('delivery_way',$delivery_way);
        $this->assign('userShallBill',$userShallBill);
        $this->assign('ctype_money',$couponInfo['type_money']);   //优惠劵金额
        $this->assign('userDefaultAddress',$userDefaultAddress);
        $this->assign('coupon_id',$coupon_id);
        $this->assign('userId',$user_id);
        $this->assign('addressCounts',$addressCounts);
        $this->assign('returnUrl',$returnUrl);
        $this->assign('orderMoney',number_format($orderinfo['money'],2));
        $this->assign('orderCounts',$orderinfo['num']);
        //dump($orderinfo['info']);exit;
        $this->assign('orderInfo',$orderinfo['info']);
        $this->assign('daliverArr',$daliver_arr?$daliver_arr:0);
        $this->display();
    }



    //优惠劵
    public function couponAction(){
        $cartIds=cookie('cartIds');
        $user_id=session('mobile.id');
        $action=I('action','canUse');
        //用户优惠劵
        $CouponArr=D('Common/Order')->getCouponArr($cartIds);
        $order_price=$CouponArr['money'];
        $goods_array=$CouponArr['info'];
        $result=D('Common/Coupon')->orderCouponAction($order_price,$goods_array);
        //var_dump($result);exit;
        $returnUrl = urlencode($_SERVER['HTTP_REFERER']);//获取上个操作页面
        $this->assign('share_bill',$share_bill);
        $this->assign('returnUrl',$returnUrl);
        $this->assign('action',$action);
        $this->assign('cantUseCouponNum',$result['cantUseCouponNum']);
        $this->assign('couponNum',$result['couponNum']);
        $this->assign('can_use',$result['can_use']);
        $this->assign('cant_use',$result['cant_use']);
        $this->display("UserOrder/myCoupon");
    }

    /*选择优惠劵*/
    public function selectCouponInfoAction(){
        $id = I("get.id",0);
        $returnUrl = I("get.return_url",'');
        $defaultUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        if($id==0||$returnUrl==""){
            redirect($defaultUrl);
        }else{
            $returnUrl = urldecode($returnUrl);
            cookie("cart.coupon_id",$id);
            redirect($returnUrl);
        }
    }

    //选择收货地址
    public function selectOrderAddressAction(){
        $id = I("get.id",0);
        $returnUrl = urldecode($returnUrl);
        //如果收货地址改变,则清空选择的配送信息
        if (    $id != cookie('cart_address_id') ) {
            session('cart_way_info',null);
        }
        cookie("cart_address_id",$id);   //存取收货地址到cookie
        $this->redirect('UserOrder/order');
    }

    //分享币
    public function shareBillAction(){
        //用户分享币
        $user_id=session('mobile.id');
        $userShallBill=D('Common/Order')->getUserShallBill($user_id);
        $this->assign('shareBill',C('SHARE_BILL'));
        $this->assign('userShallBill',$userShallBill);
        $this->display();
    }

    //更新分享币的cookie
    public function updataShareBillAction(){
        $cart_share_bill=I('post.billMoney1');
        if (IS_POST) {
            if (!is_numeric($cart_share_bill)) {
                $this->tips("您的优惠币格式不对!",1,2000,$_SERVER['HTTP_REFERER']);exit;
            }
        }
        //var_dump($cart_share_bill);exit;
        session('cart_share_bill',$cart_share_bill,3600);
        $this->redirect('UserOrder/order');

    }

    //删除收货地址
    public function delOrderAddressAction(){
        $address_id=I('address_id');
        $condition['address_id']=$address_id;
        //判断如果这个收货地址在订单中存在,则不删除这个id
        if (M('order')->where("address_id=$address_id")->count() != 0) {
            $rs = M('usersAddress')->where("address_id=$address_id")->setField("is_show",1);  //让原来的收货地址隐藏
        }else {
            $rs=M('usersAddress')->where($condition)->delete();
        }
        if ($rs) {
            $this->ajaxInfoReturn("",'删除成功!',1);
        }else {
            $this->ajaxInfoReturn("",'删除失败!',0);
        }
    }

    //编辑用户收货地址
    public function editUserAddressAction(){
        $address_id = I('address_id');
        $user_id=session('mobile.id');
        $condition = array(
            'user_id' => $user_id,
            'address_id'=>$address_id
        );
        // $result=M('usersAddress')->where($condition)->find();
        $result=D('Common/Order')->getUserOneAddress($condition,$user_id);
        $this->assign('address_id',$address_id);
        $this->assign('list',$result);
        $this->display();
    }

    //更新收货地址
    public function updataUserAddressAction(){
        $user_id  = session('mobile.id');
        $dataArr  = I('post.');
        //var_dump($dataArr);exit;
        $address_id =  I('post.address_id');
        $a=explode(',',$dataArr['data']['area_list']);
        foreach ($a as $key => $value) {
            $dataArr[]=$value;
        }
        $data = array(
            'consignee' =>$dataArr['person'] ,
            'mobile' =>$dataArr['mobile'] ,
            'address' =>$dataArr['address'] ,
            'province' =>$dataArr['0'] ,
            'city' =>$dataArr['1'] ,
            'district' =>$dataArr['2'],
            'user_id' => $user_id,
        );
        //判断如果这个收货地址在订单中存在,则不删除这个id
        if (M('order')->where("address_id=$address_id")->count() != 0) {
            $rs1 = M('usersAddress')->add($data);   //添加一个新数据
            $rs2 = M('usersAddress')->where("address_id=$address_id")->setField("is_show",1);  //让原来的收货地址隐藏
        }else {
            $rs1 = M('usersAddress')->where("address_id=$address_id")->save($data); //更新
            $rs2 = true;
        }
        $dataInfo=array(
            'status'=>'1',
            'info'=>'更新收货地址成功'
        );
        if ($rs1!==false && $rs2 !== false) {
            $this->ajaxInfoReturn($dataInfo,'json');
        }else {
            $this->ajaxInfoReturn("","更新收货地址失败",0);
        }

    }

    //添加用户收货地址
    public function addUserAddressAction(){
        $user_id=session('mobile.id');
        $dataArr=I('post.');
        $a=explode(',',$dataArr['data']['area_list']);
        foreach ($a as $key => $value) {
            $dataArr[]=$value;
        }
        $data = array(
            'consignee' =>$dataArr['person'] ,
            'mobile' =>$dataArr['mobile'] ,
            'address' =>$dataArr['address'] ,
            'province' =>$dataArr['0'] ,
            'city' =>$dataArr['1'] ,
            'district' =>$dataArr['2'],
            'user_id' =>$user_id,
            'is_default' => '1'
        );
        $condition['user_id']=$user_id;
        $condition['is_default']='1';
        M('users_address')->where($condition)->setField('is_default','0');
        M('usersAddress')->add($data);
        $dataInfo=array(
            'status'=>'1',
            'info'=>'加入收货地址成功'
        );
        $this->ajaxInfoReturn($dataInfo,'json');
    }

    public function addAction(){
        $this->display("UserOrder/newAddress");
    }

    //更新默认收货地址
    public function updateDefaultAddressAction(){
        $user_id=session('mobile.id');
        $address_id=I('address_id');
        $data1['is_default']='0';
        $data['is_default']='1';
        $map['user_id']=$user_id;
        $map['is_default']='1';
        M('usersAddress')->where($map)->save($data1);
        M('usersAddress')->where("address_id=$address_id")->save($data);
        $dataInfo=array(
            'status'=>'1',
            'info'=>'修改默认地址成功'
        );
        $this->ajaxInfoReturn($dataInfo,'json');
    }

    //用户收货地址管理
    public function receiptAction(){
        $user_id=session('mobile.id');
        $cart_id=cookie('cartIds');
        $result=D('Common/Order')->getUserAddress($user_id);
        if (IS_POST) {
            $dataArr=I('post.');
            //var_dump($dataArr);
            //验证用户填写数据
            if($dataArr['person']==""){
                $this->ajaxInfoReturn('',"亲，请填写收货人名字！",0);
            }
            if($dataArr['mobile']==""){
                $this->ajaxInfoReturn('',"亲，请填写手机号！",0);
            }
            if(!regexp_str('mobile',$dataArr['mobile'])){
                $this->ajaxInfoReturn('',"亲，手机格式有误！",0);
            }
            $areaListData = $dataArr['data']['area_list'];
            if(!$areaListData){
                $this->ajaxInfoReturn('',"亲，请选择城市！",0);
            }
            $regionArr = explode(',',$areaListData);
            if(count($regionArr)<3){
                $this->ajaxInfoReturn('',"亲，城市选择异常！",0);
            }
            if($dataArr['address']==""){
                $this->ajaxInfoReturn('',"亲，请填写收货地址！",0);
            }
            $regionArr = explode(',',$dataArr['data']['area_list']);
            foreach ($regionArr as $key => $value) {
                $tempArr[]=$value;
            }
            if (I('post.action') == 'edit') {
                $this->updataUserAddressAction();
            }else {
                $this->addUserAddressAction();
            }
        }
        $returnUrl = urlencode($_SERVER['HTTP_REFERER']);//获取上个操作页面
        $this->assign('cart_id',$cart_id);
        $this->assign('returnUrl',$returnUrl);
        $this->assign('list',$result);
        $this->display();
    }

    //订单跳转清空cookie
    public function redirectCookieNullAction(){
        cookie('cartIds',null);
        session('cart_share_bill',null);
        cookie('cart_delivery_way',null);
        session('cart_coupon_id',null);
        cookie('cart_address_id',null);
        session('cart_way_info',null);
        $this->redirect('Cart/index');
    }


    public function cancelOrderAction(){
        //手动取消过时订单
        if (IS_AJAX) {
            $orderModel=D('Common/Order');
            $cron_time=C('CRON_ORDER_TIME');
            $order_sn=I('order_sn');
            $action='myCancel';
            $rs=$orderModel->rollOrder($cron_time,$action,$order_sn);
            if ($rs) {
                $this->ajaxInfoReturn("",'取消订单成功!',1);
            }else {
                $this->ajaxInfoReturn("",'取消订单失败!',0);
            }
        }

    }



    public function testAction(){
        $test = $this->assign('title','web_title');
        var_dump($test);
        //    I方法
        //    I('变量类型.变量名/修饰符',['默认值'],['过滤方法或正则'],['额外数据源'])
    }


}
?>
