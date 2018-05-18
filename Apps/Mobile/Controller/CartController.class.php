<?php
namespace Mobile\Controller;
use \Think\Controller;

/*
*购物车
*/
class CartController extends BaseController {

    //购物车信息
    public function indexAction(){
        //判断是否登录
        $user_id = session("mobile.id");
        $condition = '';
        if ($user_id) {
            $condition['c.user_id'] = $user_id;
        }else{
            $user_id=0;
            $phpsessid=cookie('equipment_id');
            if (empty($phpsessid)) {
                $phpsessid = $_COOKIE['PHPSESSID'];
                cookie('equipment_id',$phpsessid,360000000); // 指定cookie保存时间
            }
            $condition['phpsessid'] = $phpsessid;
            $condition['c.user_id'] = 0;
        }
        $cart = D('Common/Cart');
        //此处要判断是否登录,在点击结算时候判断是否登录,登录之后更新cart表的user_id
        $counts = $cart->alias('c')->where($condition)->count();   //用户购物车商品类数量
        $result = $cart->getOneCart($condition);
        //根据seller_id 分组展示每个商品对应的店铺名
        $shop = $cart->alias('c')->field('c.*,u.user_name,group_concat(c.store_name),us.company_name')
                                 ->join("LEFT JOIN __USERS__ AS u ON u.uid = c.seller_id")
                                 ->join("LEFT JOIN __USERS_SELLER__ AS us ON u.uid = us.user_id")
                                 ->where($condition)->group('c.seller_id')->select();
        $returnUrl=substr($_SERVER['HTTP_REFERER'],-10);
        //dump($result);exit;
        $this->assign('returnUrl',$returnUrl);
        $this->assign('user_id',$user_id);
        $this->assign('shop',$shop);
        $this->assign('counts',$counts);
        $this->assign('info', $result);
        $this->display();
    }

    public function ajaxGetGoodsStatusAction(){
          $goods_id=I('goods_id');
          $cart_id=I('cart_id');
          if (IS_AJAX) {
              $condition['id']=$goods_id;
              $res=M('goods')->field('goods_num,is_on_sale')->where($condition)->find();
              if ($res['goods_num'] == 0 || $res['is_on_sale'] == 0) {
                  $this->ajaxInfoReturn($cart_id,'商品已失效!',0);
              }else {
                  $this->ajaxInfoReturn('','商品未失效!',1);
              }
          }
    }


    //获取购物车商品数量
    public function getCartNumAction(){
        $user_id=session('mobile.id');
        if ($user_id) {
            $condition['user_id'] = $user_id;
        }else{
            $phpsessid = cookie('equipment_id');
            if (!$phpsessid) {
                $phpsessid = $_COOKIE['PHPSESSID'];
                cookie('equipment_id',$phpsessid,360000000);
            }
            $condition['phpsessid'] = $phpsessid;
            $condition['user_id'] = 0;
        }
        $cartNum = M('cart')->where($condition)->sum('counts');
        if ($cartNum == NULL) {
            $cartNum =0;
        }
        $this->ajaxInfoReturn($cartNum,"获取成功！",1);
    }

    /*添加到购物车
    * */
    public function addGoodsAction(){
        $goods_id = I('goods_id');
        $shop_id = I('shop_id','0');        //seller_delivery的id
        $goods_num = I('goods_num');    //商品加入购物车的数量
        $add_time = time();
        $user_id = session("mobile.id");
        if (!$user_id) {
            $user_id = 0;
        }
        if ($user_id>0) {
            $condition['user_id'] = $user_id;
            $phpsessid = 0;
        }else {
            $phpsessid = cookie('equipment_id');
            if (!$phpsessid) {
                $phpsessid = $_COOKIE['PHPSESSID'];
                cookie('equipment_id',$phpsessid,360000000);
            }
            $condition['phpsessid']=$phpsessid;
        }
        //获取商品信息
        $goodsModel = D("Common/Goods");
        if ($shop_id == 0) {
            $goodsInfo = $goodsModel->field("market_price,price,goods_title,goods_thumb,user_id")->where("id=$goods_id")->find();
        }else {
            $goodsInfo = $goodsModel->getGoodsPartInfo($shop_id);
            //var_dump($goodsInfo);exit;
        }
        //获得这个商品的卖家id
        $seller_id=$goodsModel->where("id=$goods_id")->getField('user_id');
        //验证商品数量
        $data['user_id'] = $user_id;
        $data['goods_id'] = $shop_id?$shop_id:0;
        $data['real_goods_id'] = $goods_id;
        $data['store_id'] = $goodsInfo['store_id']?$goodsInfo['store_id']:0;
        $data['seller_id'] = $seller_id;
        $data['store_name'] = $goodsInfo['store_name']?$goodsInfo['store_name']:'线上购买';
        $data['phpsessid'] = $phpsessid;
        $data['market_price'] = $goodsInfo['market_price'];
        $data['goods_price'] = $goodsInfo['price'];
        $data['counts'] = $goods_num;
        $data['goods_title'] = $goodsInfo['goods_title'];
        $data['goods_thumb'] = $goodsInfo['goods_thumb'];
        $data['add_time'] = $add_time;
        //检测库存
        $cart = D('Common/Cart');
        if ($shop_id !=0) {
            $cartGoodsNum = $cart->getCartgoodsNum($phpsessid,$user_id,$shop_id);       //线下库存 //这个商品在购物车中的数量
            if ($cartGoodsNum['counts'] >= $goodsInfo['stock'] && $cartGoodsNum['counts'] >=$goodsInfo['online_stock'] ) {
                $this->ajaxInfoReturn("","商品库存不足！",0);
            }
        }else {
            $condition['real_goods_id'] = $goods_id;
            $condition['store_id'] = 0;
            $cartGoodsNum=$cart->where($condition)->getField('counts');     //这个商品在购物车中的数量
            if ($cartGoodsNum ==NULL)
                    $cartGoodsNum = 0;
            $online_stock=M('goods')->where("id=$goods_id")->getField('online_stock');      //线上库存
            if ($cartGoodsNum >=$online_stock) {
                $this->ajaxInfoReturn("","商品库存不足！",0);
            }
            //var_dump($cartGoodsNum);exit;
        }
        //判断是购物车是否已经有商品记录
        if (($cartGoodsNum['counts'] == 0  && $goodsInfo['store_id'] != $cartGoodsNum['store_id']) ||$cartGoodsNum == 0 ) {
            //购物车没有记录
                //检测是否获取到phpsessid
                if ( $phpsessid == NULL && $user_id == 0) {
                    $this->ajaxInfoReturn("","获取phpsessid失败!",0);
                }
                    $result= $cart->add($data);//添加至购物车
            if ($result) {
                //加入购物车成功
                $this->ajaxInfoReturn("","加入成功！",1);
            }else {
                //加入购物车失败
                $this->ajaxInfoReturn("","加入失败！",0);
            }
        }else {
            $condition['goods_id'] = $shop_id;
            if ($shop_id != 0) {
                $cartGoodsNum['counts']++;
                $data1['counts'] = $cartGoodsNum['counts'];
            }else {
                $cartGoodsNum++;
                $data1['counts'] = $cartGoodsNum;
            }
            $data1['add_time'] = $add_time;
            $result = $cart->where($condition)->save($data1);
            if ($result) {
                //更新购物车商品数量成功
                $this->ajaxInfoReturn("","加入购物车成功！",1);
            }else {
                //更新购物车商品数量失败
                $this->ajaxInfoReturn("","加入购物车失败！",0);
            }
        }
    }

    /*更新购物车产品数量
    * */
    public function ajaxUpdateGoodsNumAction(){
        $cart_id = I('goodsid');
        $counts = I('counts');
        //购物车商品数量
        $cart=D('Cart');
        $res=$cart->getOneGoodsNum($cart_id);
        $goods_num=$res['stock'];
        if ($counts > $goods_num && $res['online_stock']) {
            $this->ajaxInfoReturn("","商品库存不足！",0);
            die();
        }
        $data['counts'] = $counts;
        $result = M('cart')->where("cart_id=$cart_id")->save($data);
        if($result) {
            $this->ajaxInfoReturn("","更新成功！",1);
        }else{
            $this->ajaxInfoReturn("","您的商品不能再少了,亲！",0);
        }
    }
    /*删除购物车产品
    * */
    public function delCartGoodsAction()
    {
        $cart_id = I('cart_id');
        $result = M('cart')->where("cart_id=$cart_id")->delete();
        if ($result) {
            $this->ajaxInfoReturn("","删除成功！",'1');
        }else {
            $this->ajaxInfoReturn("","删除失败！",'0');
        }
    }


    //清空cookie并存取cartIds
    public function cookieNullAction()
    {
        $cartIds=I('cartIds'); //购物车Id数组
        cookie('cartIds',$cartIds);
        //清空cookie
        session('cart_share_bill',null);
        cookie('cart_delivery_way',null);
        session('cart_coupon_id',null);
        cookie('cart_address_id',null);
        session('cart_way_info',null);
        $this->redirect("mobile/UserOrder/order");
    }

    //判断是否商家或店主
    public function isWhoAction(){
        $userId = session('mobile.id');
        if (IS_AJAX) {
            if ($userId) { //已登录
                $isSeller = M('users')->where("uid=$userId")->getField('is_seller');
                $isStore = M('users')->where("uid=$userId")->getField('is_store');
                $dataArr = array(
                    'isSeller' =>$isSeller ,
                    'isStore' =>$isStore  );
                $this->ajaxInfoReturn($dataArr,"成功获取!",1);
            }else {
                $this->ajaxInfoReturn('',"你谁也不是啊!",0);
            }
        }
    }

    //测试
    public function testAction(){
        $cartModel=D('Common/cart');
        $cartModel->getGoodsInfoById($cartIds);
    }






}
