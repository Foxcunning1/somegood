<?php
namespace Crm\Controller;
use Think\Controller;
/**公司员工内部访问基类
 *
 */
class EmployeeController extends BaseController
{

     public function __construct()
     {
         parent::__construct();
         $this->checkUsersGroupAction();//检测用户会员组

     }
    //验证用户会员组是否为内部员工
    public function checkUsersGroupAction()
    {
        if (session("mobile.gid")!=C('EMPLOYEE_GID')) {//如果用户会员组不为3
            $this->error("非法用户组!",U("Mobile/Index/index"));
        }
    }
}
?>
