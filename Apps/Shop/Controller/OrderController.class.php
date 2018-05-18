<?php
namespace Shop\Controller;
use Think\Controller;
//订单结算类
class OrderController extends Controller {


	//结算页
	public function settlementAction(){
		$user_id=session('shop.id');   //用户id
		$cartIds=I('cartIds');    //购物车id数组
		cookie("cartIds",$cartIds);
		if (!$cartIds) {
			$cartIds = cookie('cartIds');
		}
		$orderModel = D('Common/Order');
		//收货地址
		$this->receiptAction();
		//商家列表(根据seller_id分组)
        $sellerInfo=$orderModel->getGoodsList($cartIds);
		//所有商品信息
		$goodslist=$orderModel->getUserOrder($cartIds);
		//计算购物币金额
		$userShallBill=$orderModel->getUserShallBill($user_id);
		//获取最大购物币使用金额
		$shareBillMax =C('SHARE_BILL');
		//优惠劵信息
		$couponArr=$orderModel->getCouponArr($cartIds);			//购物车商品信息
		$goods_array=$couponArr['info'];
        $couponList=D('Common/Coupon')->orderCouponAction($couponArr['money'],$goods_array);
		//dump($userShallBill);exit;
		$this->assign("couponList",$couponList);
		$this->assign("shareBillMax",$shareBillMax);
		$this->assign("userShallBill",$userShallBill);
		$this->assign("couponInfo",$couponArr);
		$this->assign("goodslist",$goodslist['info']);
		$this->assign("sellerInfo",$sellerInfo);
		$this->display();
	}

	/**计算店铺可用物流及费用
	*
	**/
	public function logisticsAction(){
		$address_id= I('address_id','');		//获取用户选择的收货地址
		$cartIds = cookie('cartIds');   //购物车id数组
		//获取订单信息计算价格
        $sellerLogisticsMoel=D('Common/SellerLogistics');
        //计算店铺可用物流及费用
		$result=M('users_address')->field('province,city,district')->where("address_id=$address_id")->find();
        $province_id=$result['province'];
        $city_id=$result['city'];
        $region_id=$result['district'];
		//物流清单
        $store_id_arr1=$sellerLogisticsMoel->getSellerLogistics($cartIds,$province_id,$city_id,$region_id);
		$this->assign("list",$store_id_arr1);
		if (IS_AJAX) {
			$this->assign("isAjax",1);
		}
		$this->display();
	}

	//用户收货地址管理
    public function receiptAction(){
		$user_id=session('shop.id');   //用户id
		if (!$orderModel) {
			$orderModel = D('Common/Order');
		}
		$receipt= $orderModel->getUserAddress($user_id);			//收货地址列表
        $returnUrl = urlencode($_SERVER['HTTP_REFERER']);//获取上个操作页面
		$addressModel = M('users_address');
		$defaultId = $addressModel->where(array('is_default'=>1,'is_show'=>'0','user_id'=>$user_id))->getField('address_id');
		//$addressModel->field("")->where("address_id=$defaultId")->find();
		$this->assign('defaultId',$defaultId);
        $this->assign('cart_id',$cart_id);
        $this->assign('returnUrl',$returnUrl);
		$this->assign('list',$receipt);
		if (IS_AJAX) {
			$this->assign('isAjax','1');
			$this->display();
		}
    }


	//更新收货地址
	public function updataUserAddressAction(){
		$user_id  = session('shop.id');
		$data = I('post.data');
		$address_id =  I('post.address_id');
        //验证用户填写数据
        $this->checkData($data);
		$data['user_id'] = $user_id;
		//var_dump($data);exit;
		//判断如果这个收货地址在订单中存在,则不删除这个id
		if (M('order')->where("address_id=$address_id")->count() != 0) {
			$rs1 = M('usersAddress')->add($data);   //添加一个新数据
			$rs2 = M('usersAddress')->where("address_id=$address_id")->setField("is_show",1);  //让原来的收货地址隐藏
		}else {
			$rs1 = M('usersAddress')->where("address_id=$address_id")->save($data); //更新
			$rs2 = true;
		}
		if ($rs1!==false && $rs2 !== false) {
			$this->ajaxInfoReturn("","更新收货地址成功!",1);
		}else {
			$this->ajaxInfoReturn("","更新收货地址失败!",0);
		}
	}

	//添加用户收货地址
	public function addUserAddressAction(){
		$user_id=session('shop.id');
		$data = I('post.')['data'];
		$this->checkData($data);		//收货地址数据验证
		$data['user_id'] = $user_id;
		$data['is_default'] = 1;
		$condition['user_id']=$user_id;
		$condition['is_default']='1';
		M('users_address')->where($condition)->setField('is_default','0');  //取消默认地址
		$res=M('usersAddress')->add($data);
		if ($res) {
			$this->ajaxInfoReturn('',"加入收货地址成功!",1);
		}else {
			$this->ajaxInfoReturn("","加入收货地址失败!",0);
		}


	}

	//更新默认收货地址
	public function updateDefaultAddressAction(){
		$user_id=session('shop.id');
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

		//添加收货地址
	public function newaddressAction(){
		$this->display();
	}

		/*编辑收货地址
		*@params					$data 					arr                                 收货地址post数据
		*/
		public function editaddressAction(){
			$address_id = I('address_id');			//收货地址id
			//获得这个收货地址的信息
			$res=M('users_address')->where("address_id=$address_id")->find();
			$this->assign("info",$res);
			$this->display();
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

		/*收货地址数据验证
		*@params					$data 					arr                                 收货地址post数据
		*/
		public function checkData($dataArr){
			//验证用户填写数据
			if (IS_AJAX) {
				if($dataArr['consignee']==""){
	                $this->ajaxInfoReturn('',"亲，请填写收货人名字！",0);
	            }
	            if($dataArr['mobile']==""){
	                $this->ajaxInfoReturn('',"亲，请填写手机号！",0);
	            }
	            if(!regexp_str('mobile',$dataArr['mobile'])){
	                $this->ajaxInfoReturn('',"亲，手机格式有误！",0);
	            }
	            if($dataArr['province'] <= 0){
	                $this->ajaxInfoReturn('',"亲，请选择省份！",0);
	            }
				if($dataArr['city'] <= 0){
	                $this->ajaxInfoReturn('',"亲，请选择城市！",0);
	            }
				if($dataArr['district'] <= 0){
	                $this->ajaxInfoReturn('',"亲，请选择城市！",0);
	            }
	            if($dataArr['address']==""){
	                $this->ajaxInfoReturn('',"亲，请填写收货地址！",0);
	            }
			}
		}

		//提交订单
	    public function submitOrderAction(){
	        $user_id  =  session('shop.id');
			$data	=	I('post.');
	        $coupon_id=$data['data']['coupon_id']?$data['data']['coupon_id']:0;
	        $address_id=$data['data']['address_id']?$data['data']['address_id']:0;
	        $delivery_way=!empty($data['data']['delivery_way'])?$data['data']['delivery_way']:0;
	        $share_bill=!empty($data['canUseBill'])?$data['canUseBill']:0;
			$cart_way_info=$data['delivery'];
	        $cartIds=cookie('cartIds');
	        //检测购物车是否有商品
	        $orderModel=D('Common/Order');
	        $cart_info = $orderModel->getUserOrder($cartIds);
	        if ($cart_info['num'] == 0)
	        {
	            /* 购物车是空的 */
				$this->error('购物车是空的!',U("Shop/Cart/index"),2);
	        }
	        /*  检查是否添加收货人地址  */
	        if (empty($address_id)) {
	            $condition['is_default']='1';
	        }else {
	            $condition['address_id']=$address_id;
	        }
	        $userOneAddress = $orderModel->getUserOneAddress($condition,$user_id);
	        if ( $delivery_way == 1 && $address_id== 0 ) {
	            $address_id = $userOneAddress['address_id'];
	        }
	        if ( !$userOneAddress && $delivery_way!=0) {
				$this->error('用户没有填写收货地址!',U("Shop/Cart/index"),2);
	        }
	        /* 优惠券数据处理 */
	        if (isset($coupon_id) && $coupon_id!=0) {
	            $coupon = $orderModel->getUserOneCoupon($coupon_id);
	            if (empty($coupon))
	            {
					$this->error('你没有这个优惠劵!',U("Shop/Cart/index"),2);
	            }
	            $time = time();
	            if ($coupon['use_start_date'] <= $time && $coupon['use_end_date'] >= $time)
	            { }else {
					$this->error('你的优惠劵时间不对啊!',U("Shop/Cart/index"),2);
	            }
	            if ($coupon['min_amount'] > $cart_info['money'])
	            {
					$this->error('你的订单金额不足!',U("Shop/Cart/index"),2);
	            }
	            unset($time);
	            $cart_info['discount'] = $coupon['type_money'];
	        }
	        /*分享币数据处理*/
	        $userShallBill=$orderModel->getUserShallBill($user_id);
	        if ( $share_bill > $userShallBill || $share_bill > $cart_info['money']) {
				$this->error('你的购物币有点小问题!',U("Shop/Cart/index"),2);
	        }
	        //验证订单价格是否正确
	        $allMoney=$orderModel->countPrice($cartIds);
	        if ( floor($cart_info['money']) != floor($allMoney) ) {
				$this->error('你的订单价格不对啊!',U("Shop/Cart/index"),2);
	        }
	        //检测订单的快递是否全部填写
	        if ( $delivery_way == 1) {
				//获取订单信息计算价格
		        $sellerLogisticsMoel=D('Common/SellerLogistics');
		        //计算店铺可用物流及费用
				$result=M('users_address')->field('province,city,district')->where("address_id=$address_id")->find();
		        $province_id=$result['province'];
		        $city_id=$result['city'];
		        $region_id=$result['district'];
				//物流清单
		        $store_id_arr1=$sellerLogisticsMoel->getSellerLogistics($cartIds,$province_id,$city_id,$region_id);
				foreach ($cart_way_info as $k => &$v) {
					foreach ($store_id_arr1 as $ks => $vs) {
						if ($k ==$ks) {
							foreach ($vs as $key => $vd) {
								if ($vd['id'] == $v['id']) {
									$v['price'] = $vd['price'];
									$v['value'] = $vd['value'];
								}
							}
						}
					}
				}
	            if (!$cart_way_info) {
					$this->error('你的快递没有选择!',U("Shop/Cart/index"),2);
	            }
	        }
	        //获得快递选择的信息
	        foreach ($cart_way_info as $key => $v) {
	            $selectTransportPrice=$selectTransportPrice+$v['price'];  //运费总价
	        }
	        /*  检查商品库存 */
	        $groupByOrderArr = $orderModel->groupByOrder($cartIds);  //根据seller_id分组的订单数组
	            if ($delivery_way == 0) {
	                foreach ($cart_info['info'] as $key => $v) {
	                if ($v['stock'] < $v['counts'] ) {
						$this->error('商品库存不足!',U("Shop/Cart/index"),2);
	                    }
	                }
	            }else {
	                foreach ($groupByOrderArr as $key => $vd) {
	                    if ($v['online_stock'] < $v['scounts']) {
							$this->error('商品库存不足!',U("Shop/Cart/index"),2);
	                    }
	                }
	            }
	        /*生成订单*/
	        //事务：表必须是innodb
	        $orderModel->startTrans();//开启事务
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
	            $rsAffairs5=$orderModel->createUserOneOrder($cart_info['info'],$oneOrder_sn,$data,$cart_way_info,$delivery_way);
	        }else {
	            $rsAffairs5=$orderModel->createUserOrder($oneOrder_sn,$data,$groupByOrderArr,$address_id,$delivery_way,$user_id,$cart_way_info);
	        }
	        if (empty($coupon_id)) {
	            $rsAffairs=true;
	        }else {
	            $rsAffairs=$orderModel->updateCouponUseTime($coupon_id); //更新优惠劵使用时间
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
	        $rsAffairs3=$orderModel->reduceGoodsNum($cart_info['info'],$delivery_way);
	        /* 下单完成后清理商品，如清空购物车，或将团购拍卖的状态转为已下单之类的 */
	        $rsAffairs4=$orderModel->clearCart($cartIds);
	        //清空cookie
	        cookie('cartIds',null);
	        if ( $rsAffairs && $rsAffairs2 && $rsAffairs3 && $rsAffairs4 && $rsAffairs5 ) {
	            $orderModel->commit();
	        }else {
	            $orderModel->rollback();
				$this->error('你的订单提交失败!',U("Shop/Cart/index"),2);
	        }
	        /* 到收银台付款 */
	            $jumpUrl = U('Shop/Wxpay/index')."?order_sn=".$oneOrder_sn;
	            redirect($jumpUrl);
	    }


		public function manageAction(){
			$this->display();
		}


}
