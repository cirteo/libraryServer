<?php

require_once '../DAL/seatDAL.php';
require_once '../jsonHelper.php';
//引用中文字符转义

//接收数据
$body = file_get_contents( 'php://input' );
$json = json_decode( $body );
$stuNum = $json-> {
    'stuNum'}
    ;

    $seatID = $json-> {
        'seatID'}
        ;

        //向DAL层传参
        $seatDAL = new seatDAL();

        //插入历史记录
        $seatDAL->addSignOut( $stuNum, $seatID );
        $result = array(
            'state' => '0',
            'msg' => '签退成功'
        );
        $seatDAL->releaseSeat( $seatID );
        echo toJson( $result );

        ?>