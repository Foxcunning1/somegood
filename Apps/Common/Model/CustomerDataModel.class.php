<?php
//客户模型
namespace Common\Model;
use Think\Model;
class CustomerDataModel extends Model{

    /**
     * 更新数据信息
     * @Param          int              $id              被修改数据的ID
     * @Param          array            $data            要更新的数据
     * @Return         bool
    */
    public function update($id,$data){
        $customerDataModel = M('CustomerData');
        return $customerDataModel->where(array('id'=>$id))->save($data);
    }

    /**
     * 根据条件统计数量
     * @Param          array            $condition            查找条件
     * @Return         int
     */
    public function calcTotal($condition = array()){
        $customerDataModel = M('CustomerData');
        return $customerDataModel->where($condition)->count();
    }

    /** 获得带页码的客户列表
     * @param $pageNum        int    页码
     * @param $pageSize       int    每页显示的数量
     * @param $condition      array  查询条件
     * @param $pageCondition  array  分页条件
     * @return mixed                包括分页和广告位数组
     */
    public function getCustomerPageList($pageNum , $pageSize, $order = 'add_time DESC,id desc', $condition , $pageCondition){
        $customerDataModel = M('CustomerData');
        $count = $customerDataModel->where($condition)->count();
        $page = new \Think\Page($count,$pageSize,$pageCondition);
        $result['show'] = $page->show();
        $result['pageCount'] = $page->pageCount();
        $result['list'] = $customerDataModel->where($condition)->order($order)->page($pageNum,$pageSize)->select();
        return $result;
    }
}