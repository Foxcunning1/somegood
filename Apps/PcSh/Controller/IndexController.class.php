<?php
namespace Ershou\Controller;
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
        $hotCategoryList = S('hotCategoryList');
        if (empty($hotCategoryList)) {
            $goodsCategoryModel = D('Common/GoodsCategory');
            $hotCategoryList = $goodsCategoryModel->getTopCategoryList(8,array("is_index"=>1,"is_sh"=>1));
            S('hotCategoryList',$hotCategoryList,300);
        }
        $regionModel = D("Common/Region");
        $cityId = cookie('city_id');
        $condition['is_rec']  = 1;//商品推荐
        $condition['g.status']  = 1;//商品状态
        $gOrderStr = "update_time desc, id desc";//商品排序
        $areaIds = $regionModel->getChildrenIds($cityId);
        $condition['g.area_id'] = array("in",$areaIds);
        if($regionModel->isExist()){//用户位置城市与选择城市一致
            $gOrderStr = "ACOS(SIN((".session("lat")." * 3.1415) / 180 ) *SIN((g.lat * 3.1415) / 180 ) + COS((".session("lat")." * 3.1415) / 180 ) 
                        * COS((g.lat * 3.1415) / 180 ) *COS((".session("lng")."* 3.1415) / 180 - (g.lng * 3.1415) / 180 ) ) * 6380 asc";
        }else{
            $subQuery = M('region')->alias("r")->field('id')->where(array("r.id"=>$cityId))->order("id asc")->buildSql();
            $condition['g.area_id'] = array('in',$subQuery,'exp');
        }
        //同城求购
        $wantModel = D("Common/Want");
        $curTime = time();
        $wCondition['w_overtime'] = array("gt",$curTime);
        $wCondition['status'] = 1;
        $wCondition['w_region'] = array("in",$areaIds);
        $newWantList = $wantModel->getTopWantList(10,$wCondition);
        $goodsModel = D("Common/ShGoods");
        $recGoodsList = $goodsModel->getTopGoodsList(10, $gOrderStr, $condition);//商品推荐
        $this->assign('web_title',"3好连锁店");
        $this->assign('bannerList',$bannerList);
        $this->assign('newWantList',$newWantList);
        $this->assign('recGoodsList',$recGoodsList);
        $this->assign('hotWord',$hotWords);
        $this->assign('hotCategoryList',$hotCategoryList);
        $this->display();
    }
    //批量导入县去对应的街道或乡镇
    public function batchImportRegionData(){
        $areaModel = M("area");
        $regionModel = M("region");
        vendor('Pinyin.Pinyin');//引入中文转拼音类
        //引入第三方中文转拼音
        $Pinyin = new \Pinyin();
        $map['level'] = 3;
        $regionList = $regionModel->where($map)->order('pid asc,id asc')->select();
        foreach($regionList as $key=>$val){
            $copyList = array();
            $condition = array();
            $data = array();
            
            if(($val['pid']==1707)||($val['pid']==1822)||($val['pid']==2306)||$val['pid']==3317){//直辖县级
                
            }else{

            }
            $condition['areaname'] = $val['name'];
            $count = $areaModel->where($condition)->count();
            if($count>0){

            }
            $likeRegionList = $areaModel->where($condition)->select();
            $data = array();
            foreach($copyList as $k=>$value){
                $tempArr = array();
                $tempArr['pid'] = $val['id'];
                $tempArr['shortname'] = $value['shortname'];
                $tempArr['name'] = $value['areaname'];
                $tempArr['lng'] = $value['lng'];
                $tempArr['lat'] = $value['lat'];
                $tempArr['level'] = 4;
                $tempArr['merger_name'] = $val['merger_name'].",".$value['areaname'];
                $keywordPinyin = $Pinyin::getPinyin($value['shortname']);
                $tempArr['pinyin'] = $keywordPinyin;
                $tempArr['first'] = strtoupper(substr($keywordPinyin, 0, 1 ));//根据拼音获取首字母，并转成大写
                $tempArr['code'] = $val['code'];
                array_push($data,$tempArr);
            }
            $importCount = $regionModel->addAll($data);
            echo $val['name']."下导入了<b style=\"color:red;\">".$importCount."</b>\r\n";
        }

    }
}
