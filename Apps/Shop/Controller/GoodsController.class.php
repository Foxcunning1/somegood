<?php
namespace Shop\Controller;
use Think\Controller;
//商城商品类
class GoodsController extends Controller {

    public function goodsListAction(){
        $keywords=I('keywords');
        if ($keywords){
            R('Mobile/Search/list');
        }else{
            R('Mobile/Goods/list');
        }
    }

	public function goodsAction(){
        if(IS_AJAX){
            $goodsId = I('id', 0, '/^\d+$/');  //商品id
            $sellerDeliveryModel = D('Common/SellerDelivery');
            $list=$sellerDeliveryModel->getGoodsStoreList($goodsId);
            $this->ajaxInfoReturn($list,'',1);
        }else{
            $userId = session('shop.id');  //用户id
            $goodsId = I('id', 0, '/^\d+$/');  //商品id
            //获得商品详情info
            $goodsModel = D('Common/Goods');
            //查询条件
            $info=$goodsModel->getGoodsDetailById($goodsId);   //商品信息及卖家信息
            //增加商品浏览次数
            M('goods')->where("id=$goodsId")->setInc("browse_num", 1, 60);
            //获取卖家全部宝贝
            $counts=$goodsModel->where(array('user_id'=>$info['user_id']))->count();
            //销量
            $online_sales_volume=$goodsModel->where(array('user_id'=>$info['user_id']))->sum('online_sales_volume');
            //判断用户是否收藏商品
            $favNum = 0;
            if ($userId>0) {
                $fCondition['fuser_id'] = $userId;
                $fCondition['favorite_id'] = $goodsId;
                $fCondition['favorite_type'] = 0;
                $favNum = M('favorites')->where($fCondition)->count();
            }
            $pic=unserialize($info['original_img']); //商品图片
            $this->assign('counts', $counts); //卖家全部宝贝
            $this->assign('online_sales_volume', $online_sales_volume); //销量
            $this->assign('goodsId', $goodsId);  //商品id
            $this->assign('favNum', $favNum);  //收藏数目
            $this->assign('pic', $pic);  //商品图片
            $this->assign('info', $info);  //商品的参数
            $this->display();
        }

	}

    /**收藏/取消一手*/
    public function collectAction()
    {
        $fuser_id = session("shop.id");
        $favorite_id = I('goods_id');
        if (empty($fuser_id)) {
            $this->ajaxInfoReturn("", "当前用户未登录！", 0);
            die();
        }
        $condition['fuser_id'] = $fuser_id;
        $condition['favorite_id'] = $favorite_id;
        $condition['favorite_type'] = 0;
        $favoritesNum = M('favorites')->where($condition)->count();
        if ($favoritesNum == 0) {
            //不存在则为加入收藏
            $info=M('Goods')->field('goods_title,price,goods_thumb')->where(array('id'=>$favorite_id))->find();
            $data =array(
                'fuser_id'            =>  $fuser_id,
                'favorite_id'         =>  $favorite_id,
                'favorite_type'       =>  0,
                'title'               =>  $info['goods_title'],
                'price'               =>  $info['price'],
                'goods_thumb'         =>  $info['goods_thumb'],
                'add_time'            =>  time()
            );
            $result=M('favorites')->add($data);
            if ($result) {
                $this->ajaxInfoReturn('取消收藏', "收藏成功！", 1);
            } else {
                $this->ajaxInfoReturn('', "收藏失败！", 0);
            }
        } else {
            //存在则为取消收藏
            M('favorites')->where($condition)->delete();
            $this->ajaxInfoReturn("加入收藏", "已取消收藏！", 1);
        }
    }


    /*卖家店*/
    public function sellerDetailAction(){
        $id=I('id',0);
        $keywords=I('keywords','');
        $condition['is_onsale']=1;
        $condition['user_id']=$id;
        $column_id=I('column',0);
        if($keywords!=''){
            $condition['goods_title']=array('like','%'.$keywords.'%');
            $this->assign('keywords',$keywords);
        }
        if($column_id>0){
            $condition['seller_column_id']=$column_id;
        }
        //获取卖家栏目
        $columnModel=D('Common/Goods');
        $map['user_id']=$id;
        $column_list=$columnModel->getSellerColumnTreeList($map);
        //获取卖家商品
        $list=M('goods')->field('id,goods_title,price,goods_thumb,is_onsale')->where($condition)->select();
        $this->assign('list',$list);
        $this->assign('seller_id',$id);
        $this->assign('column_list',$column_list);
        $this->display();
    }

}
