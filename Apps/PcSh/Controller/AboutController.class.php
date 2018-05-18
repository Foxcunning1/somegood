<?php
/*关于我们
 *
 * */
namespace Mobile\Controller;
use Think\Controller;
class AboutController extends BaseController {
    /*平台功能介绍*/
    public function funProfile(){
        $this->assign("web_title","3好连锁平台功能介绍");
        $this->display();
    }

}