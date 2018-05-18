<?php
namespace Shop\Controller;
use Think\Controller;
//购物车类
class CartController extends Controller {

		//购物车主页
	public function indexAction(){
        $user_id = session("shop.id");
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
        $counts = $cart->alias('c')->where($condition)->count();   //用户购物车商品类数量
        $result = $cart->getOneCart($condition);		//所有商品信息
        //根据seller_id 分组展示每个商品对应的店铺名
        $shop = $cart->alias('c')->field('c.*,u.user_name,group_concat(c.store_name)')
                                 ->join("LEFT JOIN __USERS__ AS u ON u.uid = c.seller_id")
                                 ->where($condition)->group('c.seller_id')->select();
        $returnUrl=substr($_SERVER['HTTP_REFERER'],-10);
        $this->assign('returnUrl',$returnUrl);
        $this->assign('user_id',$user_id);
        $this->assign('shop',$shop);
        $this->assign('counts',$counts);
        $this->assign('info', $result);
        $this->display();
	}

    //获取购物车商品
    public function getCartGoodsAction(){
        $user_id=session('shop.id');
        $cartModel=D('Common/Cart');
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
        $list['list'] = $cartModel->where($condition)->select();
        $cartNum = M('cart')->where($condition)->sum('counts');
        $list['cartNum']=(int)$cartNum;
        $this->ajaxInfoReturn($list,"获取成功！",1);
    }

	/*删除购物车产品
    * */
    public function delCartGoodsAction()
    {
        $cart_id = I('goodsid');
		$cartIdArr=explode(',',$cart_id);
		foreach ($cartIdArr as $key => $v) {
			$result = M('cart')->where("cart_id=$v")->delete();
		}
        if ($result) {
			$this->ajaxInfoReturn("","删除成功！",'1');
        }else {
            $this->ajaxInfoReturn("","删除失败！",'0');
        }
    }

	/*更新购物车产品数量
	* */
	public function ajaxUpdateGoodsNumAction(){
		$cart_id = I('cartId');
		$counts = I('counts');
		//购物车商品数量
		$cart=D('Common/Cart');
		$res=$cart->getOneGoodsNum($cart_id);
		$goods_num=$res['stock'];
		if ($counts > $goods_num && $res['online_stock']<$goods_num) {
			$this->ajaxInfoReturn("","商品库存不足！",0);
			die();
		}
		$data['counts'] = $counts;
		$result = $cart->where("cart_id=$cart_id")->save($data);
		if($result) {
			$this->ajaxInfoReturn("","更新成功！",1);
		}else{
			$this->ajaxInfoReturn("","您的商品不能再少了,亲！",0);
		}
	}

	/*添加到购物车
	* */
	public function addGoodsAction(){
		$goods_id = I('goods_id');
		$seller_delivery_id = I('seller_delivery_id');
		$goods_num = I('goods_num');
		$add_time = time();
		$user_id = session("shop.id");
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
		$goodsInfo = $goodsModel->getGoodsPartInfo($seller_delivery_id);
		//验证商品数量
		$data['user_id'] = $user_id;
		$data['goods_id'] = $shop_id;
		$data['real_goods_id'] = $goods_id;
		$data['store_id'] = $goodsInfo['store_id'];
		$data['seller_id'] = $goodsInfo['seller_id'];
		$data['store_name'] = $goodsInfo['store_name'];
		$data['phpsessid'] = $phpsessid;
		$data['market_price'] = $goodsInfo['market_price'];
		$data['goods_price'] = $goodsInfo['price'];
		$data['counts'] = $goods_num;
		$data['goods_title'] = $goodsInfo['goods_title'];
		$data['goods_thumb'] = $goodsInfo['goods_thumb'];
		$data['add_time'] = $add_time;
		$cart = D('Common/Cart');
		$cartGoodsNum = $cart->getCartgoodsNum($phpsessid,$user_id,$shop_id);
		//var_dump($cartGoodsNum);       //这个商品在购物车中的数量
		if ($cartGoodsNum['counts'] >= $goodsInfo['stock'] && $cartGoodsNum['counts'] >=$goodsInfo['online_stock'] ) {
			$this->ajaxInfoReturn("","商品库存不足！",0);
		}
		if ($cartGoodsNum['counts'] == 0  && $goodsInfo['store_id'] != $cartGoodsNum['store_id']) {
				//检测是否获取到phpsessid
				if ( $phpsessid == NULL && $user_id == 0) {
					$this->ajaxInfoReturn("","获取phpsessid失败!",0);
				}
				// //验证此商品是否已存在
				// $phpsessid =cookie('equipment_id');
				// $map5['goods_id']=$shop_id;
				// $map5['_string'] = "(phpsessid=$phpsessid) OR (user_id=$user_id)";
				// echo $count=$cart->where($map5)->count();exit;
				// if ( $count== 0) {
					$result= $cart->add($data);//添加至购物车
				// }else {
				//     $result= $cart->where($map5)->setInc('counts',$goods_num);//添加至购物车
				// }
			if ($result) {
				//加入购物车成功
				$this->ajaxInfoReturn("","加入成功！",1);
			}else {
				//加入购物车失败
				$this->ajaxInfoReturn("","加入失败！",0);
			}
		}else {
			$condition['goods_id'] = $shop_id;
			$cartGoodsNum['counts']++;
			$data1['counts'] = $cartGoodsNum['counts'];
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

	/*收藏购物车产品
    * */
    public function addFavoritesAction()
    {
			//判断是否登录
			$userId  =	session('shop.id');
			if (!$userId) {
				$this->ajaxInfoReturn("","当前用户未登录!",'-1');
			}
			$goodsId = I('goodsid');   //购物车ids
			$goodsId = explode(',',$goodsId);		//购物车ids

			$favoritesModel=M('favorites');
			$cartModel=M('cart');
			$goodsModel=M('goods');
			foreach ($goodsId as $key => $v) {
				$id=$cartModel->where("cart_id=$v")->getField('goods_id');
				//判断是否已关注
				if ( $favoritesModel->where(array('fuser_id'=>$userId,'favorite_id' => $id,'favorite_type'=>'0'))->count()==0) {
					//收藏商品
					$info=$goodsModel->field("goods_title,price,goods_thumb")->where("id=$id")->find();
					$data = array(
						'fuser_id' => $userId,
						'favorite_id' => $id,
						'title' => $info['goods_title'],
						'price' =>$info['price'],
						'goods_thumb' =>$info['goods_thumb'],
					);
					$result = $favoritesModel->data($data)->add();
					if ($result) {
			            $this->ajaxInfoReturn("","移入收藏夹成功！",'1');
			        }else {
						$this->ajaxInfoReturn("","移入收藏夹失败！",'0');
			        }
				}else {
					$this->ajaxInfoReturn("","商品已关注！",'1');
				}
			}


    }

	public function testAction(){
		$cart_id = 127;
		//购物车商品数量
		//var_dump(M('Cart','sg'));exit;
		$res=$cart->getOneGoodsNum($cart_id);
	}
}
