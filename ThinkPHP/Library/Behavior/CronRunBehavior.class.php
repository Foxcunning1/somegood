<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Behavior;
/**
 * 自动执行任务
 */

class CronRunBehavior {

    public function run(&$params) {
        if (C('CRON_CONFIG_ON')) {
            $this->checkTime();
        }
    }

    private function checkTime() {
        if (F('CRON_CONFIG')) {
            $crons = F('CRON_CONFIG');
        } else{
            $crons = M('plan')->where(array('is_start'=>1))->select();
        }
        if (!empty($crons) && is_array($crons)) {
            $update = false;
            $log = array();
            foreach ($crons as $key => $cron) {
                if (empty($cron['start_time']) || $_SERVER['REQUEST_TIME'] > $cron['start_time']) {
                    G('cronStart');
                    if ($cron['is_private']==0){
                        R($cron['plan_action']);
                    }else{
                        $this->$cron['plan_action']();
                    }
                    G('cronEnd');
                    $_useTime = G('cronStart', 'cronEnd', 6);
                    $cron['start_time'] = $_SERVER['REQUEST_TIME'] + $cron['interval'];
                    $crons[$key] = $cron;
                    $log[] = 'Cron:' . $cron['name'] . ' Runat ' . date('Y-m-d H:i:s') . ' Use ' . $_useTime . ' s ' . "\r\n";
                    $update = true;
                }
            }
            if ($update) {
                \Think\Log::write(implode('', $log));
                F('CRON_CONFIG', $crons);
            }
        }
    }
    //处理即将过期商品
    private function offSaleGoodsAction(){
        $sellerDeliveryModel=D('Common/sellerDelivery');
        $sellerDeliveryModel->handleOverGoods();
    }
}