<?php
//邮件模板模型
namespace Common\Model;
use Think\Model;
class MailTemplateModel extends Model{
	/*获取对应模板信息并替换指定标签内容
	 * Param        string            call_index                邮件调用别名
	 * */
	public function getSendMailTemplate($call_index="reg"){
		$mailTemp = M('mail_template');
		$map['call_index'] = $call_index;
		$info = $mailTemp->where($map)->find();
		return $info;
	}
	/*后台获取所有邮件模板*/
    public function getMailTplPageList($pageNum , $condition , $pageCondition){
        import('ORG.Util.Page');
        $mailTemplateList = M('mail_template');
        $count = $mailTemplateList->where($condition)->count();
        $page = new Page($count,C('PAGE_SIZE'));
        foreach($pageCondition as $key=>$val) {
            $page->parameter   .=   "$key=".urlencode($val).'&';
        }
        $result['show'] = $page->show();
        $result['list'] = $mailTemplateList->where($condition)->order('id DESC')->page($pageNum,C('PAGE_SIZE'))->select();
        return $result;
    }
}