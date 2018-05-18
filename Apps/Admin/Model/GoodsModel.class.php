<?php
//商品模型
namespace Admin\Model;
use Think\Model\RelationModel;
use Think\Page;
class GoodsModel extends RelationModel{
    //商品类型表
    protected $_link = array(
    //厂家表
        'UsersSeller'=> array(
            'class_name'     => 'UsersSeller',
            'mapping_type'   => self::BELONGS_TO,
            'foreign_key'    => 'user_id',
            'mapping_name'   => 'seller',
            'mapping_fields' => 'company_name',
            'as_fields'      => 'company_name:company_name',
        ),
    //投放店铺表
        'SellerDelivery'=> array(
            'class_name'   =>'SellerDelivery',
            'mapping_type' => self::HAS_MANY,
            'foreign_key'   => 'sg_id',
            'mapping_name'  => 'store',
        ),
    //商品分类表
        'GoodsCategory'=> array(
            'class_name'     => 'GoodsCategory',
            'mapping_type'   =>self::BELONGS_TO,
            'foreign_key'    => 'category_id',
            'mapping_fields' => 'title',
            'as_fields'      => 'title:category_title',
        ),
    );

    /**得带页码的商品列表
     * @param $pageNum            int              页码
     * @param $order              string           排序
     * @param $condition          array            查询条件
     * @param $pageCondition      array            分页条件
     * @return mixed              array            包括分页信息和查询结果数组
     */
    public function getDeliveryPageList($pageNum = 1,$order = 'id DESC',  $condition = array() , $pageCondition = array()){
        $goodsModule = D('Admin/Goods');
        $count = $goodsModule->where($condition)->count();
        $page = new \Think\Page($count,C('PAGE_SIZE'),$pageCondition);
        //带入查询条件的页码
        foreach ($pageCondition as $key => $val) {
            $page->parameter .= "$key=" . urlencode($val) . '&';
        }
        $result['show'] = $page->show();
        $result['list'] = $goodsModule->relation(true)->where($condition)->order($order)->page($pageNum,C('PAGE_SIZE'))->select();
        //var_dump($result['list']);
        return $result;
    }


    /**获得单条产品的信息
     * @param $id               int                 产品ID
     * @return mixed            array               产品信息
     */
    public function getDeliveryById($id){
        $info=D('Admin/Goods')->relation(true)->where(array('id'=>$id))->find();
        return $info;
    }

    /**获取商品投放店铺列表
     * @param $pageNum            int                页码
     * @param $order              string             排序
     * @param $condition          array              查询条件
     * @param $pageCondition      array              分页条件
     * @return mixed              array              包括分页信息和查询结果数组
     */
    public function getDeliveryStoreList($pageNum=1,$order='is_delivery asc,end_time desc,stock asc',$condition,$pageCondition){
        $deliveryModel=M('SellerDelivery');
        $count = $deliveryModel->alias('sd')->field('num,stock,is_delivery,end_time,s.store_name,sn')
            ->join('LEFT JOIN __STORE__ AS s on sd.store_id = s.sid')
            ->join('LEFT JOIN __STORE_BOX__ AS sb on sd.box_id = sb.id')
            ->where($condition)
            ->count();
        $page = new \Think\Page($count,C('PAGE_SIZE'),$pageCondition);
        $result['show'] = $page->show();
        $result['list'] =$deliveryModel->alias('sd')->field('sd.id,goods_title,num,stock,is_delivery,end_time,s.store_name,sn')
            ->join('LEFT JOIN __STORE__ AS s on sd.store_id = s.sid')
            ->join('LEFT JOIN __STORE_BOX__ AS sb on sd.box_id = sb.id')
            ->where($condition)
            ->order($order)
            ->page($pageNum,C('PAGE_SIZE'))
            ->select();
        return $result;
    }

    /**获取商品指定数量的商品
     * @param $catId              int              商品分类ID
     * @param $limitCount         int              商品显示的数量
     * @param $orderStr           string           排序
     * @param $condition          array            查询条件
     * @return mixed              array            包括分页信息和查询结果数组
     */
    public function getTopGoodsList($catId = 0, $limitCount = 10, $condition = array(), $orderStr = 'id DESC'){
        if($catId > 0){
            $map['class_list'] = array("like","%,".$catId.",%");
            $subQuery = M('goods_category')->field('id')->where($map)->order("sort_num,id DESC")->buildSql();
            $condition['g.category_id'] = array('in',$subQuery,'exp');
        }
        $goodsPriceListModule = M('goods_price_list');
        if($orderStr == ""){
            $orderStr = 'id DESC';
        }
        if($limitCount > 0){
            $result = $goodsPriceListModule->alias('gpl')->field('g.*,gpl.*')->join('cs_goods g on gpl.goods_id = g.id')->where($condition)->order($orderStr)->limit($limitCount)->select();
        }else{
            $result = $goodsPriceListModule->alias('gpl')->field('g.*,gpl.*')->join('cs_goods g on gpl.goods_id = g.id')->where($condition)->order($orderStr)->select();
        }
        return $result;
    }

    public function getRelatedGoodsList($limitCount = 10 ,$condition = array(), $order = 'id DESC'){
        $goodsPriceListModule = M('goods_price_list');
        if($limitCount > 0) {
            $goodsList = $goodsPriceListModule->alias('gpl')->field('g.*,gpl.*')->join('cs_goods g on gpl.goods_id = g.id')->where($condition)->order($order)->limit($limitCount)->select();
        }else{
            $goodsList = $goodsPriceListModule->alias('gpl')->field('g.*,gpl.*')->join('cs_goods g on gpl.goods_id = g.id')->where($condition)->order($order)->select();
        }
        return $goodsList;
    }

    /**获取商品类别列表
     * @param $condition array 条件
     * @return array 返回树状的类别列表
     */
    public function getGoodsCategoryTreeListAction($condition = array()){
        $categoryModule = M('goods_category');
        if(!$condition) {
            $list = treeAction($categoryModule->order('parent_id ASC , sort_num ASC , id DESC')->select(), 0, 1);
        }else{
            $list = treeAction($categoryModule->where($condition)->order('parent_id ASC , sort_num ASC , id DESC')->select(), 0, 1);
        }
        return $list;
    }



    public function getCateGoodsIdArray(){
        $categoryModule = M('goods_category');
        $goodsModule = M('goods');
        $categoryList =  $categoryModule->where(array('parent_id' => 0))->order('parent_id ASC , sort_num ASC , id DESC')->getField('id,title',true);
        $categoryArray = array();
        foreach ($categoryList as $key1 => $val1){
            $categoryArray[$key1]['title'] = $val1;
            $cateTempList = $categoryModule->where(array('parent_id' => $key1))->order('parent_id ASC , sort_num ASC , id DESC')->getField('id,title',true);
            foreach ($cateTempList as $key2 => $val2){
                $categoryArray[$key1]['children'][$key2]['children'] = $goodsModule->field('id,goods_title')->where(array('category_id' => $key2))->order('sort_num ASC , id DESC')->select();
                $categoryArray[$key1]['children'][$key2]['title'] = $val2;
            }
        }
        return $categoryArray;
    }

    /**获得带页码的商品类型列表
     * @param $pageNum       int      页码
     * @param $condition     array    查询条件
     * @param $pageCondition array    分页条件
     * @param $order         string   排序
     * @return mixed         array    包括分页和查询结果数组
     */
    public function getGoodsTypePageList($pageNum = 1,$order = 'id DESC',  $condition = array() , $pageCondition = array()){
        import('ORG.Util.Page');
        $goodsTypeModule = M('goods_type');
        $count = $goodsTypeModule->where($condition)->count();
        //$totalRows 设置总记录数, $listRows=20 设置每页显示行数, $parameter  分页跳转时要带的参数
        $page = new Page($count,C('PAGE_SIZE'),$pageCondition);
        $result['show'] = $page->show();
        $result['list'] = $goodsTypeModule->where($condition)->order($order)->page($pageNum,C('PAGE_SIZE'))->select();
        return $result;
    }

    /**获得带页码的商品价格列表
     * @param $pageNum       int      页码
     * @param $condition     array    查询条件
     * @param $pageCondition array    分页条件
     * @param $order         string   排序
     * @return mixed         array    包括分页和查询结果数组
     */
    public function getGoodsPricePageList($pageNum = 1,$order = 'id DESC',  $condition = array() , $pageCondition = array()){
        import('ORG.Util.Page');
        $goodsPriceListModule = M('goods_price_list');
        $count = $goodsPriceListModule->alias('gp')->where($condition)->count();
        $page = new Page($count,C('PAGE_SIZE'));
        //带入查询条件的页码
        foreach ($pageCondition as $key => $val) {
            $page->parameter .= "$key=" . urlencode($val) . '&';
        }
        $result['show'] = $page->show();
        $result['list'] = $goodsPriceListModule->alias('gp')->field('gp.*,r.name')->join('cs_region r on r.id = gp.city_id')->where($condition)->order($order)->page($pageNum,C('PAGE_SIZE'))->select();
        return $result;
    }

    /**得带页码的商品列表
     * @param $pageNum            int              页码
     * @param $order              string           排序
     * @param $condition          array            查询条件
     * @param $pageCondition      array            分页条件
     * @return mixed              array            包括分页信息和查询结果数组
     */
    public function getGoodsPageList($pageNum = 1,$order = 'id DESC',  $condition = array() , $pageCondition = array()){
        $goodsModule = D('Admin/Goods');
        $count = $goodsModule->where($condition)->count();
        $page = new \Think\Page($count,C('PAGE_SIZE'),$pageCondition);
        //带入查询条件的页码
        foreach ($pageCondition as $key => $val) {
            $page->parameter .= "$key=" . urlencode($val) . '&';
        }
        $result['show'] = $page->show();
        $result['list'] = $goodsModule->relation('category_title')->where($condition)->order($order)->page($pageNum,C('PAGE_SIZE'))->select();
        return $result;
    }

    /**得带页码的商品列表
     * @param $pageNum            int              页码
     * @param $order              string           排序
     * @param $condition          array            查询条件
     * @param $pageCondition      array            分页条件
     * @return mixed              array            包括分页信息和查询结果数组
     */
    public function getFrontGoodsPageList($pageNum = 1,$order = 'id DESC',  $condition = array() , $pageCondition = array()){
        import('ORG.Util.Page');
        $goodsPriceListModule = M('goods_price_list');
        $condition['g.status'] = 1;
        $count = $goodsPriceListModule->alias('gpl')->join('cs_goods g on gpl.goods_id = g.id')->where($condition)->count();
        $page = new Page($count,C('PAGE_SIZE'));
        //带入查询条件的页码
        foreach ($pageCondition as $key => $val) {
            $page->parameter .= "$key=" . urlencode($val) . '&';
        }
        $result['show'] = $page->show();
        $result['list'] = $goodsPriceListModule->alias('gpl')->field('g.*,gpl.*,count(gm.comment_id) as comment_num')->join('cs_goods_comment gm on gm.goods_id = gpl.goods_id')->join('cs_goods g on gpl.goods_id = g.id')->group('gpl.goods_id')->where($condition)->order($order)->page($pageNum,C('PAGE_SIZE'))->select();
        return $result;
    }

    /**得到前台一个商品的信息
     * @param array $condition 条件
     * @return mixed|null 返回数组
     */
    public function getOneFrontGoods($condition = array()){
        $goodsPriceListModule = M('goods_price_list');
        $info = $goodsPriceListModule->alias('gpl')->field('g.*,gpl.*')->join('cs_goods g on gpl.goods_id = g.id')->where($condition)->find();
        return $info;
    }

    /**得带页码的商品搜索列表
     * @param $catid              int              类别id
     * @param $pageNum            int              页码
     * @param $order              string           排序
     * @param $map                array            查询条件
     * @param $pageCondition      array            分页条件
     * @return mixed              array            包括分页信息和查询结果数组
     */
    public function getSearchGoodsPageList($catid, $pageNum = 1,$order = 'id DESC',  $map = array() , $pageCondition = array()){
        import('ORG.Util.Page');
        $goodsPriceListModule = M('goods_price_list');
        if(empty($map)){
            $condition['g.id'] = array('gt',0);
        }else{
            $condition['_complex'] = $map;
        }
        if($catid>0){
            $condition['g.category_id'] = $catid;
        }
        $condition['g.status'] = 1;
        $count = $goodsPriceListModule->alias('gpl')->join('cs_goods g on gpl.goods_id = g.id')->where($condition)->count();
        $page = new Page($count,C('PAGE_SIZE'));
        //带入查询条件的页码
        foreach ($pageCondition as $key => $val) {
            $page->parameter .= "$key=" . urlencode($val) . '&';
        }
        $result['show'] = $page->show();
        $result['list'] = $goodsPriceListModule->alias('gpl')->field('g.*,gpl.*,count(gm.comment_id) as comment_num')->join('cs_goods_comment gm on gm.goods_id = gpl.goods_id')->join('cs_goods g on gpl.goods_id = g.id')->group('gpl.goods_id')->where($condition)->order($order)->page($pageNum,C('PAGE_SIZE'))->select();
        return $result;
    }


    /**商品类型
     * @param $categoryId int 类别id
     * @return mixed
     */
    public function getGoodsCategoryIdList($categoryId){
        $category = M('goods_category');
        $ids = $category->where(array('class_list' => array('like','%,'.$categoryId.',%')))->getField('id',true);
        return $ids;
    }

    /*获得对应ID的详细信息
 	 * parma              int         $id              查询的id
 	 * parma              int         $site_id         所在城市的id信息
 	 * return             array       返回数组
 	 **/
    public function getCategoryInfo($id=0)
    {
        $category = M('goods_category');
        $result = array();
        $map['id'] = $id;
        $result = $category->where($map)->find();
        return $result;
    }

    /*获取当前分类的父节点*/
    public function getCategoryClassList($id=0){
        $categoryList = array();
        $class_list = "";
        if($id>0){
            $category = M('goods_category');
            $map['id'] = $id;
            $class_list = $category->where($map)->getField('class_list');
            //获取class_list之后去除左右“,”逗号
            $class_list = substr($class_list,1,strlen($class_list)-2);
        }
        if($class_list!=""){
            $ids = explode(',',$class_list);
            $categoryList = $this->getCategoryIdInInfo($ids);
        }
        return  $categoryList;
    }

    /*获得指定的一组ID的分类信息
      * parma              string         $ids              查询的id
      * return             array          返回数组
      **/
    public function getCategoryIdInInfo($ids,$orderStr="parent_id ASC")
    {
        $result = array();
        if($ids==""){
            return $result;
        }
        $category = M('goods_category');
        $result = array();
        $map['id'] = array('in',$ids);
        $result = $category->where($map)->select();
        return $result;
    }

    /*获取商品关联的商品
    * Param                  int          $id                    商品ID
     * Return                array        返回数组
    */
    public function getGoodsLinkedList($id){
        $GoodsLinked = M('goods_linked');
        $result = array();
        if($id>0){
            $result = $GoodsLinked->field('linked_goods_id,linked_goods_name')->where(array('goods_id'=>$id))->order('linked_goods_id')->select();
        }
        return $result;
    }
    /*获取分类及子类，并获取子类下对应的所有商品
    * Param         int             $topCount                  获取商品的数量
    * Param         int             $category_id               分类ID
    */
    public function getChildCategoryAndGoodsList($topCount,$category_id,$condition=array(),$order){
        $result = array();
        if($category_id){
            $categoryModule = M('goods_category');
            $goodsPriceListModule = M('goods_price_list');
            //首先获取当前分类对应的子类
            $catCondition['parent_id'] = $category_id;
            $catCondition['is_hidden'] = 0;
            $result = $categoryModule->where($catCondition)->order('sort_num')->select();
            foreach($result as $key=>$cate){
                //遍历分类获取当前分类对应的商品
                $condition['g.status'] = 1;
                $condition['g.category_id'] = $cate['id'];
                if($order == ""){
                    $order = "sort_num asc, id desc";
                }
                if($topCount>0){
                    $goodList = $goodsPriceListModule->alias('gpl')->field('g.*,gpl.*')->join('cs_goods g on gpl.goods_id = g.id')->group('gpl.goods_id')->where($condition)->order($order)->select();
                }else{
                    $goodList = $goodsPriceListModule->alias('gpl')->field('g.*,gpl.*')->join('cs_goods g on gpl.goods_id = g.id')->group('gpl.goods_id')->where($condition)->limit($topCount)->order($order)->select();
                }
                if($goodList){
                    $result[$key]['goods_list'] = $goodList;
                }
            }

        }
        return $result;
    }
    /**获取企业类型列表
     * @param $condition array 条件
     * @return array 返回树状的类别列表
     */
    public function getIndustrysTypeTreeListAction($condition = array()){
        $industrysTypeModule = M('industrysType');
        if(!$condition) {
            $list = treeAction($industrysTypeModule->order('parent_id ASC , sort_num ASC , id DESC')->select(), 0, 1);
        }else{
            $list = treeAction($industrysTypeModule->where($condition)->order('parent_id ASC , sort_num ASC , id DESC')->select(), 0, 1);
        }
        return $list;
    }

}
