<?php
//求购模型
namespace Common\Model;
use Think\Model\RelationModel;
use Think\Page;

class WantModel extends RelationModel{
    protected $_link = array(
        //用户表
        'Users' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Users',
            'foreign_key'   => 'w_uid',
            'mapping_name'  => 'users',
            'mapping_fields' => 'nick_name,mobile',
            'as_fields'      => 'nick_name:user_name,mobile:mobile',
        ),
        //商品类别表
        'GoodsType'=> array(
            'class_name'     => 'GoodsCategory',
            'mapping_type'   => self::BELONGS_TO,
            'foreign_key'    => 'w_cateid',
            'mapping_name'   => 'title',
            'mapping_fields' => 'title',
            'as_fields'      => 'title:cate_title',
        ),
        //地区表
        'Region' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Region',
            'foreign_key'   => 'w_region',
            'mapping_name'  => 'region',
            'mapping_fields' => 'merger_name,pid,name',
            'as_fields'      => 'merger_name:region,name:area,pid:city_id',
        ),
    );

    /**获得某个求购的详细信息
     * @param $id                 int              求购id
     * @return mixed              array            查询结果数组
     */
    public function getWantInfo($id){
        $wantModel = M('want');
        $info = $wantModel->where(array('w_id' => $id))->find();
        return $info;
    }


    /** 获得带指定数量的求购列表
     * @param $topCount       int    每页显示的数量
     * @param $condition      array  查询条件
     * @return mixed                 返回数组
     */
    public function getTopWantList($topCount=0 , $condition=array()){
        $wantModel = D('Common/Want');
        $condition['w_id'] = array('gt',0);
        if($topCount>0){
            $list = $wantModel->relation(true)->where($condition)->limit($topCount)->select();
        }else{
            $list = $wantModel->relation(true)->where($condition)->select();
        }
        return $list;
    }

    /**得带页码的求购信息列表
     * @param $pageNum            int              页码
     * @param $order              string           排序
     * @param $condition          array            查询条件
     * @param $pageCondition      array            分页条件
     * @return mixed              array            包括分页信息和查询结果数组
     */
    public function getWantPageList($pageNum = 1,$order = 'w_updatetime DESC',  $condition = array() , $pageCondition = array()){
        import('ORG.Util.Page');
        $wantModule = D('Common/Want');
        $count = $wantModule->where($condition)->count();
        $result['count']=$count;
        $page = new Page($count,C('WANT_PAGE_SIZE'),$pageCondition);
        $result['pageCount']=$page->pageCount();
        $result['show'] = $page->show();
        $result['list'] = $wantModule->relation(true)->where($condition)->order($order)->page($pageNum,C('WANT_PAGE_SIZE'))->select();
        return $result;
    }

}