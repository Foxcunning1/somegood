<?php
/* 关键词模型类
 * Created on 2016-6-16
 *
 */
 namespace Common\Model;
 use Think\Model;

class KeywordsModel extends Model{

	

	/**获得前几个搜索关键字列表
	 * @param $topCount            int             要获取关键词的数量
	 * @param $order              string           排序
	 * @param $condition          array            查询条件
	 * @return mixed              array            查询结果数组
	 */
	public function getTopKeywordsList($topCount = 0, $order = 'count DESC',  $condition = array()){
		$keywordModule = M('keywords');
		if($topCount>0){
			$result = $keywordModule->alias("k")->field("k.*,c.title as category_name")
									->join("LEFT JOIN __GOODS_CATEGORY__ AS c on c.id = k.category_id")
				                    ->where($condition)->order($order)->limit($topCount)->select();
		}else{
			$result = $keywordModule->alias("k")->field("k.*,c.title as category_name")
									->join("LEFT JOIN __GOODS_CATEGORY__ AS c on c.id = k.category_id")
									->where($condition)->order($order)->select();
		}
		return $result;
	}

    //keyword 页面查询数据	$category_id    查询条件
	public function getKeywordPageList($pageNum,$condition=array(),$pageCondition=array()){
		$keywordModule = M('keywords');
		$count      = $keywordModule->alias("k")->join("LEFT JOIN __GOODS_CATEGORY__ AS c on c.id = k.category_id")->where($condition)->count();// 查询满足要求的总记录数
		$Page = new \Think\Page($count,10,$pageCondition);
		$list['page']       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
	    $list['list'] = $keywordModule->alias("k")->field("k.*,c.title as category_name")->join("LEFT JOIN __GOODS_CATEGORY__ AS c on c.id = k.category_id")
									  ->where($condition)->page($pageNum,10)->order('count DESC')->select();
		return $list;
	}
}
