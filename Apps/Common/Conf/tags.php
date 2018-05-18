<?php
return array(
    'app_begin' => array('Behavior\CronRunBehavior'),//计划任务
	'view_filter' => array('Behavior\TokenBuildBehavior'),//token表单防重复验证
);
?>
