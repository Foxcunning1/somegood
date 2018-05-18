<?php
namespace Wap\Controller;
use Think\Controller;

/*
*首页
*/
class IndexController extends BaseController {

    public function indexAction(){
        //获取新闻
        $articleModel = D('Admin/Article');
        $condition1['cid'] = $condition['cid'] = 8;
        $condition['is_top'] = 1;
        $condition1['is_rec'] = 1;
        $topNewsList = $articleModel->getTopArticleList(1,$condition);
        $newsList = $articleModel->getTopArticleList(3,$condition1);
        $this->assign("web_title",C('SEO_TITLE'));
        $this->assign("web_keywords",C('SEO_KEYWORDS'));
        $this->assign("web_description",C('SEO_DESCRIPTIONS'));
        $this->assign("isCur",0);
        $this->assign("topNewsList",$topNewsList);
        $this->assign("newsList",$newsList);
        $this->display();
    }
}
