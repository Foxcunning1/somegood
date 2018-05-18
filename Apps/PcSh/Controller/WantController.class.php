<?php
/**
 * Created by PhpStorm.
 * User: ZHY
 * Date: 2017/8/14
 * Time: 18:34
 */
namespace Ershou\Controller;
use Think\Controller;

class WantController extends BaseController{
    //求购详情页
    public function detailAction(){
        $id = I('id');//详情ID
        $wantModel = D("Common/Want");
        if($id>0){
            $wantModel->where(array("w_id"=>$id))->setInc("browser_num",1);
        }
        $info = $wantModel->getWantInfo($id);
        $this->assign('info', $info);
        $this->assign('web_title', "寻宝详情");
        $this->display();
    }
    //求购列表
    public function listAction(){
        $pageNum = I('p',1,'strip_tags');
        $area_id=I('area_id',0);//区域ID
        $cate_id=I('cate_id',0);//商品类别ID
        $order = I('order',0);//排序
        $is_ajax=I('is_ajax',0);//是否AJAX,0为正常,1为ajax
        $wantModel=D('Common/want');

        //求购信息状态 0为未审核,1为正常
        $condition['status']=1;
        $pageCondition['status']=1;

        $condition['w_updatatime']=array('gt'=>strtotime(time(),""));
        //区域条件
        if($area_id > 0){
            $condition['w_region']=$area_id;
            $pageCondition['w_region']=$area_id;
            $area_name=M('region')->field('name')->where(array('id'=>$area_id))->find()['name'];
            $this->assign('area_name',$area_name);
        }
            $this->assign('area_id',$area_id);
        //商品类别条件
        if($cate_id > 0){
            $condition['w_cateid']=$cate_id;
            $pageCondition['w_cateid']=$cate_id;
            $cate_name=M('goodsCategory')->where(array('id'=>$cate_id))->find()['title'];
            $this->assign('cate_name',$cate_name);
        }
            $this->assign('cate_id',$cate_id);
        //仅获取未过期的求购信息
        $condition['w_overtime']=array('gt',time());
        $pageCondition['w_overtime']=array('gt',time());
        //排序条件
        if ($order==0){
            //分页获取
            $result=$wantModel->getWantPageList($pageNum,'w_updatetime DESC',$condition,$pageCondition);
            $this->assign('a_o',0);
        }else{
            //分页获取
            $result = $wantModel->getWantPageList($pageNum, 'w_updatetime ASC', $condition, $pageCondition);
            $this->assign('a_o',1);
        }

        if($is_ajax){
            $this->ajaxInfoReturn($result,'',1);
        }else{
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
            if($cate_id>0){
                $catInfo = D("Common/GoodsCategory")->getCategoryInfo($cate_id);
                $tempArr = explode(",",$catInfo['class_list']);
                if(count($tempArr)>1){
                    $bigCatId = $tempArr[1];
                }
            }
            //区域列表
            //根据城市id获取区域信息
            $regionModel = D("Common/Region");
            $areaList = $regionModel->getAreaList(cookie("city_id"));
            $this->assign('catid',$cate_id);
            $this->assign('bigCatId',$bigCatId);
            $this->assign('order',$order);
            $this->assign('categoryList',$goodsCategoryList);
            $this->assign('areaList',$areaList);
            $this->assign('list',$result['list']);
            $this->assign('pageCount',$result['pageCount']);
            $this->display('list');
        }
    }
}