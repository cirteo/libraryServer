<?php

require_once '../DAL/seatDAL.php';
require_once '../seatHelper.php';
require_once '../jsonHelper.php';
//引用中文字符转义

//接收数据
$body = file_get_contents( 'php://input' );
$json = json_decode( $body );

$seatHelper = new seatHelper();
$seatDAL = new seatDAL();
$seatID = $seatDAL->reserveSeatRand();

$arr = array(
    'state' => '0',
    'seatLoc' => $seatHelper->querySeatLoc($seatID),
    'seatID' => $seatID,
    'msg' => '随机选座结果'
);

echo json_encode($arr);


?>