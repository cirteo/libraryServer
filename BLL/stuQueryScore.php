<?php 
	
	require_once '../DAL/stuDAL.php';
	
    //接收数据
    $body = file_get_contents('php://input');
    $json = json_decode($body );
	$stuNum = $json->{'stuNum'};
	
	//向DAL层传参
	$stuDAL = new stuDAL();
	$arr = $stuDAL->queryHistory($stuNum);

	//向微信小程序返回参数
	echo json_encode($arr);
	
?>