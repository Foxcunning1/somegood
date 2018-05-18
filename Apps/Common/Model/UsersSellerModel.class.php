<?php
/* 卖家管理模型类
 * Created on 2016-6-16
 *
 */
 namespace Common\Model;
 use Think\Model;

class UsersSellerModel extends Model{

        /*检测卖家申请
        *@parm   $condition  arr   查询条件
        *@parm   $pageNum  int   当前页码
        *@parm   $pageCondition  arr  分页传参
        *@parm   return  str
        */
  		public function checkSellerApply($condition,$pageNum,$pageCondition){
  				$count=M('users_seller')->alias('us')->field("us.*,u.is_seller")
  				->join("LEFT JOIN __USERS__ AS u on u.uid = us.user_id")
  				->where($condition)->count();
  				$page_size=C('SELLER_PAGE_SIZE');
  				$Page = new \Think\Page($count,$page_size,$pageCondition);// 实例化分页类 传入总记录数和每页显示的记录数(25)
  				$res['list']=M('users_seller')->alias('us')->field("us.*,u.is_seller")
  				->join("LEFT JOIN __USERS__ AS u on u.uid = us.user_id")
  				->where($condition)->page($pageNum,$page_size)->select();
  				$res['page']=$Page->show();
  				return $res;
  		}



	/**结算统计函数  卖家
	*@Param           $pageNum            int            当前页码
	*@Param           $map                array          查询条件
	*@Param           $isUsed             int            是否二手商品结算
	*@Param           $order              string         排序条件*
	*@parm   return  str
	*/
    public function sellerSettlement($pageNum, $map, $isUsed = 0, $order = 'o.add_time DESC,o.id DESC'){
		$page_size=C('SETTLEMENT_PAGE_SIZE');
		$param=C('users');
		$seller_percent = $param['seller_percent']; //卖家商品卖完后最后获取得商品最终卖出的总金额的比例
		$store_percent  = $param['store_percent']; //商家商品卖完后获得订单总金额的比例
		$OrderModel=M('Order');
		//统计数量
		$counts=$OrderModel->alias('o')->where($map)->count();
		              $res['pageCount']=ceil($counts/$page_size);   //总页数
		              $res['counts']=$counts;  //用户订单结算总数量
		//根据订单来结算卖家的money
		$res['list']=$OrderModel->alias('o')->field("o.order_sn,o.original_price,o.original_price*$seller_percent as seller_money,o.settlement_time,o.complete_time")
		                      ->where($map)->page("$pageNum,$page_size")->order($order)->select();
		//得到总收入 和待收入
		if($isUsed==0){
			$res['allGetMoney'] = ($OrderModel->alias('o')->where($map)->sum('original_price'))*$seller_percent;
		}else{
			$res['allGetMoney'] = ($OrderModel->alias('o')->where($map)->sum('original_price'))*$param['sh_seller_percent'];
		}
		return $res;
    }




}
