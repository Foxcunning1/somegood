<?php
/**
 * Created by PhpStorm.
 * User: ZHY
 * Date: 2017/8/14
 * Time: 18:34
 */
namespace Seller\Controller;
use Think\Controller;

class GoodsController extends BaseController{
    //卖家商品
    public function indexAction(){
        $goods_model=D('Common/Goods');
        $pageNum=I('p',1);
        $condition['user_id']=session('seller.id');
        $type=I('type','all');
        switch ($type){
            case 'off':
                $condition['g.is_onsale']=0;
                $pageCondition['type']='off';
                break;
            case 'sale':
                $condition['g.is_onsale']=1;
                $pageCondition['type']='sale';
                break;
            default:
                break;
        }
        $result=$goods_model->getSellerGoodsPageList($pageNum,C('GOODS_PAGE_SIZE'),'sales_volume DESC',$condition,$pageCondition);
        $this->assign('list',$result['list']);
        $this->assign('pageType',$type);
        $this->assign('pageShow',$result['show']);
        $this->assign('pageCount',$result['pageCount']);
        $this->display();
    }

    /**商品投放店铺列表及部分信息*/
    public function sellerDeliveryAction(){
        $id=I('id');
        $pageNum=I('p',1);
        $pageCondition['id']=$id;
        $goods_model=D('Common/Goods');
        //获取商品信息
        $info=$goods_model->getGoodsInfo($id);
        //获取该商品投放店铺列表及店铺库存等信息
        $sellerDeliveryModel=D('Common/SellerDelivery');
        $condition['sg_id']=$id;
        $result=$sellerDeliveryModel->getGoodsPageList($pageNum,C('SELLER_DELIVERY_PAGE_SIZE'),'sales_volume DESC',$condition,$pageCondition);
        $this->assign('info',$info);
        $this->assign('list',$result['list']);
        $this->assign('page',$result['show']);
        $this->display();
    }

    /**发布商品*/
    public function publishAction(){
        $goodsModel=M('Goods');
        $action=I('action','add');
        if (IS_POST){
            $data=I('data');
            $photoArr=I('photo');
            if(!$data['goods_thumb']){
                $this->ajaxInfoReturn('','请上传商品缩略图',0);
            }elseif(!$data['goods_title']){
                $this->ajaxInfoReturn('','请输入标题',0);
            }elseif(!$data['price']){
                $this->ajaxInfoReturn('','请输入商品价格',0);
            }elseif(!$data['category_id']){
                $this->ajaxInfoReturn('','请选择宝贝分类',0);
            }elseif(!$data['seller_column_id']){
                $this->ajaxInfoReturn('','请选择商品栏目',0);
            }else{
                $rphotoArr=I('rphoto');
                $data['original_img']=serialize($photoArr)."||".serialize($rphotoArr);
//                $data['volume']=$data['length']*$data['width']*$data['height']/1000000;
                if ($action=='add'){
                    $data['user_id']=session('seller.id');
                    $data['add_time']=$data['update_time']=time();//发布时间/最后更新时间
                    $data['goods_sn']=session('seller.id').get_goods_sn().time();
                    $good_id=$goodsModel->add($data);
                    if ($good_id){
                        $this->ajaxInfoReturn(U('Seller/Goods/index',array('type'=>'all')),'添加成功','y');
                    }else{
                        $this->ajaxInfoReturn('','添加失败','n');
                    }
                }elseif($action=='edit'){
                    $data['update_time']=time();//最后更新时间
                    $id=I('id',0);
                    $re=$goodsModel->where(array('id'=>$id,'user_id'=>session('seller.id')))->save($data);
                    if ($re!==false){
                        $this->ajaxInfoReturn(U('Seller/Goods/index',array('type'=>'all')),'修改成功','y');
                    }else{
                        $this->ajaxInfoReturn('','修改成功','n');
                    }
                }

            }
        }else{
            //获取商品分类
            $category_list = require_once ("./Cache/category/category.cache.sort.php");//商品分类
            $this->assign('category_list', $category_list);
            //获取卖家栏目
            //获取卖家栏目
            $columnModel=D('Common/Goods');
            $map['user_id']=session('seller.id');
            $column_list=$columnModel->getSellerColumnTreeList($map);
            $this->assign('column_list',$column_list);
            $id=I('id',0);
            if($action=='edit'){
                //获取商品信息
                $info=$goodsModel->field('id,goods_title,category_id,price,market_price,goods_thumb,original_img,content,params,details,online_stock')->where(array('id'=>$id,'user_id'=>session('seller.id')))->find();
                $photo_alt_array=explode('||',$info['original_img']);
                $info['plist']=unserialize($photo_alt_array[0]);
                $info['rlist']=unserialize($photo_alt_array[1]);
                unset($info['original_img']);
                $this->assign('info',$info);
            }
            $this->assign('action',$action);
            $this->display();
        }
    }

    //变更商品上下架状态
    public function updateGoodsSaleAction(){
        $id=I('id',0);
        $status=I('status','on');
        if($id){
            if($status=='on')$re=M('goods')->where(array('id'=>$id,'user_id'=>session('seller.id')))->setField('is_onsale',1);
            if($status=='off')$re=M('goods')->where(array('id'=>$id,'user_id'=>session('seller.id')))->setField('is_onsale',0);
            if($re){
                $this->ajaxInfoReturn('','提交成功',1);
            }else{
                $this->ajaxInfoReturn('','提交失败',0);
            }
        }
    }

    //商品申请推广
    public function extensionAction(){
        $data['length']=I('length',0);
        $data['width']=I('width',0);
        $data['height']=I('height',0);
        $data['volume']=$data['length']*$data['width']*$data['height']/1000000;
        $goods_id=I('goods_id',0);
        $goodsModel=M('goods');
        if($data['volume']*$goods_id!=0){
            $info=$goodsModel->field('goods_title,category_id,volume,goods_thumb')->where(array('id'=>$goods_id,'user_id'=>session('seller.id'),'is_extension'=>0))->find();
            if($info){
                //补全该商品长宽高信息
                $re=$goodsModel->where(array('id'=>$goods_id,'user_id'=>session('seller.id')))->save($data);
                if ($re!==false){
                    //插入推荐表
                    $info['goods_id']=$goods_id;
                    $info['user_id']=session('seller.id');
                    $info['length']=$data['length'];
                    $info['width']=$data['length'];
                    $info['height']=$data['length'];
                    $info['add_time']=time();
                    $ex_id=M('extension')->add($info);
                    if ($ex_id){
                        $goodsModel->where(array('id'=>$goods_id))->setField('is_extension',1);
                        $this->ajaxInfoReturn('','申请成功,工作人员会尽快与您取得联系',1);
                    }else{
                        $this->ajaxInfoReturn('','提交失败,请重试',0);
                    }
                }else{
                    $this->ajaxInfoReturn('','提交失败,请重试',0);
                }
            }else{
                $this->ajaxInfoReturn('','该商品已申请推广,请勿重复提交',0);
            }
        }else{
            $this->ajaxInfoReturn('','长宽高信息不可为空,请重试',0);
        }
    }

    //打印列表
    public function printListAction(){
        if (I('ids')!=false){
            $this->indexAction();
        }else{
            $this->display();
        }
    }
    /**生成商品url地址二维码
     * @Param       string           code_url           要生成二维码的内容，如：url地址，内容等
     */
    public function qrCodeAction(){
        vendor('phpqrcode.phpqrcode');
        $value =  I('code_url');
        $errorCorrectionLevel = 'M';//容错级别
        $matrixPointSize = 6;//生成图片大小
        // //生成二维码图片
        \QRcode::png($value, false, $errorCorrectionLevel, $matrixPointSize, 2);
    }
}