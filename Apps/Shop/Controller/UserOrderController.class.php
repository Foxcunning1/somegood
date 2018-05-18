<?php
namespace Shop\Controller;
use Think\Controller;
//商城订单中心类
class UserOrderController extends Controller {

		//订单列表
	public function orderlistAction(){
		// is_son   0已拆主单  1子单  2 未拆主单
          $status=I('status','100');
          $pageNum = I('p','1');
          $user_id=session('shop.id');
          $map['o.uid'] = $user_id;
          if($status == 100) {
              $map['o.status'] = array('in','0,1,2,3,6,99');
              $map['_string'] = '!(o.is_son=1 and o.status=0) and !( o.is_son=0 and o.status!=0)';
              // !(o.is_son=1 and o.status=0)
          }elseif ($status == 0) {
              //待付款
              $map['o.status'] = $status;
              $map['_string'] = 'o.is_son=0 OR o.is_son=2';
          }elseif ($status == 1 || $status == 2 ) {
              $map['o.status'] = $status;
              $map['_string'] = 'o.is_son=1 OR o.is_son=2';
              $pageCondition['status']=$status;
              //$map['o.is_son'] = array('neq',1);
          }elseif ($status == 3) {
              $map['_string'] = '(o.is_son=1 OR o.is_son=2) and (o.status=3 or o.status=6)';
          }else {
			  $this->error("非法操作!",U("Mobile/Users/index"),2);
          }
          $orderModule = D('Common/Order');
          //获取这个根据order_sn分组统计goods_thumb的数组
          $list = $orderModule->getOrderPageListPC($pageNum,$map,$pageCondition);
          foreach ($list['list'] as $key => $value) {
              $list['list'][$key]['goods_thumb'] = explode(',',$value['tb']);
          }
		  //dump($list['list']);exit;
          //订单物流信息
          $callbackurl=C('MOBILE_URL').__SELF__;
          $this->assign('callbackurl',$callbackurl);
          if (IS_AJAX) {
              $this->assign("isAjax",1);
              $this->assign('list',$list['list']);
          }else{
              $this->assign('pageCount',$list['counts']);
              $this->assign('status',$status);
              $this->assign('list',$list['list']);
			  $this->assign('page',$list['page']);
          }
          $this->display();
	}


	public function detailAction(){
		$order_sn=I('order_sn');
        $isWho=I('isWho','');
        $OrderModel=D('Common/Order');
        $user_id=session('shop.id');
        //获取订单信息
        $map['o.order_sn']=$order_sn;
        $map['o.uid'] = $user_id;
        $res=$OrderModel->selectOrderInfoById($map);
        //dump($res);exit;
        $address_id=$res['list2']['address_id'];
        //订单物流信息
        $type=$res['0']['code'];
        $postid=$res['0']['logistics_sn'];
        $callbackurl=C('MOBILE_URL').__SELF__;
        $url="https://m.kuaidi100.com/index_all.html?type=$type&postid=$postid&callbackurl=$callbackurl";
        $this->assign('url',$url);
        $this->assign('isWho',$isWho);
        $this->assign('order_sn',$order_sn);
        $this->assign('status',$res['0']['status']);
        $this->assign('address',$address);
        $this->assign('info',$res['0']);
        $this->display();
	}
}
