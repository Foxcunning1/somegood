<?php
//通知模型
namespace Common\Model;
use Think\Model;
use Think\Page;

class NoticeModel extends Model{
    /**得带页码的通知信息列表
     * @param $pageNum            int              页码
     * @param $condition          array            查询条件
     * @return mixed              array            包括分页信息和查询结果数组
     */
    public function getNoticePageList($pageNum = 1,$condition = array()){
        $noticeModel=M('notice');
        $count=$noticeModel->where($condition)->count();
        $result['count']=$count;
        $page = new Page($count,C('NOTICE_PAGE_SIZE'),$condition);
        $result['pageCount']=$page->pageCount();
        $result['show'] = $page->show();
        $result['list'] = $noticeModel->where($condition)->order('is_readed ASC')->page($pageNum,C('NOTICE_PAGE_SIZE'))->select();
        return $result;
    }
}