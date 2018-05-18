<?php
/* 收货地址模型类
 * Created on 2017-10-18
 *
 */
 namespace Common\Model;
 use Think\Model;

class UsersAddressModel extends Model{

	/**添加收获地址
	 * @param      $condition          array           查询条件
	 * @param      $data               array           要添加的数据
	 * @return mixed              bool
	 */
	public function addAddress($data){
		$usersAddressModel = M('users_address');
		$res = $usersAddressModel->add($data);
		return $res;
	}
	/**更新收获地址
	 * @param      $condition          array           查询条件
	 * @param      $data               array           要更新的数据
	 * @return mixed              bool
	 */
	public function updateAddress($condition,$data){
		$usersAddressModel = M('users_address');
		$res = $usersAddressModel->where($condition)->save($data);
		return $res;
	}

	/**删除收获地址
	 * @param      $condition          array           查询条件
	 * @param      $data               array           要更新的数据
	 * @return mixed              bool
	 */
	public function delAddress($address_id,$condition = array()){
		$usersAddressModel = M('users_address');
		$condition['address_id'] = $address_id;
		$res = $usersAddressModel->where($condition)->delete();
		return $res;
	}

	/**获取收货人的某条信息
	*/
	public function getUserAddressInfo($condition=array()){
		$userAddressModel = M("users_address");
		$info = $userAddressModel->where($condition)->find();
		return $info;
	}

}
