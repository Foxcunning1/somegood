<?php
//求购模型
namespace Common\Model;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Think\Model;
class AdvModel extends Model{
    /** 获得动画类型
     * @return list                动画类型列表
     */
    public function getAdvAnimateTypeList(){
        $advAnimateTypeModel = M('adv_animate_type');
        return $advAnimateTypeModel->order('sort_num desc,aid desc')->select();
    }

    /** 获得带指定数量的广告位列表
     * @param $topCount       int    每页显示的数量
     * @param $condition      array  查询条件
     * @return mixed                包括分页和广告位数组
     */
    public function getTopAdvPosPageList($topCount=0 , $condition=array()){
        $advPosList = M('adv_position');
        $condition['id'] = array('gt',0);
        if($topCount>0){
            $list = $advPosList->where($condition)->limit($topCount)->select();
        }else{
            $list = $advPosList->where($condition)->select();
        }
        return $list;
    }

    /** 获得带页码的广告位列表
     * @param $pageNum        int    页码
     * @param $pageSize       int    每页显示的数量
     * @param $condition      array  查询条件
     * @param $pageCondition  array  分页条件
     * @return mixed                包括分页和广告位数组
     */
    public function getAdvPosPageList($pageNum , $pageSize, $condition , $pageCondition){
        $advPosList = M('adv_position');
        $count = $advPosList->where($condition)->count();
        $page       = new \Think\Page($count,$pageSize,$pageCondition);// 实例化分页类 传入总记录数和每页显示的记录数
        $result['show'] = $page->show();
        $result['list'] = $advPosList->where($condition)->page($pageNum,$pageSize)->select();
        return $result;
    }

    /**获取几条广告信息
     * @param    $advPsId    int      广告位ID
     * @param    $limitCount int      调取广告的数量
     * @param    $condition  array    调取条件
     * @param    $orderStr   string   排序方式
     * @param    mixed array
     */
    public function getTopAdvPageList($advPsId, $limitCount=0, $condition=array(), $orderStr="sort_num ASC,id DESC"){
        if($advPsId>0){
            $condition['adv_pos'] = $advPsId;
        }
        if(empty($condition)){
            $condition['id'] = array('gt',0);
        }
        $adv = M("adv");
        if($limitCount > 0) {
            $list = $adv->where($condition)->order($orderStr)->limit($limitCount)->select();
        }else{
            $list = $adv->where($condition)->order($orderStr)->select();
        }
        return $list;
    }

    /**获得带页码的广告列表
     * @param $pageNum       int      页码
     * @param $condition     array    查询条件
     * @param $pageCondition array    分页条件
     * @return mixed array            包括分页和广告数组
     */
    public function getAdvPageList($pageNum , $condition , $pageCondition){
        $advModel = M('adv');
        $count = $advModel->alias('a')->where($condition)->count();
        $page  = new \Think\Page($count,C('COMMON_PAGE_NUM'),$pageCondition);// 实例化分页类 传入总记录数和每页显示的记录数
        $result['show'] = $page->show();
        $result['list'] = $advModel->alias('a')->field("a.*,ap.title pos_title")->join('__ADV_POSITION__ ap on a.posid = ap.id')->where($condition)->order('sort_num ASC, id DESC')->page($pageNum,C('PAGE_SIZE'))->select();
        return $result;
    }

    /**获得带页码的店铺广告列表
     * @param $pageNum       int      页码
     * @param $condition     array    查询条件
     * @param $pageCondition array    分页条件
     * @param $type          string   类型,online线上/store店铺
     * @return mixed array            包括分页和广告数组
     */
    public function getStoreAdvPageList($pageNum , $condition , $pageCondition,$type){
        $advModel = M('SellerAdvLog');
        $count = $advModel->alias('sal')->where($condition)->count();
        $page  = new \Think\Page($count,C('COMMON_PAGE_NUM'),$pageCondition);// 实例化分页类 传入总记录数和每页显示的记录数
        $result['show'] = $page->show();
        $result['totalPage']=$page->totalPages;
        if($type=='store'){
            //店铺广告位
            $condition['type']=1;
            $result['list'] = $advModel->alias('sal')->field("sal.id,sal.title,sal.img_url,s.store_name,r.name as area,s.address,sap.title as pos_title,sal.adv_id,sal.begin_time,sal.end_time,sal.is_delivery")
                ->join('LEFT JOIN __STORE__ AS s on sal.store_id = s.sid')
                ->join('LEFT JOIN __REGION__ AS r on s.area_id = r.id')
                ->join('LEFT JOIN __STORE_ADV_POSITION__ AS sap on sal.adv_position = sap.id')
                ->where($condition)->order('sal.is_delivery ASC, sal.seller_id DESC')
                ->page($pageNum,C('PAGE_SIZE'))
                ->select();
        }elseif ($type=='online'){
            $condition['type']=0;
            $ids=$advModel->where($condition)->getField('adv_id',true);
            if ($ids){
                $onlineContion['a.id']=array('in',$ids);
                $result1=D('Common/Adv')->getAdvPageList($pageNum,$onlineContion,$pageCondition);
                $result['show']=$result1['show'];
                $result['list']=$result1['list'];
            }
        }
        return $result;
    }

    /**获得带页码的店铺广告位规格列表
     * @param $pageNum       int      页码
     * @return mixed array            包括分页和广告数组
     */
    public function getStorePosPageList($pageNum){
        $advModel = M('StoreAdvPosition');
        $count = $advModel->count();
        $page  = new \Think\Page($count,C('COMMON_PAGE_NUM'));// 实例化分页类 传入总记录数和每页显示的记录数
        $result['show'] = $page->show();
        //已使用的个数
        $sql=M('StoreAdv')->alias("sa")->field("COUNT(pos_id) as used,pos_id")->where('is_lock =1')->group('pos_id')->buildSql();
        $result['list']=$advModel->alias('sap')->field('sap.id,sap.title,width,height,count(sa.id) as count,t.used')
            ->join('LEFT JOIN __STORE_ADV__ AS sa ON sap.id = sa.pos_id')
            ->join('LEFT JOIN '. $sql .' AS t ON sap.id = t.pos_id')
            ->group('sap.id')
            ->page($pageNum,C('COMMON_PAGE_NUM'))
            ->select();
        return $result;
    }
}