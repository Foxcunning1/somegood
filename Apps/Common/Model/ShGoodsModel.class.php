<?php
//二手商品模型
namespace Common\Model;
use Think\Model;

class ShGoodsModel extends Model{

    /**添加商品
     * @Param        int          $goods_id           商品对应的ID
     * @Param        array        $data               要更新的数据
     */
    public function add($data){
        $goodsModel = M("sh_goods");
        $result = $goodsModel->data($data)->add();
        return $result;
    }

    /**更新商品
     * @Param        int          $goods_id           商品对应的ID
     * @Param        array        $data               要更新的数据
     */
    public function update($goodsId,$data){
        $goodsModel = M("sh_goods");
        $result = $goodsModel->where(array("id"=>$goodsId))->save($data);
        return $result;
    }
    /**更新商品库存
     * @Param         int            $goodsId          商品ID
     * @Param         int            $num              更新的数量
     * @Return        bool
    */
    public function updateGoodsNum($goodsId,$num = 1){
        $goodsModel = M("sh_goods");
        //首先获取当前商品的数量
        $goodsNum = $goodsModel->where(array("id"=>$goodsId))->getField("goods_num");
        if($num > $goodsNum){
            return false;
        }
        if(($goodsNum-$num)==0){//商品卖完，并下架当前商品
            $data['goods_num'] = 0;
            $data['status'] = 3;
            $result = $goodsModel->where(array("id"=>$goodsId))->save($data);
        }else{
            $result = $goodsModel->where(array("id"=>$goodsId))->setDec('goods_num',$num);
        }
        return $result;
    }
    /**更新浏览量
     * @Param         int            $goodsId          商品ID
     * @Return        bool
     */
    public function updateBrowseNum($goodsId){
        $goodsModel = M("sh_goods");
        $result = $goodsModel->where(array("id"=>$goodsId))->setInc("browse_num",1,60);
        return $result;
    }
    /**获得某个商品的详细信息
     * @param $id                 int              商品id
     * @return mixed              array            查询结果数组
     */
    public function getGoodsInfo($id){
        $goodsModel = M('ShGoods');
        $orderGoodsModel = M("OrderGoods");
        //查询店铺商品销售记录
        $saleCountQuery = $orderGoodsModel->alias("o")->field("o.store_id,SUM(o.counts) as totalSaleCount")->group("o.store_id")->buildSql();
        $info = $goodsModel->alias("g")
                            ->field("g.*,gc.title AS category_name,u.uid,u.user_name,u.avatar,u.nick_name,
                                     u.mobile,r.pid,r.name AS area_name,r.merger_name AS region,
                                     IFNULL(t.totalSaleCount,0) as sale_counts
                                     ")
                            ->join("LEFT JOIN __GOODS_CATEGORY__ AS gc ON g.category_id = gc.id")
                            ->join("LEFT JOIN __USERS__ AS u ON g.user_id = u.uid")
                            ->join("LEFT JOIN __REGION__ AS r ON g.area_id = r.id")
                            ->join("LEFT JOIN ". $saleCountQuery ." as t on u.uid=t.store_id ")
                            ->where(array('g.id'=>$id))->find();
        //查询该用户的商品数量
        $sellGoodsCount = $goodsModel->alias("g")->where(array('g.status'=>1,'user_id'=>$info['user_id']))->count();
        $info['goods_count'] = $sellGoodsCount;
        return $info;
    }

    /**获得某个商品的部分信息
     * @param $id                 int              商品id
     * @return mixed              array            查询结果数组
     */
    public function getGoodsPartInfo($id){
        $goodsModel = M('ShGoods');
        $info = $goodsModel->alias("g")
            ->field("g.id,g.user_id,g.goods_title,g.goods_price,g.logistics_price,g.goods_num,g.goods_thumb,g.status,u.mobile,u.nick_name,u.avatar")
            ->join("LEFT JOIN __USERS__ AS u ON g.user_id = u.uid")
            ->where(array('g.id'=>$id))->find();
        return $info;
    }

    /**获得前几个商品数据
     * @param $topCount            int             要获取商品的数量
     * @param $order              string           排序
     * @param $condition          array            查询条件
     * @return mixed              array            查询结果数组
     */
    public function getTopGoodsList($topCount = 0, $order = 'id DESC',  $condition = array()){
        $goodsModule = M('ShGoods');
        if($topCount>0){
            $result = $goodsModule->field("g.*,gc.id AS catid,gc.title AS category_name,u.nick_name,u.avatar")->alias("g")
                                  ->join("LEFT JOIN __GOODS_CATEGORY__ AS gc ON g.category_id = gc.id")
                                  ->join("LEFT JOIN __USERS__ AS u ON g.user_id = u.uid")
                                  ->where($condition)->order($order)->limit($topCount)->select();
        }else{
            $result = $goodsModule->field("g.*,gc.id AS catid,gc.title AS category_name,u.nick_name,u.avatar")->alias("g")
                                  ->join("LEFT JOIN __GOODS_CATEGORY__ AS gc ON g.category_id = gc.id")
                                  ->join("LEFT JOIN __USERS__ AS u ON g.user_id = u.uid")
                                  ->where($condition)->order($order)->select();
        }
        return $result;
    }

  /**得带页码的商品列表
   * @param $pageNum            int              页码
   * @param $pageSize           int              每页显示的数量
   * @param $order              string           排序
   * @param $condition          array            查询条件
   * @param $pageCondition      array            分页条件
   * @return mixed              array            包括分页信息和查询结果数组
   */
  public function getGoodsPageList($pageNum = 1, $pageSize = 0, $order = 'update_time DESC',  $condition = array() , $pageCondition = array()){
      $goodsModule = M('sh_goods');
      $count = $goodsModule->field("g.*,gc.id AS catid,gc.title AS category_name,u.nick_name,u.avatar")->alias("g")
                           ->join("LEFT JOIN __GOODS_CATEGORY__ AS gc ON g.category_id = gc.id")
                           ->join("LEFT JOIN __USERS__ AS u ON g.user_id = u.uid")
                           ->where($condition)->count();
      if($pageSize==0){
          $pageSize = $count;
      }
      $page = new \Think\Page($count,$pageSize,$pageCondition);
      $result['show'] = $page->show();
      $result['pageCount'] = $page->pageCount();
      $result['list'] = $goodsModule->field("g.*,gc.id AS catid,gc.title AS category_name,u.nick_name,u.avatar")->alias("g")
                                    ->join("LEFT JOIN __GOODS_CATEGORY__ AS gc ON g.category_id = gc.id")
                                    ->join("LEFT JOIN __USERS__ AS u ON g.user_id = u.uid")
                                    ->where($condition)->order($order)->page($pageNum,$pageSize)->select();
      return $result;
  }

    /**商品审核
     * @param $goods_id           int              商品ID
     * @param $status             int              审核状态
     * @param $reason              string           失败原因
     * @return bool
     */
    public function doAuditGoods($goods_id,$status=1,$reason=''){
        $goodsModel=M('sh_goods');
        $store_id=session('mobile.store_id')?session('mobile.store_id'):session('store.store_id');
        $data['status']=$status;
        $data['audit_time']=time();//审核时间
        if ($reason!=''){
            $data['error_reason']=$reason;
        }
        $result=$goodsModel->where(array('id'=>$goods_id,'store_id'=>$store_id))->save($data);
        return $result;
    }

    /**商品上下架
     * @param $goods_id           int              商品ID
     * @param $saleStatus         string           上下架状态on上架,off下架
     * @return bool
     */
    public function doSale($goods_id,$saleStatus='on'){
        $goodsModel=D('Common/ShGoods');
        $userId = session('mobile.id')?session('mobile.id'):0;
        if ($saleStatus=='on'){
            $data['status']=1;
            $re=$goodsModel->where(array('user_id'=>$userId,'id'=>$goods_id))->save($data);
        }elseif($saleStatus=='off'){
            $data['status']=3;
            $re=$goodsModel->where(array('user_id'=>$userId,'id'=>$goods_id))->save($data);
        }
        return $re;
    }
    /**变更商品置顶/最热/最新/推荐状态
     * @param $goods_id           int              商品ID
     * @param $param              string           置顶/最热/最新/推荐状态
     * @return bool
     */
    public function updateGoodsParam($goods_id,$param){
        $goodsModel=D('Common/ShGoods');
        $userId = session('mobile.id')?session('mobile.id'):0;
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
        $re = $goodsModel->where(array('user_id'=>$userId,'id'=>$goods_id))->save($data);
        return $re;
    }
}
