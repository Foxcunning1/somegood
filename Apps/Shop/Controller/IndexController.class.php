<?php
namespace Shop\Controller;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Think\Controller;
//商城用户登录类
class IndexController extends Controller {

	
	public function indexAction(){
		$this->display();
	}

	public function testAction(){
	    echo "<img src=\"".__ROOT__."Public/uploads/idcard.jpg\" alt=\"logo\" class=\"logo\"/>";
        var_dump(aip_ocr('Public/uploads/idcard.jpg'));
    }

    public function test1Action(){
        echo "<img src=\"__PUBLIC__/uploads/test/1.jpg\" alt=\"logo\" class=\"logo\"/>";
        var_dump(aip_ocr('Public/uploads/test/1.jpg'));
    }

    public function test2Action(){
        echo "<img src=\"__PUBLIC__/uploads/test/2.jpg\" alt=\"logo\" class=\"logo\"/>";
        var_dump(aip_ocr('Public/uploads/test/2.jpg'));
    }

    public function test3Action(){
        echo "<img src=\"__PUBLIC__/uploads/test/3.jpg\" alt=\"logo\" class=\"logo\"/>";
        var_dump(aip_ocr('Public/uploads/test/3.jpg'));
    }

    public function test4Action(){
        echo "<img src=\"__PUBLIC__/uploads/test/4.jpg\" alt=\"logo\" class=\"logo\"/>";
        var_dump(aip_ocr('Public/uploads/test/4.jpg'));
    }

    public function test5Action(){
        echo "<img src=\"__PUBLIC__/uploads/test/5.jpg\" alt=\"logo\" class=\"logo\"/>";
        var_dump(aip_ocr('Public/uploads/test/5.jpg'));
    }

}
