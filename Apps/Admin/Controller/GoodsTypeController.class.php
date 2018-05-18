<?php
namespace Admin\Controller;
use Think\Controller;

class GoodsTypeController extends AjaxController{
    /*
     * 商品类型列表显示
     */
    public function goodsTypeListAction(){
      if(!parent::checkAdminRoleAction('goodsType_manage','view')){
          jscript_msg_tips('亲，您无权进行操作！');
      }
      $keywords = I('keywords');
      $pageNum = I('p',1,'strip_tags');
      if($keywords) {
          $condition['title|name'] = array('like', '%' . $keywords . '%');
          $pageCondition['keywords'] = $keywords;
      }
      $order = 'id ASC';
      $goodsModel=D('Admin/Goods');
      $result=$goodsModel->getGoodsTypePageList($pageNum,$order,$condition,$pageCondition);
      $this->assign('list',$result['list']);
      $this->assign('page',$result['show']);
      $this->display("GoodsType/goods_type_list");
    }


    /*
     * 检测用户名是否重名
     * id存在就是修改时的检测重名
     * id存在就是添加时的检测重名
     */
    public function like(){
        $name = I('post.name');
        $goods_type = M('goods_type');
        $res = $goods_type->where(['name'=>$name])->select();
        if($res){
            $data['data'] = '';
            $data['info'] = '该名称已存在';
            $data['status'] = 1;

            $this->ajaxReturn($data);
        }else{
             $data['data'] = '';
             $data['info'] = '该名称可用';
             $data['status'] = 0;
        }
    }



   /**批量删除商品类型
    */
   public function delAction(){
       $ids = I('ids');
       if(!parent::checkAdminRoleAction('goodsType_manage','delete')){
           jscript_msg_tips('亲，您无权进行操作！');
       }
       $jumpUrl = $_SERVER['HTTP_REFERER'];
       $goodsTypeModule  = M('goods_type');
       $condition['id'] = array('in',$ids);
       $result = $goodsTypeModule->where($condition)->delete();
       if($result!==false) {
           add_admin_log('del',session('admin_name').'成功删除了'.$result.'个商品类型!');
           jscript_msg("删除成功! ",$jumpUrl);
       }else{
           jscript_msg_tips("删除失败！");
       }
   }


  /**
   * 商品类型添加和修改
   */
  public function addAction(){
    if(!parent::checkAdminRoleAction('goodsType_manage','edit')){
        jscript_msg_tips('亲，您无权进行操作！');
    }
    $action  = I('action','add');
    $id = I('id');
    $dataArr = I('data');
    $goodsTypeModel = M('goods_type');
    $jumpUrl = I('post.returnUrl');
    if($action=='edit'){
        $res = $goodsTypeModel->data($dataArr)->where("id=$id")->save();
        if($res!==false){
            add_admin_log('edit',session('admin.admin_name').'成功修改了ID为'.$id.'的商品类型!');
            jscript_msg("修改成功! ",$jumpUrl);
        }else{
            jscript_msg("修改失败! ");
        }
    }else{
        $goodsTypeId = $goodsTypeModel->data($dataArr)->add();
        if($goodsTypeId>0){
            add_admin_log('add',session('admin.admin_name').'成功添加了ID为'.$id.'的商品类型!');
            jscript_msg("添加成功! ",$jumpUrl);
        }else{
            jscript_msg("修改失败! ");
        }
    }
  }

}
?>
