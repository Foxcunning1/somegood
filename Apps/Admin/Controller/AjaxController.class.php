<?php
/*后台默认页类
 *
 * */
namespace Admin\Controller;
use Think\Controller;
class AjaxController extends BaseController {
    /*后台获取系统菜单*/
    public function getSystemNavAction(){
        $strMenuHtml = '';
        if(F('Admin/Nav/manage_system_nav_'.session('admin.role_id'))){
            $list = F('Admin/Nav/manage_system_nav_'.session('admin.role_id'));
        }else{
            $list = parent::getSystemNavListAction(0);
            F('Admin/Nav/manage_system_nav_'.session('admin.role_id'),$list);
        }
        if($list){
            foreach($list as $key=>$val){
                $strMenuHtml .= "<div class=\"list-group\">\r\n";
                $strMenuHtml .= "<h1 title=\"".$val['sub_title']."\">";
                if($val['icon']!=""){
                    if(substr($val['icon'],0,1)=='.'){
                        $strMenuHtml .= "<i class=\"iconfont " .substr($val['icon'],1,mb_strlen($val['icon'])-1). "\"></i>";
                    }else{
                        $strMenuHtml .= "<img src=\"" .$val['icon']. "\" />";
                    }
                }
                $strMenuHtml .= "</h1>\n";
                $strMenuHtml .= "<div class=\"list-wrap\">\n";
                $strMenuHtml .= "<h2>" .$val['title']. "<i class=\"iconfont icon-arrow-down\"></i></h2>\n";
                $strMenuHtml .= parent::getSystemNavChildsHtmlAction($val['childs'],2);
                $strMenuHtml .= "</div>\r\n";
                $strMenuHtml .= "</div>\r\n";
            }
        }
        echo $strMenuHtml;
    }
    /**验证角色名字是否存在*/
    public function validateManagerRoleAction(){
        //获取角色列表数据
        if(!parent::checkAdminRoleAction('manager_role_manage','view')){
            $this->ajaxInfoReturn('','没有权限获取验证数据 !','n');
        }
        $old_name           = I('get.old_name');
        $param              = I('param');
        if($param==""){
            die($this->ajaxInfoReturn(0,"参数有误",'n'));
        }else{
            if(strtolower($old_name)==strtolower($param)){
                $this->ajaxInfoReturn(0,"该角色名可使用",'y');
            }else{
                //验证导航菜单是否已占用
                $manageRoleModel = M('admin_role');
                $condition = array('role_name'=>$param);
                if($manageRoleModel->where($condition)->count()>0){
                    die($this->ajaxInfoReturn(0,"该角色名已使用，请重新填写！",'n'));
                }else{
                    die($this->ajaxInfoReturn(0,"该角色名可使用",'y'));
                }
            }
        }
    }

    /**验证角色名字是否存在*/
    public function validateManagerAction(){
        //获取角色列表数据
        if(!parent::checkAdminRoleAction('manager_manage','view')){
            $this->ajaxInfoReturn('','没有权限获取验证数据 !','n');
        }
        $old_name           = I('get.old_name');
        $param              = I('param');
        if($param==""){
            die($this->ajaxInfoReturn(0,"参数有误",'n'));
        }else{
            if(strtolower($old_name)==strtolower($param)){
                $this->ajaxInfoReturn(0,"该用户名可使用",'y');
            }else{
                //验证管理员账户名是否已占用
                $adminModel = M('admin');
                $condition = array('admin_name'=>$param);
                if($adminModel->where($condition)->count()>0){
                    die($this->ajaxInfoReturn(0,"该用户名已使用，请重新填写！",'n'));
                }else{
                    die($this->ajaxInfoReturn(0,"该用户名可使用",'y'));
                }
            }
        }
    }

    /** Ajax验证别名是否重复
     * parm             $param          string            get的数据
     * parm             $old_name        string           初始数据
     */
    public function validateSystemNavAction(){
        //获取角色列表数据
        if(!parent::checkAdminRoleAction('system_nav','view')){
            $this->ajaxInfoReturn('','没有权限获取验证数据 !','n');
        }
        $old_name           = I('get.old_name');
        $param              = I('param');
        if($param==""){
            die($this->ajaxInfoReturn(0,"参数有误",'n'));
        }else{
            if(strtolower($old_name)==strtolower($param)){
                die($this->ajaxInfoReturn(0,"该导航菜单ID可使用",'y'));
            }else{
                //验证导航菜单是否已占用
                $systemNav = M('system_nav');
                $condition = array('name'=>$param);
                if($systemNav->where($condition)->count()>0){
                    die($this->ajaxInfoReturn(0,"该导航菜单ID已使用，请重新填写！",'n'));
                }else{
                    die($this->ajaxInfoReturn(0,"该导航菜单ID可使用",'y'));
                }
            }
        }
    }

    /**页面通用双击修改方法
     * parm     string         $table_name         要修改的表名称
     * parm     string         $id_name            自增字段名称
     * parm     int            $id                 自增字段值
     * parm     string         $field_name         被修改字段的名称
     * parm     string         $field_value        字段值
     * parm     int            $is_exist           字段是否启用验证重复
     * parm     string         $old_value          未修改的字段值名称
     * */
    public function doubleClickModifyAction(){
        $table_name             = I('table_name');
        $id_name                = I('id_name');
        $id                     = I('id');
        $field_name             = I('name');
        $field_value            = I('value');
        $ajax_type              = I('ajax_type');
        if($ajax_type==0){//文字字段修改
            $is_exist               = I('is_exist');
            $old_value              = I('old_value');
            $arr[$field_name]       = $field_value;
            $Data = M($table_name);
            if($is_exist==1){
                if($this->isExistAction($table_name,$id_name,$id,$field_name,$field_value)){
                    $this->ajaxInfoReturn($old_value,'名称已存在，请重新输入！',0);
                    die();
                }
            }
            if($Data->where("$id_name=$id")->setField($arr)){
                $this->ajaxInfoReturn($field_value,'修改成功！',1);
            }else{
                $this->ajaxInfoReturn($old_value,'修改失败！',-1);
            }
        }else{//状态修改
            if($field_value=="1"){
                $arr[$field_name]   = 0;
            }else{
                $arr[$field_name]   = 1;
            }
            $Data = M($table_name);
            if($Data->where("$id_name=$id")->setField($arr)){
                $this->ajaxInfoReturn("",'修改成功！',1);
            }else{
                $this->ajaxInfoReturn("",'修改失败！',0);
            }
        }
    }
    /**判断字段值除了本身以外是否存在
     * parm       int           $id                    字段中ID
     * parm       string        $field_name            字段名称
     * parm       string        $field_value           字段值
     * */
    public function isExistAction($table_name,$id_name,$id,$field_name,$field_value){
        if($id>0){
            $arr[$id_name]          = array('neq',$id);
        }
        $arr[$field_name]       = $field_value;
        $Map = M($table_name);
        if(!is_null($Map->where($arr)->select())){
            return true;
        }else{
            return false;
        }
    }

    //会员中心用户验证
    /**验证用户名字是否存在*/
    public function validateUsersNameAction(){
        //获取角色列表数据
        if(!parent::checkAdminRoleAction('users_manage','view')){
            $this->ajaxInfoReturn('','没有权限获取验证数据 !','n');
        }
        $old_name           = I('get.old_name');
        $param              = I('param');
        if($param==""){
            die($this->ajaxInfoReturn(0,"参数有误",'n'));
        }else{
            if(strtolower($old_name)==strtolower($param)){
                $this->ajaxInfoReturn(0,"该用户名可使用",'y');
            }else{
                //验证导航菜单是否已占用
                $usersModel = M('users');
                $condition = array('user_name'=>$param);
                if($usersModel->where($condition)->count()>0){
                    die($this->ajaxInfoReturn(0,"该用户名已使用，请重新填写！",'n'));
                }else{
                    die($this->ajaxInfoReturn(0,"该用户名可使用",'y'));
                }
            }
        }
    }

    /**验证手机号是否存在*/
    public function validateUsersMobileAction(){
        //获取角色列表数据
        if(!parent::checkAdminRoleAction('users_manage','view')){
            $this->ajaxInfoReturn('','没有权限获取验证数据 !','n');
        }
        $old_name           = I('get.old_name');
        $param              = I('param');
        if($param==""){
            die($this->ajaxInfoReturn(0,"参数有误",'n'));
        }else{
            if(strtolower($old_name)==strtolower($param)){
                $this->ajaxInfoReturn(0,"该用户名可使用",'y');
            }else{
                //验证导航菜单是否已占用
                $usersModel = M('users');
                $condition = array('mobile'=>$param);
                if($usersModel->where($condition)->count()>0){
                    die($this->ajaxInfoReturn(0,"该用手机号已使用，请重新填写！",'n'));
                }else{
                    die($this->ajaxInfoReturn(0,"该用手机号可用",'y'));
                }
            }
        }
    }

    /*城市四级联动通用方法*/
    public function getRegionListAction(){
        $pid = I('pid',0);
        $level = I('type',0);
        $regionModel = D("Common/Region");
        $list = $regionModel->getRegionList($pid,$level);
        echo json_encode($list);
    }
    /*生成城市数据缓存及JS数据*/
    public function buildCityDataListAction(){
        $pid             = I('pid',0);
        $regionModel = D('Common/Region');
        //生成城市JS
        $list = $regionModel->getSubChildrenFiledList($pid);
        $cityList = array();
        foreach($list as $key=>$v){
            $subList = array();
            $subList = $regionModel->getSubChildrenFiledList($v['id']);
            if($subList){
                $list[$key]['child'] = $subList;
                foreach($subList as $k=>$vo){
                    $childrenList = array();
                    $childrenList =  $regionModel->getSubChildrenFiledList($vo['id']);
                    if($childrenList){
                        $list[$key]['child'][$k]['child'] = $childrenList;
                    }
                }
            }
        }
        //var_dump(json_encode_ex($cityList));
        //die();
        file_put_contents("Public/scripts/js/region.js","var cityJson=".json_encode_ex($list).";");
        die($this->ajaxInfoReturn(0,'缓存更新成功!',1));
    }

    /**生成商品分类缓存
     *
     **/
    public function buildGoodsCategoryCacheAction(){
        $pid             = I('pid',0);
        $categoryModel = D('Common/GoodsCategory');
        $list = $categoryModel->getTopCategoryList(0,array(),"parent_id asc,id asc");//获取所有分类数据
        $pattern = array("/ /","/  /","/\r\n/","/\r/","/\n/");//正则替换代码中的换行符、空格
        /*创建多级树结构*/
        $categoryList = tree_category($list,0,1);//调用分类排序函数排序
        $tempArrStr = var_export($categoryList,true);
        //die;
        //$arrStr = str_replace($pattern,"",$tempArrStr);
        $arrStr = preg_replace_callback($pattern,function($m){ return "";},$tempArrStr);
        $arrStr =  "<?php return ".$arrStr."; ?>";
        file_put_contents("Cache/category/category.cache.php",$arrStr);
        /*创建多级树结构 结束*/
        /*创建一级树以count层级为区别*/
        $categoryList1 = treeAction($list,0,1);//进行排序
        $tempArrStr1 = var_export($categoryList1,true);//返回数组为php代码
        $arrStr1 = preg_replace_callback($pattern,function($m){ return "";},$tempArrStr1);//替换空格换行符
        $arrStr1 =  "<?php return ".$arrStr1."; ?>";
        file_put_contents("Cache/category/category.cache.sort.php",$arrStr1);
        /*创建一级树以count层级为区别 结束*/
        die($this->ajaxInfoReturn(0,'缓存更新成功!',1));
    }
    /**生成企业类型缓存
     *
     **/
    public function buildIndustrysTypeCacheAction(){
        $list = M('IndustrysType')->select();//获取所有类型数据
        $pattern = array("/ /","/  /","/\r\n/","/\r/","/\n/");//正则替换代码中的换行符、空格
        /*创建多级树结构*/
        $categoryList = tree_category($list,0,1);//调用分类排序函数排序
        $tempArrStr = var_export($categoryList,true);
        //die;
        //$arrStr = str_replace($pattern,"",$tempArrStr);
        $arrStr = preg_replace_callback($pattern,function($m){ return "";},$tempArrStr);
        $arrStr =  "<?php return ".$arrStr."; ?>";
        file_put_contents("Cache/industrys/industrys_type.cache.php",$arrStr);
        /*创建多级树结构 结束*/
        /*创建一级树以count层级为区别*/
        $categoryList1 = treeAction($list,0,1);//进行排序
        $tempArrStr1 = var_export($categoryList1,true);//返回数组为php代码
        $arrStr1 = preg_replace_callback($pattern,function($m){ return "";},$tempArrStr1);//替换空格换行符
        $arrStr1 =  "<?php return ".$arrStr1."; ?>";
        file_put_contents("Cache/industrys/industrys_type.cache.sort.php",$arrStr1);
        /*创建一级树以count层级为区别 结束*/
        die($this->ajaxInfoReturn(0,'缓存更新成功!',1));
    }

    //后台清空缓存数据

    /*更新缓存
     *
     * */
    /*此方法为公共方法用来删除某个文件夹下的所有文件
    * $path为文件的路径
    * $fileName文件夹名称
    * */
    public function rmFile($path,$fileName){
        //去除空格
        $path = preg_replace('/(\/){2,}|{\\\}{1,}/','/',$path);
        //得到完整目录
        $path.= $fileName;
        //判断此文件是否为一个文件目录
        if(is_dir($path)){
            //打开文件
            if ($dh = opendir($path)){
                //遍历文件目录名称
                while (($file = readdir($dh)) != false){
                    //逐一进行删除
                    unlink($path.'/'.$file);
                }
                //关闭文件
                closedir($dh);
            }
        }
    }

    /**清空系统菜单产生的数据缓存
     * parm       string        $cacheName             对应模块所有的缓存文件路径，格式如：Cache-Data-Temp-Logs-Cache/Home-Cache
     * */
    public function clearSystemCacheAction(){
        $cacheName = I('cacheName');
        if(!$cacheName){
            $this->ajaxInfoReturn("","缓存文件名字不能为空",0);
            die();
        }
        ////前台用ajax get方式进行提交的，这里是先判断一下
        $type = $cacheName;
        //将传递过来的值进行切割，我是已“-”进行切割的
        $name=explode('-', $type);
        //得到切割的条数，便于下面循环
        $count=count($name);
        //循环调用上面的方法
        for ($i=0;$i<$count;$i++){
            //得到文件的绝对路径
            $abs_dir=dirname(dirname(dirname(dirname(__FILE__))));
            //组合路径
            $pa=$abs_dir.'/Runtime/';
            //调用删除文件夹下所有文件的方法
            $this->rmFile($pa,$name[$i]);
        }
        //给出提示信息
        $this->ajaxInfoReturn("","清除缓存成功！",1);
    }

    /*根据中文词获取对应的拼音*/
    public function getPinyinByChinsesAction(){
        $str = I('param');//被划词的字符串
        if($str!="") {
            vendor('Pinyin.Pinyin');//引入中文转拼音类
            //引入第三方中文转拼音
            $Pinyin = new \Pinyin();
            $keywordPinyin = $Pinyin::getPinyin($str);
            die($this->ajaxInfoReturn($keywordPinyin, '数据获取成功！', 1));
        }else{
            die($this->ajaxInfoReturn($str,'数据获取失败！',0));
        }
    }
}