<?php
/**
 * Created by PhpStorm.
 * User: ZHY
 * Date: 2017/8/14
 * Time: 18:34
 */
namespace Mobile\Controller;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Think\Controller;

class StoreController extends Controller{
    //验证店铺
    public function valiStoreAction(){
        $store_count=M('store')->where(array('user_id'=>session('mobile.id'),'status'=>1))->count();
        if($store_count==0){
            header("Location:apply");
        }
    }

    //店铺管理
    public function indexAction(){
        $this->valiStoreAction();
        $storeModel=D('Common/store');
        $storeDetail=$storeModel->getStoreDetail(session('mobile.id'));
        $sellerDeliveryModel=M('SellerDelivery');
        $storeId = $storeDetail['sid'];
        if ($storeId) {
            $attention = M('favorites')->where("favorite_id=$storeId")->count(); //关注店铺的人数
        }
        $this->assign('attention',$attention);
        //在售数量
        $on_sale_num=$sellerDeliveryModel->where(array('is_onsale'=>1,'store_id'=>session('mobile.store_id')))->count();
        $this->assign('on_sale_num',$on_sale_num);
        //过期的数量
        $off_sale_num=$sellerDeliveryModel->where(array('end_time'=>array('lt',time()),'store_id'=>session('mobile.store_id')))->count();
        $this->assign('off_sale_num',$off_sale_num);
        $this->assign('store',$storeDetail);
        $this->display();
    }

    //店铺中心-商品上架/下架列表
    public function storeGoodsListAction(){
        $this->valiStoreAction();
        $goods_model=D('Common/SellerDelivery');
        $pageType=I('pageType');//列表类型,sale上架,off下架
        $pageNum=I('p',1);
        $result=$goods_model->getStoreGoodsList($pageType,$pageNum,C('GOODS_PAGE_SIZE'),'');
        if(IS_AJAX){
            $this->assign("isAjax",1);
            $this->assign('pageType',$pageType);
            $this->assign('list',$result['list']);
            $this->assign('pageCount',$result['pageCount']);
            $this->assign('time',time());
            $this->display();
        }else{
            $this->assign("isAjax",0);
            $this->assign('pageType',$pageType);
            $this->assign('pageCount',$result['pageCount']);
            $this->assign('pageTitle',$result['pageTitle']);
            $this->display();
        }
    }

    //订单结算统计页面  店铺
    public function settlementAction(){
        $OrderModel=D('Common/Order');
        //参数
        $user_id=session('mobile.id');
        $store_id=session('mobile.store_id');  //店铺id
        $pageNum=I('p','1');
        $action=I('action')?I('action'):'ing';
        //查询条件
        $map['mog.store_id']=$store_id;
        $map['o.is_used']='0';  //一手订单
        if ($action == 'ing') {
          //正在结算
          $map['_string'] = 'o.status=3 and (o.is_son != 0)';
        }else {
          //已结算
          $map['_string'] = 'o.status=6 and (o.is_son != 0)';
        }
        //店铺结算数据
        $list=$OrderModel->mobileStoreSettlement($pageNum,$map,$store_id);
        $pageCount=$list['pageCount']; //总页数
          //var_dump($list);exit;
        if (IS_AJAX) {
          $this->assign("is_ajax",1);
          $this->assign('list',$list['list']);
        }else{
          $this->assign('pageCount',$pageCount);
          $this->assign('action',$action);
          $this->assign('list',$list);
        }
          $this->display();
    }


    //ajax 测试页面
        public function testAction(){
            $this->valiStoreAction();
          $OrderModel=D('Common/Order');
          $user_id=session('mobile.id');
          $store_id=session('mobile.store_id');
          $pageNum=I('p','1');
          $action=I('action')?I('action'):'ed';
          $map['store_id']=$store_id;
          if ($action == 'ing') {
            //正在结算
            $map['_string'] = 'status=3 and (is_son != 0)';
          }else {
            //已结算
            $map['_string'] = 'status=6 and (is_son != 0)';
          }
          $list=$OrderModel->countSettlement($pageNum,$map,$store_id);
          $pageCount=$list['pageCount'];
            //var_dump($list);exit;
          if (IS_AJAX) {
            $this->assign("is_ajax",1);
            $this->assign('list',$list['list']);
          }else{
            $this->assign('pageCount',$pageCount);
            $this->assign('action',$action);
            $this->assign('list',$list);
          }
            $this->display();
        }


    //店铺列表
    public function listAction(){
        $pageNum = I('p',1,'strip_tags');
        $areaId = I('get.area_id',0);//区域id
        $order = I('get.order','2');//数据获取类型，0为销量降序，1为销量升序，2为距离
        $isAjax = I('get.isajax',0);//数据获取类型,0为正常分页，1为ajax获取
        $storeModel = D('Common/Store');
        $orderStr = 'sid DESC';
        if($areaId>0){
            $condition['area_id'] = $areaId;
        }
        $condition['status']=1;
        $pageCondition['area_id'] = $areaId;//区域id
        $pageCondition['isajax'] = $isAjax;//是否ajax获取
        if($areaId>0){
            $map['g.area_id'] = $areaId;
        }
        //把用户坐标加入条件数组里
        $regionModel = D("Region");
        if($regionModel->isExist()) {//用户位置城市与选择城市一致
            //$condition['s.lng'] = array("gt", session("lng") - 1);
            //$condition['s.lng'] = array("lt", session("lng") + 1);
            //$condition['s.lat'] = array("gt", session("lat") - 1);
            //$condition['s.lat'] = array("lt", session("lat") + 1);
            if ($order == 2) {
                $orderStr = "ACOS(SIN((" . session("lat") . " * 3.1415) / 180 ) *SIN((s.lat * 3.1415) / 180 ) + COS((" . session("lat") . " * 3.1415) / 180 )
                              * COS((s.lat * 3.1415) / 180 ) *COS((" . session("lng") . "* 3.1415) / 180 - (s.lng * 3.1415) / 180 ) ) * 6380 asc";
            }
        }else{
            if($order == 0){
                $orderStr = "sale_counts desc, sid asc";
            }
            if($order == 1){
                $orderStr = "sale_counts asc, sid asc";
            }
        }
        //分页获取
        $result = $storeModel->getStoreList($pageNum,10,$orderStr,$condition,$pageCondition);
        if($isAjax){//ajax获取数据
            if($result){
                $this->ajaxInfoReturn($result,"数据获取成功",1);
            }else{
                $this->ajaxInfoReturn('',"亲，已经没有数据了！",2);
            }
        }else {//正常输出页面数据
            //根据城市id获取区域信息
            $regionModel = D("Region");
            $areaList = $regionModel->getAreaList(cookie("city_id"));
            $this->assign('area_id', $areaId);
            $this->assign("order", $order);
            $this->assign('areaList', $areaList);
            $this->assign('storeList', $result['list']);
            $this->assign('pageCount', $result['pageCount']);//分页总数
            $this->assign('count', $result['count']);
            $this->display();
        }
    }
    //详情
    public function detailAction(){
        $sid=I('shopid');
        $pageNum=I('p',1);
        $storeModel=D('Common/store');
        $storeDetail=$storeModel->storeDetail($sid);
        $region=explode(',',$storeDetail['region']);
        $storeDetail['region']=$region[2].$region[3].$storeDetail['address'];
        //置顶商品
        $goodsModel=D('Common/SellerDelivery');
        $condition['store_id']=$sid;
        $condition['g.is_onsale']=1;
        $result=$goodsModel->getGoodsPageList($pageNum,C('GOODS_PAGE_SIZE'),'',$condition);
        if (IS_AJAX){
            $this->assign('list', $result['list']);
            $this->assign('isAjax',1);
            $this->display();
        }else{
            //获取该店铺关注状态
            //            $favoriteStatus=M('favorites')->where(array('fuser'=>session('mobile.id'),'favorite_id'=>$sid,'favorite_type'=>1))->count();
            //            $this->assign('favoriteStatus',$favoriteStatus);
            $this->assign('info', $storeDetail);
            $this->assign('pageCount', $result['pageCount']);
            $this->assign('shop_id',$sid);
            $this->display();
        }
    }
    //店铺商品列表
    public function storeGoodsAction(){
        $sid=I('shopid');
        $pageNum=I('p',1);
        $category_id=I('category_id',0);
        $pageType=I('pageType');
        $goodsModel=D('Common/SellerDelivery');
        $condition['store_id']=$sid;
        $condition['g.is_onsale']=1;
        if ($category_id>0){
            $condition['category_id']=$category_id;
            $pageTitle=M('store_cate_list')->where(array('goods_cate_id'=>$category_id))->getField('goods_cate_name');
            $this->assign('pageTitle',$pageTitle);
        }else{
            $this->assign('pageTitle',"全部");
        }
        switch ($pageType){
            case 'hot':
                $condition['is_hot']=1;
                $this->assign('pageTitle','热卖');
                break;
            case 'rec':
                $condition['is_rec']=1;
                $this->assign('pageTitle','推荐');
                break;
            case 'new':
                $condition['is_new']=1;
                $this->assign('pageTitle','最新');
                break;
            case 'top':
                $condition['is_top']=1;
                $this->assign('pageTitle','置顶');
                break;
        }
        $result=$goodsModel->getGoodsPageList($pageNum,C('GOODS_PAGE_SIZE'),'',$condition,$condition);
        if (IS_AJAX){
            $this->assign('list',$result['list']);
            $this->assign('isAjax',1);
            $this->display();
        }else{
            $store_cate=M('store_cate_list')->where(array('store_id'=>$sid))->select();
            $this->assign('store_cate',$store_cate);
            $this->assign('shopid',$sid);
            $this->assign('pageType',$pageType);
            $this->assign('category_id',$category_id);
            $this->assign('pageCount',$result['pageCount']);
            $this->display();
        }
    }

    //店铺入驻申请
    public function applyAction(){
        $id=session('mobile.id');
        if(!$id){
            $this->error('当前用户未登录，请重新登录！', U('login/login'));
        }
        $action=I('action','add');
        $storeModel=D('Common/store');
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        if (strpos($returnUrl,'login')){
            $returnUrl=U('Mobile/Users/index');
        }
        if(!$returnUrl){
            $returnUrl = U('Mobile/Users/index');
        }
        $isStore = M('users')->where("uid=$id")->getField('is_store');
        $this->assign('isStore',$isStore);
        //获取用户实名认证状态
        /*$status=M('users')->where(array('uid'=>$id))->getField('auth_status');
        if($status!=2){
            $this->tips("请先实名认证!",1,2000,U("Mobile/Users/setting",array("type"=>"id_confirm")));
        }*/
        if (IS_POST){
            $dataArr = I('data');
            if (!$dataArr['area_list']){
                $dataArr['area_list']=I('province').','.I('city').','.I('district');
            }
            $dataArr['user_id'] = $id;
            if ($action=="add"){
                $store = $storeModel->where(array('user_id' => $id))->count();
                $mobile = $storeModel->where(array('mobile' => I('data')['mobile']))->count();
                if ($store>0){
                    $this->ajaxInfoReturn("","店铺审核中!",1);
                }
                if ($mobile){
                    $this->ajaxInfoReturn("","手机号已使用!",0);
                }
            }
            if(!$dataArr['store_name']){
                $this->ajaxInfoReturn("","请输入您的店铺名称!",0);
            }elseif(explode(',',$dataArr['area_list'])[2]==0){
                $this->ajaxInfoReturn("","请选择区域!",0);
            }elseif(!$dataArr['address']){
                $this->ajaxInfoReturn("","请输入您店铺详细地址!",0);
            }elseif (!$dataArr['store_type']){
                $this->ajaxInfoReturn("","请选择您的店铺类型!",0);
            }elseif(!preg_match("/^1[34578]\d{9}$/", $dataArr['mobile'])){
                $this->ajaxInfoReturn("","手机号格式不正确!",0);
            }elseif(!$dataArr['owner']){
                $this->ajaxInfoReturn("","请输入店主姓名!",0);
            }else{
                $dataArr['logo']=M('users')->where(array('uid'=>$id))->find()['avatar'];//将用户头像同步到店铺LOGO
                $dataArr['area_id'] = explode(',',$dataArr['area_list'])[2];//区域ID
                $dataArr['idcard_photo']=serialize(I('photo'));//身份证照
                if ($action=="edit"){
                    //更新店铺审核信息
                    $storeId = $storeModel->where(array('sid'=>session('mobile.store_id')))->save($dataArr);
                }else{
                    $dataArr['reg_time']=time();//申请时间
                    $storeId = $storeModel->data($dataArr)->add();
                    session('mobile.store_id',$storeId);
                }
                if ($storeId !==false) {
                    //增加通知
                    insert_notice('admin','auditStore','新增店铺ID:'.$storeId.'待审核',0);
                    $this->ajaxInfoReturn("","提交申请成功，请等待审核!",1);
                }else{
                    $this->ajaxInfoReturn("","提交申请失败!",0);
                }
            }
        }else{
            //获取店铺信息
            $storeInfo = $storeModel->relation(true)->where(array('user_id'=>session("mobile.id")))->find();
            if($storeInfo){
                $action = "edit";
            }
            $this->assign('returnUrl',$returnUrl);
            if(!F('jsonStoreTypeList')){
                $store_type_list=M('StoreType')->select();
                foreach ($store_type_list as $k=>$v){
                    $store_type_list[$k]['value']=$v['type_name'];
                    unset($store_type_list[$k]['type_name']);
                }
                F('jsonStoreTypeList',json_encode($store_type_list));
            }
            $this->assign('storeTypeList',F('jsonStoreTypeList'));
            $this->assign('action',$action);
            if ($action=="add"){
                //初始化区域
                $city_id=cookie('city_id');
                $province_id=M('region')->where(array('id'=>$city_id))->find()['pid'];
                $area_list="$province_id,$city_id";
                $this->assign('area_list',$area_list);
                $this->display();
            }elseif($action=="edit"){
                //获取店铺申请信息
                $storeInfo['region']=str_replace(',',' ',mb_substr($storeInfo['region'],7));
                //获取店铺相册
                $photosArray=explode('|',$storeModel->where(array('sid'=>session('mobile.store_id')))->getField('photos'));
                $hidFocusPhoto=unserialize($photosArray[0]);
                $photos=unserialize($photosArray[1]);
                $rphotos=unserialize($photosArray[2]);
                $this->assign('hidFocusPhoto',$hidFocusPhoto);
                $this->assign('plist',$photos);
                $this->assign('rlist',$rphotos);
                $this->assign('info',$storeInfo);
                $this->display();
            }
            $this->assign('empty', C('NOT_DATA'));
        }

    }

    //店铺照片
    public function photosAction(){
        //验证店铺
        $this->valiStoreAction();
        if(IS_POST){
            $hidFocusPhoto=I('hidFocusPhoto');
            $photos=I('photo');
            $rphotos=I('rphoto');
            $data=I('data');
            if (!$photos){
                $this->ajaxInfoReturn('','请上传图片',0);
            }else{
                $data['photos']=serialize($hidFocusPhoto).'|'.serialize($photos).'|'.serialize($rphotos);
                //写入
                $re=M('store')->where(array('sid'=>session('mobile.store_id')))->save($data);
                if ($re!==false){
                    $this->ajaxInfoReturn('','上传成功',1);
                }else{
                    $this->ajaxInfoReturn('','上传失败',0);
                }
            }
        }else{
            $storeModel=D('Common/store');
            //获取店铺信息
            $storeInfo = $storeModel->relation(true)->where(array('user_id'=>session("mobile.id")))->find();
            //获取店铺相册
            $photosArray=explode('|',$storeModel->where(array('sid'=>session('mobile.store_id')))->getField('photos'));
            $hidFocusPhoto=unserialize($photosArray[0]);
            $photos=unserialize($photosArray[1]);
            $rphotos=unserialize($photosArray[2]);
            $this->assign('hidFocusPhoto',$hidFocusPhoto);
            $this->assign('plist',$photos);
            $this->assign('rlist',$rphotos);
            $this->assign('info',$storeInfo);
            $this->assign('returnUrl',$_SERVER['HTTP_REFERER']);
            $this->display();
        }
    }


    //上/下架
    public function doSaleAction(){
        if (IS_AJAX){
        $this->valiStoreAction();
        $goods_id=I('id');
        $saleStatus=I('get.saleStatus','on');
        $heavy=I('heavy');
            if($goods_id){
                $goodsModel=D('Common/Goods');
                $re=$goodsModel->doSale($goods_id,$saleStatus,$heavy);
                if ($re){
                    //获取商品发布人的信息
                    $info=$goodsModel->getGoodsPartInfo($goods_id);
                    if ($saleStatus=='on'){
                        send_sms_message('on_shelf',$info['mobile']);
                    }elseif ($saleStatus=='off'){
                        send_sms_message('off_shelf',$info['mobile']);
                    }
                    $this->ajaxInfoReturn('','更新宝贝上架状态成功',1);
                }else{
                    $this->ajaxInfoReturn('','更新宝贝上架状态失败',0);
                }
            }
        }else{
            $this->error('提交方式不正确',$_SERVER['HTTP_REFERER']);
        }
    }
    //变更商品置顶/最热/最新/推荐状态
    public function updateGoodsParamAction(){
        if (IS_AJAX){
            $this->valiStoreAction();
            $goods_id=I('id');
            $param=I('param');
            $goodsModel=D('Common/SellerDelivery');
            $re=$goodsModel->updateGoodsParam($goods_id,$param);
            if ($re){
                $this->ajaxInfoReturn('','成功',1);
            }else{
                $this->ajaxInfoReturn('','出错了',0);
            }
        }else{
            $this->error('提交方式不正确',$_SERVER['HTTP_REFERER']);
        }
    }
    //店铺订单列表
    public function storeOrderAction(){
        $this->valiStoreAction();
        $orderModel=D('Common/Order');
        $pageNum=I('p',1);
        $pageType=I('pageType',1);//列表类型,1待发货,2待完成,3已完成,0代付款
        $result=$orderModel->getStoreOrderPageList($pageNum,'',$pageType);
        if(IS_AJAX){
            foreach ($result['list'] as &$v){
                $v['goods_thumb']=explode(',',$v['goods_thumb']);
                $v['goods_title']=explode(',',$v['goods_title']);
            }
            $this->assign("isAjax",1);
            $this->assign('pageType',$pageType);
            $this->assign('list',$result['list']);
            $this->assign('pageCount',$result['pageCount']);
            $this->display();
        }else{
            $this->assign("isAjax",0);
            $this->assign('pageType',$pageType);
            $this->assign('pageCount',$result['pageCount']);
            $this->display();
        }
    }

    //改价
    public function editPriceAction(){
        if (!IS_AJAX){
            $this->tips('提交方式错误',1,500,$_SERVER['HTTP_REFERER']);
        }else {
            $order_sn = I('order_sn');
            $edit_price = (float)I('edit_price');
            $orderModel = D('Common/Order');
            $info = $orderModel->selectOrderDetail($order_sn);

            $goods_info = $info['list1'][0];
            $order_info = $info['list2'];
            if ($order_info['status']!=0 || $order_info['delivery_way'] !=0 ||$order_info['is_son'] !=2||$goods_info['store_id']!=session('mobile.store_id')){
                $this->ajaxInfoReturn('','订单状态不正确,请重试',0);
            }elseif ($edit_price<$goods_info['goods_price']){
                $this->ajaxInfoReturn('','不得低于商品底价',0);
            }else{
                //修改价格
                $data['money']=$edit_price;
                $re=M('market_order')->where(array('order_sn'=>$order_sn))->save($data);
                if ($re!==false){
                    $this->ajaxInfoReturn('','改价成功',1);
                }else{
                    $this->ajaxInfoReturn('','改价失败',0);
                }
            }
        }
    }
}
