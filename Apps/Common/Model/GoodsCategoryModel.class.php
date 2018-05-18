<?php
//求购模型
namespace Common\Model;
use Think\Model;
class GoodsCategoryModel extends Model{
    static private $ids = '';//递归调用
    static private $treeList = array();//递归调用
    /*获得对应ID的详细信息
     * parma              int         $id              查询的id
     * return             array       返回数组
     **/
    public function getCategoryInfo($id=0)
    {
        $categoryModel = M('goods_category');
        $result = array();
        $map['id'] = $id;
        $result = $categoryModel->where($map)->find();
        return $result;
    }

    /*获取前几条分类数据
     * @Param        int            $topCount            获得分类的数量
     * @Param        string         $orderStr            排序方式
     * @Param        array          $condition           查询条件
    */
    public function getTopCategoryList($topCount, $condition = array(), $orderStr = "sort_num,id desc "){
        $result = array();
        if($topCount>0){
            $result = M('goods_category')->where($condition)->order($orderStr)->limit($topCount)->select();
        }else{
            $result = M('goods_category')->where($condition)->order($orderStr)->select();
        }
        return $result;
    }
    /*获得所有父类id为$pid的同级信息
     *
     * */
    public function getCategoryList($pid=0){
        $goodsCategoryModel = M('goods_category');
        $result = $goodsCategoryModel->where("parent_id=$pid")->select();
        return $result;
    }

    /**获得指定分类下的所有分类
     * @Param          int             $pid              查询的ID
     * @Return         array
    */
    public function getCategoryChildrenIds($pid = 0){
        $goodsCategoryModel = M('goods_category');
        $condition['class_list'] = array('like',"%,".$pid.",%");
        $ids = $goodsCategoryModel->where($condition)->getField('id',true);
        return $ids;
    }

    /**获得父类id下所有的子类列表信息
     * @Parma              int         $pid              查询的id
     * @Return             array       返回数组
     **/
    public function getCategoryChildrenList ($pid){
        $goodCategoryModel = M('goods_category');
        $categoryList = array();
        $result = $goodCategoryModel->where(array('parent_id'=>$pid))->select();
        if ($result) {
            foreach ($result as $key => $val) {
                $categoryList[$val['id']] = $val;
                $categoryList[$val['id']]['children'] = $this->getCategoryChildrenList($val['id']);
                unset($result['key']);
            }
        }
        return $categoryList;
    }

    /*获得指定的一组ID的信息
     * parma              string         $ids              查询的id
     * return             array          返回数组
     **/
    public function getRegionIdInInfo($ids,$orderStr="pid ASC")
    {
        $result = array();
        if($ids==""){
            return $result;
        }
        $region = M('region');
        $result = array();
        $map['id'] = array('in',$ids);
        $result = $region->where($map)->select();
        return $result;
    }

    /*获得父类id下所有的子类id列表信息,不含子类的子类
     * parma              int         $pid              查询的id
     * return             string      返回ids例如：1,2,3
     **/
    public function getSubChildrenList ($pid)
    {
        $region = M('region');
        $result = array();
        $result = $region->where(array('pid'=>$pid))->select();
        return $result;
    }

    /*获得父类id下所有的子类id列表信息,不含子类的子类
      * parma              int         $pid              查询的id
      * return             string      返回ids例如：1,2,3
      **/
    public function getSubChildrenFiledList ($pid)
    {
        $region = M('region');
        $result = array();
        $result = $region->field(array("id","name"))->where(array('pid'=>$pid))->select();
        return $result;
    }

    /**得到想要的field的值
     * @param $field string field名字
     * @param null $condition 条件
     * @return mixed
     */
    public function getWhatYouWant($field,$condition = null){
        $region = M('region');
        return $region->where($condition)->getField($field);
    }
}