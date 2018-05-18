<?php
namespace Seller\Controller;
use Think\Controller;

class ColumnController extends BaseController{

    /*卖家栏目主题*/
    public function indexAction(){
        $user_id=session('seller.id');
        $columnModel=D('Common/Goods');
        $condition['user_id']=$user_id;
        $list=$columnModel->getSellerColumnTreeList($condition);
        //获取设为导航的栏目的数量
        $index_num=M('SellerColumn')->where(array('user_id'=>session('seller.id'),'is_index'=>1))->count();
        $max_num=C('SELLER_INDEX_MAX_NUM')?C('SELLER_INDEX_MAX_NUM'):4;
        if ($index_num>=$max_num){
            $this->assign('index','disable');
        }
        $this->assign('list',$list);
        $this->display();
    }

    /*卖家栏目添加*/
    public function addColumnAction(){
        $data=I('data');
        var_dump($data);exit;
        if($data['name']==''){
            $this->ajaxInfoReturn('','名称不可为空',0);
        }else{
            $data['user_id']=session('seller.id');
            $re=M('sellerColumn')->add($data);
            if($re){
                $this->ajaxInfoReturn('','添加成功',1);
            }else{
                $this->ajaxInfoReturn('','添加失败',0);
            }
        }
    }

    /*设为/取消导航*/
    public function changeIndexAction(){
        $id=I('id');
        $change=I('change');
        $columnModel=M('SellerColumn');
        $condition['user_id']=session('seller.id');
        $condition['is_index']=1;
        //判断是设置还是取消
        if($change==1){
            //限制栏目导航数量
            $max_num=C('SELLER_INDEX_MAX_NUM')?C('SELLER_INDEX_MAX_NUM'):4;
            $index_num=$columnModel->where($condition)->count();
            if($index_num<$max_num){
                $re=$columnModel->where(array('user_id'=>session('seller.id'),'id'=>$id))->setField('is_index',1);
                if($re){
                    $this->ajaxInfoReturn('','设为导航成功',1);
                }else{
                    $this->ajaxInfoReturn('','设为导航失败',0);
                }
            }else{
                $this->ajaxInfoReturn('','导航数量超出限制',0);
            }
        }else{
            $condition['id']=$id;
            $re=$columnModel->where($condition)->setField('is_index',0);
            if($re){
                $this->ajaxInfoReturn('','已取消导航',1);
            }else{
                $this->ajaxInfoReturn('','取消失败',0);
            }
        }
    }

}