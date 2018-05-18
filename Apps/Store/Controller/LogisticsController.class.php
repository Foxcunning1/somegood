<?php
namespace Store\Controller;
use Think\Controller;

class LogisticsController extends BaseController{

    /**
     * 配送方式
     */
    public function indexAction(){
        $SellerLogistics=D('Common/SellerLogistics');
        $pageNum=I('p',1);
        $condition['users_id']=session('store.id');
        $result=$SellerLogistics->getLogisticsPageList($pageNum,'',$condition);
        //获取物流/快递列表
        $logistics_list=M('logistics')->order('sort_num ASC')->select();
        $this->assign('logistics_list',$logistics_list);
        $this->assign('list',$result['list']);
        $this->assign('pageShow',$result['show']);
        $this->display();
    }

    /**
     * 新增配送方式
     */
    public function addLogisticsAction(){
        $data=I('data');
        $data['users_id']=session('store.id');
        $sellerLogisticsModel=M('SellerLogistics');
        $count=$sellerLogisticsModel->where(array('users_id'=>$data['users_id'],'logistics_id'=>$data['logistics_id']))->count();
        if ($count==0){
            $re=$sellerLogisticsModel->add($data);
            if ($re!==false){
                $this->ajaxInfoReturn('',$re,1);
            }else{
                $this->ajaxInfoReturn('','添加失败',0);
            }
        }else{
            $this->ajaxInfoReturn('','已存在',0);
        }

    }

    /**
     * 配送方式启用/禁用
     */
    public function startAction(){
        $id=I('id');
        $save['is_on']=I('is_on');
        $SellerLogistics=M('SellerLogistics');
        $re=$SellerLogistics->where(array('id'=>$id,'users_id'=>session('store.id')))->setField($save);
        if ($re!==false){
            $this->ajaxInfoReturn($id,'',1);
        }else{
            $this->ajaxInfoReturn('','操作失败',0);
        }
    }

    /**
    *配送区域列表
    */
    public function listAction(){
        $id=I('id');
        $pageNum=I('p',1);
        $condition['store_logistics_id']=$id;
        $SellerLogistics=D('Common/SellerLogistics');
        $result=$SellerLogistics->getRegionPageList($pageNum,$condition);
        $this->assign('list',$result['list']);
        $this->assign('pageShow',$result['show']);
        $this->assign('logistics_id',$id);
        $this->display();
    }

    /**
     * 配送区域新增修改
     */
    public function editRegionAction(){
        $lid=I('lid');
        $sid=M('SellerLogistics')->where(array('id'=>$lid))->getField('users_id');
        if ($sid!=session('store.id')){
            $this->tips('操作错误',0,3,$_SERVER['HTTP_REFERER']);
        }else{
            $sellerLogisticsRegionModel=M('SellerLogisticsRegion');
            $action=I('action','add');
            if (IS_POST){
                $data=I('data');
                $data['store_logistics_id']=$lid;
                $data['logistics_region']=implode(',',I('regions'));
                $data['logistics_region_name']=implode(',',array_slice(I('region_names'),0,count(I('regions'))));
                if ($action=='edit'){
                    $re=$sellerLogisticsRegionModel->where(array('id'=>I('id')))->save($data);
                }else{
                    $re=$sellerLogisticsRegionModel->add($data);
                }
                if ($re!==false){
                    $this->ajaxInfoReturn('','操作成功',1);
                }else{
                    $this->ajaxInfoReturn('','操作失败',0);
                }
            }else{
                if ($action=='edit'){
                    $id=I('id');
                    //获取配送价格等信息
                    $info=$sellerLogisticsRegionModel->where(array('id'=>$id))->find();
                    //获取配送范围
                    $info['rs']=explode(',',$info['logistics_region']);
                    $info['ns']=explode(',',$info['logistics_region_name']);
                    $this->assign('info',$info);
                    $this->assign('action',$action);
                }
                //获取省级
                $regionModel = D('Common/Region');
                $regionList = $regionModel->getAreaList(0);
                $this->assign('list',$regionList);
                $this->assign('returnUrl',$_SERVER['HTTP_REFERER']);
                $this->assign('lid',$lid);
                $this->display();
            }
        }
    }


    /**
     * 配送区域删除
     */
    public function delRegionAction(){
        $ids=I('ids');
        $sellerLogisticsRegionModel=M('sellerLogisticsRegion');
        if (is_array($ids)){
            $condition['id']=array('in',$ids);
        }else{
            $condition['id']=$ids;
        }
        $re=$sellerLogisticsRegionModel->where($condition)->delete();
        if ($re!==false){
            $this->ajaxInfoReturn('','删除成功',1);
        }else{
            $this->ajaxInfoReturn('','删除失败',0);
        }
    }
    /**获得指定区域的信息
    */
    public function getRegionListAction(){
        $pid = I('id',0);
        $regionModel = D('Common/Region');
        $list = $regionModel->getAreaList($pid);
        $this->ajaxInfoReturn($list,"获取成功！",1);
    }
    /**修改备注
     */
    public function editRemarksAction(){
        $id=I('id');
        $data['remarks']=I('value');
        $re=M('store_logistics')->where(array('id'=>$id))->save($data);
        if ($re!==false){
            $this->ajaxInfoReturn('','',1);
        }
    }
}