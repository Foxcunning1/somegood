<?php
//卖家配送模型
namespace Common\Model;
use Think\Model\RelationModel;
use Think\Page;

class SellerLogisticsModel extends RelationModel{
    protected $_link = array(
        //快递公司表
        'Logistics' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Logistics',
            'foreign_key'   => 'logistics_id',
            'mapping_name'  => 'Logistics',
            'mapping_fields' => 'id,name',
            'as_fields'      => 'id:logistics_id,name:logistics_name',
        ),
        //厂家快递配送区域
        'SellerLogisticsRegion' => array(
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'SellerLogisticsRegion',
            'foreign_key'   => 'seller_logistics_id',
            'mapping_name'  => 'regions',
        ),
    );

    /**得带页码的厂家配送方式
     * @param $pageNum            int              页码
     * @param $order              string           排序
     * @param $condition          array            查询条件
     * @param $pageCondition      array            分页条件
     * @return mixed              array            包括分页信息和查询结果数组
     */
    public function getLogisticsPageList($pageNum = 1,$order = 'id DESC',  $condition = array() , $pageCondition = array()){
        import('ORG.Util.Page');
        $logisticsModule = D('Common/SellerLogistics');
        $count = $logisticsModule->where($condition)->count();
        $result['count']=$count;
        $page = new Page($count,C('LOGISTICS_PAGE_SIZE'),$pageCondition);
        $result['pageCount']=$page->pageCount();
        $result['show'] = $page->show();
        $result['list'] = $logisticsModule->relation(true)->where($condition)->order($order)->page($pageNum,C('LOGISTICS_PAGE_SIZE'))->select();
        return $result;
    }

    /**得带页码的厂家区域
     * @param $pageNum            int              页码
     * @param $condition          array            查询条件
     * @param $pageCondition      array            分页条件
     * @return mixed              array            包括分页信息和查询结果数组
     */
    public function getRegionPageList($pageNum = 1,$condition = array() , $pageCondition = array()){
        import('ORG.Util.Page');
        $logisticsModule = M('SellerLogisticsRegion');
        $count = $logisticsModule->where($condition)->count();
        $result['count']=$count;
        $page = new Page($count,C('LOGISTICS_PAGE_SIZE'),$pageCondition);
        $result['pageCount']=$page->pageCount();
        $result['show'] = $page->show();
        $result['list'] = $logisticsModule->where($condition)->page($pageNum,C('LOGISTICS_PAGE_SIZE'))->select();
        return $result;
    }

    /**根据商品和地区获得商品所在厂家的快递及费用
     * @param  $cartIds        array   商品ID
     * @param  $province_id    int     省ID
     * @param  $city_id        int     城市ID
     * @param  $region_id      int     区域ID
     * @return mixed           array   各厂家支持的快递及费用
     */
    public function getSellerLogistics($cartIds,$province_id,$city_id,$region_id){
        $OrderModel=D('Common/Order');
        $info=$OrderModel->getUserOrder($cartIds);
        //var_dump($info);
        $sellerLogistics=array();
        //分厂家获取该厂家商品总价.总重
        foreach ($info['info'] as $v){
            $sellerLogistics[$v['seller_id']]['total_money']+=$v['goods_price'];
            $sellerLogistics[$v['seller_id']]['total_heavy']+=$v['heavy'];
        }
        //获取支持的配送方式及价格
        foreach ($sellerLogistics as $k=>&$v){
            $condition['user_id']=$k;
            $condition['is_on']=1;
            $logistics=D('Common/SellerLogistics')->relation(true)->where($condition)->select();
            foreach ($logistics as $key=>$sv){
                if (count($sv['regions'])>0){
                    foreach ($sv['regions'] as $ssk=>$ssv){
                        //根据地区筛选
                        $regions=explode(',',$ssv['logistics_region']);
                        if (in_array('0',$regions)||in_array($province_id,$regions)||in_array($city_id,$regions)||in_array($region_id,$regions)){
                            //物流公司名称
                            $v[$key]['id']=$sv['logistics_id'];
                            //配送费
                            if ($v['total_money']>=$ssv['free']){
                                //该厂家商品总额大于免邮
                                $v[$key]['price']=0;
                            }elseif($v['total_heavy']>1){
                                //该厂家商品总重大于1
                                $v[$key]['price']=$ssv['first_heavy']+ceil($v['total_heavy']-1)*$ssv['con_heavy'];
                            }else{
                                $v[$key]['price']=$ssv['first_heavy'];
                            }
                            $v[$key]['value']=$sv['logistics_name'].' ￥'.$v[$key]['price'];
                        }
                    }
                }
            }
            unset($v['total_money']);
            unset($v['total_heavy']);
        }
        foreach ($sellerLogistics as $key => $value) {
            $sellerLogistics1[$key]=array_values($sellerLogistics[$key]);
        }
        return $sellerLogistics1;
    }

}
