<?php
//求购模型
namespace Common\Model;
use Think\Model;
class UsersWeixinModel extends Model{
    /*判断微信用户是否存在
     * 微信用户openid
     * */
    public function isOpenidExits($openid){
        if($openid==""){
            return false;
        }else{
            $weixin = M('users_weixin');
            if($weixin->where(array('openid'=>$openid))->count()>0){
                return true;
            }else{
                return false;
            }
        }
    }
    /*获得微信用户信息
     *
     * */
    public function getWeixinUsersInfo($openid){
        $info = array();
        $weixin = M("users_weixin");
        if($openid==''){
            return $info;
        }else{
            $map['openid'] = $openid;
        }
        $info = $weixin->alias("wx")->where($map)->find();
        return $info;
    }
    /*保存用户微信信息
     * Param         array       $data             用户数据
     * */
    public function add($data){
        if(empty($data)){
            return false;
        }
        $weixin = M("users_weixin");
        $res = $weixin->add($data);
        return $res;
    }
    /*更新用户微信信息
    * Param        array       $data             用户数据
    * Param        str         $openid           用户openid
    * */
    public function save($data, $openid){
        if($openid == ""||empty($data)){
            return false;
        }
        $condition['openid'] = $openid;
        $userWeixinModel = M('users_weixin');
        $res = $userWeixinModel->data($data)->where($condition)->save();
        return $res;
    }

    /*更新微信用户表对应的uid
    * 同时查询当前用户是否是通过分享链接获注册，并查询虚拟金币中，当前用户是否已获取过虚拟金币
    * @Param        array       $data             用户数据
    * @Param        str         $openid           用户openid
    */
    public function bindWxUsersInfo($data,$openid){
        $this->save($data,$openid);//更新当前用户信息
        $wxUsersInfo = $this->getWeixinUsersInfo($openid);//获取当前用户的微信信息
        //获取当前用户是否通过分享链接注册
        $fromUid = $wxUsersInfo['from_uid'];
        if($fromUid>0){//当前用户是通过分享链接注册的
            //查询当前用户是否已通过fromuid获取过金币，此处判断防止用户通过其他渠道，比如手机APP,PC端等
            $VirtualCashLogModel = D('VirtualCashLog');
            if(!$VirtualCashLogModel->isExits($fromUid,$wxUsersInfo['uid'])){//未获取虚拟币
                //
                $data['user_id'] = $fromUid;
                $data['from_uid'] = $wxUsersInfo['uid'];
                $data['from_type'] = 1;
                $data['user_money'] = C('users.gift_money');
                $data['change_time'] = time();
                $data['change_note'] = sprintf(C('users.change_note'),$wxUsersInfo['nickname']);
                $VirtualCashLogModel->add($data);//赠虚拟金币给用户
            }
        }
        return true;
    }

    /*获得所有微信用户信息
     *@Param        array             $condition        查询条件
     * */
    public function getUsersWeixinList($condition=array()){
        $usersWeixinModel = M("users_weixin");
        $condition['id'] = array('gt',0);
        $list = $usersWeixinModel->fliel("wx.*,u.user_name")->alias("wx")->join("left join __USERS__ AS u ON wx.uid=u.uid")->where($condition)->select();
        return $list;
    }
}