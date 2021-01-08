<?php
    
    require_once '../DAL/seatDAL.php';
    require_once '../jsonHelper.php';
    
    //接收数据
    $body = file_get_contents('php://input');
    $json = json_decode($body );
    $seatID = $json->{'seatID'};
    
    $seatDAL = new seatDAL();
    $result = $seatDAL->seatList($seatID);
    

    echo json_encode($result);
?>