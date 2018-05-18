<?php
namespace Store\Controller;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Think\Controller;

class IndexController extends BaseController{

    //店铺管理
    public function indexAction(){
        $store_model=D('Common/store');
        $storeDetail=$store_model->getStoreDetail(session('store.id'));
        $this->assign('store',$storeDetail);
        $this->display();
    }

    //店铺资料设置
    public function settingAction(){
        if (IS_POST){
            if (IS_AJAX){
                if (I('type')=='pw'){
                    $oldPw=I('oldPassword');
                    $newPw=I('newPassword');
                    if ($oldPw=='' || $newPw ==''){
                        $this->ajaxInfoReturn('','密码不能为空',0);
                        exit;
                    }
                    $userModel=M('Users');
                    $user=$userModel->where(array('uid'=>session('store.id')))->find();
                    //验证密码
                    if (md5(md5($oldPw).$user['salt'])==$user['password']){
                        $save['password']=md5(md5($newPw).$user['salt']);
                        $re=$userModel->where(array('uid'=>session('store.id')))->setField($save);
                        if ($re){
                            $this->ajaxInfoReturn('','密码修改成功',1);
                        }else{
                            $this->ajaxInfoReturn('','密码修改失败',0);
                        }
                    }else{
                        $this->ajaxInfoReturn('','密码错误',0);
                    }
                }elseif(I('type')=='store'){
                    $save['store_name']=I('store_name');
                    $save['mobile']=I('mobile');
                    $save['products']=I('products');
                    $save['business_hours']=I('business_hours');
                    if ($save['store_name']==''){
                        $this->ajaxInfoReturn('','店铺名不能为空',0);
                        exit;
                    }
                    if (!preg_match("/^1[34578]\d{9}$/", $save['mobile'])){
                        $this->ajaxInfoReturn('','手机号格式不正确',0);
                        exit;
                    }
                    $re=M('store')->where(array('sid'=>session('store.store_id')))->save($save);
                    if ($re!==false){
                        $this->ajaxInfoReturn('','修改成功',1);
                    }else{
                        $this->ajaxInfoReturn('','修改失败',0);
                    }
                }
            }else{
                $this->tips('提交方式错误',0,3,$_SERVER['HTTP_REFERER']);
            }
        }else{
            //获取店铺基本信息
            $info=M('store')->field('store_name,mobile,logo,products,business_hours')->where(array('sid'=>session('store.store_id')))->find();
            $this->assign('info',$info);
            $this->display();
        }
    }

    /**店铺相册*/
    public function storePhotosAction(){
        if(IS_POST){
            $hidFocusPhoto=I('hidFocusPhoto');
            $photos=I('photo');
            $rphotos=I('rphoto');
            if (!$photos){
                $this->ajaxInfoReturn('','请上传图片',0);
            }else{
                $data['photos']=serialize($hidFocusPhoto).'|'.serialize($photos).'|'.serialize($rphotos);
                //写入
                $re=M('store')->where(array('sid'=>session('store.store_id')))->save($data);
                if ($re!==false){
                    $this->ajaxInfoReturn('','上传成功',1);
                }else{
                    $this->ajaxInfoReturn('','上传失败',0);
                }
            }
        }else{
            //获取店铺相册
            $photosArray=explode('|',M('Store')->where(array('sid'=>session('store.store_id')))->getField('photos'));
            $hidFocusPhoto=unserialize($photosArray[0]);
            $photos=unserialize($photosArray[1]);
            $rphotos=unserialize($photosArray[2]);
            $this->assign('hidFocusPhoto',$hidFocusPhoto);
            $this->assign('plist',$photos);
            $this->assign('rlist',$rphotos);
            $this->assign('returnUrl',$_SERVER['HTTP_REFERER']);
            $this->display();
        }
    }

    /**店铺申请*/
    public function applyAction(){
        //获取店铺类型列表
        $store_type_list=M('StoreType')->select();
        //获取省级
        $regionModel = D('Common/Region');
        $regionList = $regionModel->getAreaList(0);
        $this->assign('type_list',$store_type_list);
        $this->assign('list',$regionList);
        R('Mobile/Store/apply');
    }
    /**获得指定区域的信息
     */
    public function getRegionListAction(){
        $pid = I('id',0);
        $regionModel = D('Common/Region');
        $list = $regionModel->getAreaList($pid);
        $this->ajaxInfoReturn($list,"获取成功！",1);
    }
    //获取店铺通知
    public function getNoticeAction(){
        if (IS_AJAX){
            $noticeModel=M('notice');
            //本次查询时间
            $condition['time']=array('lt',time());
            $condition['to']='store';
            $condition['s_id']=session('store.store_id');
            $condition['is_readed']=0;
            //待审核商品数量
            $condition['type']='audit';
            $result['audit_num']=$noticeModel->where($condition)->count();
            //待发货订单数量
            $condition['type']='waitSend';
            $result['wait_num']=$noticeModel->where($condition)->count();
            //未处理消息总数
            $result['totalCount']=$result['audit_num']+$result['wait_num'];
            $this->ajaxInfoReturn($result,'',1);
        }else{
            $this->tips('提交方式错误',0,3,$_SERVER['HTTP_REFERER']);
        }
    }
    //店铺通知列表
    public function noticeAction(){
        $condition['to']='store';
        $condition['s_id']=session('store.store_id');
        $pageNum=I('p',1);
        $noticeModel=D('Common/Notice');
        $result=$noticeModel->getNoticePageList($pageNum,$condition);
        $this->assign('list',$result['list']);
        $this->assign('pageCount',$result['pageCount']);
        $this->assign('pageShow',$result['show']);
        $this->display();
    }
    //处理通知
    public function readAction(){
        $id=I('id');
        if ($id=='all'){
            $condition['to']='store';
            $condition['s_id']=session('store.store_id');
            $condition['is_readed']=0;
            M('notice')->where($condition)->setField('is_readed',1);
            header("Location:".U('Store/Index/notice')."");
        }else{
            $re=M('notice')->where(array('id'=>$id))->setField('is_readed',1);
            if ($re!==false){
                $type= M('notice')->where(array('id'=>$id))->getField('type');
                switch ($type){
                    case 'audit':
                    header("Location:".U('Store/Goods/index')."?pageType=audit");
                    break;
                    case 'waitSend':
                    header("Location:".U('Store/Order/index')."?pageType=1");
                    break;
                }
            }
        }
    }
}
