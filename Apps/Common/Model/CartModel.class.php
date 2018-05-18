<?php
//购物车模型
namespace Common\Model;
 use Think\Model;

class CartModel extends Model{


  /**获得购物车的详细信息
   * @param $condition
   * @return mixed
   */
  public function getOneCart($condition)
  {
      $result = $this->alias('c')->field("c.cart_id,c.real_goods_id,sd.stock,sd.sg_id,g.id,g.online_stock,c.goods_id,c.seller_id,c.store_name,c.store_id,sd.is_onsale as is_on_sale,c.goods_title,c.counts,c.goods_thumb,c.store_name as s_name,c.market_price,c.goods_price")
                     ->join("LEFT JOIN __SELLER_DELIVERY__ AS sd ON sd.id = c.goods_id")
                     ->join("LEFT JOIN __GOODS__ AS g ON c.real_goods_id = g.id")
                     ->where($condition)->select();
      return $result;
  }



  /**判断购物车是否存在该商品
   * @param $goods_id
   * @param $phpsessid
   * @param $user_id
   * @return mixed
   */
  public function checkCartgoods($goods_id,$phpsessid,$user_id){
      $cart = M('cart');
      $condition = '';
      if($user_id){
          $condition['user_id'] = $user_id;
      }else{
          $condition['phpsessid'] = $phpsessid;
          $condition['user_id'] = 0;
      }
      $condition['goods_id']=$goods_id;
      $result=$cart->where($condition)->count();
      //echo $cart->_sql();
      return $result;
  }

  //返回用户购物车商品数量
  public function reCartNum(){
    $user_id=session('mobile.id');
    if ($user_id) {
        $condition['user_id'] = $user_id;
    }else{
        $phpsessid = cookie('equipment_id');
        if (empty($phpsessid)) {
            $phpsessid = $_COOKIE['PHPSESSID'];
            cookie('equipment_id',$phpsessid,360000000);
        }
        $condition['phpsessid'] = $phpsessid;
        $condition['user_id'] = 0;
    }
    $cartNum = M('cart')->where($condition)->count();
    return $cartNum;
  }


    //获得用户购物车商品数量
  public function getCartgoodsNum($phpsessid,$user_id,$shop_id){
      $cart = M('cart');
      $condition=array(
        'phpsessid'=>$phpsessid,
        'user_id'=>$user_id,
        'goods_id'=>$shop_id
      );
      $cartCounts=$cart->field("counts,store_id")->where($condition)->find();
      return $cartCounts;
  }

    //根据购物车的cart_id获取商品数量
    public function getGoodsNum($goods_id){
        $goodsNum = M('goods')->where("id=$goods_id")->getField("goods_num");
        return $goodsNum;
    }

  //根据购物车的cart_id获取商品数量
  public function getOneGoodsNum($cart_id){
    $result = M('cart')->alias('c')->field('sd.stock,g.online_stock')
    ->join("LEFT JOIN __SELLER_DELIVERY__ AS sd ON c.goods_id = sg_id")
    ->join("LEFT JOIN __GOODS__ AS g ON g.id = sd.sg_id")->where("cart_id=$cart_id")->find();
    return $result;
  }


  /**设置购物车商品数量
   * @param $goods_id
   * @param $pay_type
   * @param $phpsessid
   * @param $services_id
   * @param $city_id
   * @param $user_id
   * @return bool|string
   */
  public function setCartGoods($goods_id,$pay_type,$phpsessid,$services_id,$city_id,$user_id){
      //$goods_title = $this->getGoodsName($goods_id);//获取商品名称
      $goods_info   = $this->getGoodsInfo($goods_id);//获取商品信息
      $want_price = $this->getGoodsPrice($city_id, $goods_id);
      $goodsPay_type = $this->getPayType($goods_id, $pay_type);
      $services_adviser = M('service_adviser')->where(array('id'=>$services_id))->find();
      foreach ($goods_id AS $key => $value) {
              $data[$key]['goods_id'] = $value;
              $data[$key]['user_id'] = $user_id ? $user_id : 0;
              $data[$key]['city_id'] = $city_id;
              $data[$key]['pay_type'] = $goodsPay_type[$value];
              $data[$key]['goods_title'] = $goods_info[$value]['goods_title'];
              $data[$key]['goods_img'] = $goods_info[$value]['original_img'];
              $data[$key]['want_price'] = $want_price[$value];
              $data[$key]['phpsessid'] = $phpsessid;
              $data[$key]['services_id'] = $services_id;
              $data[$key]['services_img'] = $services_adviser['img'];
              $data[$key]['services_name'] = $services_adviser['title'];
              $data[$key]['join_id'] = $value . '-' . $services_id;

          }
      $cartModel = M('cart');
      $result = $cartModel->addALL($data);
      return $result;
  }


      /**根据store_id分组商品列表
      * @parm $cartIds        arr 购物车id
      * @parm $cart_way_info  arr 用户选择的快递方式
      * @parm $condition      arr 查询条件
      * @parm $order          str 排序条件
      */

      public function getGoodsInfoById($cartIds,$cart_way_info,$condition,$order = 'store_id DESC'){

        //根据配送方式选择 cartIds 和  distinct
            $distinct=0;
            $cartModel=M('cart');
            $cart_id=implode(',',$cartIds);   //购物车Ids
            $condition['c.cart_id']=array('in',$cart_id);
            $res=$cartModel->alias('c')->field("group_concat(c.goods_thumb) as goods_img,group_concat(c.store_name) as store_name,c.seller_id")
            ->group('c.seller_id')->where($condition)->order($order)->select();
            foreach ($res as $key => $v) {
                $res[$key]['goods_img']=explode(',',$v['goods_img']);
                $res[$key]['store_name']=explode(',',$v['store_name']);
                    foreach ($cart_way_info as $k => $vs) {
                        if ($v['seller_id'] == $vs['seller_id']) {
                            $res[$key]['info']=$cart_way_info[$k];
                        }
                    }
            }

            return $res;
      }




  /**得带页码的购物车商品列表
   * @param $pageNum            int              页码
   * @param $order              string           排序
   * @param $condition          array            查询条件
   * @param $pageCondition      array            分页条件
   * @return mixed              array            包括分页信息和查询结果数组
   */
  public function getCartPageList($pageNum = 1,$order = 'counts DESC',  $condition = array() , $pageCondition = array()){
      $goodsModule = M('cart');
      $count = $goodsModule->where($condition)->count('distinct goods_id');
      $page = new \Think\Page($count,10,$pageCondition);
      //带入查询条件的页码
      foreach ($pageCondition as $key => $val) {
          $page->parameter .= "$key=" . urlencode($val) . '&';
      }
      $result['show'] = $page->show();
      $result['list'] = $goodsModule->field("cart_id,goods_title,count(distinct cart_id) as count_num,sum(counts) as counts,market_price,want_price")->group('goods_id')->where($condition)->order($order)->select();
      return $result;
  }

}
