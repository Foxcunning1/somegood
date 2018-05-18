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
            $condition['g.user_id'] = $user_id;
        }else{
            $user_id=0;
            $phpsessid=cookie('equipment_id');
            if (empty($phpsessid)) {
                $phpsessid = $_COOKIE['PHPSESSID'];
                cookie('equipment_id',$phpsessid,360000000); // 指定cookie保存时间
            }
            $condition['phpsessid'] = $phpsessid;
            $condition['g.user_id'] = 0;
        }
        $cart = D('Common/Cart');
        //此处要判断是否登录,在点击结算时候判断是否登录,登录之后更新cart表的user_id
        $counts = $cart->alias('g')->where($condition)->count();
        $result = $cart->getOneCart($condition);
        $shop = $cart->alias('g')->field('g.*,s.store_name as s_name,s.address')
                   ->join("LEFT JOIN __STORE__ AS s ON g.store_id = s.sid")
                   ->where($condition)->group('store_id')->select();
                        //var_dump($result);exit;
        $returnUrl=substr($_SERVER['HTTP_REFERER'],-10); ;
        //var_dump($returnUrl);
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
        $goods_id = I('post.goods_id');
        $goods_num = I('goods_num');
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
        $goodsInfo = $goodsModel->getGoodsPartInfo($goods_id);
        if($goodsInfo['goods_num']==0){
            $this->ajaxInfoReturn("","商品已售罄！",0);
            die;
        }
        if($goods_num > $goodsInfo['goods_num']){
            $this->ajaxInfoReturn("","商品库存不足！",0);
            die;
        }
        //验证商品数量
        $data['user_id'] = $user_id;
        $data['goods_id'] = $goods_id;
        $data['store_id'] = $goodsInfo['store_id'];
        $data['store_name'] = $goodsInfo['store_name'];
        $data['phpsessid'] = $phpsessid;
        $data['goods_title'] = $goodsInfo['goods_title'];
        $data['goods_img'] = $goodsInfo['goods_thumb'];
        $data['add_time'] = $add_time;
        $cart = D('Common/Cart');
        //购物车商品数量
        $cartGoodsNum = $cart->getCartgoods($phpsessid,$user_id,$goods_id);
        if ($cartGoodsNum >= $goodsInfo['goods_num']) {
            $this->ajaxInfoReturn("","商品库存不足！",0);
            die();
        }
        if ($cartGoodsNum == 0) {
                //检测是否获取到phpsessid
                if ( $phpsessid === NULL && $user_id == 0) {
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
            $condition['goods_id'] = $goods_id;
            $cartGoodsNum++;
            $data1['counts'] = $cartGoodsNum;
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
        $goods_num=$res['goods_num'];
        if ($counts > $goods_num) {
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

    //测试
    public function testAction(){
        $cartModel=D('Common/cart');
        $cartModel->getGoodsInfoById($cartIds);
    }



}
