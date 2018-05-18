<?php
namespace Admin\Controller;
use Think\Controller;

class SellerDeliveryController extends BaseController{

  /**商品推广列表*/
  public function goodsAction(){
    if(!parent::checkAdminRoleAction('goods_delivery','view')){
        jscript_msg_tips('亲，您无权进行操作！');
    }
    //通知设为已读
    if (I('notice')==1){
        set_readed('admin','waitDelivery');
    }
      $categoryId = I('category_id');
      $property = I('property','all','strip_tags');
      $keywords = I('keywords');
      $pageNum = I('p',1,'strip_tags');
    if($keywords) {
        $condition['goods_title'] = array('like', '%' . $keywords . '%');
        $pageCondition['keywords'] = $keywords;
    }
    if($categoryId) {
        $condition['category_id'] = $categoryId;
        $pageCondition['category_id'] = $categoryId;
        $this->assign('category_id',$categoryId);
    }
    if($property!="all"){
      $property1 = 0;
      switch($property){
        case 'wait':
          $property1 = 0;
          break;
        case 'start':
          $property1 = 1;
          break;
        case 'end':
          $property1 = 2;
          break;
      }
      $condition['status'] = $property1;
      $pageCondition['property'] = $property;
    }
    $category_list = D('Admin/Goods')->getGoodsCategoryTreeListAction();//商品分类
    $GoodsModule = D('Admin/Extension');
    $order = 'add_time DESC';
    $result = $GoodsModule->getDeliveryPageList($pageNum,$order,$condition,$pageCondition);
    //var_dump($result);
      //属性列表
      $property_list = array(
          'all' => '所有属性',
          'wait' => '待投放',
          'start' => '投放中',
          'end' => '投放完成'
      );
    $this->assign('category_list',$category_list);
    $this->assign('property', $property);
    $this->assign('property_list',$property_list);
    $this->assign('list',$result['list']);
    $this->assign('page',$result['show']);
    $this->display();
  }

  /**
   *未投放的商品删除
   * parm     string        $ids               导航ID字符串
   */
  public function delAction(){
      if(!parent::checkAdminRoleAction('goods_delivery','delete')){
          jscript_msg_tips('亲，您无权进行操作！');
      }
      $ids = I('ids');
      $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
      $goodsModel  = M('Goods');
      //判断id是数组还是一个数值
      $count_record = 0;
      if(is_array($ids)){
          for($i = 0; $i < count($ids); $i++){
              $condition["id"] = $ids[$i];
              $del_count = $goodsModel->where(array('id'=>$ids[$i],'status'=>0))->delete();
              if($del_count!==false){
                  $count_record += $del_count;
              }else{
                  $count_record = false;
              }
          }
      }else{
          $count_record = $goodsModel->where(array('id'=>$ids,'status'=>0))->delete();
      }

      if($count_record!==false) {
          add_admin_log('del',$_SESSION['admin']['admin_name'].'成功删除了'.$count_record.'条商品!');
          jscript_msg("删除成功 ",$returnUrl);
      }else{
          jscript_msg_tips("删除失败！");
      }
  }

    /**投放页
     */
    public function deliveryAction(){
        if(!parent::checkAdminRoleAction('goods_delivery','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $type=I('type');
        $id=I('id');
        $info=D('Admin/Goods')->getDeliveryById($id);
        switch ($type){
            case 'edit':
                $photo_alt_array=explode('||',$info['original_img']);
                $info['plist']=unserialize($photo_alt_array[0]);
                $info['rlist']=unserialize($photo_alt_array[1]);
                unset($info['original_img']);
                break;
            case 'delivery':
                //获取店铺类型列表
                $store_type_list=M('StoreType')->select();
                //获取省级
                $regionModel = D('Common/Region');
                $regionList = $regionModel->getAreaList(0);
                $this->assign('type_list',$store_type_list);
                $this->assign('list',$regionList);
                break;
            case 'add':
                //获取用户信息
                $user_id=I('user_id');
                if($user_id){
                    $info=M('UsersSeller')->where(array('user_id'=>$user_id))->find();
                    $this->assign('info',$info);
                    $this->assign('user_id',$user_id);
                }else{
                    jscript_msg_tips('卖家信息错误,请重试');
                }
                break;
        }
        $category_list = require_once ("./Cache/category/category.cache.sort.php");//商品分类
        $this->assign('category_list', $category_list);
        $this->assign('info',$info);
        $this->assign('id',$id);
        $this->assign('type',$type);
        $this->assign('returnUrl',$_SERVER['HTTP_REFERER']);
        $this->display();
    }

    /**获得指定区域的信息
     */
    public function getRegionListAction(){
        $pid = I('id',0);
        $regionModel = D('Common/Region');
        $list = $regionModel->getAreaList($pid);
        $this->ajaxInfoReturn($list,"获取成功！",1);
    }

    /**AJAX获取店铺列表*/
    public function getStoreAction(){
        if (!IS_AJAX){
            $this->ajaxInfoReturn('提交方式错误','',0);
        }else{
            $storeType=I('storeType');
            $region=I('region');
            $l=I('length');
            $w=I('width');
            $h=I('height');
            if ($storeType>0){
                $condition['store_type']=$storeType;
            }
            if ($region>0){
                $condition['area_list']=array('like','%,'.$region.',%');
            }
            //店铺货柜格子剩余大于0
            $condition['vacancy']=array('gt',0);
            $store_list = M('store')->alias('s')->field('sid,store_name,logo,s.lng,s.lat,code,area_id')->join("LEFT JOIN __REGION__ AS r ON s.area_id = r.id")->where($condition)->select();
            //筛选店铺
            if($store_list!==false){
                //查询该商品现有的投放店铺
                $store_id_list=M('SellerDelivery')->field('store_id')->where(array('sg_id'=>I('id')))->select();
                if (count($store_id_list)>0){
                    foreach ($store_id_list as $k=>$v){
                        $store_id_list[$k]=$v['store_id'];
                    }
                    //从全部店铺中剔除现有店铺
                    foreach ($store_list as $k=>$v){
                        if(in_array($v['sid'],$store_id_list)){
                            unset($store_list[$k]);
                        }
                    }
                }
                //筛选有符合大小的格子的店铺
                foreach ($store_list as $k=>$v){
                    //查询该店铺未被锁定的格子
                    $boxList=M('StoreBox')->alias('sb')->field('sb.id,l,w,h,l*w*h as volume')->join('LEFT JOIN __STORE_BOX_SIZE__ AS sbs ON sb.size_id = sbs.id')->where(array('store_id'=>$v['sid'],'is_lock'=>0))->order('volume asc')->select();
                    foreach ($boxList as $sk=>$sv){
                        //查找符合大小的格子
                        if ($l<$sv['l']&&$w<$sv['w']&&$h<$sv['h']){
                            $store_list[$k]['box_id']=$sv['id'];
                            break;
                        }
                    }
                    if (!$store_list[$k]['box_id'])unset($store_list[$k]);
                }
                $this->ajaxInfoReturn($store_list,'',1);
            }else{
                $this->ajaxInfoReturn('获取失败','',0);
            }
        }
    }

    /**投放处理*/
    public function addDeliveryAction(){
        if(!parent::checkAdminRoleAction('goods_delivery','edit')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $id=I('sg_id');
        $type=I('type');
        $returnUrl=I('returnUrl');
        $sellerGoodsModel=M('Goods');
        $data=I('data');
        if ($type=='edit'){
            //修改产品信息
            unset($data['user_id']);
            $data['volume']=$data['length']*$data['width']*$data['height']/1000000;
            $re=$sellerGoodsModel->where(array('id'=>$id))->save($data);
            if ($re!==false){
                jscript_msg("修改成功 ", $returnUrl);
            }else{
                jscript_msg_tips("修改失败!");
            }

        }elseif($type=='add'){
            //发布产品
            $data=I('data');
            if ($data['user_id']){
                $photoArr=I('photo');
                if(count($photoArr)<3){
                    jscript_msg_tips("最少上传三张宝贝图片更容易成交哦!");
                }elseif(!$data['goods_thumb']){
                    jscript_msg_tips("请上传商品缩略图!");
                }elseif(!$data['goods_title']){
                    jscript_msg_tips("请输入标题!");
                }elseif(!$data['price']){
                    jscript_msg_tips("请输入商品价格!");
                }elseif(!$data['category_id']){
                    jscript_msg_tips("请选择宝贝分类!");
                }else{
                    $rphotoArr=I('rphoto');
                    $data['original_img']=serialize($photoArr)."||".serialize($rphotoArr);
                    $data['volume']=$data['length']*$data['width']*$data['height']/1000000;
                    $good_id=$sellerGoodsModel->add($data);
                    if ($good_id){
                        add_admin_log('add',$_SESSION['admin']['admin_name'].'成功为卖家(ID:'.$data['user_id'].')新增了1条商品(ID:'.$good_id.')!');
                        jscript_msg("添加成功 ", $returnUrl);
                    }else{
                        jscript_msg_tips("添加失败!");
                    }
                }
            }else{
                jscript_msg_tips('卖家信息错误,请重试');
            }
        }elseif ($type=='delivery'){
            //新增投放店铺
            $storeStr=I('storeId_month_deliveryNum');
            if ($storeStr==null){
                jscript_msg_tips("请选择店铺!");
            }else{
                $data['sg_id']=$id;
                $data['seller_id']=I('seller_id');
                $storeArray=explode(',',$storeStr);
                $num='';
                foreach ($storeArray as $v){
                    $deliveryInfo=explode('|',$v);
                    $data['store_id']=$deliveryInfo[0];
                    $data['month']=$deliveryInfo[1];
                    $data['num']=$deliveryInfo[2];
                    $data['box_id']=$deliveryInfo[3];
                    M()->startTrans();
                    //投放店铺表增加记录
                    $re1=M('SellerDelivery')->add($data);
                    //锁定格子
                    $re2=M('StoreBox')->where(array('id'=>$data['box_id']))->setField('is_lock',1);
                    //店铺表格子剩余减1
                    $re3=M('Store')->where(array('sid'=>$data['store_id']))->setDec('vacancy');
                    if($re1&&$re2&&$re3){
                        M()->commit();
                        $num++;
                    }else{
                        M()->rollback();
                    }
                }
                if($num>0){
                    $sellerGoodsModel->where(array('id'=>$id))->setField('status',1);
                    add_admin_log('add',$_SESSION['admin']['admin_name'].'成功给商品(ID:'.$data['sg_id'].')新增了'.$num.'家店铺投放计划!');
                    jscript_msg("成功生成".$num."家店铺投放计划", $returnUrl);
                }else{
                    jscript_msg_tips("失败!");
                }
            }
        }
    }

    /**投放店铺列表*/
    public function deliveryStoreAction(){
        if(!parent::checkAdminRoleAction('goods_delivery','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $sg_id=I('id');
        $pageNum=I('p',1);
        $condition['sg_id']=$sg_id;
        $pageCondition['id']=$sg_id;
        $sellerDeliveModel=D('Admin/Goods');
        $result=$sellerDeliveModel->getDeliveryStoreList($pageNum,'',$condition,$pageCondition);
        $this->assign('list',$result['list']);
        $this->assign('page',$result['show']);
        $this->display();
    }

    /**完成投放*/
    public function doDeliveryAction(){
        if(!parent::checkAdminRoleAction('goods_delivery','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $id=I('id');
        $deliveryModel=M('SellerDelivery');
        $info=$deliveryModel->field('month,num,stock,sg_id')->where(array('id'=>$id))->find();
        $data['is_delivery']=1;
        $data['is_onsale']=1;
        $data['end_time']=strtotime('+'.$info['month'].'month');
        $data['stock']=$info['stock']+$info['num'];
        $re=$deliveryModel->where(array('id'=>$id))->save($data);
        //查询该商品投放计划中未投放的数量,如没有将商品状态改为投放完成
        $count=$deliveryModel->where(array('sg_id'=>$info['sg_id'],'is_delivery'=>0))->count();
        if ($count==0){
            M('goods')->where(array('id'=>$info['sg_id']))->setField('status',2);
        }
        if ($re!==false){
            //获取投放信息
            $info=$deliveryModel->field('sg_id,store_id')->where(array('id'=>$id))->find();
            add_admin_log('delivery',$_SESSION['admin']['admin_name'].'完成了商品(ID:'.$info['sg_id'].')在店铺(ID:'.$info['store_id'].')的投放计划(ID:'.$id.')!');
            jscript_msg("投放成功",$_SERVER['HTTP_REFERER']);
        }else{
            jscript_msg_tips("投放失败!");
        }
    }
    /**删除投放店铺*/
    public function delDeliveryStoreAction(){
        $ids=I('ids');
        $condition['id']=array('in',$ids);
        $sellerDelivery=M('SellerDelivery');
        //获取要删除的投放所占用的格子ID
        $boxIdsArr=$sellerDelivery->where($condition)->getField('box_id',true);
        //获取要增加格子空余的店铺
        $storeIdsArr=$sellerDelivery->where($condition)->getField('store_id',true);

        M()->startTrans();
        //删除投放店铺
        $re1=$sellerDelivery->where($condition)->delete();
        //解锁对应Box
        $re2=M('StoreBox')->where(array('id'=>array('in',$boxIdsArr)))->setField('is_lock',0);
        //店铺格子空余增加
        $successNum=0;
        foreach ($storeIdsArr as $v){
            $re=M('store')->where(array('sid'=>$v))->setInc('vacancy');
            if ($re){
                $successNum++;
            }
        }
        if($successNum==count($storeIdsArr))$re3=true;
        if ($re1&&$re2&&$re3){
            M()->commit();
            add_admin_log('del',$_SESSION['admin']['admin_name'].'成功删除了'.$successNum.'条投放计划!');
            jscript_msg("删除成功",$_SERVER['HTTP_REFERER']);
        }else{
            M()->rollback();
            jscript_msg_tips("删除失败!");
        }
    }
}
?>
