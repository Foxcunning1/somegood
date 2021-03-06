<?php
/*后台默认页类
 *
 * */
namespace Mobile\Controller;
use Think\Controller;
class AjaxController extends MemberController {

    /*城市四级联动通用方法*/
    public function getRegionListAction(){
        $pid = I('pid',0);
        $level = I('type',0);
        $regionModel = D("Region");
        $list = $regionModel->getRegionList($pid,$level);
        echo json_encode($list);
    }
    
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

}