<?php
/*后台默认页类
 *
 * */
namespace Mobile\Controller;
use Think\Controller;
class UsersController extends MemberController {
    /*会员中心首页*/
    public function indexAction(){
        //获取用户信息
        $id=session('mobile.id');
        $usersModel=D('Common/users');
        $user=$usersModel->getUsersInfo($id);
        $this->assign('user',$user);
        //认证状态
        $this->assign('auth',$user['auth_status']);
        //卖出条数
        $count=M('market_order_goods')->alias('og')
            ->join("LEFT JOIN __MARKET_ORDER__ AS o ON og.order_sn = o.order_sn")
            ->join('LEFT JOIN __GOODS__ AS g ON og.goods_id = g.id')
            ->where(array('user_id'=>$id,'o.status'=>3))->getField('counts',true);
        $success_num=0;
        foreach ($count as $v){
            $success_num +=$v;
        }
        $this->assign('success_num',$success_num);
        //商品发布条数
        $goods_num=M('goods')->where(array('user_id'=>$id))->count();
        $this->assign('goods_num',$goods_num);
        //求购发布条数
        $want_num=M('want')->where(array('w_uid'=>$id))->count();
        $this->assign('want_num',$want_num);
        //收藏个数
        $favorite_num=M('favorites')->where(array('fuser_id'=>$id))->count();
        $this->assign('favorite_num',$favorite_num);
        //优惠券个数
        $coupon_num=M('coupon')->where(array('user_id'=>$id))->count();
        $this->assign('coupon_num',$coupon_num);

        //我的店铺信息
        $store=$usersModel->getUsersStoreInfo();
        $this->assign('store',$store);

        $this->display();
    }

    //订单管理
    public function orderIndexAction(){
      // is_son   0已拆主单  1子单  2 未拆主单
        $status=I('status','100');
        $pageNum = I('p','1');
        $user_id=session('mobile.id');
        $map['o.uid'] = $user_id;
        if($status == 100) {
            $map['o.status'] = array('in','0,1,2,3,6,99');
            $map['_string'] = '!(o.is_son=1 and o.status=0) and !( o.is_son=0 and o.status!=0)';
            // !(o.is_son=1 and o.status=0)
        }elseif ($status == 0) {
            //待付款
            $map['o.status'] = $status;
            $map['_string'] = 'o.is_son=0 OR o.is_son=2';
        }elseif ($status == 1 || $status == 2 ) {
            $map['o.status'] = $status;
            $map['_string'] = 'o.is_son=1 OR o.is_son=2';
            //$map['o.is_son'] = array('neq',1);
        }elseif ($status == 3) {
            $map['_string'] = '(o.is_son=1 OR o.is_son=2) and (o.status=3 or o.status=6)';
        }else {
            $this->tips("非法操作!",1,2000,U("Mobile/Users/index"));
        }
        $orderModule = D('Common/MarketOrder');
        //获取这个根据order_sn分组统计goods_thumb的数组
        $list = $orderModule->getOrderPageList($pageNum,$map,$pageCondition);
        foreach ($list['list'] as $key => $value) {
            $list['list'][$key]['goods_thumb'] = explode(',',$value['tb']);
        }
        //订单物流信息
        $callbackurl=C('MOBILE_URL').__SELF__;
        $this->assign('callbackurl',$callbackurl);
        if (IS_AJAX) {
            $this->assign("isAjax",1);
            $this->assign('list',$list['list']);
        }else{
          //var_dump($list);exit;
            $this->assign('pageCount',$list['counts']);
            $this->assign('status',$status);
            $this->assign('list',$list['list']);
        }
        $this->display("UserOrder/index");
    }

    //确认收货
    public function comfirmAction(){
        $id=I('id');
        $orderModel=M('marketOrder');
        $time=time();
        $data = array('status'=>'3','complete_time'=>$time);
        $rs=$orderModel->where("id=$id")->setField($data);
        if ($rs) {
            //给店铺发送短信
            $day = C('SETTLEMENT_DAY');
            $mobile=$orderModel->alias('o')->join("LEFT JOIN __STORE__ AS s ON s.sid = o.store_id")->where("id=$id")->getField('s.mobile');
            $rs=send_sms_message('sell', $mobile,'',array($day));
            //给卖家发送短信
            $sellMobile=$orderModel->alias('o')->field('u.mobile')
            ->join("LEFT JOIN __MARKET_ORDER_GOODS__ AS mog ON mog.order_sn = o.order_sn")
            ->join("LEFT JOIN __GOODS__ AS g ON g.id = mog.goods_id")
            ->join("LEFT JOIN __USERS__ AS u ON u.uid = g.user_id")->where("o.id=$id")->select();
            foreach ($sellMobile as $key => $v) {
                send_sms_message('sell', $v['mobile'],'',array($day));
            }
            $this->ajaxInfoReturn('','确认收货成功!',1);
        }else {
            $this->ajaxInfoReturn('','确认收货失败!',0);
        }
    }


   //   用户卖出的商品结算
    public function settlementAction(){
        $user_id=session('mobile.id');
        $pageNum=I('p','1');
        $action=I('action','ing');
        $map['g.user_id']=$user_id;
        $marketOrderModel=D('Common/marketOrder');
        if ($action == 'ing') {
          //正在结算
          $map['_string'] = 'o.status=3';
        }else {
          //已结算
          $map['_string'] = 'o.status=6';
        }
        $res=$marketOrderModel->countSettlement1($pageNum,$map);
        $pageCount=$res['pageCount'];
        //获取用户头像
        $avatar=M('users')->where("uid=$user_id")->getField('avatar');
        //var_dump($res);
        if (IS_AJAX) {
            $this->assign('is_ajax',1);
            $this->assign('list',$res);
            $this->assign('allGetMoney',$res['allGetMoney']);
        }else {
            $this->assign('avatar',$avatar);
            $this->assign('pageCount',$pageCount);
            $this->assign('action',$action);
            $this->assign('list',$res);
        }
        $this->display("Users/settlement");
    }

    //订单详情页
    public function orderDetailAction(){
      $order_sn=I('order_sn');
      $MarketOrderModel=D('Common/MarketOrder');
      $user_id=session('mobile.id');
      //获取订单信息
      $map['o.order_sn']=$order_sn;
      $res=$MarketOrderModel->selectOrderInfoById($map);
      //var_dump($res['0']['status']);exit;
      $address_id=$res['list2']['address_id'];
      //订单物流信息
      $type=$res['0']['code'];
      $postid=$res['0']['logistics_sn'];
      $callbackurl=C('MOBILE_URL').__SELF__;
      $url="https://m.kuaidi100.com/index_all.html?type=$type&postid=$postid&callbackurl=$callbackurl";
      $this->assign('url',$url);
      $this->assign('order_sn',$order_sn);
      $this->assign('status',$res['0']['status']);
      $this->assign('address',$address);
      $this->assign('list2',$res['0']);
      $this->display();
    }



    //订单删除
    public function delOrderAction(){
      $order=D('Common/MarketOrder');
      $order_sn = I('order_sn');
      $result = $order->delOrder($order_sn);
      if ($result) {
          $info = array(
            'status' => '1',
            'infomation' => '删除成功'
           );
        $this->ajaxInfoReturn($info,'JSON');
      }else {
          $info = array(
            'status' => '0',
            'infomation' => '删除失败'
           );
        $this->ajaxInfoReturn($info,'JSON');
      }
    }
    /*发布和修改商品*/
    public function publishAction(){
        $action = I('action','add', 'strip_tags');//操作类型
        $uid=session('mobile.id');
        $id=I('id');
        $goodsModel=D('Common/ShGoods');
        if (IS_POST){
            $dataArr = I('data');
            $photoArr = I('photo');
            if(count($photoArr)<3){
                $this->ajaxInfoReturn('','最少上传三张宝贝图片更容易成交哦',0);
            }elseif(!$dataArr['goods_title']){
                $this->ajaxInfoReturn('','请输入标题',0);
            }elseif($dataArr['want_price']<$dataArr['goods_price']){
                $this->ajaxInfoReturn('','意向价比低价还低？',0);
            }elseif(!$dataArr['category_id']){
                $this->ajaxInfoReturn('','请选择宝贝分类',0);
            }elseif(!$dataArr['goods_condition']){
                $this->ajaxInfoReturn('','请选择宝贝新旧程度',0);
            }elseif (explode(',',$dataArr['area_list'])[2]==0){
                $this->ajaxInfoReturn('','请选择区域',0);
            }elseif (!$dataArr['store_id']){
                $this->ajaxInfoReturn('','请选择店铺',0);
            }else{
                if($action == 'edit') {
                    $dataArr['id']=$id;
                    $dataArr['update_time']=time();
                    $result = $goodsModel->where(array('id' => $id))->save($dataArr);
                    if($result !== false) {
                        $this->ajaxInfoReturn('','修改成功',1);
                    }
                    else $this->ajaxInfoReturn('','修改失败',0);
                }else{
                    $dataArr['user_id']=$uid;
                    $dataArr['add_time']=$dataArr['update_time']=time();//发布时间/最后更新时间
                    $dataArr['area_id']=explode(',',$dataArr['area_list'])[2];//区域ID
                    $dataArr['goods_sn']=$id.get_goods_sn().time();
                    $dataArr['goods_thumb']=I('hidFocusPhoto');//商品封面缩略图
                    $dataArr['original_img']=serialize(I('photo'))."||".serialize(I('rphoto'));//商品图组||备注组
                    $goodsId = $goodsModel->add($dataArr);
                    if($goodsId > 0) {
                        $store=M('store')->where(array('sid'=>$dataArr['store_id']))->find();
                        send_sms_message('audit',$store['mobile']);//给店铺发送短信通知审核
                        D('Common/Notice')->insertNotice('audit',"商品".$dataArr['goods_title']."待审核",$dataArr['store_id']);
                        $this->ajaxInfoReturn('','发布成功',1);
                    }
                    else $this->ajaxInfoReturn('','发布失败',0);
                }
            }
        }else {
            $returnUrl=$_SERVER['HTTP_REFERER'];//获取上一操作URL
            if($action == 'edit'){
                $info = $goodsModel->getGoodsInfo($id);
                $info['region']=str_replace(',',' ',mb_substr($info['region'],stripos($info['region'],',')+1));
                $photoArr=explode("||",$info['original_img']);
                $this->assign('plist',unserialize($photoArr[0]));
                $this->assign('rlist',unserialize($photoArr[1]));
                $this->assign('id',$id);
            }else{
                //初始化区域
                $info['goods_num'] = 1;
                $city_id=cookie('city_id');
                $province_id=M('region')->where(array('id'=>$city_id))->find()['pid'];
                $info['area_list']="$province_id,$city_id";
            }
            //商品分类
            $goodsCategoryList = F('Mobile/Category/category_list_0');
            if (!$goodsCategoryList) {
                $cacheCategoryList = require_once ("./Cache/category/category.cache.php");
                $categoryArr = array();
                foreach($cacheCategoryList as $key=>$val){
                    $tempCategoryList['id'] = $val['id'];
                    $tempCategoryList['title'] = $val['title'];
                    $tempCategoryList['img_url'] = $val['img_url'];
                    $tempCategoryList['parent_id'] = $val['parent_id'];
                    $categoryArr[] = $tempCategoryList;
                }
                $goodsCategoryList = $categoryArr;
                F('Mobile/Category/category_list_0',$goodsCategoryList);
            }
            //获取当前类别的信息
            $bigCatId = 0;
            if($info['category_id']>0){
                $catInfo = D("Common/GoodsCategory")->getCategoryInfo($info['category_id']);
                $tempArr = explode(",",$catInfo['class_list']);
                if(count($tempArr)>1){
                    $bigCatId = $tempArr[1];
                }
            }
            $this->assign('bigCatId', $bigCatId);
            $this->assign('info', $info);
            $this->assign('categoryList',$goodsCategoryList);
            $this->assign('returnUrl',$returnUrl);
            $this->assign('action',$action);
            $this->assign('goods_condition',C('GOODS_CONDITION'));
            $this->display();
        }
    }
    //AJAX获取店铺列表
    public function getStoreAction(){
        if (!IS_AJAX){
            $this->ajaxInfoReturn('提交方式错误','',0);
        }else{
            $goodsCategory=I('goods_category');
            $area_list=I('area_list');

            //判断是否选择区域
            if(explode(',',$area_list)[2]!=0){//区域ID不为0
                $condition['area_list']=$area_list;
            }else{
                $this->ajaxInfoReturn('请选择区域','',0);
            }
            $condition['status']=1;
            $store_list = M('store')->field('sid,store_name,logo,lng,lat')->where($condition)->select();
            //根据商品类型筛选店铺
            if($goodsCategory){
                foreach ($store_list as $k=>$sl){
                    $cateCount=M('store_cate_list')->where(array('store_id'=>$sl['sid'],'goods_cate_id'=>$goodsCategory))->count();
                    if($cateCount==0){
                        unset($store_list[$k]);
                    }
//                    if(!in_array($goodsCategory,explode(',',$sl['store_goods_cate']))){
//                        unset($store_list[$k]);
//                    }
                }
                $this->ajaxInfoReturn($store_list,'',1);
            }else{
                $this->ajaxInfoReturn('请选择商品类型','',0);
            }
        }

    }
    /*个人资料设置*/
    public function settingAction(){
        $type=I('type');
        $uid=session('mobile.id');
        $userModel=D('Common/users');
        if (IS_POST){
            $data=I('data');
            //区域
            if ($data['area_id']){
                if(explode(',',$data['area_id'])[2]==0){
                    $this->ajaxInfoReturn('','请选择区域',0);
                }else{
                    $data['area_list']=$data['area_id'];
                    $data['area_id']=substr($data['area_id'],strripos($data['area_id'],',')+1);
                }
            }
            //生日
            if ($data['birthday']){
                $data['birthday']=strtotime($data['birthday']);//生日
            }
            //实名认证信息
            if (I('photo')){
                if(!$data['real_name']){
                    $this->ajaxInfoReturn('','请输入真实姓名',0);
                }elseif (!preg_match("/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{2}[0-9Xx]$)/", $data['id_cardnum'])){
                    $this->ajaxInfoReturn('','请输入正确的身份证号',0);
                }elseif(!I('photo')[1]){
                    $this->ajaxInfoReturn('','请上传身份证正反面各一张',0);
                }else{
                    $data['id_cardphoto']=serialize(I('photo'));//身份证照片
                    $data['auth_status']=1;
                    //把用户头像同步到店铺LOGO
                    $data['logo']=M('users')->where(array('uid'=>$uid))->getField('avatar');
                }
            }
            //验证手机号
            if($data['mobile']){
                if(!preg_match("/^1[34578]\d{9}$/", $data['mobile'])){
                    $this->ajaxInfoReturn('','手机号格式不正确',0);
                }else{
                    //验证手机号是否已被绑定
                    $re=M('users')->where(array('mobile'=>$data['mobile']))->count();
                    if($re){
                        $this->ajaxInfoReturn('','该手机号已被绑定',0);
                    }
                }
            }
            //验证邮箱
            if($data['email']){
                if(!preg_match("/^[A-Za-z0-9\u4e00-\u9fa5]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/", $data['email'])){
                    $this->ajaxInfoReturn('','邮箱格式不正确',0);
                }
            }
            //写入
            $re=$userModel->where(array('uid'=>$uid))->save($data);
            if ($re!==false){
                $this->ajaxInfoReturn('','修改成功',1);
            }else{
                $this->ajaxInfoReturn('','修改失败',0);
            }
        }else{
            if (I('is_store')==1){
                //获取店铺LOGO
                $store_logo=M('store')->where(array('sid'=>session('mobile.store_id')))->getField('logo');
                $this->assign('store_logo',$store_logo);
                $this->assign('is_store',I('is_store'));
            }else{
                //获取用户数据
                $info=$userModel->relation(true)->where(array('uid'=>$uid))->find();
                $info['region']=str_replace(',',' ',mb_substr($info['region'],stripos($info['region'],',')+1));
                $plst=unserialize($info['id_cardphoto']);
                if ($plst!==false){
                    $this->assign('plist',$plst);
                }
                $this->assign('is_qrcode',I('is_qrcode'));
                $this->assign('info',$info);
            }
        }
        $this->assign('returnUrl',$_SERVER['HTTP_REFERER']);
        $this->assign('type',$type);
        $this->display();
    }
    //头像修改
    public function editAvatarAction(){
        $is_store=I('get.is_store',0);//店铺LOGO还是用户头像,0为头像,1为LOGO
        $upload = new \Think\Upload();
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =      './Public/uploads/'; // 设置附件上传根目录
        $upload->subName  = array('date','Ymd');
        // 上传单个文件
        $info   =   $upload->uploadOne($_FILES['avatar_file']);
        //获取图片裁剪旋转信息
        $avatar_data=stripslashes(I('avatar_data'));
        $avaArr=explode(',',$avatar_data);
        $x=explode(':',$avaArr[0])[1];
        $y=explode(':',$avaArr[1])[1];
        $width=explode(':',$avaArr[2])[1];
        $height=explode(':',$avaArr[3])[1];
        $rotate=360-substr(explode(':',$avaArr[4])[1],0,-1);
        $avatar='./Public/uploads/'.$info['savepath'].$info['savename'];
        //处理图片
        pictureFlip($avatar,$avatar,$rotate);
        $image = new \Think\Image();
        $image->open($avatar);
        $image->crop($width, $height,$x,$y)->save($avatar);
        if(!$info) {// 上传错误提示错误信息
            $this->ajaxInfoReturn('',$upload->getError(),0);
        }else{// 上传成功 获取上传文件信息
            //写入数据库
            if ($is_store==1){
                $re=M('store')->where(array('sid'=>session('mobile.store_id')))->setField('logo',$info['savepath'].$info['savename']);
            }elseif (I('get.is_qrcode')==1){
                $re=M('users')->where(array('uid'=>session('mobile.id')))->setField('wx_account_qrcode',$info['savepath'].$info['savename']);
            }else{
                $re=M('users')->where(array('uid'=>session('mobile.id')))->setField('avatar',$info['savepath'].$info['savename']);
            }
            if ($re){
                $arr = array ('result'=>I('avatar_src').$info['savepath'].$info['savename']);
                echo json_encode($arr);
            }else{
                $this->ajaxInfoReturn('','失败',1);
            }
        }
    }

    //我的认证
    public function cerAction(){
        $uid=session('mobile.id');
        $userModel=D('Common/users');
        //获取用户数据
        $info=$userModel->field('id_cardphoto,real_name,id_cardnum')->where(array('uid'=>$uid))->find();
        $plst=unserialize($info['id_cardphoto']);
        $this->assign('plist',$plst);
        $this->assign('info',$info);
        $this->display();
    }
    //我的发布
    public function goodsManageAction(){
        $user_id=session('mobile.id');
        $pageNum=I('p',1);
        $pageType=I('pageType');//页面状态,audit审核,sale上架,success卖出,over过期
        switch ($pageType){
            case "audit":
                $condition['g.status']=array('in',array('0','2'));
                break;
            case "wait":
                $condition['g.status']=1;
                $condition['over_time']=-1;
                $condition['goods_num']=1;
                break;
            case "sale":
                $condition['is_on_sale']=1;
                $condition['over_time']=array('gt',time());
                break;
            case "success":
                $successListCount=M('market_order_goods')->alias('og')->field('g.*')->join("LEFT JOIN __MARKET_ORDER__ AS o ON og.order_sn = o.order_sn")->join('LEFT JOIN __GOODS__ AS g ON og.goods_id = g.id')->where(array('user_id'=>session('mobile.id'),'o.status'=>3))->count();
                $successList=M('market_order_goods')->alias('og')->field('g.*')->join("LEFT JOIN __MARKET_ORDER__ AS o ON og.order_sn = o.order_sn")->join('LEFT JOIN __GOODS__ AS g ON og.goods_id = g.id')->where(array('user_id'=>session('mobile.id'),'o.status'=>3))->limit(($pageNum-1)*C('GOODS_PAGE_SIZE'),C('GOODS_PAGE_SIZE'))->select();
                $this->assign('time',time());
                break;
            case "over":
                $condition['over_time']=array('between',array(0,time()));
                $condition['g.status']=1;
                $condition['goods_num']=1;
                break;
        }
        $condition['g.user_id']=$user_id;
        $goodsModel=D('Common/goods');
        $result=$goodsModel->getGoodsPageList($pageNum,C('GOODS_PAGE_SIZE'),'',$condition,$condition);
        if (IS_AJAX){
            if ($pageType=="success"){
                $result['list']=$successList;
                $result['pageCount']=ceil($successListCount/C('GOODS_PAGE_SIZE'));
            }
            $this->ajaxInfoReturn($result,'',1);
        }else{
            $this->assign('list',$result['list']);
            $this->assign('pageCount',$result['pageCount']);
            $this->assign('pageType',$pageType);
            $this->display();
        }
    }
    //删除发布的商品
    public function delGoodsAction(){
        if (!IS_AJAX){
            $this->ajaxInfoReturn('提交方式错误','',0);
        }else{
            $user_id=session('mobile.id');
            $goods_id=I('id');
            $goodsModel=M('goods');
            $re=$goodsModel->where(array('id'=>$goods_id,'user_id'=>$user_id))->delete();
            if ($re){
                $this->ajaxInfoReturn('删除成功','',1);
            }else{
                $this->ajaxInfoReturn('删除失败','',0);
            }
        }
    }

    //发布和修改求购
    public function addWantAction(){

        $action = I('action','add', 'strip_tags');//操作类型
        $uid=session('mobile.id');
        $today_begin=strtotime(date('Y-m-d',time()));
        //获取用户今天发布求购信息的条数
        $condition['w_uid']=$uid;
        $condition['w_addtime']=array('gt',$today_begin);
        $wantCount=M('want')->where($condition)->count();
        $wid = I('w_id');
        $wantModel = D('Common/Want');
        if (IS_POST) {
            if (!IS_AJAX){
                $this->ajaxInfoReturn('','提交方式不正确',0);
            }else{
                $returnUrl = I('returnUrl');
                $dataArr = I('data');
                if($dataArr[w_validitytime]<1){
                    //有效期不填默认为1个月
                    $dataArr[w_validitytime]=1;
                }
                if(!$dataArr['w_title']){
                    $this->ajaxInfoReturn('','输入标题更容易买到想要的宝贝哦',0);
                }elseif (!$dataArr['w_cateid']){
                    $this->ajaxInfoReturn('','请选择您需求的宝贝所属类别',0);
                }elseif(explode(',',$dataArr['w_arealist'])[2]==0){
                    $this->ajaxInfoReturn('','请选择区域',0);
                }else{
                    if ($action == 'edit') {
                        $dataArr['w_updatetime'] = time();
                        $dataArr['w_overtime'] = strtotime("+$dataArr[w_validitytime] month");
                        $result = $wantModel->where(array('w_id' => $wid))->save($dataArr);
                        if ($result !== false) {
                            $this->ajaxInfoReturn('','发布成功',1);
                        } else $this->ajaxInfoReturn('','发布失败',0);
                    } else {
                        if ($wantCount < C('WANT_DAY_COUNT')) {
                            $dataArr['w_uid'] = $uid;
                            $dataArr['w_addtime'] = $dataArr['w_updatetime'] = time();//发布时间/最后更新时间
                            $dataArr['w_overtime'] = strtotime("+$dataArr[w_validitytime] month");//过期时间
                            $dataArr['w_region'] = substr($dataArr['w_arealist'], strripos($dataArr['w_arealist'], ',') + 1);//区域ID
                            $wantId = $wantModel->add($dataArr);
                            if ($wantId > 0) {
                                $this->ajaxInfoReturn('','发布成功,正在审核',1);
                            } else $this->ajaxInfoReturn('','发布失败',0);
                        } else {
                            $this->ajaxInfoReturn('','每天最多发布'.C('WANT_DAY_COUNT').'条求购信息',0);
                        }
                    }
                }
            }
        } else {
            $returnUrl = $_SERVER['HTTP_REFERER'];//获取上一操作URL
            //获取商品类型列表
            $goodsCategoryList=M('goodsCategory')->where(array('is_hidden' => 0))->select();
            $this->assign('goodsCategoryList', $goodsCategoryList);
            $this->assign('returnUrl', $returnUrl);
            $this->assign('action', $action);
            $this->assign('validityTime', C('validityTime'));
            //商品分类
            $goodsCategoryList = F('Mobile/Category/category_list_0');
            if (!$goodsCategoryList) {
                $cacheCategoryList = require_once ("./Cache/category/category.cache.php");
                $categoryArr = array();
                foreach($cacheCategoryList as $key=>$val){
                    $tempCategoryList['id'] = $val['id'];
                    $tempCategoryList['title'] = $val['title'];
                    $tempCategoryList['img_url'] = $val['img_url'];
                    $tempCategoryList['parent_id'] = $val['parent_id'];
                    $categoryArr[] = $tempCategoryList;
                }
                $goodsCategoryList = $categoryArr;
                F('Mobile/Category/category_list_0',$goodsCategoryList);
            }
            $this->assign('categoryList', $goodsCategoryList);
            if ($action == 'edit') {
                $info = $wantModel->relation(true)->where(array('w_id' => $wid))->find();
                $info['region'] = str_replace(',', ' ', mb_substr($info['region'], stripos($info['region'], ',') + 1));
                //获取当前类别的信息
                $bigCatId = 0;
                if($info['category_id']>0){
                    $catInfo = D("Common/GoodsCategory")->getCategoryInfo($info['category_id']);
                    $tempArr = explode(",",$catInfo['class_list']);
                    if(count($tempArr)>1){
                        $bigCatId = $tempArr[1];
                    }
                }
                $this->assign('bigCatId', $bigCatId);
                $this->assign('info', $info);
                $this->assign('web_title', "求购信息更新");
                $this->display();
            } else {
                if ($wantCount < C('WANT_DAY_COUNT')) {
                    $info=M('region')->where(array('id'=>cookie('city_id')))->find();
                    $info['region']=str_replace(',', ' ', mb_substr($info['merger_name'], stripos($info['merger_name'], ',') + 1));
                    $info['w_arealist']=$info['pid'].','.$info['id'].',0';
                    $this->assign('info',$info);
                    $this->assign('web_title', "发布求购");
                    $this->display();
                } else {
                    $this->ajaxInfoReturn('','每天做多发布'.C('WANT_DAY_COUNT').'条求购信息',0);
                }
            }
        }

    }
//   求购信息删除
    public function delWantAction(){
        if (!IS_AJAX){
            $this->ajaxInfoReturn("提交方式不正确");
        }else{
            $ids=I('ids');
            $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
            $wantModel  = D('Common/want');
            //判断id是数组还是一个数值
            $count_record = 0;
            if(is_array($ids)){
                for($i = 0; $i < count($ids); $i++){
                    $condition["w_id"] = $ids[$i];
                    $del_count = $wantModel->where($condition)->delete();
                    if($del_count!==false){
                        $count_record += $del_count;
                    }else{
                        $count_record = false;
                    }
                }
            }else{
                $count_record = $wantModel->where(array('w_id'=>$ids))->delete();
            }
            if($count_record!==false) {
                $this->ajaxInfoReturn("","删除成功",1);
            }else{
                $this->ajaxInfoReturn("","删除失败",0);
            }
        }
    }

    //我的求购
    public function wantAction(){
        $w_uid=session('mobile.id');
        $isAjax=I('is_ajax',0);//判断是否是AJAX获取
        $pageNum=I('p',1);
        $condition['w_uid']=$w_uid;
        $pageCondition['w_uid']=$w_uid;
        $wantModel=D('Common/want');
        if ($isAjax){
            $vali=I('vali','gt');//获取过期或未过期求购,gt为有效,为过期
            $condition['w_overtime']=array($vali,time());
            $pageCondition['w_overtime']=array($vali,time());
            $info=$wantModel->getWantPageList($pageNum,'',$condition,$pageCondition);
            $this->ajaxInfoReturn($info,'',1);
        }else{
            //获取未过期的求购页数
            $condition['w_overtime']=array('gt',time());
            $pageCondition['w_overtime']=array('gt',time());
            $info=$wantModel->getWantPageList($pageNum,'',$condition,$pageCondition);
            $this->assign('pageCount0',$info['pageCount']);
            //获取过期的求购页数
            $condition['w_overtime']=array('lt',time());
            $pageCondition['w_overtime']=array('lt',time());
            $re=$wantModel->getWantPageList($pageNum,'',$condition,$pageCondition);
            $this->assign('pageCount1',$re['pageCount']);
            $this->display();
        }
    }

    //我的收藏
    public function favoritesAction(){

        $user_id=session('mobile.id');
        $isAjax=I('is_ajax',0);//判断是否是AJAX获取
        $pageNum=I('p',1);
        $favorite_type=I('favorite_type',0);

        //根据favorite_type判断是商品还是店铺
        $condition['favorite_type']=$favorite_type;
        $pageCondition['favorite_type']=$favorite_type;

        $condition['fuser_id']=$user_id;
        $pageCondition['fuser_id']=$user_id;
        $favoriteModel=D('Common/Favorites');

        if ($isAjax){
            $info=$favoriteModel->getFavoritesPageList($pageNum,'',$condition,$pageCondition);
            $this->ajaxInfoReturn($info,'',1);
        }else{
            //收藏商品总页数
            $info=$favoriteModel->getFavoritesPageList($pageNum,'',$condition,$pageCondition);
            $this->assign('pageCount0',$info['pageCount']);
            //获取收藏店铺总页数
            $condition['favorite_type']=1;
            $pageCondition['favorite_type']=1;
            $info=$favoriteModel->getFavoritesPageList($pageNum,'',$condition,$pageCondition);
            $this->assign('pageCount1',$info['pageCount']);
            $this->display();
        }
    }

    //我的优惠券
    public function couponAction(){
        $user_id=session('mobile.id');
        $pageNum=I('p',1);
        $pageType=I('pageType');//页面类型,can_use可用，used使用记录,over过期
        $condition['user_id']=$user_id;
        switch ($pageType){
            case 'can_use':
                $condition['used_time']=0;
                $condition['use_start_date']=array('lt',time());
                $condition['use_end_date']=array('gt',time());
                break;
            case 'used':
                $condition['used_time']=array('gt',0);
                break;
            case 'over':
                $condition['used_time']=0;
                $condition['use_end_date']=array('lt',time());
                break;
        }

        $couponModel=D('Common/Coupon');
        $result=$couponModel->getCouponPageList($pageNum,'',$condition,$condition);
        //如果非全场通用，获取可用范围
        foreach ($result['list'] as &$v){
            if($v['is_all']==0){
                $couponTypeListModel=M('coupon_type_list');
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
        if (IS_AJAX){
            $this->assign('isAjax',1);
            $this->assign('list',$result['list']);
            $this->display();
        }else{
        //可用优惠券条数
        $condition['used_time']=0;
        $condition['use_start_date']=array('lt',time());
        $condition['use_end_date']=array('gt',time());
        $can_used=$couponModel->alias('c')->join("LEFT JOIN __COUPON_TYPE__ AS ct ON c.coupon_type_id = ct.type_id")->where($condition)->count();
        //过期
        $condition['use_end_date']=array('lt',time());
        $cant_used=$couponModel->alias('c')->join("LEFT JOIN __COUPON_TYPE__ AS ct ON c.coupon_type_id = ct.type_id")->where($condition)->count();
        //使用记录条数
        $condition['used_time']=array('gt',0);
        $used_num=$couponModel->alias('c')->join("LEFT JOIN __COUPON_TYPE__ AS ct ON c.coupon_type_id = ct.type_id")->where($condition)->count();
        $this->assign('can_used',$can_used);
        $this->assign('used_num',$used_num);
        $this->assign('cant_used',$cant_used);
        $this->assign('pageCount',$result['pageCount']);
        $this->assign('pageType',$pageType);
        $this->display();
        }
    }
    /*我的虚拟币*/
    public function virtualCashAction(){
        $type = I('get.type',0); //虚拟币类型，0为获得，1为抵扣
        $pageNum = I('p',1);
        $condition['v.change_type'] = $type;
        $pageCondition['type'] = $type;
        $condition['user_id'] = session("mobile.id");
        $virtualCashLogModel = D("Common/VirtualCashLog");
        $result = $virtualCashLogModel->getVirtualCashLogPageList($pageNum,0,'',$condition,$pageCondition);
        if(IS_AJAX){
            $this->ajaxInfoReturn($result,"数据获取成功！",1);
        }else{
            //统计当前用户的虚拟币信息
            $countInfo = $virtualCashLogModel->getVirtualCashLogCountInfo();
            $this->assign('web_title', "购物币记录");
            $this->assign('countInfo', $countInfo);
            $this->assign('type', $type);
            $this->assign('pageCount', $result['pageCount']);//分页总数
            $this->assign('list', $result['list']);
            $this->display();
        }
    }
}
