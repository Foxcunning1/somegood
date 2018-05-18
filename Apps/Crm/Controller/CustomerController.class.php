<?php
namespace Crm\Controller;
use Think\Controller;

/**
**客户管理
*/
class CustomerController extends EmployeeController {

    //获取客户列表
    public function listAction(){
        $isAjax = I('get.isajax', 0);//数据获取类型,0为正常分页，1为ajax获取
        $time = I('get.time', 0);//数据获取时间,0为正常，1为昨天添加，2为前天
        $pageNum = I('p',1);
        $customerDataModel = D("Common/CustomerData");
        $pageCondition['isajax'] = $isAjax;
        switch ($time){
            case 0:
                $condition = array();
                break;
            case 1:
                $condition['_string'] = "to_days(now()) - to_days(date_format(from_UNIXTIME(`add_time`),'%Y-%m-%d')) = 1";//昨天
                break;
            case 2:
                $condition['_string'] = "to_days(now()) - to_days(date_format(from_UNIXTIME(`add_time`),'%Y-%m-%d')) = 2";//前天
                break;
        }

        $result = $customerDataModel->getCustomerPageList($pageNum,10,'add_time DESC,id desc',$condition,array());
        $allCount = $customerDataModel->calcTotal();//记录总数
        $where['_string'] = "to_days(now()) - to_days(date_format(from_UNIXTIME(`add_time`),'%Y-%m-%d')) = 1";//昨天
        $where1['_string'] = "to_days(now()) - to_days(date_format(from_UNIXTIME(`add_time`),'%Y-%m-%d')) = 2";//前天
        $yesterdayCount = $customerDataModel->calcTotal($where);//昨天记录总数
        $beforeDayCount = $customerDataModel->calcTotal($where1);//前天记录总数
        if(!IS_AJAX){
            $this->assign("pageCount",$result['pageCount']);
            $this->assign("time",$time);
        }
        $this->assign("list",$result['list']);
        $this->assign("isAjax",$isAjax);
        $this->assign("allCount",$allCount);
        $this->assign("yesterdayCount",$yesterdayCount);
        $this->assign("beforeDayCount",$beforeDayCount);
        $this->display();
    }

    /**设置客户信息是否已读
     * */
    public function setStatusAction(){
        $id = I('id',0);
        $status = I('status',0);
        if($id>0&&$status!=0){
            $customerDataModel = D("Common/CustomerData");
            $data['status'] = $status;
            $data['do_time'] = time();
            $data['operator'] = session("mobile.username");
            $result = $customerDataModel->update($id,$data);
            if($result!==false){
                $this->ajaxInfoReturn($data['operator'],"设置成功！",1);
            }else{
                $this->ajaxInfoReturn('',"设置失败！",0);
            }
        }else{
            $this->ajaxInfoReturn('',"设置失败！",0);
        }
    }
}
