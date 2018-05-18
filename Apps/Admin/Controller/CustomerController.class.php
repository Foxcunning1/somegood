<?php
namespace Admin\Controller;
use Think\Controller;
class CustomerController extends AjaxController{
    //客户信息列表
    public function indexAction(){
        if(!parent::checkAdminRoleAction('customer_data_list','view')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $pageNum=I('p',1);
        $customerModel=D('Common/CustomerData');
        $result=$customerModel->getCustomerPageList($pageNum ,15, $order = 'add_time DESC,id desc');
        $this->assign('list',$result['list']);
        $this->assign('page',$result['show']);
        $this->display();
    }

    //新增
    public function addAction(){
        if(!parent::checkAdminRoleAction('customer_data_list','add')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $data=I('data');
        if($data['company_name']==''){
            $this->ajaxInfoReturn('','公司名不得为空',0);
        }elseif($data['products']==''){
            $this->ajaxInfoReturn('','主营产品',0);
        }elseif($data['address']==''){
            $this->ajaxInfoReturn('','地址不得为空',0);
        }/*elseif($data['consignee']==''){
            $this->ajaxInfoReturn('','联系人不得为空',0);
        }*/elseif($data['mobile']==''){
            $this->ajaxInfoReturn('','电话不得为空',0);
        }else{
            $customerModel=M('CustomerData');
            //验证重复
            $count=$customerModel->where(array('company_name'=>$data['company_name']))->count();
            if($count>0){
                $this->ajaxInfoReturn('','已存在',0);
            }else{
                $data['add_time']=time();
                $data['add_admin']=session('admin.admin_name');
                $re=$customerModel->add($data);
                if($re){
                    $this->ajaxInfoReturn('','添加成功',1);
                }else{
                    $this->ajaxInfoReturn('','添加失败',0);
                }
            }
        }
    }

    //删除
    public function delAction(){
        $ids = I('ids');
        if(!parent::checkAdminRoleAction('customer_data_list','delete')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        $returnUrl = $_SERVER['HTTP_REFERER'];//获取上个操作页面
        $customerModel  = M('CustomerData');
        //判断id是数组还是一个数值
        if(is_array($ids)){
            $condition['id'] = array('in',$ids);
            $re=$customerModel->where($condition)->delete();
            $ids_str=implode(',',$ids);
        }else{
            $re = $customerModel->where(array('id'=>$ids))->delete();
            $ids_str=$ids;
        }
        if($re) {

            add_admin_log('del',$_SESSION['admin']['admin_name'].'成功删除了'.count($ids).'条客户信息!ID为'.$ids_str);
            jscript_msg("删除成功 ",$returnUrl);
        }else{
            jscript_msg_tips("删除失败！");
        }
    }

    //EXCEL导入
    public function excelAddAction(){
        if(!parent::checkAdminRoleAction('customer_data_list','add')){
            jscript_msg_tips('亲，您无权进行操作！');
        }
        if(!empty($_FILES['excel_updata']['name'])){
            $file_types=explode(".",$_FILES['excel_updata']['name']);
            $file_type=$file_types[count($file_types)-1];
            /*判别是不是excel文件*/
            $extsArr = explode(',', C('FILE_EXTENSION'));
            /*设置上传路径*/
            $savePath='Excel/';
            /*以时间来命名上传的文件*/
            $str=date('His');
            $file_name=$str.".".$file_type;
            /*是否上传成功*/
            $uploadConfig = array(
                'rootPath'   =>    "./Public/",
                'savePath'   =>    $savePath,
                'saveName'   =>    $str,
                'exts'       =>    $extsArr,
                'subName'    =>    array('date','Ymd'),
            );
            $upload = new \Think\Upload($uploadConfig);
            $info   =   $upload->upload();
            if(!$info) {// 上传错误提示错误信息
                $this->ajaxInfoReturn('',$upload->getError(),0);
            }else{// 上传成功 获取上传文件信息
                vendor("PHPExcel.PHPExcel");
                vendor("PHPExcel.PHPExcel.IOFactory");
                if ($file_type =='xlsx') {
                    $objReader = new \PHPExcel_Reader_Excel2007();
                    $objExcel = $objReader ->load("./Public/".$info['excel_updata']['savepath'].$file_name,$encode='utf-8');
                } else if ($file_type =='xls') {
                    $objReader = new \PHPExcel_Reader_Excel5();
                    $objExcel = $objReader ->load("./Public/".$info['excel_updata']['savepath'].$file_name,$encode='utf-8');
                } else if ($file_type=='csv') {
                    $PHPReader = new \PHPExcel_Reader_CSV();
                    //默认输入字符集
                    $PHPReader->setInputEncoding('GBK');
                    //默认的分隔符
                    $PHPReader->setDelimiter(',');
                    //载入文件
                    $objExcel = $PHPReader->load("./Public/".$info['excel_updata']['savepath'].$file_name,$encode='utf-8');
                }
                $sheet = $objExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow(); // 取得总行数
                for($i=1;$i<$highestRow+1;$i++){
                    $data[$i-1]['company_name'] = $objExcel->getActiveSheet()->getCell('A'.$i)->getValue();
                    $data[$i-1]['products'] = $objExcel->getActiveSheet()->getCell('B'.$i)->getValue();
                    $data[$i-1]['address'] = $objExcel->getActiveSheet()->getCell('C'.$i)->getValue();
                    $data[$i-1]['consignee'] = $objExcel->getActiveSheet()->getCell('D'.$i)->getValue();
                    $data[$i-1]['mobile'] = $objExcel->getActiveSheet()->getCell('E'.$i)->getValue();
                    $data[$i-1]['delivery_type'] = $objExcel->getActiveSheet()->getCell('F'.$i)->getValue();
                }
                // 去掉第exl表格中第一行
                unset($data[0]);
                // 清理空数组
                foreach($data as $k=>$v){
                    if(empty($v['company_name'])){
                        unset($data[$k]);
                    }
                };
                // 重新排序
                sort($data);
                $count = count($data);

                // 检测表格导入成功后，是否有数据生成
                if($count<1){
                    $this->ajaxInfoReturn('',未检测到有效数据,0);
                }

                //写入
                $customerDataModel=M('CustomerData');
                $s=0;//成功条数
                $f=0;//重复条数
                $str="";//重复的公司名称
                foreach ($data as $k=>$v){
                    //检测重复
                    $count=$customerDataModel->where(array('company_name'=>$v['company_name']))->count();
                    if ($count>0){
                        $str .= '<br/>'.$v['company_name'];
                        unset($data[$k]);
                        $f++;
                    }else{
                        $v['add_admin']=session('admin.admin_name');
                        $v['add_time']=time();
                        $re=$customerDataModel->add($v);
                        if ($re){
                            $s++;
                        }
                    }
                }
                // 删除Excel文件
                unlink("./Public/".$info['excel_updata']['savepath'].$file_name);
                $this->ajaxInfoReturn('','成功导入'.$s.'条客户信息,'.$f.'条信息重复'.$str,1);
            }
        }else{
            $this->ajaxInfoReturn('','不是Excel文件，重新上传',0);
        }
    }
}