<?php
/**
 * Created by PhpStorm.
 * User: ZHY
 * Date: 2017/8/14
 * Time: 18:34
 */
namespace Ershou\Controller;
use Think\Controller;

class StoreController extends BaseController{
    //验证店铺
    public function valiStoreAction(){
        $store_count=M('store')->where(array('user_id'=>session('mobile.id'),'status'=>1))->count();
        if($store_count==0){
            header("Location:apply");
        }
    }

    //店铺管理
    public function indexAction(){
        $this->valiStoreAction();
        $store_model=D('Common/Store');
        $storeDetail=$store_model->getStoreDetail(session('mobile.id'));
        $goodsModel=M('sh_goods');
        //待发货的数量
        $wait_send_num=M('market_order')->alias('o')->field('status')->join("LEFT JOIN __MARKET_ORDER_GOODS__ AS og ON o.order_sn = og.order_sn")->group('og.order_sn')->where(array('status'=>1,'og.store_id'=>session('mobile.store_id')))->select();
        $this->assign('wait_send_num',count($wait_send_num));
        //待审核数量
        $audit_num=$goodsModel->where(array('status'=>0,'store_id'=>session('mobile.store_id')))->count();
        $this->assign('audit_num',$audit_num);
        //待上架数量
        $wait_sale_num=$goodsModel->where(array('status'=>1,'over_time'=>-1,'store_id'=>session('mobile.store_id')))->count();
        $this->assign('wait_sale_num',$wait_sale_num);
        //卖出数量

        //上架数量
        $on_sale_num=$goodsModel->where(array('is_on_sale'=>1,'store_id'=>session('mobile.store_id')))->count();
        $this->assign('on_sale_num',$on_sale_num);
        //过期的数量
        $off_sale_num=$goodsModel->where(array('goods_num'=>array('gt',0),'over_time'=>array('lt',time()),'store_id'=>session('mobile.store_id')))->count();
        $this->assign('off_sale_num',$off_sale_num);

        $this->assign('store',$storeDetail);
        $this->display();
    }


    //店铺列表
    public function listAction(){
        $pageNum = I('p',1,'strip_tags');
        $areaId = I('get.area_id',0);//区域id
        $order = I('get.order','2');//数据获取类型，0为销量降序，1为销量升序，2为距离
        $isAjax = I('get.isajax',0);//数据获取类型,0为正常分页，1为ajax获取
        $storeModel = D('Common/Store');
        $orderStr = 'sid DESC';
        if($areaId>0){
            $condition['area_id'] = $areaId;
        }
        $condition['status']=1;
        $pageCondition['area_id'] = $areaId;//区域id
        $pageCondition['isajax'] = $isAjax;//是否ajax获取
        if($areaId>0){
            $map['g.area_id'] = $areaId;
        }
        //把用户坐标加入条件数组里
        $regionModel = D("Common/Region");
        if($regionModel->isExist()) {//用户位置城市与选择城市一致
            //$condition['s.lng'] = array("gt", session("lng") - 1);
            //$condition['s.lng'] = array("lt", session("lng") + 1);
            //$condition['s.lat'] = array("gt", session("lat") - 1);
            //$condition['s.lat'] = array("lt", session("lat") + 1);
            if ($order == 2) {
                $orderStr = "ACOS(SIN((" . session("lat") . " * 3.1415) / 180 ) *SIN((s.lat * 3.1415) / 180 ) + COS((" . session("lat") . " * 3.1415) / 180 )
                              * COS((s.lat * 3.1415) / 180 ) *COS((" . session("lng") . "* 3.1415) / 180 - (s.lng * 3.1415) / 180 ) ) * 6380 asc";
            }
        }else{
            if($order == 0){
                $orderStr = "sale_counts desc, sid asc";
            }
            if($order == 1){
                $orderStr = "sale_counts asc, sid asc";
            }
        }
        //分页获取
        $result = $storeModel->getStoreList($pageNum,10,$orderStr,$condition,$pageCondition);
        if($isAjax){//ajax获取数据
            if($result){
                $this->ajaxInfoReturn($result,"数据获取成功",1);
            }else{
                $this->ajaxInfoReturn('',"亲，已经没有数据了！",2);
            }
        }else {//正常输出页面数据
            //根据城市id获取区域信息
            $areaList = $regionModel->getAreaList(cookie("city_id"));
            $this->assign('area_id', $areaId);
            $this->assign("order", $order);
            $this->assign('areaList', $areaList);
            $this->assign('storeList', $result['list']);
            $this->assign('pageCount', $result['pageCount']);//分页总数
            $this->assign('count', $result['count']);
            $this->display();
        }
    }
    //卖家店铺
    public function myShopAction(){
        $sid = I('shopid');
        $pageNum=I('p',1);
        $pageType = I('pageType','all');//数据获取条件
        $usersModel = D('Common/Users');
        $userInfo = $usersModel->getErshouSellerInfo($sid);
        //置顶商品
        $goodsModel = D('Common/ShGoods');
        $condition['g.user_id'] = $sid;
        $pageCondition['shopid']=$sid;
        switch($pageType){
            case 'rec':
                $condition['g.is_rec'] = 1;
                break;
            case 'hot':
                $condition['g.is_hot'] = 1;
                break;
            case 'new':
                $condition['g.is_new'] = 1;
                break;
        }
        $condition['g.status'] = 1;
        //$condition['is_top'] = 1;
        //$pageCondition['is_top']=1;
        $result = $goodsModel->getGoodsPageList($pageNum,C('GOODS_PAGE_SIZE'),'',$condition,$pageCondition);
        if (IS_AJAX){
            $this->assign('list', $result['list']);
            $this->assign('isAjax',1);
            $this->display();
        }else{
            //获取该店铺关注状态
            $favoriteStatus=M('favorites')->where(array('fuser'=>session('mobile.id'),'favorite_id'=>$sid,'favorite_type'=>1))->count();
            $this->assign('favoriteStatus',$favoriteStatus);
            $this->assign('info', $userInfo);
            $this->assign('pageCount', $result['pageCount']);
            $this->assign('pageType', $pageType);
            $this->assign('shop_id',$sid);
            $this->display();
        }
    }
}
