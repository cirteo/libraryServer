<?php 
	
	require_once '../DAL/seatDAL.php';//引用管理员DAL
	require_once '../jsonHelper.php';//引用中文字符转义

	date_default_timezone_set('Asia/Shanghai');
	
	//接收数据
	$body = file_get_contents( 'php://input' );
	$json = json_decode( $body );
	$seatID = $json-> { 'seatID'};
	$startTime = strtotime($json-> { 'startTime'});
	$endTime = strtotime($json-> { 'endTime'});

	//向DAL层传参
	$seatDAL = new seatDAL();

	$cnMysqli = mysqli_connect("localhost:3306","root","root","library");
    $sqlList = "insert into queue_manage(startTime,endTime,seatID) values($startTime,$endTime,'$seatID')";
	mysqli_query($cnMysqli,$sqlList);
	
	//$result = $seatDAL->closeLibrary($seatID);

	//向微信小程序返回参数
	//echo toJson($result);
	
?>