<?php
/** 系统导航菜单管理类，此类需继承BaseController类,用户是否登录验证
 *
 */

namespace Admin\Controller;
use Think\Controller;
use Think\Model;

class CityController extends BaseController{
    /*城市列表*/
    public function listAction(){
        //判断权限
        if(!parent::checkAdminRoleAction('city_manage','view')){
            $this->error('亲，您无权进行操作！');
        }
        $pid = I('get.pid',0);
        //初始化后台系统导航列表
        $regionModel = D('Region');
        $list = $regionModel->getAreaList($pid);
        $info = $regionModel->getRegionInfo($pid);//获取当前pid对应的城市信息
        $this->assign('pid',$pid);
        $this->assign('list',$list);
        $this->assign('info',$info);
        $this->display();
    }
    /**修改或添加城市列表
     * parm     string        $action               操作类型，默认add（添加）
     */
    public function addAction(){
        $action         = I('action','add','strip_tags');//操作类型
        //判断权限
        if(!parent::checkAdminRoleAction('city_manage',$action)){
            $this->error('亲，您无权进行操作！');
        }
        $pid         = I('pid',0,'strip_tags');//父级ID
        $id          = I('id',0,'strip_tags');//当前ID
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        $regionModel = D('Region');
        if(IS_POST){
            $jumpUrl = I('post.returnUrl');
            $dataArr = I('data');
            $coordinate = I('coordinate','');
            if($coordinate!=''){
                $coordinateArr = explode(',',$coordinate);
                $dataArr['lng'] = $coordinateArr[0];
                $dataArr['lat'] = $coordinateArr[1];
            }
            if($dataArr['pinyin']){
                $dataArr['first'] = strtoupper(substr($dataArr['pinyin'], 0, 1 ));//根据拼音获取首字母，并转成大写
            }
            if($action == 'edit') {
                $status = $regionModel->save($id,$dataArr);
                if ($status !== false) {
                    add_admin_log('edit', session('admin.admin_name') . '成功更新了ID为' . $id . '的区域信息!');
                    jscript_msg("修改成功 ", $jumpUrl);
                } else {
                    jscript_msg_tips("修改失败！");
                }
            }else{
                $cityId = $regionModel->add($dataArr);
                if ($cityId > 0 ) {
                    add_admin_log('add', session('admin.admin_name') . '成功增加了ID为' . $cityId . '的区域信息!');
                    jscript_msg("添加成功 ", $jumpUrl);
                } else {
                    jscript_msg_tips("添加失败！");
                }
            }
        }else {
            if ($action == 'edit') {
                //实例化数据
                $info = $regionModel->getRegionInfo($id);
            }
            if ($action == 'add') {
                $info['pid'] = $pid;
            }
            if($pid>0){
                $tempInfo = $regionModel->getRegionInfo($pid);//获取当前pid对应的城市信息
                $info['parent_name'] = $tempInfo['name'];//获取父级名字
            }else{
                $info['parent_name'] = "无父级";//获取父级名字
            }
            //获取系统所有分类
            $this->assign('action', $action);
            $this->assign('returnUrl',$returnUrl);
            $this->assign('info', $info);
            $this->assign('pid', $pid);
            $this->display();
        }
    }
    /**批量删除导航信息
     * parm     string        $ids               导航ID字符串
     */
    public function delAction(){
//     	//判断权限
        if(!parent::checkAdminRoleAction('city_manage','delete')){
            $this->error('亲，您无权进行操作！');
        }
        $idArr        = I('ids');
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        $regionModel  = M('region');
        $condition['id'] = array('in', $idArr);
        $result = $regionModel->where($condition)->delete();
        if($result!==false){
            add_admin_log('del', session('admin.admin_name') . '成功删除了' . $result . '条区域信息，区域信息ID分别为：' . implode(',', $idArr) . '!');
            jscript_msg("删除成功", $returnUrl);
        }else{
            jscript_msg_tips("删除失败");
        }
    }
}