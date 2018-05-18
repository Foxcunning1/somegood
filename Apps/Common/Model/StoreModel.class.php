<?php
//求购模型
namespace Common\Model;
use Think\Model\RelationModel;
use Think\Page;

class StoreModel extends RelationModel{
    protected $_link = array(
        //用户表
        'Users' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Users',
            'foreign_key'   => 'user_id',
            'mapping_name'  => 'users',
            'mapping_fields' => 'user_name,mobile',
            'as_fields'      => 'user_name:user_name,mobile:user_mobile',
        ),
        //地区表
        'Region' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Region',
            'foreign_key'   => 'area_id',
            'mapping_name'  => 'region',
            'mapping_fields' => 'merger_name,pid,name',
            'as_fields'      => 'merger_name:region,name:area,pid:city_id',
        ),
        //商品表
//        'Goods' => array(
//            'mapping_type'  => self::HAS_MANY,
//            'class_name'    => 'Goods',
//            'foreign_key'   => 'store_id',
//            'mapping_name'  => 'goods',
//        ),
        //店铺类型
        'StoreType' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'StoreType',
            'foreign_key'   => 'store_type',
            'mapping_name'  => 'StoreType',
            'mapping_fields' => 'type_name',
            'as_fields'      => 'type_name:type_name',
        ),
    );

    /**获得前几个店铺信息
     * @param $topCount            int             要获取店铺的数量
     * @param $order              string           排序
     * @param $condition          array            查询条件
     * @return mixed              array            查询结果数组
     */
    public function getTopStoreList($topCount = 0, $order = 'sid DESC',  $condition = array()){
        $storeModule = M('Store');
        if($topCount>0){
            $result = $storeModule->where($condition)->order($order)->limit($topCount)->select();
        }else{
            $result = $storeModule->where($condition)->order($order)->select();
        }
        return $result;
    }

    /**得带页码的店铺信息列表
     * @param $pageNum            int              页码
     * @param $order              string           排序
     * @param $condition          array            查询条件
     * @param $pageCondition      array            分页条件
     * @return mixed              array            包括分页信息和查询结果数组
     */
    public function getStorePageListAction($pageNum = 1,$order = 'sid DESC',  $condition = array() , $pageCondition = array()){
        import('ORG.Util.Page');
        $storeModule = D('Store');
        $count = $storeModule->where($condition)->count();
        $page = new Page($count,C('STORE_PAGE_SIZE'),$pageCondition);

        $result['show'] = $page->show();
        $result['list'] = $storeModule->relation(true)->where($condition)->order($order)->page($pageNum,C('STORE_PAGE_SIZE'))->select();
        return $result;
    }

    /**带页码及统计销量数据的的店铺列表
     * @param $pageNum            int              页码
     * @param $pageSize           int              每页显示的数量
     * @param $order              string           排序
     * @param $condition          array            查询条件
     * @param $pageCondition      array            分页条件
     * @return mixed              array            包括分页信息和查询结果数组
     * @return   返回数组键值说明
     *           表字段：  s.*所有字段说明参考sg_store表字段说明
     *           临时字段：t.totalSaleCount     商品销售数量,此数据为统计该店铺在order_goods中counts之和
     *                    sg.counts            该店铺中的在售商品的种类的数量
     *           别名字段：sale_counts          商品销售数量
     *                    goods_counts         商品种类数量
     *
     */
    public function getStoreList($pageNum = 1, $pageSize = 0, $order = 'sid DESC',  $condition = array() , $pageCondition = array()){
        $storeModule = M('store');
        /*$orderGoodsModel = M("order_goods");
        $goodsModel = M("goods");*/
        /*$str = "SELECT s.*,IFNULL(t.total,0) saleCount,IFNULL(sg.count,0) goods_counts  FROM `sg_store` AS s
LEFT JOIN (SELECT o.store_id, SUM(o.counts) as total from `sg_market_order_goods` as o group by o.store_id) as t on s.sid=t.store_id
LEFT JOIN (SELECT g.store_id,COUNT(*) as count from `sg_goods` as g group by g.store_id) as sg on s.sid=sg.store_id
order by saleCount desc";*/
        //查询店铺商品销售记录
        /*$saleCountQuery = $orderGoodsModel->alias("o")->field("o.store_id,SUM(o.counts) as totalSaleCount")->group("o.store_id")->buildSql();
        //查询店铺商品数量
        $map['status'] = 1;
        $currentTime = time();//当前时间
        $map['over_time'] = array("gt",$currentTime);//
        $storeGoodsCountQuery = $goodsModel->alias("g")->field("g.store_id,COUNT(*) as count")->where($map)->group("g.store_id")->buildSql();*/
        $count = $storeModule->alias("s")->where($condition)->count();
        if($pageSize==0){
            $pageSize = C('PAGE_SIZE');
        }
        $page = new \Think\Page($count,$pageSize,$pageCondition);
        $result['show'] = $page->show();
        $result['list'] = $storeModule
            ->alias("s")
//            ->field("s.*,IFNULL(t.totalSaleCount,0) as sale_counts,IFNULL(sg.count,0) as goods_counts")
//                                      ->join("LEFT JOIN ". $saleCountQuery." as t on s.sid=t.store_id ")
//                                      ->join("LEFT JOIN ".$storeGoodsCountQuery ." as sg on s.sid=sg.store_id ")
                                      ->where($condition)->order($order)->page($pageNum,$pageSize)->select();
        $result['pageCount'] = $page->pageCount();

        return $result;
    }
    /**获得店铺管理详情
     * @param $user_id     int     用户ID
     * @return   mixed     array   店铺信息
     */
    public function getStoreDetail($user_id){
        $storeModel = M('store');
        $storeDetail=$storeModel->where(array('user_id'=>$user_id))->find();
        return $storeDetail;
    }
    /*查看他人店铺*/
    public function storeDetail($sid){
        $storeModule = D('Common/Store');
        $storeDetail=$storeModule->relation(true)->where(array('sid'=>$sid))->find();
        return $storeDetail;

    }
    /**获取店铺格子信息
     * @param $id               int     店铺ID
     * @return mixed                    格子信息
     */
    public function getStoreBox($id){
        $storeModule = M('StoreBox');
        $result=$storeModule->alias('sb')
            ->field('sb.id,sn,l,w,h,is_lock')
            ->join("LEFT JOIN __STORE_BOX_SIZE__ AS sbz ON sb.size_id = sbz.id")
            ->where(array('store_id'=>$id))
            ->select();
        return $result;
    }

    /**获取店铺广告位信息
     * @param $id               int     店铺ID
     * @return mixed                    格子信息
     */
    public function getStorePos($id){
        $storeModule = M('StoreAdv');
        $result=$storeModule->alias('sa')
            ->field('sa.id,title,width,height,is_lock')
            ->join("LEFT JOIN __STORE_ADV_POSITION__ AS sap ON sa.pos_id = sap.id")
            ->where(array('store_id'=>$id))
            ->select();
        return $result;
    }


    /**更新店铺格子信息
     * @param $store_id        int       店铺ID
     * @param $delIds         string     要删除的格子IDS
     * @param $add_box         array     新增格子size_id数组
     * @return int
     */
    public function updataStoreBox($store_id,$delIds='',$add_box=array()){
        $storeBoxModel=M('StoreBox');
        $storeModel=M('store');
        $success_num=0;
        if ($delIds){
            $condition['store_id']=$store_id;
            $condition['is_lock']=0;
            $delIdsArr=explode(',',$delIds);
            $condition['id']=array('in',$delIdsArr);
            $re=$storeBoxModel->where($condition)->delete();
            if ($re){
                $success_num++;
                $storeModel->where(array('sid'=>$store_id))->setDec('vacancy',count($delIdsArr));
            }
        }
        if ($add_box){
            $areaId=$storeModel->where(array('sid'=>$store_id))->getField('area_id');
            $areaId=str_pad($areaId,4,"0",STR_PAD_LEFT);
            foreach ($add_box as $v){
                $addDate['store_id']=$store_id;
                $addDate['size_id']=$v;
                $id=$storeBoxModel->add($addDate);
                $re=$storeBoxModel->where(array('id'=>$id))->setField('sn',$areaId.str_pad($id,C('STORE_BOX_SN_LENGTH'),"0",STR_PAD_LEFT));
                if ($re){
                    $success_num++;
                    //空余格子增加
                    $storeModel->where(array('sid'=>$store_id))->setInc('vacancy');
                }
            }
        }
        return $success_num;
    }


    /**更新店铺广告位信息
     * @param $store_id        int       店铺ID
     * @param $delPosIds       string    要删除的广告位IDS
     * @param $adv_pos         array     新增广告位pos_id数组
     * @return int
     */
    public function updataStoreAdvPos($store_id,$delPosIds='',$adv_pos=array()){
        $storeAdvModel=M('StoreAdv');
        $success_num=0;
        if ($delPosIds){
            $condition['store_id']=$store_id;
            $condition['is_lock']=0;
            $delIdsArr=explode(',',$delPosIds);
            $condition['id']=array('in',$delIdsArr);
            $re=$storeAdvModel->where($condition)->delete();
            if ($re){
                $success_num++;
            }
        }
        if ($adv_pos){
            foreach ($adv_pos as $v){
                $addDate['store_id']=$store_id;
                $addDate['pos_id']=$v;
                $id=$storeAdvModel->add($addDate);
                if ($id){
                    $success_num++;
                }
            }
        }
        return $success_num;
    }


    /**带页码的格子规格列表
     * @param $pageNum int          页码
     * @return mixed
     * */
    public function getBoxSizeList($pageNum){
        $boxSizeModel=M('StoreBoxSize');
        $count=$boxSizeModel->count();
        $page = new \Think\Page($count,C('COMMON_PAGE_NUM'));
        $result['show'] = $page->show();
        //已使用的个数
        $sql=M('StoreBox')->alias("sb")->field("COUNT(size_id) as used,size_id")->where('is_lock =1')->group('size_id')->buildSql();
        $result['list']=$boxSizeModel->alias('sbs')->field('sbs.id,l,w,h,count(sb.id) as count,t.used')
            ->join('LEFT JOIN __STORE_BOX__ AS sb ON sbs.id = sb.size_id')
            ->join('LEFT JOIN '. $sql .' AS t ON sbs.id = t.size_id')
            ->group('sbs.id')
            ->page($pageNum,C('COMMON_PAGE_NUM'))
            ->select();
        return $result;
    }

    /*检测店铺申请
    *@parm   $condition  arr   查询条件
    *@parm   $pageNum  int   当前页码
    *@parm   $pageCondition  arr  分页传参
    *@parm   return  str
    */
    public function checkStoreApply($condition,$pageNum,$pageCondition){
            $count=M('store')->alias('s')->field("s.*,u.is_store")
            ->join("LEFT JOIN __USERS__ AS u on u.uid = s.user_id")
            ->where($condition)->count();
            $page_size=C('SELLER_PAGE_SIZE');
            $Page = new \Think\Page($count,$page_size,$pageCondition);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $Page->setconfig('next','下一页');
            $Page->setconfig('prev','上一页');
            $res['list']=M('store')->alias('s')->field("s.user_id,s.address,s.store_name,s.mobile,u.is_store")
            ->join("LEFT JOIN __USERS__ AS u on u.uid = s.user_id")
            ->where($condition)->page($pageNum,$page_size)->select();
            $res['page']=$Page->show();
            return $res;
    }

    
}
