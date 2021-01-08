<?php 
	
	require_once '../DAL/adminDAL.php';//引用管理员DAL
    require_once '../jsonHelper.php';//引用中文字符转义
	
	//向DAL层传参
	$adminDAL = new adminDAL();
	$result = $adminDAL->resetAllScore();

	//向微信小程序返回参数
	echo toJson($result);
	
?>