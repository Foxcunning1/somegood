<?php
/**
 * Created by PhpStorm.
 * User: ZHY
 * Date: 2017/8/14
 * Time: 18:34
 */
namespace Seller\Controller;
use Think\Controller;

class AdvController extends BaseController{
    //广告推广列表
    public function indexAction(){
        $pageNum = I('p',1,'strip_tags');
        $type=I('type');
        switch ($type){
            case 'online':
                $pageCondition['type']='online';
                break;
            case 'store':
                $pageCondition['type']='store';
                break;
        }
        $advModel = D('Common/Adv');
        $condition['seller_id']=session('seller.id');
        $result=$advModel->getStoreAdvPageList($pageNum,$condition,$pageCondition,$type);
        $this->assign('list',$result['list']);
        $this->assign('page',$result['show']);
        $this->assign('type',$type);
        $this->display();
    }
}