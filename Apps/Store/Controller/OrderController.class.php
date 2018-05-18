<?php
/**
 * Created by PhpStorm.
 * User: ZHY
 * Date: 2017/8/14
 * Time: 18:34
 */
namespace Store\Controller;
use Think\Controller;

class OrderController extends BaseController{

    //店铺订单
    public function indexAction(){
        $orderModel=D('Common/Order');
        $pageNum=I('p',1);
        $pageType=I('pageType',1);//列表类型,1待发货,2待完成,3已完成,0代付款
        if (I('keywords')){
           $condition['o.order_sn|a.consignee|a.tel']=array('like','%'.I('keywords').'%');
        }
        $result=$orderModel->getStoreOrderPageList($pageNum,'',$pageType,$condition);
        foreach ($result['list'] as &$v){
            $v['goods_thumb']=explode(',',$v['goods_thumb']);
            $v['goods_title']=explode(',',$v['goods_title']);
        }
        /*//获取物流/快递列表
        $logistics_list=M('logistics')->order('sort_num ASC')->select();
        $this->assign('logistics_list',$logistics_list);*/
        $this->assign('list',$result['list']);
        $this->assign('pageShow',$result['show']);
        $this->assign('pageType',$pageType);
        $this->assign('keywords',I('keywords'));
        $this->display();
    }

    //订单详情
    public function orderDetailAction(){
        R("Mobile/Users/orderDetail");
    }
    //物流信息
    public function orderLogisticsAction(){
        $typeNu=I('nu');
        if(S($typeNu)){
            $logistics_info=S($typeNu);
        }else{
            $orderModel = D('Common/Order');
            //获取订单信息
            $logistics_info = $orderModel->getLogisticsInfoByOrderSn($typeNu);
        }
        $this->assign('logistics_info',$logistics_info);
        $this->assign('query_mode',C('LOGISTICS_INFO_QUERY_MODE'));
        $this->display();
    }

    //发货
    public function deliverGoodsAction(){
        if (IS_AJAX){
            $data=I('data');
            //验证订单当前状态
            $orderModel=M('market_order');
            $status=$orderModel->where(array('store_id'=>session('store.store_id'),'order_sn'=>$data['order_sn'],'delivery_way'=>1))->getField('status');
            if ($status==1){
                $re=M('market_order_logistics')->add($data);
                //变成订单发货状态
                $save['status']=2;
                $orderModel->where(array('order_sn'=>$data['order_sn']))->save($save);
                if ($re){
                    $this->ajaxInfoReturn('','发货成功',1);
                }else{
                    $this->ajaxInfoReturn('','信息有误,请重试',0);
                }
            }else{
                $this->ajaxInfoReturn('','订单状态不正确',0);
            }
        }else{
            //获取物流/快递列表
            $logistics_list=M('logistics')->order('sort_num ASC')->select();
            $this->assign('logistics_list',$logistics_list);
            $this->assign('returnUrl',$_SERVER['HTTP_REFERER']);
            $this->display();
        }
    }
    //订单结算统计页面  店铺
    public function settlementAction(){
    	$marketOrderModel=D('Common/Order');
    	$store_id=session('store.store_id')?session('store.store_id'):session('store.store_id');
      $store_id = 9;
    	$pageNum=I('p','1');
    	$keywords=I('keywords','');
    	$pageCondition['keywords'] = $keywords;
    	$action=I('action','all');
    	//结算列表
    	switch ($action) {
    		case 'all':
    		          $map['_string'] = 'o.status=3 or o.status=6';
    		break;
    		case 'ing':
    		          $map['o.status'] = 3;
    		break;
    		default:
    		          $map['o.status'] = 6;
    		break;
    	}
    	$map['mog.store_id'] = $store_id;
    	$map['o.is_son'] = array('neq','0');
    	$map['o.order_sn'] = array('like',  $keywords . '%');
    	$list=$marketOrderModel->PCStoreSettlement($pageNum,$map,$pageCondition,$store_id);
    	//var_dump($list);exit;
    	//昨日进账    今日进账
    	$rs=$marketOrderModel->dayCounts($store_id);
    	$this->assign('action',$action);
    	$this->assign('countsNum',$rs);
    	$this->assign('list',$list);
    	$this->display();
    }
    //改价
    public function editPriceAction(){
        if (!IS_AJAX){
            $this->tips('提交方式错误',1,500,$_SERVER['HTTP_REFERER']);
        }else {
            $order_sn = I('order_sn');
            $edit_price = (float)I('edit_price');
            $orderModel = D('Common/Order');
            $info = $orderModel->selectOrderDetail($order_sn);
            $goods_info = $info['list1'][0];
            $order_info = $info['list2'];
            if ($order_info['status']!=0 || $order_info['delivery_way'] !=0 ||$order_info['is_son'] !=2||$goods_info['store_id']!=session('store.store_id')){
                $this->ajaxInfoReturn('','订单状态不正确,请重试',0);
            }elseif ($edit_price<$goods_info['goods_price']){
                $this->ajaxInfoReturn('','不得低于商品底价',0);
            }else{
                //修改价格
                $data['money']=$edit_price;
                $re=M('market_order')->where(array('order_sn'=>$order_sn))->save($data);
                if ($re!==false){
                    $this->ajaxInfoReturn('','改价成功',1);
                }else{
                    $this->ajaxInfoReturn('','改价失败',0);
                }
            }
        }
    }
}
