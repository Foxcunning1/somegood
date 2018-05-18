<?php
//模型
namespace Common\Model;
use Think\Model;
use Think\Page;

class OrderModel extends Model{

    /**得到前台订单列表
     * @param int $pageNum                页码
     * @param array $map                  查询条件
     * @param array $pageCondition        分页条件
     * @param string $order               排序
     * @return mixed
     */
    public function getOrderPageList($pageNum = 1 , $map = array() , $pageCondition = array() , $order = 'add_time DESC,id DESC'){

      $orderModule = D('Common/Order');
      $page_size=C('ORDER_PAGE_SIZE');
      //统计总页数
       $subQuery= $orderModule->alias('o')->field('o.*,c.goods_title,g.goods_id,group_concat(c.goods_thumb) as tb')
       ->join("LEFT JOIN __ORDER__ AS mo ON mo.parent_order_sn = o.order_sn")
       ->join("LEFT JOIN __ORDER_GOODS__ AS g ON (o.order_sn = g.order_sn)or(mo.order_sn = g.order_sn)")
       ->join("LEFT JOIN __GOODS__ AS c ON c.id = g.goods_id")
       ->where($map)->group('o.order_sn')->order($order)->buildSql();
       $total=$orderModule->table($subQuery.' a')->count();
      $result['counts']=ceil($total/$page_size);
      $result['list'] = $orderModule->alias('o')->field('o.*,c.goods_title,g.goods_id,l.code,mol.logistics_sn,c.store_id,s.store_name,group_concat(c.goods_thumb) as tb')
      ->join("LEFT JOIN __ORDER__ AS mo ON mo.parent_order_sn = o.order_sn")
      ->join("LEFT JOIN __ORDER_GOODS__ AS g ON (o.order_sn = g.order_sn)or(mo.order_sn = g.order_sn)")
      ->join("LEFT JOIN __SELLER_DELIVERY__ AS c ON c.id = g.goods_id")
      ->join("LEFT JOIN __STORE__ AS s ON s.sid = c.store_id")
      ->join("LEFT JOIN __ORDER_LOGISTICS__ AS mol ON o.order_sn = mol.order_sn")
      ->join("LEFT JOIN __LOGISTICS__ AS l ON l.id = mol.type")
      ->where($map)->group('o.order_sn')->page("$pageNum,$page_size")->order($order)->select();
      //var_dump($result['list']);exit;
      return $result;
    }

    /**得到前台订单列表
     * @param int $pageNum                页码
     * @param array $map                  查询条件
     * @param array $pageCondition        分页条件
     * @param string $order               排序
     * @return mixed
     */
    public function getOrderPageListPC($pageNum = 1 , $map = array() , $pageCondition = array() , $order = 'add_time DESC,id DESC'){

      $orderModule = D('Common/Order');
      $page_size=C('ORDER_PAGE_SIZE');
      //统计总页数
       $subQuery= $orderModule->alias('o')->field('o.*,c.goods_title,g.goods_id,group_concat(c.goods_thumb) as tb')
       ->join("LEFT JOIN __ORDER__ AS mo ON mo.parent_order_sn = o.order_sn")
       ->join("LEFT JOIN __ORDER_GOODS__ AS g ON (o.order_sn = g.order_sn)or(mo.order_sn = g.order_sn)")
       ->join("LEFT JOIN __GOODS__ AS c ON c.id = g.goods_id")
       ->where($map)->group('o.order_sn')->order($order)->buildSql();
       $total=$orderModule->table($subQuery.' a')->count();
      $result['counts']=ceil($total/$page_size);
      //实例化分页类
      $page = new \Think\Page($total,C('PAGE_SIZE'),$pageCondition);
      $result['page']=$page->show();
      $result['list'] = $orderModule->alias('o')->field('o.*,c.goods_title,ua.consignee,g.goods_id,g.counts,l.code,mol.logistics_sn,c.store_id,s.store_name,group_concat(c.goods_thumb) as tb')
      ->join("LEFT JOIN __ORDER__ AS mo ON mo.parent_order_sn = o.order_sn")
      ->join("LEFT JOIN __ORDER_GOODS__ AS g ON (o.order_sn = g.order_sn)or(mo.order_sn = g.order_sn)")
      ->join("LEFT JOIN __SELLER_DELIVERY__ AS c ON c.id = g.goods_id")
      ->join("LEFT JOIN __STORE__ AS s ON s.sid = c.store_id")
      ->join("LEFT JOIN __USERS_ADDRESS__ AS ua ON ua.address_id = o.address_id")
      ->join("LEFT JOIN __ORDER_LOGISTICS__ AS mol ON o.order_sn = mol.order_sn")
      ->join("LEFT JOIN __LOGISTICS__ AS l ON l.id = mol.type")
      ->where($map)->group('o.order_sn')->page("$pageNum,$page_size")->order($order)->select();
      //dump($result['list']);exit;
      return $result;
    }

    //全部用户订单商品数组
    public function getOrderAllList($pageNum,$map,$pageCondition){
      $orderModule = M('Order');
      //$map['o.uid'] = array('eq',$user_id);
      $result = $orderModule->alias('o')->field('o.*,c.goods_title,g.goods_id,c.store_id,c.goods_thumb,s.store_name,g.counts')
      ->join("LEFT JOIN __ORDER__ AS mo ON mo.parent_order_sn = o.order_sn")
      ->join("LEFT JOIN __ORDER_GOODS__ AS g ON (o.order_sn = g.order_sn)or(mo.order_sn = g.order_sn)")
      ->join("LEFT JOIN __GOODS__ AS c ON c.id = g.goods_id")
      ->join("LEFT JOIN __STORE__ AS s ON s.sid = c.store_id")
      ->where($map)->order("add_time DESC,id DESC")->select();
      return $result;
    }

          /**得到订单详情页信息
         * @param int $map             主单id或者order_sn
         * @return mixed
         */
      public function selectOrderInfoById($map){
        $orderModule = M('Order');
        $order_time=C('CRON_ORDER_TIME');
        $time=time();
        $res=$orderModule->alias('o')->field("o.*,mol.logistics_sn,l.name,o.add_time+$order_time as cancel_time,u.user_name,g.goods_price as price,mol.pay_money,ct.type_money,group_concat(g.goods_thumb) as gthumb,group_concat(g.goods_title) as gtitle,ua.mobile,ua.consignee,group_concat(c.price) as gprice,group_concat(g.counts) as gcounts,g.goods_id,c.store_id,s.store_name,group_concat(c.goods_thumb) as tb,r.name as rname,b.name as oname,i.name as iname,ua.address,concat(i.name,b.name,r.name,ua.address) as dizhi")
        ->join("LEFT JOIN __ORDER__ AS mo ON mo.parent_order_sn = o.order_sn")
        ->join("LEFT JOIN __ORDER_GOODS__ AS g ON (o.order_sn = g.order_sn)or(mo.order_sn = g.order_sn)")
        ->join("LEFT JOIN __SELLER_DELIVERY__ AS c ON c.id = g.goods_id")
        ->join("LEFT JOIN __STORE__ AS s ON s.sid = c.store_id")
        ->join("LEFT JOIN __USERS__ AS u ON u.uid = o.seller_id")
        ->join("LEFT JOIN __USERS_ADDRESS__ AS ua ON ua.address_id = o.address_id")
        ->join("LEFT JOIN __REGION__ AS r ON ua.district = r.id")
        ->join("LEFT JOIN __REGION__ AS b ON ua.city = b.id")
        ->join("LEFT JOIN __REGION__ AS i ON ua.province = i.id")
        ->join("LEFT JOIN __COUPON__ AS co ON co.coupon_id = o.coupon_id")
        ->join("LEFT JOIN __COUPON_TYPE__ AS ct ON ct.type_id = co.coupon_type_id")
        ->join("LEFT JOIN __ORDER_LOGISTICS__ AS mol ON (o.order_sn = mol.order_sn) or (mo.order_sn = mol.order_sn)")
        ->join("LEFT JOIN __LOGISTICS__ AS l ON l.id = mol.type")
        ->where($map)->group('o.order_sn')->select();
            $oneGoodstitle=explode(',',$res['0']['gtitle']);
            $oneCounts  =  explode(',',$res['0']['gcounts']);
            $onePrice   =  explode(',',$res['0']['gprice']);
            $oneGoodsthumb=explode(',',$res['0']['gthumb']);
            foreach ($oneGoodstitle as $key => $vbb) {
                $rs[$key]['goods_title']=$vbb;
            }
            foreach ($oneGoodsthumb as $key => $vbb) {
                $rs[$key]['goods_thumb']=$vbb;
            }
            foreach ($oneCounts as $key => $vii) {
                $rs[$key]['counts']=$vii;
            }
            foreach ($onePrice as $key => $v) {
                $rs[$key]['price']=$v;
            }
            $res['0']['info']=$rs;
          //var_dump($res);exit;
        return $res;
      }

      //删除用户订单
      public function delOrder($order_sn){
        $orderModule = M('Order');
        $rs1 = $orderModule->alias('o')->field('o.order_sn,mo.order_sn as cc')
        ->join("LEFT JOIN __ORDER__ AS mo ON mo.parent_order_sn = o.order_sn")->where("o.order_sn=$order_sn")->select();
        foreach ($rs1 as $key => $value) {
          $map['order_sn']=$value['order_sn'];
          $data['status']=4;
          $rs3=$orderModule->where($map)->save($data);
          //   $User->where('id=5')->save($data);
        }
        if ($rs3 ) {
          return true;
        }
      }

       //根据订单号查询实时物流信息
       public function getLogisticsInfoByOrderSn($order_sn){
         $orderModule = M('Order');
         $result=$orderModule->alias('o')->field('o.order_sn,mol.logistics_sn,mol.type,l.code,l.name,ua.mobile')
         ->join("LEFT JOIN __ORDER_LOGISTICS__ AS mol ON mol.order_sn = o.order_sn")
         ->join("LEFT JOIN __LOGISTICS__ AS l ON l.id = mol.type")
         ->join("LEFT JOIN __USERS_ADDRESS__ AS ua ON ua.address_id = o.address_id")
         ->where("o.order_sn=$order_sn")->find();
         //快递100获取物流信息
         $logistics_info=get_logistics_info($result['code'],$result['logistics_sn']);
         $logistics_info['name']=$result['name'];
         $logistics_info['logistics_sn']=$result['logistics_sn'];
         S($order_sn,$logistics_info,10800);

         return $logistics_info;
       }


       /**订单结算的订单列表
       *@param $cartIds int 购物车id数组
       */

       public function getUserOrder($cartIds,$order = 'store_id DESC'){
         $cart['num']=0;
         $cart['money']=0;
         $a=array();
         $cartModel=M('cart');
         $cart_id=implode(',',$cartIds);
         $condition['c.cart_id']=array('in',$cart_id);
         $cart['info']=$cartModel->alias('c')->field('c.*,g.heavy,sd.is_onsale as is_on_sale,sd.stock,sd.sg_id,g.online_stock')
                      ->join("LEFT JOIN __SELLER_DELIVERY__ AS sd ON sd.id = c.goods_id")
                      ->join("LEFT JOIN __GOODS__ AS g ON g.id = c.real_goods_id")
                      ->where($condition)->order($order)->select();
                      //var_dump($cart['info']);
                      foreach ($cart['info'] as $key => $v) {
                          //购物车商品数量
                          $cart['num']=$v['counts']+$cart['num'];
                          //获得订单总金额
                         $cart['money']=$cart['money']+($v['goods_price'] * $v['counts']);
                      }
         return $cart;
       }


       /*优惠劵判断传递数组
       *@parm $cartIds  arr 购物车id
       *return arr
       */
       public function getCouponArr($cartIds){
         $cart['num']   = 0;
         $cart['money'] = 0;
         $a=array();
         $cartModel=M('cart');
         foreach ($cartIds as $key => $cart_id) {
           $cartinfo=$cartModel->alias('c')->field('c.goods_id,c.counts,c.goods_price,gc.id as goods_category')
                               ->join("LEFT JOIN __SELLER_DELIVERY__ AS sd ON sd.id = c.goods_id")
                               ->join("LEFT JOIN __GOODS__ AS g ON sd.sg_id = g.id")
                               ->join("LEFT JOIN __GOODS_CATEGORY__ AS gc ON gc.id = g.category_id")
                               ->where("cart_id=$cart_id")->select();
           $cart['num']=$cart['num']+$cartinfo['0']['counts'];
           $a=array_merge($a,$cartinfo);
           $cart['info']  = $a;
           $cart['money'] = $cart['money']+$cartinfo['0']['counts']*$cartinfo['0']['goods_price'];
         }
         return $cart;
       }


       //根据cart_id计算订单价格
       public function countPrice($cartIds){
         $a=0;
         $cartIds=implode(',',$cartIds);
         $where['c.cart_id']=array('in',$cartIds);
         $result=M('cart')->alias('c')->field('c.goods_id,c.counts,c.goods_price')->where($where)->select();
         foreach ($result as $key => $value) {
           $a=$a+$value['goods_price']*$value['counts'];
         }
         return $a;
       }

       //根据优惠劵选取id获取优惠劵信息
       public function getCouponInfo($coupon_id){
         $coupon=M('coupon');
         $caouponinfo=$coupon->alias('c')->field('c.*,ct.type_money')
         ->join("LEFT JOIN __COUPON_TYPE__ AS ct ON ct.type_id = c.coupon_type_id")->where("coupon_id=$coupon_id")->find();
         return $caouponinfo;
       }



        //获得用户一个优惠劵id的信息
        public function getUserOneCoupon($coupon_id,$user_id){
          $user_id=session('mobile.id');
          //$user_id=18;
          $condition['used_time']=0;
          $condition['coupon_id']=$coupon_id;
          $condition['user_id']=$user_id;
          $couponModule = D('Coupon');
          $result=$couponModule->field("c.coupon_id,c.coupon_type_id,ct.is_all,ctl.goods_cate,ct.type_money,ct.min_goods_amount,ct.type_name,ct.use_start_date,ct.use_end_date")->alias("c")
                                  ->join("LEFT JOIN __COUPON_TYPE__ AS ct ON c.coupon_type_id = ct.type_id")
                                  ->join("LEFT JOIN __COUPON_TYPE_LIST__ AS ctl ON ctl.coupon_type_id = ct.type_id")
                                  ->where($condition)->find();
          return $result;
        }


       //获得用户收货地址
       public function getUserOneAddress($condition,$user_id){
         //$user_id=session('mobile.id');
           $condition['user_id']=$user_id;
           $usersAddress=M('usersAddress');
          $result=$usersAddress->alias('ua')->field('ua.*,r.id,r.name as rname,o.name as oname,i.name as iname')
          ->join("LEFT JOIN __REGION__ AS r ON ua.district = r.id")
          ->join("LEFT JOIN __REGION__ AS o ON ua.city = o.id")
          ->join("LEFT JOIN __REGION__ AS i ON ua.province = i.id")
          ->where($condition)->find();
         //var_dump($result);exit;
         //echo $region->_sql();exit;
         return $result;
       }

       //判断用户多少分享币可用
       public function getUserShallBill($user_id){
         $result=M('virtualCashLog')->field('log_id,user_id,sum(user_money) as c,sum(frozen_money) as b')
         ->where("user_id=$user_id")->group('user_id')->select();
         //var_dump($result);exit;
         $money=$result['0']['c']-$result['0']['b'];
         return $money;
       }


       /**得到一个用户所有收货地址
        * @param $user_id int 用户id
        * @param $condition array 查询条件
        * @return mixed
        */
        public function getUserAddress($user_id,$order = "is_default DESC"){
          $condition['user_id']=$user_id;
          $usersAddress=M('usersAddress');
          $uAddress=$usersAddress->alias('ua')->field('ua.*,r.id,r.name as rname,o.name as oname,i.name as iname')
          ->join("LEFT JOIN __REGION__ AS r ON ua.district = r.id")
          ->join("LEFT JOIN __REGION__ AS o ON ua.city = o.id")
          ->join("LEFT JOIN __REGION__ AS i ON ua.province = i.id")
          ->where(array('user_id' =>$user_id ,'is_show'=>0 ))->order($order)->select();
          return $uAddress;
        }


      /**得到一个订单的详细信息
       * @param                     $id                               int                 订单id
       * @param                     $condition                  array            查询条件
       * @return mixed
       */
      public function getOneOrder($id = 0 ,$condition = array()){
        $billDiscountModule = M('order_bill_discount');
        $orderModule = D('Admin/Order');
        if($id > 0 && empty($condition)) {
          $order = $orderModule->relation(true)->find($id);
        }elseif($id <= 0 && !empty($condition)){
          $order = $orderModule->relation(array('order_goods','order_children','order_bill_discount'))->where($condition)->find();
        }elseif($id > 0 && !empty($condition)){
          $order = $orderModule->relation(array('order_goods','order_children','order_bill_discount'))->where($condition)->find($id);
        }else{
          $order = null;
        }
        return $order;
      }

      /**更新优惠劵使用时间
       * @param $coupon_id int 优惠劵id
       * @return mixed
       */
       public function updateCouponUseTime($coupon_id){
         $time=time();
         $data['used_time']=$time;
         $condition['coupon_id']=$coupon_id;
         $a=M('coupon')->where($condition)->save($data);
         return $a;
       }

       /**冻结用户分享币
        * @param        $user_id                int                     用户id
        * @param        $share_bill            int                     分享币
        * @return mixed
        */
        public function frozenBill($user_id,$share_bill){
          $condition['user_id']=$user_id;
          $data['change_time']=time();
          $everShareBill=M('virtualCashLog')->where($condition)->order('log_id desc')->limit(1)->find();
          $data['frozen_money']=$share_bill+$everShareBill['frozen_money'];
          M('virtualCashLog')->where($condition)->order('log_id desc')->limit(1)->save($data);
        }

        /**获得二手商品订单信息
         * @param $id int 商品id
         * @return mixed
         */
         public function getSecondOrderInfo($id){
              $res=M('goods_second_hand')->field("id,heavy,goods_thumb,want_price,goods_title,user_id")->where(array('id'=>$id))->select();
              $res['num']=1;
              $res['money']=$res['0']['want_price'];
              return $res;
         }


        /**根据seller_id分组订单
        *@param     $cartIds         arr        购物车id数组
        */

        public function groupByOrder($cartIds){
          $cart['num']=0;
          $cart['money']=0;
          $a=array();
          $cartModel=M('cart');
          $cartIds=implode(',',$cartIds);
            $where['c.cart_id']=array('in',$cartIds);
          //$Model->where($map)->select(); // 也支持
            $cartinfo=$cartModel->alias('c')->field('c.*,sum(c.counts) as scounts,g.online_stock,group_concat(c.goods_id) as gid,group_concat(c.counts) as gcounts,group_concat(c.goods_price) as gprice,c.goods_thumb,c.goods_price,sd.stock,sum((c.goods_price*c.counts)) as moneyCount')
            ->join("LEFT JOIN __SELLER_DELIVERY__ AS sd ON sd.id = c.goods_id")
            ->join("LEFT JOIN __GOODS__ AS g ON g.id = sd.sg_id")
            ->where($where)->group('c.seller_id')->order('c.cart_id ASC')->select();
            //echo $cartModel->_sql();exit;
          return $cartinfo;
        }

        /**根据seller_id分组的购物车商品数组
        *@param     $cartIds         arr        购物车id数组
        */
        public function getGoodsList($cartIds){
                $cartModel=M('cart');
                $cartIds=implode(',',$cartIds);   //购物车id
                $where['c.cart_id']=array('in',$cartIds);
                //mysql负责查询
                $cartinfo=$cartModel->alias('c')->field("c.seller_id,u.user_name")
                                                   ->join("LEFT JOIN __USERS__ AS u ON u.uid = c.seller_id")
                                                   ->where($where)->group('c.seller_id')->order('c.cart_id ASC')->select();
                return $cartinfo;
        }

        /**多个卖家的商品生成订单
         * @param $oneOrder_sn              array   主订单号
         * @param $data                     array  用户插入数据
         * @param $groupByOrderArr          array   seller_id分组数据
         * @param $address_id               int   收货地址id
         * @param $delivery_way             int   配送方式
         * @param $user_id                  int   买家用户id
         * @param $cart_way_info            array   用户选择的快递方式
         * @return arr
         */
         public function createUserOrder($oneOrder_sn,$data,$groupByOrderArr,$address_id,$delivery_way,$user_id,$cart_way_info){
           $orderModule = M('Order');
           //添加主订单信息
           $a1=$orderModule->data($data)->add();
           //添加子订单信息
           //dump($groupByOrderArr);exit;
           foreach ($groupByOrderArr as $key => $v) {
              //生成子订单 订单号
              $Asn = StrOrderOne(); //子订单的parent_sn
              //验证订单号是否存在
              if (M('order')->where("order_sn=$Asn")->count() != 0) {
                  $Asn = StrOrderOne(); //再生成一次订单号
              }
               //子订单的运送方式和运费
               foreach ($cart_way_info as $k => $vt) {
                   if ( $vt['seller_id'] == $v['seller_id']) {
                         $data4[]= array(
                             'order_sn' => $Asn,
                             'type' => $vt['id'],
                             'pay_money' => $vt['price']
                         );
                         $freight=$vt['price'];  //这个seller_id的子订单的运费
                   }
               }
             //子订单order表数据
             $data2[]= array(
                'parent_order_sn' => $oneOrder_sn ,
                'order_sn' => $Asn ,
                'goods_title' => $v['goods_title'] ,
                'money'  => $v['moneycount']+$freight,
                'seller_id'=>$v['seller_id'],
                'counts' => $v['counts'],
                'address_id' => $address_id,
                'delivery_way' => $delivery_way,
                'status' => '0',
                'original_price' => $v['moneycount']+$freight ,
                'add_time' => time(),
                'pay_type' => '1',
                'uid'   =>   $user_id,
                'is_son' => '1'
              );
              //子订单order_goods数据
                $oneGoods_id=explode(',',$v['gid']);
                $oneCounts=explode(',',$v['gcounts']);
                $real_goods_id=explode(',',$v['real_goods_id']);
                foreach ($oneGoods_id as $key => $vbb) {
                    $rs[$key]['goods_id']=$vbb;
                }
                foreach ($oneCounts as $key => $vii) {
                    $rs[$key]['counts']=$vii;
                }
                foreach ($real_goods_id as $key => $vic) {
                    $rs[$key]['real_goods_id']=$vic;
                }
                foreach ($rs as $key => $cc) {
                    $data3[] = array(
                      'order_sn' => $Asn ,
                      'goods_title' => $v['goods_title'] ,
                      'goods_thumb' => $v['goods_thumb'] ,
                      'goods_price' => $v['goods_price'] ,
                      'goods_id' => $cc['goods_id'] ,
                      'real_goods_id' => $cc['real_goods_id'] ,
                      'store_id' => $v['store_id'],
                      'counts' => $cc['counts'],
                    );
                }
                unset($rs);
              }
           $a2=$orderModule->addAll($data2);
           $a3=M('OrderGoods')->addAll($data3);
           $a4=M('order_logistics')->addAll($data4);
           if ($a1 && $a2 && $a3 ) {
             return true;
           }
         }

         /**不需要拆单的生成订单
          * @Param            array             $orderGoodsArr                商品信息
          * @Param            string            $orderSn                      订单编号
          * @Param            array             $data                         订单主表信息
          * @Param            array             $logisticsArr                 同一卖家商品物流及付款信息,与$delivery_way配合使用
          * @Param            int               $isDelivery                   是否快递
          * Return            bool
          * */
         public function createUserOneOrder($orderGoodsArr,$orderSn,$data,$logisticsArr,$isDelivery){
            $orderModule = M('Order');
            //添加主订单信息
            $res3=$orderModule->data($data)->add();
            //添加订单对应goods_id信息
            foreach ($orderGoodsArr as $key => $v) {
                $data3[] = array(
                    'order_sn' => $orderSn ,
                    'goods_id' => $v['goods_id'] ,
                    'real_goods_id' => $v['real_goods_id'] ,
                    'goods_title' => $v['goods_title'] ,
                    'goods_thumb' => $v['goods_thumb'] ,
                    'goods_price' => $v['goods_price'] ,
                    'store_id' => $v['store_id'],
                    'is_used_goods' => array_key_exists('is_used_goods',$v)?$v['is_used_goods']:0,
                    'counts' => $v['counts'],
                );
            }
            if ($isDelivery == 1) {         //需要快递,添加快递运费信息
                foreach ($logisticsArr as $key => $vs){
                     $data4[] = array(
                       'order_sn' => $orderSn ,
                       'pay_money' => $vs['price'] ,
                       'type' => $vs['id'] ,
                     );
                }
            }
            $res1 = M('order_goods')->addAll($data3);
            $res2 = M('order_logistics')->addAll($data4);
            if($res1&&$res3){
              if ($isDelivery == 0) {
                  return true;
              }else {
                  if ($res2) {
                      return true;
                  }else {
                      return false;
                  }
              }

            }else{
                return false;
            }
         }


         /*减去商品库存
         *@parm  $cart_info   arr   订单数组
         *@parm  $deliveryWay   int   配送方式
         */
         public function reduceGoodsNum($cartInfo,$deliveryWay){
           $sellerDeliveryModel=M('seller_delivery');
           //根据配送方式分线上线下分别减去库存
           if ($deliveryWay == 0) {
               //到店购买
               foreach ($cartInfo as $key => $v) {
                   $goods_id=$v['goods_id'];
                   $result=$sellerDeliveryModel->field('id,stock')->where("id=$goods_id")->find();
                   $data['stock']=$result['stock']-$v['counts'];
                   if ($data['stock'] == 0) {
                       //执行下架操作
                       $data['is_onsale']='0';
                   }
                   $res=$sellerDeliveryModel->where("id=$goods_id")->save($data);
               }
           }else {
               //快递配送减去goods表的库存
               //dump($cartInfo);exit;
               foreach ($cartInfo as $key => $v) {
                   $num=$v['counts'];   //购买的数量
                   $sgId = $v['real_goods_id'];  //goods表的id
                   $res=M('goods')->where("id=$sgId")->setDec('online_stock',$num);  //减去goods表的库存
               }
           }
           if ($res) {
              return true;
           }else {
              return false;
           }
         }


        //清空购物车
        public function clearCart($cartIds){
            $cartIds=implode(',',$cartIds);
            //var_dump($cartIds);exit;
            $where['cart_id']=array('in',$cartIds);
            $res = M('cart')->where($where)->delete();
            return $res;
        }
      /**得带页码的店铺订单列表
       * @param $pageNum            int              页码
       * @param $order              string           排序
       * @param $pageType           int              列表类型,1待发货,2待完成,3已完成,0代付款
       * @param $condition          array            查询条件
       * @return mixed              array            包括分页信息和查询结果数组
       */
      public function getSellerOrderPageList($pageNum = 1,$order = 'id DESC',$pageType,$condition){
          $orderModel = D('Common/Order');
          $sql = $orderModel->alias('o')->field("o.*,s.sid,group_concat(g.id) as g_id,group_concat(og.goods_title) as goods_title,group_concat(og.goods_thumb) as goods_thumb,ol.logistics_sn,ol.type,a.consignee,a.mobile,a.address")
                              ->join("LEFT JOIN __ORDER_GOODS__ AS og ON o.order_sn=og.order_sn")
                              ->join("LEFT JOIN __STORE__ AS s ON og.store_id=s.sid")
                              ->join("LEFT JOIN __GOODS__ AS g ON og.goods_id=g.id")
                              ->join("LEFT JOIN __ORDER_LOGISTICS__ AS ol ON ol.order_sn=o.order_sn")
                              ->join("LEFT JOIN __USERS_ADDRESS__ AS a ON a.address_id=o.address_id")
                              ->group('o.order_sn')
                              ->where($condition)->buildSql();
          $count=$orderModel->table($sql.' a')->count();
          $result['count']=$count;
          $page = new Page($count,C('ORDER_PAGE_SIZE'),$condition);
          $result['pageCount']=$page->pageCount();
          $result['show'] = $page->show();
          $result['list'] = $orderModel->alias('o')->field("o.*,s.sid,group_concat(g.id) as g_id,group_concat(og.goods_title) as goods_title,group_concat(og.goods_thumb) as goods_thumb,ol.logistics_sn,ol.type,a.consignee,a.mobile,a.address")
                                      ->join("LEFT JOIN __ORDER_GOODS__ AS og ON o.order_sn=og.order_sn")
                                      ->join("LEFT JOIN __STORE__ AS s ON og.store_id=s.sid")
                                      ->join("LEFT JOIN __GOODS__ AS g ON og.goods_id=g.id")
                                      ->join("LEFT JOIN __ORDER_LOGISTICS__ AS ol ON ol.order_sn=o.order_sn")
                                      ->join("LEFT JOIN __USERS_ADDRESS__ AS a ON a.address_id=o.address_id")
                                      ->group('o.order_sn')
                                      ->where($condition)->order($order)->page($pageNum,C('ORDER_PAGE_SIZE'))->select();
                                      //var_dump($result['list']);

          return $result;
      }
      /**订单发货
       * @param $data           array              发货信息（订单号,物流公司,快递号）
       * @return bool
       */
      public function deliverGoods($data){
          $user_id=session('mobile.id');
          //验证订单当前状态
          $orderModel=D('Common/Order');
          $status=$orderModel->alias('o')->where(array('seller_id'=>$user_id,'order_sn'=>$data['order_sn']))->getField('status');
          if ($status==1){
              $data1['logistics_sn']=$data['logistics_sn'];
              if ($data['id']) {
                  $data1['id']=$data['id'];
              }
              $re=M('order_logistics')->where(array('order_sn'=>$data['order_sn']))->save($data1);
              //变成订单发货状态
              $save['status']=2;
              $save['ship_time']=time();
              $re1=$orderModel->where(array('order_sn'=>$data['order_sn']))->save($save);
              if ($re && $re1!==false){
                  return true;
              }else{
                  return false;
              }
          }else{
              return false;
          }
      }

      //订单结算
      public function orderSettlement($map,$order = 'add_time DESC,id DESC'){
          $orderModel = D('Common/Order');
          $res=$orderModel->alias('o')->field('o.*,c.goods_title,g.goods_id,c.store_id,s.store_name,group_concat(c.goods_thumb) as tb')
          ->join("LEFT JOIN __ORDER__ AS mo ON mo.parent_order_sn = o.order_sn")
          ->join("LEFT JOIN __ORDER_GOODS__ AS g ON (o.order_sn = g.order_sn)or(mo.order_sn = g.order_sn)")
          ->join("LEFT JOIN __GOODS__ AS c ON c.id = g.goods_id")
          ->join("LEFT JOIN __STORE__ AS s ON s.sid = c.store_id")
          ->join("LEFT JOIN __order_SETTLEMENT__ AS mos ON mos.order_sn = o.order_sn")
          ->where($map)->group('o.order_sn')->order($order)->select();
          return $res;
      }

      /*结算分成添加数据
      *@parm     $map    查询条件
      *@parm     $order_sn    订单号
      */
      public function addSettlementMoney($map,$order_sn){
          //更新店铺提成money
          $orderModule=D('Common/Order');
          $param=C('users');
          $seller_percent = $param['seller_percent']; //卖家商品卖完后最后获取得商品最终卖出的总金额的比例
          $store_percent  = $param['store_percent']; //商家商品卖完后获得订单总金额的比例
          $res=$orderModule->alias('o')->field('o.order_sn,s.owner,o.seller_id,u.mobile as umobile,s.mobile as smobile,o.status,o.is_son,g.store_id,o.original_price,o.order_sn,sum(c.price * g.counts) as user_money')
          ->join("LEFT JOIN __ORDER_GOODS__ AS g ON o.order_sn = g.order_sn")
          ->join("LEFT JOIN __SELLER_DELIVERY__ AS c ON c.id = g.goods_id")
          ->join("LEFT JOIN __STORE__ AS s ON s.sid = g.store_id")
          ->join("LEFT JOIN __USERS__ AS u ON o.seller_id = u.uid")
          ->where($map)->group('g.store_id')->order($order)->select();
          if ($res['0']['status'] != 3) {
              $returnUrl = $_SERVER['HTTP_REFERER'];
              echo '订单状态不对!2s 后跳转';header("Refresh:2,Url=$returnUrl");
          }
          //添加卖家的分成信息
          $data = array(
            'order_sn'   => $order_sn,
            'user_money'=> $seller_percent*$res['0']['original_price'],
            'user_id'   => $res['0']['seller_id']
          );
          $addData=M('order_user_money')->add($data);
          //添加店铺的分成信息
          foreach ($res as $key => $v) {
            $data1[] = array(
              'order_sn'   => $order_sn,
              'store_money'=> $store_percent*$v['user_money'],
              'store_id'   => $v['store_id']
            );
            unset($user_money);
            //给店铺发送短信
            $smobile=$v['smobile'];
            $a=send_sms_message('settlement',$smobile,'',array());
          }
          //添加用户分成money
          $addData1=M('order_store_money')->addAll($data1);
          //更新订单状态
          $data2 = array('status'=>'6','settlement_time'=>time());
          $updateData=$orderModule->where("order_sn=$order_sn")->setField($data2);
          unset($data2);
          if ($addData && $addData1 && $updateData) {
            //给卖家发送短信
            $b=send_sms_message('s_settlement',$res['0']['umobile'],'',array($res['0']['owner']));
            return true;
          }
      }





      //结算统计函数  用户
        public function countSettlement1($pageNum,$map,$order = 'o.add_time DESC,o.id DESC'){
            $page_size=C('SETTLEMENT_PAGE_SIZE');
            $param=C('users');
            $seller_percent = $param['seller_percent']; //卖家商品卖完后最后获取得商品最终卖出的总金额的比例
            $store_percent  = $param['store_percent']; //商家商品卖完后获得订单总金额的比例
            $OrderModel=M('Order');
            //统计数量
            $counts=$OrderModel->alias('o')->field('mog.counts,g.goods_title,g.want_price,g.goods_thumb,o.order_sn,o.original_price')
                                  ->join("LEFT JOIN __ORDER_GOODS__ AS mog ON mog.order_sn = o.order_sn")
                                  ->join("LEFT JOIN __GOODS__ AS g ON g.id = mog.goods_id")
                                  ->where($map)->count();
                            $res['pageCount']=ceil($counts/$page_size);
                            $res['counts']=$counts;
            //得到用户的所有卖出的商品
            $res['list']=$OrderModel->alias('o')->field('mog.counts,g.id,g.goods_title,g.want_price,g.goods_thumb,o.order_sn,o.original_price')
                                  ->join("LEFT JOIN __ORDER_GOODS__ AS mog ON mog.order_sn = o.order_sn")
                                  ->join("LEFT JOIN __GOODS__ AS g ON g.id = mog.goods_id")
                                  ->where($map)->page("$pageNum,$page_size")->order($order)->select();
                //得到商品价格,商品名,商品图
                foreach ($res['list'] as $key => $v) {
                    $res['list'][$key]['user_money']=$v['want_price']*$seller_percent*$v['counts'];
                    $res['allGetMoney']=$res['list'][$key]['user_money']+$res['allGetMoney'];
                }
                //var_dump($res);
            return $res;
        }




      /*结算页面统计      mobile店铺
      *@parm $pageNum 页码 int
      *@parm $map 查询条件 arr
      *@parm $user_id  用户id int
      *@parm $action 当前页面参数 ing/ed
      *@
      */
     public function mobileStoreSettlement($pageNum = 1,$map,$store_id,$order = 'o.add_time DESC,o.id DESC'){
         $orderModel = M('Order');
         $store_percent  =C('users.store_percent');  //商家商品卖完后获得订单总金额的比例
         $page_size=C('SETTLEMENT_PAGE_SIZE');
         $counts=$orderModel->alias('o')->field("o.order_sn,mog.goods_title,mog.goods_thumb,status,o.complete_time,sd.price*$store_percent as store_money")
                                 ->join("LEFT JOIN __ORDER_GOODS__ AS mog ON mog.order_sn = o.order_sn")
                                 ->join("LEFT JOIN __SELLER_DELIVERY__ AS sd ON mog.goods_id = sd.id")
                                 ->where($map)->count();
         $pageCount=ceil($counts/$page_size);
         $res['counts'] = $counts; //查询出的总数量个数
         $res['pageCount']=$pageCount;
         //按每个商品写订单结算
        $res['list']=$orderModel->alias('o')->field("o.order_sn,mog.goods_title,mog.goods_thumb,status,o.complete_time,sd.price,sd.price*mog.counts*$store_percent as store_money")
                                ->join("LEFT JOIN __ORDER_GOODS__ AS mog ON mog.order_sn = o.order_sn")
                                ->join("LEFT JOIN __SELLER_DELIVERY__ AS sd ON mog.goods_id = sd.id")
                                ->where($map)->page("$pageNum,$page_size")->order($order)->select();
                                //var_dump($res['list']);exit;
           //店铺总收入
           $res['allGetMoney'] = 0;
           foreach ($res['list'] as $key => $v) {
               $res['allGetMoney'] = $res['allMoney']+$v['store_money'];
           }
           //店铺头像
           $res['logo'] = S('logo'.$store_id);
           if (!$res['logo']) {
               $res['logo']=M('store')->where("sid=$store_id")->getField('logo');//店铺头像
               S('logo'.$store_id,$res['logo'],600);
           }
                     //var_dump($res['allMoney']);exit;
        return $res;
     }

     //pc端后台店铺结算管理
     public function PCStoreSettlement($pageNum = 1,$map,$pageCondition,$store_id,$order = 'o.add_time DESC,o.id DESC'){
       $orderModel=M('Order');
       $param=C('users');
       $store_percent  = $param['store_percent']; //商家商品卖完后获得订单总金额的比例
        $page_size=C('SETTLEMENT_PAGE_SIZE');
                  $counts=$orderModel->alias('o')->field("o.order_sn,mog.goods_title,mog.goods_thumb,status,o.complete_time,sd.price,sd.price*mog.counts*$store_percent as store_money")
                                          ->join("LEFT JOIN __ORDER_GOODS__ AS mog ON mog.order_sn = o.order_sn")
                                          ->join("LEFT JOIN __SELLER_DELIVERY__ AS sd ON mog.goods_id = sd.id")
                                          ->where($map)->count();
       $Page = new \Think\Page($count,$page_size,$pageCondition);// 实例化分页类 传入总记录数和每页显示的记录数
       $Page->setConfig('prev','上一页');
       $Page->setConfig('next','下一页');
       $res['page'] = $Page->show();// 分页显示输出
       $res['list']=$orderModel->alias('o')->field("o.order_sn,mog.goods_title,mog.goods_thumb,status,o.complete_time,sd.price,sd.price*mog.counts*$store_percent as store_money")
                               ->join("LEFT JOIN __ORDER_GOODS__ AS mog ON mog.order_sn = o.order_sn")
                               ->join("LEFT JOIN __SELLER_DELIVERY__ AS sd ON mog.goods_id = sd.id")
                               ->where($map)->page("$pageNum,$page_size")->order($order)->select();
                //店铺总收入
                $res['allGetMoney']=0;
                $map1['o.status']=6;
                $map1['mog.store_id']=$store_id;
                $res['allGetMoney']=M('order_goods')->alias('mog')
                ->join("LEFT JOIN __ORDER__ AS o ON mog.order_sn = o.order_sn")
                ->join("LEFT JOIN __SELLER_DELIVERY__ AS sd ON mog.goods_id = sd.id")->where($map1)->sum("sd.price*mog.counts");
                $res['allGetMoney']=$res['allGetMoney']*$store_percent;
                          //var_dump($res);exit;
       return $res;
     }

       //昨日进账与今日进账
       public function dayCounts($store_id){
          $store_percent  = C('users.store_percent'); //商家商品卖完后获得订单总金额的比例
          $yesterday=date("Y-m-d", (time()-86400)) ;  //昨天日期
          $yesterday=strtotime($yesterday);
          $now=date("Y-m-d", time()) ;  //今天日期
          $now=strtotime($now);
          $map['o.status']=6;
          $map['mog.store_id']=$store_id;
          $map['o.settlement_time']   = array('between',array($yesterday,$yesterday+86400));
          $map1['o.status']=6;
          $map1['mog.store_id']=$store_id;
          $map1['o.settlement_time']  = array('between',array($now,$now+86400));
          $orderModel=M('Order');
          //昨日进账
          $rs['yesNum']=M('order_goods')->alias('mog')
          ->join("LEFT JOIN __ORDER__ AS o ON mog.order_sn = o.order_sn")
          ->join("LEFT JOIN __SELLER_DELIVERY__ AS sd ON mog.goods_id = sd.id")->where($map)->count();
          $rs['yesterday']=M('order_goods')->alias('mog')
          ->join("LEFT JOIN __ORDER__ AS o ON mog.order_sn = o.order_sn")
          ->join("LEFT JOIN __SELLER_DELIVERY__ AS sd ON mog.goods_id = sd.id")->where($map)->sum("sd.price*mog.counts");
          $rs['yesterday']=$rs['yesterday']*$store_percent;
          //今日进账
          $rs['nowNum'] =  M('order_goods')->alias('mog')
          ->join("LEFT JOIN __ORDER__ AS o ON mog.order_sn = o.order_sn")
          ->join("LEFT JOIN __SELLER_DELIVERY__ AS sd ON mog.goods_id = sd.id")->where($map1)->count();
          $rs['now']    =   M('order_goods')->alias('mog')
          ->join("LEFT JOIN __ORDER__ AS o ON mog.order_sn = o.order_sn")
          ->join("LEFT JOIN __SELLER_DELIVERY__ AS sd ON mog.goods_id = sd.id")->where($map1)->sum("sd.price*mog.counts");
          $rs['now']=$rs['now']*$store_percent;
          return $rs;

       }

       /**得带页码的店铺订单列表
        * @param $pageNum            int              页码
        * @param $order              string           排序
        * @param $pageType           int              列表类型,1待发货,2待完成,3已完成,0代付款
        * @param $condition          array            查询条件
        * @return mixed              array            包括分页信息和查询结果数组
        */
       public function getStoreOrderPageList($pageNum = 1,$order = 'id DESC',$pageType,$condition){
           import('ORG.Util.Page');
           $orderModel = D('Common/Order');
           $condition['o.status']=$pageType;
           $condition['og.store_id']=session('mobile.store_id')?session('mobile.store_id'):session('store.store_id');
           $sql = $orderModel->alias('o')->field("o.*,s.sid,group_concat(g.id) as g_id,group_concat(og.goods_title) as goods_title,group_concat(og.goods_thumb) as goods_thumb,ol.logistics_sn,ol.type,a.consignee,a.mobile,a.address")
                               ->join("LEFT JOIN __ORDER_GOODS__ AS og ON o.order_sn=og.order_sn")
                               ->join("LEFT JOIN __STORE__ AS s ON og.store_id=s.sid")
                               ->join("LEFT JOIN __GOODS__ AS g ON og.goods_id=g.id")
                               ->join("LEFT JOIN __ORDER_LOGISTICS__ AS ol ON ol.order_sn=o.order_sn")
                               ->join("LEFT JOIN __USERS_ADDRESS__ AS a ON a.address_id=o.address_id")
                               ->group('o.order_sn')
                               ->where($condition)->buildSql();
           $count=$orderModel->table($sql.' a')->count();
           $result['count']=$count;
           $page = new Page($count,C('ORDER_PAGE_SIZE'),$condition);
           $result['pageCount']=$page->pageCount();
           $result['show'] = $page->show();
           $result['list'] = $orderModel->alias('o')->field("o.*,s.sid,group_concat(g.id) as g_id,group_concat(og.goods_title) as goods_title,group_concat(og.goods_thumb) as goods_thumb,ol.logistics_sn,ol.type,a.consignee,a.mobile,a.address")
                                       ->join("LEFT JOIN __ORDER_GOODS__ AS og ON o.order_sn=og.order_sn")
                                       ->join("LEFT JOIN __STORE__ AS s ON og.store_id=s.sid")
                                       ->join("LEFT JOIN __GOODS__ AS g ON og.goods_id=g.id")
                                       ->join("LEFT JOIN __ORDER_LOGISTICS__ AS ol ON ol.order_sn=o.order_sn")
                                       ->join("LEFT JOIN __USERS_ADDRESS__ AS a ON a.address_id=o.address_id")
                                       ->group('o.order_sn')
                                       ->where($condition)->order($order)->page($pageNum,C('ORDER_PAGE_SIZE'))->select();

           return $result;
       }


       /*pc端后台卖家结算管理
       *@parm $pageNum  int  当前页码
       *@parm $map     arr  查询条件
       *@parm $pageCondition   arr 分页参数
       *@parm $seller_id   int 卖家id
       *@parm $order        str   排序条件
       */
       public function sellerSettlement($pageNum = 1,$map,$pageCondition,$seller_id,$order = 'add_time DESC,id DESC'){
         $orderModel=M('Order');
         $param=C('users');
         $seller_percent  = $param['seller_percent']; //商家商品卖完后获得订单总金额的比例
          $page_size=C('SETTLEMENT_PAGE_SIZE');
                    $counts=$orderModel->where($map)->order($order)->count();
         $Page = new \Think\Page($count,$page_size,$pageCondition);// 实例化分页类 传入总记录数和每页显示的记录数
         $Page->setConfig('prev','上一页');
         $Page->setConfig('next','下一页');
         $res['page'] = $Page->show();// 分页显示输出
         $res['list']=$orderModel->field("order_sn,original_price,original_price * $seller_percent as store_money,status,complete_time")
         ->where($map)->page("$pageNum,$page_size")->order($order)->select();
                  //店铺总收入
                  $res['allGetMoney']=0;
                  $map1['status']=6;
                  $map1['seller_id']=$seller_id;
                  $res['allGetMoney']=$orderModel->where($map1)->sum('original_price');
                  $res['allGetMoney']=$res['allGetMoney']*$seller_percent;
                            //var_dump($res);exit;
         return $res;
       }


       /*昨日进账与今日进账
       *@parm     $userId  int  卖家Id
       *@parm     return   arr
       */
       public function daySettlementCounts($userId){
          $seller_percent  = C('users.seller_percent'); //商家商品卖完后获得订单总金额的比例
          $yesterday=date("Y-m-d", (time()-86400)) ;  //昨天日期
          $yesterday=strtotime($yesterday);
          $now=date("Y-m-d", time()) ;  //今天日期
          $now=strtotime($now);
          $map['status']=6;
          $map['seller_id']=$userId;
          $map['settlement_time']   = array('between',array($yesterday,$yesterday+86400));
          $map1['status']=6;
          $map1['seller_id']=$userId;
          $map1['settlement_time']  = array('between',array($now,$now+86400));
          $orderModel=M('Order');
          //昨日进账
          $rs['yesNum']=$orderModel->where($map)->count();
          $rs['yesterday']=$orderModel->where($map)->sum('original_price');
          $rs['yesterday']=$rs['yesterday']*$seller_percent;
          //今日进账
          $rs['nowNum'] =  $orderModel->where($map1)->count();
          $rs['now']    =   $orderModel->where($map1)->sum('original_price');
          $rs['now']=$rs['now']*$seller_percent;
          return $rs;

       }


     //pc端公司结算管理
     public function PCSettlement($pageNum = 1,$condition,$pageCondition,$order = 'add_time DESC,id DESC'){
          $orderModel=M('Order');
          $param=C('users');
          $seller_percent = $param['seller_percent']; //卖家商品卖完后最后获取得商品最终卖出的总金额的比例
          $store_percent  = $param['store_percent']; //商家商品卖完后获得订单总金额的比例
            $page_size=C('SETTLEMENT_PAGE_SIZE');
          $count = $orderModel->field('order_sn,original_price,status')->where($condition)->count();    //已结算
          $Page = new \Think\Page($count,$page_size,$pageCondition);// 实例化分页类 传入总记录数和每页显示的记录数
          $Page->setConfig('prev','上一页');
          $Page->setConfig('next','下一页');
          $res['page'] = $Page->show();// 分页显示输出
          $res['list'] = $orderModel->field('order_sn,original_price,status,complete_time,settlement_time')->where($condition)->page("$pageNum,$page_size")->order($order)->select();
              foreach ($res['list'] as $key => $v) {
                  $res['list'][$key]['user_money']=$v['original_price']*$seller_percent;
                  $res['list'][$key]['store_money']=$v['original_price']*$store_percent;
              }
          return $res;
     }


     /*订单定时任务sql rollback
     *@param             $cron_time           int           未支付订单超时时间
     *@param             $action                str           判断手动与定时任务的参数
     *@param             $order_sn           int           订单号
     *@param             return                 mix
     */
     public function rollOrder($cron_time = '84600',$action,$order_sn){
          $orderModel=M('Order');
          if ($action == 'myCancel') {
            //用户手动取消订单
            $order=$orderModel->alias('o')->field("o.id,o.order_sn,o.is_son,coupon_id,g.real_goods_id,g.is_used_goods,o.delivery_way,o.uid,share_bill,group_concat(g.goods_id) as goods_id,group_concat(g.counts) as counts")
            ->join("LEFT JOIN __ORDER_GOODS__ AS g ON g.order_sn = o.order_sn")
            ->group('o.order_sn')->where("o.order_sn=$order_sn or o.parent_order_sn=$order_sn")->select();
          }else {
              //定时任务取消订单
            $map['o.status']=0;
            $time=time();
            //查询出所有未付款超时订单
            $subQuery=$orderModel->alias('o')->field("o.id,o.order_sn,o.delivery_way,o.is_son,coupon_id,g.real_goods_id,g.is_used_goods,o.uid,($time - o.add_time) as time,share_bill,group_concat(g.goods_id) as goods_id,group_concat(g.counts) as counts")
            ->join("LEFT JOIN __ORDER_GOODS__ AS g ON g.order_sn = o.order_sn")
            ->group('o.order_sn')->where($map)->buildSql();
            $condition['a.time'] = array('gt',$cron_time);
            $order=$orderModel->table($subQuery.' a')->where($condition)->order('a.time DESC')->select();
          }
          $orderModel->startTrans();//开启事务
          foreach ($order as $key => $v) {
                    //执行取消订单操作
                    $id=$v['id'];  //这个订单的id主键
                    $rs1 = $orderModel->where("id=$id")->setField('status','5');
                    unset($id);
                    //分享币回滚
                    $share_bill=$v['share_bill'];
                    if ( $share_bill > 0 ) {
                        $data['user_id'] = $v['uid'];
                        $data['user_money'] = $share_bill;
                        $rs2 = D('Common/VirtualCashLog')->add($data);
                        unset($share_bill);
                    }else {
                        $rs2 = true;
                    }
                    //优惠劵回滚
                    if ($v['coupon_id'] != 0 ) {
                        $coupon_id=$v['coupon_id'];
                        $rs3 = M('coupon')->where("coupon_id=$coupon_id")->setField('used_time','0');
                        unset($coupon_id);
                    }else {
                        $rs3 = true;
                    }
                    if ($v['is_son'] !=0) {
                            //更新商品数量
                            $oneGoods_id=explode(',',$v['goods_id']);
                            $oneCounts=explode(',',$v['counts']);
                            foreach ($oneGoods_id as $key => $vbb) {
                                $rs[$key]['goods_id']=$vbb;
                            }
                            foreach (explode(',',$v['real_goods_id']) as $key => $vbc) {
                                $rs[$key]['real_goods_id']=$vbc;
                            }
                            foreach ($oneCounts as $key => $vii) {
                                $rs[$key]['counts']=$vii;
                            }
                            //var_dump($rs); //$rs是这个订单的seller_delivery表的id和数量的数组
                            //一手商品的库存回滚
                            if ($v['is_used_goods'] == 0) {
                                //快递配送
                                if ($v['delivery_way'] == 1) {
                                    $goodsModel=M('goods');
                                    foreach ($rs as $key => $vs) {
                                        $goods_id=$vs['real_goods_id'];            //goods表的id
                                        //var_dump($goods_id);exit;
                                        $num = $vs['counts'];   //商品数量
                                        $rs4=$goodsModel->where("id=$goods_id")->setInc('online_stock',$num);          //更新good表在线库存
                                        unset($num);
                                        unset($goods_id);
                                        unset($id);
                                    }
                                }else {
                                    //到店购买更新seller_delivery的库存
                                    foreach ($rs as $key => $vs) {
                                        $goods_id=$vs['goods_id'];  //seller_delivery表的id
                                        $id=M("seller_delivery")->where("id=$goods_id")->setInc('stock',$vs['counts']);                  //更新seller_delivery表在线库存
                                        unset($goods_id);
                                    }
                                }
                                unset($rs);
                        }else {
                            //二手商品的库存回滚
                                $sHGoodsModel=M('sHGoods');
                                $rs4=$sHGoodsModel->where("id=$goods_id")->setField('goods_num','1');
                        }
                    }else {
                        $rs4=true;
                    }
          }
          //var_dump($rs1);var_dump($rs2);var_dump($rs3);var_dump($rs4);exit;
          if ($rs1!==false && $rs2!==false && $rs3!==false && $rs4 !==false) {
              $orderModel->commit();
              return true;
          }else {
              $orderModel->rollback();
          }
     }

     /*获取商家卖出的所有商品
     *@param             $cron_time           int           未支付订单超时时间
     *@param             $action                str           判断手动与定时任务的参数
     *@param             $order_sn           int           订单号
     *@param             return                 mix
     */
     public function getSellerSoldGoods($pageNum = '1',$order = 'complete_time DESC'){
         $result=M('order_goods')->alias('og')->field('og.*,sd.price as goods_price,sd.sg_id as id')
         ->join("LEFT JOIN __ORDER__ AS o ON og.order_sn = o.order_sn")
         ->join("LEFT JOIN __SELLER_DELIVERY__ AS sd ON sd.id = og.goods_id")
         ->where(array('o.seller_id'=>session('mobile.id'),'o.status'=>3))->limit(($pageNum-1)*C('GOODS_PAGE_SIZE'),C('GOODS_PAGE_SIZE'))->order($order)->select();
         return $result;
     }



}
