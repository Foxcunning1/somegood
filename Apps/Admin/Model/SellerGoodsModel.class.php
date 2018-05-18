<?php
//商品模型
namespace Admin\Model;
use Think\Model\RelationModel;

class SellerGoodsModel extends RelationModel{

    protected $_link = array(
    //厂家表
        'UsersSeller'=> array(
            'class_name'     => 'UsersSeller',
            'mapping_type'   => self::BELONGS_TO,
            'foreign_key'    => 'users_id',
            'mapping_name'   => 'seller',
            'mapping_fields' => 'company_name',
            'as_fields'      => 'company_name:company_name',
        ),
    //投放店铺表
        'SellerDelivery'=> array(
            'class_name'   =>'SellerDelivery',
            'mapping_type' => self::HAS_MANY,
            'foreign_key'   => 'sg_id',
            'mapping_name'  => 'store',
        ),
    //商品分类表
        'GoodsCategory'=> array(
            'class_name'     => 'GoodsCategory',
            'mapping_type'   =>self::BELONGS_TO,
            'foreign_key'    => 'category_id',
            'mapping_fields' => 'title',
            'as_fields'      => 'title:category_title',
        ),
    );

    /**得带页码的商品列表
     * @param $pageNum            int              页码
     * @param $order              string           排序
     * @param $condition          array            查询条件
     * @param $pageCondition      array            分页条件
     * @return mixed              array            包括分页信息和查询结果数组
     */
    public function getDeliveryPageList($pageNum = 1,$order = 'id DESC',  $condition = array() , $pageCondition = array()){
        $goodsModule = D('Admin/Goods');
        $count = $goodsModule->where($condition)->count();
        $page = new \Think\Page($count,C('PAGE_SIZE'),$pageCondition);
        $result['show'] = $page->show();
        $result['list'] = $goodsModule->relation(true)->where($condition)->order($order)->page($pageNum,C('PAGE_SIZE'))->select();
        return $result;
    }
    /**获得单条产品的信息
     * @param $id               int                 产品ID
     * @return mixed            array               产品信息
     */
    public function getDeliveryById($id){
        $info=D('Admin/SellerGoods')->relation(true)->where(array('id'=>$id))->find();
        return $info;
    }

    /**获取商品投放店铺列表
     * @param $pageNum            int                页码
     * @param $order              string             排序
     * @param $condition          array              查询条件
     * @param $pageCondition      array              分页条件
     * @return mixed              array              包括分页信息和查询结果数组
     */
    public function getDeliveryStoreList($pageNum=1,$order='is_delivery asc,end_time desc,stock asc',$condition,$pageCondition){
        $deliveryModel=M('SellerDelivery');
        $count = $deliveryModel->alias('sd')->field('num,stock,is_delivery,end_time,s.store_name,sn')
                                    ->join('LEFT JOIN __STORE__ AS s on sd.store_id = s.sid')
                                    ->join('LEFT JOIN __STORE_BOX__ AS sb on sd.box_id = sb.id')
                                    ->where($condition)
                                    ->count();
        $page = new \Think\Page($count,C('PAGE_SIZE'),$pageCondition);
        $result['show'] = $page->show();
        $result['list'] =$deliveryModel->alias('sd')->field('sd.id,goods_title,num,stock,is_delivery,end_time,s.store_name,sn')
                                    ->join('LEFT JOIN __STORE__ AS s on sd.store_id = s.sid')
                                    ->join('LEFT JOIN __STORE_BOX__ AS sb on sd.box_id = sb.id')
                                    ->where($condition)
                                    ->order($order)
                                    ->page($pageNum,C('PAGE_SIZE'))
                                    ->select();
        return $result;
    }

}
