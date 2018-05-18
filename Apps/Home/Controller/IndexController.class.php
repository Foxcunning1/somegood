<?php
/*首页*/
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function indexAction(){
        //获取新闻
        $articleModel = D('Admin/Article');
        $condition1['cid'] = $condition['cid'] = 8;
        $condition['is_top'] = 1;
        $condition1['is_rec'] = 1;
        $topNewsList = $articleModel->getTopArticleList(1,$condition);
        $newsList = $articleModel->getTopArticleList(6,$condition1);
        $this->assign("web_title",C('SEO_TITLE'));
        $this->assign("web_keywords",C('SEO_KEYWORDS'));
        $this->assign("web_description",C('SEO_DESCRIPTIONS'));
        $this->assign("isCur",0);
        $this->assign("topNewsList",$topNewsList);
        $this->assign("newsList",$newsList);
        $this->display();
    }

    //阿里大于短信发送范例
    public function alidayuSmsSendDemoAction(){
        $templateCode='SMS_93740002';
        $mobile='17639366156';
        $param=array(
            'code'=>'123',
        );
        $result=alidayu_sms_send($templateCode,$mobile,$param);
        var_dump($result);
    }
}
