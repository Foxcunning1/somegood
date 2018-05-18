<?php
//求购模型
namespace Admin\Model;
use Think\Model\RelationModel;
use Think\Page;

class MarketOrderModel extends RelationModel{

          //   'Article' => array(
          // 'mapping_type' => self::HAS_MANY,
          // 'class_name' => 'Article',
          // 'foreign_key' => 'userId',
          // 'mapping_name' => 'articles',
          // 'mapping_order' => 'create_time desc',
          // // 定义更多的关联属性
          // ……
          // )

  	 protected $_link = array(
       // 就是代表 MarketOrder与MarketOrderGoods关联
  		 'MarketOrderGoods'=> array(
  			 'class_name'   => 'MarketOrderGoods',   //要关联的模型类名    表名
  			 'mapping_type' => self::HAS_MANY,        //一对多    一对一一对多这种类型
  			 'mapping_name' => 'order_goods',   //关联的映射名称，用于获取数据用 该名称不要和当前模型的字段有重复，否则会导致关联数据获取的冲突。
  			 'foreign_key'  => 'order_sn',      //关联的外键名称
  			 'mapping_key'  => 'order_sn'
  		 ),


  		 'MarketOrder'=> array(
  			 'class_name'   => 'MarketOrder',
  			 'mapping_type' => self::HAS_MANY,
  			 'mapping_name' => 'order_children',
  			 'parent_key'   => 'parent_order_sn',
  			 'mapping_key'  => 'order_sn',
  		 ),

  		 'Users'=> array(
  			 'class_name'     => 'Users',
  			 'mapping_type'   => self::BELONGS_TO,
  			 'foreign_key'    => 'uid',
  			 'mapping_name'   => 'user',
  			 'mapping_fields' => 'user_name,mobile',
  		 ),

       'Store'=> array(
  			 'class_name'     => 'Store',
  			 'mapping_type'   => self::BELONGS_TO,
  			 'foreign_key'    => 'uid',
  			 'mapping_name'   => 'store',
  			 'mapping_fields' => 'store_name',
  		 ),

      //  'Goods'=> array(
  		// 	 'class_name'     => 'Goods',
  		// 	 'mapping_type'   => self::HAS_MANY,
  		// 	 'foreign_key'    => 'uid',
  		// 	 'mapping_name'   => 'store',
  		// 	 'mapping_fields' => 'store_name',
  		//  ),

  	 );

  	/**得到订单列表
  	 * @param int $pageNum                页码
  	 * @param array $condition            查询条件
  	 * @param array $pageCondition        分页条件
  	 * @param string $order               排序
  	 * @return mixed
  	 */
  	public function getOrderPageList($pageNum = 1 , $condition = array() , $pageCondition = array() , $order = 'add_time DESC,id DESC'){
  		//$condition['parent_order_sn'] = 0;
      $orderModule = D('Admin/MarketOrder');
  		$count = $orderModule->where($condition)->order('id DESC')->count();
  		$page=new \Think\Page($count,C('PAGE_SIZE'),$pageCondition);
      $page->setConfig('prev','上一页');
      $page->setConfig('next','下一页');
  		$result['show'] = $page->show();
  		$result['list'] = $orderModule->relation(true)->where($condition)->order($order)->page($pageNum,C('PAGE_SIZE'))->select();
  		return $result;
  	}


    /**得到一个订单的详细信息
     * @param $id int 订单id
     * @param $condition array 查询条件
     * @return mixed
     */
    public function getOneOrder($id = 0 ,$condition = array()){
      $orderModule = D('Admin/MarketOrder');
      if($id > 0 && empty($condition)) {
        $order = $orderModule->relation(true)->find($id);
      }elseif($id <= 0 && !empty($condition)){
        $order = $orderModule->relation(array('order_goods'))->where($condition)->find();
      }elseif($id > 0 && !empty($condition)){
        $order = $orderModule->relation(array('order_goods'))->where($condition)->find($id);
      }else{
        $order = null;
      }
      return $order;
    }





}
