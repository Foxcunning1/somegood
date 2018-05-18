<?php
/* 关键词模型类
 * Created on 2016-6-16
 *
 */
 namespace Common\Model;
 use Think\Model;

class SellerDeliveryModel extends Model{

    /**得带页码的商品列表
     * @param $pageNum            int              页码
     * @param $pageSize           int              每页显示的数量
     * @param $order              string           排序
     * @param $condition          array            查询条件
     * @param $pageCondition      array            分页条件
     * @return mixed              array            包括分页信息和查询结果数组
     
     */
    public function getGoodsPageList($pageNum = 1, $pageSize = 0, $order = 'sales_volume DESC',  $condition = array() , $pageCondition = array()){
        $goodsModule = M('SellerDelivery');
        $count = $goodsModule->alias("sd")->field("sd.*,g.user_id,g.content,gc.title AS category_name")
            ->join("LEFT JOIN __GOODS__ AS g ON sd.sg_id = g.id")
            ->join("LEFT JOIN __GOODS_CATEGORY__ AS gc ON sd.category_id = gc.id")
            ->where($condition)->count();
        if($pageSize==0){
            $pageSize = C('GOODS_PAGE_SIZE');
        }
        $page = new \Think\Page($count,$pageSize,$pageCondition);
        $result['show'] = $page->show();
        $result['pageCount'] = $page->pageCount();
        $result['list'] = $goodsModule->alias("sd")->field("sd.*,g.user_id,g.add_time,g.goods_sn,g.content,g.original_img,gc.title AS category_name,s.store_name,s.address,r.name as area_name,st.type_name as store_type")
            ->join("LEFT JOIN __GOODS__ AS g ON sd.sg_id = g.id")
            ->join("LEFT JOIN __GOODS_CATEGORY__ AS gc ON sd.category_id = gc.id")
            ->join("LEFT JOIN __STORE__ AS s ON sd.store_id = s.sid")
            ->join("LEFT JOIN __REGION__ AS r ON s.area_id = r.id")
            ->join("LEFT JOIN __STORE_TYPE__ AS st ON s.store_type = st.id")
            ->where($condition)->order($order)->page($pageNum,$pageSize)->select();
        return $result;
    }

    /**商品上架/下架列表
     * @param $pageType           string           列表类型,sale上架,off下架
     * @param $pageNum            int              页码
     * @param $pageSize           int              每页显示的数量
     * @param $order              string           排序
     * @return mixed              array            包括分页信息和查询结果数组
     */
    public function getStoreGoodsList($pageType,$pageNum,$pageSize,$order){
        $goods_model=D('Common/SellerDelivery');
        $condition['store_id']=session('mobile.store_id');
        switch ($pageType){
            case "sale":
                $condition['is_onsale']=1;
                $pageCondition['is_onsale']=1;
                $pageTitle="在售";
                $pageCondition['pageType']="sale";
                break;
//            case "success":
//                $successList=M('market_order_goods')->alias('og')->field('goods_id')->join("LEFT JOIN __MARKET_ORDER__ AS o ON og.order_sn = o.order_sn")->where(array('og.store_id'=>$condition['store_id'],'o.status'=>3))->select();
//                foreach ($successList as &$v){
//                    $v=$v['goods_id'];
//                }
//                if ($successList!=array()){
//                    $condition['g.id']=array('in',$successList);
//                }else{
//                    $condition['g.id']=array('in',array(0));
//                }
//                $pageTitle="成功售出";
//                break;
            case "off":
                $condition['end_time']=array('lt',time());
                $condition['end_time']=array('gt',0);
                $condition['is_onsale']=0;
                $pageTitle="失效";
                $pageCondition['pageType']="off";
                break;
        }
        $result=$goods_model->getGoodsPageList($pageNum,$pageSize,$order,$condition,$condition);
        foreach ($result['list'] as &$v){
            $photo_alt_array=explode('||',$v['original_img']);
            $v['plist']=unserialize($photo_alt_array[0]);
            $v['rlist']=unserialize($photo_alt_array[1]);
        }
        $result['pageTitle']=$pageTitle;
        return $result;
    }

    /**变更商品置顶/最热/最新/推荐状态
     * @param $goods_id           int              商品ID
     * @param $param              string           置顶/最热/最新/推荐状态
     * @return bool
     */
    public function updateGoodsParam($goods_id,$param){
        $goodsModel=D('Common/SellerDelivery');
        $store_id=session('mobile.store_id')?session('mobile.store_id'):session('store.store_id');
        switch ($param){
            case "top":
                $data['is_top']=I('status');
                break;
            case "rec":
                $data['is_rec']=I('status');
                break;
            case "hot":
                $data['is_hot']=I('status');
                break;
            case "new":
                $data['is_new']=I('status');
                break;
        }
        $re=$goodsModel->where(array('store_id'=>$store_id,'sg_id'=>$goods_id))->save($data);
        return $re;
    }

    /**处理即将过期的商品
    */
    public function handleOverGoods(){
        /**获取十天内即将过期的商品进行通知*/
        $gt_time=strtotime('+9days');
        $lt_time=strtotime('+10days');
        $condition['is_onsale']=1;
        $condition['end_time']=array('between',array($gt_time,$lt_time));
        $sellerDeliveryModel=M('sellerDelivery');
        $soon_over_array=$sellerDeliveryModel->alias('sd')->field('us.name,us.mobile,sg_id,count(sg_id) as count')
            ->join('LEFT JOIN __USERS_SELLER__ AS us ON sd.seller_id = us.user_id')
            ->where($condition)->group('sg_id')->select();
        foreach ($soon_over_array as $v){
            //短信通知
            send_sms_message('soon_over_goods',$v['mobile'],'',array($v['name'],$v['sg_id'],$v['count'],'还有10天'));
        }
        /**处理过期的商品*/
        $condition['end_time']=array('lt',time());
        $over_array=$sellerDeliveryModel->alias('sd')->field('us.name,us.mobile,sd.id,box_id,sg_id,count(sg_id) as count')
            ->join('LEFT JOIN __USERS_SELLER__ AS us ON sd.seller_id = us.user_id')
            ->where($condition)->group('sg_id')->select();
        foreach ($over_array as $v){
            //下架处理
            $sellerDeliveryModel->where(array('id'=>$v['id']))->setField('is_onsale',0);
            //解锁对应格子
            M('StoreBox')->where(array('id'=>$v['box_id']))->setField('is_lock',0);
            //短信通知
            send_sms_message('soon_over_goods',$v['mobile'],'',array($v['name'],$v['sg_id'],$v['count'],'已'));
        }
    }

    /**获得某个商品的在售店铺列表
     * @param $id                 int              商品id
     * @return mixed              array            查询结果数组
     */
    public function getGoodsStoreList($id){
        $sellerDeliveryModel = D('Common/SellerDelivery');
        $list=$sellerDeliveryModel->alias('sd')->field('sd.id as id,s.sid as store_id,s.store_name')
            ->join('LEFT JOIN __STORE__ AS s ON sd.store_id = s.sid')
            ->where(array('sg_id'=>$id))
            ->select();
        return $list;
    }
}
