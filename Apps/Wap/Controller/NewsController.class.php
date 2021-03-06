<?php
/*新闻中心*/
namespace Wap\Controller;
use Think\Controller;
class NewsController extends Controller {
    //新闻列表
    public function listAction(){
        $page = I('p',1);
        $articleModel = D('Admin/Article');
        $condition['cid'] = 8;
        $order = "update_time desc, aid desc";
        $list = $articleModel->getArticlePageList($page,10,$order,$condition);
        if(!IS_AJAX){
            $this->assign("web_title","新闻中心-".C('SEO_TITLE'));
            $this->assign("web_keywords",C('SEO_KEYWORDS'));
            $this->assign("web_description",C('SEO_DESCRIPTIONS'));
            $this->assign("ajaxShow",$list['ajaxShow']);
            $this->assign("empty","<li>".C('NOT_DATA')."</li>");
            $this->assign("isCur",4);
        }else{
            $this->assign("isAjax",1);
        }
        $this->assign("newsList",$list['list']);
        $this->display();
    }
    //新闻详情页
    public function showAction(){
        $aid = I('id');
        if(!$aid){
            $this->error("亲，非法操作!",U('Home/News/list'));
            die;
        }
        //获取文章信息
        $articleModel = D('Admin/Article');
        $info = $articleModel->getArticleInfo($aid);
        //上一篇，下一篇条件
        $condition1['cid'] = $condition['cid'] = $info['cid'];
        $condition1['aid'] = $condition['aid'] = array("neq",$aid);
        $condition['aid'] = array("lt",$aid);
        $condition1['aid'] = array("gt",$aid);
        //获取当前文章的上一篇信息
        $preInfo = $articleModel->getOneArticleInfo($condition,"aid desc");
        //获取当前文章的下一篇信息
        $nextInfo = $articleModel->getOneArticleInfo($condition1,"aid desc");
        $this->assign("web_title",$info['title']);
        $this->assign("web_keywords",$info['keywords']);
        $this->assign("web_description",$info['description']);
        $this->assign("isCur",4);
        $this->assign("info",$info);
        $this->assign("preInfo",$preInfo);
        $this->assign("nextInfo",$nextInfo);
        $this->display();
    }
}
