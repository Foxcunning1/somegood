<?php
/*文章管理*/
namespace Admin\Controller;
use Think\Controller;

class ArticleController extends BaseController{
    /**
     * 文章列表
     */
    public function indexAction(){
        if(!parent::checkAdminRoleAction('article_list_manage','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $columnId=I('columnId');
        $keywords = I('keywords');
        $pageNum = I('p',1,'strip_tags');
        if($keywords) {
            $condition['title|keywords'] = array('like', '%' . $keywords . '%');
            $pageCondition['keywords'] = $keywords;
        }
        if($columnId) {
            $condition['cid'] = $columnId;
            $pageCondition['cid'] = $columnId;
            $this->assign('columnId',$columnId);
        }

        //栏目列表
        $column_list=parent::treeAction(D('column')->order('parent_id ASC , sort_num ASC , id DESC')->select(), 0, 1);
        //获取文章列表
        $articleModel=D('Admin/Article');
        $order = 'add_time DESC,aid DESC';
        $result = $articleModel->getArticlePageList($pageNum,0,$order,$condition,$pageCondition);
        $this->assign('column_list',$column_list);
        $this->assign('list',$result['list']);
        $this->assign('page',$result['show']);
        $this->display("Article/list");
    }

    /**
     * 文章添加和修改
     */
    public function addAction(){
        $articleModule = D('article');
        $action = I('action','add', 'strip_tags');//操作类型
        $id = I('id', 0, 'strip_tags');//属性ID
        $position = $articleModule->getAllPositionAction();//获取推荐位

        if(IS_POST){
            $recdata=I('recdata');
            //判断权限
            if(!parent::checkAdminRoleAction('article_list_manage',$action)){
                jscript_msg_tips('亲，您无权进行操作！');
            }
            $returnUrl=I('returnUrl');
            $dataArr = I('data');
            $position = I('position');
            $dataArr['rec_id'] = implode(',',$position);
            $dataArr['add_time'] = strtotime($dataArr['add_time']);
            if($dataArr['add_time']==0){
                $dataArr['add_time'] = time();
            }
            $param['title'] = $dataArr['title'];
            $param['img_url'] = $dataArr['img_url'];
            $param['add_time'] = $dataArr['add_time'];
            $param['sort_num'] = $dataArr['sort_num'];
            if($action == 'edit') {
                $dataArr['update_time'] = time();
                $status = $articleModule->where(array('aid' => $id))->save($dataArr);
                if ($status!== false) {
                    //推荐位处理
                    $articleModule->setContentPositionAction($id,$position,$param);
                    add_admin_log('edit', $_SESSION['admin']['admin_name']. '成功更新了ID为' . $id . '的文章!');
                    if ($recdata==1){
                        close_msg_tips("修改成功");
                    }else{
                        jscript_msg("修改成功 ", $returnUrl);
                    }
                } else {
                    jscript_msg_tips("修改失败!");
                }
            }else{
                $dataArr['publisher'] = session("admin.admin_name");
                $dataArr['admin_id'] = session("admin.admin_id");
                $articleId = $articleModule->add($dataArr);
                if ($articleId > 0 ) {
                    //推荐位处理
                    $articleModule->setContentPositionAction($articleId,$position,$param);
                    add_admin_log('add', $_SESSION['admin']['admin_name'] . '成功增加了ID为' . $articleId . '的文章!');
                    jscript_msg("添加成功 ", $returnUrl);
                } else {
                    jscript_msg_tips("添加失败!");
                }
            }
        }else {
            //判断权限
            if(!parent::checkAdminRoleAction('article_list_manage',$action)){
                jscript_msg_tips('亲，您无权进行操作！');
            }
            $returnUrl=$_SERVER['HTTP_REFERER'];//获取上一操作页面

            if ($action == 'edit') {
                $info = $articleModule->find($id);
                if ($info['rec_id']) {
                    $rec = explode(',', $info['rec_id']);
                    foreach ($rec AS $val) {
                        $rec[$val] = 'checked';
                    }
                    $this->assign('rec', $rec);
                }
            }else{
                //添加操作初始化时间空间
                $info['is_rec'] = 0;
                $info['sort_num'] = 999;
                $info['parent_id'] = $id;
                $info['add_time'] = time();
            }
            $this->assign('info', $info);
            $column_list=parent::treeAction(M('column')->order('parent_id ASC , sort_num ASC , id DESC')->select(), 0, 1);
            $this->assign('column_list',$column_list);
            //具体操作类型
            $this->assign('action',$action);
            $this->assign('returnUrl',$returnUrl);
            $this->assign('position', $position);
            $this->display('Article/edit');
        }
    }

    /**批量删除文章
     * parm     string        $ids               导航ID字符串
     */
    public function delAction(){
        $ids = I('ids');
        if(!parent::checkAdminRoleAction('article_list_manage','delete')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        $articleModel  = M('article');
        //判断id是数组还是一个数值
        $count_record = 0;
        if(is_array($ids)){
            for($i = 0; $i < count($ids); $i++){
                $condition["aid"] = $ids[$i];
                $del_count = $articleModel->where(array('aid'=>$ids[$i]))->delete();
                if($del_count!==false){
                    $count_record += $del_count;
                }else{
                    $count_record = false;
                }
            }
        }else{
            $count_record = $articleModel->where(array('aid'=>$ids))->delete();
        }

        if($count_record!==false) {
            add_admin_log('del',$_SESSION['admin']['admin_name'].'成功删除了'.$count_record.'条文章!');
            jscript_msg("删除成功 ",$returnUrl);
        }else{
            jscript_msg_tips("删除失败！");
        }
    }

/************************************************文章栏目*****************************************************************************/
    /**
     * 栏目列表
     */
    public function columnAction(){
        if(!parent::checkAdminRoleAction('article_column_manage','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $keywords = I('keywords');
        if($keywords) {
            $condition['c_name'] = array('like', '%' . $keywords . '%');
        }
        $list = D('Article')->getArticleColumnTreeListAction($condition);
        $this->assign('list',$list);
        $this->display();
    }
    /**
     * 文章栏目添加和修改
     */
    public function columnAddAction(){
        $columnModule = M('column');
        $action = I('action','add', 'strip_tags');//操作类型
        $id = I('id', 0, 'strip_tags');//属性ID
        if(IS_POST){
            $returnUrl=I('returnUrl');
            //判断权限
            if(!parent::checkAdminRoleAction('article_column_manage',$action)){
                jscript_msg_tips('亲，您无权进行操作！');
            }
            $dataArr = I('data');
            $class_list              = get_deep_table_class_list('column',$dataArr['parent_id']);
            $dataArr['class_list']   = $class_list;//导航层级关系
            //获得父类的层级数量
            $class_layer             = get_deep_table_class_layer('column',$dataArr['parent_id']);
            $dataArr['class_layer']  = $class_layer + 1;//导航层级数量
            if($action == 'edit') {
                $new_class=$columnModule->field('class_list')->where(array('id'=>$dataArr['parent_id']))->find()['class_list'];
                if (in_array($id,explode(',',$new_class))){
                    jscript_msg("父栏目不可为当前栏目子栏目",$_SERVER['HTTP_REFERER']);
                }else{
                    $status = $columnModule->where(array('id' => $id))->save($dataArr);
                    if ($status!== false) {
                        if($dataArr['class_list']==""){
                            $class_list = ",".$id.",";
                        }else{
                            $class_list = $class_list.$id.",";
                        }
                        $columnModule->where("id = $id")->setField('class_list',$class_list);
                        add_admin_log('edit', session('admin_name') . '成功更新了ID为' . $id . '的文章栏目!');
                        jscript_msg("修改成功 ", $returnUrl);
                    } else {
                        jscript_msg_tips("修改失败!");
                    }
                }
            }else{
                $columnId = $columnModule->add($dataArr);
                if ($columnId > 0 ) {
                    if($dataArr['class_list']==""){
                        $class_list = ",".$columnId.",";
                    }else{
                        $class_list = $class_list.$columnId.",";
                    }
                    $columnModule->where("id=$columnId")->setField('class_list',$class_list);
                    add_admin_log('add', $_SESSION['admin']['admin_name'] . '成功增加了ID为' . $columnId . '的文章栏目!');
                    jscript_msg("添加成功 ", $returnUrl);
                } else {
                    jscript_msg_tips("添加失败!");
                }
            }
        }else {
            //判断权限
            if(!parent::checkAdminRoleAction('article_column_manage',$action)){
                jscript_msg_tips('亲，您无权进行操作！');
            }
            $returnUrl=$_SERVER['HTTP_REFERER'];//获取上一操作URL
            //添加操作初始化时间空间
            if ($action == 'edit') {
                $info = $columnModule->find($id);
                $info['son_count']=$columnModule->where(array("parent_id"=>$info['id']))->count();

            }else{
                $info['c_type']=1;
                $info['c_is_hidden'] = 0;
                $info['sort_num'] = 999;
                $info['parent_id'] = $id;
            }
            $this->assign('info', $info);
            $column_list = D('Article')->getArticleColumnTreeListAction();
            $this->assign('column_list',$column_list);
            //具体操作类型
            $this->assign('action',$action);
            $this->assign('returnUrl',$returnUrl);
            $this->display('Article/column_edit');
        }
    }

    /**批量删除文章栏目
     * parm     string        $ids               导航ID字符串
     */
    public function columnDelAction(){
        $ids = I('ids');
        if(!parent::checkAdminRoleAction('article_column_manage','delete')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        $columnModel  = M('column');
        //判断id是数组还是一个数值
        $count_record = 0;
        $count_article=0;
        if(is_array($ids)){
            for($i = 0; $i < count($ids); $i++){
                $condition["class_list"] = array("like", "%,".$ids[$i].",%");
                //删除该栏目下所有文章
                $column_ids=$columnModel->field('id')->where($condition)->select();
                foreach ($column_ids as $v){
                   $re=M('article')->where(array('a_cid'=>$v['id']))->delete();
                   if ($re!==false){
                       $count_article++;
                   }
                }
                //删除该栏目及子栏目
                $del_count = $columnModel->where($condition)->delete();
                if($del_count!==false){
                    $count_record += $del_count;
                }else{
                    $count_record = false;
                }
            }
        }else{
            $condition["class_list"] = array("like", "%,".$ids.",%");
            //删除该栏目下所有文章
            $column_ids=$columnModel->field('id')->where($condition)->select();
            foreach ($column_ids as $v){
                $count_article=M('article')->where(array('a_cid'=>$v['id']))->delete();
            }
            //删除该栏目及子栏目
            $count_record = $columnModel->where($condition)->delete();
        }

        if($count_record!==false) {
            add_admin_log('del',$_SESSION['admin']['admin_name'].'成功删除了'.$count_article.'条文章!');
            add_admin_log('del',$_SESSION['admin']['admin_name'].'成功删除了'.$count_record.'条文章栏目!');
            jscript_msg("删除成功 ",$returnUrl);
        }else{
            jscript_msg_tips("删除失败！");
        }
    }
    /*************************************************推荐位********************************************************************/
    /**
     * 推荐位列表
     */
    public function recAction(){
        if(!parent::checkAdminRoleAction('article_rec_manage','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $keywords = I('keywords');
        if($keywords) {
            $condition['title|name'] = array('like', '%' . $keywords . '%');
            $pageCondition['keywords'] = $keywords;
        }
        $articleModule = D('Article');
        $order = 'sort_num ASC, id DESC';
        $pageNum = I('p',1,'strip_tags');
        $result = $articleModule->getRecListAction($pageNum,$condition,$pageCondition,$order);
        $this->assign('list',$result['list']);
        $this->assign('page',$result['show']);
        $this->display("Article/rec_list");
    }

    /**
     * 添加和修改推荐位
     */
    public function recAddAction(){
        $action = I('action','add', 'strip_tags');//操作类型
        $id = I('id', 0, 'strip_tags');//类别ID
        $recModule = M('article_rec');
        if(IS_POST){
            if(!parent::checkAdminRoleAction('article_rec_manage',$action)){
                jscript_msg_tips('亲，您无权进行操作！');
            }
            $returnUrl=I('returnUrl');
            $dataArr = I('data');
            if($action == 'edit') {
                $result = $recModule->where(array('id' => $id))->save($dataArr);
                if($result !== false) {
                    add_admin_log('edit', $_SESSION['admin']['admin_name'] . '成功更新了ID为' . $id . '头条推荐位!');
                    jscript_msg("修改成功 ", $returnUrl);
                }
                else jscript_msg_tips("修改失败！");
            }else{
                $recId = $recModule->add($dataArr);
                if($recId > 0) {
                    add_admin_log('add', $_SESSION['admin']['admin_name'] . '成功增加了ID为' . $id . '头条推荐位!');
                    jscript_msg("添加成功 ", $returnUrl);
                }
                else jscript_msg_tips("添加失败！");
            }
        }else {
            if(!parent::checkAdminRoleAction('article_rec_manage',$action)){
                jscript_msg_tips('亲，您无权进行操作！');
            }
            $returnUrl=$_SERVER['HTTP_REFERER'];//获取上一操作URL
            if($action == 'edit'){
                $info = $recModule->find($id);
                $this->assign('info', $info);
            }else{
                $info['sort_num'] = 999;
                $info['max_num']  = 10;
                $this->assign('info', $info);
            }
            $this->assign('returnUrl',$returnUrl);
            $this->assign('action',$action);
            $this->display("rec_edit");
        }
    }

    /**批量删除推荐位
     * parm     string        $ids               导航ID字符串
     */
    public function recDelAction(){
        //判断权限
        if(!parent::checkAdminRoleAction('article_rec_manage','delete')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $ids = I('ids');
        $returnUrl=$_SERVER['HTTP_REFERER'];//获取上一操作页面
        $recModule  = M('article_rec');
        //判断id是数组还是一个数值
        $condition['id'] = array('in',$ids);
        $result = $recModule->where($condition)->delete();
        if($result!==false) {
            add_admin_log('del',$_SESSION['admin']['admin_name'].'成功删除了'.$result.'个头条推荐位!');
            jscript_msg("删除成功 ",$returnUrl);
        }else{
            jscript_msg_tips("删除失败！");
        }
    }

    /** Ajax验证推荐位别名是否重复
     * parm             $param          string            get的数据
     * parm             $old_name        string           初始数据
     */
    public function recNameValidateAction(){
        $oldName           = $_GET['old_name'];
        $param             = $_POST['param'];
        if($param==""){
            $this->ajaxInfoReturn(0,"参数有误",0);
        }else{
            if(strtolower($oldName)==strtolower($param)){
                $this->ajaxInfoReturn(0,"该栏目类别名可使用",'y');
            }else{
                //验证导航菜单是否已占用
                $recModule = M('article_rec');
                $condition = array('name'=>$param);
                if($recModule->where($condition)->count()>0){
                    $this->ajaxInfoReturn(0,"该栏目类别名已使用，请重新填写！",'n');
                }else{
                    $this->ajaxInfoReturn(0,"该栏目类别名可使用",'y');
                }
            }
        }
    }

    /**
     * 推荐位内容
     */
    public function recDataAction(){
        $id = I('id', 0, 'strip_tags');//推荐位ID
        $condition['rec_id'] = $id;
        $keywords = I('keywords');
        if($keywords) {
            $condition['title'] = array('like', '%' . $keywords . '%');
            $pageCondition['keywords'] = $keywords;
        }
        $articleModule = D('Article');
        $pageCondition['id'] = $id;
        $order = 'sort_num ASC , rec_time DESC ';
        $pageNum = I('p',1,'strip_tags');
        $result = $articleModule->getRecDataListAction($pageNum,$condition,$pageCondition,$order);
        $this->assign('id',$id);
        $this->assign('list',$result['list']);
        $this->assign('page',$result['show']);
        $this->assign('rec_id',$id);
        $this->display("rec_data_list");
    }
}
