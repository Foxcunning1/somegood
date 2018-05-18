<?php
namespace Admin\Controller;
use Think\Controller;

class ShGoodsController extends BaseController{

    /*商品列表*/
    public function goodsListAction(){
        if(!parent::checkAdminRoleAction('sh_goods_manage','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $categoryId = I('category_id');
        $property = I('property','all','strip_tags');
        $keywords = I('keywords');
        $pageNum = I('p',1,'strip_tags');
        if($keywords) {
            $condition['goods_title|goods_name|goods_brief|seo_keywords'] = array('like', '%' . $keywords . '%');
            $pageCondition['keywords'] = $keywords;
        }
        //属性列表
        $property_list = array(
            'all' => '所有属性',
            'lock' => '待审核',
            'unlock' => '已审核',
            'hidden' => '不显示'
        );
        if($categoryId) {
            $catIds = D("Common/GoodsCategory")->getCategoryChildrenIds($categoryId);
            $condition['g.category_id'] = array("in",$catIds);
            $pageCondition['category_id'] = $categoryId;
            $this->assign('category_id',$categoryId);
        }
        //筛选属性
        if($property!="all"){
            $property1 = 0;
            switch($property){
                case 'lock':
                    $property1 = 0;
                    break;
                case 'unlock':
                    $property1 = 1;
                    break;
                case 'hidden':
                    $property1 = 2;
                    break;
            }
            $condition['status'] = $property1;
            $pageCondition['property'] = $property1;
        }
        //商品分类
        //获取商品所有分类缓存
        $category_list = require_once ("./Cache/category/category.cache.sort.php");//商品分类
        $goodsModule = D('Common/ShGoods');
        $order = 'id DESC';
        $result = $goodsModule->getGoodsPageList($pageNum,C('PAGE_SIZE'),$order,$condition,$pageCondition);
        $this->assign('category_list',$category_list);
        $this->assign('property', $property);
        $this->assign('property_list',$property_list);
        $this->assign('list',$result['list']);
        $this->assign('page',$result['show']);
        $this->display("ShGoods/goodslist");
    }

    /**批量删除商品
     * parm     string        $ids               导航ID字符串
     */
    public function delAction(){
        $ids = I('ids');
        if(!parent::checkAdminRoleAction('sh_goods_manage','delete')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $goodsModule  = M('sh_goods');
        $jumpUrl = $_SERVER['HTTP_REFERER'];
        $condition['id'] = array('in',$ids);
        $result = $goodsModule->where($condition)->delete();
        if($result!==false) {
            add_admin_log('del',session('admin_name').'成功删除了'.$result.'条商品类别!');
             jscript_msg("修改成功! ",$jumpUrl);
        }else{
            jscript_msg_tips("删除失败！");
        }
    }

    /**编辑商品信息
    * @parm  $id  商品id
    */
    public function editAction(){
        $action = I('action','add');//操作类型
        if(!parent::checkAdminRoleAction('sh_goods_manage','edit')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $id=I('id');
        $goodsModel=D('Common/ShGoods');
        $goodsConditionList = C('GOODS_CONDITION');
        //var_dump($info);exit;
        $category_list = require_once ("./Cache/category/category.cache.sort.php");//商品分类
        //城市列表
        $regionModel = D('Common/Region');
        if(F('Admin/City/province_list')){
            $provinceList = F('Admin/City/province_list');
        }else{
            $provinceList = $regionModel->getAreaList();
        }
        $cityList = array();
        $townList = array();
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        if(IS_POST){
            $jumpUrl = I('post.returnUrl');//回跳页面
            $dataArr = I('data');
            $imgArr = I('post.photo');//获取图片相册
            $imgRemarkArr = I('post.rphoto');//获取图片描述
            if(!$dataArr['goods_thumb']){
                $dataArr['goods_thumb'] = I('post.hidFocusPhoto');//商品封面缩略图
            }
            if($imgArr){
                $dataArr['original_img'] = serialize($imgArr)."||".serialize($imgRemarkArr);
            }
            if($action == 'edit') {
                $status = $goodsModel->update($id,$dataArr);
                if ($status !== false) {
                    add_admin_log('edit', session('admin.admin_name') . '成功更新了ID为' . $id . '的商品!');
                    jscript_msg("修改成功 ", $jumpUrl);
                } else {
                    jscript_msg_tips("修改失败！");
                }
            }else{
                $dataArr['user_id'] = I('post.user_Id',0);
                if($dataArr['user_id']>0) {
                    $gId = $goodsModel->add($dataArr);
                    if ($gId > 0) {
                        add_admin_log('add', session('admin.admin_name') . '成功增加了ID为' . $gId . '的商品!');
                        jscript_msg("添加成功 ", $jumpUrl);
                    } else {
                        jscript_msg_tips("添加失败！");
                    }
                }else{
                    jscript_msg("请选择用户!");
                }
            }
        }else {
            if($action=='edit'){
                $info = $goodsModel->getGoodsInfo($id);
                if($info['area_id']){
                    $areaInfo = $regionModel->getRegionInfo($info['area_id']);
                    if($areaInfo['pid']>0){
                        $cityInfo = $regionModel->getRegionInfo($areaInfo['pid']);
                        $townList = $regionModel->getAreaList($areaInfo['pid']);
                        $cityList = $regionModel->getAreaList($cityInfo['pid']);
                    }
                }
                //获得产品相册
                if($info['original_img']!=""){
                    $tempArr = explode('||',$info['original_img']);
                    $photoList = unserialize($tempArr[0]);
                    if(empty($tempArr[1])){
                        $tempArr[1] = "";
                    }
                    $remarkList = unserialize($tempArr[1]);
                }
            }
            $this->assign('action', $action);
            $this->assign('provinceId',$cityInfo['pid']);//省会ID
            $this->assign('cityId', $areaInfo['pid']);//城市id
            $this->assign('provinceList', $provinceList);
            $this->assign('cityList', $cityList);
            $this->assign('townList', $townList);
            $this->assign('status_list', array('待审核', '正常', '不显示'));
            $this->assign('id', $id);
            $this->assign('category_list', $category_list);
            $this->assign('goodsConditionList', $goodsConditionList);
            $this->assign('info', $info);
            $this->assign('plist', $photoList);//图片相册
            $this->assign('rlist', $remarkList);//图片描述
            $this->assign('returnUrl', $returnUrl);
            $this->display("ShGoods/goods_edit");
        }
    }


    /**更新商品信息
    * @parm  $id  商品id
    */
    public function updateInfoAction(){
        $data=I('data');
        $returnUrl=$_SERVER['HTTP_REFERER'];
        //var_dump($data);exit;
        $id=$data['id'];
        M('sh_goods')->where("id=$id")->save($data);
        redirect($returnUrl);

    }



    /** Ajax验证商品类型调用别名是否重复
     * parm             $param          string            get的数据
     * parm             $old_name        string           初始数据
     */
    public function nameValidate(){

        $old_name           = I('old_name');
        $param              = I('param');
        if($param==""){
            $this->ajaxReturn(0,"参数有误",0);
        }else{
            if(strtolower($old_name)==strtolower($param)){
                $this->ajaxReturn(0,"该名称可使用",'y');
            }else{
                //验证导航菜单是否已占用
                $advPositionList = M('sh_goods');
                $condition = array('goods_name'=>$param);
                if($advPositionList->where($condition)->count()>0){
                    $this->ajaxReturn(0,"该名称已使用，请重新填写！",'n');
                }else{
                    $this->ajaxReturn(0,"该名称可使用",'y');
                }
            }
        }
    }

}
?>
