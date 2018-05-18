<?php
//计划任务
namespace Home\Controller;
use Think\Controller;
class CronController extends Controller {

    public function indexAction(){
        $this->display();
    }

    public function clearAction() {
        //清除统计数据
        $condition['access_time']=array('lt',strtotime(C('CLEAR_TIME_INTERVAL')));
        $statsModel=D('Common/stats');
        $statsModel->del($condition);
    }

    public function cancelOrderAction() {
        //定时取消过时订单
		$orderModel=D('Common/Order');
		$cron_time=C('CRON_ORDER_TIME');
		$orderModel->rollOrder($cron_time);
  	}
}
