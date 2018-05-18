<?php
namespace Admin\Controller;
use Think\Controller;

class GoodsController extends AjaxController{

  /*商品列表*/
  public function goodsListAction(){
    if(!parent::checkAdminRoleAction('goods_manage','view')){
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
        $condition['category_id'] = $categoryId;
        $pageCondition['category_id'] = $categoryId;
        $this->assign('category_id',$categoryId);
    }
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
    $category_list = D('Admin/Goods')->getGoodsCategoryTreeListAction();//商品分类
    $GoodsModule = D('Admin/Goods');
    $order = 'id DESC';
    //var_dump($pageCondition);
    $result = $GoodsModule->getGoodsPageList($pageNum,$order,$condition,$pageCondition);
    $this->assign('category_list',$category_list);
    $this->assign('property', $property);
    $this->assign('property_list',$property_list);
    $this->assign('list',$result['list']);
    $this->assign('page',$result['show']);
    $this->display("Goods/goodslist");
  }

    /**批量删除商品
     * parm     string        $ids               导航ID字符串
     */
    public function delAction(){
        $ids = I('ids');
        if(!parent::checkAdminRoleAction('goods_manage','delete')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $goodsModule  = M('goods');
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
      $id=I('id');
      $goodsModel=D('Common/Goods');

      $info=$goodsModel->getGoodsInfo($id);
      //var_dump($info);exit;
      $category_list=M('goods_category')->field('id,title')->select();
      $this->assign('status_list',array('待审核','正常','不显示'));
      $this->assign('id',$id);
      $this->assign('category_list',$category_list);
      $this->assign('info',$info);
      $this->display("Goods/goods_edit");
    }


    /**更新商品信息
    * @parm  $id  商品id
    */
    public function updateInfoAction(){
        $data=I('data');
        $returnUrl=$_SERVER['HTTP_REFERER'];
        //var_dump($data);exit;
        $id=$data['id'];
        M('goods')->where("id=$id")->save($data);
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
                $advPositionList = M('goods');
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
