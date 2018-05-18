<?php
namespace Admin\Controller;
use Think\Controller;

/*
*网站后台订单管理
*/

class OrderController extends AjaxController{

  //订单管理列表
  public function indexAction(){
    if(!parent::checkAdminRoleAction('order_list_manage','view')){
      jscript_msg_tips('亲，您无权进行操作！');
    }
      $status_list = array(
        '0' => '待付款',
        '1' => '已付款',
        '2' => '待收货',
        '3' => '已确认',
        '4' => '已删除',
        '5' => '已作废',
        '6' => '已完成',
        '99' => '退货'
    );
    $pageNum = I('p',1,'strip_tags');   //当前页码
    $isUsed = I('isUsed',2,'strip_tags');  //一手二手订单参数
    $status=I('status','100');    //订单状态
    $keywords=I('keywords','');    //搜索关键字
    $orderGoodsModule = M('order_goods');
    if ($isUsed == 2) {
        $map['_string'] = 'is_used=1 OR is_used=0';
    }else {
        $condition['is_used'] = $isUsed;
    }
    if($keywords) {
        $condition['order_sn|mobile|user_name'] = array('like',  $keywords . '%');
        $pageCondition['keywords'] = $keywords;
    }
    if($status != 100) {
			$condition['status'] = $status;
      $pageCondition['status'] = $status;
		}
    $orderModel=D('Admin/Order');
    $result=$orderModel->getOrderPageList($pageNum,$condition,$pageCondition);
    $this->assign('isUsed',$isUsed);
    $this->assign('status',$status);
    $this->assign('status_list',$status_list);
    $this->assign('page',$result['show']);
    $this->assign('list',$result['list']);
    $this->display("Order/index");
  }

  //公司后台 pc端结算管理
  public function settlementAction(){
    $pageNum = I('p',1);
    $status  =  I('status',99);
    $keywords=I('keywords','');
    $condition['order_sn']=array('like','%'.$keywords.'%');
    if ($status == '99') {
        $condition['_string']='status = 3 or status = 6';
        $pageCondition['_string'] = 'status = 3 or status = 6';
    }else {
        $condition['status']=$status;
        $pageCondition['status']=$status;
    }
    $pageCondition['keywords'] = $keywords;
    $orderModel=D('Common/Order');
        $res=$orderModel->PCSettlement($pageNum,$condition,$pageCondition);
        S('company_settlement',$res,1800);
    $this->assign('status',$status);
    $this->assign('list',$res);
    $this->display();
  }

  //批量结算
  public function someSettlementAction(){
      $orderSn = I('orderSn');
      foreach ($orderSn as $key => $v) {
          $order_sn = $v;
          $map['o.order_sn']=$v;
          $orderModel=D('Common/Order');
          $res=$orderModel->addSettlementMoney($map,$order_sn);
          if ($res) {
              add_admin_log('edit',  session('admin_name').'成功结算了订单'.$order_sn.'!');
              $this->ajaxInfoReturn('','结算成功!',1);
          }else {
              $this->ajaxInfoReturn('','结算失败!',0);
          }
      }
  }

  //订单结算添加数据
  public function addSettlementDataAction(){
      $user_id=session('mobile.id');
      $order_sn=I('order_sn');
      $map['o.order_sn']=$order_sn;
      //$map['_string'] = 'o.is_son=1 OR o.is_son=2';
      $orderModel=D('Common/Order');
      $res=$orderModel->addSettlementMoney($map,$order_sn);
      if ($res) {
          add_admin_log('edit',  session('admin_name').'成功结算了订单'.$order_sn.'!');
          $this->ajaxInfoReturn('','结算成功!',1);
      }else {
          $this->ajaxInfoReturn('','结算失败!',0);
      }
      //$this->redirect('User/index');
  }

  //订单设置
  public function orderDetailAction(){
      $id=I('id','');
      $orderModel=D('Admin/Order');
      $marketOrderModel=D('Common/Order');
      $map['o.id']=$id;
      $res=$marketOrderModel->selectOrderInfoById($map);
      //var_dump($res);exit;
      $logistics=M('logistics')->field('code,id,name')->select();
      $this->assign('logistics',$logistics);
      $this->assign('is_son',$res['0']['is_son']);
      $this->assign('list',$res['0']);
      $this->assign('goods',$res['0']['info']);
      //var_dump($res['0']['info']);
      $this->display("Order/detail");
  }

  //结算管理
  public function configAction(){
    $this->display("Order/order_config");
  }

  //更新订单价格
  public function updateOrderAction(){
    if(!parent::checkAdminRoleAction('order_manage','edit')){
      jscript_msg_tips('亲，您无权进行操作！');
    }
    $order_sn=I('order_no');
    $editStatus=I('edit_type');
    $info = array('status' => '1', );
    switch ($editStatus) {
        case 'edit_original_price':
        $remark=I('remark');
        $totalMoney=I('total_money');
        $data['money']=$remark;
        $result = M('Order')->where(array('order_sn' => $order_sn))->save(array('money' => $remark,'original_price'=>$remark));
        add_admin_log('edit',  session('admin_name').'成功更新了订单号为'.$order_sn.'的订单价格!');
          $this->ajaxInfoReturn($info,'JSON');
          break;
        case 'order_cancel':
        $result = M('Order')->where(array('order_sn' => $order_sn))->save(array('status' => '5'));
        add_admin_log('edit',  session('admin_name').'成功取消了订单'.$order_sn.'!');
          $this->ajaxInfoReturn($info,'JSON');
          break;
        case 'edit_order_logistics_sn':
          $remark=I('remark');
          $result = M('order_logistics')->where(array('order_sn' => $order_sn))->save(array('logistics_sn' => $remark));
          add_admin_log('edit',  session('admin_name').'成功更新了订单号为'.$order_sn.'的物流单号!');
          $this->ajaxInfoReturn($info,'JSON');
          break;
        case 'logistics_type':
          $remark=I('remark');
          $result = M('order_logistics')->where(array('order_sn' => $order_sn))->save(array('type' => $remark));
          add_admin_log('edit',  session('admin_name').'成功更新了订单号为'.$order_sn.'的物流类型!');
          $this->ajaxInfoReturn($info,'JSON');
          break;
      default:
        # code...
        break;
    }
  }

  /**批量删除栏目
   * parm     string        $ids               导航ID字符串
   */
   public function delAction(){
   //判断权限
   if(!parent::checkAdminRoleAction('order_list_manage','delete')){
     jscript_msg_tips('亲，您无权进行操作！');
   }
   $ids             = I('ids');
   $returnUrl =  $_SERVER['HTTP_REFERER'];;
   //判断id是数组还是一个数值
   if (is_array($ids)) {
     $ids=implode(',',$ids);
   }
   //var_dump($returnUrl);exit;
   $condition['id'] = array('in',$ids);
   $data['status']='4';
   $result2 = M('Order')->where($condition)->save($data);
   redirect($returnUrl);

   }

}
