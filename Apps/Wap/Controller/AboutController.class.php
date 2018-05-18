<?php
/*关于我们*/
namespace Wap\Controller;
use Think\Controller;
class AboutController extends Controller {
    //公司简介
    public function profileAction(){
        $this->assign("web_title","关于我们-".C('SEO_TITLE'));
        $this->assign("web_keywords",C('SEO_KEYWORDS'));
        $this->assign("web_description",C('SEO_DESCRIPTIONS'));
        $this->assign("isCur",1);
        $this->display();
    }
    //关于团队
    public function teamAction(){
        $this->assign("web_title","团队支持-".C('SEO_TITLE'));
        $this->assign("web_keywords",C('SEO_KEYWORDS'));
        $this->assign("web_description",C('SEO_DESCRIPTIONS'));
        $this->assign("isCur",3);
        $this->display();
    }
    //联系我们
    public function contactAction(){
        $this->assign("web_title","联系我们-".C('SEO_TITLE'));
        $this->assign("web_keywords",C('SEO_KEYWORDS'));
        $this->assign("web_description",C('SEO_DESCRIPTIONS'));
        $this->assign("isCur",5);
        $this->display();
    }
    //合作伙伴
    public function partnerAction(){
        $map['status'] = 1;
        $map['is_rec'] = 1;
        $storeCount=M('Store')->where($map)->count();
        $totalPage=ceil($storeCount/10);

        $this->assign('totalPage',$totalPage);
        $this->display();
    }
}
