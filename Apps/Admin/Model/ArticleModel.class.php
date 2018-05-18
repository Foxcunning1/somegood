<?php
//文章模型
namespace Admin\Model;
use Think\Model\RelationModel;
use Think\Page;

class ArticleModel extends RelationModel{
    protected $_link = array(
        //文章栏目表
        'Column'=> array(
            'class_name'     => 'Column',
            'mapping_type'   => self::BELONGS_TO,
            'foreign_key'    => 'cid',
            'mapping_name'   => 'column_name,class_list',
            'as_fields'      => 'c_name:column_name,class_list:class_list',
        ),
        //管理员表
        'Admin'=> array(
            'class_name'     => 'Admin',
            'mapping_type'   => self::BELONGS_TO,
            'foreign_key'    => 'admin_id',
            'mapping_name'   => 'admin_name',
            'mapping_fields' => 'admin_name',
            'as_fields'      => 'admin_name:admin_name'
        )
    );

    /**获取文章详情页
     * @Param          int              id          文章di
     */
    public function getArticleInfo($id){
        $articleModule = D('Admin/Article');
        $info = $articleModule->alias("a")->field("a.*,c.c_name,c.class_List")
                              ->join("LEFT JOIN __COLUMN__ AS c ON c.id=a.cid")
                              ->where(array("aid"=>$id))->find();
        return $info;
    }

    /**获取前几条文章信息列表
     * @param $pageNum            int              页码
     * @param $order              string           排序
     * @param $condition          array            查询条件
     * @param $pageCondition      array            分页条件
     * @return mixed              array            包括分页信息和查询结果数组
     */
    public function getTopArticleList($topCount, $condition = array() ,  $order = 'aid DESC'){
        $articleModule = D('Admin/Article');
        if($topCount==0){
            $topCount = 10;
        }
        $list = $articleModule->relation(true)->where($condition)->order('sort_num ASC',$order)->limit($topCount)->select();
        return $list;
    }

    /**得带页码的文章列表
     * @param $pageNum            int              页码
     * @param $order              string           排序
     * @param $condition          array            查询条件
     * @param $pageCondition      array            分页条件
     * @return mixed              array            包括分页信息和查询结果数组
     */
    public function getArticlePageList($pageNum = 1, $pageSize = 0, $order = 'aid DESC',  $condition = array() , $pageCondition = array()){
        $articleModule = D('Admin/Article');
        if($pageSize==0){
            $pageSize = C('WANT_PAGE_SIZE');
        }
        $count = $articleModule->where($condition)->count();
        $page = new \Think\Page($count,$pageSize,$pageCondition);
        $result['ajaxShow'] = $page->ajaxShow();
        $result['show'] = $page->show();
        $result['list'] = $articleModule->relation(true)->where($condition)->order('sort_num ASC',$order)->page($pageNum,$pageSize)->select();
        $result['pageCount'] = $page->pageCount();
        return $result;
    }

    /**根据条件获取某一条数据
     *
    **/
    public function getOneArticleInfo($condition,$orderStr){
        $articleModel = M("Article");
        $info = $articleModel->field("aid,title,cid,add_time,update_time")->where($condition)->order($orderStr)->find();
        return $info;
    }

    /**获得指定分类下的子类非递归，只查询一级
     * @Param       int      $topCount     获取的数量
     * @Param       int      pid           父类Id
    */
    public function getTopColumnList($topCount,$pid=0){
        $columnModel = M("column");
        if($topCount>0){
            return $columnModel->where(array("parent_id"=>$pid))->limit($topCount)->select();
        }else{
            return $columnModel->where(array("parent_id"=>$pid))->select();
        }
    }

    /**获取文章栏目列表
     * @param $condition array 条件
     * @return array 返回树状的类别列表
     */
    public function getArticleColumnTreeListAction($condition = array()){
        $columnModule = M('column');
        if(!$condition) {
            $list = treeAction($columnModule->order('parent_id ASC , sort_num ASC , id DESC')->select(), 0, 1);
        }else{
//            $list = treeAction($columnModule->where($condition)->order('parent_id ASC , sort_num ASC , id DESC')->select(), 0, 1);
            $list=$columnModule->where($condition)->order('parent_id ASC , sort_num ASC , id DESC')->select();
        }
        return $list;
    }
    /*获取推荐位列表*/
    public function getRecListAction($pageNum,$condition,$pageCondition,$order){
        import('ORG.Util.Page');
        $recModule = M('article_rec');
        $count = $recModule->where($condition)->count();
        $page = new Page($count,C('PAGE_SIZE'),$pageCondition);
        $result['show'] = $page->show();
        $result['list'] = $recModule->where($condition)->order($order)->page($pageNum,C('PAGE_SIZE'))->select();
        return $result;
    }

    /**获取所有推荐位
     * @return mixed
     */
    public function getAllPositionAction(){
        $result = M('article_rec')->field('id,title')->select();
        $data = array();
        foreach ($result AS $key=>$value){
            $data[$value['id']] = $value['title'];
        }
        return $data;
    }

    public function getRecDataListAction($pageNum , $condition , $pageCondition , $order){
        import('ORG.Util.Page');
        $recDataModule = M('article_rec_data');
        $count = $recDataModule->where($condition)->count();
        $page = new Page($count,C('PAGE_SIZE'));
        //带入查询条件的页码
        foreach ($pageCondition as $key => $val) {
            $page->parameter .= "$key=" . urlencode($val) . '&';
        }
        $result['show'] = $page->show();
        $list = $recDataModule->where($condition)->order($order)->page($pageNum,C('PAGE_SIZE'))->select();

        foreach ($list as &$value){
            $data = unserialize($value['data']);
            $value['title'] = $data['title'];
            $value['sort_num'] = $data['sort_num'];
        }
        $result['list'] = $list;
        return $result;
    }

    /**推荐位操作
     * @param $id
     * @param $position
     * @param $param
     */
    public function setContentPositionAction($id,$position,$param){
        $content = M('article_rec_data');
        $content->where(array('article_id'=>$id))->delete();
        if(is_array($position)){
            $data['rec_time'] = time();
            $data['article_id'] = $id;
            $data['data'] = serialize($param);
            $data['sort_num']=$param['sort_num'];
            $data['title']=$param['title'];
            foreach($position AS $value){
                $data['rec_id'] = $value;
                $content->add($data);
            }
        }
    }

}