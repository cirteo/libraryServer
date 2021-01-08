<?php 
	
	require_once '../DAL/seatDAL.php';//引用管理员DAL
	require_once '../jsonHelper.php';//引用中文字符转义
	
	//接收数据
	$body = file_get_contents( 'php://input' );
	$json = json_decode( $body );
    $seatID = $json-> { 'seatID'};
    $seatState = $json-> {'seatState'};
	
	//向DAL层传参
	$seatDAL = new seatDAL();
	$result = $seatDAL->editSeat($seatID,$seatState);

	//向微信小程序返回参数
	echo toJson($result);
	
?>