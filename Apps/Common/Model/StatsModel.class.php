<?php
/* 流量统计模型类
 * Created on 2016-6-16
 *
 */
 namespace Common\Model;
 use Think\Model;

class StatsModel extends Model{
	
	/**插入统计信息
	 * @Param          array            $data            插入的统计信息
	 * @Return         bool
	 * */
	public function add($data){
		if($data){
			$statsModel = M('stats');
			$result = $statsModel->data($data)->add();
			return $result;
		}
		return false;
	}

	/**删除指定时间段的统计信息
	 * @Param         array            $condition        查询条件
	 * @Return        bool
	*/
	public function del($condition){
		if(!$condition){
			$curTime = time();
			$curTime = strtotime("-1 day",$curTime);
			$condition['access_time'] = array("lt",$curTime);
		}
		$statsModel = M('stats');
		return $statsModel->where($condition)->delete();
	}
}
