<?php
namespace Mobile\Controller;
use Think\Controller;
/*
*首页
*/
class SearchController extends BaseController {

   //列表
    public function listAction(){
        $catid = I('get.catid',0);//商品分类id
        $keywords=I('get.keywords','');//商品关键字
        $shopKeywords=I('keywords');
        if ($keywords==''){
            $keywords=$shopKeywords;
        }
        $pageNum = I('get.p',1);
        $isAjax = I('get.isajax',0);//数据获取类型,0为正常分页，1为ajax获取
        $order = I('get.order',0);//数据获取类型，0为时间降序，1为时间升序，2为距离
        $searchType = I('get.type',0);//搜索类型,0为商品，1为店铺
        if($keywords) {//关键字查询
            //$keyArr = explode(' ',$keywords);
            //进行自动分词处理
            vendor("Pscws.phpanalysis");
            \PhpAnalysis::$loadInit = false;
            $pa = new \PhpAnalysis('utf-8', 'utf-8', false);
            $pa->LoadDict();
            $pa->SetSource($keywords);
            $pa->StartAnalysis( false );
            $keyArr = $pa->GetFinallyList();
            foreach($keyArr as $v){
                $map['g.goods_title'][] = array('like',"%".$v."%");
                $map['g.goods_title'][] = 'or';
                $map['_logic'] = 'and';
                $map1['_logic'] = 'and';
                $pageCondition['keywords'] = $keywords;
                /*记录搜索关键词*/
                /*
				 * 1. 判断输入的关键词
				 * 2. 判断ip是否一致
				 * 3. 判断关键词的类型
				 * 4. 判断手否有用户名
				 **/
                $user_ip = get_client_ip();  //获取用户的ip
                $keywordModel = M('keywords');	//实例化关键词表
                $keywordUserModel = M('keywords_user');	//实例化用户表
                $where['keyword'] = $v;	//查询条件
                $keywordInfo = $keywordModel -> where($where) -> find();	//用关键词查询数据库中对应的数据		$keyword_d   数据数组
                $keywordStr = $keywordInfo['keyword'];  //获取到数据库中的关键词
                $keywordList = array();
                $keywordUsersList = array();
                if ($v==$keywordStr) {	//判断输入的关键字是否与查询的关键字相同
                    $w_one = array(
                        'keyword'=>$v,
                    );
                    $conditions = $keywordModel -> where($w_one) -> find();
                    if ($conditions) {
                        $w['id'] = $conditions['id'];
                        $keywordModel->where($w)->setInc('count');//字段自动增加1
                        $userKeywordInfo = $keywordUserModel -> where('kid='.$conditions['id']) ->find();
                        if ($user_ip != $userKeywordInfo['user_ip']) {	//判断搜索关键词的IP地址
                            $isCurrentUser = false;//定义关键词是否是当前用户
                            if (!session('mobile.id')) {//判断是否登录，如果登录则把当前uid存入
                                $keywordUsersList['user_id'] = 0;//匿名用户
                            }else{
                                if(session('mobile.id')==$userKeywordInfo['user_id']){
                                    $isCurrentUser == true;
                                    $keywordUserModel->where('id='.$userKeywordInfo['id'])->setInc('user_count');//字段自动增加1
                                }else{
                                    $keywordUsersList['user_id'] = session('mobile.id');
                                }
                            }
                            if(!$isCurrentUser){
                                $keywordUsersList['first_time'] = time();
                                $keywordUsersList['last_time'] = time();
                                $keywordUsersList['user_ip'] = $user_ip;
                                $keywordUsersList['kid'] = $conditions['id'];
                                $keywordUsersList['user_count'] = 1;
                                $keywordUserModel->add($keywordUsersList);//添加数据到数据库keywords_user
                            }
                        }else {
                            //用户两种情况处理
                            $isCurrentUser = false;//定义关键词是否是当前用户
                            if (session('mobile.id')) {
                                if(session('mobile.id')!=$userKeywordInfo['user_id']){
                                    $isCurrentUser = true;
                                    $keywordUsersList['user_id'] = session('mobile.id');
                                }
                            }
                            if($isCurrentUser){
                                $keywordUsersList['first_time'] = time();
                                $keywordUsersList['last_time'] = time();
                                $keywordUsersList['user_ip'] = $user_ip;
                                $keywordUsersList['kid'] = $conditions['id'];
                                $keywordUsersList['user_count'] = 1;
                                $keywordUserModel->add($keywordUsersList);//添加数据到数据库keywords_user
                            }else{
                                $keywordUserModel->where('id='.$userKeywordInfo['id'])->setInc('user_count');//字段自动增加1
                            }

                        }
                    }else {
                        $keywordList['date'] = time();
                        $keywordList['keyword'] = $v;
                        $keywordList['count'] = 1;
                        if($searchType==1){
                            $keywordList['search_type'] = 1;//搜索类型
                        }
                        vendor('Pinyin.Pinyin');//引入中文转拼音类
                        //引入第三方中文转拼音
                        $Pinyin = new \Pinyin();
                        $keywordPinyin = $Pinyin::getPinyin($v);
                        $keywordList['prod_key'] = substr($keywordPinyin, 0, 1 );
                        $keywordList['alias_name'] = $keywordPinyin;
                        $kid = $keywordModel->add($keywordList);//添加数据到数据库keywords
                        $userKeywordInfo = $keywordUserModel -> where('kid='.$kid['id']) ->find();
                        if ($user_ip != $userKeywordInfo['user_ip']) {
                            if (session('mobile.id')) {
                                $keyword_users['user_id'] = session('mobile.id');
                            }
                            $keywordUsersList['first_time'] = time();
                            $keywordUsersList['last_time'] = time();
                            $keywordUsersList['user_ip'] = $user_ip;
                            $keywordUsersList['kid'] = $kid['id'];
                            $keywordUsersList['user_count'] = 1;
                            $keywordUserModel->add($keywordUsersList);//添加数据到数据库keywords_user
                        }else {
                            $keywordUsersList['last_time'] = time();
                            $keywordUserModel->where(array("id"=>$userKeywordInfo['id']))->data($keywordUsersList)->save();
                            $keywordUserModel->where(array("id"=>$userKeywordInfo['id']))->setInc('user_count');//字段自动增加1
                        }
                    }
                }else {
                    $keywordList['date'] = time();
                    $keywordList['keyword'] = $v;
                    $keywordList['count'] = 1;
                    if($searchType==1){
                        $keywordList['search_type'] = 1;//搜索类型
                    }
                    vendor('Pinyin.Pinyin');//引入中文转拼音类
                    //引入第三方中文转拼音
                    $Pinyin = new \Pinyin();
                    $keywordPinyin = $Pinyin::getPinyin($v);
                    $keywordList['prod_key'] = substr($keywordPinyin, 0, 1 );
                    $keywordList['alias_name'] = $keywordPinyin;
                    $kid = $keywordModel->add($keywordList);//添加数据到数据库keywords
                    if (session('mobile.id')) {
                        $keywordUsersList['user_id'] = session('mobile.id');
                    }
                    $keywordUsersList['first_time'] = time();
                    $keywordUsersList['last_time'] = time();
                    $keywordUsersList['user_ip'] = $user_ip;
                    $keywordUsersList['user_count'] = 1;
                    $keywordUsersList['kid'] = $kid;
                    $keywordUserModel->add($keywordUsersList);//添加数据到数据库keywords_user
                }
            }
        }
        if($catid>0&&$searchType!=1){//类别id查询
            $goodsCategoryModel = D('Common/GoodsCategory');
            $ids = $goodsCategoryModel->getCategoryChildrenIds($catid);
            $map['g.category_id'] = array("in",$ids);
            $pageCondition['catid'] = $catid;
            $map1['category_id'] = array("in",$ids);;
        }
        $pageCondition['order'] = $order;//排序
        $pageCondition['area_id'] = $areaId;//区域id
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
        $result = $GoodsModule->getSellerGoodsPageList($pageNum, 10, $orderStr, $map, $pageCondition );
        //var_dump($result);
        if(IS_AJAX){//ajax获取数据
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
            $this->assign("web_title", "搜索“".$keywords."”商品的结果");//页面标题
            $this->assign("keywords", $keywords);
            $this->assign("bigCatId", $bigCatId);
            $this->assign("catid", $catid);
            $this->assign("categoryList", $goodsCategoryList);
            $this->assign("isAjax", $isAjax);
            $this->assign("order", $order);
            $this->assign('list', $result['list']);
            $this->assign('pageCount', $result['pageCount']);//分页总数
            $this->assign('empty', C('NOT_DATA'));
            $this->display();
        }
    }
}
