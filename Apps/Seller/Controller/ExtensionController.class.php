<?php
/**
 * Created by PhpStorm.
 * User: ZHY
 * Date: 2017/8/14
 * Time: 18:34
 */
namespace Seller\Controller;
use Think\Controller;

class ExtensionController extends BaseController{
    //卖家商品
    public function indexAction(){
        $goods_model=D('Common/Goods');
        $pageNum=I('p',1);
        $condition['user_id']=session('seller.id');
        $type=I('type','all');
        switch ($type){
            case 'wait':
                $condition['g.status']=0;
                $pageCondition['type']='off';
                break;
            case 'ing':
                $condition['g.status']=array('gt',0);
                $pageCondition['type']='sale';
                break;
            default:
                break;
        }
        $condition['is_extension']=1;
        $result=$goods_model->getSellerGoodsPageList($pageNum,C('GOODS_PAGE_SIZE'),'sales_volume DESC',$condition,$pageCondition);
        $this->assign('list',$result['list']);
        $this->assign('pageType',$type);
        $this->assign('pageShow',$result['show']);
        $this->assign('pageCount',$result['pageCount']);
        $this->display();
    }

    /**商品投放店铺列表及部分信息*/
    public function sellerDeliveryAction(){
        $id=I('id');
        $pageNum=I('p',1);
        $pageCondition['id']=$id;
        $goods_model=D('Common/Goods');
        //获取商品信息
        $info=$goods_model->getGoodsInfo($id);
        //获取该商品投放店铺列表及店铺库存等信息
        $sellerDeliveryModel=D('Common/SellerDelivery');
        $condition['sg_id']=$id;
        $result=$sellerDeliveryModel->getGoodsPageList($pageNum,C('SELLER_DELIVERY_PAGE_SIZE'),'sales_volume DESC',$condition,$pageCondition);
        $this->assign('info',$info);
        $this->assign('list',$result['list']);
        $this->assign('page',$result['show']);
        $this->display();
    }
}