<?php
/**
 * 优惠券信息管理类
 * Created by PhpStorm.
 * User: ZHY
 * Date: 2017/8/10
 * Time: 10:08
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Page;

class CouponController extends BaseController {
    //优惠券类型列表
    public function typeAction(){
        if(!parent::checkAdminRoleAction('coupon_type_manage','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        //发放类型
        $sendType = I('sendType');
        //搜索条件
        $keywords = I('keywords');
        $pageNum = I('p',1,'strip_tags');
        //列表状态，正常还是回收站
        $status=I('get.status');
        if($keywords) {
            $condition['type_name'] = array('like', '%' . $keywords . '%');
            $pageCondition['keywords'] = $keywords;
        }

        if($sendType) {
            $condition['send_type'] = $sendType;
            $pageCondition['send_type'] = $sendType;
            $this->assign('sendType',$sendType);
        }
        if ($status=='list'){
            $condition['is_del'] = 0;
            $pageCondition['is_del'] = 0;
        }elseif ($status=='reduction'){
            $condition['is_del'] = 1;
            $pageCondition['is_del'] = 1;
        }
        //获取优惠券类型列表
        $couponModel=M('couponType');
        $order = 'type_id DESC';
        $count = $couponModel->where($condition)->count();

        $page = new Page($count,C('WANT_PAGE_SIZE'));
        //带入查询条件的页码
        foreach ($pageCondition as $key => $val) {
            $page->parameter .= "$key=" . urlencode($val) . '&';
        }
        $result['show'] = $page->show();
        $result['list'] = $couponModel->where($condition)->order($order)->page($pageNum,C('WANT_PAGE_SIZE'))->select();

        foreach ($result['list'] as &$v){
            //该优惠券发放个数
            $v['send_num']=M('coupon')->where(array('coupon_type_id'=>$v['type_id'],'is_delete'=>0))->count();
            //使用个数
            $v['used_num']=M('coupon')->where(array('coupon_type_id'=>$v['type_id'],'used_time'=>array('neq',0),'is_delete'=>0))->count();
        }

        $this->assign('type_list',array(1=>'按用户发放的优惠券',2=>'线下发放的优惠券',3=>'按订单金额发放的优惠券'));
        $this->assign('list',$result['list']);
        $this->assign('page',$result['show']);
        $this->assign('status',$status);
        $this->display("type_list");
    }

    /**
     * 优惠券类型添加和修改
     */
    public function typeAddAction(){
        $couponTypeModule = M('couponType');
        $action = I('action','add', 'strip_tags');//操作类型
        $id = I('type_id', 0, 'strip_tags');//属性ID
        //获取商品类型
        $cate_list = M('goods_category')->order('id DESC,sort_num ASC')->select();
        $this->assign('cate_list',$cate_list);

        if(IS_POST){
            //判断权限
            if(!parent::checkAdminRoleAction('coupon_type_manage',$action)){
                jscript_msg_tips('亲，您无权进行操作！');
            }
            $returnUrl=I('returnUrl');
            $dataArr = I('data');
            $dataArr['send_start_date']=strtotime($dataArr['send_start_date']);
            $dataArr['send_end_date']=strtotime($dataArr['send_end_date']);
            $dataArr['use_start_date']=strtotime($dataArr['use_start_date']);
            $dataArr['use_end_date']=strtotime($dataArr['send_end_date']);
            if($action == 'edit') {
                $status = $couponTypeModule->where(array('type_id' => $id))->save($dataArr);
                if ($status!== false) {
                    //处理优惠券可使用商品类型
                    M('couponTypeList')->where(array('coupon_type_id'=>$id))->delete();
                    if ($dataArr['is_all']==0){
                        $cate_list=explode(',',I('cate_list'));
                        foreach ($cate_list as $v){
                            $typeArr['coupon_type_id']=$id;
                            $typeArr['goods_cate']=$v;
                            M('couponTypeList')->add($typeArr);
                        }
                    }
                    add_admin_log('edit', $_SESSION['admin']['admin_name']. '成功更新了ID为' . $id . '的优惠券!');
                    jscript_msg("修改成功 ", $returnUrl);
                } else {
                    jscript_msg_tips("修改失败!");
                }
            }else{
                if($dataArr['is_all']==1 || ($dataArr['is_all']==0 && I('cate_list')!='')){
                    $couponTypeId = $couponTypeModule->add($dataArr);
                }else{
                    jscript_msg_tips("请选择商品类型!");
                }

                if ($couponTypeId > 0 ) {
                    //处理优惠券可使用商品类型
                    if ($dataArr['is_all']==0){
                        $type_list=explode(',',I('cate_list'));
                        foreach ($type_list as $v){
                            $typeArr['coupon_type_id']=$couponTypeId;
                            $typeArr['goods_cate']=$v;
                            M('couponTypeList')->add($typeArr);
                        }
                    }
                    add_admin_log('add', $_SESSION['admin']['admin_name'] . '成功增加了ID为' . $couponTypeId . '的优惠券!');
                    jscript_msg("添加成功 ", $returnUrl);
                } else {
                    jscript_msg_tips("添加失败!");
                }
            }
        }else {
            //判断权限
            if(!parent::checkAdminRoleAction('coupon_type_manage',$action)){
                jscript_msg_tips('亲，您无权进行操作！');
            }
            $returnUrl=$_SERVER['HTTP_REFERER'];//获取上一操作页面

            if ($action == 'edit') {
                $info = $couponTypeModule->find($id);
                if ($info['is_all']==0) {
                    $info['goods_cate_list']=M('couponTypeList')->alias('c')->join(C('DB_PREFIX').'goods_category as g ON c.goods_cate=g.id')->field('id,title')->where(array('coupon_type_id'=>$id))->select();
                }
            }else{
                //添加操作初始化时间空间
                $info['send_type'] = 1;
                $info['is_all']=1;
                $info['send_start_date'] = time();
                $info['send_end_date'] = strtotime("+1month");
                $info['use_start_date'] = time();
                $info['use_end_date'] =strtotime("+1month");
            }
            $this->assign('info', $info);
            //具体操作类型
            $this->assign('action',$action);
            $this->assign('returnUrl',$returnUrl);
            $this->assign('sendtype_list',array(1=>'按用户发放的优惠券',2=>'线下发放的优惠券',3=>'按订单金额发放的优惠券'));
            $this->display('type_edit');
        }
    }
    /**
     * 优惠券类型删除
     */
    public function typeDelAction(){
        $ids = I('ids');
        $couponTypeModel=M('couponType');
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        $action=I('get.action');
        //判断权限
        if(!parent::checkAdminRoleAction('coupon_type_manage','deleted')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        if ($action=='del'){
            //逻辑删除
            //判断id是数组还是一个数值
            $count_record = 0;
            if(is_array($ids)){
                for($i = 0; $i < count($ids); $i++){
                    $condition["type_id"] = $ids[$i];
                    $del_count = $couponTypeModel->where($condition)->setField(array('is_del'=>1));
                    if($del_count!==false){
                        $count_record ++;
                    }else{
                        $count_record = false;
                    }
                }
            }else{
                $count_record = $couponTypeModel->where(array('type_id'=>$ids))->setField('is_del','1');
            }

            if($count_record!==false) {
                add_admin_log('del',$_SESSION['admin']['admin_name'].'成功将'.$count_record.'条优惠券类型放入回收站!');
                jscript_msg("删除成功 ",$returnUrl);
            }else{
                jscript_msg_tips("删除失败！");
            }
        }elseif ($action=='deleted'){
            //物理删除
            //判断id是数组还是一个数值
            $count_record = 0;
            if(is_array($ids)){
                for($i = 0; $i < count($ids); $i++){
                    $condition["type_id"] = $ids[$i];
                    $del_count = $couponTypeModel->where($condition)->delete();
                    //同时删除该类型的优惠券
                    M('Coupon')->where(array('coupon_type_id'=>$ids[$i]))->delete();
                    //删除该优惠券适用范围
                    M('CouponTypeList')->where(array('coupon_type_id'=>$ids[$i]))->delete();
                    if($del_count!==false){
                        $count_record +=$del_count;
                    }else{
                        $count_record = false;
                    }
                }
            }else{
                $count_record = $couponTypeModel->where(array('type_id'=>$ids))->delete();
                //同时删除该类型的优惠券
                M('Coupon')->where(array('coupon_type_id'=>$ids))->delete();
                M('CouponTypeList')->where(array('coupon_type_id'=>$ids))->delete();
            }

            if($count_record!==false) {
                add_admin_log('del',$_SESSION['admin']['admin_name'].'成功删除了'.$count_record.'条优惠券类型!');
                jscript_msg("删除成功 ",$returnUrl);
            }else{
                jscript_msg_tips("删除失败！");
            }
        }elseif ($action=="reduction"){
            //还原
            //判断id是数组还是一个数值
            $count_record = 0;
            if(is_array($ids)){
                for($i = 0; $i < count($ids); $i++){
                    $condition["type_id"] = $ids[$i];
                    $del_count = $couponTypeModel->where($condition)->setField(array('is_del'=>0));
                    if($del_count!==false){
                        $count_record ++;
                    }else{
                        $count_record = false;
                    }
                }
            }else{
                $count_record = $couponTypeModel->where(array('type_id'=>$ids))->setField('is_del','0');
            }

            if($count_record!==false) {
                add_admin_log('del',$_SESSION['admin']['admin_name'].'成功将'.$count_record.'条优惠券类型还原!');
                jscript_msg("还原成功 ",$returnUrl);
            }else{
                jscript_msg_tips("还原失败！");
            }
        }

    }

    /**
     * 优惠券发放
     */
    public function typeSendAction(){
        $type_id=I('type_id');
        $send_type=I('send_type');
        $couponTypeModel=M('couponType');
        if(IS_POST){
            //判断权限
            if(!parent::checkAdminRoleAction('coupon_type_manage','add')){
                jscript_msg_tips('亲，您无权进行操作！');
            }
            $returnUrl=I('returnUrl');
            $couponModel=M('coupon');
            $count_record = 0;
            if($send_type==1) {
                $users_group=I('users_group');
                if ($users_group){
                    //根据用户组发放优惠券
                    foreach ($users_group as $v){
                        //获取当前用户组所有is_lock=0用户
                        $users_list=M('users')->field('uid')->where(array('group_id'=>$v,'is_lock'=>0))->select();
                        //优惠券发放
                        foreach ($users_list as $user){
                            $dataArr=array('coupon_type_id'=>$type_id,'user_id'=>$user['uid']);
                            $couponId= $couponModel->add($dataArr);
                            if($couponId!==false){
                                $count_record ++;
                            }else{
                                $count_record = false;
                            }
                        }
                    }
                }
                $users=I('user_list');
                if ($users){
                    //指定用户发放优惠券
                    $users_array=explode(',',$users);
                    foreach ($users_array as $u){
                        $dataArr=array('coupon_type_id'=>$type_id,'user_id'=>$u);
                        $couponId= $couponModel->add($dataArr);
                        if($couponId!==false){
                            $count_record ++;
                        }else{
                            $count_record = false;
                        }
                    }
                }
            }elseif($send_type==2){

                $coupon_num=I('coupon_num');
                for ($i=0;$i<$coupon_num;$i++){
                    $data['coupon_sn']=substr(md5(time().mt_rand(1,1000000)),8,16);
                    $data['coupon_type_id']=$type_id;
                    $couponId=$couponModel->add($data);
                    if($couponId!==false){
                        $count_record ++;
                    }else{
                        $count_record = false;
                    }
                }
            }
            if($count_record!==false) {
                add_admin_log('del',$_SESSION['admin']['admin_name'].'成功发放了'.$count_record.'条type_id为'.$type_id.'的优惠券!');
                jscript_msg("发放成功 ",$returnUrl);
            }else{
                jscript_msg_tips("发放失败！");
            }
        }else {
            //判断权限
            if(!parent::checkAdminRoleAction('coupon_type_manage','add')){
                jscript_msg_tips('亲，您无权进行操作！');
            }
            $returnUrl=$_SERVER['HTTP_REFERER'];//获取上一操作页面

            //添加操作初始化时间空间
            if ($send_type==1){
                //获取所有用户组
                $users_group=M('users_group')->field('gid,group_name')->select();
                $this->assign('users_group',$users_group);

            }elseif ($send_type==2){
                //获取发放类型为线下发放的所有优惠券类型
                $coupon_type_list=$couponTypeModel->field('type_id,type_name,type_money,min_goods_amount')->where(array('send_type'=>$send_type))->select();
                $this->assign('coupon_type_list', $coupon_type_list);
            }
            $this->assign('type_id', $type_id);
            $this->assign('send_type', $send_type);
            //具体操作类型
            $this->assign('returnUrl',$returnUrl);
            $this->display('type_send');
        }
    }

    /*搜索用户
         * Param          string       keyword          关键词
         * Return         json                          返回Json对象
         * */
    public function ajaxUserListAction(){
        if(!parent::checkAdminRoleAction('users_manage','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $keyword = I('keyword',0,'strip_tags');
        if($keyword) {
            $condition['nick_name|user_name|uid'] = array('like', '%' . $keyword . '%');
        }
        $condition['is_lock']=0;
        $userModule = D('users');
        $order = 'uid DESC';
        $result = $userModule->field('uid,nick_name,user_name')->where($condition)->order($order)->select();
        $this->ajaxInfoReturn($result,'获取成功！',1);
    }

    /******************************优惠券列表*********************************************/
    //优惠券列表
    public function listAction(){
        if(!parent::checkAdminRoleAction('coupon_manage','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $pageNum = I('p',1,'strip_tags');
        //优惠券类型
        $coupon_type_id = I('coupon_type_id');
        $condition['coupon_type_id']=$coupon_type_id;
        $pageCondition['coupon_type_id']=$coupon_type_id;
        //发放类型
        $sendType=I('send_type');
        $condition['send_type']=$sendType;
        $pageCondition['send_type']=$sendType;

        //状态类型，正常或回收站
        $status=I('get.status');
        //获取当前优惠券类型已发放优惠券列表
        $couponModel=D('Common/coupon');
        $result=$couponModel->getCouponPageList($pageNum,'',$condition,$pageCondition,$status);
        $this->assign('send_type',$sendType);
        $this->assign('list',$result['list']);
        $this->assign('page',$result['show']);
        $this->assign('coupon_type_id',$coupon_type_id);
        $this->assign('status',$status);
        $this->display("list");
    }

    /**批量删除优惠券
     * parm     string        $ids               导航ID字符串
     */
    public function delAction(){
        $ids = I('ids');
        if(!parent::checkAdminRoleAction('coupon_manage','delete')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        $couponModel  = M('coupon');
        $status=I('get.status');
        if ($status=='del'){
            //逻辑删除
            //判断id是数组还是一个数值
            $count_record = 0;
            if(is_array($ids)){
                for($i = 0; $i < count($ids); $i++){
                    $condition["id"] = $ids[$i];
                    $del_count = $couponModel->where(array('coupon_id'=>$ids[$i]))->setField(array('is_delete'=>1));
                    if($del_count!==false){
                        $count_record ++;
                    }else{
                        $count_record = false;
                    }
                }
            }else{
                $count_record = $couponModel->where(array('coupon_id'=>$ids))->setField(array('is_delete'=>1));
            }

            if($count_record!==false) {
                add_admin_log('del',$_SESSION['admin']['admin_name'].'成功删除了'.$count_record.'条优惠券!');
                jscript_msg("删除成功 ",$returnUrl);
            }else{
                jscript_msg_tips("删除失败！");
            }
        }elseif($status=='deleted'){
            //物理删除
            //判断id是数组还是一个数值
            $count_record = 0;
            if(is_array($ids)){
                for($i = 0; $i < count($ids); $i++){
                    $condition["id"] = $ids[$i];
                    $del_count = $couponModel->where(array('coupon_id'=>$ids[$i]))->delete();
                    if($del_count!==false){
                        $count_record ++;
                    }else{
                        $count_record = false;
                    }
                }
            }else{
                $count_record = $couponModel->where(array('coupon_id'=>$ids))->delete();
            }

            if($count_record!==false) {
                add_admin_log('del',$_SESSION['admin']['admin_name'].'成功删除了'.$count_record.'条优惠券!');
                jscript_msg("删除成功 ",$returnUrl);
            }else{
                jscript_msg_tips("删除失败！");
            }

        }elseif ($status=='reduction'){
            //还原
            //判断id是数组还是一个数值
            $count_record = 0;
            if(is_array($ids)){
                for($i = 0; $i < count($ids); $i++){
                    $condition["id"] = $ids[$i];
                    $del_count = $couponModel->where(array('coupon_id'=>$ids[$i]))->setField(array('is_delete'=>0));
                    if($del_count!==false){
                        $count_record ++;
                    }else{
                        $count_record = false;
                    }
                }
            }else{
                $count_record = $couponModel->where(array('coupon_id'=>$ids))->setField(array('is_delete'=>0));
            }

            if($count_record!==false) {
                add_admin_log('del',$_SESSION['admin']['admin_name'].'成功还原了'.$count_record.'条优惠券!');
                jscript_msg("还原成功 ",$returnUrl);
            }else{
                jscript_msg_tips("删除失败！");
            }
        }

    }

}