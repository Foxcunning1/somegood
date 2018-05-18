<?php
/* 关键词模型类
 * Created on 2016-6-16
 *
 */
 namespace Common\Model;
 use Think\Model;

class GoodsModel extends Model{

	/**获得商品详情信息
	 * @param $order              string           排序
	 * @param $condition          array            查询条件
	 * @return mixed              array            查询结果数组
	 */
		public function getDetailById($condition,$pageNum = '1',$order = 'distance ASC'){
            $goodsModle = D('Common/Goods');
            $page_size=8;
            //统计在售商家总数量
            $total=$goodsModle->alias('g')->field("sd.id,g.original_img,g.user_id,g.goods_title,g.market_price,g.price,g.browse_num,s.store_name,r.shortname,s.lng,s.lat,g.goods_sn")
            ->join("LEFT JOIN __SELLER_DELIVERY__ AS sd ON sd.sg_id = g.id")
            ->join("LEFT JOIN __STORE__ AS s ON s.sid = sd.store_id")
            ->join("LEFT JOIN __REGION__ AS r ON r.id = s.area_id")
            ->where($condition)->count();
            $result['counts']=$total;
            $result['page']=ceil($total/$page_size);
            //获得用户经纬度
            $lng1=session("lng");//从session取出用户经纬度
            $lat1=session("lat");
            if (empty($lng1) || empty($lat1)) {
                $lng1 = 114.06;
                $lat1  = 22.61;
            }
            //获得商品信息
            $result['list'] = $goodsModle->alias('g')->field("sd.id,s.sid,g.details,g.user_id,g.original_img,g.goods_title,g.params,g.length,g.width,g.height,g.content,sd.stock,g.market_price,g.goods_sn,gc.title,g.price,g.browse_num,s.store_name,r.shortname,s.lng,s.lat,round(6378.138*2*asin(sqrt(pow(sin( ($lat1*pi()/180-s.lat*pi()/180)/2),2)+cos($lat1*pi()/180)*cos(s.lat*pi()/180)* pow(sin( ($lng1*pi()/180-s.lng*pi()/180)/2),2)))*1000)*0.001 as distance")
            ->join("LEFT JOIN __SELLER_DELIVERY__ AS sd ON sd.sg_id = g.id")
            ->join("LEFT JOIN __STORE__ AS s ON s.sid = sd.store_id")
            ->join("LEFT JOIN __GOODS_CATEGORY__ AS gc ON gc.id = g.category_id")
            ->join("LEFT JOIN __REGION__ AS r ON r.id = s.area_id")
            ->where($condition)->page("$pageNum,$page_size")->order($order)->select();

            return $result;
		}


    /**获得某个商品的部分信息
     * @param $id                 int              seller_delivery_id
     * @return mixed              array            查询结果数组
     */
    public function getGoodsPartInfo($id){
        $sellerDeliveryModel = M('seller_delivery');
        $info = $sellerDeliveryModel->alias("sd")->field("sd.id,g.online_stock,g.user_id as seller_id,sd.store_id,s.store_name,sd.goods_title,sd.goods_thumb,sd.market_price,sd.price,sd.stock")
                ->join("LEFT JOIN __GOODS__ AS g ON g.id = sd.sg_id")
                ->join("LEFT JOIN __STORE__ AS s ON s.sid = sd.store_id")
                ->where(array('sd.id' =>$id))->find();
        return $info;
    }

    /**获得某个商品的信息
     * @param $id                 int              商品id
     * @return mixed              array            查询结果数组
     */
    public function getGoodsInfo($id){
        $GoodsModel = D('Common/Goods');
        $info = $GoodsModel->alias("g")->field("g.*,gc.title as category_name,count(sd.id) as store_count,SUM(sales_volume) as total_sales_volume")
            ->join("LEFT JOIN __GOODS_CATEGORY__ AS gc ON g.category_id = gc.id")
            ->join("LEFT JOIN __SELLER_DELIVERY__ AS sd ON g.id = sd.sg_id")
            ->where(array('g.id' =>$id,'g.is_onsale'=>1))->find();
        $photo_alt_array=explode('||',$info['original_img']);
        $info['plist']=unserialize($photo_alt_array[0]);
        $info['rlist']=unserialize($photo_alt_array[1]);
        unset($info['original_img']);
        return $info;
    }

	/**获得前几个商品数据
	 * @param $topCount            int             要获取商品的数量
	 * @param $order              string           排序
	 * @param $condition          array            查询条件
	 * @return mixed              array            查询结果数组
	 */
	public function getTopGoodsList($topCount = 0, $order = 'id DESC',  $condition = array()){
		$goodsModule = M('goods');
		if($topCount>0){
			$result = $goodsModule->alias("g")->field("g.id,g.goods_title,g.price,g.content,gc.title AS category_name,SUM(sd.sales_volume) as sales_volume")
				->join("LEFT JOIN __GOODS_CATEGORY__ AS gc ON g.category_id = gc.id")
				->join("LEFT JOIN __SELLER_DELIVERY__ AS sd ON g.id = sd.sg_id")
				->where($condition)->order($order)->limit($topCount)->select();
		}else{
			$result = $goodsModule->alias("g")->field("g.id,g.goods_title,g.price,g.content,gc.title AS category_name,SUM(sd.sales_volume) as sales_volume")
				->join("LEFT JOIN __GOODS_CATEGORY__ AS gc ON g.category_id = gc.id")
				->join("LEFT JOIN __SELLER_DELIVERY__ AS sd ON g.id = sd.sg_id")
				->where($condition)->order($order)->select();
		}
		return $result;
	}

    /**得带页码的厂家商品列表
    * @param            $pageNum               int              页码
    * @param            $pageSize              int              每页显示的数量
    * @param            $order                 string           排序
    * @param            $condition             array            查询条件
    * @param            $pageCondition         array            分页条件
    * @return           mixed                                   包括分页信息和查询结果数组
    */
    public function getSellerGoodsPageList($pageNum = 1, $pageSize = 10, $order = 'sales_volume DESC',  $condition = array() , $pageCondition = array()){
        $goodsModel = D('Common/Goods');
        $count = $goodsModel->alias("g")->where($condition)->count();
        $page = new \Think\Page($count,$pageSize,$pageCondition);
        $result['show'] = $page->show();
        $result['pageCount'] = $page->pageCount();
        $result['list'] = $goodsModel->alias("g")->field("g.id,g.is_onsale,g.is_extension,g.length,g.width,g.height,g.status,g.add_time,g.online_stock,g.online_sales_volume,g.goods_thumb,g.original_img,g.goods_title,g.price,g.content,gc.title AS category_name,SUM(sd.sales_volume) as sales_volume")
        ->join("LEFT JOIN __GOODS_CATEGORY__ AS gc ON g.category_id = gc.id")
        ->join("LEFT JOIN __SELLER_DELIVERY__ AS sd ON g.id = sd.sg_id")
        ->where($condition)
        ->group('g.id')
        ->order($order)
        ->page($pageNum,$pageSize)
        ->select();
        foreach ($result['list'] as &$v){
            $v['store_count']=M('SellerDelivery')->where(array('sg_id'=>$v['id'],'is_onsale'=>1))->count();
            $v['total_sales_volume']=$v['online_sales_volume']+$v['sales_volume'];
        }
        return $result;
    }

    /**根据商品ID获取商品信息
     * @param           $id             int                 商品ID（goods_id）
     * @return          array                               商品信息,卖家信息
     * */
    public function getGoodsDetailById($id){
        $goodsModle = D('Common/Goods');
        $info=$goodsModle->alias('g')->field('g.goods_title,g.price,g.goods_thumb,g.original_img,g.content,g.details,g.params,g.is_onsale,g.is_extension,g.online_stock,g.online_sales_volume,s.*')
                        ->join('LEFT JOIN __USERS_SELLER__ AS s ON s.user_id = g.user_id')
                        ->where(array('g.id'=>$id))
                        ->find();
        return $info;
    }

    /**店中店的卖家商品列表
    * @param            $pageNum               int              页码
    * @param            $pageSize              int              每页显示的数量
    * @param            $order                 string           排序
    * @param            $condition             array            查询条件
    * @param            $pageCondition         array            分页条件
    * @return           mixed                                   包括分页信息和查询结果数组
    */
    public function getSellerGoodsPageList1($pageNum = 1, $pageSize = 10, $order = 'sales_volume DESC',  $condition = array() , $pageCondition = array()){
        $count = $this->alias("g")->where($condition)->count();
        $page = new \Think\Page($count,$pageSize,$pageCondition);
        $result['show'] = $page->show();
        $result['pageCount'] = $page->pageCount();
        $result['list'] = $this->alias("g")->field("g.id,g.status,g.add_time,g.online_stock,g.online_sales_volume,g.goods_thumb,g.original_img,g.goods_title,g.price,g.content,gc.title AS category_name")
        ->join("LEFT JOIN __GOODS_CATEGORY__ AS gc ON g.category_id = gc.id")
        ->where($condition)
        ->group('g.id')
        ->order($order)
        ->page($pageNum,$pageSize)
        ->select();
        foreach ($result['list'] as &$v){
            $v['store_count']=M('SellerDelivery')->where(array('sg_id'=>$v['id'],'is_onsale'=>1))->count();
            $v['total_sales_volume']=$v['online_sales_volume']+$v['sales_volume'];
        }
        return $result;
    }

    /**获取卖家栏目主题列表
     * @param $condition array 条件
     * @return array 返回树状的类别列表
     */
    public function getSellerColumnTreeList($condition = array()){
        $sellerColumnModule = M('sellerColumn');
        if(!$condition) {
            $list = treeAction($sellerColumnModule->order('id DESC , parent_id ASC , sort_num')->select(), 0, 1);
        }else{
            $list = treeAction($sellerColumnModule->where($condition)->order('id DESC , parent_id ASC , sort_num')->select(), 0, 1);
        }
        return $list;
    }


}
