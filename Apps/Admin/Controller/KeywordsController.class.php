<?php

/*
*搜索关键字统计分析
*/
namespace Admin\Controller;
use Think\Controller;

class KeywordsController extends AjaxController{

//搜索关键字列表
	public function indexAction(){
		if(!parent::checkAdminRoleAction('keywords_manage','view')){
		  jscript_msg_tips('亲，您无权进行操作！');
		}
		$categoryId=I('category_id','0','strip_tags');
		$pageNum = I('p',1,'strip_tags');
		$keywords = I('keywords','','strip_tags');
		$category_list = D('Admin/Goods')->getGoodsCategoryTreeListAction();
		$keywordModule = D('Keywords');
		$pageCondition = array();
		if ($keywords) {
			$condition['keyword'] = array('like',"%".$keywords."%");
			$pageCondition['keywords'] = $keywords;
		}
		if($categoryId>0){
			$condition['category_id'] = $categoryId;
			$pageCondition['category_id'] = $categoryId;
		}
		$keyword_list = $keywordModule->getKeywordPageList($pageNum,$condition,$pageCondition);
		$this->assign('keywords',$keywords);
		$this->assign('category_id',$categoryId);
		$this->assign('category_list',$category_list);
		$this->assign('list',$keyword_list['list']);// 赋值数据集
		$this->assign('page',$keyword_list['page']);// 赋值分页输出
		$this->display();
	}



  //删
	public function delAction(){
        //判断权限
        if(!parent::checkAdminRoleAction('keywords_manage','delete')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $ids = I('ids');
        $returnURL=$_SERVER['HTTP_REFERER'];
        $KeyWoredList  = M('keywords');
        //判断id是数组还是一个数值
        $condition['id'] = array('in',$ids);
        $result = $KeyWoredList->where($condition)->delete();
        if($result!==false) {
            add_admin_log('del',session('admin_name').'成功删除了'.$result.'用户搜索!');
            jscript_msg("删除成功！",$returnURL);
        }else{
            jscript_msg_tips("删除失败！");
        }
    }

}
