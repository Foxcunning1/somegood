<?php
/**
 * Created by PhpStorm.
 * User: ZHY
 * Date: 2017/8/14
 * Time: 18:34
 */
namespace Store\Controller;
use Think\Controller;

class GoodsController extends BaseController{
    //店铺商品
    public function indexAction(){
        $goods_model=D('Common/SellerDelivery');
        $pageType=I('pageType');//列表类型,sale在售,off下架
        $pageNum=I('p',1);
        $result=$goods_model->getStoreGoodsList($pageType,$pageNum,C('GOODS_PAGE_SIZE'),'');
        $this->assign('list',$result['list']);
        $this->assign('pageShow',$result['show']);
        $this->assign('pageType',$pageType);
        $this->assign('pageCount',$result['pageCount']);
        $this->display();
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