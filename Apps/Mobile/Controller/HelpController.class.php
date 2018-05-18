<?php
/*帮助
 *
 * */
namespace Mobile\Controller;
use Think\Controller;
class HelpController extends Controller {
    /**帮助中心
    */
    public function centerAction(){
        $pageNum = I('p',0);
        $catId = I('cat_id',2);
        if($catId > 0){
            $columnModel = M("column");
            $map['class_list'] = array("like","%,".$catId.",%");
            $ids = $columnModel->where($map)->getField("id");
            $condition['cid'] = array("in",$ids);
            $pageCondition['cat_id'] = $catId;
        }
        $condition['aid'] = array("gt",0);
        $articleModel = D("Admin/Article");
        $orderStr = "update_time desc,aid desc";
        $result = $articleModel->getArticlePageList($pageNum,15,$orderStr,$condition,$pageCondition);
        if(IS_AJAX){
            $this->ajaxInfoReturn($result,"获取成功！",1);
        }else{
            $categorylist = $articleModel->getTopColumnList(0,1);
            $this->assign("web_title", "帮助中心");
            $this->assign("catId",$catId);
            $this->assign("categorylist", $categorylist);
            $this->assign("pageCount",$result['pageCount']);
            $this->assign("list",$result['list']);
            $this->assign("empty",C("NOT_DATA"));
            $this->display();
        }
    }

    /**文章详情
     * @Param       int     id        文章id
     */
    public function detailAction(){
        $id = I("get.id",0);
        if($id<1){
            $this->error('非法操作', U('Mobile/Help/center'));
        }else{
            $articleModel = D("Admin/Article");
            $info = $articleModel->getArticleInfo($id);
            $this->assign("info",$info);
            $this->assign("web_title",$info['c_name']."-帮助中心");
            $this->display();
        }
    }
}