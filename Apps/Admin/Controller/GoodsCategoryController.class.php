<?php
/*商品分类
 * */
namespace Admin\Controller;
use Think\Controller;

class GoodsCategoryController extends AjaxController{

    public function indexAction(){
        if(!parent::checkAdminRoleAction('goodsCategory_manage','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $keywords = I('keywords');
        if($keywords) {
            $condition['name|title'] = array('like', '%' . $keywords . '%');
        }
        $list = D('Admin/Goods')->getGoodsCategoryTreeListAction($condition);
        $this->assign('list',$list);
        $this->display("GoodsCategory/category_list");
    }

    /**
     * 商品类别添加和修改
     */
    public function addAction(){
        $goodsCategoryModule = M('goods_category');
        $action = I('action','add', 'strip_tags');//操作类型
        $id = I('id', 0, 'strip_tags');//属性ID

        if(IS_POST){
            $returnUrl=I('returnUrl');
            //判断权限
            if(!parent::checkAdminRoleAction('goodsCategory_manage',$action)){
                jscript_msg_tips('亲，您无权进行操作！');
            }
            $dataArr = I('data');
            $class_list              = get_deep_table_class_list('goods_category',$dataArr['parent_id']);
            $dataArr['class_list']   = $class_list;//导航层级关系
            //获得父类的层级数量
            $class_layer             = get_deep_table_class_layer('goods_category',$dataArr['parent_id']);
            $dataArr['class_layer']  = $class_layer + 1;//导航层级数量
            if($action == 'edit') {
                $new_class=$goodsCategoryModule->field('class_list')->where(array('id'=>$dataArr['parent_id']))->find()['class_list'];
                if (in_array($id,explode(',',$new_class))){
                    jscript_msg("父类别不可为当前类别的子类别",$_SERVER['HTTP_REFERER']);
                }else {
                    $status = $goodsCategoryModule->where(array('id' => $id))->save($dataArr);
                    if ($status !== false) {
                        if ($dataArr['class_list'] == "") {
                            $class_list = "," . $id . ",";
                        } else {
                            $class_list = $class_list . $id . ",";
                        }
                        $goodsCategoryModule->where("id = $id")->setField('class_list', $class_list);
                        add_admin_log('edit', session('admin_name') . '成功更新了ID为' . $id . '的商品类别!');
                        jscript_msg("修改成功 ", $returnUrl);
                    } else {
                        jscript_msg_tips("修改失败!");
                    }
                }
            }else{
                $goodsCategoryId = $goodsCategoryModule->add($dataArr);
                if ($goodsCategoryId > 0 ) {
                    if($dataArr['class_list']==""){
                        $class_list = ",".$goodsCategoryId.",";
                    }else{
                        $class_list = $class_list.$goodsCategoryId.",";
                    }
                    $goodsCategoryModule->where("id=$goodsCategoryId")->setField('class_list',$class_list);
                    add_admin_log('add', session('admin_name') . '成功增加了ID为' . $goodsCategoryId . '的商品类别!');
                    jscript_msg("添加成功 ", $returnUrl);
                } else {
                    jscript_msg_tips("添加失败!");
                }
            }
        }else {
            //判断权限
            if(!parent::checkAdminRoleAction('goodsCategory_manage',$action)){
                jscript_msg_tips('亲，您无权进行操作！');
            }
            $returnUrl=$_SERVER['HTTP_REFERER'];//获取上一操作URL
            //添加操作初始化时间空间
            if ($action == 'edit') {
                $info = $goodsCategoryModule->find($id);
            }else{
                $info['is_nav'] = 0;
                $info['is_index'] = 0;
                $info['is_hidden'] = 0;
                $info['sort_num'] = 999;
                $info['parent_id'] = $id;
            }
            $this->assign('info', $info);
            $category_list = D('Goods')->getGoodsCategoryTreeListAction();
            $this->assign('category_list',$category_list);
            //具体操作类型
            $this->assign('action',$action);
            $this->assign('returnUrl',$returnUrl);
            $this->display('GoodsCategory/category_edit');
        }
    }

    /**批量删除商品类型
     * parm     string        $ids               导航ID字符串
     */
    public function delAction(){
        $ids = I('ids');
        if(!parent::checkAdminRoleAction('goodsCategory_manage','delete')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        $goodsCategoryModel  = M('goods_category');
        //判断id是数组还是一个数值
        $count_record = 0;
        if(is_array($ids)){
            for($i = 0; $i < count($ids); $i++){
                $condition["class_list"] = array("like", "%,".$ids[$i].",%");
                $del_count = $goodsCategoryModel->where($condition)->delete();
                if($del_count!==false){
                    $count_record += $del_count;
                }else{
                    $count_record = false;
                }
            }
        }else{
            $condition["class_list"] = array("like", "%,".$ids.",%");
            $count_record = $goodsCategoryModel->where($condition)->delete();
        }
        if($count_record!==false) {
            add_admin_log('del',session('admin_name').'成功删除了'.$count_record.'条商品类别!');
            jscript_msg("删除成功 ",$returnUrl);
        }else{
            jscript_msg_tips("删除失败！");
        }
    }
    /** Ajax验证商品类型调用别名是否重复
     * parm             $param          string            get的数据
     * parm             $old_name        string           初始数据
     */
    public function nameValidateAction(){

        $old_name           = I('get.old_name');
        $param              = I('param');
        if($param==""){
            $this->ajaxInfoReturn(0,"参数有误",0);
        }else{
            if(strtolower($old_name)==strtolower($param)){
                $this->ajaxInfoReturn(0,"该名称可使用",'y');
            }else{
                //验证导航菜单是否已占用
                $advPositionList = M('goods_category');
                $condition = array('name'=>$param);
                if($advPositionList->where($condition)->count()>0){
                    $this->ajaxInfoReturn(0,"该名称已使用，请重新填写！",'n');
                }else{
                    $this->ajaxInfoReturn(0,"该名称可使用",'y');
                }
            }
        }
    }

}
?>
