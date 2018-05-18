<?php
//求购模型
namespace Common\Model;
use Think\Model;
use Think\Page;

class FavoritesModel extends Model{

/**得带页码的收藏信息列表
 * @param $pageNum            int              页码
 * @param $order              string           排序
 * @param $condition          array            查询条件
 * @param $pageCondition      array            分页条件
 * @return mixed              array            包括分页信息和查询结果数组
 */
public function getFavoritesPageList($pageNum = 1,$order = 'addtime DESC',  $condition = array() , $pageCondition = array()){
    import('ORG.Util.Page');
    $favoritesModule = D('Favorites');
    $count = $favoritesModule->where($condition)->count();
    $result['count']=$count;
    $page = new Page($count,C('WANT_PAGE_SIZE'),$pageCondition);
    $result['pageCount']=$page->pageCount();
    $result['show'] = $page->show();
    //获取收藏商品信息
    $result['list'] = $favoritesModule->where($condition)->order($order)->page($pageNum,C('FAVORITES_PAGE_SIZE'))->select();
    return $result;
}
}
