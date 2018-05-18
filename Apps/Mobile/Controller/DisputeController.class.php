<?php
/*售后类
 *
 * */
namespace Mobile\Controller;
use Think\Controller;
class DisputeController extends MemberController {

//售后服务列表
public function afterSaleListAction(){
	$this->display();
}

//用户申请售后
public function wirelessApplyAction(){

	$this->display();
}

//用户提交物流信息
public function logisticsSubmitAction(){
	$this->display();
}

//售后进度查询
public function afterSaleSelectAction(){
	$this->display();
}




}
