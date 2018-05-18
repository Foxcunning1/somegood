<?php
namespace Admin\Controller;
use Think\Controller;
/*
*购物车管理
*/
class CartController extends AjaxController{

  //购物车信息管理
  public function indexAction(){
    if(!parent::checkAdminRoleAction('cart_manage','view')){
      jscript_msg_tips('亲，您无权进行操作！');
    }
    $pageNum = I('p',1);
    $keywords = I('keywords');
    if($keywords){
      $condition['goods_title'] = array('like', '%' . $keywords . '%');
      $pageCondition['keywords'] = $keywords;
    }
    $CartModel= D('Common/Cart');

    $cartinfo = $CartModel->getCartPageList($pageNum,$order,$condition,$pageCondition);
    $this->assign('page',$cartinfo['show']);
    $this->assign('list',$cartinfo['list']);
    $this->display("Cart/cartList");
  }


  public function testAction(){
    $res=M('goods_category')->field("id,concat(concat('20170829/',name),'.jpg') as img_url")->select();
        foreach ($res as $key => $v) {
          $id=$v['id'];
            M('goods_category')->where("id=$id")->setField('img_url',$v['img_url']);
        }
    var_dump($res);
  }




}
