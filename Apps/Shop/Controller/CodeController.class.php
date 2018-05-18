<?php
namespace Shop\Controller;
use Think\Controller;
//验证码类
class CodeController extends Controller {
	//生成验证码
	public function verifyAction(){
		$Verify =     new \Think\Verify();
		$Verify->fontttf = 'ariali.ttf';
		$Verify->imageW = 120;
		$Verify->imageH = 36;
		$Verify->fontSize = 18;
		$Verify->length   = 4;
		$Verify->useNoise = false;
		$Verify->useCurve = true;
		$Verify->entry();
	}
}
