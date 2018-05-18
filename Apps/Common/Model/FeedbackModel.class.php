<?php
//留言模型
namespace Common\Model;
use Think\Model;
class FeedbackModel extends Model{

    /** 添加留言信息
     * @param        $data        array        留言信息数组
     * @return       $id          int          添加的留言ID
     */
    public function add($data){
        $feedbackModel = M('feedback');
        $fid = $feedbackModel->data($data)->add();
        return $fid;
    }
    /** 更新留言信息
     * @param        $data        array        留言信息数组
     * @return       $result      bool
     */
    public function updateStatus($condition){
        $feedbackModel = M('feedback');
        $result = $feedbackModel->where($condition)->setField('is_show','1');
        return $result;
    }
    /**删除留言信息
     * @param        $condition        array        查询条件
     * @return       $result           bool
     */
    public function del($condition){
        $feedbackModel = M('feedback');
        $result = $feedbackModel->where($condition)->delete();
        return $result;
    }
    /** 获得带指定数量的留言列表
     * @param $topCount       int    每页显示的数量
     * @param $condition      array  查询条件
     * @return mixed          array
     */
    public function getTopFeedbackPageList($topCount=0 , $condition=array()){
        $feedbackModel = M('feedback');
        if($topCount>0){
            $list = $feedbackModel->where($condition)->limit($topCount)->select();
        }else{
            $list = $feedbackModel->where($condition)->select();
        }
        return $list;
    }

    /** 获得带页码的留言列表
     * @param $pageNum        int    页码
     * @param $pageSize       int    每页显示的数量
     * @param $condition      array  查询条件
     * @param $pageCondition  array  分页条件
     * @return mixed                包括分页和数组
     */
    public function getFeedbackPageList($pageNum , $pageSize, $condition , $pageCondition){
        $feedbackModel = M('feedback');
        $count = $feedbackModel->where($condition)->count();
        $page       = new \Think\Page($count,$pageSize,$pageCondition);// 实例化分页类 传入总记录数和每页显示的记录数
        $result['show'] = $page->show();
        $result['pageCount'] = $page->pageCount();
        $result['list'] = $feedbackModel->where($condition)->page($pageNum,$pageSize)->select();
        return $result;
    }

}