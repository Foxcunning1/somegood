<?php
namespace Mobile\Controller;

use Think\Controller;

/*
*首页
*/
class GoodsController extends BaseController
{

    //商品分类
    public function categoryAction()
    {
        //热门推荐
        $pid = I('id', 0);
        $goodsCategoryList = F('Mobile/Category/category_list_'.$pid);
        $recCategoryList = "";
        if (!$goodsCategoryList) {
            $cacheCategoryList = require_once("./Cache/category/category.cache.php");
            if ($pid==0) {//默认为0时调取左侧大类
                $categoryArr = array();
                foreach ($cacheCategoryList as $key=>$val) {
                    $tempCategoryList['id'] = $val['id'];
                    $tempCategoryList['title'] = $val['title'];
                    $tempCategoryList['img_url'] = $val['img_url'];
                    $tempCategoryList['parent_id'] = $val['parent_id'];
                    $categoryArr[] = $tempCategoryList;
                }
                $goodsCategoryList = $categoryArr;
                F('Mobile/Category/category_list_'.$pid, $goodsCategoryList);
            } else {//id不为0时，调取当前ID下的所有子类
                $goodsCategoryList = $cacheCategoryList[$pid]['children'];
                F('Mobile/Category/category_list_'.$pid, $goodsCategoryList);
            }
        }
        if (IS_AJAX) {
            $this->ajaxInfoReturn($goodsCategoryList, "数据获取成功!", 1);
        } else {
            //获取焦点图
            $categoryBannerList = F('Mobile/Category/categoryBannerList');
            if (!$categoryBannerList) {
                $advModel = D('Common/Adv');
                $categoryBannerList = $advModel->getTopAdvPageList(2, 1);
                F('Mobile/Category/categoryBannerList', $categoryBannerList);
            }
            $categoryModel = D("Common/GoodsCategory");
            //推荐分类
            $recGoodsCategoryList = F('Mobile/Category/recCategoryList');
            if (!$recGoodsCategoryList) {
                $recGoodsCategoryList = $categoryModel->getTopCategoryList(6, array("rec"=>1));
                F('Mobile/Category/recCategoryList', $recGoodsCategoryList);
            }
            //热门分类
            $hotGoodsCategoryList = F('Mobile/Category/hotCategoryList');
            if (!$hotGoodsCategoryList) {
                $hotGoodsCategoryList = $categoryModel->getTopCategoryList(6, array("hot"=>1));
                F('Mobile/Category/hotCategoryList', $hotGoodsCategoryList);
            }
            $this->assign('web_title', "所有商品分类");
            $this->assign('categoryBannerList', $categoryBannerList);
            $this->assign('categoryList', $goodsCategoryList);
            $this->assign('recCategoryList', $recGoodsCategoryList);
            $this->assign('hotCategoryList', $hotGoodsCategoryList);
            $this->assign('pid', $pid);
            $this->display();
        }
    }
    //商品列表
    public function listAction()
    {
        $catid = I('get.catid', 0);//商品分类id
        $keywords=I('get.keywords', '');//商品关键字
        $pageNum = I('get.p', 1);
        $isAjax = I('get.isajax', 0);//数据获取类型,0为正常分页，1为ajax获取
        $order = I('get.order', 0);//数据获取类型，0为默认，1为销量，2为价格升序，3为价格降序
        if ($keywords) {//关键字查询
            $keyArr = explode(' ', $keywords);
            foreach ($keyArr as $v) {
                $map['goods_title'][] = array('like',"%".$v."%");
                $map['keywords'][] = array('like',"%".$v."%");
            }
            $map['goods_title'][] = 'or';
            $map['keywords'][] = 'or';
            $map['_logic'] = 'or';
            $pageCondition['keywords'] = $keywords;
        }
        if ($catid>0) {//类别id查询
            $goodsCategoryModel = D('Common/GoodsCategory');
            $ids = $goodsCategoryModel->getCategoryChildrenIds($catid);
            $map['g.category_id'] = array("in",$ids);
            $pageCondition['catid'] = $catid;
        }
        $pageCondition['order'] = $order;//排序
        $pageCondition['isajax'] = $isAjax;//是否ajax获取
        switch ($order) {
            case 0:
                $orderStr = " update_time desc,id desc";
                break;
            case 1:
                $orderStr = "sales_volume DESC,id desc";
                break;
            case 2:
                $orderStr = "price asc,id desc";
                break;
            case 3:
                $orderStr = "price asc,id desc";
                break;
        }
        $GoodsModule = D('Common/Goods');
        $map['g.status'] = array("gt",0);
        $result = $GoodsModule->getSellerGoodsPageList($pageNum, 10, $orderStr, $map, $pageCondition);
        //var_dump($result);
        if ($isAjax) {//ajax获取数据
            if ($result) {
                $this->ajaxInfoReturn($result, "数据获取成功", 1);
            } else {
                $this->ajaxInfoReturn('', "亲，已经没有数据了！", 2);
            }
        } else {//正常输出页面数据
            //商品分类
            $goodsCategoryList = F('Mobile/Category/category_list_0');
            if (!$goodsCategoryList) {
                $cacheCategoryList = require_once("./Cache/category/category.cache.php");
                $categoryArr = array();
                foreach ($cacheCategoryList as $key=>$val) {
                    $tempCategoryList['id'] = $val['id'];
                    $tempCategoryList['title'] = $val['title'];
                    $tempCategoryList['img_url'] = $val['img_url'];
                    $tempCategoryList['parent_id'] = $val['parent_id'];
                    $categoryArr[] = $tempCategoryList;
                }
                $goodsCategoryList = $categoryArr;
                F('Mobile/Category/category_list_0', $goodsCategoryList);
            }
            //获取当前类别的信息
            $bigCatId = 0;
            if ($catid>0) {
                $catInfo = D("Common/GoodsCategory")->getCategoryInfo($catid);
                $tempArr = explode(",", $catInfo['class_list']);
                if (count($tempArr)>1) {
                    $bigCatId = $tempArr[1];
                }
            }
            //根据城市id获取区域信息
            $this->assign("web_title", "商品列表");//页面标题
            $this->assign("keywords", $keywords);
            $this->assign("catid", $catid);
            $this->assign("bigCatId", $bigCatId);
            $this->assign("isAjax", $isAjax);
            $this->assign("order", $order);
            $this->assign('categoryList', $goodsCategoryList);
            $this->assign('list', $result['list']);
            $this->assign('page', $result['show']);
            $this->assign('pageCount', $result['pageCount']);//分页总数
            $this->assign('empty', C('NOT_DATA'));
            $this->display();
        }
    }

    //商品详情
    public function detailAction()
    {
        $userId = session('mobile.id');  //用户id
        $goodsId = I('id', 0, '/^\d+$/');  //商品id
        $city_id=cookie('city_id')?cookie('city_id'):1988;  //城市id
        $pageNum=I('p', '1');  //区域id
        $area_id=I('area_id', '');  //区域id
        //查询城市下面的区域
        $district=M('region')->field("id,name")->where("pid=$city_id")->select();
        //获得商品详情info
        $goodsModel = D('Common/Goods');
        //查询条件
        $condition['g.id']=$goodsId;
        $condition['sd.is_onsale']=1;
        $condition['sd.is_delivery']=1;
        if ($area_id) {
            //用户所选区域
            $sel_area=M('region')->where("id=$area_id")->getField('name');
            $condition['s.area_id']=$area_id;
        } else {
            //城市下所有店铺
            $carea_id=M('region')->field("group_concat(id) as cid")->where("pid=$city_id")->find();   //城市下所有区id
            $condition['s.area_id']=array('in',$carea_id['cid']);
        }
        $list=$goodsModel->getDetailById($condition,$pageNum);   //商品下的店铺列表
        //商品二维码
        $QRCode=createQRCode($goodsId);
        $this->assign('QRCode', $QRCode);
        $this->assign('area_id', $area_id);  //区域id
        $this->assign('sel_area', $sel_area);
        $this->assign('goodsId', $goodsId);
        $this->assign('district', $district);
        $this->assign('counts', $list['counts']);
        $this->assign('list', $list['list']);
        $this->assign('pageCount', $list['page']);
        $this->assign('empty', C('NOT_DATA'));
        if (IS_AJAX) {
            $this->assign('is_ajax', '1');
        } else {
            //增加商品浏览次数
            M('goods')->where("id=$goodsId")->setInc("browse_num", 1, 60);
            //店中店信息
            $seller_id = $goodsModel->where("id=$goodsId")->getField('user_id');
            $sellerInfo = M('users')->field("user_name,avatar")->where("uid=$seller_id")->find();
            $company_name=M('users_seller')->where("user_id=$seller_id")->getField('company_name');
            //全部宝贝
            $num = $goodsModel->where("user_id=$seller_id")->count();
            //商家销量
            $volume = $goodsModel->where("user_id=$seller_id")->sum('online_sales_volume');
            //判断用户是否收藏商品
            $favNum = 0;
            if ($userId>0) {
                $fCondition['fuser_id'] = $userId;
                $fCondition['favorite_id'] = $goodsId;
                $fCondition['favorite_type'] = 0;
                $favNum = M('favorites')->where($fCondition)->count();
            }
            $pic=unserialize($list['list']['0']['original_img']); //商品图片
            $this->assign('num', $num);
            $this->assign('company_name', $company_name);
            $this->assign('volume', $volume);
            $this->assign('sellerInfo', $sellerInfo);
            $this->assign('seller_id', $seller_id);
             $this->assign('goodsId', $goodsId); //商品id
             $this->assign('favNum', $favNum);  //收藏数目
             $this->assign('pic', $pic);  //商品图片
             $this->assign('list', $list['list']['0']);  //商品的参数
        }
        $this->display();
    }

    //卖家商品列表页
    public function sellerGoodsAction(){
        //商品属性
        $pageType = I('pageType','all');
        $keywords  = I('keywords','');
        $seller_column_id = I('seller_column_id','');  //卖家自定义分类id
        if ($pageType == 'hot') {
            $condition['g.is_extension']=1;
        }elseif ($pageType == 'new') {
            $order ="add_time DESC";
        }
        $seller_id = I('id','');
        if (IS_AJAX) {
            $id = I('id','');
            $pageNum = I('p','');
            $this->assign("isAjax",1);
        }else {
            $seller_id = I('get.id','');
            $userModel =M('users');
            //卖家信息
            $sellerInfo = $userModel->field("avatar")->where("uid=$seller_id")->find();
            $info=M('users_seller')->field("company_name,address,mobile")->where("user_id=$seller_id")->find();
            //dump($list);exit;
            $this->assign('seller_id', $seller_id);
            $this->assign('info', $info);
            $this->assign('sellerInfo', $sellerInfo);
        }
        //卖家的商品信息
        $goodsModel=D('Common/Goods');
        $condition['g.user_id'] = $seller_id;
        if($keywords) {
            $condition['goods_title|goods_name'] = array('like', '%' . $keywords . '%');
            $pageCondition['keywords'] = $keywords;
        }
        if ($seller_column_id) {
            $condition['seller_column_id'] = $seller_column_id;
        }
        $list=$goodsModel->getSellerGoodsPageList1(1, 10,$order,  $condition, $condition);
        $this->assign('pageType', $pageType);
        if ($seller_column_id) {
            $this->assign('seller_column_id', $seller_column_id);
        }
        $this->assign('keywords', $keywords);
        $this->assign('pageCount', $list['pageCount']);
        $this->assign('list', $list['list']);
        //获取卖家的自定义分类
        $this->sellerGoodsCategoryAction($seller_id);
        $this->display();
    }

    //获取二级分类
    public function getSecondCategoryAction(){
        $c_id=I('c_id','');        //二级分类的parent_id
        $seller_id = I('seller_id','');     //卖家id
        $list=M('seller_column')->where(array('user_id' =>$seller_id ,'parent_id'=>$c_id ))->select();
        if ($list) {
            $this->ajaxInfoReturn(json_encode($list),"sometime we always success!",1);
        }else {
            $this->ajaxInfoReturn("","sometime we always fail!",0);
        }

    }

    /*获取卖家的自定义分类
    *@params        $user_id            int             卖家的id
    */
    public function sellerGoodsCategoryAction($user_id){
        $categoryList=M('seller_column')->where("user_id=$user_id")->select();
        $categoryList=treeAction($categoryList);        //处理所有分类数据
        //dump($categoryList);exit;
        $this->assign('categoryList',$categoryList);
    }

    //图片展示
    public function previewAction()
    {
        $id=I('id');
        $pic=M('goods')->where("id=$id")->getField('original_img');
        $pic=unserialize($pic);
        $this->assign('pic', $pic);
        $this->display();
    }

    //收藏/取消一手/二手
    public function collectAction()
    {
        $fuser_id = session("mobile.id");
        //$user_id = 18;
        $favorite_id = I('goods_id');
        $favorite_type = I('favorite_type', 0);
        if (empty($fuser_id)) {
            $this->ajaxInfoReturn("", "当前用户未登录！", 0);
            die();
        }
        $condition['fuser_id'] = $fuser_id;
        $condition['favorite_id'] = $favorite_id;
        $condition['favorite_type'] = $favorite_type;
        $favoritesNum = M('favorites')->where($condition)->count();
        if ($favoritesNum == 0) {
            //获取部分收藏信息
            if($favorite_type==1){
                //二手
                $info=M('ShGoods')->field('goods_title,goods_price as price,goods_thumb')->where(array('id'=>$favorite_id))->find();
            }else{
                //一手
                $info=M('Goods')->field('goods_title,price,goods_thumb')->where(array('id'=>$favorite_id))->find();
            }
            $data =array(
              'fuser_id'            =>  $fuser_id,
              'favorite_id'         =>  $favorite_id,
              'favorite_type'       =>  $favorite_type,
              'title'               =>  $info['goods_title'],
              'price'               =>  $info['price'],
              'goods_thumb'         =>  $info['goods_thumb'],
              'add_time'            =>  time()
            );
            $result=M('favorites')->add($data);
            if ($result) {
                 $this->ajaxInfoReturn('', "收藏成功！", 1);
            } else {
                 $this->ajaxInfoReturn('', "收藏失败！", 0);
            }
        } else {
            $result=M('favorites')->where($condition)->delete();
            $this->ajaxInfoReturn("", "已取消！", 1);
        }
    }
}
