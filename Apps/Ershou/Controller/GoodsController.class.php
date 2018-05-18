<?php
namespace Ershou\Controller;
use Think\Controller;
/*
*首页
*/
class GoodsController extends BaseController {

    //商品分类
    public function categoryAction(){
        //热门推荐
        $pid = I('id',0);
        $goodsCategoryList = F('Mobile/Category/category_list_'.$pid);
        $recCategoryList = "";
        if (!$goodsCategoryList) {
            $cacheCategoryList = require_once ("./Cache/category/category.cache.php");
            if($pid==0){//默认为0时调取左侧大类
                $categoryArr = array();
                foreach($cacheCategoryList as $key=>$val){
                    $tempCategoryList['id'] = $val['id'];
                    $tempCategoryList['title'] = $val['title'];
                    $tempCategoryList['img_url'] = $val['img_url'];
                    $tempCategoryList['parent_id'] = $val['parent_id'];
                    $categoryArr[] = $tempCategoryList;
                }
                $goodsCategoryList = $categoryArr;
                F('Mobile/Category/category_list_'.$pid,$goodsCategoryList);
            }else{//id不为0时，调取当前ID下的所有子类
                $goodsCategoryList = $cacheCategoryList[$pid]['children'];
                F('Mobile/Category/category_list_'.$pid,$goodsCategoryList);
            }
        }
        if(IS_AJAX){
            $this->ajaxInfoReturn($goodsCategoryList,"数据获取成功!",1);
        }else{
            //获取焦点图
            $categoryBannerList = F('Mobile/Category/categoryBannerList');
            if(!$categoryBannerList){
                $advModel = D('Common/Adv');
                $categoryBannerList = $advModel->getTopAdvPageList(2,1);
                F('Mobile/Category/categoryBannerList',$categoryBannerList);
            }
            $categoryModel = D("Common/GoodsCategory");
            //推荐分类
            $recGoodsCategoryList = F('Mobile/Category/recCategoryList');
            if(!$recGoodsCategoryList){
                $recGoodsCategoryList = $categoryModel->getTopCategoryList(6,array("rec"=>1));
                F('Mobile/Category/recCategoryList',$recGoodsCategoryList);
            }
            //热门分类
            $hotGoodsCategoryList = F('Mobile/Category/hotCategoryList');
            if(!$hotGoodsCategoryList){
                $hotGoodsCategoryList = $categoryModel->getTopCategoryList(6,array("hot"=>1));
                F('Mobile/Category/hotCategoryList',$hotGoodsCategoryList);
            }
            $this->assign('web_title',"所有商品分类");
            $this->assign('categoryBannerList',$categoryBannerList);
            $this->assign('categoryList',$goodsCategoryList);
            $this->assign('recCategoryList',$recGoodsCategoryList);
            $this->assign('hotCategoryList',$hotGoodsCategoryList);
            $this->assign('pid',$pid);
            $this->display();
        }
    }
   //商品列表
    public function listAction(){
        $catid = I('get.catid',0);//商品分类id
        $keywords=I('get.keywords','');//商品关键字
        $pageNum = I('get.p',1);
        $isAjax = I('get.isajax',0);//数据获取类型,0为正常分页，1为ajax获取
        $order = I('get.order','2');//数据获取类型，0为价格降序，1为价格升序，2为距离
        $areaId = I('get.area_id',0);//区域id
        if($keywords) {//关键字查询
            $keyArr = explode(' ',$keywords);
            foreach($keyArr as $v){
                $map['goods_title'][] = array('like',"%".$v."%");
                $map['keywords'][] = array('like',"%".$v."%");
            }
            $map['goods_title'][] = 'or';
            $map['keywords'][] = 'or';
            $map['_logic'] = 'or';
            $pageCondition['keywords'] = $keywords;
        }
        if($catid>0){//类别id查询
            $goodsCategoryModel = D('Common/GoodsCategory');
            $ids = $goodsCategoryModel->getCategoryChildrenIds($catid);
            $map['category_id'] = array("in",$ids);
            $pageCondition['catid'] = $catid;
        }
        if($areaId>0){
            $map['g.area_id'] = $areaId;
        }
        $pageCondition['order'] = $order;//排序
        $pageCondition['area_id'] = $areaId;//区域id
        $pageCondition['isajax'] = $isAjax;//是否ajax获取
        //把用户坐标加入条件数组里
        $regionModel = D("Common/Region");
        if($regionModel->isExist()) {//用户位置城市与选择城市一致
            //$map['s.lng'] = array("gt", session("lng") - 1);
            //$map['s.lng'] = array("lt", session("lng") + 1);
            //$map['s.lat'] = array("gt", session("lat") - 1);
            //$map['s.lat'] = array("lt", session("lat") + 1);
            if ($order == 2) {
                $orderStr = "ACOS(SIN((" . session("lat") . " * 3.1415) / 180 ) *SIN((g.lat * 3.1415) / 180 ) + COS((" . session("lat") . " * 3.1415) / 180 )
                              * COS((g.lat * 3.1415) / 180 ) *COS((" . session("lng") . "* 3.1415) / 180 - (g.lng * 3.1415) / 180 ) ) * 6380 asc";
            }
        }else{
            if($order == 0){
                $orderStr = "want_price desc,id desc";
            }
            if($order == 1){
                $orderStr = "want_price asc,id desc";
            }
        }
        $GoodsModule = D('Common/ShGoods');
        $map['g.status'] = 1;
        $result = $GoodsModule->getGoodsPageList($pageNum, 10, $orderStr, $map, $pageCondition );
        //var_dump($result);
        if($isAjax){//ajax获取数据
            if($result){
                $this->ajaxInfoReturn($result,"数据获取成功",1);
            }else{
                $this->ajaxInfoReturn('',"亲，已经没有数据了！",2);
            }
        }else {//正常输出页面数据
            //商品分类
            $goodsCategoryList = F('Mobile/Category/category_list_0');
            if (!$goodsCategoryList) {
                $cacheCategoryList = require_once ("./Cache/category/category.cache.php");
                $categoryArr = array();
                foreach($cacheCategoryList as $key=>$val){
                    $tempCategoryList['id'] = $val['id'];
                    $tempCategoryList['title'] = $val['title'];
                    $tempCategoryList['img_url'] = $val['img_url'];
                    $tempCategoryList['parent_id'] = $val['parent_id'];
                    $categoryArr[] = $tempCategoryList;
                }
                $goodsCategoryList = $categoryArr;
                F('Mobile/Category/category_list_0',$goodsCategoryList);
            }
            //获取当前类别的信息
            $bigCatId = 0;
            if($catid>0){
                $catInfo = D("Common/GoodsCategory")->getCategoryInfo($catid);
                $tempArr = explode(",",$catInfo['class_list']);
                if(count($tempArr)>1){
                    $bigCatId = $tempArr[1];
                }
            }
            //根据城市id获取区域信息
            $areaList = $regionModel->getAreaList(cookie("city_id"));
            $this->assign("web_title", "商品列表");//页面标题
            $this->assign("keywords", $keywords);
            $this->assign("catid", $catid);
            $this->assign("bigCatId", $bigCatId);
            $this->assign("isAjax", $isAjax);
            $this->assign("order", $order);
            $this->assign("area_id", $areaId);
            $this->assign('areaList', $areaList);
            $this->assign('categoryList', $goodsCategoryList);
            $this->assign('list', $result['list']);
            $this->assign('pageCount', $result['pageCount']);//分页总数
            $this->assign('empty', C('NOT_DATA'));
            $this->display();
        }
    }

    //商品详情
    public function detailAction(){
        $userId = session('mobile.id');
        $goodsId = I('get.id',0,'/^\d+$/');
        $goodsModel = D('Common/ShGoods');
        /*获取用户所在城市*/
        $returnUrl = $_SERVER['HTTP_REFERER'];
        $info = $goodsModel->getGoodsInfo($goodsId);
        //增加商品浏览次数
        $goodsModel->updateBrowseNum($goodsId);
        //判断用户是否收藏商品
        $favNum = 0;
        if($userId>0){
            $fCondition['fuser_id'] = $userId;
            $fCondition['favorite_id'] = $goodsId;
            $fCondition['favorite_type'] = 1;
            $favNum = M('favorites')->where($fCondition)->count();
        }
        $photoList = array();//图片列表
        $photoRemark = array();//图片简述
        if($info['original_img']){
            $tempArr = explode("||",$info['original_img']);
            $photoList = $tempArr[0];
            $photoRemark = $tempArr[1];
        }
        //用户推荐商品
        if(S('user_rec_goods_list'.$info['user_id'])){
            $userRecGoodsList = S('user_rec_goods_list'.$info['user_id']);
        }else{
            $condition['g.is_rec']  = 1;//商品推荐
            $condition['g.status']  = 1;//商品状态
            $condition['g.user_id'] = $info['user_id'];
            $gOrderStr = "g.update_time desc, g.id desc";//商品排序
            $userRecGoodsList = $goodsModel->getTopGoodsList(10,$gOrderStr,$condition);
            S('user_rec_goods_list'.$info['user_id'],3600);
        }
        $this->assign('web_title',$info['goods_title']);
        $this->assign('favNum',$favNum);
        $this->assign('photoList',unserialize($photoList));
        $this->assign('photoRemark',unserialize($photoRemark));
        $this->assign('returnUrl',$returnUrl);
        $this->assign('info',$info);
        $this->assign('userRecGoodsList',$userRecGoodsList);
        $this->assign('empty',C('NOT_DATA'));
        $this->display();
    }

    //收藏/取消商品/店铺
    public function collectAction(){
        $fuser_id = session("mobile.id");
        //$user_id = 18;
        $favorite_id = I('goods_id');
        $favorite_type = I('favorite_type',0);
        if (empty($fuser_id)) {
            $this->ajaxInfoReturn("","当前用户未登录！",0);
            die();
        }
        $condition['fuser_id'] = $fuser_id;
        $condition['favorite_id'] = $favorite_id;
        $condition['favorite_type'] = $favorite_type;
        $favoritesNum = M('favorites')->where($condition)->count();
        if ( $favoritesNum == 0 ) {
            $data =array(
              'fuser_id'   =>  $fuser_id,
              'favorite_id'  =>  $favorite_id,
              'favorite_type'=>$favorite_type
            );
            $result=M('favorites')->add($data);
            if ($result) {
                if($favorite_type==1){
                    $this->ajaxInfoReturn('',1,1);
                }else{
                $this->ajaxInfoReturn("","收藏成功！",1);
                }
            }else {
                if($favorite_type==1){
                    $this->ajaxInfoReturn('',0,0);
                }
                $this->ajaxInfoReturn("","收藏失败！",0);
            }
        }else {
            $result=M('favorites')->where($condition)->delete();
            $this->ajaxInfoReturn("","已取消！",1);
        }

    }
}
