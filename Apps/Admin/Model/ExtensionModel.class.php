<?php
//推广模型
namespace Admin\Model;
use Think\Model\RelationModel;
use Think\Page;
class ExtensionModel extends RelationModel{
    //商品类型表
    protected $_link = array(
    //厂家表
        'UsersSeller'=> array(
            'class_name'     => 'UsersSeller',
            'mapping_type'   => self::BELONGS_TO,
            'foreign_key'    => 'user_id',
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
    public function getDeliveryPageList($pageNum = 1,$order = 'add_time DESC',  $condition = array() , $pageCondition = array()){
        $goodsModule = D('Admin/Extension');
        $count = $goodsModule->where($condition)->count();
        $page = new \Think\Page($count,C('PAGE_SIZE'),$pageCondition);
        //带入查询条件的页码
        foreach ($pageCondition as $key => $val) {
            $page->parameter .= "$key=" . urlencode($val) . '&';
        }
        $result['show'] = $page->show();
        $result['list'] = $goodsModule->relation(true)->where($condition)->order($order)->page($pageNum,C('PAGE_SIZE'))->select();
        //var_dump($result['list']);
        return $result;
    }

}
