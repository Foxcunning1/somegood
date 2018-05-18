<?php
//求购模型
namespace Common\Model;
use Think\Model;
use Think\Page;

class CouponModel extends Model{

    /**得带页码的优惠券列表
     * @param $pageNum            int              页码
     * @param $order              string           排序
     * @param $condition          array            查询条件
     * @param $pageCondition      array            分页条件
     * @param $status             string           删除状态条件
     * @return mixed              array            包括分页信息和查询结果数组
     */
    public function getCouponPageList($pageNum = 1,$order = 'coupon_id DESC',  $condition = array() , $pageCondition = array(),$status='list'){
        import('ORG.Util.Page');
        $couponModule = D('Coupon');
        if ($status=='list'){
            //该优惠券是否删除
            $condition['is_delete']=0;
            $pageCondition['status']='list';
        }elseif ($status=='reduction'){
            //该优惠券是否删除
            $condition['is_delete']=1;
            $pageCondition['status']='reduction';
        }
        $count = $couponModule->where($condition)->count();
        $page = new Page($count,C('COUPON_PAGE_SIZE'),$pageCondition);
        $result['pageCount']=$page->pageCount();
        $result['show'] = $page->show();
        $result['list'] = $couponModule->alias("c")->field("c.coupon_id,c.coupon_sn,c.used_time,o.order_sn,u.nick_name,ct.type_name,ct.is_del,ct.type_money,ct.min_goods_amount AS min,ct.is_all,ct.use_start_date,ct.use_end_date,ct.type_id")
                                        ->join("LEFT JOIN __USERS__ AS u ON u.uid = c.user_id")
                                        ->join("LEFT JOIN __ORDER__ AS o ON c.order_id = o.id")
                                        ->join("LEFT JOIN __COUPON_TYPE__ AS ct ON c.coupon_type_id = ct.type_id")
                                        ->where($condition)
                                        ->order($order)
                                        ->page($pageNum,C('COUPON_PAGE_SIZE'))
                                        ->select();
        return $result;
    }

    /**订单结算优惠券
     * @param $order_price        string           订单总价
     * @param $goods_array        array            商品数组
     */
    public function orderCouponAction($order_price,$goods_array){
        $couponModule = D('Coupon');
        //订单金额达成的优惠券
        $condition['user_id']=session('mobile.id');
        $condition['min_goods_amount']=array('lt',$order_price);
        $condition['used_time']=0;
        $condition['use_start_date']=array('lt',time());
        $condition['use_end_date']=array('gt',time());

        $couponList=$couponModule->field("c.coupon_id,c.coupon_type_id,ct.is_all,ct.type_money,ct.min_goods_amount,ct.type_name,ct.use_start_date,ct.use_end_date")->alias("c")
                                ->join("LEFT JOIN __COUPON_TYPE__ AS ct ON c.coupon_type_id = ct.type_id")
                                ->where($condition)
                                ->select();

        $couponTypeModel=M('couponTypeList');

        foreach ($couponList as &$cl){
            //判断是否全品类
            if($cl['is_all']==1){
                $cl['can_use']=1;
            }else{
                //符合优惠券使用类别的商品总价
                $goods_money=0;
                //遍历该优惠券支持的goodsCategory
                $goodsCateArr=$couponTypeModel->where(array('coupon_type_id'=>$cl['coupon_type_id']))->select();
                foreach ($goodsCateArr as $gc){
                    //遍历商品查找与该优惠券类型相同的商品
                    foreach ($goods_array as $g){
                        //将符合类型的商品计算总价，达到满减则可用
                        if($g['goods_category']==$gc['goods_cate']){
                            $goods_money +=$g['goods_price'];
                            if ($goods_money>$cl['min_goods_amount']){
                                $cl['can_use']=1;
                                break;
                            }
                        }
                    }
                }
            }
        }
        foreach ($couponList as $k=>$v){
            if($v['can_use']==1){
                $couponList['can_use'][]=$v;
                unset($couponList[$k]);
            }else{
                $couponList['cant_use'][]=$v;
                unset($couponList[$k]);
            }
        }
        $couponList['couponNum']=count($couponList['can_use']);
        $couponList['cantUseCouponNum']=count($couponList['cant_use']);
        return $couponList;
    }

}
