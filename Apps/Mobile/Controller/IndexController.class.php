<?php
namespace Mobile\Controller;
use Think\Controller;

/*
*首页
*/
class IndexController extends BaseController {

    public function indexAction(){
        //获取焦点图
        $bannerList = S('indexBannerList');
        if(!$bannerList){
            $advModel = D('Common/Adv');
            $bannerList = $advModel->getTopAdvPageList(1,4);
            S('indexBannerList',$bannerList,3600);
        }
        //热门关键字
        $hotWords = S('hotWords');
        if (empty($hotWords)) {
            $keywords=M('keywords');
            $hotWords=$keywords->order('count DESC')->limit('0,4')->select();
            S('hotWords',$hotWords,300);
        }
        //热门分类
        $indexCategoryList = F('Mobile/Category/indexCategoryList');
        if (!$indexCategoryList) {
            $goodsCategoryModel = D('GoodsCategory');
            $indexCategoryList = $goodsCategoryModel->getTopCategoryList(10,array("is_index"=>1));
            F('Mobile/Category/indexCategoryList',$indexCategoryList);
        }
        //$condition['is_rec']  = 1;//商品推荐
        $condition['g.status']  = 1;//商品状态
        $gOrderStr = "update_time desc, id desc";//商品排序
        $goodsModel = D("Common/Goods");
        $recGoodsList = $goodsModel->getTopGoodsList(10, $gOrderStr, $condition);//商品推荐

        //店铺类型列表
        $typeList=M('StoreType')->select();

        //获取店铺总页数
        $areaId = I('area_id',0);//区域id
        $typeId = I('type',0);//店铺类型id
        $order = I('order',2);//店铺类型id
        if($areaId>0){
            $map['area_id'] = $areaId;
        }
        if($typeId>0){
            $map['store_type'] = $typeId;
        }
        $map['status']=1;
        $storeCount=M('Store')->where($map)->count();
        $totalPage=ceil($storeCount/10);


        $this->assign('totalPage',$totalPage);
        $this->assign('web_title',"3好连锁店");
        $this->assign('bannerList',$bannerList);
        $this->assign('recGoodsList',$recGoodsList);
        $this->assign('hotWord',$hotWords);
        $this->assign('indexCategoryList',$indexCategoryList);
        $this->assign('type',$typeId);
        $this->assign('area',$areaId);
        $this->assign('order',$order);
        $this->assign('typeList',$typeList);
        $this->display();
    }

    public function storeListAction(){
        if(!IS_AJAX){
            $this->tips('提交方式错误','',3,$_SERVER['HTTP_REFERER']);
        }else{
            $pageNum = I('p',1,'strip_tags');
            $areaId = I('area_id',0);//区域id
            $typeId = I('type',0);//店铺类型id
            $order = I('order','2');//数据获取类型，0为销量降序，1为销量升序，2为距离
            $storeModel = D('Common/Store');
            $orderStr = 'sid DESC';
            if($areaId>0){
                $condition['area_id'] = $areaId;
            }
            if($typeId>0){
                $condition['store_type'] = $typeId;
            }
            $condition['status']=1;
            $condition['is_rec']=1;//推荐显示
            $pageCondition['area_id'] = $areaId;//区域id
            $pageCondition['type'] = $typeId;//店铺类型id

            //把用户坐标加入条件数组里
            $regionModel = D("Region");
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

            $this->assign('list',$result['list']);
            $this->assign('totalPage',$result['pageCount']);
            $this->assign('isAjax',1);
            $this->display('index');
        }
    }

    //详情
    public function storeDetailAction(){
        $sid=I('shopid');
        $storeModel=D('Common/store');
        $storeDetail=$storeModel->storeDetail($sid);
        $region=explode(',',$storeDetail['region']);
        $storeDetail['region']=$region[2].$region[3].$storeDetail['address'];
        //获取店铺相册
        $photosArray=explode('|',$storeDetail['photos']);
        $photos=unserialize($photosArray[1]);
        $rphotos=unserialize($photosArray[2]);
        unset($storeDetail['photos']);
        //获取该店铺关注状态
        $favoriteStatus=M('favorites')->where(array('fuser'=>session('mobile.id'),'favorite_id'=>$sid,'favorite_type'=>1))->count();
        $this->assign('favoriteStatus',$favoriteStatus);
        $this->assign('info', $storeDetail);
        $this->assign('plist', $photos);
        $this->assign('rlist', $rphotos);
        $this->assign('shop_id',$sid);
        $this->display();

    }
}
