<?php
//求购模型
namespace Common\Model;
use Think\Model;
class RegionModel extends Model{
    static private $num = 1;//定义递归循环数
    static private $ids = "";//递归后的存储

    /*添加区域信息
     * @Param      array        $dataArr    区域信息对应的数组
     * @Return     int          $result     区域信息id
     */
    public function add($dataArr){
        if($dataArr) {
            $regionModel = M('region');
            $result = $regionModel->data($dataArr)->add();
            /*更新全称、层级*/
            if($result>0){
                /*获取当前信息的父信息*/
                if($dataArr['pid']==0){
                    $data['merger_name'] = "中国,".$dataArr['shortname'];
                    $data['level'] = 1;
                }else{
                    $parentInfo = $this->getRegionInfo($dataArr['pid']);
                    $data['merger_name'] = $parentInfo['merger_name'].",".$dataArr['shortname'];
                    $data['level'] = $parentInfo['level']+1;
                }
                $regionModel->where(array('id'=>$result))->data($data)->save();//更新全称和层级
            }
            return $result;
        }else{
            return null;
        }
    }

    /*添加区域信息
     * @Param      int          $id         区域信息对应的id
     * @Param      array        $dataArr    区域信息对应的数组
     * @Return     bool                     返回bool值
     */
    public function save($id,$dataArr){
        if($dataArr) {
            /*获取当前信息的父信息*/
            /*更新全称、层级*/
            if($dataArr['pid']==0){
                $dataArr['merger_name'] = "中国,".$dataArr['shortname'];
                $dataArr['level'] = 1;
            }else{
                $parentInfo = $this->getRegionInfo($dataArr['pid']);
                $dataArr['merger_name'] = $parentInfo['merger_name'].",".$dataArr['shortname'];
                $dataArr['level'] = $parentInfo['level']+1;
            }
            $regionModel = M('region');
            $result = $regionModel->where(array("id"=>$id))->data($dataArr)->save();
            return $result;
        }else{
            return null;
        }
    }

    /*判断用户当前位置城市ID是是否与选择城市id一致，如果不一致，则返回false
    * @Return     bool
    */
    public function isExist(){
        //根据用户坐标位置返回的session城市名字判断该城市是否开通，如果未开通则返回false
        $cityName = session('city_name');//用户所在的城市名字，根据百度地图api获取
        $userCityInfo = $this->getRegionInfoByName($cityName);
        //根据cookie判断当前城市是否已开通城市站，如果没开通，则自动切换至默认站点
        if($userCityInfo){
            if($userCityInfo['id'] != cookie('city_id')){
                return false;
            }
            return true;
        }else{
            return false;
        }
    }
    /*获得对应ID的详细信息
     * parma              int         $id              查询的id
     * return             array       返回数组
     **/
    public function getRegionInfo($id=0)
    {
        $region = M('region');
        $result = array();
        $map['id'] = $id;
        $result = $region->where($map)->find();
        return $result;
    }
    /*获得城市名字获得城市信息
     * parma              string         $name              查询的名字
     * return             array       返回数组
     **/
    public function getRegionInfoByName($name)
    {
        $region = M('region');
        $result = array();
        $map['name'] = $name;
        $result = $region->where($map)->find();
        return $result;
    }

    /*获得所有父类id为$pid的信息
     *
     * */
    public function getAreaList($pid=0){
        $region = M('region');
        $result   = $region->where("pid=$pid")->select();
        return $result;
    }

    /*Ajax四级城市联动
     *
     * */
    public function getRegionList($pid,$type){
        $list = array();
        if($pid<1||$type<1){
            return $list;
        }
        $Region=M("Region");
        $map['pid']=$pid;
        $map['type']=$type;
        $list = $Region->where($map)->select();
        return $list;
    }

    /*获得父类id下所有的子类id列表信息
     * parma              int         $pid              查询的id
     * return             string      返回ids例如：1,2,3
     **/
    /*public function getChildrenIds ($pid)
   {
       $region = M('region');
      $ids = '';
      $result = $region->where(array('pid'=>$pid))->select();
      if ($result)
      {
         foreach ($result as $key=>$val)
         {
            $ids .= ','.$val['id'];
               $ids .= $this->getChildrenIds ($val['id']);
         }
      }
      return $ids;
   }*/

    /*获得父类id下所有的子类id列表信息
     * parma              int         $pid              查询的id
     * return             string      返回ids例如：1,2,3
     **/
    public function getChildrenIds ($pid)
    {
        $region = M('region');
        $result = array();
        $result = $region->where(array('pid'=>$pid))->select();
        if ($result)
        {
            foreach ($result as $key=>$val)
            {
                self::$ids .= $val['id'].',';
                $this->getChildrenIds ($val['id']);
            }
        }
        return self::$ids;
    }

    /*获得父类id下所有的子类id列表信息
     * parma              int         $pid              查询的id
     * return             array       返回数组
     **/
    public function getChildrenList ($pid)
    {
        $region = M('region');
        $result = array();
        $count = 0;
        $result = $region->where(array('pid'=>$pid))->select();
        if ($result)
        {
            foreach ($result as $key=>$val)
            {
                $result[$key]["count"] = $count;
                $count++;
                $temp =  $this->getChildrenList ($val['id']);
                if($temp != null) {
                    $result = array_merge($result, $temp);
                }
            }
        }
        return $result;
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