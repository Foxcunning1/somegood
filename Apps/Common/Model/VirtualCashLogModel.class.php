<?php
//求购模型
namespace Common\Model;
use Think\Model;
class VirtualCashLogModel extends Model{
    /*判断用户是否已获取过对应的奖励
     * @Param       int         $uid         用户id
     * @Param       int         $from_uid    来源用户uid
     * */
    public function isExits($uid,$from_uid){
        if(!$uid||!$from_uid){
            return false;
        }else{
            $virtualCashLogModel = M('virtual_cash_log');
            $condition['user_id'] = $uid;
            $condition['from_uid'] = $from_uid;
            if($virtualCashLogModel->where($condition)->count()>0){
                return true;
            }else{
                return false;
            }
        }
    }
    /*添加虚拟币信息
     * Param         array       $data             虚拟币数据
     * */
    public function add($data){
        if(empty($data)){
            return false;
        }
        $virtualCashLogModel = M('virtual_cash_log');
        $res = $virtualCashLogModel->data($data)->add();
        return $res;
    }
    /*更新用户微信信息
    * Param        array       $data             用户数据
    * Param        str         $openid           用户openid
    * */
    public function save($data, $openid){
        if($openid == ""||empty($data)){
            return false;
        }
        $condition['openid'] = $openid;
        $userWeixinModel = M('users_weixin');
        $res = $userWeixinModel->data($data)->where($condition)->save();
        return $res;
    }

    /**统计虚拟币的可用金额、来源数量，使用记录*/
    public function getVirtualCashLogCountInfo($condition=array()){
        $virtualCashLogModel = M('virtual_cash_log');
        if(!$condition){
            $condition['user_id'] = session('mobile.id');
        }
        /*$strSql = "SELECT SUM(user_money) as total_money, IFNULL(from_counts,0) as f_counts,IFNULL(to_counts,0) as t_counts FROM `sg_virtual_cash_log` as vc
                   LEFT JOIN (SELECT vc1.user_id as uid1,count(*) as from_counts FROM `sg_virtual_cash_log` as vc1 where vc1.change_type=0 group by vc1.user_id) as t1 on uid1=vc.user_id 
                   LEFT JOIN (SELECT vc2.user_id as uid2,count(*) as to_counts FROM `sg_virtual_cash_log` as vc2 where vc2.change_type=1 group by vc2.user_id ) as t2 on uid2=vc.user_id
                    WHERE user_id=820 ";*/
        //获得虚拟币的记录总数
        $fromCountQuery = $virtualCashLogModel->alias("vc1")->field("vc1.user_id as uid1,count(*) as from_counts")->where("vc1.change_type=0")->group("vc1.user_id")->buildSql();
        //已抵扣虚拟币的记录总数
        $toCountQuery = $virtualCashLogModel->alias("vc2")->field("vc2.user_id as uid2,count(*) as to_counts")->where("vc2.change_type=1")->group("vc2.user_id")->buildSql();
        $countInfo = $virtualCashLogModel->alias("vc")->field("IFNULL(SUM(user_money),0) as total_money, IFNULL(from_counts,0) as f_counts,IFNULL(to_counts,0) as t_counts")
                                         ->join("LEFT JOIN ".$fromCountQuery." AS t1 ON t1.uid1 = vc.user_id")
                                         ->join("LEFT JOIN ".$toCountQuery." AS t2 ON t2.uid2 = vc.user_id")
                                         ->where($condition)->find();
        return $countInfo;
    }

    /**获得带页码的虚拟币信息
     * @param $pageNum       int      页码
     * @param $condition     array    查询条件
     * @param $pageCondition array    分页条件
     * @return mixed array            包括分页和广告数组
     */
    public function getVirtualCashLogPageList($pageNum , $pageSize = 0, $orderStr = "change_time desc,log_id desc", $condition , $pageCondition){
        $virtualCashLogModel = M('virtual_cash_log');
        $count = $virtualCashLogModel->alias("v")->where($condition)->count();
        if($pageSize==0){
            $pageSize = C("VIRTUAL_CASH_PAGE_SIZE");
        }
        if($orderStr==""){
            $orderStr = "change_time desc,log_id desc";
        }
        $page  = new \Think\Page($count,$pageSize,$pageCondition);// 实例化分页类 传入总记录数和每页显示的记录数
        $result['show'] = $page->show();
        $result['pageCount'] = $page->pageCount();
        $result['list'] = $virtualCashLogModel->alias("v")->field("v.*,u.uid,u.user_name,u.mobile")
                                              ->join("LEFT JOIN __USERS__ AS u ON v.user_id=u.uid ")
                                              ->where($condition)->order($orderStr)->page($pageNum,$pageSize)->select();
        return $result;
    }
}