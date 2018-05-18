<?php

namespace Mobile\Controller;
use Think\Controller;
/**
 *  测试Controller
 */
class OrderController extends Controller{

	public function testAction(){
		//$test = $this->assign('title','web_title');
		//对Controller类的研究

		$a=Hook::listen('action_begin',$this->config);
		var_dump($a);

	}





	// object(Mobile\Controller\OrderController) {
	//   ["view":protected]=>
	// 			  object(Think\View)  {
	// 			    ["tVar":protected]=>
	// 			    array(1) {
	// 			      ["title"]=>
	// 			      string(9) "web_title"
	// 			    }
	// 			    ["theme":protected]=>
	// 			    string(0) ""
	// 			  }
	//
	//   ["config":protected]=>
	//   array(0) {
	//   }
	// }

}
