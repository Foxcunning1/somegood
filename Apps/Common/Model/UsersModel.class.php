<?php
//求购模型
namespace Common\Model;
use Think\Model\RelationModel;
class UsersModel extends RelationModel {
    protected $_link = array(
        //地区表
        'Region' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Region',
            'foreign_key'   => 'area_id',
            'mapping_name'  => 'region',
            'mapping_fields' => 'merger_name',
            'as_fields'      => 'merger_name:region',
        ),
    );

    /**判断指定字段是否存在
     * @Param       string         $param        要验证的值
     * @Param       string         $type         验证类型，username为验证用户名是否重复，mobile为验证手机号
     */
    public function isExits($param,$type){
        switch($type){
            case 'username':
                $map = array('user_name'=>$param);
                break;
            case 'mobile':
                $map = array('mobile'=>$param);
                break;
            default:
                $map = array('user_name'=>$param);
                break;
        }
        $user = M('users');
        if($user->where($map)->count()>0){
            return true;
        }else{
            return false;
        }
    }

    //当用户勾选"记住我"
    public function saveRemember($uid,$identifier,$token,$timeout){
        $users = M("users");
        $data['identifier'] = $identifier;
        $data['token'] = $token;
        $data['timeout'] = $timeout;
        $where = " uid = ".$uid;
        $res = $users->data($data)->where($where)->save();
        return $res;
    }

    /*获得用户信息
     *
     * */
    public function getUsersInfo($uid=0){
        $info = array();
        $users = M("users");
        if($uid==0){
            $map['uid'] = session("mobile.id");
        }else{
            $map['uid'] = $uid;
        }
        $info = $users->field("u.*,s.sid,s.store_name")->alias("u")->join("LEFT JOIN __STORE__ AS s ON s.user_id=u.uid")->where($map)->find();
        return $info;
    }

    /**获得登录用户的厂家的公司名,头像
     * */
    public function getSellerInfo(){
        $sellerModel = M("UsersSeller");
        $info = $sellerModel->alias('s')->field("uid,s.company_name,u.avatar")
            ->join('LEFT JOIN __USERS__ AS u on s.user_id = u.uid')
            ->where(array('user_id'=>session('mobile.id')))->find();
        return $info;
    }

    /**获得二手商品卖家的信息
     * @Param       int        $uid              用户ID
     * */
    public function getErshouSellerInfo($uid=0){
        $goodsModel = M('sh_goods');
        $orderModel = M("order");
        $users = M("users");
        //查询店铺商品销售记录
        $oMap['sell_id'] = $uid;
        $oMap['status'] = 3;
        $oMap['is_used'] = 1;
        $saleCount = $orderModel->where($oMap)->count();
        //查询店铺商品数量
        $gMap['user_id'] = $uid;
        $gMap['status'] = 1;
        $goodsCount = $goodsModel->where($gMap)->count();
        //获取二手商品卖家信息
        if(F("Ershou/Seller/seller_info_".$uid)){
            $info = F("Ershou/Seller/seller_info_".$uid);
        }else{
            $map['uid'] = $uid;
            $info = $users->field("uid,user_name,mobile,email,area_id,avatar,nick_name,is_lock")->where($map)->find();
            F("Ershou/Seller/seller_info_".$uid,$info);
        }
        $info['sale_counts'] = $saleCount;
        $info['goods_counts'] = $goodsCount;
        return $info;
    }

    /*获得用户店铺信息
    *
    * */
    public function getUsersStoreInfo(){
        $info = array();
        $users = M("users");
        $info = $users->alias("u")->join('LEFT JOIN __STORE__ AS s on u.uid = s.user_id')
            ->join('LEFT JOIN __USERS_GROUP__ AS g on g.gid = u.group_id')
            ->field('u.uid,u.user_name,s.*,g.gid,g.group_name')
            ->where(array("uid"=>session("mobile.id")))->find();
        return $info;
    }


    //验证用户是否永久登录（记住我）
    public function checkIsLogin($returnUrl){
        $arr = array();
        $now = time();
        list($identifier,$token) = explode(':',cookie('auth'));
        if (ctype_alnum($identifier) && ctype_alnum($token)){
            $arr['identifier'] = $identifier;
            $arr['token'] = $token;
        }else{
            return false;
        }
        $users = M("Users");
        $info = $users->where($arr['identifier'])->find();
        if($info != null){
            if($arr['token'] != $info['token']){
                return false;
            }else if($now > $info['timeout']){
                return false;
            }else{
                /*if($returnUrl!=""){
                    header("Location: $returnUrl");
                }*/
                return $info;
            }
        }else{
            return false;
        }
    }


    /*保存用户信息
     * Param         array       $data             用户数据
     * */
    public function save($data){
        if(empty($data)){
            return false;
        }
        $users = M("users");
        $res = $users->data($data)->where(array('uid'=>session('mobile.id')))->save();
        return $res;
    }

    /*更新字段信息
	 * Param       string         $fieldName            要更新的字段名称
	 * Param       string         $fieldValue           要更新字段的值
	 * */
    public function updateField($fieldName,$fieldValue){
        $users = M('users');
        $map['uid'] = session('mobile.id');
        $arr[$fieldName]   = $fieldValue;
        if($users->where($map)->setField($arr)!==false){
            return true;
        }
        return false;
    }

    /**登录设置cookie、session
     * @param    int         $id            用户uid
     * @param    string      $type          shop/store/seller/mobile
     * @param    int         $group_id      用户群组ID
     * @param    string      $last_time     最后登录时间
     * @param    string      $last_ip       最后一次登录IP
     * @param    string      $mobile        手机号
     * @param    string      $user_name     用户名字
     * @param    int         $sid           店铺id
     */
    public function setLoginCookie($id,$type,$group_id,$last_time,$last_ip,$mobile,$user_name='',$sid=0){
        //用户信息存入session
        session($type.".id",$id);
        session($type.".gid",$group_id);
        session($type.".last_time",$last_time);
        session($type.".last_ip",$last_ip);
        session($type.".mobile",$mobile);
        session($type.".store_id",$sid);
        if ($type=='store'){
            session('mobile.id',$id);
        }
        if($user_name){
            session($type.".username",$user_name);
        }
        //记录当前登录时间、IP
        $condition['last_time'] = time();
        $condition['last_ip']   = get_client_ip();
        M('users')->where(array('mobile'=>$mobile))->save($condition);
        //把用户名存入cookie，退出登录后在表单保存用户名信息
        cookie('_id',$id,time()+3600*24);
        cookie('_mobile',$mobile,time()+3600*24);
        if($user_name){
            cookie('_username',$user_name,time()+3600*24);
        }
    }
}