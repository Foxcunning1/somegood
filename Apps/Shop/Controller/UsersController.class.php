<?php
namespace Shop\Controller;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Think\Controller;
//商城用户中心类
class UsersController extends BaseController {
    /**用户中心
     *TODO:待处理订单数量,待处理订单列表
     */
    public function indexAction(){
        //获取用户信息
        $id=session('shop.id');
        $usersModel=D('Common/users');
        $user=$usersModel->getUsersInfo($id);
        $this->assign('user',$user);
        //认证状态
        $this->assign('auth',$user['auth_status']);
        //未完成的订单
        $condition['o.status'] = array('lt','3');

        $this->display();
    }

    /**资料设置*/
    public function settingAction(){
        $id=session('shop.id');
        if (IS_POST){
            $data=I('data');
            $province=I('province');
            $city=I('city');
            if($data['email']){
                if (!regexp_str('email',$data['email']))$this->ajaxInfoReturn('','邮箱格式不正确',0);
            }elseif($data['mobile']){
                if (!regexp_str('mobile',$data['mobile']))$this->ajaxInfoReturn('','手机号格式不正确',0);
                $codeInfo=M('verification')->where(array('mobile'=>$data['mobile'],'type'=>'bind'))->find();
                if ($data['bindCode'] != $codeInfo['code'])$this->ajaxInfoReturn('','绑定验证码错误','n');
                $curTime = time();
                if(strtotime('+5 minute', $codeInfo['time']) < $curTime)$this->ajaxInfoReturn('','绑定验证码超时，请重新获取！','n');
            }
            if(!$data['nick_name']){
                $this->ajaxInfoReturn('','请填写昵称','n');
            }elseif (!$data['sex']){
                $this->ajaxInfoReturn('','请选择性别','n');
            }elseif (!$province){
                $this->ajaxInfoReturn('','请选择省份','n');
            }elseif (!$city){
                $this->ajaxInfoReturn('','请选择城市','n');
            }elseif (!$data['area_id']){
                $this->ajaxInfoReturn('','请选择区域','n');
            }else{
                $data['area_list']=$province.','.$city.','.$data['area_id'];
                if ($data['birthday'])$data['birthday']=strtotime($data['birthday']);
                $re=M('Users')->where(array('uid'=>$id))->save($data);
                if ($re){
                    $this->ajaxInfoReturn('','修改成功','y');
                }else{
                    $this->ajaxInfoReturn('','修改失败或信息未变更','n');
                }
            }
        }else{
            $action=I('action');
            //获取用户信息
            $usersModel=D('Common/users');
            $info=$usersModel->getUsersInfo($id);
            //获取省级
            $regionModel = D('Common/Region');
            $list = $regionModel->getAreaList(0);
            //获取地区
            $areaInfo=M('Region')->where(array('id'=>$info['area_id']))->getField('merger_name');
            $area=explode(',',$areaInfo);
            //dump($list);
            $this->assign('info',$info);
            $this->assign('area',$area);
            $this->assign('provinceList',$list);
            $this->assign('action',$action);
            if ($action=='edit'){
                $this->assign('mnav',2);
            }elseif($action=='info'){
                $this->assign('mnav',1);
            }
        }
        $this->display();
    }

    /**获得指定区域的信息*/
    public function getRegionListAction(){
        $pid = I('id',0);
        $regionModel = D('Common/Region');
        $list = $regionModel->getAreaList($pid);
        $this->ajaxInfoReturn($list,"获取成功！",1);
    }

    /**获得指定区域的信息*/
    public function commissionAction(){
        $this->display();
    }

    /**头像修改*/
    public function avatarAction(){
        //获取用户信息
        $id=session('shop.id');
        $usersModel=D('Common/users');
        $userInfo=$usersModel->getUsersInfo($id);
        $this->assign('info',$userInfo);
        $this->display();
    }

    /**上传头像*/
    public function uploadImgAction(){

        $upload = new \Think\Upload();
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =      './Public/uploads/'; // 设置附件上传根目录
        $upload->subName  = 'avatar';
        // 上传单个文件
        $info   =   $upload->uploadOne($_FILES['Filedata']);
        if(!$info) {// 上传错误提示错误信息
            $this->ajaxInfoReturn('',$upload->getError(),0);
        }else{// 上传成功 获取上传文件信息
            $this->ajaxInfoReturn('http://'.$_SERVER['HTTP_HOST'].'/'.C('WEB_NAME').'/Public/uploads/'.$info['savepath'].$info['savename'],"",1);

        }
    }

    /**裁剪并保存用户头像*/
    public function avatarCropAction(){
        //图片裁剪数据
        $params = $_POST;						//裁剪参数
        if(!isset($params) && empty($params)){
            die($this->ajaxInfoReturn('','非法上传！','n'));
        }
        //获取图片裁剪信息
        $x=$params['hideX1'];
        $y=$params['hideY1'];
        $width=$params['hideWidth'];
        $height=$params['hideHeight'];
        $name=explode('Public/uploads',$params['hideFileName']);
        $avatar='./Public/uploads/'.$name[1];
        //处理图片
        $image = new \Think\Image();
        $image->open($avatar);
        $image->crop($width, $height,$x,$y)->save($avatar);

        //写入数据库
        $re=M('users')->where(array('uid'=>session('shop.id')))->setField('avatar',$name[1]);
        if ($re){
            $this->ajaxInfoReturn('','','y');
        }else{
            $this->ajaxInfoReturn('','','n');
        }
    }

    /**密码修改*/
    public function passwordAction(){
        if(IS_AJAX){
            $oldPw=I('oldPassword');
            $pw=I('password');
            $pw1=I('password1');
            $tUrl=I('tUrl');
            if($oldPw==$pw)$this->ajaxInfoReturn('','新密码不可与旧密码相同','n');
            if($pw!=$pw1)$this->ajaxInfoReturn('','两次输入的密码不一致','n');
            //获取用户信息
            $id=session('shop.id');
            $usersModel=D('Common/users');
            $userInfo=$usersModel->getUsersInfo($id);
            if(md5(md5($oldPw).$userInfo['salt'])==$userInfo['password']){
                $re=M('Users')->where(array('uid'=>$id))->setField('password',md5(md5($pw).$userInfo['salt']));
                if($re){
                    $this->ajaxInfoReturn($tUrl,'修改成功','y');
                }else{
                    $this->ajaxInfoReturn($tUrl,'修改失败','n');
                }
            }else{
                $this->ajaxInfoReturn($tUrl,'密码错误','n');
            }
        }else{
            $this->assign('mnav',2);
            $this->display();
        }
    }

    /**判断密码是否正确*/
    public function ajaxAction(){
        $pw = I('param');
        //获取用户信息
        $id=session('shop.id');
        $usersModel=D('Common/users');
        $userInfo=$usersModel->getUsersInfo($id);
        if(md5(md5($pw).$userInfo['salt'])==$userInfo['password']){
            die($this->ajaxInfoReturn(0,'密码正确!','y'));
        }else{
            die($this->ajaxInfoReturn(0,'密码错误!','n'));
        }

    }

    /**手机、邮箱绑定修改*/
    public function contactAction(){
        $type=I('type');//要更换的类型mobile,email
        if(IS_POST){
            $data = I('data');
            $old_mobile = $data['old_mobile'];//原手机
            $old_email = $data['old_email'];//原邮箱
            $sendCode = $data['sendCode'];//解绑验证码
            $new = $data['new'];//新手机/邮箱
            $bindCode = $data['bindCode'];//绑定验证码
            $codeType = $data['codeType'];//验证码类型,1为手机,0为邮箱
            $usersModel = M('Users');
            $veriMobile = M('verification');
            if($codeType==1){
                $oldCodeInfo= $veriMobile->where(array('mobile'=>$old_mobile,'type'=>'bind'))->find();
            }else{
                $oldCodeInfo= $veriMobile->where(array('email'=>$old_email,'type'=>'login'))->find();
            }
            if ($sendCode == $oldCodeInfo['code']){
                $curTime = time();
                if(strtotime('+5 minute', $oldCodeInfo['time']) > $curTime){
                    if($type=='mobile'){
                        $newCodeInfo= $veriMobile->where(array('mobile'=>$new,'type'=>'bind'))->find();
                    }else{
                        $newCodeInfo= $veriMobile->where(array('email'=>$new,'type'=>'bind'))->find();
                    }
                    if ($bindCode == $newCodeInfo['code']){
                        $curTime = time();
                        if(strtotime('+5 minute', $newCodeInfo['time']) > $curTime){
                            //修改
                            if($type=='mobile'){
                                $re=$usersModel->where(array('uid'=>session('shop.id')))->setField($type,$new);
                            }else{
                                $re=$usersModel->where(array('uid'=>session('shop.id')))->setField($type,$new);
                            }
                            if ($re){
                                $this->ajaxInfoReturn('','修改成功','y');
                            }else{
                                $this->ajaxInfoReturn('','修改失败','n');
                            }
                        }else{
                            $this->ajaxInfoReturn('','绑定验证码超时，请重新获取！','n');
                        }
                    }else{
                        $this->ajaxInfoReturn('','绑定验证码错误','n');
                    }
                }else{
                    $this->ajaxInfoReturn('','解绑验证码超时，请重新获取！','n');
                }
            }else{
                $this->ajaxInfoReturn('','解绑验证码错误','n');
            }
        }else{
            //获取用户信息
            $id=session('shop.id');
            $usersModel=D('Common/users');
            $info=$usersModel->getUsersInfo($id);
            $this->assign('mnav',2);
            $this->assign('info',$info);
            $this->display($type);
        }
    }

    /**优惠券*/
    public function couponAction(){
        $user_id=session('shop.id');
        $pageNum=I('p',1);
        $pageType=I('pageType');//页面类型,can_use可用，used使用记录,over过期
        $condition['user_id']=$user_id;
        switch ($pageType){
            case 'can_use':
                $condition['used_time']=0;
                $condition['use_start_date']=array('lt',time());
                break;
            case 'used':
                $condition['used_time']=array('gt',0);
                break;
        }

        $couponModel=D('Common/Coupon');
        $result=$couponModel->getCouponPageList($pageNum,'',$condition,$condition);
        //如果非全场通用，获取可用范围
        foreach ($result['list'] as &$v){
            if($v['is_all']==0){
                $couponTypeListModel=M('CouponTypeList');
                $goods_title=$couponTypeListModel->field('gc.title')->alias('ctl')
                    ->join("LEFT JOIN __GOODS_CATEGORY__ AS gc ON ctl.goods_cate = gc.id")
                    ->where(array('coupon_type_id'=>$v['type_id']))
                    ->select();
                $goods_cate_str="";
                foreach ($goods_title as $gt){
                    $goods_cate_str .=$gt['title'].',';
                }
                $v['goods_category']=$goods_cate_str;
            }
        }

        $this->assign('pageType',$pageType);
        $this->assign('list',$result['list']);
        $this->assign('page',$result['show']);
        $this->assign('mnav',14);
        $this->display();

    }



    /**我的收藏*/
    public function favoritesAction(){
        $user_id=session('shop.id');
        $pageNum=I('p',1);
        $favorite_type=I('favorite_type',0);
        //根据favorite_type判断是一手还是二手
        $condition['favorite_type']=$favorite_type;
        $pageCondition['favorite_type']=$favorite_type;

        $condition['fuser_id']=$user_id;
        $pageCondition['fuser_id']=$user_id;
        $favoriteModel=D('Common/Favorites');
        //收藏一手
        $result=$favoriteModel->getFavoritesPageList($pageNum,'',$condition,$pageCondition);
        $this->assign('list',$result['list']);
        $this->assign('page',$result['show']);
        $this->assign('mnav',16);
        $this->display();
    }

    /**删除收藏*/
    public function delFavoritesAction(){
        $checkId=I('checkId');
        if ($checkId){
            if(!is_array($checkId)){
                $ids[0]=$checkId;
            }else{
                $ids=$checkId;
            }
            $condition['favorite_id']=array('in',$ids);
            $condition['fuser_id']=session('shop.id');
            $favoriteModel=M('favorites');
            $re=$favoriteModel->where($condition)->delete();
            if($re){
                $this->ajaxInfoReturn('','删除成功',1);
            }else{
                $this->ajaxInfoReturn('','删除失败',0);
            }
        }else{
            $this->ajaxInfoReturn('','请选择要删除的收藏',0);
        }
    }
}
